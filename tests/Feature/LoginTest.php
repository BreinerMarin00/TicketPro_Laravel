<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pruebas del módulo de inicio de sesión.
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Crea un usuario de prueba.
     */
    private function crearUsuario(): Usuario
    {
        return Usuario::create([
            'nombre_completo' => 'Usuario Prueba',
            'email' => 'prueba@ticketpro.com',
            'username' => 'prueba',
            'password' => 'secreto123',
        ]);
    }

    public function test_la_pantalla_de_login_se_muestra(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertSee('Iniciar sesión')
            ->assertSee('Tu soporte, nuestra prioridad');
    }

    public function test_puede_iniciar_sesion_con_nombre_de_usuario(): void
    {
        $usuario = $this->crearUsuario();

        $this->post('/login', ['usuario' => 'prueba', 'password' => 'secreto123'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($usuario);
    }

    public function test_puede_iniciar_sesion_con_correo(): void
    {
        $usuario = $this->crearUsuario();

        $this->post('/login', ['usuario' => 'prueba@ticketpro.com', 'password' => 'secreto123'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($usuario);
    }

    public function test_no_inicia_sesion_con_contrasena_incorrecta(): void
    {
        $this->crearUsuario();

        $this->from('/login')
            ->post('/login', ['usuario' => 'prueba', 'password' => 'incorrecta'])
            ->assertRedirect('/login')
            ->assertSessionHasErrors('usuario');

        $this->assertGuest();
    }

    public function test_los_campos_son_obligatorios(): void
    {
        $this->post('/login', [])
            ->assertSessionHasErrors(['usuario', 'password']);
    }

    public function test_puede_cerrar_sesion(): void
    {
        $this->actingAs($this->crearUsuario())
            ->post('/logout')
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }
}
