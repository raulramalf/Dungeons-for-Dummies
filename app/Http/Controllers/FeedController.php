<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comentario;
use App\Models\Like;

class FeedController extends Controller
{
    // Salas disponibles en la Taberna
    const SALAS = [
        'general'     => ['label' => 'Barra Principal',    'desc' => 'Conversación libre para todo aventurero'],
        'hazanas'     => ['label' => 'Sala de Hazañas',    'desc' => 'Comparte tus victorias (y tus derrotas épicas)'],
        'dm'          => ['label' => 'Rincón del DM',       'desc' => 'Consejos, mapas y lamentos de Dungeon Masters'],
        'personajes'  => ['label' => 'Mesa de Personajes',  'desc' => 'Presenta tu héroe o pide ayuda para crearlo'],
        'reglas'      => ['label' => 'Archivo de Reglas',   'desc' => 'Dudas, aclaraciones y debates de reglamento'],
        'campanas'    => ['label' => 'Sala de Campañas',    'desc' => 'Busca grupo, comparte tu campaña o pide jugadores'],
    ];

    public function index(Request $request)
    {
        $salaActual = $request->get('sala', 'general');
        if (!array_key_exists($salaActual, self::SALAS)) {
            $salaActual = 'general';
        }

        $posts = Post::with([
                'usuario',
                'comentarios' => function ($query) {
                    $query->whereNull('parent_id')
                          ->with(['usuario', 'respuestas.usuario', 'likes'])
                          ->latest();
                },
                'likes',
            ])
            ->withCount('likes')
            ->where('sala', $salaActual)
            ->latest()
            ->get();

        return view('feed', [
            'posts'      => $posts,
            'salas'      => self::SALAS,
            'salaActual' => $salaActual,
        ]);
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
            'etiquetas' => json_decode($request->etiquetas, true) ?? [],
            'sala'      => $sala,
        ]);

        return back()->with('success', '¡Tu hazaña ha sido publicada en la taberna!');
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