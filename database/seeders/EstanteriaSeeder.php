<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EstanteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $zonas = DB::table('zonas')->get();

        foreach ($zonas as $zona) {
            for ($i = 1; $i <= 3; $i++) {
                $capacidad = rand(50, 100);
                DB::table('estanterias')->insert([
                    'zona_id' => $zona->id,
                    'capacidad_maxima' => $capacidad,
                    'capacidad_libre' => $capacidad,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
