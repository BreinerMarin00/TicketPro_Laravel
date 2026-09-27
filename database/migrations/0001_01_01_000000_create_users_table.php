<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración de la tabla "usuarios" de TicketPro.
 *
 * Replica la estructura de la tabla original del proyecto en PHP nativo
 * (database/ticketpro.sql) y agrega las tablas que Laravel necesita para
 * manejar las sesiones y la recuperación de contraseñas.
 */
return new class extends Migration
{
    /**
     * Crea las tablas.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 100);
            $table->string('email', 100)->unique();
            $table->string('username', 50)->unique();
            $table->string('password');
            // Token usado por la opción "Recordarme" del inicio de sesión.
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Elimina las tablas.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
