{{-- Comentario o respuesta. Cada uno muestra el avatar de SU autor (antes se mostraba el del autor del post). --}}
<div class="comment-item {{ $esRespuesta ? 'reply' : '' }}" id="comment-{{ $comentario->id }}">
    <div class="comment-meta">
        <img class="avatar avatar-sm" src="{{ $comentario->usuario->avatar_url }}" alt="" width="28" height="28"
             loading="lazy" decoding="async" data-lightbox title="Ampliar foto de {{ $comentario->usuario->nombre }}">
        <strong>{{ $comentario->usuario->nombre }}</strong>
        <time datetime="{{ $comentario->created_at->toIso8601String() }}">{{ $comentario->created_at->diffForHumans() }}</time>
    </div>

    <div class="comment-body">{{ $comentario->contenido }}</div>

    <div class="comment-acciones">
        @auth
            <button type="button" class="accion-comentario {{ $comentario->isLikedBy(auth()->id()) ? 'liked' : '' }}"
                    data-like data-id="{{ $comentario->id }}" data-type="App\Models\Comentario"
                    aria-pressed="{{ $comentario->isLikedBy(auth()->id()) ? 'true' : 'false' }}" aria-label="Me gusta">
                @include('partials.icon', ['name' => 'heart', 'class' => 'icon-sm'])
                <span class="like-count">{{ $comentario->likes_count }}</span>
            </button>

            @unless ($esRespuesta)
                <button type="button" class="accion-comentario" data-responder="reply-{{ $post->id }}-{{ $comentario->id }}">
                    Responder
                </button>
            @endunless

            @if (auth()->id() === $comentario->user_id)
                <button type="button" class="accion-comentario accion-borrar" data-borrar-comentario="{{ $comentario->id }}">
                    Eliminar
                </button>
            @endif
        @else
            <span class="accion-comentario">
                @include('partials.icon', ['name' => 'heart', 'class' => 'icon-sm']) {{ $comentario->likes_count }}
            </span>
        @endauth
    </div>

    @unless ($esRespuesta)
        @auth
            <div class="reply-form-wrap" id="reply-{{ $post->id }}-{{ $comentario->id }}" hidden>
                <form action="{{ route('comentarios.store') }}" method="POST" class="comment-form">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comentario->id }}">
                    <textarea name="contenido" class="feed-input" rows="2" maxlength="500" required
                              placeholder="Tu respuesta…" aria-label="Tu respuesta" data-autosize></textarea>
                    <button type="submit" class="btn btn-primario btn-sm">Enviar</button>
                </form>
            </div>
        @endauth

        @foreach ($comentario->respuestas as $respuesta)
            @include('partials.feed-comentario', ['comentario' => $respuesta, 'post' => $post, 'esRespuesta' => true])
        @endforeach
    @endunless
</div>
