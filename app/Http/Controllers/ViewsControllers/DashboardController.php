<?php

namespace App\Http\Controllers\ViewsControllers;

use App\Http\Controllers\Controller;
use App\Models\AlertaStock;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Contadores para las tarjetas
        $productoscount = Producto::count();
        $usuarioscount = User::count();
        $movimientoscount = Movimiento::count();
        $alertascount = AlertaStock::count();

        // Datos para el gráfico de movimientos por día de la semana
        $lastWeekStart = now()->startOfWeek();
        $lastWeekEnd = now()->endOfWeek();

        $movimientosPorDia = Movimiento::selectRaw('DAYOFWEEK(fecha_movimiento) as dia, COUNT(*) as total')
            ->whereBetween('fecha_movimiento', [$lastWeekStart, $lastWeekEnd])
            ->groupBy('dia')
            ->orderBy('dia')
            ->get();

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

        // Obtener usuarios con movimientos en el mes actual - CONSULTA CORREGIDA
        $mesInicio = Date::now()->startOfMonth();
        $mesFin = Date::now()->endOfMonth();

        // Primero obtenemos todos los movimientos del mes actual con sus usuarios
        $movimientosUsuarios = Movimiento::join('users', 'movimientos.user_id', '=', 'users.id')
            ->select('users.id as user_id', 'users.name', DB::raw('COUNT(*) as total'))
            ->whereBetween('movimientos.fecha_movimiento', [$mesInicio, $mesFin])
            ->whereNotNull('movimientos.user_id')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        // Si no hay suficientes usuarios con movimientos, completamos con usuarios sin movimientos
        if ($movimientosUsuarios->count() < 3) {
            $usuariosConMovimientos = $movimientosUsuarios->pluck('user_id')->toArray();

            $usuariosSinMovimientos = User::where('rol', '!=', 'Administrador')
                ->whereNotIn('id', $usuariosConMovimientos)
                ->select('id', 'name')
                ->limit(3 - $movimientosUsuarios->count())
                ->get();

            foreach ($usuariosSinMovimientos as $usuario) {
                $movimientosUsuarios->push((object)[
                    'user_id' => $usuario->id,
                    'name' => $usuario->name,
                    'total' => 0
                ]);
            }
        }

        // Si aún no hay 3 usuarios (en caso de que haya menos de 3 usuarios en total en la BD)
        while ($movimientosUsuarios->count() < 3) {
            $movimientosUsuarios->push((object)[
                'user_id' => null,
                'name' => 'Sin datos',
                'total' => 0
            ]);
        }

        // Convertimos a un formato compatible con el gráfico
        $usuarios = $movimientosUsuarios->map(function ($item) {
            return [
                'name' => $item->name,
                'total' => (int)$item->total
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
