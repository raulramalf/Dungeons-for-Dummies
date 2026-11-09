@extends('layouts.app')

@section('titulo', 'Crear Personaje')

@push('head')
    @vite(['resources/css/pages/personaje-crear.css', 'resources/js/pages/personaje-crear.js'])
@endpush

@section('contenido')

<div class="form-container">
    <h2 class="form-titulo">@include('partials.icon', ['name' => 'swords']) Crear Nuevo Personaje</h2>

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
                <select name="clase_id" id="claseSelect" class="form-control @error('clase_id') error @enderror" required onchange="filtrarSubclases()">
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
            <label>Subclase</label>
            <select name="subclase_id" id="subclaseSelect" class="form-control @error('subclase_id') error @enderror">
                <option value="">Sin subclase todavía</option>
                @foreach($subclases as $subclase)
                    <option value="{{ $subclase->id }}" data-clase="{{ $subclase->clase_id }}" {{ old('subclase_id') == $subclase->id ? 'selected' : '' }}>
                        {{ $subclase->nombre }} ({{ $subclase->clase->nombre ?? '' }})
                    </option>
                @endforeach
            </select>
            @error('subclase_id')
                <span class="error-text">{{ $message }}</span>
            @enderror
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

        <h3 class="form-seccion-titulo">@include('partials.icon', ['name' => 'star']) Estadísticas</h3>

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
            <a href="{{ route('personajes.index') }}" class="btn-cancel">@include('partials.icon', ['name' => 'x', 'class' => 'icon-sm']) Cancelar</a>
            <button type="submit" class="btn-submit">@include('partials.icon', ['name' => 'sword', 'class' => 'icon-sm']) Crear Personaje</button>
        </div>
    </form>
</div>

@endsection
