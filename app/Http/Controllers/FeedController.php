<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comentario;
use App\Models\Like;

class FeedController extends Controller
{
    /**
     * Muestra la vista del feed principal.
     */
    public function index()
    {
        // Traemos los posts con sus relaciones para evitar el problema de N+1 consultas
        $posts = Post::with(['user', 'comentarios.user', 'likes'])
                     ->latest()
                     ->get();

        return view('feed', compact('posts'));
    }

    /**
     * Guarda una nueva publicación en el feed.
     */
    public function store(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
        ]);

        Post::create([
            'user_id' => auth()->id(),
            'contenido' => $request->contenido,
        ]);

        return back()->with('success', 'Tu hazaña ha sido publicada en la taberna.');
    }

    /**
     * Guarda un nuevo comentario en una publicación.
     */
    public function storeComentario(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'contenido' => 'required|string|max:500',
        ]);

        Comentario::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id,
            'contenido' => $request->contenido,
        ]);

        return back()->with('success', 'Comentario añadido.');
    }

    /**
     * Alterna (da o quita) un like polimórfico a un Post o Comentario.
     */
    public function toggleLike(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:App\Models\Post,App\Models\Comentario',
        ]);
        
        $userId = auth()->id();

        // Buscamos si el usuario ya le dio like a este elemento
        $like = Like::where('user_id', $userId)
                    ->where('likeable_id', $request->id)
                    ->where('likeable_type', $request->type)
                    ->first();

        if ($like) {
            // Si ya existe, lo eliminamos (Quitar like)
            $like->delete();
        } else {
            // Si no existe, lo creamos (Dar like)
            Like::create([
                'user_id' => $userId,
                'likeable_id' => $request->id,
                'likeable_type' => $request->type,
            ]);
        }

        return back();
    }
}