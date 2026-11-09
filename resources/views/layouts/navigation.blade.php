@php
    /*
     * Única fuente de verdad de la navegación. La usan la barra lateral
     * (escritorio), la barra inferior y la hoja "Más" (móvil).
     */
    $nav = [
        ['url' => url('/'),           'icono' => 'home',   'texto' => 'Inicio',      'activo' => request()->is('/')],
        ['url' => url('/personajes'), 'icono' => 'sword',  'texto' => 'Personajes',  'activo' => request()->is('personajes*')],
        ['url' => url('/campanyas'),  'icono' => 'scroll', 'texto' => 'Campañas',    'activo' => request()->is('campanyas*')],
        ['url' => url('/feed'),       'icono' => 'feed',   'texto' => 'Taberna',     'activo' => request()->is('feed*')],
        ['url' => url('/enemigos'),   'icono' => 'skull',  'texto' => 'Bestiario',   'activo' => request()->is('enemigos*')],
        ['url' => url('/minijuego'),  'icono' => 'dice',   'texto' => 'Reto Diario', 'activo' => request()->is('minijuego*')],
        ['url' => url('/perfil'),     'icono' => 'user',   'texto' => 'Perfil',      'activo' => request()->is('perfil*')],
    ];
    $principales = array_slice($nav, 0, 4);   // barra inferior
    $secundarios = array_slice($nav, 4);      // hoja "Más"
    $masActivo   = collect($secundarios)->contains('activo', true);
    $usuario     = auth()->user();
@endphp

{{-- ======================= BARRA LATERAL (escritorio) ======================= --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <a href="{{ url('/') }}" class="marca-logo" aria-label="Dungeons for Dummies, ir al inicio">
            <x-logo-mark />
            <span class="marca-texto">
                <span class="marca">Dungeons</span>
                <span class="sub">for Dummies</span>
            </span>
        </a>
    </div>

    <nav class="sidebar-nav" aria-label="Principal">
        @foreach ($nav as $item)
            <a href="{{ $item['url'] }}" class="nav-link" @if ($item['activo']) aria-current="page" @endif>
                @include('partials.icon', ['name' => $item['icono']])
                {{ $item['texto'] }}
            </a>
        @endforeach
    </nav>

    <div class="sidebar-footer">
        @auth
            <div class="sidebar-usuario">
                <a href="{{ url('/perfil') }}" class="sidebar-usuario-info" aria-label="Mi perfil">
                    <img class="avatar" src="{{ $usuario->avatar_url }}" alt="" width="38" height="38" decoding="async">
                    <span class="usuario-datos">
                        <span class="usuario-nombre">{{ $usuario->nombre }}</span>
                        <span class="usuario-rol">{{ str_replace('_', ' ', $usuario->rol ?? 'aventurero') }}</span>
                    </span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-icono" aria-label="Cerrar sesión" title="Cerrar sesión">
                        @include('partials.icon', ['name' => 'logout'])
                    </button>
                </form>
            </div>
        @else
            <div class="sidebar-cta">
                <a href="{{ route('login') }}" class="btn btn-primario">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="btn btn-secundario">Crear cuenta</a>
            </div>
        @endauth
    </div>
</aside>

{{-- ======================= BARRA SUPERIOR (móvil) ======================= --}}
<header class="topbar">
    <a href="{{ url('/') }}" class="marca-logo" aria-label="Dungeons for Dummies, ir al inicio">
        <x-logo-mark />
    </a>
    <span class="topbar-titulo" aria-hidden="true">@yield('titulo')</span>
    @auth
        <a href="{{ url('/perfil') }}" aria-label="Mi perfil">
            <img class="avatar" src="{{ $usuario->avatar_url }}" alt="" width="34" height="34" decoding="async">
        </a>
    @endauth
</header>

{{-- ======================= BARRA INFERIOR (móvil) ======================= --}}
<nav class="bottomnav" aria-label="Navegación inferior">
    @auth
        @foreach ($principales as $item)
            <a href="{{ $item['url'] }}" class="tab" @if ($item['activo']) aria-current="page" @endif>
                @include('partials.icon', ['name' => $item['icono']])
                <span>{{ $item['texto'] }}</span>
            </a>
        @endforeach
        <button type="button" class="tab" data-sheet="sheet-mas" aria-haspopup="dialog" @if ($masActivo) aria-current="page" @endif>
            @include('partials.icon', ['name' => 'more'])
            <span>Más</span>
        </button>
    @else
        <a href="{{ $nav[0]['url'] }}" class="tab" @if ($nav[0]['activo']) aria-current="page" @endif>
            @include('partials.icon', ['name' => 'home'])
            <span>Inicio</span>
        </a>
        <a href="{{ route('login') }}" class="tab">
            @include('partials.icon', ['name' => 'login'])
            <span>Entrar</span>
        </a>
        <a href="{{ route('register') }}" class="tab">
            @include('partials.icon', ['name' => 'user'])
            <span>Registrarse</span>
        </a>
    @endauth
</nav>

{{-- ======================= HOJA "MÁS" (móvil) ======================= --}}
@auth
    <dialog class="sheet" id="sheet-mas" aria-label="Más opciones">
        <div class="sheet-grabber" aria-hidden="true"></div>
        <div class="sheet-cuerpo">
            <a href="{{ url('/perfil') }}" class="sheet-usuario">
                <img class="avatar" src="{{ $usuario->avatar_url }}" alt="" width="38" height="38" decoding="async">
                <span class="usuario-datos">
                    <span class="usuario-nombre">{{ $usuario->nombre }}</span>
                    <span class="usuario-rol">Ver mi perfil</span>
                </span>
            </a>
            @foreach ($secundarios as $item)
                <a href="{{ $item['url'] }}" class="nav-link" @if ($item['activo']) aria-current="page" @endif>
                    @include('partials.icon', ['name' => $item['icono']])
                    {{ $item['texto'] }}
                </a>
            @endforeach
            <form method="POST" action="{{ route('logout') }}" class="sheet-form">
                @csrf
                <button type="submit" class="nav-link">
                    @include('partials.icon', ['name' => 'logout'])
                    Cerrar sesión
                </button>
            </form>
        </div>
    </dialog>
@endauth
