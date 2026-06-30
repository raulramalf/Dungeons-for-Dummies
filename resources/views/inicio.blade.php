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
            Lleva tus personajes, organiza tus campañas y cuenta lo que pasó en mesa.
        </p>
        <div class="hero-acciones">
            @auth
                <a href="{{ route('personajes.create') }}" class="btn btn-primario">
                    Crear Personaje
                </a>
                <a href="{{ route('feed.index') }}" class="btn btn-secundario">
                    Entrar a la Taberna
                </a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primario">
                    Comenzar la Aventura
                </a>
                <a href="{{ route('login') }}" class="btn btn-secundario">
                    Iniciar Sesión
                </a>
            @endauth
        </div>
    </section>

    {{-- TU PRÓXIMA SESIÓN --}}
    @auth
        @if($proximaSesion)
        <section class="bloque">
            <div class="proxima-sesion">
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
            </div>
        </section>
        @endif
    @endauth

    {{-- LA BITÁCORA --}}
    @if($bitacora->count() > 0)
    <section class="bloque">
        <h2 class="seccion-titulo">La Bitácora</h2>
        <p class="seccion-subtitulo">Lo último que ha pasado en la comunidad, según fue ocurriendo</p>

        <div class="bitacora">
            @foreach($bitacora as $entrada)
                @php $item = $entrada['data']; @endphp
                <div class="bitacora-fila">
                    <div class="bitacora-marcador">
                        @switch($entrada['tipo'])
                            @case('hazana')
                                @include('partials.icon', ['name' => 'comment'])
                                @break
                            @case('personaje')
                                @include('partials.icon', ['name' => 'sword'])
                                @break
                            @case('campana')
                                @include('partials.icon', ['name' => 'shield'])
                                @break
                        @endswitch
                    </div>
                    <div class="bitacora-contenido">
                        @switch($entrada['tipo'])
                            @case('hazana')
                                <span class="bitacora-eyebrow">Taberna</span>
                                <p class="bitacora-texto">
                                    <strong>{{ $item->usuario->nombre }}</strong>
                                    contó: {{ Str::limit($item->contenido, 140) }}
                                </p>
                                @break
                            @case('personaje')
                                <span class="bitacora-eyebrow">Nuevo aventurero</span>
                                <p class="bitacora-texto">
                                    <strong>{{ $item->usuario->nombre }}</strong>
                                    creó a {{ $item->nombre }}
                                    @if($item->raza || $item->clase)
                                        ({{ $item->raza->nombre ?? '' }} {{ $item->clase->nombre ?? '' }})
                                    @endif
                                </p>
                                @break
                            @case('campana')
                                <span class="bitacora-eyebrow">Mesa abierta</span>
                                <p class="bitacora-texto">
                                    <strong>{{ $item->dungeonMaster->nombre ?? 'Alguien' }}</strong>
                                    abrió la campaña {{ $item->nombre }}
                                </p>
                                @break
                        @endswitch
                        <span class="bitacora-fecha">{{ $entrada['fecha']->diffForHumans() }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif

</div>

<style>
/* ----- PRÓXIMA SESIÓN ----- */
.proxima-sesion {
    display: flex;
    align-items: flex-start;
    gap: 1.1rem;
    background: rgba(179,3,3,0.07);
    border-left: 3px solid var(--c-rojo);
    border-radius: 0 var(--r-md) var(--r-md) 0;
    padding: 1.2rem 1.5rem;
}

.proxima-sesion-icono {
    color: var(--c-rojo);
    flex-shrink: 0;
    margin-top: 0.15rem;
}

.proxima-sesion-eyebrow {
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.72rem;
    color: var(--t-tenue);
    margin-bottom: 0.25rem;
}

.proxima-sesion-cuerpo h3 {
    font-family: var(--f-titulo);
    font-size: 1.1rem;
    margin: 0 0 0.3rem;
}

.proxima-sesion-cuerpo p {
    color: var(--t-secundario);
    font-size: 0.92rem;
    margin: 0;
}

/* ----- LA BITÁCORA (timeline) ----- */
.bitacora {
    position: relative;
    margin-top: 0.5rem;
}

.bitacora::before {
    content: '';
    position: absolute;
    left: 17px;
    top: 6px;
    bottom: 6px;
    width: 1px;
    background: var(--b-sutil);
}

.bitacora-fila {
    position: relative;
    display: flex;
    gap: 1.1rem;
    padding: 0.85rem 0;
}

.bitacora-marcador {
    position: relative;
    z-index: 1;
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--c-superficie);
    border: 1px solid var(--b-medio);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--c-rojo-claro);
}

.bitacora-contenido {
    flex: 1;
    padding-top: 0.2rem;
}

.bitacora-eyebrow {
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.7rem;
    color: var(--t-tenue);
    margin-bottom: 0.2rem;
}

.bitacora-texto {
    color: var(--t-principal);
    font-size: 0.96rem;
    line-height: 1.55;
    margin: 0 0 0.2rem;
}

.bitacora-texto strong {
    color: #D46043;
}

.bitacora-fecha {
    color: var(--t-tenue);
    font-size: 0.78rem;
}

@media (max-width: 600px) {
    .proxima-sesion { flex-direction: column; }
}
</style>
@endsection