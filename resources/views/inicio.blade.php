@extends('layouts.app')

@section('titulo', 'Forja tu Leyenda')

@section('contenido')
<style>
    /* =============================================
       HERO
    ============================================= */
    .hero {
        position: relative;
        background:
            linear-gradient(160deg, rgba(13,5,5,0.92) 0%, rgba(179,3,3,0.18) 100%),
            url('https://images.unsplash.com/photo-1612537785149-c3ea13c7cb01?w=1600&h=600&fit=crop&q=80') center/cover no-repeat;
        border: 1px solid rgba(179,3,3,0.35);
        border-radius: 14px;
        padding: 5rem 3rem;
        text-align: center;
        margin-bottom: 3.5rem;
        overflow: hidden;
    }

    .hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: repeating-linear-gradient(
            0deg,
            transparent,
            transparent 2px,
            rgba(0,0,0,0.06) 2px,
            rgba(0,0,0,0.06) 4px
        );
        pointer-events: none;
    }

    .hero-ornamento {
        font-size: 3rem;
        display: block;
        margin-bottom: 0.5rem;
        filter: drop-shadow(0 0 12px rgba(179,3,3,0.7));
    }

    .hero h1 {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: clamp(2.2rem, 5vw, 4rem);
        color: #fff;
        letter-spacing: 4px;
        text-shadow: 0 4px 24px rgba(0,0,0,0.9), 0 0 40px rgba(179,3,3,0.3);
        margin-bottom: 1.2rem;
        line-height: 1.15;
    }

    .hero-subtitle {
        font-size: 1.15rem;
        color: #a0a8b0;
        max-width: 640px;
        margin: 0 auto 2.5rem;
        line-height: 1.9;
        font-style: italic;
    }

    .hero-divider {
        width: 80px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #B30303, transparent);
        margin: 0 auto 2.5rem;
    }

    .hero-btns {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-medieval {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.9rem 2.2rem;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.25s;
        letter-spacing: 0.5px;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-medieval.primary {
        background: #B30303;
        color: #fff;
        box-shadow: 0 4px 18px rgba(179,3,3,0.4), inset 0 1px 0 rgba(255,255,255,0.1);
    }
    .btn-medieval.primary:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(179,3,3,0.5);
    }

    .btn-medieval.secondary {
        background: rgba(255,255,255,0.06);
        color: #c8cdd2;
        border: 1px solid rgba(255,255,255,0.15);
    }
    .btn-medieval.secondary:hover {
        background: rgba(255,255,255,0.12);
        color: #fff;
        transform: translateY(-2px);
    }

    /* =============================================
       SECCIONES GENERALES
    ============================================= */
    .seccion {
        margin-bottom: 3.5rem;
    }

    .seccion-cabecera {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.8rem;
    }

    .seccion-cabecera h2 {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 1.4rem;
        color: #fff;
        letter-spacing: 2px;
        text-transform: uppercase;
    }

    .seccion-linea {
        flex: 1;
        height: 1px;
        background: linear-gradient(90deg, rgba(179,3,3,0.5), transparent);
    }

    /* =============================================
       CAROUSEL DE PERSONAJES
    ============================================= */
    .carousel-outer {
        position: relative;
        overflow: hidden;
    }

    .carousel-track-wrap {
        overflow: hidden;
    }

    .carousel-track {
        display: flex;
        gap: 1.2rem;
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        will-change: transform;
    }

    .hero-card {
        flex: 0 0 200px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(179,3,3,0.18);
        border-radius: 10px;
        padding: 1.4rem 1rem;
        text-align: center;
        text-decoration: none;
        color: #fff;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .hero-card::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, #B30303, transparent);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .hero-card:hover {
        border-color: rgba(179,3,3,0.5);
        background: rgba(179,3,3,0.06);
        transform: translateY(-5px);
        box-shadow: 0 12px 35px rgba(0,0,0,0.4);
    }

    .hero-card:hover::after {
        opacity: 1;
    }

    .hero-card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #B30303;
        margin-bottom: 0.9rem;
        box-shadow: 0 0 18px rgba(179,3,3,0.25);
    }

    .hero-card-nombre {
        font-weight: 700;
        font-size: 1rem;
        margin-bottom: 0.3rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .hero-card-meta {
        color: #768596;
        font-size: 0.82rem;
        margin-bottom: 0.6rem;
    }

    .nivel-badge {
        display: inline-block;
        background: #B30303;
        color: #fff;
        padding: 0.15rem 0.7rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .carousel-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 38px;
        height: 38px;
        background: rgba(13,5,5,0.9);
        border: 1px solid rgba(179,3,3,0.4);
        border-radius: 50%;
        color: #fff;
        font-size: 1.3rem;
        line-height: 1;
        cursor: pointer;
        z-index: 10;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .carousel-btn:hover {
        background: #B30303;
        border-color: #B30303;
    }

    .carousel-btn.prev { left: -18px; }
    .carousel-btn.next { right: -18px; }

    .carousel-empty {
        text-align: center;
        padding: 2rem;
        color: #768596;
        border: 1px dashed rgba(179,3,3,0.2);
        border-radius: 10px;
    }

    /* =============================================
       GRID INFO D&D
    ============================================= */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.4rem;
    }

    .info-card {
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(179,3,3,0.14);
        border-radius: 10px;
        padding: 1.8rem;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .info-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 0;
        background: #B30303;
        transition: height 0.3s;
        border-radius: 3px 0 0 3px;
    }

    .info-card:hover::before {
        height: 100%;
    }

    .info-card:hover {
        border-color: rgba(179,3,3,0.35);
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.35);
    }

    .info-card-icono {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.9rem;
    }

    .info-card h3 {
        color: #D46043;
        font-size: 1.1rem;
        margin-bottom: 0.6rem;
        font-family: 'Cinzel', 'Georgia', serif;
    }

    .info-card p {
        color: #7f8d9a;
        line-height: 1.65;
        font-size: 0.95rem;
    }

    /* =============================================
       DATOS CURIOSOS
    ============================================= */
    .curiosidades-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.2rem;
    }

    .curiosidad {
        background: rgba(0,0,0,0.25);
        border-left: 3px solid #B30303;
        border-radius: 0 8px 8px 0;
        padding: 1.1rem 1.3rem;
        transition: background 0.3s;
    }

    .curiosidad:hover {
        background: rgba(179,3,3,0.07);
    }

    .curiosidad strong {
        color: #D46043;
        display: block;
        font-size: 0.9rem;
        margin-bottom: 0.35rem;
        font-family: 'Cinzel', 'Georgia', serif;
    }

    .curiosidad p {
        color: #7f8d9a;
        font-size: 0.88rem;
        line-height: 1.55;
        margin: 0;
    }

    /* =============================================
       CAMPAÑAS
    ============================================= */
    .campanas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 1.4rem;
    }

    .campana-card {
        background: rgba(255,255,255,0.025);
        border: 1px solid rgba(179,3,3,0.14);
        border-radius: 10px;
        padding: 1.6rem;
        transition: all 0.3s;
    }

    .campana-card:hover {
        border-color: rgba(179,3,3,0.4);
        transform: translateY(-3px);
        box-shadow: 0 8px 28px rgba(0,0,0,0.35);
    }

    .campana-card h4 {
        color: #fff;
        font-size: 1.05rem;
        margin-bottom: 0.4rem;
        font-family: 'Cinzel', 'Georgia', serif;
    }

    .campana-card .dm {
        color: #768596;
        font-size: 0.85rem;
        margin-bottom: 0.7rem;
    }

    .campana-card .desc {
        color: #768596;
        font-size: 0.88rem;
        line-height: 1.5;
        margin-bottom: 0.9rem;
    }

    .badge-niveles {
        display: inline-block;
        background: rgba(64, 72, 52, 0.6);
        border: 1px solid #404834;
        color: #9ab090;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    /* =============================================
       ALERT FLASH
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

    /* =============================================
       RESPONSIVE
    ============================================= */
    @media (max-width: 768px) {
        .hero { padding: 3rem 1.5rem; }
        .info-grid { grid-template-columns: 1fr; }
        .carousel-btn.prev { left: 0; }
        .carousel-btn.next { right: 0; }
    }
</style>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

{{-- ===== HERO ===== --}}
<div class="hero">
    <span class="hero-ornamento">⚔️</span>
    <h1>Forja tu Leyenda</h1>
    <div class="hero-divider"></div>
    <p class="hero-subtitle">
        El grimorio digital para gestionar tus campañas, dar vida a héroes inolvidables
        y compartir tus hazañas con la comunidad.
    </p>
    <div class="hero-btns">
        @auth
            <a href="{{ route('personajes.create') }}" class="btn-medieval primary">
                ⚔️ Crear Personaje
            </a>
            <a href="{{ route('feed.index') }}" class="btn-medieval secondary">
                🍺 Entrar a la Taberna
            </a>
        @else
            <a href="{{ route('register') }}" class="btn-medieval primary">
                📜 Comenzar la Aventura
            </a>
            <a href="{{ route('login') }}" class="btn-medieval secondary">
                🔑 Iniciar Sesión
            </a>
        @endauth
    </div>
</div>

{{-- ===== CAROUSEL DE PERSONAJES ===== --}}
@if(isset($personajesDestacados) && $personajesDestacados->count() > 0)
<div class="seccion">
    <div class="seccion-cabecera">
        <h2>🌟 Héroes del Realm</h2>
        <div class="seccion-linea"></div>
    </div>

    <div class="carousel-outer" id="carouselOuter">
        <button class="carousel-btn prev" onclick="moverCarousel(-1)" aria-label="Anterior">‹</button>
        <div class="carousel-track-wrap" id="carouselWrap">
            <div class="carousel-track" id="carouselTrack">
                @foreach($personajesDestacados as $personaje)
                @auth
                <a href="{{ route('personajes.show', $personaje) }}" class="hero-card">
                @else
                <div class="hero-card" style="cursor:default">
                @endauth
                    <img src="{{ $personaje->avatar_url }}" alt="{{ $personaje->nombre }}">
                    <div class="hero-card-nombre">{{ $personaje->nombre }}</div>
                    <div class="hero-card-meta">
                        {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
                    </div>
                    <span class="nivel-badge">Nivel {{ $personaje->nivel }}</span>
                @auth
                </a>
                @else
                </div>
                @endauth
                @endforeach
            </div>
        </div>
        <button class="carousel-btn next" onclick="moverCarousel(1)" aria-label="Siguiente">›</button>
    </div>
</div>
@endif

{{-- ===== INFORMACIÓN D&D ===== --}}
<div class="seccion">
    <div class="seccion-cabecera">
        <h2>📜 El Mundo de D&D</h2>
        <div class="seccion-linea"></div>
    </div>
    <div class="info-grid">
        <div class="info-card">
            <span class="info-card-icono">📜</span>
            <h3>¿Qué es D&D?</h3>
            <p>Dungeons & Dragons es el juego de rol de fantasía más influyente de la historia. Creas un personaje, el Dungeon Master narra el mundo y los dados deciden tu destino.</p>
        </div>
        <div class="info-card">
            <span class="info-card-icono">🎲</span>
            <h3>Los Dados Sagrados</h3>
            <p>El d4, d6, d8, d10, d12 y el rey absoluto: el d20. Cada tirada puede convertirte en leyenda o en cenizas. Un 20 natural es la gracia de los dioses.</p>
        </div>
        <div class="info-card">
            <span class="info-card-icono">🏰</span>
            <h3>Campañas Épicas</h3>
            <p>Historias que pueden durar meses o años. Cada sesión es un capítulo en la crónica de tu grupo. Los fracasos dan sabor; los triunfos, gloria.</p>
        </div>
        <div class="info-card">
            <span class="info-card-icono">🐉</span>
            <h3>Dragones</h3>
            <p>Las criaturas más icónicas. Cromáticos de naturaleza malvada, metálicos de alineamiento noble. Desde el ardiente Rojo hasta el sabio Platino.</p>
        </div>
    </div>
</div>

{{-- ===== DATOS CURIOSOS ===== --}}
@if(isset($datosCuriosos) && count($datosCuriosos) > 0)
<div class="seccion">
    <div class="seccion-cabecera">
        <h2>📖 Saber del Viejo Mundo</h2>
        <div class="seccion-linea"></div>
    </div>
    <div class="curiosidades-grid">
        @foreach($datosCuriosos as $dato)
        <div class="curiosidad">
            <strong>{{ $dato['titulo'] }}</strong>
            <p>{{ $dato['texto'] }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- ===== CAMPAÑAS ACTIVAS ===== --}}
@if(isset($campanasActivas) && $campanasActivas->count() > 0)
<div class="seccion">
    <div class="seccion-cabecera">
        <h2>⚔️ Campañas en Curso</h2>
        <div class="seccion-linea"></div>
    </div>
    <div class="campanas-grid">
        @foreach($campanasActivas as $campana)
        <div class="campana-card">
            <h4>📜 {{ $campana->nombre }}</h4>
            <div class="dm">DM: {{ $campana->dungeonMaster->nombre ?? 'Desconocido' }}</div>
            @if($campana->descripcion)
                <div class="desc">{{ Str::limit($campana->descripcion, 110) }}</div>
            @endif
            <span class="badge-niveles">
                Niveles {{ $campana->nivel_inicial ?? 1 }}–{{ $campana->nivel_maximo ?? '∞' }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endif

<script>
(function () {
    let pos = 0;
    const CARD_W = 212; // 200px ancho + 12px gap

    function moverCarousel(dir) {
        const track  = document.getElementById('carouselTrack');
        const wrap   = document.getElementById('carouselWrap');
        if (!track) return;

        const cards   = track.querySelectorAll('.hero-card');
        const visible = Math.max(1, Math.floor(wrap.offsetWidth / CARD_W));
        const max     = Math.max(0, cards.length - visible);

        pos = Math.min(Math.max(pos + dir, 0), max);
        track.style.transform = `translateX(-${pos * CARD_W}px)`;
    }

    // Exponer globalmente para los botones onclick
    window.moverCarousel = moverCarousel;

    // Swipe táctil
    const wrap = document.getElementById('carouselWrap');
    if (wrap) {
        let startX = 0;
        wrap.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
        wrap.addEventListener('touchend', e => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) moverCarousel(diff > 0 ? 1 : -1);
        }, { passive: true });
    }

    // Animación de entrada para las tarjetas info
    const cards = document.querySelectorAll('.info-card, .curiosidad, .campana-card');
    const io = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(18px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        io.observe(card);
    });
})();
</script>
@endsection