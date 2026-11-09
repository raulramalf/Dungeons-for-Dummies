<?php

namespace Tests\Feature;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FeedTest extends TestCase
{
    use RefreshDatabase;

    private function postsConComentarios(int $posts, int $comentariosPorPost): void
    {
        $usuarios = Usuario::factory()->count(3)->create();

        for ($i = 1; $i <= $posts; $i++) {
            $post = Post::create([
                'user_id' => $usuarios[$i % 3]->id,
                'contenido' => "Publicación $i",
                'etiquetas' => ['prueba'],
                'sala' => 'general',
            ]);

            for ($c = 0; $c < $comentariosPorPost; $c++) {
                $raiz = Comentario::create([
                    'user_id' => $usuarios[($i + $c) % 3]->id,
                    'post_id' => $post->id,
                    'contenido' => "Comentario $c",
                ]);
                Comentario::create([
                    'user_id' => $usuarios[($i + $c + 1) % 3]->id,
                    'post_id' => $post->id,
                    'parent_id' => $raiz->id,
                    'contenido' => 'Respuesta',
                ]);
            }
        }
    }

    private function consultas(callable $accion): int
    {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $accion();
        $n = count(DB::getQueryLog());
        DB::disableQueryLog();

        return $n;
    }

    public function test_la_taberna_pagina_de_diez_en_diez(): void
    {
        $this->postsConComentarios(14, 0);
        $usuario = Usuario::factory()->create();

        $primera = $this->actingAs($usuario)->get('/feed');
        $primera->assertOk();
        $this->assertSame(10, substr_count($primera->getContent(), '<article class="feed-card"'));
        $primera->assertSee('data-feed-mas', false);

        $segunda = $this->actingAs($usuario)->get('/feed?page=2&parcial=1');
        $segunda->assertOk()->assertDontSee('<html', false);
        $this->assertSame(4, substr_count($segunda->getContent(), '<article class="feed-card"'));
        $segunda->assertDontSee('data-feed-mas', false);
    }

    public function test_las_consultas_no_crecen_con_el_numero_de_comentarios(): void
    {
        $usuario = Usuario::factory()->create();

        $this->actingAs($usuario)->get('/feed'); // calienta la caché de etiquetas (1 consulta extra solo la 1.ª vez)

        $this->postsConComentarios(10, 1);
        $pocas = $this->consultas(fn () => $this->actingAs($usuario)->get('/feed')->assertOk());

        $this->postsConComentarios(10, 5); // muchos más comentarios y respuestas
        $muchas = $this->consultas(fn () => $this->actingAs($usuario)->get('/feed')->assertOk());

        $this->assertSame($pocas, $muchas, "Hay N+1: $pocas consultas con pocos comentarios y $muchas con muchos");
        $this->assertLessThanOrEqual(15, $muchas);
    }

    public function test_cada_comentario_muestra_el_avatar_de_su_autor(): void
    {
        $autorPost = Usuario::factory()->create(['nombre' => 'Zeta']);
        $autorComentario = Usuario::factory()->create(['nombre' => 'Omar']);
        $post = Post::create(['user_id' => $autorPost->id, 'contenido' => 'Hola', 'sala' => 'general']);
        Comentario::create(['user_id' => $autorComentario->id, 'post_id' => $post->id, 'contenido' => 'Buenas']);

        $html = $this->actingAs($autorPost)->get('/feed')->getContent();
        preg_match('/comment-meta">\s*<img[^>]*src="([^"]+)"/', $html, $m);

        $this->assertNotEmpty($m, 'No se encontró el avatar del comentario');
        $this->assertStringContainsString('>O<', rawurldecode(html_entity_decode($m[1])), 'Debe ser el marcador de Omar, no el de Zeta');
    }

    public function test_las_etiquetas_se_limitan_y_se_sanean_al_publicar(): void
    {
        $usuario = Usuario::factory()->create();
        $etiquetas = array_merge(
            array_map(fn ($i) => "etiqueta-$i", range(1, 12)),
            [str_repeat('x', 100), ['no-es-texto'], 123],
        );

        $this->actingAs($usuario)->post('/feed', [
            'contenido' => 'Con etiquetas',
            'sala' => 'general',
            'etiquetas' => json_encode($etiquetas),
        ])->assertRedirect();

        $guardadas = Post::firstOrFail()->etiquetas;
        $this->assertCount(8, $guardadas);
        $this->assertSame(array_map(fn ($i) => "etiqueta-$i", range(1, 8)), $guardadas);
    }

    public function test_el_contenido_del_usuario_se_escapa(): void
    {
        $usuario = Usuario::factory()->create();
        Post::create(['user_id' => $usuario->id, 'contenido' => '<script>alert(1)</script>', 'sala' => 'general']);

        $this->actingAs($usuario)->get('/feed')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }
}
