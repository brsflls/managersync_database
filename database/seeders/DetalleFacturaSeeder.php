<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetalleFactura; // Asegúrate de que este modelo exista
use App\Models\Factura;
use App\Models\Producto;

class DetalleFacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de facturas y productos existentes
        $facturaIds = Factura::pluck('id')->toArray();
        $productoIds = Producto::pluck('id')->toArray();

        // Asegúrate de que haya datos en las tablas relacionadas
        if (empty($facturaIds) || empty($productoIds)) {
            $this->command->error('No hay datos en las tablas relacionadas. Asegúrate de que las tablas facturas y productos contengan datos.');
            return;
        }

        // Crear detalles
        DetalleFactura::create([
            'factura_id' => $facturaIds[array_rand($facturaIds)],
            'producto_id' => $productoIds[array_rand($productoIds)],
            'cantidad' => 2,
            'precio_unitario' => 500.00,
            'total' => 1000.00,
        ]);

        DetalleFactura::create([
            'factura_id' => $facturaIds[array_rand($facturaIds)],
            'producto_id' => $productoIds[array_rand($productoIds)],
            'cantidad' => 1,
            'precio_unitario' => 2500.00,
            'total' => 2500.00,
        ]);

        DetalleFactura::create([
            'factura_id' => $facturaIds[array_rand($facturaIds)],
            'producto_id' => $productoIds[array_rand($productoIds)],
            'cantidad' => 3,
            'precio_unitario' => 7500.00,
            'total' => 22500.00,
        ]);
    }
}
