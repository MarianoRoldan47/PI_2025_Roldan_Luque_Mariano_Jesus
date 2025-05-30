<?php

namespace Database\Seeders;

use App\Models\AlertaStock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;

class AlertaStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AlertaStock::create([
            'producto_id' => 1,
            'stock_actual' => 5,
            'fecha_alerta' => Date::now(),
        ]);

        AlertaStock::create([
            'producto_id' => 2,
            'stock_actual' => 3,
            'fecha_alerta' => Date::now(),
        ]);
    }
}
