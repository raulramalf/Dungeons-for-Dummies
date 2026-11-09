<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * /profile es la antigua pantalla de Breeze: ahora redirige a /perfil, pero los
 * endpoints PATCH/DELETE se conservan y deben funcionar con el modelo Usuario.
 */
class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pantalla_antigua_redirige_al_perfil(): void
    {
        $this->actingAs(Usuario::factory()->create())
            ->get('/profile')
            ->assertRedirect(route('perfil.index'));
    }

    public function test_los_datos_del_perfil_se_pueden_actualizar(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->patch('/profile', ['nombre' => 'Nuevo Nombre', 'email' => 'nuevo@example.com'])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $usuario->refresh();
        $this->assertSame('Nuevo Nombre', $usuario->nombre);
        $this->assertSame('nuevo@example.com', $usuario->email);
        $this->assertNull($usuario->email_verified_at, 'Cambiar el correo invalida su verificación');
    }

    public function test_el_estado_de_verificacion_no_cambia_si_el_correo_es_el_mismo(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->patch('/profile', ['nombre' => 'Otro Nombre', 'email' => $usuario->email])
            ->assertSessionHasNoErrors();

        $this->assertNotNull($usuario->refresh()->email_verified_at);
    }

    public function test_un_usuario_puede_borrar_su_cuenta(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->delete('/profile', ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertSoftDeleted($usuario); // Usuario usa SoftDeletes
    }

    public function test_hay_que_dar_la_contrasena_correcta_para_borrar_la_cuenta(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->from('/perfil')
            ->delete('/profile', ['password' => 'incorrecta'])
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/perfil');

        $this->assertNotNull($usuario->fresh());
    }
}
