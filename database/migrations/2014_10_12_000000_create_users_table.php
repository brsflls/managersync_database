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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Nombre del usuario
            $table->string('email')->unique(); // Correo electrónico del usuario, debe ser único
            $table->string('cedula', 12)->unique(); // Cédula de identidad del contador (opcional)
          
            $table->enum('role', ['admin', 'contador', 'empleado'])->default('admin'); // Rol del usuario
          
            $table->string('profile_image')->nullable(); // Imagen de perfil
            $table->timestamp('email_verified_at')->nullable(); // Verificación del correo electrónico
            $table->string('password'); // Contraseña del usuario
            $table->rememberToken(); // Token para recordar sesión
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade'); // Relación con empresa
            $table->timestamps(); // Timestamps para created_at y updated_at
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Correo electrónico del usuario
            $table->string('token'); // Token de restablecimiento de contraseña
            $table->timestamp('created_at')->nullable(); // Fecha de creación del token
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Identificador de la sesión
            $table->foreignId('user_id')->nullable()->index(); // ID del usuario asociado a la sesión
            $table->string('ip_address', 45)->nullable(); // Dirección IP del usuario
            $table->text('user_agent')->nullable(); // Agente de usuario (navegador, etc.)
            $table->longText('payload'); // Datos de la sesión
            $table->integer('last_activity')->index(); // Última actividad
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions'); // Primero elimina 'sessions' debido a las referencias
        Schema::dropIfExists('password_reset_tokens'); // Luego elimina 'password_reset_tokens'
        Schema::dropIfExists('usuarios'); // Finalmente elimina 'usuarios'
    }
};
