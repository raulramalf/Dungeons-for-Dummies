@extends('layouts.app')

@section('titulo', 'Campañas')
@section('descripcion', 'Tus campañas de D&D como Dungeon Master y como jugador.')

@push('head')
    @vite('resources/css/pages/campanyas.css')
@endpush

@section('contenido')
@php
    $activas = $campanasDM->where('estado', 'activa');
    $pausadas = $campanasDM->where('estado', 'pausada');
    $finalizadas = $campanasDM->where('estado', 'finalizada');
    $totalDM = $campanasDM->count();
@endphp

<div class="contenedor">

    @if ($errors->any())
        <div class="alerta alerta-error" role="alert">{{ $errors->first() }}</div>
    @endif

    <div class="barra-acciones">
        <p class="barra-acciones-info">{{ $totalDM }} {{ $totalDM === 1 ? 'campaña' : 'campañas' }} como Dungeon Master</p>
        <button type="button" class="btn btn-primario" data-modal-abrir="modal-crear-campana">
            @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Crear campaña
        </button>
    </div>

    {{-- COMO DUNGEON MASTER --}}
    @if ($totalDM > 0)
        <section class="bloque" aria-label="Campañas que diriges">
            <div role="tablist" class="tabs-lista" aria-label="Estado de la campaña">
                <button type="button" role="tab" class="tab-boton" id="tab-activas" aria-controls="panel-activas" aria-selected="true">
                    @include('partials.icon', ['name' => 'swords', 'class' => 'icon-sm']) Activas ({{ $activas->count() }})
                </button>
                @if ($pausadas->isNotEmpty())
                    <button type="button" role="tab" class="tab-boton" id="tab-pausadas" aria-controls="panel-pausadas" aria-selected="false" tabindex="-1">
                        Pausadas ({{ $pausadas->count() }})
                    </button>
                @endif
                @if ($finalizadas->isNotEmpty())
                    <button type="button" role="tab" class="tab-boton" id="tab-finalizadas" aria-controls="panel-finalizadas" aria-selected="false" tabindex="-1">
                        Finalizadas ({{ $finalizadas->count() }})
                    </button>
                @endif
            </div>

            @foreach (['activas' => $activas, 'pausadas' => $pausadas, 'finalizadas' => $finalizadas] as $clave => $grupo)
                @continue($clave !== 'activas' && $grupo->isEmpty())
                <div role="tabpanel" class="tab-panel camp-lista" id="panel-{{ $clave }}" aria-labelledby="tab-{{ $clave }}" @if ($clave !== 'activas') hidden @endif>
                    @forelse ($grupo as $campana)
                        <article class="camp-card camp-card--{{ $campana->estado }}">
                            <div class="camp-cuerpo">
                                <h3 class="camp-nombre">
                                    <a href="{{ url('/campanyas/'.$campana->id) }}">{{ $campana->nombre }}</a>
                                </h3>
                                <p class="camp-meta">
                                    {{ $campana->ambientacion ?? 'Sin ambientación' }}
                                    · Nivel {{ $campana->nivel_inicial }}{{ $campana->nivel_maximo ? '-'.$campana->nivel_maximo : '+' }}
                                    · {{ $campana->sesiones_count }} {{ $campana->sesiones_count === 1 ? 'sesión' : 'sesiones' }}
                                </p>
                                @if ($campana->descripcion)
                                    <p class="camp-desc">{{ Str::limit($campana->descripcion, 120) }}</p>
                                @endif
                                <div class="camp-pie">
                                    <span class="estado estado--{{ $campana->estado }}">{{ $campana->estado }}</span>
                                    <span class="camp-codigo">Código <strong>{{ $campana->codigo_invitacion }}</strong></span>
                                </div>
                            </div>
                            <form class="camp-acciones" method="POST" action="/campanyas/{{ $campana->id }}"
                                  data-confirm="¿Eliminar la campaña «{{ $campana->nombre }}»? Se perderán sus sesiones y notas.">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icono btn-icono-peligro" aria-label="Eliminar {{ $campana->nombre }}" title="Eliminar">
                                    @include('partials.icon', ['name' => 'trash'])
                                </button>
                            </form>
                        </article>
                    @empty
                        <p class="camp-vacio">No tienes campañas activas.</p>
                    @endforelse
                </div>
            @endforeach
        </section>
    @else
        <div class="vacio">
            @include('partials.icon', ['name' => 'scroll', 'class' => 'icon-xl'])
            <h3>No tienes campañas aún</h3>
            <p>¡Crea tu primera campaña y empieza la aventura!</p>
            <button type="button" class="btn btn-primario" data-modal-abrir="modal-crear-campana">
                @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Crear primera campaña
            </button>
        </div>
    @endif

    {{-- COMO JUGADOR --}}
    @if ($campanasJugador->isNotEmpty())
        <section class="bloque" aria-labelledby="titulo-juegas">
            <h2 class="seccion-titulo" id="titulo-juegas">@include('partials.icon', ['name' => 'dice']) Campañas en las que juegas</h2>
            <div class="camp-lista">
                @foreach ($campanasJugador as $campana)
                    <article class="camp-card camp-card--jugador">
                        <div class="camp-cuerpo">
                            <h3 class="camp-nombre">
                                <a href="{{ url('/campanyas/'.$campana->id) }}">{{ $campana->nombre }}</a>
                            </h3>
                            <p class="camp-meta">
                                DM: {{ $campana->dungeonMaster->nombre }}
                                · {{ $campana->ambientacion ?? 'Sin ambientación' }}
                                · {{ $campana->sesiones_count }} {{ $campana->sesiones_count === 1 ? 'sesión' : 'sesiones' }}
                            </p>
                            @if ($campana->descripcion)
                                <p class="camp-desc">{{ Str::limit($campana->descripcion, 120) }}</p>
                            @endif
                            <div class="camp-pie">
                                <span class="estado estado--{{ $campana->estado }}">{{ $campana->estado }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    {{-- UNIRSE --}}
    <section class="bloque" aria-labelledby="titulo-unirse">
        <h2 class="seccion-titulo" id="titulo-unirse">@include('partials.icon', ['name' => 'lock']) Unirse a una campaña</h2>
        <form class="tarjeta camp-unirse" method="POST" action="/unirse-campana">
            @csrf
            <div class="campo">
                <label for="codigo_invitacion">Código de invitación</label>
                <input id="codigo_invitacion" type="text" name="codigo_invitacion" class="control control-codigo"
                       placeholder="ABC123" maxlength="6" autocomplete="off" autocapitalize="characters" spellcheck="false" required>
                @error('codigo_invitacion')
                    <span class="campo-error">{{ $message }}</span>
                @enderror
            </div>

            @if ($misPersonajes->isNotEmpty())
                <div class="campo">
                    <label for="personaje_id">Personaje con el que juegas</label>
                    <select id="personaje_id" name="personaje_id" class="control">
                        <option value="">Sin personaje por ahora</option>
                        @foreach ($misPersonajes as $personaje)
                            <option value="{{ $personaje->id }}">{{ $personaje->nombre }} (Nivel {{ $personaje->nivel }})</option>
                        @endforeach
                    </select>
                </div>
            @else
                <p class="camp-vacio">No tienes personajes activos. Puedes unirte sin personaje y añadirlo después.</p>
            @endif

            <div class="camp-unirse-pie">
                <button type="submit" class="btn btn-primario">Unirse</button>
            </div>
        </form>
    </section>
</div>

{{-- MODAL: NUEVA CAMPAÑA --}}
<dialog class="modal" id="modal-crear-campana" aria-labelledby="titulo-nueva-campana" @if ($errors->any() && old('nombre') !== null) data-abrir-al-cargar @endif>
    <form class="modal-form" method="POST" action="/campanyas">
        @csrf
        <header class="modal-cab">
            <h2 id="titulo-nueva-campana">Nueva campaña</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>

        <div class="modal-cuerpo">
            <div class="campo">
                <label for="nc-nombre">Nombre <span class="req">*</span></label>
                <input id="nc-nombre" type="text" name="nombre" class="control" value="{{ old('nombre') }}" required maxlength="255">
            </div>
            <div class="campo">
                <label for="nc-descripcion">Descripción</label>
                <textarea id="nc-descripcion" name="descripcion" class="control" rows="3">{{ old('descripcion') }}</textarea>
            </div>
            <div class="campo">
                <label for="nc-ambientacion">Ambientación</label>
                <input id="nc-ambientacion" type="text" name="ambientacion" class="control" value="{{ old('ambientacion') }}" placeholder="Barovia, Reinos Olvidados…" maxlength="255">
            </div>
            <div class="campos-grid campos-grid--3">
                <div class="campo">
                    <label for="nc-nivel-ini">Nivel inicial <span class="req">*</span></label>
                    <input id="nc-nivel-ini" type="number" name="nivel_inicial" class="control" value="{{ old('nivel_inicial', 1) }}" min="1" max="20" inputmode="numeric" required>
                </div>
                <div class="campo">
                    <label for="nc-nivel-max">Nivel máximo</label>
                    <input id="nc-nivel-max" type="number" name="nivel_maximo" class="control" value="{{ old('nivel_maximo') }}" min="1" max="20" inputmode="numeric">
                </div>
                <div class="campo">
                    <label for="nc-estado">Estado <span class="req">*</span></label>
                    <select id="nc-estado" name="estado" class="control" required>
                        <option value="activa">Activa</option>
                        <option value="pausada">Pausada</option>
                        <option value="finalizada">Finalizada</option>
                    </select>
                </div>
            </div>
        </div>

        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Crear campaña</button>
        </footer>
    </form>
</dialog>
@endsection
