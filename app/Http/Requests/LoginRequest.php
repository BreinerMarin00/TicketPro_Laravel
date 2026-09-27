<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Valida los datos del formulario de inicio de sesión.
 */
class LoginRequest extends FormRequest
{
    /**
     * Cualquier visitante puede intentar iniciar sesión.
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
            'usuario' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string'],
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
            'usuario.required' => 'Por favor ingresa tu usuario o correo.',
            'password.required' => 'Por favor ingresa tu contraseña.',
        ];
    }
}
