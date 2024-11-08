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
        Schema::create('proveedors', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('cedula_juridica', 12)->unique();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade'); // Relación con empresas

            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
