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

    {{-- ÚLTIMAS HAZAÑAS DE LA TABERNA --}}
    @if(isset($ultimasHazanas) && $ultimasHazanas->count() > 0)
    <section class="bloque">
        <h2 class="seccion-titulo">Crónicas de la Taberna</h2>
        <p class="seccion-subtitulo">Lo que se está contando ahora mismo entre jarras y dados</p>
        <div class="hazanas-lista">
            @foreach($ultimasHazanas as $hazana)
            <div class="hazana-item">
                <div class="hazana-autor">
                    <img src="{{ $hazana->usuario->avatar
                        ? $hazana->usuario->avatar
                        : 'https://ui-avatars.com/api/?name=' . urlencode($hazana->usuario->nombre) . '&background=B30303&color=fff&size=32' }}"
                        alt="{{ $hazana->usuario->nombre }}">
                    <span class="hazana-nombre">{{ $hazana->usuario->nombre }}</span>
                    <span class="hazana-tiempo">· {{ $hazana->created_at->diffForHumans() }}</span>
                </div>
                <div class="hazana-texto">{{ Str::limit($hazana->contenido, 160) }}</div>
                <div class="hazana-footer">
                    @if($hazana->etiquetas && count($hazana->etiquetas) > 0)
                        @foreach(array_slice($hazana->etiquetas, 0, 3) as $et)
                            <span class="hazana-tag">#{{ $et }}</span>
                        @endforeach
                    @endif
                    <span class="hazana-likes">{{ $hazana->likes_count }} votos</span>
                </div>
            </div>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:1.5rem;">
            @auth
                <a href="{{ route('feed.index') }}" class="btn btn-secundario" style="display:inline-flex;">
                    Ver toda la Taberna
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-secundario" style="display:inline-flex;">
                    Entra para participar
                </a>
            @endauth
        </div>
    </section>
    @endif

    {{-- INFORMACIÓN D&D --}}
    <section class="bloque">
        <h2 class="seccion-titulo">El Mundo de D&D</h2>
        <div class="info-grid">
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'scroll', 'class' => 'icon-lg'])</span>
                <h3>¿Qué es D&D?</h3>
                <p>Un juego de rol de fantasía donde tú decides quién eres. El Dungeon Master monta el mundo, los demás lo destrozan. Los dados hacen de árbitros cuando nadie se pone de acuerdo. Desde 1974 arruinando planes perfectos.</p>
            </article>
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'dice', 'class' => 'icon-lg'])</span>
                <h3>Los Dados Sagrados</h3>
                <p>d4, d6, d8, d10, d12 y el d20 que gobierna sobre todos. Un 20 natural te convierte en leyenda. Un 1 te hace caer al suelo de formas que nadie esperaba. Siempre en el peor momento posible.</p>
            </article>
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'book', 'class' => 'icon-lg'])</span>
                <h3>Campañas Épicas</h3>
                <p>Sesiones que empiezan un viernes a las 8 y acaban el sábado a las 3 de la mañana. Historias que el grupo recordará años. Los momentos más épicos no están en ningún libro: los habéis inventado vosotros.</p>
            </article>
            <article class="info-card">
                <span class="info-icono">@include('partials.icon', ['name' => 'dragon', 'class' => 'icon-lg'])</span>
                <h3>Dragones</h3>
                <p>Cromáticos malvados, metálicos nobles. El Rojo quema aldeas por aburrimiento. El Platino te juzga antes de hablar. Y el Verde miente tan bien que no sabrás que lo ha hecho hasta tres sesiones después.</p>
            </article>
        </div>
    </section>

    {{-- DATOS CURIOSOS --}}
    @if(isset($datosCuriosos) && count($datosCuriosos) > 0)
    <section class="bloque">
        <h2 class="seccion-titulo">Saber del Viejo Mundo</h2>
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
        <h2 class="seccion-titulo">Campañas en Curso</h2>
        <div class="campanas-grid">
            @foreach($campanasActivas as $campana)
            <div class="campana-card">
                <h4>{{ $campana->nombre }}</h4>
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

<style>
/* Sección crónicas de la taberna */
.seccion-subtitulo {
    color: var(--t-secundario);
    font-size: 0.9rem;
    margin-top: -0.8rem;
    margin-bottom: 1.4rem;
    font-style: italic;
}

.hazanas-lista {
    display: flex;
    flex-direction: column;
    gap: 0.9rem;
}

.hazana-item {
    background: rgba(255,255,255,0.025);
    border: 1px solid rgba(179,3,3,0.18);
    border-radius: 8px;
    padding: 1.1rem 1.3rem;
    transition: border-color 0.25s;
}

.hazana-item:hover {
    border-color: rgba(179,3,3,0.38);
}

.hazana-autor {
    display: flex;
    align-items: center;
    gap: 0.55rem;
    margin-bottom: 0.55rem;
}

.hazana-autor img {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    border: 1px solid #B30303;
}

.hazana-nombre {
    font-weight: 700;
    color: #D46043;
    font-size: 0.92rem;
}

.hazana-tiempo {
    color: #768596;
    font-size: 0.8rem;
}

.hazana-texto {
    color: #d0d5da;
    font-size: 0.97rem;
    line-height: 1.6;
    margin-bottom: 0.7rem;
}

.hazana-footer {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.hazana-tag {
    background: rgba(179,3,3,0.1);
    color: #768596;
    border: 1px solid rgba(179,3,3,0.2);
    padding: 0.15rem 0.6rem;
    border-radius: 20px;
    font-size: 0.75rem;
}

.hazana-likes {
    margin-left: auto;
    color: #768596;
    font-size: 0.82rem;
}
</style>
@endsection