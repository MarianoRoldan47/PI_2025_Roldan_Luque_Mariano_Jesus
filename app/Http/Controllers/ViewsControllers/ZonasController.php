<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;

class ZonasController extends Controller
{
    public function create()
    {
        return view('zonas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:zonas',
            'descripcion' => 'nullable|string'
        ]);

        Zona::create($request->all());

        return redirect()->route('zonas.index')
            ->with('status', 'Zona creada correctamente.')
            ->with('status-type', 'success');
    }

    public function show(Zona $zona)
    {
        $zona->load('estanterias');
        return view('zonas.show', compact('zona'));
    }

    public function edit(Zona $zona)
    {
        return view('zonas.edit', compact('zona'));
    }

    public function update(Request $request, Zona $zona)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:zonas,nombre,' . $zona->id,
            'descripcion' => 'nullable|string'
        ]);

        $zona->update($request->all());

        return redirect()->route('zonas.index')
            ->with('status', 'Zona actualizada correctamente.')
            ->with('status-type', 'success');
    }

    public function destroy(Zona $zona)
    {
        try {
            $zona->delete();
            return redirect()->route('zonas.index')
                ->with('status', 'Zona eliminada correctamente.')
                ->with('status-type', 'success');
        } catch (\Exception $e) {
            return redirect()->route('zonas.index')
                ->with('status', 'No se pudo eliminar la zona porque tiene estanterías asociadas.')
                ->with('status-type', 'danger');
        }
    }
}
