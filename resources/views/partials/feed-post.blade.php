{{-- Una publicación de la Taberna. Contadores y "me gusta" vienen precargados por FeedController. --}}
@php($totalComentarios = $post->comentarios_count)
<article class="feed-card" id="post-{{ $post->id }}">

    <header class="post-meta">
        <img class="avatar" src="{{ $post->usuario->avatar_url }}" alt="" width="40" height="40"
             loading="lazy" decoding="async" data-lightbox title="Ampliar foto de {{ $post->usuario->nombre }}">
        <div class="post-autor">
            <strong>{{ $post->usuario->nombre }}</strong>
            <time class="post-tiempo" datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->diffForHumans() }}</time>
        </div>
    </header>

    <div class="post-contenido">{{ $post->contenido }}</div>

    @if ($post->etiquetas && count($post->etiquetas) > 0)
        <div class="post-tags">
            @foreach ($post->etiquetas as $et)
                <a class="tag" href="{{ route('feed.index', ['sala' => $post->sala, 'etiqueta' => $et]) }}">#{{ $et }}</a>
            @endforeach
        </div>
    @endif

    <div class="post-acciones">
        @auth
            <button type="button" class="accion-post btn-like {{ $post->isLikedBy(auth()->id()) ? 'liked' : '' }}"
                    data-like data-id="{{ $post->id }}" data-type="App\Models\Post"
                    aria-pressed="{{ $post->isLikedBy(auth()->id()) ? 'true' : 'false' }}" aria-label="Me gusta">
                @include('partials.icon', ['name' => 'heart', 'class' => 'icon-sm'])
                <span class="like-count">{{ $post->likes_count }}</span>
            </button>
        @else
            <span class="accion-post" aria-label="{{ $post->likes_count }} me gusta">
                @include('partials.icon', ['name' => 'heart', 'class' => 'icon-sm']) {{ $post->likes_count }}
            </span>
        @endauth

        <button type="button" class="accion-post" data-comentarios="{{ $post->id }}"
                aria-expanded="false" aria-controls="comments-{{ $post->id }}">
            @include('partials.icon', ['name' => 'comment', 'class' => 'icon-sm'])
            <span id="comment-count-{{ $post->id }}">{{ $totalComentarios }}</span>
            <span class="solo-escritorio">{{ $totalComentarios === 1 ? 'comentario' : 'comentarios' }}</span>
        </button>

        @auth
            @if (auth()->id() === $post->user_id)
                <button type="button" class="accion-post accion-borrar" data-borrar-post="{{ $post->id }}"
                        aria-label="Eliminar publicación">
                    @include('partials.icon', ['name' => 'trash', 'class' => 'icon-sm'])
                    <span class="solo-escritorio">Eliminar</span>
                </button>
            @endif
        @endauth
    </div>

    {{-- COMENTARIOS (ocultos hasta pulsar el botón) --}}
    <div class="comments-section" id="comments-{{ $post->id }}" hidden>
        @foreach ($post->comentarios as $comentario)
            @include('partials.feed-comentario', ['comentario' => $comentario, 'post' => $post, 'esRespuesta' => false])
        @endforeach

        @auth
            <form action="{{ route('comentarios.store') }}" method="POST" class="comment-form" id="comment-form-{{ $post->id }}">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="contenido" class="feed-input" rows="2" maxlength="500" required
                          placeholder="Escribe un comentario…" aria-label="Escribe un comentario" data-autosize></textarea>
                <button type="submit" class="btn btn-primario btn-sm">Enviar</button>
            </form>
        @else
            <p class="comments-login"><a href="{{ route('login') }}">Inicia sesión</a> para comentar.</p>
        @endauth
    </div>
</article>
