<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Estanteria;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MovimientosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movimientos = Movimiento::with([
            'usuario',
            'producto' => function ($query) {
                $query->withTrashed();
            },
            'origen',
            'destino'
        ])
            ->orderBy('fecha_movimiento', 'desc')
            ->paginate(10);

        return view('movimientos.index', compact('movimientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $tipo = $request->tipo ?? 'entrada';
        if (!in_array($tipo, ['entrada', 'traslado', 'salida'])) {
            return redirect()->route('movimientos.index')
                ->with('error', 'Tipo de movimiento no válido');
        }

        $productos = Producto::all();
        $usuarios = User::all();

        // Obtener las estanterías con su información
        $estanteriasBase = Estanteria::with(['zona', 'productos'])
            ->get()
            ->map(function ($estanteria) use ($request) {
                $data = [
                    'id' => $estanteria->id,
                    'nombre' => "Estantería {$estanteria->nombre}",
                    'zona' => $estanteria->zona ? "- Zona {$estanteria->zona->nombre}" : '',
                    'capacidad_libre' => $estanteria->capacidad_libre,
                    'capacidad_maxima' => $estanteria->capacidad_maxima
                ];

                // Añadir información de stock por producto si hay uno seleccionado
                if ($request->producto_id) {
                    $stock = $estanteria->productos()
                        ->where('producto_id', $request->producto_id)
                        ->first();
                    $data['stock_producto'] = $stock ? $stock->pivot->cantidad : 0;
                }

                return $data;
            });

        $viewData = [
            'productos' => $productos,
            'usuarios' => $usuarios,
            'tipo' => $tipo
        ];

        if ($tipo === 'entrada') {
            $viewData['estanterias'] = $estanteriasBase->filter(function ($estanteria) {
                return $estanteria['capacidad_libre'] > 0;
            })->values();
        } elseif ($tipo === 'traslado') {
            // Para traslados:
            // - Origen: solo estanterías que tienen stock del producto
            // - Destino: estanterías con capacidad libre EXCEPTO las que ya tienen el producto
            $viewData['estanteriasOrigen'] = $estanteriasBase
                ->filter(function ($estanteria) {
                    return isset($estanteria['stock_producto']) && $estanteria['stock_producto'] > 0;
                })
                ->values();

            // Obtener IDs de estanterías que tienen el producto
            $estanteriasConProducto = $viewData['estanteriasOrigen']->pluck('id')->toArray();

            $viewData['estanteriasDestino'] = $estanteriasBase
                ->filter(function ($estanteria) use ($estanteriasConProducto) {
                    return $estanteria['capacidad_libre'] > 0 &&
                        !in_array($estanteria['id'], $estanteriasConProducto);
                })
                ->values();
        } else { // salida
            $viewData['estanterias'] = $estanteriasBase;
        }

        return view('movimientos.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validaciones básicas
            $validated = $request->validate([
                'producto_id' => 'required|exists:productos,id',
                'cantidad' => 'required|integer|min:1',
                'tipo' => 'required|in:entrada,salida,traslado',
            ]);

            $producto = Producto::findOrFail($request->producto_id);

            // Manejar cada tipo de movimiento
            switch ($request->tipo) {
                case 'entrada':
                    // Validar ubicaciones
                    $request->validate([
                        'ubicaciones' => 'required|array',
                        'ubicaciones.*' => 'required|integer|min:1'
                    ]);

                    $totalAsignado = array_sum($request->ubicaciones);
                    if ($totalAsignado !== (int)$request->cantidad) {
                        throw new \Exception('La cantidad total asignada no coincide con la cantidad del movimiento');
                    }

                    foreach ($request->ubicaciones as $estanteriaId => $cantidad) {
                        Movimiento::create([
                            'producto_id' => $request->producto_id,
                            'cantidad' => $cantidad,
                            'tipo' => 'entrada',
                            'origen_tipo' => 'proveedor',
                            'destino_tipo' => 'estanteria',
                            'ubicacion_destino_id' => $estanteriaId,
                            'fecha_movimiento' => now(),
                            'user_id' => Auth::id(),
                            'estado' => 'confirmado'
                        ]);

                        $estanteria = Estanteria::findOrFail($estanteriaId);

                        // Comprobar si ya existe el producto en la estantería
                        $existingPivot = $estanteria->productos()
                            ->where('producto_id', $request->producto_id)
                            ->first();

                        if ($existingPivot) {
                            // Actualizar la cantidad existente
                            $estanteria->productos()->updateExistingPivot($request->producto_id, [
                                'cantidad' => DB::raw("cantidad + $cantidad"),
                                'updated_at' => now()
                            ]);
                        } else {
                            // Crear nueva relación
                            $estanteria->productos()->attach($request->producto_id, [
                                'cantidad' => $cantidad,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                    break;

                case 'salida':
                    // Verificar stock total disponible
                    if ($producto->stock_total < $request->cantidad) {
                        throw new \Exception('No hay suficiente stock disponible.');
                    }

                    // Obtener ubicaciones con stock ordenadas por fecha de entrada (FIFO)
                    $ubicaciones = DB::table('producto_estanteria')
                        ->where('producto_id', $request->producto_id)
                        ->where('cantidad', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $cantidadPendiente = $request->cantidad;

                    foreach ($ubicaciones as $ubicacion) {
                        $cantidadASacar = min($cantidadPendiente, $ubicacion->cantidad);

                        // Crear movimiento de salida
                        Movimiento::create([
                            'producto_id' => $request->producto_id,
                            'cantidad' => $cantidadASacar,
                            'tipo' => 'salida',
                            'origen_tipo' => 'estanteria',
                            'ubicacion_origen_id' => $ubicacion->estanteria_id,
                            'destino_tipo' => 'cliente',
                            'fecha_movimiento' => now(),
                            'user_id' => Auth::user()->id,
                            'estado' => 'confirmado'
                        ]);

                        // Actualizar stock en ubicación
                        DB::table('producto_estanteria')
                            ->where('estanteria_id', $ubicacion->estanteria_id)
                            ->where('producto_id', $request->producto_id)
                            ->update([
                                'cantidad' => DB::raw("cantidad - $cantidadASacar"),
                                'updated_at' => now()
                            ]);

                        $cantidadPendiente -= $cantidadASacar;
                        if ($cantidadPendiente <= 0) break;
                    }

                    if ($cantidadPendiente > 0) {
                        throw new \Exception('Error al procesar la salida del stock.');
                    }

                    break;

                case 'traslado':
                    // Validaciones para traslado
                    $request->validate([
                        'ubicacion_origen_id' => 'required|exists:estanterias,id',
                        'ubicacion_destino_id' => 'required|different:ubicacion_origen_id|exists:estanterias,id'
                    ]);

                    $estanteriaOrigen = Estanteria::findOrFail($request->ubicacion_origen_id);
                    $estanteriaDestino = Estanteria::findOrFail($request->ubicacion_destino_id);

                    // Verificar stock en origen
                    $stockOrigen = $estanteriaOrigen->productos()
                        ->where('producto_id', $request->producto_id)
                        ->first()?->pivot->cantidad ?? 0;

                    if ($stockOrigen < $request->cantidad) {
                        throw new \Exception('No hay suficiente stock en la ubicación de origen.');
                    }

                    // Verificar capacidad en destino
                    if ($estanteriaDestino->capacidad_libre < $request->cantidad) {
                        throw new \Exception('La estantería de destino no tiene suficiente espacio.');
                    }

                    // Crear movimiento de traslado
                    $movimiento = Movimiento::create([
                        'producto_id' => $request->producto_id,
                        'cantidad' => $request->cantidad,
                        'tipo' => 'traslado',
                        'origen_tipo' => 'estanteria',
                        'ubicacion_origen_id' => $request->ubicacion_origen_id,
                        'destino_tipo' => 'estanteria',
                        'ubicacion_destino_id' => $request->ubicacion_destino_id,
                        'fecha_movimiento' => now(),
                        'user_id' => Auth::user()->id,
                        'estado' => 'confirmado'
                    ]);

                    // Actualizar stock en ambas ubicaciones
                    DB::table('producto_estanteria')
                        ->where('estanteria_id', $request->ubicacion_origen_id)
                        ->where('producto_id', $request->producto_id)
                        ->update([
                            'cantidad' => DB::raw("cantidad - {$request->cantidad}"),
                            'updated_at' => now()
                        ]);

                    // Actualizar o crear stock en destino
                    $existingDestino = DB::table('producto_estanteria')
                        ->where('estanteria_id', $request->ubicacion_destino_id)
                        ->where('producto_id', $request->producto_id)
                        ->first();

                    if ($existingDestino) {
                        // Actualizar cantidad existente
                        DB::table('producto_estanteria')
                            ->where('estanteria_id', $request->ubicacion_destino_id)
                            ->where('producto_id', $request->producto_id)
                            ->update([
                                'cantidad' => DB::raw("cantidad + {$request->cantidad}"),
                                'updated_at' => now()
                            ]);
                    } else {
                        // Crear nueva relación
                        $estanteriaDestino->productos()->attach($producto->id, [
                            'cantidad' => $request->cantidad,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                    break;
            }

            DB::commit();
            return redirect()->route('movimientos.index')
                ->with('success', 'Movimiento registrado correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Movimiento $movimiento)
    {
        // Cargar el producto incluso si está eliminado
        $movimiento->load(['producto' => function ($query) {
            $query->withTrashed();
        }]);

        return view('movimientos.show', compact('movimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimiento $movimiento)
    {
        $productos = Producto::all();
        $usuarios = User::all();

        // Obtener las estanterías con su información de stock y capacidad
        $estanterias = Estanteria::with(['productos' => function ($query) use ($movimiento) {
            $query->where('producto_id', $movimiento->producto_id)
                ->withPivot('cantidad');
        }])
            ->get()
            ->map(function ($estanteria) {
                return [
                    'id' => $estanteria->id,
                    'nombre' => $estanteria->nombre ?? 'Estantería ' . $estanteria->id,
                    'zona' => $estanteria->zona?->nombre,
                    'stock_producto' => $estanteria->productos->first()?->pivot->cantidad ?? 0,
                    'espacio_disponible' => $estanteria->capacidad_libre,
                    'capacidad_maxima' => $estanteria->capacidad_maxima
                ];
            });

        return view('movimientos.edit', compact('movimiento', 'productos', 'usuarios', 'estanterias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movimiento $movimiento)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'user_id' => 'required|exists:users,id',
            'tipo' => 'required|in:entrada,salida,traslado',
            'cantidad' => 'required|integer|min:1',
            'origen_tipo' => 'required|in:estanteria,proveedor',
            'ubicacion_origen_id' => 'required_if:origen_tipo,estanteria|nullable|exists:estanterias,id',
            'destino_tipo' => 'required|in:estanteria,cliente',
            'ubicacion_destino_id' => 'required_if:destino_tipo,estanteria|nullable|exists:estanterias,id',
            'estado' => 'required|in:pendiente,confirmado,cancelado',
            'fecha_movimiento' => 'required|date',
        ]);

        $movimiento->update($request->all());

        return redirect()->route('movimientos.index')
            ->with('success', 'Movimiento actualizado con éxito');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movimiento $movimiento)
    {
        $movimiento->delete();

        return redirect()->route('movimientos.index')->with('success', 'Movimiento eliminado con éxito');
    }

    public function getEstanterias(Request $request)
    {
        try {
            $producto_id = $request->producto_id;

            if (!$producto_id) {
                return response()->json([
                    'error' => 'Se requiere un producto_id'
                ], 400);
            }

            // Obtener estanterías que tienen el producto con cantidad > 0
            $estanteriasOrigen = DB::table('estanterias')
                ->join('producto_estanteria', 'estanterias.id', '=', 'producto_estanteria.estanteria_id')
                ->leftJoin('zonas', 'estanterias.zona_id', '=', 'zonas.id')
                ->where('producto_estanteria.producto_id', $producto_id)
                ->where('producto_estanteria.cantidad', '>', 0)
                ->select(
                    'estanterias.id',
                    'estanterias.nombre',
                    'zonas.nombre as zona_nombre',
                    'producto_estanteria.cantidad as stock_producto'
                )
                ->get()
                ->map(function ($estanteria) {
                    return [
                        'id' => $estanteria->id,
                        'nombre' => "Estantería {$estanteria->nombre}",
                        'zona' => $estanteria->zona_nombre ? "- {$estanteria->zona_nombre}" : '',
                        'stock_producto' => $estanteria->stock_producto
                    ];
                });

            // IDs de estanterías que ya tienen el producto
            $estanteriasConProducto = $estanteriasOrigen->pluck('id');

            // Obtener estanterías disponibles para destino
            $estanteriasDestino = Estanteria::with('zona')
                ->whereNotIn('id', $estanteriasConProducto)
                ->where('capacidad_libre', '>', 0)
                ->get()
                ->map(function ($estanteria) {
                    return [
                        'id' => $estanteria->id,
                        'nombre' => "Estantería {$estanteria->nombre}",
                        'zona' => $estanteria->zona ? "- {$estanteria->zona->nombre}" : '',
                        'capacidad_libre' => $estanteria->capacidad_libre
                    ];
                });

            return response()->json([
                'estanteriasOrigen' => $estanteriasOrigen,
                'estanteriasDestino' => $estanteriasDestino
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al obtener las estanterías: ' . $e->getMessage()
            ], 500);
        }
    }
}
