<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador de la pantalla principal (Panel de Tickets).
 */
class DashboardController extends Controller
{
    /** Filtros disponibles en la barra de la tabla. */
    private const FILTROS = ['todos', 'abiertos', 'resueltos'];

    /**
     * Muestra el panel con los contadores y la lista de tickets del usuario.
     *
     * El filtro llega por la URL, por ejemplo: /dashboard?filtro=abiertos
     */
    public function index(Request $request): View
    {
        $usuario = $request->user();

        $filtro = $request->query('filtro', 'todos');
        if (! in_array($filtro, self::FILTROS, true)) {
            $filtro = 'todos';
        }

        // Consulta base: solo los tickets del usuario autenticado.
        $consulta = $usuario->tickets()->latest('id');

        if ($filtro === 'abiertos') {
            $consulta->abiertos();
        } elseif ($filtro === 'resueltos') {
            $consulta->resueltos();
        }

        // Contadores de las tarjetas de resumen.
        $contadores = [
            'total' => $usuario->tickets()->count(),
            'abiertos' => $usuario->tickets()->abiertos()->count(),
            'resueltos' => $usuario->tickets()->resueltos()->count(),
        ];

        return view('dashboard', [
            'usuario' => $usuario,
            'tickets' => $consulta->get(),
            'contadores' => $contadores,
            'filtro' => $filtro,
        ]);
    }
}
