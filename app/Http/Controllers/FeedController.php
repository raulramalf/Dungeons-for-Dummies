<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comentario;
use App\Models\Like;
use Illuminate\Support\Facades\Cache;

class FeedController extends Controller
{
    // Salas disponibles en la Taberna
    const SALAS = [
        'general'     => ['label' => 'La Barra',          'desc' => 'Charla libre entre aventureros'],
        'hazanas'     => ['label' => 'Crónicas y Hazañas', 'desc' => 'Cuenta cómo te fue (bien o mal)'],
        'dm'          => ['label' => 'Mesa del DM',        'desc' => 'Sesiones, Resumenes y Notas de Máster'],
        'personajes'  => ['label' => 'Galería de Héroes',  'desc' => 'Enseña tu personaje o pide consejo'],
        'reglas'      => ['label' => 'Consultas al Sabio', 'desc' => 'Pregunta tus dudas de reglas y que te ayuden'],
        'campanas'    => ['label' => 'Forja de Ideas',     'desc' => 'Comparte tus reglas y contenido casero para hacer dnd más divertido'],
    ];

    public function index(Request $request)
    {
        $salaActual = $request->get('sala', 'general');
        if (!array_key_exists($salaActual, self::SALAS)) {
            $salaActual = 'general';
        }

        $etiquetaActual = $request->get('etiqueta');
        $usuarioId      = auth()->id();

        // "Me gusta" del usuario actual como columna (liked_by_me) en la propia
        // consulta: sin un EXISTS por publicación/comentario.
        $likeMio = fn ($q) => $q->where('user_id', $usuarioId);

        $posts = Post::with([
                'usuario',
                'comentarios' => function ($q) use ($likeMio) {
                    $q->whereNull('parent_id')
                        ->with([
                            'usuario',
                            'respuestas' => fn ($r) => $r->without('respuestas')
                                ->with('usuario')
                                ->withCount('likes')
                                ->withExists(['likes as liked_by_me' => $likeMio]),
                        ])
                        ->withCount('likes')
                        ->withExists(['likes as liked_by_me' => $likeMio])
                        ->latest();
                },
            ])
            ->withCount(['likes', 'comentarios'])
            ->withExists(['likes as liked_by_me' => $likeMio])
            ->where('sala', $salaActual)
            ->when($etiquetaActual, fn ($q) => $q->whereJsonContains('etiquetas', $etiquetaActual))
            ->latest()
            ->simplePaginate(10)
            ->withQueryString();

        // "Cargar más": solo las tarjetas, sin el layout
        if ($request->boolean('parcial')) {
            return view('partials.feed-posts', compact('posts', 'salaActual'));
        }

        return view('feed', [
            'posts'          => $posts,
            'salas'          => self::SALAS,
            'salaActual'     => $salaActual,
            'etiquetas'      => $this->etiquetasDeSala($salaActual),
            'etiquetaActual' => $etiquetaActual,
        ]);
    }

    /**
     * Etiquetas usadas en una sala. Antes se leían las de TODAS las publicaciones
     * en cada visita; ahora se cachean 5 min y se invalidan al publicar/borrar.
     */
    private function etiquetasDeSala(string $sala)
    {
        // Se cachea un array plano: Laravel no deserializa objetos (Collection) de la caché
        return collect(Cache::remember("feed.etiquetas.$sala", 300, fn () => Post::where('sala', $sala)
            ->whereNotNull('etiquetas')
            ->pluck('etiquetas')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->all()));
    }

    /** Máx. 8 etiquetas de texto, de hasta 30 caracteres y sin duplicados. */
    private function limpiarEtiquetas(?string $json): array
    {
        return collect(json_decode($json ?? '[]', true) ?: [])
            ->filter(fn ($t) => is_string($t))
            ->map(fn ($t) => mb_substr(trim($t), 0, 30))
            ->filter()
            ->unique()
            ->take(8)
            ->values()
            ->all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
            'etiquetas' => 'nullable|json',
            'sala'      => 'nullable|string',
        ]);

        $sala = $request->sala;
        if (!array_key_exists($sala, self::SALAS)) {
            $sala = 'general';
        }

        Post::create([
            'user_id'   => auth()->id(),
            'contenido' => $request->contenido,
            'etiquetas' => $this->limpiarEtiquetas($request->etiquetas),
            'sala'      => $sala,
        ]);

        Cache::forget("feed.etiquetas.$sala");

        return back()->with('success', '¡Tu hazaña ha sido publicada en la taberna!');
    }

    public function destroy(Post $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403, 'No puedes borrar publicaciones ajenas.');
        }

        $comentarioIds = $post->comentarios()->pluck('id');

        Like::where('likeable_type', Post::class)->where('likeable_id', $post->id)->delete();
        if ($comentarioIds->isNotEmpty()) {
            Like::where('likeable_type', Comentario::class)->whereIn('likeable_id', $comentarioIds)->delete();
        }

        $post->delete();
        Cache::forget("feed.etiquetas.{$post->sala}");

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Publicación eliminada.');
    }

    public function storeComentario(Request $request)
    {
        $request->validate([
            'post_id'   => 'required|exists:posts,id',
            'contenido' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comentarios,id',
        ]);

        Comentario::create([
            'user_id'   => auth()->id(),
            'post_id'   => $request->post_id,
            'parent_id' => $request->parent_id,
            'contenido' => $request->contenido,
        ]);

        return back()->with('success', 'Comentario añadido correctamente.');
    }

    public function destroyComentario(Comentario $comentario)
    {
        if ($comentario->user_id !== auth()->id()) {
            abort(403, 'No puedes borrar comentarios ajenos.');
        }

        $comentario->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Comentario eliminado.');
    }

    public function toggleLike(Request $request)
    {
        $request->validate([
            'id'   => 'required|integer',
            'type' => 'required|string|in:App\Models\Post,App\Models\Comentario',
        ]);

        $userId = auth()->id();

        $like = Like::where('user_id', $userId)
                    ->where('likeable_id', $request->id)
                    ->where('likeable_type', $request->type)
                    ->first();

        if ($like) {
            $like->delete();
            $liked   = false;
            $message = 'Like eliminado';
        } else {
            Like::create([
                'user_id'       => $userId,
                'likeable_id'   => $request->id,
                'likeable_type' => $request->type,
            ]);
            $liked   = true;
            $message = 'Like añadido';
        }

        $count = Like::where('likeable_id', $request->id)
                     ->where('likeable_type', $request->type)
                     ->count();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count'   => $count,
                'liked'   => $liked,
                'message' => $message,
            ]);
        }

        return back();
    }
    
}