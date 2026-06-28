@extends('layouts.app')

@section('titulo', 'Forja tu Leyenda')

@section('contenido')
<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(32, 5, 14, 0.95), rgba(179, 3, 3, 0.3)), 
                    url('https://images.unsplash.com/photo-1549351512-c5e12b11e283?w=1400&h=400&fit=crop') center/cover;
        padding: 4rem 2rem;
        border-radius: 12px;
        margin-bottom: 3rem;
        text-align: center;
        border: 1px solid rgba(179, 3, 3, 0.3);
    }

    .hero-title {
        font-size: 4rem;
        color: #fff;
        text-shadow: 0 4px 20px rgba(0,0,0,0.8);
        margin-bottom: 1rem;
        letter-spacing: 3px;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: var(--color-gris);
        max-width: 700px;
        margin: 0 auto 2rem;
        line-height: 1.8;
    }

    .btn-group-hero {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-hero {
        padding: 1rem 2.5rem;
        font-size: 1.1rem;
        font-weight: bold;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-hero-primary {
        background: var(--color-rojo);
        color: #fff;
    }

    .btn-hero-primary:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(179, 3, 3, 0.4);
    }

    .btn-hero-secondary {
        background: rgba(255,255,255,0.1);
        color: #fff;
        border: 1px solid var(--color-gris);
    }

    .btn-hero-secondary:hover {
        background: rgba(255,255,255,0.2);
        transform: translateY(-2px);
    }

    /* Sección de información D&D */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .info-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(179, 3, 3, 0.15);
        border-radius: 10px;
        padding: 2rem;
        transition: all 0.3s;
    }

    .info-card:hover {
        border-color: var(--color-rojo);
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(179, 3, 3, 0.1);
    }

    .info-card-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        display: block;
    }

    .info-card h3 {
        color: var(--color-rojo);
        margin-bottom: 0.8rem;
        font-size: 1.3rem;
    }

    .info-card p {
        color: var(--color-gris);
        line-height: 1.6;
    }

    /* Personajes destacados */
    .destacados-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 3rem;
    }

    .personaje-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(179, 3, 3, 0.15);
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s;
        text-decoration: none;
        color: #fff;
    }

    .personaje-card:hover {
        border-color: var(--color-rojo);
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(179, 3, 3, 0.15);
    }

    .personaje-card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid var(--color-rojo);
        margin-bottom: 1rem;
    }

    .personaje-card h4 {
        font-size: 1.1rem;
        margin-bottom: 0.3rem;
    }

    .personaje-card p {
        color: var(--color-gris);
        font-size: 0.9rem;
    }

    .personaje-card .nivel-badge {
        display: inline-block;
        background: var(--color-rojo);
        color: #fff;
        padding: 0.2rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    /* Datos curiosos */
    .curiosidades {
        background: rgba(179, 3, 3, 0.05);
        border: 1px solid rgba(179, 3, 3, 0.2);
        border-radius: 10px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .curiosidades h3 {
        color: var(--color-rojo);
        margin-bottom: 1rem;
        text-align: center;
    }

    .curiosidades-list {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }

    .curiosidad-item {
        background: rgba(0,0,0,0.2);
        padding: 1rem;
        border-radius: 6px;
        border-left: 3px solid var(--color-rojo);
    }

    .curiosidad-item strong {
        color: var(--color-naranja);
        display: block;
        margin-bottom: 0.3rem;
    }

    .curiosidad-item p {
        color: var(--color-gris);
        font-size: 0.9rem;
        margin: 0;
    }

    .section-title {
        color: #fff;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        text-align: center;
        letter-spacing: 1px;
    }

    .section-title::after {
        content: '';
        display: block;
        width: 60px;
        height: 3px;
        background: var(--color-rojo);
        margin: 0.5rem auto 0;
    }

    @media (max-width: 768px) {
        .hero-title { font-size: 2.5rem; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="hero-section">
    <h1 class="hero-title">⚔️ Forja tu Leyenda</h1>
    <p class="hero-subtitle">
        El sistema definitivo para gestionar tus campañas, crear héroes inolvidables 
        y compartir tus tiradas críticas con la comunidad.
    </p>
    <div class="btn-group-hero">
        <a href="{{ route('personajes.create') }}" class="btn-hero btn-hero-primary">
            ⚔️ Crear Personaje
        </a>
        <a href="{{ route('feed.index') }}" class="btn-hero btn-hero-secondary">
            🍺 Entrar a la Taberna
        </a>
    </div>
</div>

<!-- Información de D&D -->
<div class="info-grid">
    <div class="info-card">
        <span class="info-card-icon">📜</span>
        <h3>¿Qué es D&D?</h3>
        <p>
            Dungeons & Dragons es un juego de rol donde creas un personaje y vives 
            aventuras épicas en mundos de fantasía. La imaginación es el límite.
        </p>
    </div>
    <div class="info-card">
        <span class="info-card-icon">🎲</span>
        <h3>Los Dados</h3>
        <p>
            El corazón de D&D son los dados poliédricos: d4, d6, d8, d10, d12 y d20. 
            Cada tirada decide el destino de tu héroe.
        </p>
    </div>
    <div class="info-card">
        <span class="info-card-icon">🏰</span>
        <h3>Campañas</h3>
        <p>
            Las campañas son historias largas que pueden durar meses o años. 
            Cada sesión es un capítulo en la leyenda de tu grupo.
        </p>
    </div>
    <div class="info-card">
        <span class="info-card-icon">🐉</span>
        <h3>Dragones</h3>
        <p>
            Los dragones son las criaturas más emblemáticas de D&D. 
            Desde el temible Tiamat hasta el sabio Bahamut.
        </p>
    </div>
</div>

<!-- Personajes Destacados -->
@if(isset($personajesDestacados) && $personajesDestacados->count() > 0)
<h3 class="section-title">🌟 Héroes Destacados</h3>
<div class="destacados-grid">
    @foreach($personajesDestacados as $personaje)
    <a href="{{ route('personajes.show', $personaje) }}" class="personaje-card">
        <img src="{{ $personaje->avatar_url }}" alt="{{ $personaje->nombre }}">
        <h4>{{ $personaje->nombre }}</h4>
        <p>{{ $personaje->raza->nombre ?? 'Raza' }} | {{ $personaje->clase->nombre ?? 'Clase' }}</p>
        <span class="nivel-badge">Nivel {{ $personaje->nivel }}</span>
    </a>
    @endforeach
</div>
@endif

<!-- Datos Curiosos -->
<div class="curiosidades">
    <h3>📖 Datos Curiosos de D&D</h3>
    <div class="curiosidades-list">
        <div class="curiosidad-item">
            <strong>El primer D&D</strong>
            <p>Se publicó en 1974 y fue creado por Gary Gygax y Dave Arneson.</p>
        </div>
        <div class="curiosidad-item">
            <strong>El d20</strong>
            <p>El dado de 20 caras es el más importante. ¡Un 20 natural es un éxito crítico!</p>
        </div>
        <div class="curiosidad-item">
            <strong>El Ojo de Vecna</strong>
            <p>Uno de los artefactos más poderosos. Arrancarte el ojo para usarlo... ¿te atreves?</p>
        </div>
        <div class="curiosidad-item">
            <strong>Drizzt Do'Urden</strong>
            <p>El elfo oscuro más famoso. Apareció por primera vez en 1988.</p>
        </div>
    </div>
</div>

<!-- Campañas Activas -->
@if(isset($campanasActivas) && $campanasActivas->count() > 0)
<h3 class="section-title">📜 Campañas Activas</h3>
<div class="destacados-grid">
    @foreach($campanasActivas as $campana)
    <div class="personaje-card" style="text-align: left;">
        <h4>📜 {{ $campana->nombre }}</h4>
        <p style="color: var(--color-gris); font-size: 0.9rem;">DM: {{ $campana->dungeonMaster->nombre ?? 'Desconocido' }}</p>
        <p style="color: var(--color-gris); font-size: 0.8rem;">{{ Str::limit($campana->descripcion, 100) }}</p>
        <span class="nivel-badge" style="background: var(--color-verde);">Niveles {{ $campana->nivel_inicial }}-{{ $campana->nivel_maximo ?? '∞' }}</span>
    </div>
    @endforeach
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación de entrada para las tarjetas
    const cards = document.querySelectorAll('.info-card, .personaje-card, .curiosidad-item');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.5s ease';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});
</script>
@endsection