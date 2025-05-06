<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ZonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zonas = [
            ['nombre' => 'Zona A', 'descripcion' => 'Área principal de almacenamiento'],
            ['nombre' => 'Zona B', 'descripcion' => 'Zona de productos terminados'],
            ['nombre' => 'Zona C', 'descripcion' => 'Zona de materias primas'],
            ['nombre' => 'Zona D', 'descripcion' => 'Zona de despacho y carga'],
        ];

        foreach ($zonas as $zona) {
            DB::table('zonas')->insert([
                'nombre' => $zona['nombre'],
                'descripcion' => $zona['descripcion'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
