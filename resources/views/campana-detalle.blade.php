@extends('layouts.app')

@section('titulo', $campana->nombre)
@section('descripcion', 'Sesiones, jugadores, enemigos y notas de la campaña '.$campana->nombre.'.')

@push('head')
    @vite(['resources/css/pages/campana-detalle.css', 'resources/js/pages/campana-detalle.js'])
@endpush

@section('contenido')
@php
    $dm = $campana->dungeonMaster;
    $sesiones = $campana->sesiones->sortByDesc('numero_sesion');
    $fechaCorta = fn ($s) => $s->fecha_sesion ? \Carbon\Carbon::parse($s->fecha_sesion)->format('d/m/Y') : 'Sin fecha';
@endphp

{{-- Dato que necesita campana-detalle.js --}}
<div id="campana-datos" data-campana-id="{{ $campana->id }}" hidden></div>

<div class="contenedor cd">

    {{-- ================= CABECERA (DM y jugador) ================= --}}
    <header class="tarjeta cd-cab">
        <div class="cd-cab-info">
            <h2>{{ $campana->nombre }}</h2>
            <p class="cd-meta">
                {{ $campana->ambientacion ?? 'Sin ambientación' }}
                · Nivel {{ $campana->nivel_inicial }}{{ $campana->nivel_maximo ? '-'.$campana->nivel_maximo : '+' }}
            </p>
            @if ($campana->descripcion)
                <p class="cd-desc">{{ $campana->descripcion }}</p>
            @endif
        </div>

        <div class="cd-cab-lado">
            <span class="estado estado--{{ $campana->estado }}">{{ $campana->estado }}</span>
            @if ($esDM)
                <p class="cd-codigo">Código <strong>{{ $campana->codigo_invitacion }}</strong></p>
            @endif
        </div>

        <div class="cd-cab-acciones">
            @if ($esDM)
                <button type="button" class="btn btn-secundario btn-sm" data-modal-abrir="modal-editar-campana">
                    @include('partials.icon', ['name' => 'edit', 'class' => 'icon-sm']) Editar campaña
                </button>
                <form method="POST" action="/campanyas/{{ $campana->id }}" data-confirm="¿Eliminar la campaña «{{ $campana->nombre }}»? Se perderán sus sesiones y notas.">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-peligro btn-sm">
                        @include('partials.icon', ['name' => 'trash', 'class' => 'icon-sm']) Eliminar
                    </button>
                </form>
            @endif
            <a href="/campanyas" class="btn btn-secundario btn-sm">
                @include('partials.icon', ['name' => 'arrow-left', 'class' => 'icon-sm']) Volver
            </a>
        </div>
    </header>

    @if ($esDM)
    {{-- ======================= VISTA DM ======================= --}}
    <div class="cd-grid">

        {{-- SESIONES --}}
        <section aria-labelledby="t-sesiones">
            <h2 class="seccion-titulo" id="t-sesiones">@include('partials.icon', ['name' => 'scroll']) Sesiones ({{ $campana->sesiones->count() }})</h2>

            @forelse ($sesiones as $sesion)
                <article class="cd-item">
                    <div class="cd-item-cuerpo">
                        <button type="button" class="cd-titulo-boton" data-editar-sesion
                                data-id="{{ $sesion->id }}"
                                data-titulo="{{ $sesion->titulo }}"
                                data-numero="{{ $sesion->numero_sesion }}"
                                data-fecha="{{ $sesion->fecha_sesion ? \Carbon\Carbon::parse($sesion->fecha_sesion)->format('Y-m-d') : '' }}"
                                data-resumen="{{ $sesion->resumen }}"
                                data-duracion="{{ $sesion->duracion_minutos }}">
                            Sesión {{ $sesion->numero_sesion }}: {{ $sesion->titulo }}
                        </button>
                        <p class="cd-meta">
                            {{ $fechaCorta($sesion) }}{{ $sesion->duracion_minutos ? ' · '.$sesion->duracion_minutos.' min' : '' }}
                        </p>
                        @if ($sesion->resumen)
                            <p class="cd-texto-suave">{{ Str::limit($sesion->resumen, 80) }}</p>
                        @endif
                    </div>
                    <form class="cd-item-acciones" method="POST" action="/campanyas/{{ $campana->id }}/sesiones/{{ $sesion->id }}" data-confirm="¿Eliminar la sesión {{ $sesion->numero_sesion }}?">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icono btn-icono-peligro" aria-label="Eliminar la sesión {{ $sesion->numero_sesion }}" title="Eliminar">@include('partials.icon', ['name' => 'trash'])</button>
                    </form>
                </article>
            @empty
                <p class="cd-vacio">No hay sesiones registradas aún.</p>
            @endforelse

            <button type="button" class="btn btn-secundario btn-sm cd-anadir" data-modal-abrir="modal-sesion">
                @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir sesión
            </button>
        </section>

        {{-- ENEMIGOS --}}
        <section aria-labelledby="t-enemigos">
            <h2 class="seccion-titulo" id="t-enemigos">@include('partials.icon', ['name' => 'skull']) Enemigos ({{ $campana->enemigos->count() }})</h2>

            @forelse ($campana->enemigos as $enemigo)
                <article class="cd-item">
                    <div class="cd-item-cuerpo cd-item-cuerpo--fila">
                        @if ($enemigo->imagen)
                            <img class="cd-miniatura" src="{{ Storage::url($enemigo->imagen) }}" alt="{{ $enemigo->nombre }}" width="48" height="48" loading="lazy" data-lightbox>
                        @else
                            <span class="cd-miniatura cd-miniatura--vacia">@include('partials.icon', ['name' => 'skull'])</span>
                        @endif
                        <div>
                            <p class="cd-nombre">
                                @if ($enemigo->es_boss)<span class="cd-boss">Boss</span>@endif
                                {{ $enemigo->nombre }}
                            </p>
                            <p class="cd-meta">{{ $enemigo->tipo }} · CR {{ $enemigo->cr_texto }}</p>
                            @if ($enemigo->pivot->visible_jugadores)
                                <span class="cd-visibilidad cd-visibilidad--si">@include('partials.icon', ['name' => 'eye', 'class' => 'icon-sm']) Visible para jugadores</span>
                            @else
                                <span class="cd-visibilidad">@include('partials.icon', ['name' => 'lock', 'class' => 'icon-sm']) Solo DM</span>
                            @endif
                        </div>
                    </div>
                    <form class="cd-item-acciones" method="POST" action="/campanyas/{{ $campana->id }}/enemigos/{{ $enemigo->id }}" data-confirm="¿Quitar a {{ $enemigo->nombre }} de la campaña?">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icono btn-icono-peligro" aria-label="Quitar a {{ $enemigo->nombre }}" title="Quitar">@include('partials.icon', ['name' => 'x'])</button>
                    </form>
                </article>
            @empty
                <p class="cd-vacio">No hay enemigos asignados.</p>
            @endforelse

            @php $disponibles = $enemigos->whereNotIn('id', $campana->enemigos->pluck('id')); @endphp
            @if ($disponibles->isNotEmpty())
                <form class="cd-form-enemigo" method="POST" action="/campanyas/{{ $campana->id }}/enemigos">
                    @csrf
                    <label class="sr-only" for="enemigo_id">Enemigo a añadir</label>
                    <select id="enemigo_id" name="enemigo_id" class="control">
                        @foreach ($disponibles as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre }} (CR {{ $e->cr_texto }})</option>
                        @endforeach
                    </select>
                    <label class="check-inline" for="enemigo_visible">
                        <input type="checkbox" name="visible_jugadores" id="enemigo_visible"> Visible
                    </label>
                    <button type="submit" class="btn btn-primario btn-sm">@include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir</button>
                </form>
            @endif
        </section>
    </div>

    {{-- JUGADORES --}}
    <section class="cd-seccion" aria-labelledby="t-jugadores">
        <h2 class="seccion-titulo" id="t-jugadores">@include('partials.icon', ['name' => 'user']) Jugadores ({{ $campana->usuarios->count() }})</h2>
        <div class="cd-jugadores">
            <article class="tarjeta cd-jugador cd-jugador--dm">
                <div class="cd-jugador-cab">
                    <img class="avatar avatar-md" src="{{ $dm->avatar_url }}" alt="" width="48" height="48" loading="lazy" data-lightbox>
                    <div>
                        <p class="cd-nombre">{{ $dm->nombre }}</p>
                        <p class="cd-rol cd-rol--dm">Dungeon Master</p>
                    </div>
                </div>
            </article>

            @foreach ($campana->usuarios as $usuario)
                @php $pj = optional($personajesPorUsuario[$usuario->id] ?? null)->first(); @endphp
                <article class="tarjeta cd-jugador">
                    <div class="cd-jugador-cab">
                        <img class="avatar avatar-md" src="{{ $usuario->avatar_url }}" alt="" width="48" height="48" loading="lazy" data-lightbox>
                        <div class="cd-jugador-datos">
                            <p class="cd-nombre">{{ $usuario->nombre }}</p>
                            <p class="cd-rol">Jugador</p>
                        </div>
                        <form method="POST" action="/campanyas/{{ $campana->id }}/usuarios/{{ $usuario->id }}" data-confirm="¿Expulsar a {{ $usuario->nombre }} de la campaña?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icono btn-icono-peligro" aria-label="Expulsar a {{ $usuario->nombre }}" title="Expulsar">@include('partials.icon', ['name' => 'x'])</button>
                        </form>
                    </div>
                    @if ($pj)
                        <button type="button" class="cd-pj" data-ver-personaje="{{ $pj->id }}">
                            <img class="avatar" src="{{ $pj->avatar_url }}" alt="" width="40" height="40" loading="lazy">
                            <span>
                                <strong>{{ $pj->nombre }}</strong>
                                <small>{{ $pj->clase->nombre ?? '—' }} · Nivel {{ $pj->nivel }}</small>
                            </span>
                        </button>
                    @else
                        <p class="cd-texto-suave">Sin personaje asignado</p>
                    @endif
                </article>
            @endforeach

            @if ($campana->usuarios->isEmpty())
                <p class="cd-vacio">Aún no hay jugadores unidos.</p>
            @endif
        </div>
    </section>

    {{-- NOTAS DEL DM --}}
    <section class="cd-seccion" aria-labelledby="t-notas">
        <div class="cd-seccion-cab">
            <h2 class="seccion-titulo" id="t-notas">@include('partials.icon', ['name' => 'book']) Notas de la campaña ({{ $campana->notas->count() }})</h2>
            <button type="button" class="btn btn-secundario btn-sm" data-modal-abrir="modal-nota">
                @include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir nota
            </button>
        </div>

        @forelse ($campana->notas->sortByDesc('created_at') as $nota)
            <article class="cd-item cd-item--nota">
                <div class="cd-item-cuerpo">
                    <div class="cd-nota-cab">
                        <button type="button" class="cd-titulo-boton" data-editar-nota
                                data-id="{{ $nota->id }}"
                                data-titulo="{{ $nota->titulo }}"
                                data-contenido="{{ $nota->contenido }}"
                                data-visible="{{ $nota->visible_jugadores ? 'true' : 'false' }}">{{ $nota->titulo }}</button>
                        @if ($nota->visible_jugadores)
                            <span class="cd-visibilidad cd-visibilidad--si">@include('partials.icon', ['name' => 'eye', 'class' => 'icon-sm']) Visible</span>
                        @else
                            <span class="cd-visibilidad">@include('partials.icon', ['name' => 'lock', 'class' => 'icon-sm']) Solo DM</span>
                        @endif
                    </div>
                    <p class="cd-nota-texto">{{ $nota->contenido }}</p>
                    <p class="cd-texto-suave">{{ $nota->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <form class="cd-item-acciones" method="POST" action="/campanyas/{{ $campana->id }}/notas/{{ $nota->id }}" data-confirm="¿Eliminar la nota «{{ $nota->titulo }}»?">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-icono btn-icono-peligro" aria-label="Eliminar la nota {{ $nota->titulo }}" title="Eliminar">@include('partials.icon', ['name' => 'trash'])</button>
                </form>
            </article>
        @empty
            <p class="cd-vacio">No hay notas aún.</p>
        @endforelse
    </section>

    @else
    {{-- ======================= VISTA JUGADOR ======================= --}}
    @php
        $miPersonaje = optional($personajesPorUsuario[Auth::id()] ?? null)->first();
        $companeros = $campana->usuarios->where('id', '!=', Auth::id());
        $notasVisibles = $campana->notas->where('visible_jugadores', true);
        $enemigosVisibles = $campana->enemigos->filter(fn ($e) => $e->pivot->visible_jugadores);
    @endphp

    {{-- 1. MI PERSONAJE --}}
    <section class="cd-seccion" aria-labelledby="t-mi-pj">
        <h2 class="seccion-titulo" id="t-mi-pj">@include('partials.icon', ['name' => 'sword']) Tu personaje</h2>
        @if ($miPersonaje)
            @php
                $pivotPersonaje = $campana->personajes->where('id', $miPersonaje->id)->first();
                $historiaVisible = $pivotPersonaje?->pivot->historia_visible ?? false;
            @endphp
            <article class="tarjeta cd-mi-pj">
                <img class="avatar-lg" src="{{ $miPersonaje->avatar_url }}" alt="{{ $miPersonaje->nombre }}" width="90" height="90" data-lightbox>
                <div class="cd-mi-pj-datos">
                    <h3>{{ $miPersonaje->nombre }}</h3>
                    <p class="cd-subtitulo">{{ $miPersonaje->raza->nombre ?? '—' }} · {{ $miPersonaje->clase->nombre ?? '—' }}</p>
                    <span class="pjm-badge pjm-badge--nivel">Nivel {{ $miPersonaje->nivel }}</span>
                </div>
                <div class="cd-mi-pj-acciones">
                    <form method="POST" action="/campanyas/{{ $campana->id }}/personaje/historia-visible">
                        @csrf
                        <button type="submit" class="btn btn-secundario btn-sm">
                            @include('partials.icon', ['name' => $historiaVisible ? 'eye' : 'lock', 'class' => 'icon-sm'])
                            {{ $historiaVisible ? 'Historia visible' : 'Historia privada' }}
                        </button>
                    </form>
                    <button type="button" class="btn btn-secundario btn-sm" data-ver-personaje="{{ $miPersonaje->id }}">Ver ficha completa</button>
                </div>
            </article>
        @else
            <div class="tarjeta cd-sin-pj">
                <p>Aún no tienes un personaje asignado a esta campaña.</p>
                @if ($misPersonajes->isNotEmpty())
                    <form class="cd-form-enemigo" method="POST" action="/campanyas/{{ $campana->id }}/personaje">
                        @csrf
                        <label class="sr-only" for="personaje_id">Personaje</label>
                        <select id="personaje_id" name="personaje_id" class="control">
                            @foreach ($misPersonajes as $p)
                                <option value="{{ $p->id }}">{{ $p->nombre }} (Nv. {{ $p->nivel }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primario">Unir personaje</button>
                    </form>
                @else
                    <p class="cd-texto-suave">No tienes personajes creados.</p>
                @endif
            </div>
        @endif
    </section>

    {{-- 2. COMPAÑEROS --}}
    <section class="cd-seccion" aria-labelledby="t-companeros">
        <h2 class="seccion-titulo" id="t-companeros">@include('partials.icon', ['name' => 'user']) Compañeros de aventura</h2>
        <ul class="cd-companeros" role="list">
            <li class="cd-companero">
                <img class="avatar-lg avatar-lg--sm" src="{{ $dm->avatar_url }}" alt="" width="64" height="64" loading="lazy" data-lightbox>
                <strong>{{ $dm->nombre }}</strong>
                <span class="cd-rol cd-rol--dm">DM</span>
            </li>
            @foreach ($companeros as $companero)
                @php $pComp = optional($personajesPorUsuario[$companero->id] ?? null)->first(); @endphp
                <li class="cd-companero">
                    @if ($pComp)
                        <button type="button" class="cd-companero-boton" data-ver-personaje="{{ $pComp->id }}" aria-label="Ver la ficha de {{ $pComp->nombre }}">
                            <img class="avatar-lg avatar-lg--sm" src="{{ $pComp->avatar_url }}" alt="" width="64" height="64" loading="lazy">
                        </button>
                    @else
                        <img class="avatar-lg avatar-lg--sm" src="{{ $companero->avatar_url }}" alt="" width="64" height="64" loading="lazy">
                    @endif
                    <strong>{{ $companero->nombre }}</strong>
                    @if ($pComp)
                        <span class="cd-pj-nombre">{{ $pComp->nombre }}</span>
                        <span class="cd-texto-suave">{{ $pComp->clase->nombre ?? '' }} Nv.{{ $pComp->nivel }}</span>
                    @else
                        <span class="cd-texto-suave">Sin personaje</span>
                    @endif
                </li>
            @endforeach
        </ul>
        @if ($companeros->isEmpty())
            <p class="cd-vacio">Aún no hay más jugadores en esta campaña.</p>
        @endif
    </section>

    {{-- 3. NOTAS DEL DM --}}
    @if ($notasVisibles->isNotEmpty())
        <section class="cd-seccion" aria-labelledby="t-notas-dm">
            <h2 class="seccion-titulo" id="t-notas-dm">@include('partials.icon', ['name' => 'book']) Notas del Dungeon Master</h2>
            @foreach ($notasVisibles->sortByDesc('created_at') as $nota)
                <article class="tarjeta cd-nota-lectura">
                    <h3>{{ $nota->titulo }}</h3>
                    <p class="cd-nota-texto">{{ $nota->contenido }}</p>
                    <p class="cd-texto-suave">{{ $nota->created_at->format('d/m/Y') }}</p>
                </article>
            @endforeach
        </section>
    @endif

    {{-- 4. CRÓNICAS --}}
    @if ($campana->sesiones->isNotEmpty())
        <section class="cd-seccion" aria-labelledby="t-cronicas">
            <h2 class="seccion-titulo" id="t-cronicas">@include('partials.icon', ['name' => 'scroll']) Crónicas de la campaña</h2>
            @foreach ($sesiones as $sesion)
                <article class="tarjeta cd-cronica">
                    <div class="cd-cronica-cab">
                        <span class="estado">Sesión {{ $sesion->numero_sesion }}</span>
                        <h3>{{ $sesion->titulo }}</h3>
                        @if ($sesion->fecha_sesion)
                            <time class="cd-texto-suave cd-cronica-fecha">{{ $fechaCorta($sesion) }}</time>
                        @endif
                    </div>
                    @if ($sesion->resumen)
                        <p class="cd-nota-texto">{{ $sesion->resumen }}</p>
                    @endif
                    @if ($sesion->duracion_minutos)
                        <p class="cd-texto-suave">{{ $sesion->duracion_minutos }} min</p>
                    @endif
                </article>
            @endforeach
        </section>
    @endif

    {{-- 5. ENEMIGOS VISIBLES --}}
    @if ($enemigosVisibles->isNotEmpty())
        <section class="cd-seccion" aria-labelledby="t-amenazas">
            <h2 class="seccion-titulo" id="t-amenazas">@include('partials.icon', ['name' => 'skull']) Amenazas conocidas</h2>
            <ul class="cd-amenazas" role="list">
                @foreach ($enemigosVisibles as $enemigo)
                    <li class="tarjeta cd-amenaza">
                        @if ($enemigo->imagen)
                            <img src="{{ Storage::url($enemigo->imagen) }}" alt="{{ $enemigo->nombre }}" width="300" height="115" loading="lazy" data-lightbox>
                        @else
                            <span class="cd-amenaza-vacia">@include('partials.icon', ['name' => 'skull', 'class' => 'icon-lg'])</span>
                        @endif
                        <div class="cd-amenaza-cuerpo">
                            <p class="cd-nombre">@if ($enemigo->es_boss)<span class="cd-boss">Boss</span>@endif {{ $enemigo->nombre }}</p>
                            <p class="cd-texto-suave">{{ $enemigo->tipo }} · CR {{ $enemigo->cr_texto }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif
    @endif
</div>

@if ($esDM)
{{-- ======================= MODALES DM ======================= --}}

<dialog class="modal" id="modal-editar-campana" aria-labelledby="t-editar-campana">
    <form class="modal-form" method="POST" action="/campanyas/{{ $campana->id }}">
        @csrf @method('PATCH')
        <header class="modal-cab">
            <h2 id="t-editar-campana">Editar campaña</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            <div class="campo">
                <label for="ec-nombre">Nombre <span class="req">*</span></label>
                <input type="text" name="nombre" id="ec-nombre" class="control" value="{{ $campana->nombre }}" required maxlength="255">
            </div>
            <div class="campo">
                <label for="ec-descripcion">Descripción</label>
                <textarea name="descripcion" id="ec-descripcion" class="control" rows="3">{{ $campana->descripcion }}</textarea>
            </div>
            <div class="campo">
                <label for="ec-ambientacion">Ambientación</label>
                <input type="text" name="ambientacion" id="ec-ambientacion" class="control" value="{{ $campana->ambientacion }}" maxlength="255">
            </div>
            <div class="campos-grid campos-grid--3">
                <div class="campo">
                    <label for="ec-nivel-ini">Nivel inicial <span class="req">*</span></label>
                    <input type="number" name="nivel_inicial" id="ec-nivel-ini" class="control" value="{{ $campana->nivel_inicial }}" min="1" max="20" inputmode="numeric" required>
                </div>
                <div class="campo">
                    <label for="ec-nivel-max">Nivel máximo</label>
                    <input type="number" name="nivel_maximo" id="ec-nivel-max" class="control" value="{{ $campana->nivel_maximo }}" min="1" max="20" inputmode="numeric">
                </div>
                <div class="campo">
                    <label for="ec-estado">Estado <span class="req">*</span></label>
                    <select name="estado" id="ec-estado" class="control" required>
                        @foreach (['activa' => 'Activa', 'pausada' => 'Pausada', 'finalizada' => 'Finalizada'] as $valor => $texto)
                            <option value="{{ $valor }}" @selected($campana->estado === $valor)>{{ $texto }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Guardar cambios</button>
        </footer>
    </form>
</dialog>

<dialog class="modal" id="modal-sesion" aria-labelledby="t-nueva-sesion">
    <form class="modal-form" method="POST" action="/campanyas/{{ $campana->id }}/sesiones">
        @csrf
        <header class="modal-cab">
            <h2 id="t-nueva-sesion">Nueva sesión</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            @include('partials.campana.sesion-form', ['p' => 'nueva', 'numero' => $campana->sesiones->count() + 1])
        </div>
        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Guardar sesión</button>
        </footer>
    </form>
</dialog>

<dialog class="modal" id="modal-editar-sesion" aria-labelledby="t-editar-sesion">
    <form class="modal-form" id="form-editar-sesion" method="POST">
        @csrf @method('PATCH')
        <header class="modal-cab">
            <h2 id="t-editar-sesion">Editar sesión</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            @include('partials.campana.sesion-form', ['p' => 'edit'])
        </div>
        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Guardar cambios</button>
        </footer>
    </form>
</dialog>

<dialog class="modal" id="modal-nota" aria-labelledby="t-nueva-nota">
    <form class="modal-form" method="POST" action="/campanyas/{{ $campana->id }}/notas">
        @csrf
        <header class="modal-cab">
            <h2 id="t-nueva-nota">Nueva nota</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            @include('partials.campana.nota-form', ['p' => 'nueva-nota', 'visibleDefecto' => true])
        </div>
        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Guardar nota</button>
        </footer>
    </form>
</dialog>

<dialog class="modal" id="modal-editar-nota" aria-labelledby="t-editar-nota">
    <form class="modal-form" id="form-editar-nota" method="POST">
        @csrf @method('PATCH')
        <header class="modal-cab">
            <h2 id="t-editar-nota">Editar nota</h2>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            @include('partials.campana.nota-form', ['p' => 'edit-nota'])
        </div>
        <footer class="modal-pie">
            <button type="button" class="btn btn-secundario" data-modal-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-primario">Guardar cambios</button>
        </footer>
    </form>
</dialog>
@endif

{{-- ======================= FICHA RESUMIDA (DM y jugador) ======================= --}}
<dialog class="modal modal--ancho" id="modal-personaje" aria-labelledby="modal-p-nombre">
    <div class="modal-form">
        <header class="modal-cab pjm-cab">
            <img id="modal-p-avatar" class="avatar-lg" src="" alt="" width="90" height="90" data-lightbox>
            <div class="pjm-titulo">
                <h2 id="modal-p-nombre"></h2>
                <p id="modal-p-subtitulo"></p>
                <div id="modal-p-badges" class="pjm-badges"></div>
            </div>
            <button type="button" class="btn-icono" data-modal-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x'])</button>
        </header>
        <div class="modal-cuerpo">
            <section class="pjm-seccion">
                <h3 class="form-subtitulo">Características</h3>
                <div id="modal-p-stats" class="pjm-grid pjm-grid--stats"></div>
            </section>
            <section class="pjm-seccion">
                <h3 class="form-subtitulo">Combate</h3>
                <div id="modal-p-combate" class="pjm-grid pjm-grid--combate"></div>
            </section>
            <section class="pjm-seccion" id="modal-p-historia-sec" hidden>
                <h3 class="form-subtitulo">Historia</h3>
                <p id="modal-p-historia" class="pjm-historia"></p>
            </section>
            <section class="pjm-seccion" id="modal-p-personalidad-sec" hidden>
                <h3 class="form-subtitulo">Personalidad</h3>
                <div id="modal-p-personalidad" class="pjm-grid pjm-grid--personalidad"></div>
            </section>
            <section class="pjm-seccion" id="modal-p-equipo-sec" hidden>
                <h3 class="form-subtitulo">Equipo</h3>
                <div id="modal-p-equipo" class="pjm-grid pjm-grid--equipo"></div>
            </section>
        </div>
    </div>
</dialog>
@endsection
