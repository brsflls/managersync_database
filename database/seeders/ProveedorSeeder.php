<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        Proveedor::create(   [
            'nombre' => 'Proveedor S.A.',
            'direccion' => 'Edificio Central, 2do piso, San José, Costa Rica',
            'telefono' => '+506 1234 5678',
            'email' => 'contacto@proveedorsa.com',
            'cedula_juridica' => '3101234567',
            'created_at' => now(),
            'updated_at' => now(),
        ] );
        Proveedor::create(  [
            'nombre' => 'Comercial ABC',
            'direccion' => 'Avenida 12, Barrio Escalante, San José, Costa Rica',
            'telefono' => '+506 8765 4321',
            'email' => 'info@comercialabc.cr',
            'cedula_juridica' => '3107654321',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Proveedor::create(   [
            'nombre' => 'Servicios XYZ',
            'direccion' => 'Calle 8, Tibás, San José, Costa Rica',
            'telefono' => '+506 2345 6789',
            'email' => 'servicios@xyz.com',
            'cedula_juridica' => '3109876543',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

