<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use PDF;

class InformeController extends Controller
{
    public function index()
    {
        $categorias = Categoria::all();
        return view('vistasPersonalizadas.informes.index', compact('categorias'));
    }

    public function generarPdfInventario(Request $request)
    {
        // Validar entradas
        $request->validate([
            'categoria_id' => 'nullable|exists:categorias,id',
            'orden' => 'nullable|in:nombre,codigo_producto,stock',
            'direccion' => 'nullable|in:asc,desc',
        ]);

        // Consulta base
        $query = Producto::with('categoria');

        // Filtrar por categoría si se proporciona
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }

        // Aplicar orden
        $orden = $request->input('orden', 'nombre');
        $direccion = $request->input('direccion', 'asc');
        $query->orderBy($orden, $direccion);

        // Obtener productos
        $productos = $query->get();

        // Preparar datos para la vista
        $fecha = now()->format('d/m/Y');
        $titulo = 'Informe de Inventario';

        if ($request->filled('categoria_id')) {
            $categoria = Categoria::find($request->categoria_id);
            $titulo .= ' - Categoría: ' . $categoria->nombre;
        }

        // Cargar vista y generar PDF
        $pdf = PDF::loadView('vistasPersonalizadas.informes.pdf.inventario', compact(
            'productos',
            'fecha',
            'titulo'
        ));

        // Personalizar PDF
        $pdf->setPaper('a4', 'landscape');

        // Descargar o mostrar
        return $pdf->stream('inventario-' . now()->format('Y-m-d') . '.pdf');
    }
}
