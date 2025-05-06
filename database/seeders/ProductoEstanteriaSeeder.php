<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ProductoEstanteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = DB::table('productos')->get();
        $estanterias = DB::table('estanterias')->get();

        foreach ($productos as $producto) {
            $estanteriasAleatorias = $estanterias->random(rand(1, 3));

            foreach ($estanteriasAleatorias as $estanteria) {
                $cantidad = rand(5, 20);

                DB::table('producto_estanteria')->insert([
                    'producto_id' => $producto->id,
                    'estanteria_id' => $estanteria->id,
                    'cantidad' => $cantidad,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('estanterias')->where('id', $estanteria->id)->decrement('capacidad_libre', $cantidad);
            }
        }
    }
}
