<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\AlertaStock;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $productoscount = Producto::count();
        $usuarioscount = User::count();
        $movimientoscount = Movimiento::count();
        $alertascount = AlertaStock::count();

        $lastWeekStart = now()->startOfWeek();
        $lastWeekEnd = now()->endOfWeek();

        $movimientosPorDia = Movimiento::selectRaw('DAYOFWEEK(fecha_movimiento) as dia, COUNT(*) as total')
            ->whereBetween('fecha_movimiento', [$lastWeekStart, $lastWeekEnd])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

        $labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

        $dataPorDia = array_fill(0, 7, 0);

        foreach ($movimientosPorDia as $movimiento) {
            $indice = $movimiento->dia - 1;
            $dataPorDia[$indice] = $movimiento->total;
        }

        $labels = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        $data = [
            $dataPorDia[1],
            $dataPorDia[2],
            $dataPorDia[3],
            $dataPorDia[4],
            $dataPorDia[5],
        ];

        $rankingUsuarios = Movimiento::whereNotNull('user_id')
            ->whereHas('usuario')
            ->whereBetween('fecha_movimiento', [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ])
            ->selectRaw('user_id, COUNT(*) as total')
            ->with('usuario:id,name')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->limit(3)
            ->get();
        $usuarios = $rankingUsuarios->map(function ($item) {
            return [
                'name' => $item->usuario->name,
                'total' => $item->total,
            ];
        });



        return view('dashboard', [
            'productoscount' => $productoscount,
            'usuarioscount' => $usuarioscount,
            'movimientoscount' => $movimientoscount,
            'alertascount' => $alertascount,
            'labels' => array_values($labels),
            'data' => array_values($data),
            'usuarios' => $usuarios,
        ]);
    }
}
