<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->onDelete('set null'); // Relación con clientes
            $table->foreignId('proveedor_id')->nullable()->constrained('proveedors')->onDelete('set null'); // Relación con proveedores
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); // Relación con usuarios
            $table->string('numero_factura')->unique(); // Número único de la factura
            $table->timestamp('fecha_emision'); // Fecha de emisión de la factura
            $table->timestamp('fecha_vencimiento')->nullable(); // Fecha de vencimiento (opcional)
            $table->decimal('total', 12, 2); // Total de la factura con mayor precisión
            $table->enum('tipo', ['venta', 'compra']); // Tipo de factura: venta o compra
            $table->enum('estado', ['Emitida', 'Pagada', 'Cancelada'])->default('Emitida'); // Estado de la factura
            $table->string('codigo_unico', 50)->unique(); // Código único de la factura (para integración con el Ministerio de Hacienda)
            $table->text('xml_data')->nullable(); // Almacenamiento opcional de datos XML para la facturación electrónica
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
