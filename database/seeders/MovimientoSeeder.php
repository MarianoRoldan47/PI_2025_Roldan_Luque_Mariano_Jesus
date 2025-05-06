<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MovimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = DB::table('productos')->get();
        $usuarios = DB::table('users')->get();
        $estanterias = DB::table('estanterias')->get();

        foreach (range(1, 30) as $i) {
            $producto = $productos->random();
            $user = $usuarios->random();
            $tipo = collect(['entrada', 'salida', 'traslado'])->random();
            $cantidad = rand(1, 10);
            $fecha = Carbon::now()->subDays(rand(0, 30));

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

            DB::table('movimientos')->insert([
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
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
