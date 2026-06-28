@extends('layouts.app')

@section('titulo', 'La Taberna')

@section('contenido')
<style>
    .feed-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .card {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(179, 3, 3, 0.2);
        border-radius: 10px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        transition: all 0.3s;
    }

    .card:hover {
        border-color: rgba(179, 3, 3, 0.4);
    }

    .form-control {
        width: 100%;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.2);
        border: 1px solid var(--color-gris);
        color: #fff;
        border-radius: 6px;
        font-family: inherit;
        resize: vertical;
        box-sizing: border-box;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-rojo);
    }

    .btn-submit {
        background: var(--color-rojo);
        color: #fff;
        padding: 0.6rem 1.8rem;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        margin-top: 1rem;
        float: right;
        font-family: inherit;
        transition: all 0.3s;
    }

    .btn-submit:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(179, 3, 3, 0.3);
    }

    .btn-submit-sm {
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        margin-top: 0;
    }

    .post-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
        color: var(--color-gris);
        font-size: 0.9rem;
    }

    .post-author {
        font-weight: bold;
        color: var(--color-naranja);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .post-author img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--color-rojo);
    }

    .post-content {
        font-size: 1.1rem;
        line-height: 1.7;
        margin-bottom: 1rem;
    }

    .post-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }

    .tag {
        display: inline-block;
        background: rgba(179, 3, 3, 0.15);
        color: var(--color-gris);
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        border: 1px solid rgba(179, 3, 3, 0.2);
        transition: all 0.3s;
        cursor: pointer;
    }

    .tag:hover {
        background: rgba(179, 3, 3, 0.3);
        color: #fff;
    }

    .tag-input-container {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .tag-input {
        flex: 1;
        min-width: 100px;
        background: rgba(0,0,0,0.2);
        border: 1px solid var(--color-gris);
        color: #fff;
        padding: 0.4rem 0.8rem;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .tag-input:focus {
        outline: none;
        border-color: var(--color-rojo);
    }

    .tag-add-btn {
        background: var(--color-verde);
        color: #fff;
        border: none;
        padding: 0.4rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    .tag-add-btn:hover {
        background: #2a3828;
    }

    .post-actions {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        flex-wrap: wrap;
        border-top: 1px solid rgba(118, 133, 150, 0.15);
        padding-top: 1rem;
        margin-top: 0.5rem;
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
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        transition: all 0.3s;
    }

    .btn-like:hover {
        color: var(--color-rojo);
        background: rgba(179, 3, 3, 0.1);
    }

    .btn-like.liked {
        color: var(--color-rojo);
    }

    .btn-comment {
        background: none;
        border: none;
        color: var(--color-gris);
        cursor: pointer;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-family: inherit;
        padding: 0.3rem 0.8rem;
        border-radius: 4px;
        transition: all 0.3s;
    }

    .btn-comment:hover {
        color: #fff;
        background: rgba(255,255,255,0.05);
    }

    .comments-section {
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(118, 133, 150, 0.15);
    }

    .comments-section h4 {
        color: var(--color-gris);
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
    }

    .comment-item {
        background: rgba(0, 0, 0, 0.2);
        padding: 1rem;
        border-radius: 6px;
        margin-bottom: 1rem;
        border-left: 2px solid rgba(179, 3, 3, 0.3);
    }

    .comment-item.reply {
        margin-left: 2rem;
        border-left-color: var(--color-verde);
    }

    .comment-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--color-gris);
        margin-bottom: 0.5rem;
    }

    .comment-author {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .comment-author img {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        object-fit: cover;
    }

    .comment-author strong {
        color: var(--color-naranja);
    }

    .comment-content {
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .comment-actions {
        display: flex;
        gap: 1rem;
    }

    .comment-actions button {
        background: none;
        border: none;
        color: var(--color-gris);
        font-size: 0.8rem;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.3s;
    }

    .comment-actions button:hover {
        color: #fff;
    }

    .comment-form {
        display: flex;
        gap: 0.5rem;
        margin-top: 1rem;
    }

    .comment-form .form-control {
        padding: 0.6rem;
        font-size: 0.9rem;
    }

    .reply-form {
        margin-top: 0.8rem;
        margin-left: 2rem;
        display: none;
    }

    .reply-form.active {
        display: flex;
    }

    .clearfix::after {
        content: "";
        clear: both;
        display: table;
    }

    .alert-success {
        background: rgba(64, 72, 52, 0.5);
        color: #a0b890;
        padding: 1rem;
        border-radius: 6px;
        border: 1px solid var(--color-verde);
        margin-bottom: 1rem;
    }

    .loading-spinner {
        text-align: center;
        padding: 2rem;
        color: var(--color-gris);
    }

    .btn-responder {
        background: none;
        border: none;
        color: var(--color-gris);
        font-size: 0.8rem;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.3s;
        padding: 0;
    }

    .btn-responder:hover {
        color: var(--color-naranja);
    }

    @media (max-width: 768px) {
        .comment-item.reply {
            margin-left: 1rem;
        }
        .reply-form {
            margin-left: 1rem;
        }
        .post-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.3rem;
        }
    }
</style>

<div class="feed-container">
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Crear Post -->
    <div class="card clearfix">
        <form action="{{ route('feed.store') }}" method="POST" id="postForm">
            @csrf
            <textarea name="contenido" class="form-control" rows="3" 
                      placeholder="¿Qué hazaña o desgracia te ha ocurrido hoy?" required></textarea>
            
            <div class="tag-input-container">
                <input type="text" class="tag-input" id="tagInput" 
                       placeholder="Añadir etiqueta (ej: dnd, rol, aventura)" 
                       onkeydown="if(event.key==='Enter'){event.preventDefault();addTag();}">
                <button type="button" class="tag-add-btn" onclick="addTag()">➕ Añadir</button>
            </div>
            <div id="tagsContainer" class="post-tags" style="margin-top: 0.5rem;"></div>
            <input type="hidden" name="etiquetas" id="etiquetasInput" value="[]">

            <button type="submit" class="btn-submit">Publicar</button>
        </form>
    </div>

    <!-- Lista de Posts -->
    @forelse($posts as $post)
    <div class="card" id="post-{{ $post->id }}">
        <div class="post-meta">
            <span class="post-author">
                @if($post->usuario->avatar)
                    <img src="{{ $post->usuario->avatar }}" alt="{{ $post->usuario->nombre }}">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->usuario->nombre) }}&background=B30303&color=fff&size=32" alt="{{ $post->usuario->nombre }}">
                @endif
                {{ $post->usuario->nombre }}
            </span>
            <span>{{ $post->created_at->diffForHumans() }}</span>
        </div>
        
        <div class="post-content">
            {{ $post->contenido }}
        </div>

        <!-- Etiquetas -->
        @if($post->etiquetas && count($post->etiquetas) > 0)
        <div class="post-tags">
            @foreach($post->etiquetas as $etiqueta)
                <span class="tag">#{{ $etiqueta }}</span>
            @endforeach
        </div>
        @endif

        <!-- Acciones -->
        <div class="post-actions">
            <form action="{{ route('like.toggle') }}" method="POST" class="like-form">
                @csrf
                <input type="hidden" name="id" value="{{ $post->id }}">
                <input type="hidden" name="type" value="App\Models\Post">
                <button type="submit" class="btn-like {{ $post->isLikedBy(auth()->id()) ? 'liked' : '' }}">
                    ❤️ <span class="like-count">{{ $post->likes_count }}</span>
                </button>
            </form>

            <button class="btn-comment" onclick="toggleComments({{ $post->id }})">
                💬 {{ $post->comentarios->count() }} Comentarios
            </button>

            <button class="btn-responder" onclick="showReplyForm({{ $post->id }})">
                📝 Responder
            </button>
        </div>

        <!-- Comentarios -->
        <div class="comments-section" id="comments-{{ $post->id }}" style="display: none;">
            <h4>Comentarios</h4>
            
            @foreach($post->comentarios->where('parent_id', null) as $comentario)
            <div class="comment-item" id="comment-{{ $comentario->id }}">
                <div class="comment-meta">
                    <span class="comment-author">
                        @if($comentario->usuario->avatar)
                            <img src="{{ $comentario->usuario->avatar }}" alt="{{ $comentario->usuario->nombre }}">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($comentario->usuario->nombre) }}&background=B30303&color=fff&size=24" alt="{{ $comentario->usuario->nombre }}">
                        @endif
                        <strong>{{ $comentario->usuario->nombre }}</strong>
                    </span>
                    <span>{{ $comentario->created_at->diffForHumans() }}</span>
                </div>
                <div class="comment-content">{{ $comentario->contenido }}</div>
                
                <div class="comment-actions">
                    <form action="{{ route('like.toggle') }}" method="POST" class="like-form">
                        @csrf
                        <input type="hidden" name="id" value="{{ $comentario->id }}">
                        <input type="hidden" name="type" value="App\Models\Comentario">
                        <button type="submit" class="btn-like {{ $comentario->isLikedBy(auth()->id()) ? 'liked' : '' }}" style="font-size: 0.8rem; padding: 0.2rem 0.5rem;">
                            ❤️ <span class="like-count">{{ $comentario->likes_count }}</span>
                        </button>
                    </form>
                    <button class="btn-responder" onclick="showReplyForm({{ $post->id }}, {{ $comentario->id }})">
                        Responder
                    </button>
                </div>

                <!-- Respuestas -->
                @foreach($comentario->respuestas as $respuesta)
                <div class="comment-item reply" id="comment-{{ $respuesta->id }}">
                    <div class="comment-meta">
                        <span class="comment-author">
                            @if($respuesta->usuario->avatar)
                                <img src="{{ $respuesta->usuario->avatar }}" alt="{{ $respuesta->usuario->nombre }}">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($respuesta->usuario->nombre) }}&background=B30303&color=fff&size=24" alt="{{ $respuesta->usuario->nombre }}">
                            @endif
                            <strong>{{ $respuesta->usuario->nombre }}</strong>
                        </span>
                        <span>{{ $respuesta->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="comment-content">{{ $respuesta->contenido }}</div>
                    
                    <div class="comment-actions">
                        <form action="{{ route('like.toggle') }}" method="POST" class="like-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $respuesta->id }}">
                            <input type="hidden" name="type" value="App\Models\Comentario">
                            <button type="submit" class="btn-like {{ $respuesta->isLikedBy(auth()->id()) ? 'liked' : '' }}" style="font-size: 0.8rem; padding: 0.2rem 0.5rem;">
                                ❤️ <span class="like-count">{{ $respuesta->likes_count }}</span>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @endforeach

            <!-- Formulario de Comentario -->
            <form action="{{ route('comentarios.store') }}" method="POST" class="comment-form">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <input type="hidden" name="parent_id" id="parent_id_{{ $post->id }}" value="">
                <input type="text" name="contenido" class="form-control" placeholder="Escribe un comentario..." required style="padding: 0.6rem;">
                <button type="submit" class="btn-submit btn-submit-sm" style="margin-top: 0;">Enviar</button>
            </form>
        </div>
    </div>
    @empty
    <div class="card" style="text-align: center; padding: 3rem;">
        <p style="color: var(--color-gris); font-size: 1.2rem;">🍺 La taberna está vacía... ¡Sé el primero en publicar!</p>
    </div>
    @endforelse
</div>

<script>
// Manejo de etiquetas
let tags = [];

function addTag() {
    const input = document.getElementById('tagInput');
    const tag = input.value.trim().toLowerCase();
    
    if (tag && !tags.includes(tag)) {
        tags.push(tag);
        renderTags();
        input.value = '';
    }
}

function removeTag(index) {
    tags.splice(index, 1);
    renderTags();
}

function renderTags() {
    const container = document.getElementById('tagsContainer');
    const input = document.getElementById('etiquetasInput');
    
    container.innerHTML = tags.map((tag, index) => `
        <span class="tag" onclick="removeTag(${index})">
            #${tag} ✕
        </span>
    `).join('');
    
    input.value = JSON.stringify(tags);
}

// Toggle de comentarios
function toggleComments(postId) {
    const comments = document.getElementById('comments-' + postId);
    if (comments.style.display === 'none') {
        comments.style.display = 'block';
    } else {
        comments.style.display = 'none';
    }
}

// Mostrar formulario de respuesta
function showReplyForm(postId, commentId = null) {
    const parentInput = document.getElementById('parent_id_' + postId);
    if (commentId) {
        parentInput.value = commentId;
        // Buscar el formulario dentro del comentario padre
        const comment = document.getElementById('comment-' + commentId);
        if (comment) {
            const form = comment.querySelector('.reply-form');
            if (form) {
                form.classList.toggle('active');
            }
        }
    } else {
        parentInput.value = '';
        // Scroll al formulario de comentarios
        const comments = document.getElementById('comments-' + postId);
        if (comments.style.display === 'none') {
            comments.style.display = 'block';
        }
        const form = comments.querySelector('.comment-form');
        if (form) {
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const input = form.querySelector('input[name="contenido"]');
            if (input) input.focus();
        }
    }
}

// Auto-expandir textarea
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});

// Cerrar comentarios al hacer clic fuera
document.addEventListener('click', function(event) {
    if (!event.target.closest('.card')) {
        document.querySelectorAll('.comments-section').forEach(section => {
            section.style.display = 'none';
        });
    }
});

// Inicializar formularios de respuesta
document.querySelectorAll('.comment-item').forEach(comment => {
    const replyForm = document.createElement('div');
    replyForm.className = 'reply-form';
    replyForm.innerHTML = `
        <form action="{{ route('comentarios.store') }}" method="POST" style="display: flex; gap: 0.5rem; width: 100%;">
            @csrf
            <input type="hidden" name="post_id" value="${comment.closest('.card').id.replace('post-', '')}">
            <input type="hidden" name="parent_id" value="${comment.id.replace('comment-', '')}">
            <input type="text" name="contenido" class="form-control" placeholder="Escribe tu respuesta..." required style="padding: 0.6rem;">
            <button type="submit" class="btn-submit btn-submit-sm" style="margin-top: 0;">Responder</button>
        </form>
    `;
    comment.appendChild(replyForm);
});
</script>
@endsection