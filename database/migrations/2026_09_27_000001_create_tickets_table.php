<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración de la tabla "tickets".
 *
 * Cada ticket pertenece a un usuario y tiene una prioridad y un estado.
 */
return new class extends Migration
{
    /**
     * Crea la tabla tickets.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            // Si se elimina el usuario, también se eliminan sus tickets.
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->enum('prioridad', ['Baja', 'Media', 'Alta'])->default('Media');
            $table->enum('estado', ['Abierto', 'En proceso', 'Resuelto'])->default('Abierto');
            $table->timestamps();
        });
    }

    /**
     * Elimina la tabla tickets.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
