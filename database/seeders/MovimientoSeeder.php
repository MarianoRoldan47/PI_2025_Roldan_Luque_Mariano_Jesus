<?php

namespace Database\Seeders;

use App\Models\Estanteria;
use App\Models\Movimiento;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class MovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = Producto::all();
        $usuarios = User::all();
        $estanterias = Estanteria::all();

        foreach (range(1, 30) as $i) {
            $producto = $productos->random();
            $user = $usuarios->random();
            $tipo = collect(['entrada', 'salida', 'traslado'])->random();
            $cantidad = rand(1, 10);
            $fecha = Date::now()->subDays(rand(0, 30));

            $origen_tipo = null;
            $destino_tipo = null;
            $origen_id = null;
            $destino_id = null;

            if ($tipo === 'entrada') {
                $origen_tipo = 'proveedor';
                $destino_tipo = 'estanteria';
                $destino_id = $estanterias->random()->id;
            } elseif ($tipo === 'salida') {
                $origen_tipo = 'estanteria';
                $origen_id = $estanterias->random()->id;
                $destino_tipo = 'cliente';
            } elseif ($tipo === 'traslado') {
                $origen_tipo = 'estanteria';
                $origen_id = $estanterias->random()->id;
                do {
                    $destino_id = $estanterias->random()->id;
                } while ($destino_id === $origen_id);
                $destino_tipo = 'estanteria';
            }

            Movimiento::create([
                'producto_id' => $producto->id,
                'user_id' => $user->id,
                'tipo' => $tipo,
                'cantidad' => $cantidad,
                'origen_tipo' => $origen_tipo,
                'ubicacion_origen_id' => $origen_id,
                'destino_tipo' => $destino_tipo,
                'ubicacion_destino_id' => $destino_id,
                'estado' => 'confirmado',
                'fecha_movimiento' => $fecha,
            ]);
        }
    }
}
