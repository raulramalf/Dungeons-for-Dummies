<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comentario;
use App\Models\Like;

class FeedController extends Controller
{
    public function index()
    {
        $posts = Post::with(['usuario', 'comentarios' => function($query) {
                $query->whereNull('parent_id')->with(['usuario', 'respuestas.usuario', 'likes']);
            }, 'likes'])
            ->withCount('likes')
            ->latest()
            ->get();

        return view('feed', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string|max:1000',
            'etiquetas' => 'nullable|json',
        ]);

        $etiquetas = json_decode($request->etiquetas, true) ?? [];

        // OBTENER EL USUARIO ACTUAL O USAR EL PRIMERO DISPONIBLE
        $userId = auth()->id();
        
        // Si no hay usuario autenticado, usar el primer usuario de la base de datos
        if (!$userId) {
            $firstUser = \App\Models\Usuario::first();
            if ($firstUser) {
                $userId = $firstUser->id;
            } else {
                // Si no hay usuarios, crear uno por defecto
                $user = \App\Models\Usuario::create([
                    'nombre' => 'Aventurero Anónimo',
                    'email' => 'anonimo@dungeons.com',
                    'password' => bcrypt('password'),
                    'rol' => 'jugador',
                ]);
                $userId = $user->id;
            }
        }

        Post::create([
            'user_id' => $userId,
            'contenido' => $request->contenido,
            'etiquetas' => $etiquetas,
        ]);

        return back()->with('success', '¡Tu hazaña ha sido publicada en la taberna!');
    }

    public function storeComentario(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'contenido' => 'required|string|max:500',
            'parent_id' => 'nullable|exists:comentarios,id',
        ]);

        // OBTENER EL USUARIO ACTUAL O USAR EL PRIMERO DISPONIBLE
        $userId = auth()->id();
        
        if (!$userId) {
            $firstUser = \App\Models\Usuario::first();
            $userId = $firstUser ? $firstUser->id : 1;
        }

        Comentario::create([
            'user_id' => $userId,
            'post_id' => $request->post_id,
            'parent_id' => $request->parent_id,
            'contenido' => $request->contenido,
        ]);

        return back()->with('success', 'Comentario añadido correctamente.');
    }

    public function toggleLike(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'type' => 'required|string|in:App\Models\Post,App\Models\Comentario',
        ]);
        
        $userId = auth()->id();
        
        if (!$userId) {
            $firstUser = \App\Models\Usuario::first();
            $userId = $firstUser ? $firstUser->id : 1;
        }

        $like = Like::where('user_id', $userId)
                    ->where('likeable_id', $request->id)
                    ->where('likeable_type', $request->type)
                    ->first();

        if ($like) {
            $like->delete();
            $message = 'Like eliminado';
        } else {
            Like::create([
                'user_id' => $userId,
                'likeable_id' => $request->id,
                'likeable_type' => $request->type,
            ]);
            $message = 'Like añadido';
        }

        if ($request->ajax()) {
            $count = Like::where('likeable_id', $request->id)
                        ->where('likeable_type', $request->type)
                        ->count();
            return response()->json(['success' => true, 'count' => $count, 'message' => $message]);
        }

        return back();
    }
}