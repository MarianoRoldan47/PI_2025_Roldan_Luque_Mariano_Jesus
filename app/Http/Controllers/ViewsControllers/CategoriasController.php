<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info('Datos recibidos:', $request->all());

            // Validar
            $validated = $request->validate([
                'nombre' => 'required|string|max:255|unique:categorias,nombre'
            ]);

            // Crear categoría
            $categoria = Categoria::create($validated);

            Log::info('Categoría creada:', $categoria->toArray());

            // Siempre devolver JSON ya que es una petición AJAX
            return response()->json([
                'id' => $categoria->id,
                'nombre' => $categoria->nombre,
                'message' => 'Categoría creada correctamente'
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error al crear categoría: ' . $e->getMessage());

            return response()->json([
                'message' => 'Error al crear la categoría',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
