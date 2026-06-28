@extends('layouts.app')

@section('titulo', 'La Taberna')

@section('contenido')
<style>
    .feed-container {
        max-width: 800px;
        margin: 0 auto;
    }
    .card {
        background-color: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(179, 3, 3, 0.2);
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .form-control {
        width: 100%;
        padding: 1rem;
        background-color: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--color-gris);
        color: #fff;
        border-radius: 5px;
        font-family: inherit;
        resize: vertical;
        box-sizing: border-box;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--color-rojo);
    }
    .btn-submit {
        background-color: var(--color-rojo);
        color: white;
        padding: 0.5rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: bold;
        margin-top: 1rem;
        float: right;
        font-family: inherit;
    }
    .btn-submit:hover {
        background-color: #8a0202;
    }
    .post-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
        color: var(--color-gris);
        font-size: 0.9rem;
    }
    .post-author {
        font-weight: bold;
        color: var(--color-naranja);
        font-size: 1.1rem;
    }
    .post-content {
        font-size: 1.1rem;
        line-height: 1.5;
        margin-bottom: 1.5rem;
    }
    .btn-like {
        background: none;
        border: none;
        color: var(--color-gris);
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: inherit;
    }
    .btn-like:hover {
        color: var(--color-rojo);
    }
    .comments-section {
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(118, 133, 150, 0.2);
    }
    .comment-item {
        background-color: rgba(0, 0, 0, 0.2);
        padding: 1rem;
        border-radius: 5px;
        margin-bottom: 1rem;
    }
    .comment-meta {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: var(--color-gris);
        margin-bottom: 0.5rem;
    }
    .comment-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }
</style>

<div class="feed-container">
    <!-- Crear Post -->
    <div class="card clearfix">
        <form action="{{ route('feed.store') }}" method="POST">
            @csrf
            <textarea name="contenido" class="form-control" rows="3" placeholder="¿Qué hazaña o desgracia te ha ocurrido hoy?" required></textarea>
            <button type="submit" class="btn-submit">Publicar</button>
        </form>
    </div>

    <!-- Lista de Posts -->
    @foreach($posts as $post)
    <div class="card">
        <div class="post-meta">
            <span class="post-author">{{ $post->user->name }}</span>
            <span>{{ $post->created_at->diffForHumans() }}</span>
        </div>
        
        <div class="post-content">
            {{ $post->contenido }}
        </div>
        
        <!-- Like del Post -->
        <form action="{{ route('like.toggle') }}" method="POST">
            @csrf
            <input type="hidden" name="id" value="{{ $post->id }}">
            <input type="hidden" name="type" value="App\Models\Post">
            <button type="submit" class="btn-like">
                ❤️ <span>{{ $post->likes->count() }} Me gusta</span>
            </button>
        </form>

        <!-- Sección de Comentarios -->
        <div class="comments-section">
            <h4 style="margin-bottom: 1rem; color: var(--color-gris); font-size: 0.9rem; text-transform: uppercase;">Comentarios</h4>
            
            @foreach($post->comentarios as $comentario)
            <div class="comment-item">
                <div class="comment-meta">
                    <strong style="color: #fff;">{{ $comentario->user->name }}</strong>
                    <span>{{ $comentario->created_at->diffForHumans() }}</span>
                </div>
                <p style="margin-bottom: 0.5rem; font-size: 0.95rem;">{{ $comentario->contenido }}</p>
                
                <form action="{{ route('like.toggle') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $comentario->id }}">
                    <input type="hidden" name="type" value="App\Models\Comentario">
                    <button type="submit" class="btn-like" style="font-size: 0.8rem;">
                        ❤️ {{ $comentario->likes->count() }}
                    </button>
                </form>
            </div>
            @endforeach

            <!-- Formulario de Comentario -->
            <form action="{{ route('comentarios.store') }}" method="POST" class="comment-form">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="text" name="contenido" class="form-control" placeholder="Escribe un comentario..." required style="padding: 0.5rem;">
                <button type="submit" class="btn-submit" style="margin-top: 0;">Enviar</button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endsection