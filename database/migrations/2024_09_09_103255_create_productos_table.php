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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->integer('codigo_producto'); // Código
            $table->integer('codigo_cabys'); // Código CABYS
            $table->string('nombre'); // Nombre del producto
            $table->text('descripcion'); // Descripción del producto
            $table->decimal('precio_consumidor', 10, 2); // Precio del producto en colones
            $table->decimal('precio_compra', 10, 2); // Precio del producto en colones
            $table->integer('stock'); // Cantidad disponible en inventario
            $table->string('unidad_medida'); // Unidad de medida del producto
            $table->decimal('peso_por_unidad', 10, 2); // Peso por unidad
            $table->decimal('peso_neto', 10, 2)->nullable(); // Peso neto calculado
            $table->decimal('porcentaje_descuento', 5, 2)->nullable(); // Porcentaje de descuento
            $table->decimal('monto_descuento', 10, 2)->nullable(); // Monto de descuento aplicado
            $table->decimal('porcentaje_iva', 5, 2)->nullable(); // Porcentaje de IVA
            $table->decimal('monto_iva', 10, 2)->nullable(); // Monto de IVA aplicado
            $table->decimal('precio_neto', 10, 2)->nullable(); // Precio final después de aplicar descuento e IVA
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
