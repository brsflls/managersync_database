<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas_compras', function (Blueprint $table) {
            $table->id();
            
            // Datos del inicio (InicioData)
            $table->string('condicion_venta'); // Contado / Crédito
            $table->string('moneda'); // Colones / Dólares
            $table->integer('plazo')->nullable();
            $table->decimal('tipo_cambio', 10, 2)->nullable();
            $table->text('observacion')->nullable();
            $table->string('tipo_compra'); // Compra Deducible / No Deducible
            
            // Datos del Emisor (EmisorData)
            $table->string('identificacion')->index(); // Para mejorar el rendimiento en las búsquedas
            
            // Cedula Física/Jurídica
            $table->string('tipo_identificacion');
            $table->string('nombre');
            $table->string('telefono')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('direccion_exacta')->nullable();
            $table->string('provincia')->nullable();
            $table->string('canton')->nullable();
            $table->string('distrito')->nullable();
            $table->string('barrio')->nullable();
            
            // Totales del Documento (Totales)
            $table->decimal('sub_total', 15, 2);
            $table->decimal('impuestos', 15, 2)->nullable();
            $table->decimal('descuentos', 15, 2)->nullable();
            $table->decimal('total', 15, 2);

            // Archivo adjunto (opcional)
            $table->string('archivo')->nullable(); // PDF o imagen adjunta
            
            // Exoneración (ExoneraData)
            $table->string('numero_exoneracion')->nullable();
            $table->date('fecha_emision_exoneracion')->nullable();
            $table->string('tipo_exoneracion')->nullable();
            $table->string('porcentaje_exoneracion')->nullable();
            $table->string('nombre_institucion_exoneracion')->nullable();
            
            // Fecha de creación del documento
            $table->timestamp('fecha_creacion')->useCurrent();
            
            // Relación de productos/servicios (en una tabla aparte)
            $table->timestamps();
        });

        // Crear una tabla aparte para los productos/servicios asociados a la factura/compra
        Schema::create('factura_productos_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas_compras')->onDelete('cascade');
            $table->string('codigo');
            $table->string('descripcion');
            $table->integer('cantidad');
            $table->decimal('precio_bruto', 15, 2);
            $table->decimal('porcentaje_descuento', 5, 2)->nullable();
            $table->decimal('porcentaje_iva', 5, 2)->nullable();
            $table->boolean('servicio')->default(false); // Si es un servicio
            
            $table->timestamps();
        });

        // Crear una tabla aparte para las referencias asociadas a la factura/compra
        Schema::create('factura_referencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('facturas_compras')->onDelete('cascade');
            $table->string('tipo_documento');
            $table->string('numero_documento');
            $table->date('fecha_documento');
            $table->string('tipo_referencia');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('factura_referencias');
        Schema::dropIfExists('factura_productos_servicios');
        Schema::dropIfExists('facturas_compras');
    }
};
