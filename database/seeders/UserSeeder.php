<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'dni' => '20621290L',
            'name' => 'Mariano',
            'apellido1' => 'Roldan',
            'apellido2' => 'Luque',
            'telefono' => '642160405',
            'direccion' => 'Calle Fresno 69A',
            'codigo_postal' => '14960',
            'localidad' => 'Rute',
            'provincia' => 'Cordoba',
            'rol' => 'Administrador',
            'email' => 'marianojesusroldanluque1@gmail.com',
            'password' => "1234",
            'imagen' => null,
            'fecha_nacimiento' => '2005-07-04',
        ]);
    }
}
