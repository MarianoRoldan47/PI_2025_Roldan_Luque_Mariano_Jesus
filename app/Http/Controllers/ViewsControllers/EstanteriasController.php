<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Estanteria;
use App\Models\Zona;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstanteriasController extends Controller
{

    public function create()
    {
        $zonas = Zona::all();
        return view('vistasPersonalizadas.estanterias.create', compact('zonas'));
    }

    public function store(Request $request)
    {

        $uniqueInZone = Rule::unique('estanterias', 'nombre')
            ->where('zona_id', $request->zona_id);

        $request->validate([
            'nombre' => ['required', 'string', 'max:255', $uniqueInZone],
            'zona_id' => 'required|exists:zonas,id',
            'capacidad_maxima' => 'required|integer|min:1',
        ], [
            'nombre.unique' => 'Ya existe una estantería con este nombre en la zona seleccionada.',
        ]);


        $request->merge(['capacidad_libre' => $request->capacidad_maxima]);

        Estanteria::create($request->all());

        return redirect()->route('almacen.index')
            ->with('success', 'Estantería creada correctamente.');
    }

    public function show(Estanteria $estanteria)
    {
        $estanteria->load('zona', 'productos');
        return view('vistasPersonalizadas.estanterias.show', compact('estanteria'));
    }

    public function edit(Estanteria $estanteria)
    {
        $zonas = Zona::all();
        return view('vistasPersonalizadas.estanterias.edit', compact('estanteria', 'zonas'));
    }

    public function update(Request $request, Estanteria $estanteria)
    {

        $uniqueInZone = Rule::unique('estanterias', 'nombre')
            ->where('zona_id', $request->zona_id)
            ->ignore($estanteria->id);

        $request->validate([
            'nombre' => ['required', 'string', 'max:255', $uniqueInZone],
            'zona_id' => 'required|exists:zonas,id',
            'capacidad_maxima' => 'required|integer|min:' . ($estanteria->capacidad_maxima - $estanteria->capacidad_libre),
        ], [
            'nombre.unique' => 'Ya existe una estantería con este nombre en la zona seleccionada.',
        ]);


        if ($request->capacidad_maxima != $estanteria->capacidad_maxima) {
            $diferencia = $request->capacidad_maxima - $estanteria->capacidad_maxima;
            $request->merge(['capacidad_libre' => $estanteria->capacidad_libre + $diferencia]);
        }

        $estanteria->update($request->all());

        return redirect()->route('estanterias.show', $estanteria)
            ->with('status', 'Estantería actualizada correctamente.')
            ->with('status-type', 'success');
    }

    public function destroy(Estanteria $estanteria)
    {
        try {
            if ($estanteria->capacidad_maxima != $estanteria->capacidad_libre) {
                return redirect()->route('estanterias.show', $estanteria)
                    ->with('status', 'No se puede eliminar la estantería porque contiene productos.')
                    ->with('status-type', 'danger');
            }

            $estanteria->delete();
            return redirect()->route('almacen.index')
                ->with('success', 'Estantería eliminada correctamente.');
        } catch (Exception $e) {
            return redirect()->route('almacen.index')
                ->with('status', 'No se pudo eliminar la estantería.')
                ->with('status-type', 'danger');
        }
    }
}
