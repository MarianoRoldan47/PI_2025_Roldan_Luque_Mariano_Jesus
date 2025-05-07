<?php

namespace Database\Seeders;

use App\Models\Zona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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
            Zona::create([
                'nombre' => $zona['nombre'],
                'descripcion' => $zona['descripcion'],
            ]);
        }
    }
}
