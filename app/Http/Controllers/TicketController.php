<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTicketRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Controlador de las acciones sobre tickets desde el panel principal:
 * crear, cambiar de estado y eliminar.
 */
class TicketController extends Controller
{
    /**
     * Guarda un ticket nuevo para el usuario autenticado.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $datos = $request->validated();
        $datos['descripcion'] = ($datos['descripcion'] ?? null) ?: 'Sin descripción adicional.';

        $request->user()->tickets()->create($datos);

        return redirect()->route('dashboard')->with('exito', 'Ticket creado correctamente.');
    }

    /**
     * Avanza el ticket al siguiente estado (Abierto -> En proceso -> Resuelto).
     */
    public function cambiarEstado(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->verificarPropietario($request, $ticket);

        $ticket->update(['estado' => $ticket->siguienteEstado()]);

        return back();
    }

    /**
     * Elimina un ticket del usuario.
     */
    public function destroy(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->verificarPropietario($request, $ticket);

        $ticket->delete();

        return back()->with('exito', 'Ticket eliminado.');
    }

    /**
     * Impide que un usuario modifique tickets de otra persona.
     */
    private function verificarPropietario(Request $request, Ticket $ticket): void
    {
        abort_if((int) $ticket->usuario_id !== (int) $request->user()->id, 403);
    }
}
