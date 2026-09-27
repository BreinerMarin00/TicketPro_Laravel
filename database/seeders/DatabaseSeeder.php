<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Seeder;

/**
 * Carga datos de prueba para poder usar la aplicación de inmediato.
 *
 * Usuario de prueba: admin / admin123 (también sirve admin@ticketpro.com).
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Ejecuta el seeder.
     */
    public function run(): void
    {
        $usuario = Usuario::create([
            'nombre_completo' => 'Administrador TicketPro',
            'email' => 'admin@ticketpro.com',
            'username' => 'admin',
            'password' => 'admin123', // Se cifra automáticamente por el cast "hashed".
        ]);

        // Tickets de ejemplo para ver el panel con información.
        $usuario->tickets()->createMany([
            [
                'titulo' => 'No funciona el acceso al correo',
                'descripcion' => 'Al abrir Outlook aparece un error de credenciales.',
                'prioridad' => 'Alta',
                'estado' => 'Abierto',
            ],
            [
                'titulo' => 'Impresora del segundo piso sin conexión',
                'descripcion' => 'La impresora no aparece en la red desde ayer.',
                'prioridad' => 'Media',
                'estado' => 'En proceso',
            ],
            [
                'titulo' => 'Solicitud de instalación de software',
                'descripcion' => 'Se requiere instalar Visual Studio Code en el equipo 12.',
                'prioridad' => 'Baja',
                'estado' => 'Resuelto',
            ],
        ]);
    }
}
