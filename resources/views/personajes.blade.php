@extends('layouts.app')

@section('titulo', 'Ficha de ' . $personaje->nombre)

@section('contenido')
<style>
    .ficha-container {
        max-width: 900px;
        margin: 0 auto;
        background-color: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(179, 3, 3, 0.3);
        border-radius: 8px;
        overflow: hidden;
    }
    .ficha-header {
        display: flex;
        flex-wrap: wrap;
        background-color: rgba(0, 0, 0, 0.4);
        border-bottom: 2px solid var(--color-rojo);
    }
    .ficha-avatar {
        width: 250px;
        height: 300px;
        object-fit: cover;
        border-right: 2px solid var(--color-rojo);
    }
    .ficha-info-principal {
        padding: 2rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .ficha-nombre {
        font-size: 2.5rem;
        color: #fff;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }
    .ficha-clase-raza {
        font-size: 1.2rem;
        color: var(--color-naranja);
        margin-bottom: 1.5rem;
    }
    .ficha-nivel-box {
        display: inline-block;
        background-color: var(--color-rojo);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        font-weight: bold;
        font-size: 1.1rem;
    }
    .ficha-cuerpo { padding: 2rem; }
    .section-title {
        color: var(--color-gris);
        border-bottom: 1px solid rgba(118, 133, 150, 0.3);
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }
    .stat-box {
        background-color: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 6px;
        text-align: center;
        padding: 1.5rem 1rem;
    }
    .stat-label { display: block; color: var(--color-gris); font-size: 0.9rem; font-weight: bold; margin-bottom: 0.5rem; }
    .stat-value { display: block; color: #fff; font-size: 2rem; font-weight: bold; }
</style>

<div class="ficha-container">
    <div class="ficha-header">
        <img src="{{ $personaje->avatar ?? $personaje->imagen_url ?? 'https://via.placeholder.com/250x300/20050E/B30303?text=Heroe' }}" alt="{{ $personaje->nombre }}" class="ficha-avatar">
        <div class="ficha-info-principal">
            <h2 class="ficha-nombre">{{ $personaje->nombre }}</h2>
            <div class="ficha-clase-raza">
                {{ $personaje->raza->nombre ?? 'Raza Desconocida' }} | {{ $personaje->clase->nombre ?? 'Clase Desconocida' }}
            </div>
            <div><span class="ficha-nivel-box">Nivel {{ $personaje->nivel }}</span></div>
        </div>
    </div>

    <div class="ficha-cuerpo">
        <h3 class="section-title">Atributos Principales</h3>
        <div class="stats-container">
            @foreach(['FUE'=>'fuerza', 'DES'=>'destreza', 'CON'=>'constitucion', 'INT'=>'inteligencia', 'SAB'=>'sabiduria', 'CAR'=>'carisma'] as $label => $attr)
            <div class="stat-box">
                <span class="stat-label">{{ $label }}</span>
                <span class="stat-value">{{ $personaje->$attr ?? 10 }}</span>
            </div>
            @endforeach
        </div>
        <a href="{{ route('personajes.index') }}" style="color: var(--color-gris);">← Volver al Gremio</a>
    </div>
</div>
@endsection