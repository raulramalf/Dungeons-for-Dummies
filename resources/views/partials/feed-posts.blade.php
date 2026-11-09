{{-- Lista de publicaciones + disparador de "Cargar más". Se usa en la página y en la respuesta parcial. --}}
@forelse ($posts as $post)
    @include('partials.feed-post', ['post' => $post])
@empty
    @if ($posts->currentPage() === 1)
        <div class="feed-vacio">
            @include('partials.icon', ['name' => 'feed', 'class' => 'icon-xl'])
            <p>Esta sala está en silencio…</p>
            <small>¡Sé el primero en hablar aquí!</small>
        </div>
    @endif
@endforelse

@if ($posts->hasMorePages())
    <div class="feed-mas" data-feed-mas data-siguiente="{{ $posts->nextPageUrl() }}">
        <button type="button" class="btn btn-secundario">Cargar más publicaciones</button>
    </div>
@endif
