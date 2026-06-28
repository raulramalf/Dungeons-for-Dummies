@extends('layouts.app')

@section('titulo', 'Crear Personaje')

@section('contenido')
<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(179, 3, 3, 0.2);
        border-radius: 10px;
        padding: 2rem;
    }

    .form-container h2 {
        color: #fff;
        margin-bottom: 2rem;
        text-align: center;
        letter-spacing: 2px;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: var(--color-gris);
        font-size: 0.9rem;
        margin-bottom: 0.3rem;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .form-group .required {
        color: var(--color-rojo);
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        background: rgba(0,0,0,0.2);
        border: 1px solid var(--color-gris);
        color: #fff;
        border-radius: 6px;
        font-family: inherit;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-rojo);
    }

    .form-control.error {
        border-color: #cc0000;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .error-text {
        color: #cc0000;
        font-size: 0.85rem;
        margin-top: 0.3rem;
        display: block;
    }

    .btn-submit {
        background: var(--color-rojo);
        color: #fff;
        padding: 0.8rem 2.5rem;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        font-family: inherit;
        width: 100%;
    }

    .btn-submit:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(179, 3, 3, 0.3);
    }

    .btn-cancel {
        background: rgba(255,255,255,0.05);
        color: var(--color-gris);
        padding: 0.8rem 2rem;
        border: 1px solid var(--color-gris);
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        transition: all 0.3s;
        font-family: inherit;
        display: inline-block;
    }

    .btn-cancel:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
    }

    .form-actions .btn-submit {
        flex: 1;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .form-actions {
            flex-direction: column;
        }
        .btn-cancel {
            text-align: center;
        }
    }
</style>

<div class="form-container">
    <h2>⚔️ Crear Nuevo Personaje</h2>

    <form action="{{ route('personajes.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-group">
                <label>Nombre <span class="required">*</span></label>
                <input type="text" name="nombre" class="form-control @error('nombre') error @enderror" value="{{ old('nombre') }}" required>
                @error('nombre')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Nivel <span class="required">*</span></label>
                <input type="number" name="nivel" class="form-control @error('nivel') error @enderror" value="{{ old('nivel', 1) }}" min="1" max="20" required>
                @error('nivel')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Raza <span class="required">*</span></label>
                <select name="raza_id" class="form-control @error('raza_id') error @enderror" required>
                    <option value="">Selecciona una raza</option>
                    @foreach($razas as $raza)
                        <option value="{{ $raza->id }}" {{ old('raza_id') == $raza->id ? 'selected' : '' }}>
                            {{ $raza->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('raza_id')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label>Clase <span class="required">*</span></label>
                <select name="clase_id" class="form-control @error('clase_id') error @enderror" required>
                    <option value="">Selecciona una clase</option>
                    @foreach($clases as $clase)
                        <option value="{{ $clase->id }}" {{ old('clase_id') == $clase->id ? 'selected' : '' }}>
                            {{ $clase->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('clase_id')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Avatar (URL)</label>
            <input type="text" name="avatar" class="form-control @error('avatar') error @enderror" placeholder="https://ejemplo.com/imagen.jpg" value="{{ old('avatar') }}">
            @error('avatar')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label>Historia del personaje</label>
            <textarea name="historia" class="form-control @error('historia') error @enderror" rows="4" placeholder="Cuenta la historia de tu héroe...">{{ old('historia') }}</textarea>
            @error('historia')
                <span class="error-text">{{ $message }}</span>
            @enderror
        </div>

        <h3 style="color: var(--color-gris); margin-top: 2rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 1px;">🎯 Estadísticas</h3>
        
        <div class="form-row">
            @foreach(['fuerza', 'destreza', 'constitucion', 'inteligencia', 'sabiduria', 'carisma'] as $stat)
            <div class="form-group">
                <label>{{ strtoupper($stat) }} <span class="required">*</span></label>
                <input type="number" name="{{ $stat }}" class="form-control @error($stat) error @enderror" value="{{ old($stat, 10) }}" min="1" max="30" required>
                @error($stat)
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>
            @endforeach
        </div>

        <div class="form-actions">
            <a href="{{ route('personajes.index') }}" class="btn-cancel">❌ Cancelar</a>
            <button type="submit" class="btn-submit">⚔️ Crear Personaje</button>
        </div>
    </form>
</div>
@endsection