<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_hay_que_iniciar_sesion_para_ver_el_perfil(): void
    {
        $this->get('/perfil')->assertRedirect('/login');
    }

    public function test_el_perfil_muestra_los_datos_del_usuario(): void
    {
        $usuario = Usuario::factory()->create(['nombre' => 'Aventurero', 'rol' => 'dungeon_master']);

        $this->actingAs($usuario)->get('/perfil')
            ->assertOk()
            ->assertSee('Aventurero')
            ->assertSee('Dungeon master') // el rol se muestra sin guion bajo
            ->assertSee($usuario->email);
    }

    public function test_se_pueden_actualizar_nombre_y_correo(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->patch('/perfil/actualizar', ['nombre' => 'Nuevo', 'email' => 'nuevo@example.com'])
            ->assertRedirect('/perfil')
            ->assertSessionHas('success');

        $this->assertSame('Nuevo', $usuario->fresh()->nombre);
    }

    public function test_el_correo_no_puede_pertenecer_a_otro_usuario(): void
    {
        $otro = Usuario::factory()->create();
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->patch('/perfil/actualizar', ['nombre' => 'X', 'email' => $otro->email])
            ->assertSessionHasErrors('email');
    }

    public function test_se_puede_cambiar_la_contrasena(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->patch('/perfil/password', [
                'current_password' => 'password',
                'password' => 'Nueva-clave-segura-123',
                'password_confirmation' => 'Nueva-clave-segura-123',
            ])
            ->assertSessionHasNoErrors();

        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('Nueva-clave-segura-123', $usuario->fresh()->password));
    }

    public function test_la_contrasena_actual_debe_ser_correcta(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)
            ->patch('/perfil/password', [
                'current_password' => 'incorrecta',
                'password' => 'Nueva-clave-segura-123',
                'password_confirmation' => 'Nueva-clave-segura-123',
            ])
            ->assertSessionHasErrors('current_password');
    }
}
