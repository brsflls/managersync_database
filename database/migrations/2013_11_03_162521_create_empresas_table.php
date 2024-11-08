<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique(); // Nombre de la empresa
            $table->string('telefono')->nullable(); // Teléfono de contacto
            $table->string('correo')->nullable(); // Correo de contacto
            $table->string('cedula_empresa')->unique(); // Cédula de la empresa (física o jurídica)
            $table->string('codigo_actividad')->unique();
            $table->string('descripcion')->nullable(); // Descripción de la empresa
            $table->enum('empresa', ['fisica', 'extranjera', 'juridica'])->default('juridica')->nullable(); // Tipo de empresa

            // Campos para la dirección
            $table->string('provincia')->nullable(); // Provincia
            $table->string('canton')->nullable(); // Cantón
            $table->string('distrito')->nullable(); // Distrito
            $table->text('otras_senas')->nullable(); // Otras señas

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
