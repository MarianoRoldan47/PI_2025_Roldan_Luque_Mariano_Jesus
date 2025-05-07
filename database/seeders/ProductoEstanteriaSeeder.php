<?php

namespace Database\Seeders;

use App\Models\Estanteria;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class ProductoEstanteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = Producto::all();
        $estanterias = Estanteria::all();

        foreach ($productos as $producto) {
            $estanteriasAleatorias = $estanterias->random(rand(1, 3));

            foreach ($estanteriasAleatorias as $estanteria) {
                $cantidad = rand(5, 20);

                DB::table('producto_estanteria')->insert([
                    'producto_id' => $producto->id,
                    'estanteria_id' => $estanteria->id,
                    'cantidad' => $cantidad,
                ]);

                Estanteria::where('id', $estanteria->id)->decrement('capacidad_libre', $cantidad);
            }
        }
    }
}
