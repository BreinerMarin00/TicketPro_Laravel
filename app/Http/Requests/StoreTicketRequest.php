<?php

namespace App\Http\Requests;

use App\Models\Ticket;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Valida los datos del formulario "Crear nuevo ticket".
 */
class StoreTicketRequest extends FormRequest
{
    /**
     * Solo usuarios autenticados llegan aquí (lo garantiza el middleware auth).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required', 'string', 'max:150'],
            'prioridad' => ['required', Rule::in(Ticket::PRIORIDADES)],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ];
    }

    /**
     * Mensajes de error en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'titulo.required' => 'El título es obligatorio.',
            'titulo.max' => 'El título no puede superar los 150 caracteres.',
            'prioridad.in' => 'La prioridad seleccionada no es válida.',
        ];
    }
}
