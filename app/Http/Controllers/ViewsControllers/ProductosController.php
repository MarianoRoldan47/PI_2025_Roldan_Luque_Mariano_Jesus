<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;

class ProductosController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['categoria', 'estanterias'])
            ->paginate(10);
        return view('vistasPersonalizadas.productos.index', compact('productos'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('vistasPersonalizadas.productos.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'codigo_producto' => 'required|string|unique:productos,codigo_producto',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string',
            'stock_minimo_alerta' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $datos = $request->all();

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes/productos', 'public');
            $datos['imagen'] = $path;
        }

        $producto = Producto::create($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function show(Producto $producto)
    {
        $producto->load(['categoria', 'estanterias.zona', 'movimientos' => function ($query) {
            $query->latest('fecha_movimiento')->limit(5);
        }]);

        return view('vistasPersonalizadas.productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        return view('vistasPersonalizadas.productos.edit', compact('producto', 'categorias'));
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'codigo_producto' => 'required|string|unique:productos,codigo_producto,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo' => 'required|string',
            'stock_minimo_alerta' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
            'imagen' => 'nullable|image|max:2048'
        ]);

        $datos = $request->all();

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                $rutaAnterior = $producto->imagen;
                if (Storage::disk('public')->exists($rutaAnterior)) {
                    Storage::disk('public')->delete($rutaAnterior);
                }
            }

            $path = $request->file('imagen')->store('imagenes/productos', 'public');
            $datos['imagen'] = $path;
        }

        $producto->update($datos);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Producto $producto)
    {
        try {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $producto->delete();
            return redirect()->route('productos.index')
                ->with('success', 'Producto eliminado correctamente');
        } catch (Exception $e) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar el producto porque está siendo utilizado');
        }
    }

    public function generarPdfInventario(Request $request)
    {
        $request->validate([
            'categoria_id' => 'nullable|exists:categorias,id',
            'orden' => 'nullable|in:nombre,codigo_producto,stock_total',
            'direccion' => 'nullable|in:asc,desc',
        ]);

        $query = Producto::with(['categoria', 'estanterias.zona']);

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        $orden = $request->input('orden', 'nombre');
        $direccion = $request->input('direccion', 'asc');
        $query->orderBy($orden, $direccion);

        $productos = $query->get();

        $fecha = now()->format('d/m/Y');
        $titulo = 'Informe de Inventario';

        if ($request->filled('categoria_id')) {
            $categoria = Categoria::find($request->categoria_id);
            $titulo .= ' - Categoría: ' . $categoria->nombre;
        }

        $pdf = PDF::loadView('vistasPersonalizadas.informes.pdf.inventario', compact(
            'productos',
            'fecha',
            'titulo'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('inventario-' . now()->format('Y-m-d') . '.pdf');
    }
}
