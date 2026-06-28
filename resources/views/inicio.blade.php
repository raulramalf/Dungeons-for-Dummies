@extends('layouts.app')

@section('titulo', 'Inicio')

@section('contenido')
<style>
    .hero-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 70vh;
        text-align: center;
        padding: 2rem;
    }
    .hero-content {
        max-width: 800px;
    }
    .hero-title {
        font-size: 3.5rem;
        color: var(--color-rojo);
        margin-bottom: 1rem;
        letter-spacing: 2px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .hero-subtitle {
        font-size: 1.2rem;
        color: var(--color-gris);
        margin-bottom: 2.5rem;
        line-height: 1.6;
    }
    .btn-group {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    .btn {
        padding: 1rem 2rem;
        font-size: 1.1rem;
        font-weight: bold;
        text-decoration: none;
        border-radius: 5px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        cursor: pointer;
        font-family: inherit;
    }
    .btn-primary {
        background-color: var(--color-rojo);
        color: #fff;
    }
    .btn-primary:hover {
        background-color: #8a0202;
        box-shadow: 0 4px 10px rgba(179, 3, 3, 0.4);
    }
    .btn-secondary {
        background-color: transparent;
        color: var(--color-gris);
        border-color: var(--color-gris);
    }
    .btn-secondary:hover {
        background-color: rgba(118, 133, 150, 0.1);
        color: #fff;
        border-color: #fff;
    }
</style>

<div class="hero-container">
    <div class="hero-content">
        <h2 class="hero-title">Forja tu Leyenda</h2>
        <p class="hero-subtitle">El sistema definitivo para gestionar tus campañas, crear héroes inolvidables y compartir tus tiradas críticas con la comunidad.</p>
        
        <div class="btn-group">
            <a href="{{ route('personajes.index') }}#crear-personaje" class="btn btn-primary">
                ⚔️ Crear Personaje
            </a>
            <a href="{{ route('feed.index') }}" class="btn btn-secondary">
                🍺 Entrar a la Taberna
            </a>
        </div>
    </div>
</div>
@endsection