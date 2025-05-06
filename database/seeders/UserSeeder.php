<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'dni' => '12345678A',
            'name' => 'Admin',
            'apellido1' => 'Apellido1',
            'apellido2' => 'Apellido2',
            'telefono' => '600000000',
            'direccion' => 'Calle Ejemplo 123',
            'codigo_postal' => '28080',
            'localidad' => 'Madrid',
            'provincia' => 'Madrid',
            'rol' => 'Administrador',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'imagen' => null,
            'fecha_nacimiento' => '1990-01-01',
            'remember_token' => Str::random(10),
        ]);
    }
}
