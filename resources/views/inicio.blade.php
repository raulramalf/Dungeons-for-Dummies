@extends('layouts.app')

@section('titulo', 'Forja tu Leyenda')

@section('contenido')
<div class="contenedor">

    @if(session('success'))
        <div class="alerta alerta-exito">
            @include('partials.icon', ['name' => 'check']) {{ session('success') }}
        </div>
    @endif

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-orn">@include('partials.icon', ['name' => 'swords', 'class' => 'icon-xl'])</div>
        <h1 class="hero-titulo">Forja tu Leyenda</h1>
        <div class="separador-orn">@include('partials.icon', ['name' => 'dice'])</div>
        <p class="hero-sub">
            El grimorio digital para gestionar tus campañas, dar vida a héroes inolvidables
            y compartir tus hazañas con la comunidad.
        </p>
        <div class="hero-acciones">
            @auth
                <a href="{{ route('personajes.create') }}" class="btn btn-primario">
                    @include('partials.icon', ['name' => 'sword']) Crear Personaje
                </a>
                <a href="{{ route('feed.index') }}" class="btn btn-secundario">
                    @include('partials.icon', ['name' => 'feed']) Entrar a la Taberna
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primario">
                    @include('partials.icon', ['name' => 'scroll']) Comenzar la Aventura
                </a>
                <a href="{{ route('login') }}" class="btn btn-secundario">
                    @include('partials.icon', ['name' => 'lock']) Iniciar Sesión
                </a>
            @endauth
        </div>
    </section>

    {{-- CAROUSEL DE PERSONAJES --}}
    @if(isset($personajesDestacados) && $personajesDestacados->count() > 0)
    <section class="bloque">
        <h2 class="seccion-titulo">@include('partials.icon', ['name' => 'star']) Héroes del Reino</h2>
        <div class="destacados-scroll">
            @foreach($personajesDestacados as $personaje)
            @auth
            <a href="{{ route('personajes.show', $personaje) }}" class="destacado-card">
            @else
            <div class="destacado-card" style="cursor:default">
            @endauth
                <img src="{{ $personaje->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($personaje->nombre) . '&background=B30303&color=fff&size=200' }}"
                     alt="{{ $personaje->nombre }}">
                <div class="destacado-nombre">{{ $personaje->nombre }}</div>
                <div class="destacado-meta">{{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}</div>
                <span class="badge badge-rojo">Nivel {{ $personaje->nivel }}</span>
            @auth
            </a>
            @else
            </div>
            @endauth
            @endforeach
        </div>
    </section>
    @endif

    {{-- INFORMACIÓN D&D --}}
    <section class="bloque">
        <h2 class="seccion-titulo">@include('partials.icon', ['name' => 'scroll']) El Mundo de D&D</h2>
        <div class="info-grid">
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'scroll', 'class' => 'icon-lg'])</span>
                <h3>¿Qué es D&D?</h3>
                <p>El juego de rol de fantasía más influyente de la historia. Creas un personaje, el Dungeon Master narra el mundo y los dados deciden tu destino.</p>
            </article>
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'dice', 'class' => 'icon-lg'])</span>
                <h3>Los Dados Sagrados</h3>
                <p>El d4, d6, d8, d10, d12 y el rey absoluto: el d20. Cada tirada puede convertirte en leyenda o en cenizas. Un 20 natural es la gracia de los dioses.</p>
            </article>
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'book', 'class' => 'icon-lg'])</span>
                <h3>Campañas Épicas</h3>
                <p>Historias que pueden durar meses o años. Cada sesión es un capítulo en la crónica de tu grupo. Los fracasos dan sabor; los triunfos, gloria.</p>
            </article>
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'dragon', 'class' => 'icon-lg'])</span>
                <h3>Dragones</h3>
                <p>Las criaturas más icónicas. Cromáticos de naturaleza malvada, metálicos de alineamiento noble. Desde el ardiente Rojo hasta el sabio Platino.</p>
            </article>
        </div>
    </section>

    {{-- DATOS CURIOSOS --}}
    @if(isset($datosCuriosos) && count($datosCuriosos) > 0)
    <section class="bloque">
        <h2 class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Saber del Viejo Mundo</h2>
        <div class="curiosidades-grid">
            @foreach($datosCuriosos as $dato)
            <div class="curiosidad">
                <strong>{{ $dato['titulo'] }}</strong>
                <p>{{ $dato['texto'] }}</p>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- CAMPAÑAS ACTIVAS --}}
    @if(isset($campanasActivas) && $campanasActivas->count() > 0)
    <section class="bloque">
        <h2 class="seccion-titulo">@include('partials.icon', ['name' => 'swords']) Campañas en Curso</h2>
        <div class="campanas-grid">
            @foreach($campanasActivas as $campana)
            <div class="campana-card">
                <h4>@include('partials.icon', ['name' => 'scroll']) {{ $campana->nombre }}</h4>
                <div class="campana-dm">DM: {{ $campana->dungeonMaster->nombre ?? 'Desconocido' }}</div>
                @if($campana->descripcion)
                    <p class="campana-desc">{{ Str::limit($campana->descripcion, 110) }}</p>
                @endif
                <span class="badge badge-verde">
                    Niveles {{ $campana->nivel_inicial ?? 1 }}–{{ $campana->nivel_maximo ?? '∞' }}
                </span>
            </div>
            @endforeach
        </div>
    </section>
    @endif

</div>
@endsection