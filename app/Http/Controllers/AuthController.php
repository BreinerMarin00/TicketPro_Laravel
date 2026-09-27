<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controlador del módulo de autenticación (inicio y cierre de sesión).
 */
class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Procesa el inicio de sesión.
     *
     * El usuario puede escribir su nombre de usuario o su correo:
     * si el texto tiene formato de correo se busca por "email",
     * de lo contrario por "username".
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $usuario = trim($request->input('usuario'));
        $campo = filter_var($usuario, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credenciales = [
            $campo => $usuario,
            'password' => $request->input('password'),
        ];

        // Auth::attempt verifica la contraseña cifrada (bcrypt) contra la base de datos.
        if (! Auth::attempt($credenciales, $request->boolean('recordarme'))) {
            return back()
                ->withErrors(['usuario' => 'Usuario o contraseña incorrectos.'])
                ->onlyInput('usuario');
        }

        // Se regenera el id de sesión para evitar ataques de fijación de sesión.
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Cierra la sesión del usuario y lo devuelve al login.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
