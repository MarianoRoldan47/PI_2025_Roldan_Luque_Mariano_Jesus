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
use Illuminate\Support\Facades\Log;

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

        return view('vistasPersonalizadas.movimientos.index', compact('movimientos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $tipo = $request->tipo ?? 'entrada';
        $productos = Producto::all();
        $usuarios = User::all();
        $producto_id = $request->producto_id ? (int)$request->producto_id : null;

        $viewData = [
            'productos' => $productos,
            'usuarios' => $usuarios,
            'tipo' => $tipo,
            'producto_id' => $producto_id,
            'estanterias' => [],
            'estanteriasOrigen' => [],
            'estanteriasDestino' => []
        ];

        if ($tipo === 'traslado' && $producto_id) {
            try {
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

                $estanteriasDestino = Estanteria::with('zona')
                    ->where('capacidad_libre', '>', 0)
                    ->whereNotIn('id', $estanteriasOrigen->pluck('id'))
                    ->get()
                    ->map(function ($estanteria) {
                        return [
                            'id' => $estanteria->id,
                            'nombre' => "Estantería {$estanteria->nombre}",
                            'zona' => $estanteria->zona ? "- {$estanteria->zona->nombre}" : '',
                            'capacidad_libre' => $estanteria->capacidad_libre
                        ];
                    });

                $viewData['estanteriasOrigen'] = $estanteriasOrigen;
                $viewData['estanteriasDestino'] = $estanteriasDestino;
                $viewData['stockTotal'] = Producto::find($producto_id)->stock_total;
            } catch (Exception $e) {
                Log::error("Error cargando estanterías para traslado: " . $e->getMessage());
            }
        } elseif ($tipo === 'entrada') {
            $viewData['estanterias'] = Estanteria::where('capacidad_libre', '>', 0)
                ->with('zona')
                ->get()
                ->map(function ($estanteria) {
                    return [
                        'id' => $estanteria->id,
                        'nombre' => "Estantería {$estanteria->nombre}",
                        'zona' => $estanteria->zona ? "- {$estanteria->zona->nombre}" : '',
                        'capacidad_libre' => $estanteria->capacidad_libre,
                        'capacidad_maxima' => $estanteria->capacidad_maxima
                    ];
                });
        }

        return view('vistasPersonalizadas.movimientos.create', $viewData);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validate([
                'producto_id' => 'required|exists:productos,id',
                'cantidad' => 'required|integer|min:1',
                'tipo' => 'required|in:entrada,salida,traslado',
            ]);

            $producto = Producto::findOrFail($request->producto_id);

            switch ($request->tipo) {
                case 'entrada':
                    $request->validate([
                        'ubicaciones' => 'required|array',
                        'ubicaciones.*' => 'required|integer|min:1'
                    ]);

                    $capacidadTotalDisponible = Estanteria::sum('capacidad_libre');

                    if ($capacidadTotalDisponible < $request->cantidad) {
                        throw new Exception('No hay suficiente espacio disponible en las estanterías. Espacio total disponible: ' . $capacidadTotalDisponible);
                    }

                    $totalAsignado = array_sum($request->ubicaciones);
                    if ($totalAsignado !== (int)$request->cantidad) {
                        throw new Exception('La cantidad total asignada no coincide con la cantidad del movimiento');
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
                        $estanteria->capacidad_libre -= $cantidad;
                        $estanteria->save();

                        $producto->stock_total += $cantidad;
                        $producto->save();

                        $existingPivot = $estanteria->productos()
                            ->where('producto_id', $request->producto_id)
                            ->first();

                        if ($existingPivot) {
                            $estanteria->productos()->updateExistingPivot($request->producto_id, [
                                'cantidad' => DB::raw("cantidad + $cantidad"),
                                'updated_at' => now()
                            ]);
                        } else {
                            $estanteria->productos()->attach($request->producto_id, [
                                'cantidad' => $cantidad,
                                'created_at' => now(),
                                'updated_at' => now()
                            ]);
                        }
                    }
                    break;

                case 'salida':
                    if ($producto->stock_total < $request->cantidad) {
                        throw new Exception('No hay suficiente stock disponible.');
                    }

                    $ubicaciones = DB::table('producto_estanteria')
                        ->where('producto_id', $request->producto_id)
                        ->where('cantidad', '>', 0)
                        ->orderBy('created_at', 'asc')
                        ->get();

                    $cantidadPendiente = $request->cantidad;

                    foreach ($ubicaciones as $ubicacion) {
                        $cantidadASacar = min($cantidadPendiente, $ubicacion->cantidad);

                        if ($cantidadASacar > 0) {
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

                            $estanteria = Estanteria::findOrFail($ubicacion->estanteria_id);
                            $estanteria->capacidad_libre += $cantidadASacar;
                            $estanteria->save();

                            $producto->decrement('stock_total', $cantidadASacar);

                            if ($cantidadASacar >= $ubicacion->cantidad) {
                                DB::table('producto_estanteria')
                                    ->where('estanteria_id', $ubicacion->estanteria_id)
                                    ->where('producto_id', $request->producto_id)
                                    ->delete();
                            } else {
                                DB::table('producto_estanteria')
                                    ->where('estanteria_id', $ubicacion->estanteria_id)
                                    ->where('producto_id', $request->producto_id)
                                    ->decrement('cantidad', $cantidadASacar);
                            }

                            $cantidadPendiente -= $cantidadASacar;

                            if ($cantidadPendiente <= 0) {
                                break;
                            }
                        }
                    }

                    if ($cantidadPendiente > 0) {
                        throw new Exception('Error al procesar la salida del stock. Cantidad pendiente: ' . $cantidadPendiente);
                    }

                    break;

                case 'traslado':
                    $request->validate([
                        'ubicaciones_origen' => 'required|array',
                        'ubicaciones_origen.*' => 'required|integer|min:1',
                        'ubicaciones_destino' => 'required|array',
                        'ubicaciones_destino.*' => 'required|integer|min:1'
                    ]);

                    $totalOrigen = array_sum($request->ubicaciones_origen);
                    $totalDestino = array_sum($request->ubicaciones_destino);

                    if ($totalOrigen !== (int)$request->cantidad || $totalDestino !== (int)$request->cantidad) {
                        throw new Exception('Las cantidades en origen y destino deben coincidir con la cantidad total del movimiento');
                    }

                    foreach ($request->ubicaciones_origen as $origenId => $cantidadOrigen) {
                        $stockDisponible = DB::table('producto_estanteria')
                            ->where('estanteria_id', $origenId)
                            ->where('producto_id', $request->producto_id)
                            ->value('cantidad') ?? 0;

                        if ($stockDisponible < $cantidadOrigen) {
                            throw new Exception("No hay suficiente stock en la estantería origen ID: {$origenId}");
                        }
                    }

                    $destinos = [];
                    foreach ($request->ubicaciones_destino as $destinoId => $cantidadRequerida) {
                        $destinos[$destinoId] = [
                            'cantidad_requerida' => $cantidadRequerida,
                            'cantidad_pendiente' => $cantidadRequerida
                        ];
                    }

                    foreach ($request->ubicaciones_origen as $origenId => $cantidadDisponible) {
                        $cantidadRestante = $cantidadDisponible;

                        foreach ($destinos as $destinoId => &$destino) {
                            if ($cantidadRestante <= 0) break;
                            if ($destino['cantidad_pendiente'] <= 0) continue;

                            $cantidadMover = min($cantidadRestante, $destino['cantidad_pendiente']);

                            if ($cantidadMover > 0) {
                                Movimiento::create([
                                    'producto_id' => $request->producto_id,
                                    'cantidad' => $cantidadMover,
                                    'tipo' => 'traslado',
                                    'origen_tipo' => 'estanteria',
                                    'ubicacion_origen_id' => $origenId,
                                    'destino_tipo' => 'estanteria',
                                    'ubicacion_destino_id' => $destinoId,
                                    'fecha_movimiento' => now(),
                                    'user_id' => Auth::id(),
                                    'estado' => 'confirmado'
                                ]);

                                $stockActual = DB::table('producto_estanteria')
                                    ->where('estanteria_id', $origenId)
                                    ->where('producto_id', $request->producto_id)
                                    ->value('cantidad');

                                if ($stockActual == $cantidadMover) {
                                    DB::table('producto_estanteria')
                                        ->where('estanteria_id', $origenId)
                                        ->where('producto_id', $request->producto_id)
                                        ->delete();
                                } else {
                                    DB::table('producto_estanteria')
                                        ->where('estanteria_id', $origenId)
                                        ->where('producto_id', $request->producto_id)
                                        ->decrement('cantidad', $cantidadMover);
                                }

                                DB::table('producto_estanteria')
                                    ->updateOrInsert(
                                        [
                                            'estanteria_id' => $destinoId,
                                            'producto_id' => $request->producto_id
                                        ],
                                        [
                                            'cantidad' => DB::raw("COALESCE(cantidad, 0) + {$cantidadMover}"),
                                            'updated_at' => now()
                                        ]
                                    );

                                Estanteria::where('id', $origenId)->increment('capacidad_libre', $cantidadMover);
                                Estanteria::where('id', $destinoId)->decrement('capacidad_libre', $cantidadMover);

                                $cantidadRestante -= $cantidadMover;
                                $destino['cantidad_pendiente'] -= $cantidadMover;
                            }
                        }
                    }

                    foreach ($destinos as $destinoId => $destino) {
                        if ($destino['cantidad_pendiente'] > 0) {
                            throw new Exception("No se pudo distribuir toda la cantidad requerida para el destino {$destinoId}");
                        }
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
        $movimiento->load(['producto' => function ($query) {
            $query->withTrashed();
        }]);

        return view('vistasPersonalizadas.movimientos.show', compact('movimiento'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movimiento $movimiento)
    {
        $productos = Producto::all();
        $usuarios = User::all();

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

        return view('vistasPersonalizadas.movimientos.edit', compact('movimiento', 'productos', 'usuarios', 'estanterias'));
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

    /**
     * Obtiene las estanterías disponibles para un producto
     */
    public function getEstanterias(Request $request)
    {
        try {
            $producto_id = $request->producto_id;

            if (!$producto_id) {
                return response()->json([
                    'error' => 'Se requiere un producto_id'
                ], 400);
            }

            $producto = Producto::findOrFail($producto_id);

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

            $estanteriasIds = $estanteriasOrigen->pluck('id')->toArray();

            $estanteriasDestino = Estanteria::with('zona')
                ->where('capacidad_libre', '>', 0)
                ->whereNotIn('id', $estanteriasIds)
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
                'estanteriasDestino' => $estanteriasDestino,
                'stockTotal' => $producto->stock_total
            ]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al obtener las estanterías: ' . $e->getMessage()
            ], 500);
        }
    }
}
