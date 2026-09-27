<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Ticket.
 *
 * Representa una solicitud de soporte creada por un usuario.
 */
class Ticket extends Model
{
    /** Prioridades permitidas. */
    public const PRIORIDADES = ['Baja', 'Media', 'Alta'];

    /** Estados permitidos, en el orden en que avanza un ticket. */
    public const ESTADOS = ['Abierto', 'En proceso', 'Resuelto'];

    /**
     * Campos que se pueden asignar de forma masiva.
     *
     * @var list<string>
     */
    protected $fillable = [
        'titulo',
        'descripcion',
        'prioridad',
        'estado',
    ];

    /**
     * Valores por defecto de un ticket nuevo.
     *
     * @var array<string, string>
     */
    protected $attributes = [
        'prioridad' => 'Media',
        'estado' => 'Abierto',
    ];

    /**
     * Relación inversa: el ticket pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class);
    }

    /**
     * Filtra los tickets pendientes (Abierto o En proceso).
     */
    public function scopeAbiertos(Builder $query): Builder
    {
        return $query->whereIn('estado', ['Abierto', 'En proceso']);
    }

    /**
     * Filtra los tickets que ya fueron resueltos.
     */
    public function scopeResueltos(Builder $query): Builder
    {
        return $query->where('estado', 'Resuelto');
    }

    /**
     * Devuelve el siguiente estado del ciclo:
     * Abierto -> En proceso -> Resuelto -> Abierto.
     */
    public function siguienteEstado(): string
    {
        return match ($this->estado) {
            'Abierto' => 'En proceso',
            'En proceso' => 'Resuelto',
            default => 'Abierto',
        };
    }

    /**
     * Clase CSS del badge según el estado del ticket.
     */
    public function claseEstado(): string
    {
        return match ($this->estado) {
            'En proceso' => 'badge-progress',
            'Resuelto' => 'badge-resolved',
            default => 'badge-open',
        };
    }

    /**
     * Clase CSS del badge según la prioridad del ticket.
     */
    public function clasePrioridad(): string
    {
        return 'badge-priority-'.strtolower($this->prioridad);
    }
}
