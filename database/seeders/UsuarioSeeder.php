<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Usuario::create( [
                'nombre' => 'Juan Pérez',
                'email' => 'juan.perez@example.com',
                'cedula' => '12345678901',
                'role' => 'contador',
                'email_verified_at' => now(), // Simula la verificación del correo
                'password' => Hash::make('password123'), // Usa una contraseña segura en producción
                'remember_token' => null, // Deja en null si no usas tokens de sesión
                'created_at' => now(),
                'updated_at' => now(),
            ]  );
            Usuario::create([
                'nombre' => 'Ana Gómez',
                'email' => 'ana.gomez@example.com',
                'cedula' => '10987654321',
                'role' => 'admin',
                'email_verified_at' => now(), // Simula la verificación del correo
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ] );
            Usuario::create([
                'nombre' => 'Luis Fernández',
                'email' => 'luis.fernandez@example.com',
                'cedula' => '11223344556',
                'role' => 'auditor',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
    }
}
