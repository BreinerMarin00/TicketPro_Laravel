<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas del módulo de pantalla principal (Panel de Tickets).
 */
class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crea un usuario de prueba con el nombre de usuario indicado.
     */
    private function crearUsuario(string $username = 'prueba'): Usuario
    {
        return Usuario::create([
            'nombre_completo' => 'Usuario '.$username,
            'email' => $username.'@ticketpro.com',
            'username' => $username,
            'password' => 'secreto123',
        ]);
    }

    public function test_un_visitante_sin_sesion_es_enviado_al_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_muestra_el_panel_con_contadores_y_tickets(): void
    {
        $usuario = $this->crearUsuario();
        $usuario->tickets()->createMany([
            ['titulo' => 'Ticket abierto', 'estado' => 'Abierto'],
            ['titulo' => 'Ticket resuelto', 'estado' => 'Resuelto'],
        ]);

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Panel de Tickets')
            ->assertSee('Hola,')
            ->assertSee('prueba')
            ->assertSee('Ticket abierto')
            ->assertViewHas('contadores', ['total' => 2, 'abiertos' => 1, 'resueltos' => 1]);
    }

    public function test_filtra_los_tickets_resueltos(): void
    {
        $usuario = $this->crearUsuario();
        $usuario->tickets()->createMany([
            ['titulo' => 'Ticket abierto', 'estado' => 'Abierto'],
            ['titulo' => 'Ticket resuelto', 'estado' => 'Resuelto'],
        ]);

        $this->actingAs($usuario)
            ->get('/dashboard?filtro=resueltos')
            ->assertSee('Ticket resuelto')
            ->assertDontSee('Ticket abierto');
    }

    public function test_crea_un_ticket_nuevo(): void
    {
        $usuario = $this->crearUsuario();

        $this->actingAs($usuario)
            ->post('/tickets', ['titulo' => 'Falla en la red', 'prioridad' => 'Alta', 'descripcion' => ''])
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tickets', [
            'usuario_id' => $usuario->id,
            'titulo' => 'Falla en la red',
            'prioridad' => 'Alta',
            'estado' => 'Abierto',
            'descripcion' => 'Sin descripción adicional.',
        ]);
    }

    public function test_el_titulo_del_ticket_es_obligatorio(): void
    {
        $this->actingAs($this->crearUsuario())
            ->post('/tickets', ['titulo' => '', 'prioridad' => 'Media'])
            ->assertSessionHasErrors('titulo');
    }

    public function test_cambia_el_estado_del_ticket(): void
    {
        $usuario = $this->crearUsuario();
        $ticket = $usuario->tickets()->create(['titulo' => 'Ticket']);

        $this->actingAs($usuario)->patch("/tickets/{$ticket->id}/estado");

        $this->assertSame('En proceso', $ticket->fresh()->estado);
    }

    public function test_elimina_un_ticket(): void
    {
        $usuario = $this->crearUsuario();
        $ticket = $usuario->tickets()->create(['titulo' => 'Ticket']);

        $this->actingAs($usuario)->delete("/tickets/{$ticket->id}");

        $this->assertDatabaseMissing('tickets', ['id' => $ticket->id]);
    }

    public function test_no_puede_eliminar_tickets_de_otro_usuario(): void
    {
        $dueno = $this->crearUsuario('dueno');
        $ticket = $dueno->tickets()->create(['titulo' => 'Ticket ajeno']);

        $this->actingAs($this->crearUsuario('otro'))
            ->delete("/tickets/{$ticket->id}")
            ->assertForbidden();

        $this->assertDatabaseHas('tickets', ['id' => $ticket->id]);
    }
}
