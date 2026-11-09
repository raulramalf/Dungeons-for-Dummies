@extends('layouts.app')

@section('titulo', 'Inicio')
@section('descripcion', 'Lleva tus personajes de D&D 5.5e, organiza tus campañas y cuenta lo que pasó en partida.')

@push('head')
    @vite(['resources/css/pages/inicio.css', 'resources/js/pages/inicio.js'])
@endpush

{{-- El hero hace de cabecera: el <h1> de la página queda solo para lectores de pantalla --}}
@section('cabecera-oculta', '1')

@section('contenido')
@include('partials.d20-button')

<x-bg-media nombre="castillo-fondo" />

<div class="contenedor">

    {{-- HERO --}}
    <section class="hero">
        <p class="hero-eyebrow">
            @auth Bienvenido de nuevo, {{ auth()->user()->nombre }} @else D&amp;D 5.5e · en español @endauth
        </p>
        <h2 class="hero-titulo">Forja tu Leyenda</h2>
        <p class="hero-sub">
            Lleva tus personajes, organiza tus campañas y cuenta lo que pasó en partida.
        </p>
        <div class="hero-acciones">
            @auth
                <a href="{{ route('personajes.create') }}" class="btn btn-primario">
                    @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Crear personaje
                </a>
                <a href="{{ route('feed.index') }}" class="btn btn-secundario">Entrar a la Taberna</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primario">Comenzar la aventura</a>
                <a href="{{ route('login') }}" class="btn btn-secundario">Iniciar sesión</a>
            @endauth
        </div>
    </section>

    @guest
        {{-- QUÉ PUEDES HACER --}}
        <section class="bloque" aria-labelledby="titulo-funciones">
            <h2 class="seccion-titulo" id="titulo-funciones">Todo para tu mesa</h2>
            <div class="funciones">
                <article class="funcion">
                    @include('partials.icon', ['name' => 'sword', 'class' => 'icon-lg'])
                    <h3>Fichas de personaje</h3>
                    <p>Crea y sube de nivel héroes con las reglas 5.5e: especies, clases, subclases, dotes y conjuros. Exporta la ficha en PDF.</p>
                </article>
                <article class="funcion">
                    @include('partials.icon', ['name' => 'scroll', 'class' => 'icon-lg'])
                    <h3>Campañas</h3>
                    <p>Invita a tu grupo con un código, planifica sesiones en el calendario y guarda las notas del máster.</p>
                </article>
                <article class="funcion">
                    @include('partials.icon', ['name' => 'feed', 'class' => 'icon-lg'])
                    <h3>La Taberna</h3>
                    <p>Cuenta tus hazañas, pide consejo sobre reglas y comparte contenido casero con otros aventureros.</p>
                </article>
            </div>
        </section>
    @endguest

    @auth
        {{-- TU PRÓXIMA SESIÓN --}}
        @if ($proximaSesion)
            <section class="bloque" aria-label="Tu próxima sesión">
                <a class="proxima-sesion" href="{{ url('/campanyas/'.$proximaSesion->campana_id) }}">
                    <div class="proxima-sesion-icono">
                        @include('partials.icon', ['name' => 'scroll', 'class' => 'icon-lg'])
                    </div>
                    <div class="proxima-sesion-cuerpo">
                        <span class="proxima-sesion-eyebrow">Tu próxima mesa</span>
                        <h3>{{ $proximaSesion->campana->nombre }} — {{ $proximaSesion->titulo }}</h3>
                        <p>
                            {{ $proximaSesion->fecha_sesion->translatedFormat('l d \d\e F, H:i') }}
                            · DM: {{ $proximaSesion->campana->dungeonMaster->nombre ?? 'Desconocido' }}
                        </p>
                    </div>
                    @include('partials.icon', ['name' => 'chevron-right', 'class' => 'proxima-sesion-flecha'])
                </a>
            </section>
        @endif

        {{-- CALENDARIO DE SESIONES --}}
        <section class="bloque" aria-labelledby="titulo-calendario">
            <h2 class="seccion-titulo" id="titulo-calendario">Tu calendario</h2>

            <div class="calendario-wrap" data-calendario
                 data-sesiones="{{ json_encode($sesionesCalendario->groupBy('fecha'), JSON_UNESCAPED_UNICODE) }}">
                <div class="calendario-header">
                    <button type="button" class="cal-nav" data-cal-prev aria-label="Mes anterior">
                        @include('partials.icon', ['name' => 'chevron-left'])
                    </button>
                    <h3 data-cal-titulo aria-live="polite"></h3>
                    <button type="button" class="cal-nav" data-cal-next aria-label="Mes siguiente">
                        @include('partials.icon', ['name' => 'chevron-right'])
                    </button>
                </div>
                <div class="calendario-dias-semana" aria-hidden="true">
                    <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
                </div>
                <div class="calendario-grid" data-cal-grid></div>
                <button type="button" class="cal-hoy-btn" data-cal-hoy>Ir a hoy</button>
            </div>
            <div class="cal-sesiones-dia" data-cal-detalle aria-live="polite"></div>
        </section>
    @endauth

</div>
@endsection
