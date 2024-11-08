<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Cliente::create(   [
            'nombre' => 'Carlos Vargas',
            'direccion' => 'Avenida Central, San José, Costa Rica',
            'telefono' => '+506 1234 5678',
            'email' => 'carlos.vargas@example.com',
            'cedula' => '11111111111',
            'created_at' => now(),
            'updated_at' => now(),
        ] );
        Cliente::create(  [
            'nombre' => 'María López',
            'direccion' => 'Zona 4, Heredia, Costa Rica',
            'telefono' => '+506 8765 4321',
            'email' => 'maria.lopez@example.com',
            'cedula' => '22222222222',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Cliente::create(   [
            'nombre' => 'Pedro Gómez',
            'direccion' => 'Calle 25, Alajuela, Costa Rica',
            'telefono' => '+506 2345 6789',
            'email' => 'pedro.gomez@example.com',
            'cedula' => '33333333333',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
