<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = Categoria::all();

        foreach (range(1, 10) as $i) {
            Producto::create([
                'codigo_producto' => strtoupper(Str::random(8)),
                'nombre' => 'Producto ' . $i,
                'descripcion' => 'Descripción del producto ' . $i,
                'imagen' => null,
                'tipo' => $i % 2 === 0 ? 'materia_prima' : 'producto_terminado',
                'categoria_id' => $categorias->random()->id,
                'stock_total' => rand(1, 500),
                'stock_minimo_alerta' => rand(1, 100),
            ]);
        }
    }
}
