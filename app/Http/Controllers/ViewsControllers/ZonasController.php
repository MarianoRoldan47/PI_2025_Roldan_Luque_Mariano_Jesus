<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Exception;
use Illuminate\Http\Request;

class ZonasController extends Controller
{
    public function create()
    {
        return view('vistasPersonalizadas.zonas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:zonas',
            'descripcion' => 'nullable|string'
        ]);

        Zona::create($request->all());

        return redirect()->route('almacen.index')
            ->with('success', 'Zona creada correctamente.');
    }

    public function show(Zona $zona)
    {
        $zona->load('estanterias');
        return view('vistasPersonalizadas.zonas.show', compact('zona'));
    }

    public function edit(Zona $zona)
    {
        return view('vistasPersonalizadas.zonas.edit', compact('zona'));
    }

    public function update(Request $request, Zona $zona)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:zonas,nombre,' . $zona->id,
            'descripcion' => 'nullable|string'
        ]);

        $zona->update($request->all());

        return redirect()->route('zonas.show', $zona)
            ->with('success', 'Zona actualizada correctamente.');
    }

    public function destroy(Zona $zona)
    {
        // Verificar si la zona tiene estanterías asociadas
        if ($zona->estanterias->count() > 0) {
            return redirect()->route('zonas.show', $zona)
                ->with('status', 'No se puede eliminar la zona porque tiene estanterías asociadas.')
                ->with('status-type', 'danger');
        }

        try {
            $zona->delete();
            return redirect()->route('almacen.index')
                ->with('success', 'Zona eliminada correctamente.');
        } catch (Exception $e) {
            return redirect()->route('almacen.index')
                ->with('status', 'Ha ocurrido un error al eliminar la zona.')
                ->with('status-type', 'danger');
        }
    }
}
