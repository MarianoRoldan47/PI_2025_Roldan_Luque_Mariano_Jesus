<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = DB::table('categorias')->pluck('id');

        foreach (range(1, 10) as $i) {
            DB::table('productos')->insert([
                'codigo_producto' => strtoupper(Str::random(8)),
                'nombre' => 'Producto ' . $i,
                'descripcion' => 'Descripción del producto ' . $i,
                'imagen' => null,
                'tipo' => $i % 2 === 0 ? 'materia_prima' : 'producto_terminado',
                'categoria_id' => $categorias->random(),
                'stock_total' => rand(10, 100),
                'stock_minimo_alerta' => rand(1, 9),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
