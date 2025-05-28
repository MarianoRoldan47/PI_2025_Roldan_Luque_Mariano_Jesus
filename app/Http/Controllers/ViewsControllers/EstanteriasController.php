<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\Estanteria;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EstanteriasController extends Controller
{

    public function create()
    {
        $zonas = Zona::all();
        return view('estanterias.create', compact('zonas'));
    }

    public function store(Request $request)
    {
        // Regla personalizada para validar que el nombre no esté duplicado en la misma zona
        $uniqueInZone = Rule::unique('estanterias', 'nombre')
            ->where('zona_id', $request->zona_id);

        $request->validate([
            'nombre' => ['required', 'string', 'max:255', $uniqueInZone],
            'zona_id' => 'required|exists:zonas,id',
            'capacidad_maxima' => 'required|integer|min:1',
        ], [
            'nombre.unique' => 'Ya existe una estantería con este nombre en la zona seleccionada.',
        ]);

        // Inicialmente la capacidad libre es igual a la máxima
        $request->merge(['capacidad_libre' => $request->capacidad_maxima]);

        Estanteria::create($request->all());

        return redirect()->route('estanterias.index')
            ->with('status', 'Estantería creada correctamente.')
            ->with('status-type', 'success');
    }

    public function show(Estanteria $estanteria)
    {
        $estanteria->load('zona', 'productos');
        return view('estanterias.show', compact('estanteria'));
    }

    public function edit(Estanteria $estanteria)
    {
        $zonas = Zona::all();
        return view('estanterias.edit', compact('estanteria', 'zonas'));
    }

    public function update(Request $request, Estanteria $estanteria)
    {
        // Regla personalizada para validar que el nombre no esté duplicado en la misma zona
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

        // Actualizar capacidad libre si cambió la máxima
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
                return redirect()->route('estanterias.index')
                    ->with('status', 'No se puede eliminar la estantería porque contiene productos.')
                    ->with('status-type', 'danger');
            }

            $estanteria->delete();
            return redirect()->route('estanterias.index')
                ->with('status', 'Estantería eliminada correctamente.')
                ->with('status-type', 'success');
        } catch (\Exception $e) {
            return redirect()->route('estanterias.index')
                ->with('status', 'No se pudo eliminar la estantería.')
                ->with('status-type', 'danger');
        }
    }
}
