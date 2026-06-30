@extends('layouts.app')

@section('titulo', 'La Taberna')

@section('contenido')

<video autoplay muted loop playsinline id="bg-video-taberna">
    <source src="{{ asset('videos/tavern.mp4') }}" type="video/mp4">
</video>

<style>
    /* =============================================
       LAYOUT
    ============================================= */
    .feed-wrap {
        max-width: 820px;
        margin: 0 auto;
    }

    .feed-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .feed-header h2 {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 1.5rem;
        color: #fff;
        letter-spacing: 2px;
        white-space: nowrap;
    }

    .feed-linea {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, rgba(179,3,3,0.5), transparent);
    }

    /* =============================================
       SALAS / TABS
    ============================================= */
    .salas-nav {
        display: flex;
        gap: 0.4rem;
        flex-wrap: wrap;
        margin-bottom: 1.8rem;
        background: rgba(18,8,4,0.85);
        border: 1px solid rgba(179,3,3,0.15);
        border-radius: 10px;
        padding: 0.5rem;
    }

    .sala-tab {
        display: flex;
        flex-direction: column;
        padding: 0.55rem 1rem;
        border-radius: 7px;
        text-decoration: none;
        font-size: 0.88rem;
        color: #768596;
        transition: all 0.2s;
        border: 1px solid transparent;
        flex: 1;
        min-width: 120px;
        text-align: center;
    }

    .sala-tab:hover {
        background: rgba(179,3,3,0.08);
        color: #d0d5da;
        border-color: rgba(179,3,3,0.2);
    }

    .sala-tab.active {
        background: rgba(179,3,3,0.15);
        color: #fff;
        border-color: rgba(179,3,3,0.4);
        font-weight: 600;
    }

    .sala-tab-label { font-size: 0.88rem; }
    .sala-tab-desc  { font-size: 0.72rem; opacity: 0.65; margin-top: 0.15rem; display: none; }

    @media (min-width: 640px) {
        .sala-tab-desc { display: block; }
    }

    /* =============================================
       ALERTAS
    ============================================= */
    .alert {
        padding: 1rem 1.3rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        font-size: 0.95rem;
    }

    .alert-success {
        background: rgba(64,72,52,0.5);
        border: 1px solid #404834;
        color: #9ab090;
    }

    .alert-login {
        background: rgba(179,3,3,0.08);
        border: 1px solid rgba(179,3,3,0.3);
        border-radius: 8px;
        padding: 1.4rem 1.8rem;
        text-align: center;
        color: #768596;
        margin-bottom: 2rem;
    }

    .alert-login a {
        color: #D46043;
        text-decoration: none;
        font-weight: 600;
    }

    .alert-login a:hover {
        color: #e8754f;
        text-decoration: underline;
    }

    /* =============================================
       TARJETA DE NUEVO POST
    ============================================= */
    .card {
        background: rgba(18,8,4,0.88);
        border: 1px solid rgba(179,3,3,0.25);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: border-color 0.3s;
    }

    .card:hover {
        border-color: rgba(179,3,3,0.38);
    }

    .card-titulo {
        color: #768596;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.9rem 1rem;
        background: rgba(0,0,0,0.25);
        border: 1px solid rgba(118,133,150,0.3);
        color: #fff;
        border-radius: 6px;
        font-family: inherit;
        font-size: 0.97rem;
        resize: vertical;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #B30303;
        box-shadow: 0 0 0 3px rgba(179,3,3,0.12);
    }

    .form-control::placeholder {
        color: rgba(118,133,150,0.6);
    }

    .tag-row {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.7rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .tag-input {
        flex: 1;
        min-width: 120px;
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(118,133,150,0.25);
        color: #fff;
        padding: 0.45rem 0.8rem;
        border-radius: 4px;
        font-size: 0.88rem;
        font-family: inherit;
    }

    .tag-input:focus {
        outline: none;
        border-color: #B30303;
    }

    .btn-tag-add {
        background: rgba(64,72,52,0.6);
        border: 1px solid #404834;
        color: #9ab090;
        padding: 0.45rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        font-family: inherit;
        transition: background 0.2s;
        white-space: nowrap;
    }

    .btn-tag-add:hover {
        background: rgba(64,72,52,0.9);
    }

    .tags-display {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-top: 0.6rem;
    }

    .tag {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        background: rgba(179,3,3,0.12);
        color: #768596;
        padding: 0.2rem 0.7rem;
        border-radius: 20px;
        font-size: 0.78rem;
        border: 1px solid rgba(179,3,3,0.2);
        cursor: pointer;
        transition: all 0.2s;
    }

    .tag:hover {
        background: rgba(179,3,3,0.25);
        color: #fff;
    }

    .form-footer {
        display: flex;
        justify-content: flex-end;
        margin-top: 1rem;
    }

    .btn-publicar {
        background: #B30303;
        color: #fff;
        padding: 0.7rem 2rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        font-size: 0.95rem;
        transition: all 0.25s;
        letter-spacing: 0.3px;
    }

    .btn-publicar:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(179,3,3,0.35);
    }

    /* =============================================
       POST
    ============================================= */
    .post-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.9rem;
        flex-wrap: wrap;
        gap: 0.4rem;
    }

    .post-autor {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-weight: 700;
        color: #D46043;
        font-size: 1rem;
    }

    .post-autor img {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #B30303;
    }

    .post-tiempo {
        color: #768596;
        font-size: 0.82rem;
    }

    .post-contenido {
        font-size: 1rem;
        line-height: 1.75;
        margin-bottom: 1rem;
        color: #d0d5da;
        white-space: pre-line;
    }

    .post-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 1rem;
    }

    .post-acciones {
        display: flex;
        gap: 1rem;
        align-items: center;
        border-top: 1px solid rgba(118,133,150,0.12);
        padding-top: 0.9rem;
        flex-wrap: wrap;
    }

    .btn-accion-post {
        background: none;
        border: none;
        color: #768596;
        cursor: pointer;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-family: inherit;
        padding: 0.3rem 0.7rem;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .btn-accion-post:hover {
        color: #fff;
        background: rgba(255,255,255,0.06);
    }

    .btn-like:hover,
    .btn-like.liked {
        color: #B30303;
        background: rgba(179,3,3,0.08);
    }

    /* =============================================
       COMENTARIOS
    ============================================= */
    .comments-section {
        margin-top: 1.2rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(118,133,150,0.1);
    }

    .comments-label {
        color: #768596;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 1rem;
    }

    .comment-item {
        background: rgba(0,0,0,0.55);
        padding: 0.9rem 1rem;
        border-radius: 6px;
        margin-bottom: 0.8rem;
        border-left: 2px solid rgba(179,3,3,0.3);
    }

    .comment-item.reply {
        margin-left: 1.8rem;
        border-left-color: #404834;
    }

    .comment-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.78rem;
        color: #768596;
        margin-bottom: 0.45rem;
        flex-wrap: wrap;
        gap: 0.3rem;
    }

    .comment-autor {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .comment-autor img {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-autor strong {
        color: #D46043;
    }

    .comment-body {
        font-size: 0.93rem;
        line-height: 1.55;
        color: #c0c8d0;
        margin-bottom: 0.5rem;
        white-space: pre-line;
    }

    .comment-acciones {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-comentario-accion {
        background: none;
        border: none;
        color: #768596;
        font-size: 0.78rem;
        cursor: pointer;
        font-family: inherit;
        padding: 0;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-comentario-accion:hover {
        color: #fff;
    }

    .btn-comentario-accion.liked {
        color: #B30303;
    }

    .btn-eliminar-comentario {
        margin-left: auto;
        color: rgba(179,3,3,0.45);
    }

    .btn-eliminar-comentario:hover {
        color: #B30303;
    }

    /* Formulario de comentario */
    .comment-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
        align-items: flex-start;
    }

    .comment-form .form-control {
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
    }

    .btn-enviar-comentario {
        background: #B30303;
        color: #fff;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.88rem;
        font-family: inherit;
        white-space: nowrap;
        transition: background 0.2s;
        flex-shrink: 0;
    }

    .btn-enviar-comentario:hover {
        background: #8a0202;
    }

    .reply-form-wrap {
        display: none;
        margin-top: 0.6rem;
        margin-left: 1.8rem;
    }

    .reply-form-wrap.active {
        display: flex;
    }

    /* =============================================
       VACÍO
    ============================================= */
    .feed-vacio {
        text-align: center;
        padding: 3.5rem 2rem;
        color: #768596;
        border: 1px dashed rgba(179,3,3,0.2);
        border-radius: 10px;
    }

    .feed-vacio p {
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .feed-vacio small {
        font-size: 0.88rem;
        opacity: 0.7;
    }

    @media (max-width: 640px) {
        .comment-item.reply { margin-left: 0.8rem; }
        .reply-form-wrap { margin-left: 0.8rem; }
        .post-meta { flex-direction: column; align-items: flex-start; }
        .salas-nav { gap: 0.3rem; }
        .sala-tab { min-width: calc(50% - 0.3rem); font-size: 0.82rem; }
    }

    /* =============================================
        VIDEO DE FONDO
    ============================================= */
    #bg-video-taberna {
        position: fixed;
        top: 0;
        left: var(--sidebar-w);
        width: calc(100% - var(--sidebar-w));
        height: 100%;
        object-fit: cover;
        z-index: -2;
        filter: brightness(0.35);
    }

    @media (max-width: 1024px) {
        #bg-video-taberna {
            left: 0;
            width: 100%;
        }
    }
</style>

<div class="feed-wrap">

    <div class="feed-header">
        <h2>La Taberna</h2>
    </div>

    <div class="feed-layout">

    {{-- NAVEGACIÓN DE SALAS — lateral --}}
    <nav class="salas-nav" aria-label="Salas de la Taberna">
        @foreach($salas as $clave => $sala)
        <a href="{{ route('feed.index', ['sala' => $clave]) }}"
           class="sala-tab {{ $salaActual === $clave ? 'active' : '' }}">
            <span class="sala-tab-label">{{ $sala['label'] }}</span>
        </a>
        @endforeach
    </nav>

    <div class="feed-posts-area" style="flex:1;min-width:0;">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- FORMULARIO DE NUEVO POST — solo para usuarios logueados --}}
    @auth
    <div class="card card-publicar">
        <div class="card-titulo">Publicar en {{ $salas[$salaActual]['label'] }}</div>
        <div style="color: #768596; font-size: 0.85rem; margin-top: -0.5rem; margin-bottom: 1rem; font-style: italic;">{{ $salas[$salaActual]['desc'] }}</div>
        <form action="{{ route('feed.store') }}" method="POST" id="postForm">
            @csrf
            <input type="hidden" name="sala" value="{{ $salaActual }}">
            <textarea
                name="contenido"
                class="form-control"
                rows="3"
                placeholder="¿Qué hazaña o desgracia te ha ocurrido hoy, aventurero?"
                required
                maxlength="1000"></textarea>

            <div class="tag-row">
                <input type="text" id="tagInput" class="tag-input"
                       placeholder="Añadir etiqueta..."
                       onkeydown="if(event.key==='Enter'){event.preventDefault();addTag();}">
                <button type="button" class="btn-tag-add" onclick="addTag()">+ Etiqueta</button>
            </div>
            <div class="tags-display" id="tagsDisplay"></div>
            <input type="hidden" name="etiquetas" id="etiquetasHidden" value="[]">

            <div class="form-footer">
                <button type="submit" class="btn-publicar">Publicar</button>
            </div>
        </form>
    </div>
    @else
    <div class="alert-login">
        <a href="{{ route('login') }}">Inicia sesión</a> o <a href="{{ route('register') }}">regístrate</a>
        para publicar en la Taberna y participar en la comunidad.
    </div>
    @endauth

    {{-- LISTA DE POSTS --}}
    @forelse($posts as $post)
    <div class="card" id="post-{{ $post->id }}">

        <div class="post-meta">
            <span class="post-autor">
                <img src="{{ $post->usuario->avatar
                    ? $post->usuario->avatar
                    : 'https://ui-avatars.com/api/?name=' . urlencode($post->usuario->nombre) . '&background=B30303&color=fff&size=34' }}"
                    alt="{{ $post->usuario->nombre }}">
                {{ $post->usuario->nombre }}
            </span>
            <span class="post-tiempo">{{ $post->created_at->diffForHumans() }}</span>
        </div>

        <div class="post-contenido">{{ $post->contenido }}</div>

        @if($post->etiquetas && count($post->etiquetas) > 0)
        <div class="post-tags">
            @foreach($post->etiquetas as $et)
                <span class="tag" style="cursor:default">#{{ $et }}</span>
            @endforeach
        </div>
        @endif

        <div class="post-acciones">
            @auth
            {{-- LIKE AJAX --}}
            <button type="button"
                    class="btn-accion-post btn-like {{ $post->isLikedBy(auth()->id()) ? 'liked' : '' }}"
                    data-id="{{ $post->id }}"
                    data-type="App\Models\Post"
                    onclick="toggleLike(this)">
                ❤️ <span class="like-count">{{ $post->likes_count }}</span>
            </button>
            @else
            <span class="btn-accion-post" style="cursor:default">
                ❤️ {{ $post->likes_count }}
            </span>
            @endauth

            <button class="btn-accion-post" onclick="toggleComments({{ $post->id }})">
                💬 <span id="comment-count-{{ $post->id }}">{{ $post->comentarios->count() }}</span>
                {{ $post->comentarios->count() === 1 ? 'comentario' : 'comentarios' }}
            </button>

            @auth
            <button class="btn-accion-post" onclick="enfocarComentario({{ $post->id }})">
                Responder
            </button>
            @endauth

            @auth
            @if(auth()->id() === $post->user_id)
            <button class="btn-accion-post btn-eliminar-comentario"
                    onclick="eliminarPost({{ $post->id }}, this)"
                    title="Eliminar publicación">
                Eliminar
            </button>
            @endif
            @endauth
        </div>

        {{-- SECCIÓN DE COMENTARIOS --}}
        <div class="comments-section" id="comments-{{ $post->id }}" style="display:none">
            <div class="comments-label">Comentarios</div>

            @foreach($post->comentarios->where('parent_id', null) as $comentario)
            <div class="comment-item" id="comment-{{ $comentario->id }}">
                <div class="comment-meta">
                    <span class="comment-autor">
                        <img src="{{ $comentario->usuario->avatar
                            ? $comentario->usuario->avatar
                            : 'https://ui-avatars.com/api/?name=' . urlencode($comentario->usuario->nombre) . '&background=B30303&color=fff&size=22' }}"
                            alt="{{ $comentario->usuario->nombre }}">
                        <strong>{{ $comentario->usuario->nombre }}</strong>
                    </span>
                    <span>{{ $comentario->created_at->diffForHumans() }}</span>
                </div>
                <div class="comment-body">{{ $comentario->contenido }}</div>

                <div class="comment-acciones">
                    @auth
                    {{-- Like comentario AJAX --}}
                    <button type="button"
                            class="btn-comentario-accion {{ $comentario->isLikedBy(auth()->id()) ? 'liked' : '' }}"
                            data-id="{{ $comentario->id }}"
                            data-type="App\Models\Comentario"
                            onclick="toggleLike(this)">
                        ❤️ <span class="like-count">{{ $comentario->likes_count }}</span>
                    </button>

                    <button class="btn-comentario-accion" onclick="toggleReply('reply-{{ $post->id }}-{{ $comentario->id }}')">
                        ↩ Responder
                    </button>

                    {{-- Botón eliminar solo si es el autor --}}
                    @if(auth()->id() === $comentario->user_id)
                    <button class="btn-comentario-accion btn-eliminar-comentario"
                            onclick="eliminarComentario({{ $comentario->id }}, this)"
                            title="Eliminar comentario">
                        Eliminar
                    </button>
                    @endif

                    @else
                    <span class="btn-comentario-accion" style="cursor:default">❤️ {{ $comentario->likes_count }}</span>
                    @endauth
                </div>

                @auth
                {{-- Formulario de respuesta a comentario --}}
                <div class="reply-form-wrap" id="reply-{{ $post->id }}-{{ $comentario->id }}">
                    <form action="{{ route('comentarios.store') }}" method="POST" style="display:flex;gap:0.5rem;width:100%">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <input type="hidden" name="parent_id" value="{{ $comentario->id }}">
                        <textarea name="contenido" class="form-control"
                               placeholder="Tu respuesta..." required
                               rows="2"
                               style="padding:0.5rem 0.7rem;font-size:0.88rem;"></textarea>
                        <button type="submit" class="btn-enviar-comentario">Enviar</button>
                    </form>
                </div>
                @endauth

                {{-- Respuestas anidadas --}}
                @foreach($comentario->respuestas as $respuesta)
                <div class="comment-item reply" style="margin-top:0.6rem" id="comment-{{ $respuesta->id }}">
                    <div class="comment-meta">
                        <span class="comment-autor">
                            <img src="{{ $respuesta->usuario->avatar
                                ? $respuesta->usuario->avatar
                                : 'https://ui-avatars.com/api/?name=' . urlencode($respuesta->usuario->nombre) . '&background=B30303&color=fff&size=22' }}"
                                alt="{{ $respuesta->usuario->nombre }}">
                            <strong>{{ $respuesta->usuario->nombre }}</strong>
                        </span>
                        <span>{{ $respuesta->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="comment-body">{{ $respuesta->contenido }}</div>
                    <div class="comment-acciones">
                        @auth
                        <button type="button"
                                class="btn-comentario-accion {{ $respuesta->isLikedBy(auth()->id()) ? 'liked' : '' }}"
                                data-id="{{ $respuesta->id }}"
                                data-type="App\Models\Comentario"
                                onclick="toggleLike(this)">
                            ❤️ <span class="like-count">{{ $respuesta->likes_count }}</span>
                        </button>

                        @if(auth()->id() === $respuesta->user_id)
                        <button class="btn-comentario-accion btn-eliminar-comentario"
                                onclick="eliminarComentario({{ $respuesta->id }}, this)"
                                title="Eliminar comentario">
                            Eliminar
                        </button>
                        @endif

                        @else
                        <span class="btn-comentario-accion" style="cursor:default">❤️ {{ $respuesta->likes_count }}</span>
                        @endauth
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            @auth
            {{-- Formulario de comentario principal --}}
            <form action="{{ route('comentarios.store') }}" method="POST" class="comment-form" id="comment-form-{{ $post->id }}">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="contenido" class="form-control"
                       placeholder="Escribe un comentario..." required
                       rows="2"
                       style="padding:0.6rem 0.8rem;font-size:0.9rem;"></textarea>
                <button type="submit" class="btn-enviar-comentario">Enviar</button>
            </form>
            @else
            <p style="color:#768596;font-size:0.88rem;margin-top:0.8rem;text-align:center;">
                <a href="{{ route('login') }}" style="color:#D46043">Inicia sesión</a> para comentar.
            </p>
            @endauth
        </div>

    </div>
    @empty
    <div class="feed-vacio">
        <p>Esta sala está en silencio...</p>
        <small>¡Sé el primero en hablar aquí!</small>
    </div>
    @endforelse

</div>

<script>
/* Token CSRF para peticiones AJAX */
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

/* ===== Etiquetas en el formulario de post ===== */
let tags = [];

function addTag() {
    const input = document.getElementById('tagInput');
    const val   = input.value.trim().toLowerCase().replace(/\s+/g, '-');
    if (val && !tags.includes(val) && tags.length < 8) {
        tags.push(val);
        renderTags();
        input.value = '';
    }
    input.focus();
}

function removeTag(i) {
    tags.splice(i, 1);
    renderTags();
}

function renderTags() {
    const display = document.getElementById('tagsDisplay');
    const hidden  = document.getElementById('etiquetasHidden');
    if (!display) return;
    display.innerHTML = tags.map((t, i) =>
        `<span class="tag" onclick="removeTag(${i})">#${t} ✕</span>`
    ).join('');
    hidden.value = JSON.stringify(tags);
}

/* ===== Toggle de sección de comentarios ===== */
function toggleComments(postId) {
    const el = document.getElementById('comments-' + postId);
    if (!el) return;
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}

/* ===== Enfocar el textarea de comentario ===== */
function enfocarComentario(postId) {
    const section = document.getElementById('comments-' + postId);
    if (section) section.style.display = 'block';
    const form = document.getElementById('comment-form-' + postId);
    if (form) {
        const input = form.querySelector('textarea[name="contenido"]');
        if (input) {
            input.focus();
            input.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

/* ===== Toggle del formulario de respuesta a comentario ===== */
function toggleReply(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.toggle('active');
    if (el.classList.contains('active')) {
        const input = el.querySelector('textarea[name="contenido"]');
        if (input) input.focus();
    }
}

/* ===== LIKE AJAX — sin refresco de página ===== */
async function toggleLike(btn) {
    const id   = btn.dataset.id;
    const type = btn.dataset.type;
    if (!id || !type) return;

    btn.disabled = true;

    try {
        const res = await fetch('{{ route("like.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept':       'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify({ id, type }),
        });

        if (!res.ok) throw new Error('Error en la respuesta');

        const data = await res.json();

        if (data.success) {
            const countEl = btn.querySelector('.like-count');
            if (countEl) countEl.textContent = data.count;

            if (data.liked) {
                btn.classList.add('liked');
            } else {
                btn.classList.remove('liked');
            }
        }
    } catch (e) {
        console.error('Error al hacer like:', e);
    } finally {
        btn.disabled = false;
    }
}

/* ===== Eliminar comentario propio AJAX ===== */
async function eliminarComentario(id, btn) {
    if (!confirm('¿Seguro que quieres borrar este comentario?')) return;

    btn.disabled = true;

    try {
        const res = await fetch(`/comentarios/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept':       'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!res.ok) throw new Error('Error eliminando');

        const data = await res.json();
        if (data.success) {
            const el = document.getElementById('comment-' + id);
            if (el) {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
        }
    } catch (e) {
        console.error('Error al eliminar comentario:', e);
        btn.disabled = false;
    }
}

/* ===== Eliminar publicación propia AJAX ===== */
async function eliminarPost(id, btn) {
    if (!confirm('¿Seguro que quieres borrar esta publicación? También se borrarán sus comentarios.')) return;

    btn.disabled = true;

    try {
        const res = await fetch(`/feed/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept':       'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        if (!res.ok) throw new Error('Error eliminando');

        const data = await res.json();
        if (data.success) {
            const el = document.getElementById('post-' + id);
            if (el) {
                el.style.transition = 'opacity 0.3s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 300);
            }
        }
    } catch (e) {
        console.error('Error al eliminar publicación:', e);
        btn.disabled = false;
    }
}

/* ===== Auto-resize textarea ===== */
document.querySelectorAll('textarea').forEach(ta => {
    ta.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>

    </div>{{-- /feed-posts-area --}}
    </div>{{-- /feed-layout --}}

@endsection