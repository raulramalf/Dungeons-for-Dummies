@extends('layouts.app')

@section('titulo', 'Gremio de Héroes')

@section('contenido')
<style>
    .gremio-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 0 2.5rem;
    }

    .header-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-actions h2 {
        font-family: 'Cinzel', 'Georgia', serif;
        color: #fff;
        font-size: 2rem;
        letter-spacing: 2px;
    }

    .btn-crear {
        background: var(--color-rojo);
        color: #fff;
        padding: 0.8rem 2rem;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-family: inherit;
    }

    .btn-crear:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(179,3,3,0.35);
    }

    /* =============================================
       CAROUSEL
    ============================================= */
    .carousel-wrap {
        position: relative;
        width: 100%;
        margin: 0 auto;
    }

    /* El scene es el área visible — overflow hidden aquí */
    .carousel-scene {
        position: relative;
        width: 100%;
        height: 620px;
        overflow: hidden;
    }

    /* Las cartas se posicionan con JS usando left: en px calculado desde el centro */
    .hero-slide {
        position: absolute;
        top: 50%;
        border-radius: 14px;
        overflow: hidden;
        cursor: pointer;
        transition:
            left      0.5s cubic-bezier(0.25,0.46,0.45,0.94),
            width     0.5s cubic-bezier(0.25,0.46,0.45,0.94),
            height    0.5s cubic-bezier(0.25,0.46,0.45,0.94),
            margin-top 0.5s cubic-bezier(0.25,0.46,0.45,0.94),
            transform 0.5s cubic-bezier(0.25,0.46,0.45,0.94),
            filter   0.5s ease,
            opacity  0.4s ease,
            box-shadow 0.5s ease,
            border-color 0.5s ease;
        border: 2px solid rgba(179,3,3,0.15);
        background: #0d0505;
    }

    .hero-slide.es-centro {
        z-index: 10;
        filter: brightness(1) grayscale(0);
        border-color: rgba(179,3,3,0.55);
        box-shadow: 0 0 50px rgba(179,3,3,0.25), 0 25px 50px rgba(0,0,0,0.7);
    }

    .hero-slide.es-lateral1 {
        z-index: 6;
        filter: brightness(0.55) grayscale(0.9);
        border-color: rgba(179,3,3,0.08);
    }

    .hero-slide.es-lateral2 {
        z-index: 3;
        filter: brightness(0.35) grayscale(1);
        border-color: transparent;
    }

    .hero-slide.es-oculta {
        z-index: 0;
        opacity: 0;
        pointer-events: none;
        filter: brightness(0);
    }

    .hero-slide.es-lateral1:hover {
        filter: brightness(1.05) grayscale(0);
        border-color: rgba(179,3,3,0.4);
        box-shadow: 0 0 35px rgba(179,3,3,0.15);
    }

    .hero-slide.es-lateral2:hover {
        filter: brightness(0.85) grayscale(0.2);
    }

    /* Imagen */
    .slide-img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s ease;
    }

    .hero-slide.es-centro:hover .slide-img {
        transform: scale(1.04);
    }

    /* Overlay */
    .slide-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(
            to top,
            rgba(5,2,2,0.97) 0%,
            rgba(5,2,2,0.55) 42%,
            rgba(5,2,2,0.08) 68%,
            transparent 100%
        );
    }

    .hero-slide.es-centro::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, #B30303, transparent);
        opacity: 0;
        transition: opacity 0.35s;
        z-index: 20;
    }
    .hero-slide.es-centro:hover::before { opacity: 1; }

    /* Info carta central */
    .slide-info {
        position: absolute;
        bottom: 0; left: 0; right: 0;
        padding: 1.4rem;
        z-index: 15;
        display: none;
    }

    .hero-slide.es-centro .slide-info { display: block; }

    .slide-nivel {
        display: inline-block;
        background: #B30303;
        color: #fff;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.18rem 0.7rem;
        border-radius: 20px;
        margin-bottom: 0.45rem;
        letter-spacing: 0.5px;
    }

    .slide-nombre {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 1.5rem;
        color: #fff;
        font-weight: 700;
        letter-spacing: 1px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.8);
        margin-bottom: 0.2rem;
        line-height: 1.2;
    }

    .slide-meta {
        color: #9aa2aa;
        font-size: 0.85rem;
        margin-bottom: 0.9rem;
        font-style: italic;
    }

    .slide-acciones {
        display: flex;
        gap: 0.45rem;
        opacity: 0;
        transform: translateY(8px);
        transition: all 0.3s ease 0.05s;
        flex-wrap: wrap;
    }

    .hero-slide.es-centro:hover .slide-acciones {
        opacity: 1;
        transform: translateY(0);
    }

    .slide-btn {
        padding: 0.42rem 1rem;
        border-radius: 5px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .slide-btn-ver  { background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.2); }
    .slide-btn-ver:hover  { background: rgba(255,255,255,0.22); }
    .slide-btn-edit { background: #D46043; color: #fff; }
    .slide-btn-edit:hover { background: #b84a30; }
    .slide-btn-del  { background: rgba(107,26,26,0.85); color: #fff; }
    .slide-btn-del:hover  { background: #8a2222; }

    /* Nombre en laterales */
    .slide-nombre-lateral {
        position: absolute;
        bottom: 1rem; left: 1rem; right: 1rem;
        z-index: 15;
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: 1rem;
        color: rgba(255,255,255,0.65);
        text-shadow: 0 2px 6px rgba(0,0,0,0.9);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: none;
    }

    .hero-slide.es-lateral1 .slide-nombre-lateral,
    .hero-slide.es-lateral2 .slide-nombre-lateral { display: block; }

    /* Botones de navegación */
    .carousel-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 44px;
        height: 44px;
        background: rgba(13,5,5,0.82);
        border: 1px solid rgba(179,3,3,0.4);
        border-radius: 50%;
        color: #fff;
        font-size: 1.4rem;
        cursor: pointer;
        z-index: 30;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        backdrop-filter: blur(4px);
    }

    .carousel-nav:hover {
        background: #B30303;
        border-color: #B30303;
        transform: translateY(-50%) scale(1.08);
    }

    .carousel-nav.prev { left: -22px; }
    .carousel-nav.next { right: -22px; }

    /* Puntos */
    .carousel-dots {
        display: flex;
        justify-content: center;
        gap: 0.55rem;
        margin-top: 1.2rem;
        margin-bottom: 2rem;
    }

    .carousel-dot {
        width: 8px; height: 8px;
        border-radius: 50%;
        background: rgba(118,133,150,0.3);
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        padding: 0;
    }

    .carousel-dot.active {
        background: #B30303;
        width: 22px;
        border-radius: 4px;
    }

    /* Card única */
    .single-card {
        max-width: 340px;
        margin: 0 auto 2rem;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(179,3,3,0.2);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
    }
    .single-card:hover { border-color: var(--color-rojo); transform: translateY(-4px); box-shadow: 0 8px 30px rgba(179,3,3,0.15); }
    .single-card-header { position: relative; height: 260px; overflow: hidden; background: #1a0a0a; }
    .single-card-header img { width: 100%; height: 100%; object-fit: cover; }
    .single-card-header .nivel-badge { position: absolute; top: 10px; right: 10px; background: var(--color-rojo); color: #fff; padding: 0.3rem 0.8rem; border-radius: 20px; font-size: 0.8rem; font-weight: bold; }
    .single-card-body { padding: 1.5rem; }
    .single-card-body h3 { font-size: 1.3rem; margin-bottom: 0.3rem; color: #fff; font-family: 'Cinzel','Georgia',serif; }
    .single-card-body .clase-raza { color: var(--color-gris); font-size: 0.9rem; margin-bottom: 0.8rem; }
    .single-card-actions { display: flex; gap: 0.5rem; flex-wrap: wrap; padding-top: 1rem; border-top: 1px solid rgba(118,133,150,0.15); }
    .btn-ver      { background: rgba(255,255,255,0.1); color: #fff; padding: 0.4rem 1rem; border: 1px solid var(--color-gris); border-radius: 4px; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; text-decoration: none; font-family: inherit; display: inline-block; }
    .btn-ver:hover { background: rgba(255,255,255,0.2); }
    .btn-editar   { background: var(--color-naranja); color: #fff; padding: 0.4rem 1rem; border: none; border-radius: 4px; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; text-decoration: none; font-family: inherit; display: inline-block; }
    .btn-editar:hover { background: #b84a30; }
    .btn-eliminar { background: #6b1a1a; color: #fff; padding: 0.4rem 1rem; border: none; border-radius: 4px; font-size: 0.85rem; cursor: pointer; transition: all 0.3s; font-family: inherit; }
    .btn-eliminar:hover { background: #8a2222; }

    /* Empty state */
    .empty-state { text-align: center; padding: 4rem 2rem; background: rgba(255,255,255,0.02); border: 2px dashed rgba(179,3,3,0.2); border-radius: 10px; }
    .empty-state .icon { font-size: 4rem; display: block; margin-bottom: 1rem; }
    .empty-state h3 { color: var(--color-gris); margin-bottom: 0.5rem; }
    .empty-state p  { color: var(--color-gris); margin-bottom: 1.5rem; }

    .alert-success { background: rgba(64,72,52,0.5); color: #a0b890; padding: 1rem; border-radius: 6px; border: 1px solid var(--color-verde); margin-bottom: 1rem; }

    @media (max-width: 768px) {
        .carousel-scene { height: 420px; }
        .carousel-nav.prev { left: -16px; }
        .carousel-nav.next { right: -16px; }
        .header-actions { flex-direction: column; align-items: stretch; }
        .btn-crear { justify-content: center; }
    }

    @media (max-width: 480px) {
        .carousel-scene { height: 380px; }
    }
</style>

<div class="gremio-container">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="header-actions">
        <h2 style="display:flex;align-items:center;gap:10px;">@include('partials.icon', ['name' => 'swords']) Gremio de Héroes</h2>
        <a href="{{ route('personajes.create') }}" class="btn-crear">
            @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Crear Nuevo Personaje
        </a>
    </div>

    @if(isset($personajes) && $personajes->count() > 0)

        @if($personajes->count() >= 2)
        @php $slides = $personajes->values(); @endphp

        <div class="carousel-wrap">
            <button class="carousel-nav prev" onclick="moverSlide(-1)">‹</button>

            <div class="carousel-scene" id="carouselScene">
                @foreach($slides as $i => $personaje)
                <div class="hero-slide es-oculta" id="slide-{{ $i }}">
                    <img class="slide-img"
                         src="{{ $personaje->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($personaje->nombre) . '&background=B30303&color=fff&size=400' }}"
                         alt="{{ $personaje->nombre }}">
                    <div class="slide-overlay"></div>

                    <div class="slide-info">
                        <span class="slide-nivel">Nivel {{ $personaje->nivel }}</span>
                        <div class="slide-nombre">{{ $personaje->nombre }}</div>
                        <div class="slide-meta">
                            {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
                        </div>
                        <div class="slide-acciones">
                            <a href="{{ route('personajes.show', $personaje) }}" class="slide-btn slide-btn-ver">@include('partials.icon', ['name' => 'eye', 'class' => 'icon-sm']) Ver ficha</a>
                            <a href="{{ route('personajes.edit', $personaje) }}" class="slide-btn slide-btn-edit">@include('partials.icon', ['name' => 'edit', 'class' => 'icon-sm']) Editar</a>
                            <form action="{{ route('personajes.destroy', $personaje) }}" method="POST"
                                  style="display:inline"
                                  onsubmit="return confirm('¿Eliminar a {{ addslashes($personaje->nombre) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="slide-btn slide-btn-del">@include('partials.icon', ['name' => 'trash', 'class' => 'icon-sm'])</button>
                            </form>
                        </div>
                    </div>

                    <div class="slide-nombre-lateral">{{ $personaje->nombre }}</div>
                </div>
                @endforeach
            </div>

            <button class="carousel-nav next" onclick="moverSlide(1)">›</button>
        </div>

        <div class="carousel-dots">
            @foreach($slides as $i => $personaje)
            <button class="carousel-dot {{ $i === 0 ? 'active' : '' }}"
                    onclick="irA({{ $i }})"></button>
            @endforeach
        </div>

        @else
        @php $p = $personajes->first(); @endphp
        <div class="single-card">
            <div class="single-card-header">
                <img src="{{ $p->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($p->nombre) . '&background=B30303&color=fff&size=200' }}"
                     alt="{{ $p->nombre }}">
                <span class="nivel-badge">Nivel {{ $p->nivel }}</span>
            </div>
            <div class="single-card-body">
                <h3>{{ $p->nombre }}</h3>
                <div class="clase-raza">{{ $p->raza->nombre ?? 'Raza' }} | {{ $p->clase->nombre ?? 'Clase' }}</div>
                <div class="single-card-actions">
                    <a href="{{ route('personajes.show', $p) }}" class="btn-ver">@include('partials.icon', ['name' => 'eye', 'class' => 'icon-sm']) Ver</a>
                    <a href="{{ route('personajes.edit', $p) }}" class="btn-editar">@include('partials.icon', ['name' => 'edit', 'class' => 'icon-sm']) Editar</a>
                    <form action="{{ route('personajes.destroy', $p) }}" method="POST" style="display:inline"
                          onsubmit="return confirm('¿Eliminar a {{ addslashes($p->nombre) }}?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-eliminar">@include('partials.icon', ['name' => 'trash', 'class' => 'icon-sm']) Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
        @endif

    @else
    <div class="empty-state">
        <span class="icon">@include('partials.icon', ['name' => 'shield', 'class' => 'icon-xl'])</span>
        <h3>No hay personajes aún</h3>
        <p>¡Crea tu primer héroe y comienza tu aventura!</p>
        <a href="{{ route('personajes.create') }}" class="btn-crear" style="display:inline-flex">
            @include('partials.icon', ['name' => 'sword', 'class' => 'icon-sm']) Crear mi primer personaje
        </a>
    </div>
    @endif

</div>

@if(isset($personajes) && $personajes->count() >= 2)
<script>
(function () {
    const TOTAL   = {{ $personajes->count() }};
    const GAP     = 18;    // separación entre cartas en px
    const SCALE_2 = 0.9;   // escala de la 4ª+ carta (si hay más de 3 personajes)

    let current = 0;

    function scene() { return document.getElementById('carouselScene'); }

    // Ancho de carta: la escena se reparte en 3 franjas iguales (centro + 2 laterales)
    function cardW() {
        const w = scene().offsetWidth;
        return (w - GAP * 2) / 3;
    }

    function cardH() {
        return scene().offsetHeight * 0.94;
    }

    function centroScene(w) {
        return scene().offsetWidth / 2 - w / 2;
    }

    function posicion(i) {
        let diff = ((i - current) % TOTAL + TOTAL) % TOTAL;
        if (diff > TOTAL / 2) diff -= TOTAL;
        return diff; // -2,-1,0,1,2  (o más si hay muchos)
    }

    function render() {
        const w   = cardW();
        const h   = cardH();
        const cx  = centroScene(w);
        const sep = w + GAP;

        for (let i = 0; i < TOTAL; i++) {
            const slide = document.getElementById('slide-' + i);
            const diff  = posicion(i);

            slide.classList.remove('es-centro','es-lateral1','es-lateral2','es-oculta');

            let leftPx, scale, clase, anchoCarta = w;

            if (diff === 0) {
                leftPx = cx;
                scale  = 1;
                clase  = 'es-centro';
                slide.onclick = null;
            } else if (diff === 1 || diff === -1) {
                leftPx = cx + diff * sep;
                scale  = 1;
                clase  = 'es-lateral1';
                const idx = i;
                slide.onclick = () => irA(idx);
            } else if (diff === 2 || diff === -2) {
                // Solo aparece si hay 4+ personajes: una franja parcial asomando en el borde
                anchoCarta = w * SCALE_2;
                leftPx = cx + diff * sep + Math.sign(diff) * (w - anchoCarta) / 2;
                scale  = 1;
                clase  = 'es-lateral2';
                const idx = i;
                slide.onclick = () => irA(idx);
            } else {
                leftPx = diff > 0 ? cx + 9999 : cx - 9999;
                scale  = 1;
                clase  = 'es-oculta';
                slide.onclick = null;
            }

            slide.classList.add(clase);
            slide.style.left       = leftPx + 'px';
            slide.style.width      = anchoCarta + 'px';
            slide.style.height     = h + 'px';
            slide.style.marginTop  = (-h / 2) + 'px';
            slide.style.transform  = `scale(${scale})`;
            slide.style.transformOrigin = 'center center';
        }

        // Dots
        document.querySelectorAll('.carousel-dot').forEach((d, i) => {
            d.classList.toggle('active', i === current);
        });
    }

    window.moverSlide = function(dir) {
        current = ((current + dir) % TOTAL + TOTAL) % TOTAL;
        render();
    };

    window.irA = function(idx) {
        current = idx;
        render();
    };

    // Swipe
    let startX = 0;
    const sceneEl = document.getElementById('carouselScene');
    sceneEl.addEventListener('touchstart', e => { startX = e.touches[0].clientX; }, { passive: true });
    sceneEl.addEventListener('touchend',   e => {
        if (Math.abs(startX - e.changedTouches[0].clientX) > 45)
            moverSlide(startX > e.changedTouches[0].clientX ? 1 : -1);
    }, { passive: true });

    // Teclado
    document.addEventListener('keydown', e => {
        if (e.key === 'ArrowLeft')  moverSlide(-1);
        if (e.key === 'ArrowRight') moverSlide(1);
    });

    // Recalcular al redimensionar
    window.addEventListener('resize', render);

    render();
})();
</script>
@endif
@endsection