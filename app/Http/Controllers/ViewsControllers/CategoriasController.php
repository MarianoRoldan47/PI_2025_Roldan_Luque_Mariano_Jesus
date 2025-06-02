<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();

        return view('vistasPersonalizadas.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:categorias'
            ]);

            $categoria = Categoria::create($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'categoria' => $categoria,
                    'message' => '¡Categoría creada correctamente!'
                ]);
            }

            return redirect()->route('categorias.index')
                ->with('success', '¡Categoría creada correctamente!');
        } catch (Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la categoría: ' . $e->getMessage()
                ], 422);
            }

            return back()->withInput()->withErrors(['error' => 'Error al crear la categoría']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);

            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:categorias,nombre,' . $id
            ]);

            $categoria->update($validated);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'categoria' => $categoria,
                    'message' => '¡Categoría actualizada correctamente!'
                ]);
            }

            return redirect()->route('categorias.index')
                ->with('success', '¡Categoría actualizada correctamente!');
        } catch (Exception $e) {
            Log::error('Error al actualizar categoría: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la categoría: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->route('categorias.index')
                ->with('error', 'Error al actualizar la categoría: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $categoria = Categoria::withCount('productos')->findOrFail($id);

            if ($categoria->productos_count > 0) {
                return redirect()->route('categorias.index')
                    ->with('status', 'No se puede eliminar la categoría "' . $categoria->nombre . '" porque tiene ' . $categoria->productos_count . ' productos asociados.')
                    ->with('status-type', 'danger');
            }

            $nombreCategoria = $categoria->nombre;
            $categoria->delete();

            return redirect()->route('categorias.index')
                ->with('success', 'Categoría "' . $nombreCategoria . '" eliminada correctamente');
        } catch (Exception $e) {
            Log::error('Error al eliminar categoría: ' . $e->getMessage());

            return redirect()->route('categorias.index')
                ->with('status', 'Error al eliminar la categoría: ' . $e->getMessage())
                ->with('status-type', 'danger');
        }
    }
}
