@extends('layouts.app')

@section('titulo', 'Bestiario')
@section('descripcion', 'Tu bestiario de monstruos y enemigos para D&D: crea, busca y ordena por clase de desafío.')

@push('head')
    @vite(['resources/css/pages/enemigos.css', 'resources/js/pages/enemigos.js'])
@endpush

@section('contenido')
<div class="contenedor">

    @if ($errors->any())
        <div class="alerta alerta-error" role="alert">
            <ul class="alerta-lista">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="barra-acciones">
        <p class="barra-acciones-info">{{ $enemigos->count() }} {{ $enemigos->count() === 1 ? 'enemigo creado' : 'enemigos creados' }}</p>
        <button type="button" class="btn btn-primario" data-modal-abrir="modal-enemigo">
            @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir enemigo
        </button>
    </div>

    @if ($enemigos->isNotEmpty())
        {{-- BUSCADOR Y FILTROS --}}
        <div class="en-filtros" role="search" aria-label="Buscar y filtrar enemigos">
            <label class="sr-only" for="buscador">Buscar enemigo por nombre</label>
            <input type="search" id="buscador" class="control" placeholder="Buscar enemigo…" autocomplete="off">
            <div class="en-filtros-botones">
                <button type="button" class="btn btn-secundario btn-sm" data-orden="cr" aria-pressed="false">CR <span data-flecha>↕</span></button>
                <button type="button" class="btn btn-secundario btn-sm" data-orden="nombre" aria-pressed="false">Nombre <span data-flecha>↕</span></button>
                <button type="button" class="btn btn-secundario btn-sm" data-solo-boss aria-pressed="false">Solo boss</button>
            </div>
        </div>

        <p class="en-sin-resultados" id="en-sin-resultados" role="status" hidden>Ningún enemigo coincide con la búsqueda.</p>

        {{-- LISTA --}}
        <ul class="en-grid" id="lista-enemigos" role="list">
            @foreach ($enemigos as $enemigo)
                <li class="en-card" data-cr="{{ $enemigo->clase_de_desafio }}" data-boss="{{ $enemigo->es_boss ? '1' : '0' }}" data-nombre="{{ $enemigo->nombre }}">
                    <button type="button" class="en-abrir" data-enemigo="{{ $enemigo->id }}" aria-label="Ver y editar a {{ $enemigo->nombre }}"></button>

                    <div class="en-media">
                        @if ($enemigo->imagen)
                            <img src="{{ Storage::url($enemigo->imagen) }}" alt="" loading="lazy" decoding="async" width="400" height="260">
                        @else
                            @include('partials.icon', ['name' => 'skull', 'class' => 'en-media-icono'])
                        @endif

                        @if ($enemigo->es_boss)
                            <span class="en-boss">Boss</span>
                        @endif
                        <span class="en-cr en-cr--{{ $enemigo->cr_nivel }}">CR {{ $enemigo->cr_texto }}</span>

                        <div class="en-titulo">
                            <h3>{{ $enemigo->nombre }}</h3>
                            <p>{{ $enemigo->tipo }} · {{ $enemigo->tamaño }}</p>
                        </div>
                    </div>

                    <div class="en-cuerpo">
                        <dl class="en-stats">
                            <div>
                                <dt>PG</dt>
                                <dd>
                                    {{ Str::before($enemigo->puntos_de_golpe, ' (') }}
                                    @if (str_contains($enemigo->puntos_de_golpe, '('))<small>({{ Str::between($enemigo->puntos_de_golpe, '(', ')') }})</small>@endif
                                </dd>
                            </div>
                            <div><dt>CA</dt><dd>{{ $enemigo->clase_de_armadura }}</dd></div>
                            <div><dt>Iniciativa</dt><dd>{{ $enemigo->iniciativa }}</dd></div>
                        </dl>
                        <form class="en-acciones" method="POST" action="/enemigos/{{ $enemigo->id }}"
                              data-confirm="¿Eliminar a {{ $enemigo->nombre }}? Esta acción no se puede deshacer.">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icono btn-icono-peligro" aria-label="Eliminar a {{ $enemigo->nombre }}" title="Eliminar">
                                @include('partials.icon', ['name' => 'trash'])
                            </button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        <div class="vacio">
            @include('partials.icon', ['name' => 'skull', 'class' => 'icon-xl'])
            <h3>Bestiario vacío</h3>
            <p>Aún no has registrado ningún enemigo. ¡Crea el primero!</p>
            <button type="button" class="btn btn-primario" data-modal-abrir="modal-enemigo">
                @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir primer enemigo
            </button>
        </div>
    @endif
</div>

{{-- MODAL: NUEVO ENEMIGO --}}
<dialog class="modal modal--ancho" id="modal-enemigo" aria-labelledby="titulo-nuevo-enemigo" @if (old('_modal') === 'nuevo') data-abrir-al-cargar @endif>
    <form class="modal-form" method="POST" action="/enemigos" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="_modal" value="nuevo">
        <header class="modal-cab">
            <h2 id="titulo-nuevo-enemigo">Nuevo enemigo</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            @include('partials.enemigo-form', ['p' => 'nuevo', 'nuevo' => true])
        </div>
        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Crear enemigo</button>
        </footer>
    </form>
</dialog>

{{-- MODAL: VER / EDITAR ENEMIGO (lo rellena js/pages/enemigos.js) --}}
<dialog class="modal modal--ancho" id="modal-ver-enemigo" aria-labelledby="ver-titulo">
    <form class="modal-form" id="form-editar-enemigo" method="POST" enctype="multipart/form-data">
        @csrf
        <header class="modal-cab">
            <h2 id="ver-titulo">Enemigo</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            @include('partials.enemigo-form', ['p' => 'ver', 'nuevo' => false])
        </div>
        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Guardar cambios</button>
        </footer>
    </form>
</dialog>
@endsection
