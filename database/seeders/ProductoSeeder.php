<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create([
            'nombre' => 'Café de Costa Rica',
            'descripcion' => 'Café arábica 100% orgánico, producido en las regiones montañosas de Costa Rica.',
            'precio' => 5000.00, // Precio en colones
            'stock' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Producto::create([
            'nombre' => 'Pura Vida T-Shirt',
            'descripcion' => 'Camiseta de algodón con el icónico diseño "Pura Vida". Disponible en varias tallas.',
            'precio' => 2500.00, // Precio en colones
            'stock' => 150,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Producto::create([
            'nombre' => 'Artesanía en Madera',
            'descripcion' => 'Artesanía hecha a mano con madera de la región, ideal para souvenirs.',
            'precio' => 7500.00, // Precio en colones
            'stock' => 80,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Producto::create([
            'nombre' => 'Ropa de Baño Tropical',
            'descripcion' => 'Ropa de baño con diseños tropicales, perfecta para la playa en Costa Rica.',
            'precio' => 4500.00, // Precio en colones
            'stock' => 120,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Producto::create([
            'nombre' => 'Juguetes de Caucho Natural',
            'descripcion' => 'Juguetes para niños hechos de caucho natural, ideales para la playa y actividades al aire libre.',
            'precio' => 3000.00, // Precio en colones
            'stock' => 60,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
