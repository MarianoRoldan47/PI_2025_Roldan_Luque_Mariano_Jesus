<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            'Electrónica',
            'Ropa',
            'Hogar',
            'Juguetes',
            'Deportes',
            'Libros',
        ];

        foreach ($categorias as $nombre) {
            DB::table('categorias')->insert([
                'nombre' => $nombre,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
