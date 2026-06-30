@extends('layouts.app')

@section('titulo', $campana->nombre)

@section('contenido')

@if (session('success'))
    <div class="alerta alerta-exito">{{ session('success') }}</div>
@endif

<!-- CABECERA (igual para DM y jugador) -->
<div class="tarjeta" style="margin-bottom: 1.5rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="font-size: 1.4rem; margin-bottom: 6px;">{{ $campana->nombre }}</h2>
            <div style="color: var(--t-secundario); font-size: 0.9rem; margin-bottom: 8px;">
                {{ $campana->ambientacion ?? 'Sin ambientación' }} · Nivel {{ $campana->nivel_inicial }}{{ $campana->nivel_maximo ? '-'.$campana->nivel_maximo : '+' }}
            </div>
            @if($campana->descripcion)
            <p style="color: var(--t-tenue); font-size: 0.95rem;">{{ $campana->descripcion }}</p>
            @endif
        </div>
        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
            <span style="background: rgba(179,3,3,0.1); color: var(--c-rojo-claro); border: 1px solid var(--b-medio); padding: 4px 14px; border-radius: 20px; font-size: 0.8rem; font-family: var(--f-titulo); letter-spacing: 1px; text-transform: uppercase;">{{ $campana->estado }}</span>
            @if($esDM)
            <div style="color: var(--t-secundario); font-size: 0.85rem;">
                Código: <strong style="color: var(--c-oro); letter-spacing: 3px; font-size: 1rem;">{{ $campana->codigo_invitacion }}</strong>
            </div>
            @endif
        </div>
    </div>
    <div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
        @if($esDM)
            <button onclick="abrirModalEditar()" class="btn btn-secundario btn-sm">@include('partials.icon', ['name' => 'edit', 'class' => 'icon-sm']) Editar campaña</button>
            <form method="POST" action="/campanyas/{{ $campana->id }}" onsubmit="return confirm('¿Eliminar esta campaña?')" style="display:inline">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-peligro btn-sm">@include('partials.icon', ['name' => 'trash', 'class' => 'icon-sm']) Eliminar</button>
            </form>
        @endif
        <a href="/campanyas" class="btn btn-secundario btn-sm">← Volver</a>
    </div>
</div>

@if($esDM)
{{-- ============================================================
     VISTA DM
     ============================================================ --}}

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

    <!-- SESIONES -->
    <section>
        <div class="seccion-titulo">@include('partials.icon', ['name' => 'scroll']) Sesiones ({{ $campana->sesiones->count() }})</div>
        @forelse($campana->sesiones->sortByDesc('numero_sesion') as $sesion)
        <div class="tarjeta" style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1; cursor: pointer;" onclick="editarSesion({{ $sesion->id }}, '{{ addslashes($sesion->titulo) }}', {{ $sesion->numero_sesion }}, '{{ $sesion->fecha_sesion }}', '{{ addslashes($sesion->resumen ?? '') }}', {{ $sesion->duracion_minutos ?? 0 }})">
                    <div style="font-weight: bold;">Sesión {{ $sesion->numero_sesion }}: {{ $sesion->titulo }}</div>
                    <div style="color: var(--t-secundario); font-size: 0.85rem;">
                        {{ $sesion->fecha_sesion ? \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') : 'Sin fecha' }}
                        {{ $sesion->duracion_minutos ? '· '.$sesion->duracion_minutos.' min' : '' }}
                    </div>
                    @if($sesion->resumen)
                    <div style="color: var(--t-tenue); font-size: 0.85rem; margin-top: 4px;">{{ Str::limit($sesion->resumen, 80) }}</div>
                    @endif
                </div>
                <form method="POST" action="/campanyas/{{ $campana->id }}/sesiones/{{ $sesion->id }}" onsubmit="return confirm('¿Eliminar esta sesión?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-peligro btn-sm">🗑</button>
                </form>
            </div>
        </div>
        @empty
        <div style="color: var(--t-tenue); font-size: 0.9rem; padding: 1rem 0;">No hay sesiones registradas aún.</div>
        @endforelse
        <button onclick="abrirModalSesion()" class="btn btn-secundario btn-sm" style="margin-top: 10px;">+ Añadir sesión</button>
    </section>

    <!-- ENEMIGOS -->
    <section>
        <div class="seccion-titulo">@include('partials.icon', ['name' => 'skull']) Enemigos ({{ $campana->enemigos->count() }})</div>
        @forelse($campana->enemigos as $enemigo)
        <div class="tarjeta" style="margin-bottom: 10px;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 12px; flex: 1;">
                    @if($enemigo->imagen)
                    <img src="{{ Storage::url($enemigo->imagen) }}"
                        style="width: 48px; height: 48px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(179,3,3,0.3); cursor: zoom-in; flex-shrink: 0;"
                        onclick="event.stopPropagation(); document.getElementById('lightbox-campana-img').src=this.src; document.getElementById('lightbox-campana').style.display='flex';">
                    @else
                    <div style="width: 48px; height: 48px; border-radius: 6px; background: rgba(179,3,3,0.1); border: 1px solid rgba(179,3,3,0.2); display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 1.2rem;">💀</div>
                    @endif
                    <div>
                        <div style="font-weight: bold; display: flex; align-items: center; gap: 6px;">
                            @if($enemigo->es_boss)
                            <span style="background: rgba(179,3,3,0.2); color: #d44141; border: 1px solid rgba(179,3,3,0.4); padding: 1px 6px; border-radius: 4px; font-size: 0.7rem; font-family: var(--f-titulo); letter-spacing: 1px;">BOSS</span>
                            @endif
                            {{ $enemigo->nombre }}
                        </div>
                        <div style="color: var(--t-secundario); font-size: 0.85rem;">{{ $enemigo->tipo }} · CR {{ $enemigo->clase_de_desafio }}</div>
                        @if($enemigo->pivot->visible_jugadores)
                        <span style="color: var(--c-verde-claro); font-size: 0.75rem;">👁 Visible para jugadores</span>
                        @else
                        <span style="color: var(--t-tenue); font-size: 0.75rem;">🔒 Solo DM</span>
                        @endif
                    </div>
                </div>
                <form method="POST" action="/campanyas/{{ $campana->id }}/enemigos/{{ $enemigo->id }}" onsubmit="return confirm('¿Quitar este enemigo?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-peligro btn-sm">✕</button>
                </form>
            </div>
        </div>
        @empty
        <div style="color: var(--t-tenue); font-size: 0.9rem; padding: 1rem 0;">No hay enemigos asignados.</div>
        @endforelse

        @if($enemigos->count() > 0)
        <form method="POST" action="/campanyas/{{ $campana->id }}/enemigos" style="margin-top: 10px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            @csrf
            <select name="enemigo_id" style="flex: 1; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 8px 12px; color: white; font-family: var(--f-cuerpo);">
                @foreach($enemigos as $e)
                <option value="{{ $e->id }}">{{ $e->nombre }} (CR {{ $e->clase_de_desafio }})</option>
                @endforeach
            </select>
            <label style="color: var(--t-secundario); font-size: 0.85rem; display: flex; align-items: center; gap: 5px;">
                <input type="checkbox" name="visible_jugadores" style="accent-color: var(--c-rojo);"> Visible
            </label>
            <button type="submit" class="btn btn-primario btn-sm">+ Añadir</button>
        </form>
        @endif
    </section>

</div>

<!-- JUGADORES DM -->
<section style="margin-top: 1.5rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'user']) Jugadores ({{ $campana->usuarios->count() }})</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">
        <div class="tarjeta" style="border-top: 3px solid var(--c-rojo);">
            <div style="display: flex; align-items: center; gap: 12px;">
                @if($campana->dungeonMaster->avatar)
            <img src="{{ str_starts_with($campana->dungeonMaster->avatar, 'http') ? $campana->dungeonMaster->avatar : Storage::url($campana->dungeonMaster->avatar) }}"
                style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--c-rojo); flex-shrink: 0; cursor: zoom-in;"
                onclick="event.stopPropagation(); document.getElementById('lightbox-campana-img').src=this.src; document.getElementById('lightbox-campana').style.display='flex';">
            @else
            <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--c-rojo); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                {{ strtoupper(substr($campana->dungeonMaster->nombre, 0, 1)) }}
            </div>
            @endif
                <div>
                    <div style="font-weight: bold; font-size: 0.95rem;">{{ $campana->dungeonMaster->nombre }}</div>
                    <div style="color: var(--c-rojo-claro); font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">DUNGEON MASTER</div>
                </div>
            </div>
        </div>

        @foreach($campana->usuarios as $usuario)
        @php $personajeUsuario = isset($personajesPorUsuario[$usuario->id]) ? $personajesPorUsuario[$usuario->id]->first() : null; @endphp
        <div class="tarjeta" style="border-top: 3px solid var(--c-verde);">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                @if($usuario->avatar)
                <img src="{{ str_starts_with($usuario->avatar, 'http') ? $usuario->avatar : Storage::url($usuario->avatar) }}"
                    style="width: 42px; height: 42px; border-radius: 50%; object-fit: cover; border: 2px solid var(--c-verde); flex-shrink: 0; cursor: zoom-in;"
                    onclick="event.stopPropagation(); document.getElementById('lightbox-campana-img').src=this.src; document.getElementById('lightbox-campana').style.display='flex';">
                @else
                <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--c-verde); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                </div>
                @endif
                <div style="flex: 1;">
                    <div style="font-weight: bold; font-size: 0.95rem;">{{ $usuario->nombre }}</div>
                    <div style="color: var(--t-secundario); font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">JUGADOR</div>
                </div>
                <form method="POST" action="/campanyas/{{ $campana->id }}/usuarios/{{ $usuario->id }}" onsubmit="return confirm('¿Expulsar a {{ $usuario->nombre }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-peligro btn-sm">✕</button>
                </form>
            </div>
            @if($personajeUsuario)
            <div style="background: rgba(0,0,0,0.2); border-radius: 8px; padding: 12px; cursor: pointer;" onclick="verPersonaje({{ $personajeUsuario->id }})">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <img src="{{ $personajeUsuario->avatar_url }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--c-rojo);">
                    <div>
                        <div style="font-weight: bold; font-size: 0.9rem;">{{ $personajeUsuario->nombre }}</div>
                        <div style="color: var(--t-secundario); font-size: 0.78rem;">{{ $personajeUsuario->clase->nombre ?? '—' }} · Nivel {{ $personajeUsuario->nivel }}</div>
                    </div>
                </div>
            </div>
            @else
            <div style="color: var(--t-tenue); font-size: 0.82rem;">Sin personaje asignado</div>
            @endif
        </div>
        @endforeach

        @if($campana->usuarios->count() === 0)
        <div style="color: var(--t-tenue); font-size: 0.9rem; grid-column: 1/-1;">Aún no hay jugadores unidos.</div>
        @endif
    </div>
</section>

<!-- NOTAS DM -->
<section style="margin-top: 1.5rem;">
    <div class="seccion-titulo" style="display: flex; justify-content: space-between; align-items: center;">
        <span style="display:inline-flex;align-items:center;gap:6px;">@include('partials.icon', ['name' => 'book']) Notas de la campaña ({{ $campana->notas->count() }})</span>
        <button onclick="abrirModalNota()" class="btn btn-secundario btn-sm">+ Añadir nota</button>
    </div>
    @forelse($campana->notas->sortByDesc('created_at') as $nota)
    <div class="tarjeta" style="margin-bottom: 10px; border-left: 4px solid var(--c-oro); cursor: pointer;"
        data-nota-id="{{ $nota->id }}"
        data-nota-titulo="{{ $nota->titulo }}"
        data-nota-contenido="{{ $nota->contenido }}"
        data-nota-visible="{{ $nota->visible_jugadores ? 'true' : 'false' }}"
        onclick="editarNotaDesdeCard(this)">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div style="flex: 1;">
                <div style="font-weight: bold; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                    {{ $nota->titulo }}
                    @if($nota->visible_jugadores)
                    <span style="color: var(--c-verde-claro); font-size: 0.75rem;">👁 Visible</span>
                    @else
                    <span style="color: var(--t-tenue); font-size: 0.75rem;">🔒 Solo DM</span>
                    @endif
                </div>
                <div style="color: #b8c0c8; font-size: 0.9rem; line-height: 1.7; white-space: pre-wrap;">{{ $nota->contenido }}</div>
                <div style="color: var(--t-tenue); font-size: 0.78rem; margin-top: 8px;">{{ $nota->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <form method="POST" action="/campanyas/{{ $campana->id }}/notas/{{ $nota->id }}" onsubmit="return confirm('¿Eliminar esta nota?')" style="margin-left: 10px;" onclick="event.stopPropagation()">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-peligro btn-sm">🗑</button>
            </form>
        </div>
    </div>
    @empty
    <div style="color: var(--t-tenue); font-size: 0.9rem; padding: 1rem 0;">No hay notas aún.</div>
    @endforelse
</section>

@else
{{-- ============================================================
     VISTA JUGADOR
     ============================================================ --}}

@php
    $miPersonaje = isset($personajesPorUsuario[Auth::id()]) ? $personajesPorUsuario[Auth::id()]->first() : null;
    $companeros = $campana->usuarios->where('id', '!=', Auth::id());
    $notasVisibles = $campana->notas->where('visible_jugadores', true);
    $enemigosVisibles = $campana->enemigos->filter(fn($e) => $e->pivot->visible_jugadores);
@endphp

{{-- 1. MI PERSONAJE --}}
<section style="margin-bottom: 2rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'sword']) Tu personaje</div>
    @if($miPersonaje)
    <div class="tarjeta" style="display: flex; gap: 20px; align-items: center; border-left: 4px solid var(--c-rojo); flex-wrap: wrap;">
        <img src="{{ $miPersonaje->avatar_url }}" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--c-rojo); box-shadow: 0 0 20px rgba(179,3,3,0.3); flex-shrink: 0; cursor: pointer;" onclick="verPersonaje({{ $miPersonaje->id }})">
        <div style="flex: 1; min-width: 200px;">
            <div style="font-size: 1.3rem; font-weight: bold; font-family: var(--f-titulo); margin-bottom: 4px;">{{ $miPersonaje->nombre }}</div>
            <div style="color: var(--c-naranja); font-style: italic; margin-bottom: 10px;">
                {{ $miPersonaje->raza->nombre ?? '—' }} · {{ $miPersonaje->clase->nombre ?? '—' }}
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                <span style="background: var(--c-rojo); color: white; padding: 3px 12px; border-radius: 20px; font-size: 0.8rem; font-family: var(--f-titulo);">Nivel {{ $miPersonaje->nivel }}</span>
            </div>
        </div>
        @php 
            $pivotPersonaje = $campana->personajes->where('id', $miPersonaje->id)->first();
            $historiaVisible = $pivotPersonaje?->pivot->historia_visible ?? false; 
        @endphp
        <form method="POST" action="/campanyas/{{ $campana->id }}/personaje/historia-visible" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-secundario btn-sm" style="border-color: {{ $historiaVisible ? 'var(--c-verde)' : 'var(--b-neutro)' }}; color: {{ $historiaVisible ? 'var(--c-verde-claro)' : 'var(--t-secundario)' }};">
                {!! $historiaVisible ? view('partials.icon', ['name' => 'eye', 'class' => 'icon-sm'])->render().' Historia visible' : view('partials.icon', ['name' => 'lock', 'class' => 'icon-sm'])->render().' Historia privada' !!}
            </button>
        </form>
        <button onclick="verPersonaje({{ $miPersonaje->id }})" class="btn btn-secundario btn-sm">Ver ficha completa →</button>
    </div>
    @else
    <div class="tarjeta" style="border: 2px dashed var(--b-sutil);">
        <p style="color: var(--t-tenue); margin-bottom: 10px;">Aún no tienes un personaje asignado a esta campaña.</p>
        @if($misPersonajes->count() > 0)
        <form method="POST" action="/campanyas/{{ $campana->id }}/personaje" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
            @csrf
            <select name="personaje_id" style="flex: 1; min-width: 180px; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 10px 12px; color: white; font-family: var(--f-cuerpo);">
                @foreach($misPersonajes as $p)
                <option value="{{ $p->id }}">{{ $p->nombre }} (Nv. {{ $p->nivel }})</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primario">Unir personaje</button>
        </form>
        @else
        <p style="color: var(--t-tenue); font-size: 0.85rem;">No tienes personajes creados.</p>
        @endif
    </div>
    @endif
</section>

{{-- 2. COMPAÑEROS --}}
<section style="margin-bottom: 2rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'user']) Compañeros de aventura</div>
    <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-start;">

        {{-- DM --}}
        <div style="text-align: center; min-width: 70px;">
            @if($campana->dungeonMaster->avatar)
            <img src="{{ str_starts_with($campana->dungeonMaster->avatar, 'http') ? $campana->dungeonMaster->avatar : Storage::url($campana->dungeonMaster->avatar) }}"
                style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; margin: 0 auto 6px; border: 2px solid rgba(179,3,3,0.5); box-shadow: 0 0 14px rgba(179,3,3,0.25); cursor: zoom-in; display: block;"
                onclick="document.getElementById('lightbox-campana-img').src=this.src; document.getElementById('lightbox-campana').style.display='flex';">
            @else
            <div style="width: 64px; height: 64px; border-radius: 50%; background: var(--c-rojo); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.4rem; margin: 0 auto 6px; border: 2px solid rgba(179,3,3,0.5); box-shadow: 0 0 14px rgba(179,3,3,0.25);">
                {{ strtoupper(substr($campana->dungeonMaster->nombre, 0, 1)) }}
            </div>
            @endif
            <div style="font-size: 0.82rem; font-weight: bold; color: var(--t-principal);">{{ $campana->dungeonMaster->nombre }}</div>
            <div style="font-size: 0.68rem; color: var(--c-rojo-claro); font-family: var(--f-titulo); letter-spacing: 1px; margin-top: 2px;">DM</div>
        </div>

        {{-- Separador --}}
        @if($companeros->count() > 0)
        <div style="width: 1px; background: var(--b-neutro); align-self: stretch; margin: 0 4px;"></div>
        @endif

        {{-- Jugadores --}}
        @foreach($companeros as $companero)
        @php $pComp = isset($personajesPorUsuario[$companero->id]) ? $personajesPorUsuario[$companero->id]->first() : null; @endphp
        <div style="text-align: center; min-width: 70px; cursor: {{ $pComp ? 'pointer' : 'default' }};" @if($pComp) onclick="verPersonaje({{ $pComp->id }})" @endif>
            <div style="position: relative; width: 64px; margin: 0 auto 6px;">
                <img src="{{ $pComp ? $pComp->avatar_url : 'https://ui-avatars.com/api/?name='.urlencode($companero->nombre).'&background=5a6b3f&color=fff&size=64' }}"
                    style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; border: 2px solid var(--c-verde); box-shadow: 0 0 10px rgba(90,107,63,0.2);">
            </div>
            <div style="font-size: 0.82rem; font-weight: bold; color: var(--t-principal);">{{ $companero->nombre }}</div>
            @if($pComp)
            <div style="font-size: 0.72rem; color: var(--c-naranja); margin-top: 2px;">{{ $pComp->nombre }}</div>
            <div style="font-size: 0.68rem; color: var(--t-tenue);">{{ $pComp->clase->nombre ?? '' }} Nv.{{ $pComp->nivel }}</div>
            @else
            <div style="font-size: 0.7rem; color: var(--t-tenue); margin-top: 2px;">Sin personaje</div>
            @endif
        </div>
        @endforeach

        @if($companeros->count() === 0)
        <div style="color: var(--t-tenue); font-size: 0.9rem; align-self: center;">Aún no hay más jugadores en esta campaña.</div>
        @endif
    </div>
</section>

{{-- 3. NOTAS DEL DM --}}
@if($notasVisibles->count() > 0)
<section style="margin-bottom: 2rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Notas del Dungeon Master</div>
    @foreach($notasVisibles->sortByDesc('created_at') as $nota)
    <div style="background: rgba(0,0,0,0.25); border-left: 4px solid var(--c-oro); border-radius: 0 10px 10px 0; padding: 18px 22px; margin-bottom: 14px;">
        <div style="font-family: var(--f-titulo); font-size: 0.9rem; color: var(--c-oro); margin-bottom: 8px; letter-spacing: 1px;">{{ $nota->titulo }}</div>
        <div style="color: #b8c0c8; line-height: 1.8; font-family: var(--f-cuerpo); font-style: italic; white-space: pre-wrap;">{{ $nota->contenido }}</div>
        <div style="color: var(--t-tenue); font-size: 0.75rem; margin-top: 10px;">{{ $nota->created_at->format('d/m/Y') }}</div>
    </div>
    @endforeach
</section>
@endif

{{-- 4. SESIONES --}}
@if($campana->sesiones->count() > 0)
<section style="margin-bottom: 2rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'scroll']) Crónicas de la campaña</div>
    @foreach($campana->sesiones->sortByDesc('numero_sesion') as $sesion)
    <div class="tarjeta" style="margin-bottom: 12px; border-left: 3px solid var(--b-medio);">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px; flex-wrap: wrap;">
            <span style="background: rgba(179,3,3,0.15); color: var(--c-rojo-claro); border: 1px solid var(--b-sutil); padding: 2px 10px; border-radius: 20px; font-size: 0.75rem; font-family: var(--f-titulo); white-space: nowrap; flex-shrink: 0;">Sesión {{ $sesion->numero_sesion }}</span>
            <div style="font-weight: bold; font-size: 1rem;">{{ $sesion->titulo }}</div>
            @if($sesion->fecha_sesion)
            <div style="color: var(--t-tenue); font-size: 0.8rem; margin-left: auto;">{{ \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') }}</div>
            @endif
        </div>
        @if($sesion->resumen)
        <div style="color: var(--t-secundario); font-size: 0.92rem; line-height: 1.75; font-family: var(--f-cuerpo); font-style: italic;">{{ $sesion->resumen }}</div>
        @endif
        @if($sesion->duracion_minutos)
        <div style="color: var(--t-tenue); font-size: 0.78rem; margin-top: 8px;">⏱ {{ $sesion->duracion_minutos }} min</div>
        @endif
    </div>
    @endforeach
</section>
@endif

{{-- 5. ENEMIGOS VISIBLES --}}
@if($enemigosVisibles->count() > 0)
<section style="margin-bottom: 2rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'skull']) Amenazas conocidas</div>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(155px, 1fr)); gap: 12px;">
        @foreach($enemigosVisibles as $enemigo)
        <div class="tarjeta" style="padding: 0; overflow: hidden; cursor: {{ $enemigo->imagen ? 'zoom-in' : 'default' }};"
            @if($enemigo->imagen)
            onclick="document.getElementById('lightbox-campana-img').src='{{ Storage::url($enemigo->imagen) }}'; document.getElementById('lightbox-campana').style.display='flex';"
            @endif>
            @if($enemigo->imagen)
            <img src="{{ Storage::url($enemigo->imagen) }}" style="width: 100%; height: 115px; object-fit: cover; display: block;">
            @else
            <div style="width: 100%; height: 115px; background: rgba(179,3,3,0.08); display: flex; align-items: center; justify-content: center; font-size: 2.5rem;">💀</div>
            @endif
            <div style="padding: 10px 12px;">
                <div style="font-weight: bold; font-size: 0.88rem; display: flex; align-items: center; gap: 4px; flex-wrap: wrap;">
                    @if($enemigo->es_boss)
                    <span style="background: rgba(179,3,3,0.2); color: #d44141; border: 1px solid rgba(179,3,3,0.4); padding: 1px 5px; border-radius: 3px; font-size: 0.62rem; font-family: var(--f-titulo); letter-spacing: 1px;">BOSS</span>
                    @endif
                    {{ $enemigo->nombre }}
                </div>
                <div style="color: var(--t-tenue); font-size: 0.75rem; margin-top: 2px;">{{ $enemigo->tipo }} · CR {{ $enemigo->clase_de_desafio }}</div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

@endif
{{-- FIN VISTA JUGADOR --}}


{{-- ============================================================
     MODALES DM
     ============================================================ --}}
@if($esDM)

<!-- MODAL EDITAR CAMPAÑA -->
<div id="modal-editar-campana" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: var(--c-fondo-alt); border: 1px solid var(--b-medio); border-radius: 12px; padding: 30px; width: 100%; max-width: 600px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">Editar Campaña</h2>
            <button onclick="document.getElementById('modal-editar-campana').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>
        <form method="POST" action="/campanyas/{{ $campana->id }}">
            @csrf @method('PATCH')
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Nombre *</label>
                    <input type="text" name="nombre" value="{{ $campana->nombre }}" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Descripción</label>
                    <textarea name="descripcion" rows="3" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo); resize: vertical;">{{ $campana->descripcion }}</textarea>
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Ambientación</label>
                    <input type="text" name="ambientacion" value="{{ $campana->ambientacion }}" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Nivel inicial *</label>
                        <input type="number" name="nivel_inicial" value="{{ $campana->nivel_inicial }}" min="1" max="20" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Nivel máximo</label>
                        <input type="number" name="nivel_maximo" value="{{ $campana->nivel_maximo }}" min="1" max="20" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Estado *</label>
                        <select name="estado" required style="width: 100%; background: var(--c-fondo-alt); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                            <option value="activa" {{ $campana->estado == 'activa' ? 'selected' : '' }}>Activa</option>
                            <option value="pausada" {{ $campana->estado == 'pausada' ? 'selected' : '' }}>Pausada</option>
                            <option value="finalizada" {{ $campana->estado == 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" onclick="document.getElementById('modal-editar-campana').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL AÑADIR SESIÓN -->
<div id="modal-sesion" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: var(--c-fondo-alt); border: 1px solid var(--b-medio); border-radius: 12px; padding: 30px; width: 100%; max-width: 600px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">Nueva Sesión</h2>
            <button onclick="document.getElementById('modal-sesion').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>
        <form method="POST" action="/campanyas/{{ $campana->id }}/sesiones">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Número *</label>
                        <input type="number" name="numero_sesion" value="{{ $campana->sesiones->count() + 1 }}" min="1" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Fecha</label>
                        <input type="date" name="fecha_sesion" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Título *</label>
                    <input type="text" name="titulo" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Resumen</label>
                    <textarea name="resumen" rows="4" placeholder="¿Qué pasó en esta sesión?..." style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo); resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Duración (minutos)</label>
                    <input type="number" name="duracion_minutos" min="0" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="document.getElementById('modal-sesion').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Guardar Sesión</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR SESIÓN -->
<div id="modal-editar-sesion" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: var(--c-fondo-alt); border: 1px solid var(--b-medio); border-radius: 12px; padding: 30px; width: 100%; max-width: 600px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">Editar Sesión</h2>
            <button onclick="document.getElementById('modal-editar-sesion').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>
        <form id="form-editar-sesion" method="POST">
            @csrf @method('PATCH')
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Número *</label>
                        <input type="number" name="numero_sesion" id="edit-numero" min="1" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Fecha</label>
                        <input type="date" name="fecha_sesion" id="edit-fecha" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Título *</label>
                    <input type="text" name="titulo" id="edit-titulo" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Resumen</label>
                    <textarea name="resumen" id="edit-resumen" rows="4" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo); resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Duración (minutos)</label>
                    <input type="number" name="duracion_minutos" id="edit-duracion" min="0" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="document.getElementById('modal-editar-sesion').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL AÑADIR NOTA -->
<div id="modal-nota" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: var(--c-fondo-alt); border: 1px solid var(--b-medio); border-radius: 12px; padding: 30px; width: 100%; max-width: 600px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">Nueva Nota</h2>
            <button onclick="document.getElementById('modal-nota').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>
        <form method="POST" action="/campanyas/{{ $campana->id }}/notas">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Título *</label>
                    <input type="text" name="titulo" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Contenido *</label>
                    <textarea name="contenido" rows="6" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo); resize: vertical;"></textarea>
                </div>
                <label style="display: flex; align-items: center; gap: 8px; color: var(--t-secundario); font-size: 0.85rem; cursor: pointer;">
                    <input type="checkbox" name="visible_jugadores" checked style="accent-color: var(--c-rojo); width: 16px; height: 16px;">
                    Visible para los jugadores
                </label>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="document.getElementById('modal-nota').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Guardar Nota</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL EDITAR NOTA -->
<div id="modal-editar-nota" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: var(--c-fondo-alt); border: 1px solid var(--b-medio); border-radius: 12px; padding: 30px; width: 100%; max-width: 600px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">Editar Nota</h2>
            <button onclick="document.getElementById('modal-editar-nota').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>
        <form id="form-editar-nota" method="POST">
            @csrf @method('PATCH')
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Título *</label>
                    <input type="text" name="titulo" id="edit-nota-titulo" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Contenido *</label>
                    <textarea name="contenido" id="edit-nota-contenido" rows="6" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo); resize: vertical;"></textarea>
                </div>
                <label style="display: flex; align-items: center; gap: 8px; color: var(--t-secundario); font-size: 0.85rem; cursor: pointer;">
                    <input type="checkbox" name="visible_jugadores" id="edit-nota-visible" style="accent-color: var(--c-rojo); width: 16px; height: 16px;">
                    Visible para los jugadores
                </label>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" onclick="document.getElementById('modal-editar-nota').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function abrirModalEditar() {
        document.getElementById('modal-editar-campana').scrollTop = 0;
        document.getElementById('modal-editar-campana').style.display = 'flex';
    }
    function abrirModalSesion() {
        document.getElementById('modal-sesion').scrollTop = 0;
        document.getElementById('modal-sesion').style.display = 'flex';
    }
    function editarSesion(id, titulo, numero, fecha, resumen, duracion) {
        document.getElementById('form-editar-sesion').action = `/campanyas/{{ $campana->id }}/sesiones/${id}`;
        document.getElementById('edit-titulo').value = titulo;
        document.getElementById('edit-numero').value = numero;
        document.getElementById('edit-fecha').value = fecha ? fecha.substring(0, 10) : '';
        document.getElementById('edit-resumen').value = resumen;
        document.getElementById('edit-duracion').value = duracion || '';
        document.getElementById('modal-editar-sesion').scrollTop = 0;
        document.getElementById('modal-editar-sesion').style.display = 'flex';
    }
    function abrirModalNota() {
        document.getElementById('modal-nota').scrollTop = 0;
        document.getElementById('modal-nota').style.display = 'flex';
    }
    function editarNotaDesdeCard(el) {
        const id = el.dataset.notaId;
        const titulo = el.dataset.notaTitulo;
        const contenido = el.dataset.notaContenido;
        const visible = el.dataset.notaVisible === 'true';
        document.getElementById('form-editar-nota').action = `/campanyas/{{ $campana->id }}/notas/${id}`;
        document.getElementById('edit-nota-titulo').value = titulo;
        document.getElementById('edit-nota-contenido').value = contenido;
        document.getElementById('edit-nota-visible').checked = visible;
        document.getElementById('modal-editar-nota').scrollTop = 0;
        document.getElementById('modal-editar-nota').style.display = 'flex';
    }
</script>

@endif
{{-- FIN MODALES DM --}}


{{-- ============================================================
     MODAL VER PERSONAJE (común para DM y jugador)
     ============================================================ --}}
<div id="modal-personaje" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: linear-gradient(150deg, rgba(18,8,4,0.97), rgba(36,16,8,0.99)); border: 1px solid rgba(179,3,3,0.3); border-radius: 14px; width: 100%; max-width: 900px; margin: auto; overflow: hidden;">
        <div id="modal-personaje-header" style="display: flex; align-items: center; gap: 20px; background: rgba(0,0,0,0.5); border-bottom: 2px solid var(--c-rojo); padding: 25px;">
            <img id="modal-p-avatar" src="" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--c-rojo); box-shadow: 0 0 20px rgba(179,3,3,0.3); flex-shrink: 0;">
            <div>
                <h2 id="modal-p-nombre" style="font-size: 1.6rem; letter-spacing: 2px; margin-bottom: 4px;"></h2>
                <div id="modal-p-subtitulo" style="color: var(--c-naranja); font-style: italic; margin-bottom: 8px;"></div>
                <div id="modal-p-badges" style="display: flex; gap: 8px; flex-wrap: wrap;"></div>
            </div>
            <button onclick="document.getElementById('modal-personaje').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer; margin-left: auto; align-self: flex-start;">✕</button>
        </div>
        <div style="padding: 25px;">
            <div style="margin-bottom: 25px;">
                <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">🎯 Características</div>
                <div id="modal-p-stats" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px;"></div>
            </div>
            <div style="margin-bottom: 25px;">
                <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">⚔️ Combate</div>
                <div id="modal-p-combate" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px;"></div>
            </div>
            <div id="modal-p-historia-sec" style="margin-bottom: 25px; display: none;">
                <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">📖 Historia</div>
                <div id="modal-p-historia" style="background: rgba(0,0,0,0.25); border-left: 4px solid var(--c-rojo); border-radius: 0 8px 8px 0; padding: 15px 20px; color: #b8c0c8; line-height: 1.75; font-style: italic;"></div>
            </div>
            <div id="modal-p-personalidad-sec" style="margin-bottom: 25px; display: none;">
                <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">💭 Personalidad</div>
                <div id="modal-p-personalidad" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;"></div>
            </div>
            <div id="modal-p-equipo-sec" style="margin-bottom: 25px; display: none;">
                <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">🎒 Equipo</div>
                <div id="modal-p-equipo" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;"></div>
            </div>
        </div>
    </div>
</div>

<script>
function verPersonaje(id) {
    document.getElementById('modal-personaje').scrollTop = 0;
    document.getElementById('modal-personaje').style.display = 'flex';
    document.getElementById('modal-p-nombre').textContent = 'Cargando...';

    fetch(`/personajes/${id}/json?campana_id={{ $campana->id }}`)
        .then(r => r.json())
        .then(p => {
            document.getElementById('modal-p-avatar').src = p.avatar_url;
            document.getElementById('modal-p-avatar').style.cursor = 'zoom-in';
            document.getElementById('modal-p-avatar').onclick = (e) => {
                e.stopPropagation();
                document.getElementById('lightbox-personaje-img').src = p.avatar_url;
                document.getElementById('lightbox-personaje').style.display = 'flex';
            };
            document.getElementById('modal-p-nombre').textContent = p.nombre;
            document.getElementById('modal-p-subtitulo').textContent = [p.raza, p.clase, p.subclase].filter(Boolean).join(' · ');
            document.getElementById('modal-p-badges').innerHTML = `
                <span style="background: var(--c-rojo); color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem;">Nivel ${p.nivel}</span>
                ${p.alineamiento ? `<span style="background: rgba(255,255,255,0.05); color: var(--t-secundario); border: 1px solid rgba(118,133,150,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.78rem;">${p.alineamiento}</span>` : ''}
                ${p.trasfondo ? `<span style="background: rgba(255,255,255,0.05); color: var(--t-secundario); border: 1px solid rgba(118,133,150,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.78rem;">${p.trasfondo}</span>` : ''}
            `;
            const statLabels = ['FUE','DES','CON','INT','SAB','CAR'];
            const statVals = [p.fuerza, p.destreza, p.constitucion, p.inteligencia, p.sabiduria, p.carisma];
            document.getElementById('modal-p-stats').innerHTML = statLabels.map((s, i) => {
                const mod = Math.floor((statVals[i] - 10) / 2);
                return `<div style="background: rgba(0,0,0,0.35); border-radius: 10px; padding: 12px 8px; text-align: center; border: 1px solid rgba(118,133,150,0.1);">
                    <span style="color: var(--t-secundario); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; display: block;">${s}</span>
                    <span style="font-size: 2rem; font-weight: 700; color: white; display: block; line-height: 1.2;">${statVals[i]}</span>
                    <span style="color: var(--c-naranja); font-size: 0.9rem; font-weight: 600;">${mod >= 0 ? '+' : ''}${mod}</span>
                </div>`;
            }).join('');
            const combate = [
                { label: '❤️ HP', valor: `${p.pg_actuales ?? '?'}/${p.pg_maximos ?? '?'}` },
                { label: '🛡️ CA', valor: p.clase_de_armadura ?? '—' },
                { label: '⚡ Velocidad', valor: `${p.velocidad ?? 30} ft` },
                { label: '🎯 PB', valor: `+${p.bonus_competencia ?? 2}` },
                { label: '⚔️ Iniciativa', valor: p.iniciativa !== null ? (p.iniciativa >= 0 ? '+' : '') + p.iniciativa : '—' },
            ];
            document.getElementById('modal-p-combate').innerHTML = combate.map(c => `
                <div style="background: rgba(0,0,0,0.3); padding: 12px; border-radius: 8px; border-left: 3px solid var(--c-rojo);">
                    <span style="color: var(--t-secundario); font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.5px; display: block;">${c.label}</span>
                    <span style="color: white; font-size: 1.3rem; font-weight: 700;">${c.valor}</span>
                </div>
            `).join('');
            if (p.historia) {
                document.getElementById('modal-p-historia-sec').style.display = 'block';
                document.getElementById('modal-p-historia').textContent = p.historia;
            } else {
                document.getElementById('modal-p-historia-sec').style.display = 'none';
            }
            const rasgos = [
                { label: 'Rasgos', valor: p.rasgos_personalidad },
                { label: 'Ideales', valor: p.ideales },
                { label: 'Vínculos', valor: p.vinculos },
                { label: 'Defectos', valor: p.defectos },
            ].filter(r => r.valor);
            if (rasgos.length > 0) {
                document.getElementById('modal-p-personalidad-sec').style.display = 'block';
                document.getElementById('modal-p-personalidad').innerHTML = rasgos.map(r => `
                    <div style="background: rgba(0,0,0,0.2); border-radius: 8px; padding: 12px 15px; border: 1px solid rgba(118,133,150,0.1);">
                        <span style="color: var(--c-naranja); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;">${r.label}</span>
                        <span style="color: #a8b0b8; font-size: 0.9rem; line-height: 1.6;">${r.valor}</span>
                    </div>
                `).join('');
            } else {
                document.getElementById('modal-p-personalidad-sec').style.display = 'none';
            }
            if (p.equipo && p.equipo.length > 0) {
                document.getElementById('modal-p-equipo-sec').style.display = 'block';
                document.getElementById('modal-p-equipo').innerHTML = p.equipo.map(e => `
                    <div style="background: rgba(0,0,0,0.3); padding: 10px 12px; border-radius: 8px; border-left: 3px solid var(--c-naranja);">
                        <span style="color: white; font-weight: 600; font-size: 0.95rem;">${e.nombre}${e.equipado ? ' <span style="color: #9ab090; font-size: 0.75rem;">✓</span>' : ''}</span>
                        <div style="color: var(--t-secundario); font-size: 0.82rem;">${e.tipo}${e.cantidad > 1 ? ' ×' + e.cantidad : ''}</div>
                    </div>
                `).join('');
            } else {
                document.getElementById('modal-p-equipo-sec').style.display = 'none';
            }
        });
}
</script>

<div id="lightbox-personaje" onclick="this.style.display='none'" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:2000; align-items:center; justify-content:center; cursor:zoom-out;">
    <img id="lightbox-personaje-img" src="" style="max-width: 90vw; max-height: 90vh; object-fit: contain; border-radius: 8px; border: 2px solid rgba(179,3,3,0.3);">
</div>

<div id="lightbox-campana" onclick="this.style.display='none'" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:2000; align-items:center; justify-content:center; cursor:zoom-out;">
    <img id="lightbox-campana-img" src="" style="max-width: 90vw; max-height: 90vh; object-fit: contain; border-radius: 8px; border: 2px solid rgba(179,3,3,0.3);">
</div>

@endsection