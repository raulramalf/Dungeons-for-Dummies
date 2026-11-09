@extends('layouts.app')

@section('titulo', 'La Taberna')
@section('descripcion', 'Cuenta tus hazañas, pide consejo sobre reglas y comparte contenido casero con otros aventureros.')

@push('head')
    @vite(['resources/css/pages/feed.css', 'resources/js/pages/feed.js'])
@endpush

@section('contenido')

<x-bg-media nombre="Tavern" velo="0.7" />

<div class="feed"
     data-feed
     data-url-like="{{ route('like.toggle') }}"
     data-url-comentarios="{{ url('/comentarios') }}"
     data-url-feed="{{ url('/feed') }}">

    {{-- SALAS: lista lateral en escritorio, fichas desplazables en móvil --}}
    <nav class="salas-nav" aria-label="Salas de la Taberna">
        @foreach ($salas as $clave => $sala)
            <a href="{{ route('feed.index', ['sala' => $clave]) }}" class="sala-tab"
               @if ($salaActual === $clave) aria-current="page" @endif>
                {{ $sala['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="feed-main">

        <header class="feed-cabecera">
            <h2>{{ $salas[$salaActual]['label'] }}</h2>
            <p>{{ $salas[$salaActual]['desc'] }}</p>
        </header>

        @if ($etiquetas->count() > 0)
            <form class="feed-filtro" method="GET" action="{{ route('feed.index') }}">
                <input type="hidden" name="sala" value="{{ $salaActual }}">
                <label for="filtro-etiqueta">Etiqueta</label>
                <select id="filtro-etiqueta" name="etiqueta" class="feed-input" data-autosubmit>
                    <option value="">Todas</option>
                    @foreach ($etiquetas as $et)
                        <option value="{{ $et }}" @selected($etiquetaActual === $et)>#{{ $et }}</option>
                    @endforeach
                </select>
                @if ($etiquetaActual)
                    <a href="{{ route('feed.index', ['sala' => $salaActual]) }}" class="feed-quitar-filtro">
                        @include('partials.icon', ['name' => 'x', 'class' => 'icon-sm']) Quitar filtro
                    </a>
                @endif
                <noscript><button type="submit" class="btn btn-secundario btn-sm">Filtrar</button></noscript>
            </form>
        @endif

        {{-- NUEVA PUBLICACIÓN --}}
        @auth
            <section class="feed-card feed-composer" aria-label="Publicar en {{ $salas[$salaActual]['label'] }}">
                <form action="{{ route('feed.store') }}" method="POST" id="postForm">
                    @csrf
                    <input type="hidden" name="sala" value="{{ $salaActual }}">
                    <textarea name="contenido" class="feed-input" rows="3" required maxlength="1000"
                              placeholder="¿Qué hazaña o desgracia te ha ocurrido hoy, aventurero?"
                              aria-label="Contenido de la publicación" data-autosize></textarea>

                    <div class="tag-row">
                        <input type="text" id="tagInput" class="feed-input" maxlength="30"
                               placeholder="Añadir etiqueta…" aria-label="Añadir etiqueta" autocomplete="off">
                        <button type="button" class="btn btn-secundario btn-sm" data-tag-add>+ Etiqueta</button>
                    </div>
                    <div class="tags-display" id="tagsDisplay" aria-live="polite"></div>
                    <input type="hidden" name="etiquetas" id="etiquetasHidden" value="[]">

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primario">Publicar</button>
                    </div>
                </form>
            </section>
        @else
            <div class="alert-login">
                <a href="{{ route('login') }}">Inicia sesión</a> o <a href="{{ route('register') }}">regístrate</a>
                para publicar en la Taberna y participar en la comunidad.
            </div>
        @endauth

        {{-- PUBLICACIONES (+ "Cargar más") --}}
        <div class="feed-lista" data-feed-lista>
            @include('partials.feed-posts')
        </div>
    </div>
</div>
@endsection
