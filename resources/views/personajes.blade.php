@extends('layouts.app')

@section('titulo', 'Gremio de Héroes')

@section('contenido')
<style>
    .gremio-container {
        max-width: 1200px;
        margin: 0 auto;
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
        box-shadow: 0 4px 15px rgba(179, 3, 3, 0.3);
    }

    .personajes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .personaje-card {
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(179, 3, 3, 0.2);
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
    }

    .personaje-card:hover {
        border-color: var(--color-rojo);
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(179, 3, 3, 0.15);
    }

    .personaje-card-header {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: #1a0a0a;
    }

    .personaje-card-header img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .personaje-card-header .nivel-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--color-rojo);
        color: #fff;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .personaje-card-body {
        padding: 1.5rem;
    }

    .personaje-card-body h3 {
        font-size: 1.3rem;
        margin-bottom: 0.3rem;
        color: #fff;
    }

    .personaje-card-body .clase-raza {
        color: var(--color-gris);
        font-size: 0.9rem;
        margin-bottom: 0.8rem;
    }

    .personaje-card-actions {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        padding-top: 1rem;
        border-top: 1px solid rgba(118, 133, 150, 0.15);
    }

    .btn-ver {
        background: rgba(255,255,255,0.1);
        color: #fff;
        padding: 0.4rem 1rem;
        border: 1px solid var(--color-gris);
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-family: inherit;
        display: inline-block;
    }

    .btn-ver:hover {
        background: rgba(255,255,255,0.2);
    }

    .btn-editar {
        background: var(--color-naranja);
        color: #fff;
        padding: 0.4rem 1rem;
        border: none;
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        font-family: inherit;
        display: inline-block;
    }

    .btn-editar:hover {
        background: #b84a30;
    }

    .btn-eliminar {
        background: #6b1a1a;
        color: #fff;
        padding: 0.4rem 1rem;
        border: none;
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s;
        font-family: inherit;
    }

    .btn-eliminar:hover {
        background: #8a2222;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: rgba(255,255,255,0.02);
        border: 2px dashed rgba(179, 3, 3, 0.2);
        border-radius: 10px;
    }

    .empty-state .icon {
        font-size: 4rem;
        display: block;
        margin-bottom: 1rem;
    }

    .empty-state h3 {
        color: var(--color-gris);
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--color-gris);
        margin-bottom: 1.5rem;
    }

    .alert-success {
        background: rgba(64, 72, 52, 0.5);
        color: #a0b890;
        padding: 1rem;
        border-radius: 6px;
        border: 1px solid var(--color-verde);
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .personajes-grid {
            grid-template-columns: 1fr;
        }
        .header-actions {
            flex-direction: column;
            align-items: stretch;
        }
        .header-actions h2 {
            text-align: center;
        }
        .btn-crear {
            justify-content: center;
        }
    }
</style>

<div class="gremio-container">
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="header-actions">
        <h2>⚔️ Gremio de Héroes</h2>
        <a href="{{ route('personajes.create') }}" class="btn-crear">
            ➕ Crear Nuevo Personaje
        </a>
    </div>

    @if(isset($personajes) && $personajes->count() > 0)
    <div class="personajes-grid">
        @foreach($personajes as $personaje)
        <div class="personaje-card">
            <div class="personaje-card-header">
                <img src="{{ $personaje->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($personaje->nombre) . '&background=B30303&color=fff&size=200' }}" alt="{{ $personaje->nombre }}">
                <span class="nivel-badge">Nivel {{ $personaje->nivel }}</span>
            </div>
            <div class="personaje-card-body">
                <h3>{{ $personaje->nombre }}</h3>
                <div class="clase-raza">
                    {{ $personaje->raza->nombre ?? 'Raza' }} | {{ $personaje->clase->nombre ?? 'Clase' }}
                </div>
                <div class="personaje-card-actions">
                    <a href="{{ route('personajes.show', $personaje) }}" class="btn-ver">👁️ Ver</a>
                    <a href="{{ route('personajes.edit', $personaje) }}" class="btn-editar">✏️ Editar</a>
                    
                    <!-- FORMULARIO DE ELIMINACIÓN CORREGIDO -->
                    <form action="/personajes/{{ $personaje->id }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar a {{ $personaje->nombre }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-eliminar">🗑️ Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="empty-state">
        <span class="icon">🏰</span>
        <h3>No hay personajes aún</h3>
        <p>¡Crea tu primer héroe y comienza tu aventura!</p>
        <a href="{{ route('personajes.create') }}" class="btn-crear" style="display: inline-block;">
            ⚔️ Crear mi primer personaje
        </a>
    </div>
    @endif
</div>
@endsection