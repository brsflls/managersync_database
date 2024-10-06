<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Factura;
use App\Models\Cliente;
use App\Models\Proveedor; // Asegúrate de usar la clase correcta
use App\Models\Usuario;

class FacturaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener IDs de clientes, proveedores y usuarios existentes
        $clienteIds = Cliente::pluck('id')->toArray();
        $proveedorIds = Proveedor::pluck('id')->toArray(); // Corrige el nombre de la clase
        $usuarioIds = Usuario::pluck('id')->toArray();

        // Asegúrate de que haya datos en las tablas relacionadas
        if (empty($clienteIds) || empty($proveedorIds) || empty($usuarioIds)) {
            $this->command->error('No hay datos en las tablas relacionadas. Asegúrate de que las tablas clientes, proveedores y usuarios contengan datos.');
            return;
        }

        // Crear facturas
        Factura::create([
            'cliente_id' => $clienteIds[array_rand($clienteIds)], // Selección aleatoria de un cliente existente
            'proveedor_id' => $proveedorIds[array_rand($proveedorIds)], // Selección aleatoria de un proveedor existente
            'usuario_id' => $usuarioIds[array_rand($usuarioIds)], // Selección aleatoria de un usuario existente
            'numero_factura' => 'F001-' . now()->format('Ymd') . '-' . rand(1000, 9999),
            'fecha_emision' => now(),
            'fecha_vencimiento' => now()->addDays(30), // 30 días después de la fecha de emisión
            'total' => 1234.56,
            'tipo' => 'venta',
            'estado' => 'Emitida',
            'codigo_unico' => 'C-' . strtoupper(uniqid()),
            'xml_data' => '<xml></xml>', // Ejemplo de datos XML
        ]);

        Factura::create([
            'cliente_id' => $clienteIds[array_rand($clienteIds)],
            'proveedor_id' => $proveedorIds[array_rand($proveedorIds)],
            'usuario_id' => $usuarioIds[array_rand($usuarioIds)],
            'numero_factura' => 'F002-' . now()->format('Ymd') . '-' . rand(1000, 9999),
            'fecha_emision' => now(),
            'fecha_vencimiento' => now()->addDays(15),
            'total' => 5678.90,
            'tipo' => 'compra',
            'estado' => 'Pagada',
            'codigo_unico' => 'C-' . strtoupper(uniqid()),
            'xml_data' => '<xml></xml>',
        ]);

        Factura::create([
            'cliente_id' => $clienteIds[array_rand($clienteIds)],
            'proveedor_id' => $proveedorIds[array_rand($proveedorIds)],
            'usuario_id' => $usuarioIds[array_rand($usuarioIds)],
            'numero_factura' => 'F003-' . now()->format('Ymd') . '-' . rand(1000, 9999),
            'fecha_emision' => now(),
            'fecha_vencimiento' => now()->addDays(45),
            'total' => 9101.23,
            'tipo' => 'venta',
            'estado' => 'Cancelada',
            'codigo_unico' => 'C-' . strtoupper(uniqid()),
            'xml_data' => '<xml></xml>',
        ]);
    }
}
