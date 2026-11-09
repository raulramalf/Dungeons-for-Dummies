@extends('layouts.app')

@section('titulo', 'Gremio de Héroes')
@section('descripcion', 'Tus personajes de D&D 5.5e: consulta, edita y exporta sus fichas.')

@push('head')
    @vite('resources/css/pages/personajes.css')
@endpush

@section('contenido')
<div class="contenedor">

    <div class="barra-acciones">
        <p class="barra-acciones-info">
            @if ($personajes->isNotEmpty())
                {{ $personajes->count() }} {{ $personajes->count() === 1 ? 'héroe' : 'héroes' }} en tu gremio
            @else
                Tu gremio está vacío
            @endif
        </p>
        <a href="{{ route('personajes.create') }}" class="btn btn-primario">
            @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Crear personaje
        </a>
    </div>

    @if ($personajes->isNotEmpty())
        <ul class="pj-grid" role="list">
            @foreach ($personajes as $p)
                <li class="pj-card">
                    <a class="pj-media" href="{{ route('personajes.show', $p) }}" tabindex="-1" aria-hidden="true">
                        <img src="{{ $p->avatar_url }}" alt="" width="400" height="500"
                             @if (! $loop->first) loading="lazy" @endif decoding="async">
                        <span class="pj-nivel">Nivel {{ $p->nivel }}</span>
                    </a>

                    <div class="pj-cuerpo">
                        <h3 class="pj-nombre">
                            <a href="{{ route('personajes.show', $p) }}">{{ $p->nombre }}</a>
                        </h3>
                        <p class="pj-meta">{{ $p->raza->nombre ?? 'Especie' }} · {{ $p->clase->nombre ?? 'Clase' }}</p>

                        <div class="pj-acciones">
                            <a href="{{ route('personajes.show', $p) }}" class="btn btn-primario btn-sm">
                                @include('partials.icon', ['name' => 'eye', 'class' => 'icon-sm']) Ver ficha
                            </a>
                            <a href="{{ route('personajes.edit', $p) }}" class="btn-icono" aria-label="Editar a {{ $p->nombre }}" title="Editar">
                                @include('partials.icon', ['name' => 'edit'])
                            </a>
                            <form action="{{ route('personajes.destroy', $p) }}" method="POST"
                                  data-confirm="¿Eliminar a {{ $p->nombre }}? Esta acción no se puede deshacer.">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icono btn-icono-peligro" aria-label="Eliminar a {{ $p->nombre }}" title="Eliminar">
                                    @include('partials.icon', ['name' => 'trash'])
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="vacio">
            @include('partials.icon', ['name' => 'shield', 'class' => 'icon-xl'])
            <h3>No hay personajes aún</h3>
            <p>¡Crea tu primer héroe y comienza tu aventura!</p>
            <a href="{{ route('personajes.create') }}" class="btn btn-primario">
                @include('partials.icon', ['name' => 'sword', 'class' => 'icon-sm']) Crear mi primer personaje
            </a>
        </div>
    @endif

</div>
@endsection
