<?php

namespace Database\Seeders;

use App\Models\Estanteria;
use App\Models\Zona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstanteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zonas = Zona::all();

        foreach ($zonas as $zona) {
            for ($i = 1; $i <= 3; $i++) {
                $capacidad = rand(50, 100);
                Estanteria::create([
                    'zona_id' => $zona->id,
                    'capacidad_maxima' => $capacidad,
                    'capacidad_libre' => $capacidad,
                ]);
            }
        }
    }
}
