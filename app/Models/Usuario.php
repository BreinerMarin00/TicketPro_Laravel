<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Modelo Usuario.
 *
 * Representa a la persona que inicia sesión en TicketPro y registra
 * solicitudes de soporte. Extiende de Authenticatable para poder usar
 * el sistema de autenticación de Laravel (Auth::attempt, middleware auth).
 */
class Usuario extends Authenticatable
{
    /**
     * Nombre de la tabla en la base de datos.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Campos que se pueden asignar de forma masiva.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre_completo',
        'email',
        'username',
        'password',
    ];

    /**
     * Campos que nunca se muestran al convertir el modelo a arreglo o JSON.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos: la contraseña se cifra automáticamente al guardarla.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Relación uno a muchos: un usuario tiene muchos tickets.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
