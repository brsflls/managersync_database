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
        Schema::create('reportes_generales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade'); // Relación con usuarios
            $table->string('tipo'); // Tipo de reporte (e.g., 'Reporte de Ventas Totales', 'Reporte de Compras Totales')
            $table->date('fecha_inicio'); // Fecha de inicio para el reporte
            $table->date('fecha_fin'); // Fecha de fin para el reporte
            $table->text('contenido')->nullable(); // Contenido del reporte, puede ser texto o referencia a un archivo
            $table->string('formato')->default('PDF'); // Formato del reporte (e.g., PDF, Excel)
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes_generales');
    }
};
