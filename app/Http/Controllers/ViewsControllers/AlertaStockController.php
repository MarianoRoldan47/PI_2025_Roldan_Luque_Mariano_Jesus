<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\AlertaStock;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertaStockController extends Controller
{
    public function index()
    {
        $alertas = AlertaStock::with('producto')->latest('fecha_alerta')->get();
        return view('alertas.index', compact('alertas'));
    }

    public function show(AlertaStock $alerta)
    {
        $alerta->load(['producto' => function ($query) {
            $query->with(['movimientos' => function ($q) {
                $q->orderBy('fecha_movimiento', 'desc');
            }]);
        }]);

        return view('alertas.show', compact('alerta'));
    }

    public function destroy(AlertaStock $alerta)
    {
        $alerta->delete();
        return redirect()->route('alertas.index')
            ->with('status', 'Alerta eliminada correctamente.')
            ->with('status-type', 'success');
    }
}
