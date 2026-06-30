@extends('layouts.app')

@section('titulo', 'Campañas')

@section('contenido')

@if (session('success'))
    <div class="alerta alerta-exito">{{ session('success') }}</div>
@endif

@if ($errors->any())
    <div class="alerta alerta-error">{{ $errors->first() }}</div>
@endif

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
    <p style="color: var(--t-secundario);">{{ $campanasDM->count() }} campañas como Dungeon Master</p>
    <button onclick="abrirModalCrear()" class="btn btn-primario">+ Crear Campaña</button>
</div>

<!-- TABS DE ESTADO -->
@php
    $activas = $campanasDM->where('estado', 'activa');
    $pausadas = $campanasDM->where('estado', 'pausada');
    $finalizadas = $campanasDM->where('estado', 'finalizada');
@endphp
@if($campanasDM->count() > 0)
<section style="margin-bottom: 2.5rem;">
    <div style="display: flex; gap: 0.5rem; margin-bottom: 1.5rem; border-bottom: 1px solid var(--b-neutro);">
        <button onclick="cambiarTabCampana('activas')" id="tab-activas" class="tab-campana" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: none; border: none; border-bottom: 2px solid var(--c-rojo); color: white; font-family: var(--f-titulo); font-size: 0.9rem; letter-spacing: 1px; cursor: pointer; text-transform: uppercase;">
            @include('partials.icon', ['name' => 'swords', 'class' => 'icon-sm']) Activas ({{ $activas->count() }})
        </button>
        @if($pausadas->count() > 0)
        <button onclick="cambiarTabCampana('pausadas')" id="tab-pausadas" class="tab-campana" style="padding: 10px 20px; background: none; border: none; border-bottom: 2px solid transparent; color: var(--t-secundario); font-family: var(--f-titulo); font-size: 0.9rem; letter-spacing: 1px; cursor: pointer; text-transform: uppercase;">
            ⏸ Pausadas ({{ $pausadas->count() }})
        </button>
        @endif
        @if($finalizadas->count() > 0)
        <button onclick="cambiarTabCampana('finalizadas')" id="tab-finalizadas" class="tab-campana" style="padding: 10px 20px; background: none; border: none; border-bottom: 2px solid transparent; color: var(--t-secundario); font-family: var(--f-titulo); font-size: 0.9rem; letter-spacing: 1px; cursor: pointer; text-transform: uppercase;">
            ✓ Finalizadas ({{ $finalizadas->count() }})
        </button>
        @endif
    </div>

    <div id="panel-activas" class="panel-campana" style="display: flex; flex-direction: column; gap: 1rem;">
        @forelse($activas as $campana)
        <div class="tarjeta" style="border-left: 4px solid var(--c-rojo); cursor: pointer;" onclick="window.location='/campanyas/{{ $campana->id }}'">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <div style="font-size: 1.15rem; font-weight: bold; margin-bottom: 4px;">{{ $campana->nombre }}</div>
                    <div style="color: var(--t-secundario); font-size: 0.85rem; margin-bottom: 8px;">
                        {{ $campana->ambientacion ?? 'Sin ambientación' }} · Nivel {{ $campana->nivel_inicial }}{{ $campana->nivel_maximo ? '-'.$campana->nivel_maximo : '+' }} · {{ $campana->sesiones_count }} sesiones
                    </div>
                    @if($campana->descripcion)
                    <div style="color: var(--t-tenue); font-size: 0.9rem;">{{ Str::limit($campana->descripcion, 120) }}</div>
                    @endif
                    <div style="margin-top: 10px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: rgba(179,3,3,0.1); color: var(--c-rojo-claro); border: 1px solid var(--b-medio); padding: 2px 10px; border-radius: 20px; font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">ACTIVA</span>
                        <span style="color: var(--t-tenue); font-size: 0.8rem;">Código: <strong style="color: var(--c-oro); letter-spacing: 2px;">{{ $campana->codigo_invitacion }}</strong></span>
                    </div>
                </div>
                <div style="display: flex; gap: 8px; margin-left: 15px;">
                    <form method="POST" action="/campanyas/{{ $campana->id }}" onsubmit="return confirm('¿Eliminar esta campaña?')" onclick="event.stopPropagation()">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-peligro btn-sm">🗑</button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <p style="color: var(--t-tenue);">No tienes campañas activas.</p>
        @endforelse
    </div>

    <div id="panel-pausadas" class="panel-campana" style="display: none; flex-direction: column; gap: 1rem;">
        @foreach($pausadas as $campana)
        <div class="tarjeta" style="border-left: 4px solid var(--t-secundario); cursor: pointer; opacity: 0.85;" onclick="window.location='/campanyas/{{ $campana->id }}'">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div style="font-size: 1.1rem; font-weight: bold;">{{ $campana->nombre }}</div>
                    <div style="color: var(--t-secundario); font-size: 0.85rem; margin-top: 4px;">{{ $campana->sesiones_count }} sesiones · Nivel {{ $campana->nivel_inicial }}</div>
                </div>
                <div style="display: flex; gap: 8px;" onclick="event.stopPropagation()">
                    <form method="POST" action="/campanyas/{{ $campana->id }}" onsubmit="return confirm('¿Eliminar esta campaña?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-peligro btn-sm">🗑</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div id="panel-finalizadas" class="panel-campana" style="display: none; flex-direction: column; gap: 1rem;">
        @foreach($finalizadas as $campana)
        <div class="tarjeta" style="opacity: 0.6; cursor: pointer;" onclick="window.location='/campanyas/{{ $campana->id }}'">
            <div style="font-weight: bold;">{{ $campana->nombre }}</div>
            <div style="color: var(--t-secundario); font-size: 0.85rem;">{{ $campana->sesiones_count }} sesiones</div>
        </div>
        @endforeach
    </div>
</section>
@else
<div class="vacio">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 60px; height: 60px; stroke: var(--c-rojo); fill: none; stroke-width: 1.5; opacity: 0.5; margin: 0 auto 1rem; display: block;">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/>
    </svg>
    <h3>No tienes campañas aún</h3>
    <p>¡Crea tu primera campaña y empieza la aventura!</p>
    <button onclick="abrirModalCrear()" class="btn btn-primario">+ Crear primera campaña</button>
</div>
@endif

<!-- CAMPAÑAS COMO JUGADOR -->
@if($campanasJugador->count() > 0)
<section style="margin-top: 2.5rem; margin-bottom: 2.5rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'dice']) Campañas en las que juegas</div>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @foreach($campanasJugador as $campana)
        <div class="tarjeta" style="border-left: 4px solid var(--c-naranja); cursor: pointer;" onclick="window.location='/campanyas/{{ $campana->id }}'">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <div style="font-size: 1.15rem; font-weight: bold; margin-bottom: 4px;">{{ $campana->nombre }}</div>
                    <div style="color: var(--t-secundario); font-size: 0.85rem; margin-bottom: 8px;">
                        DM: {{ $campana->dungeonMaster->nombre }} · {{ $campana->ambientacion ?? 'Sin ambientación' }} · {{ $campana->sesiones_count }} sesiones
                    </div>
                    @if($campana->descripcion)
                    <div style="color: var(--t-tenue); font-size: 0.9rem;">{{ Str::limit($campana->descripcion, 120) }}</div>
                    @endif
                    <div style="margin-top: 10px;">
                        @if($campana->estado === 'activa')
                        <span style="background: rgba(179,3,3,0.1); color: var(--c-rojo-claro); border: 1px solid var(--b-medio); padding: 2px 10px; border-radius: 20px; font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">ACTIVA</span>
                        @elseif($campana->estado === 'pausada')
                        <span style="background: rgba(118,133,150,0.1); color: var(--t-secundario); border: 1px solid var(--b-neutro); padding: 2px 10px; border-radius: 20px; font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">PAUSADA</span>
                        @else
                        <span style="background: rgba(64,72,52,0.2); color: var(--c-verde-claro); border: 1px solid var(--c-verde); padding: 2px 10px; border-radius: 20px; font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">FINALIZADA</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- UNIRSE A CAMPAÑA -->
<section style="margin-top: 2rem;">
    <div class="seccion-titulo">@include('partials.icon', ['name' => 'lock']) Unirse a una campaña</div>
    <div class="tarjeta" style="max-width: 500px;">
        <form method="POST" action="/unirse-campana" style="display: flex; flex-direction: column; gap: 12px;">
            @csrf
            <div style="display: flex; gap: 10px; align-items: flex-start;">
                <div style="flex: 1;">
                    <input type="text" name="codigo_invitacion" placeholder="Código de invitación..." maxlength="6" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 3px;">
                    @error('codigo_invitacion')
                        <span style="color: var(--c-rojo-claro); font-size: 0.8rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            @if($misPersonajes->count() > 0)
            <div>
                <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Personaje con el que juegas</label>
                <select name="personaje_id" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    <option value="">Sin personaje por ahora</option>
                    @foreach($misPersonajes as $personaje)
                    <option value="{{ $personaje->id }}">{{ $personaje->nombre }} (Nivel {{ $personaje->nivel }})</option>
                    @endforeach
                </select>
            </div>
            @else
            <p style="color: var(--t-tenue); font-size: 0.85rem;">No tienes personajes activos. Puedes unirte sin personaje y añadirlo después.</p>
            @endif
            <div style="display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-primario">Unirse →</button>
            </div>
        </form>
    </div>
</section>

<!-- MODAL CREAR CAMPAÑA -->
<div id="modal-crear-campana" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: var(--c-fondo-alt); border: 1px solid var(--b-medio); border-radius: 12px; padding: 30px; width: 100%; max-width: 600px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">Nueva Campaña</h2>
            <button onclick="document.getElementById('modal-crear-campana').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>

        <form method="POST" action="/campanyas">
            @csrf
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Nombre *</label>
                    <input type="text" name="nombre" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Descripción</label>
                    <textarea name="descripcion" rows="3" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo); resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Ambientación</label>
                    <input type="text" name="ambientacion" placeholder="Barovia, Forgotten Realms..." style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Nivel inicial *</label>
                        <input type="number" name="nivel_inicial" value="1" min="1" max="20" required style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Nivel máximo</label>
                        <input type="number" name="nivel_maximo" min="1" max="20" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                    </div>
                    <div>
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Estado *</label>
                        <select name="estado" required style="width: 100%; background: var(--c-fondo-alt); border: 1px solid var(--b-neutro); border-radius: 8px; padding: 12px 15px; color: white; font-family: var(--f-cuerpo);">
                            <option value="activa">Activa</option>
                            <option value="pausada">Pausada</option>
                            <option value="finalizada">Finalizada</option>
                        </select>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" onclick="document.getElementById('modal-crear-campana').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Crear Campaña</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function cambiarTabCampana(tab) {
        document.querySelectorAll('.panel-campana').forEach(p => p.style.display = 'none');
        document.querySelectorAll('.tab-campana').forEach(t => {
            t.style.borderBottomColor = 'transparent';
            t.style.color = 'var(--t-secundario)';
        });

        document.getElementById('panel-' + tab).style.display = 'flex';
        const tabBtn = document.getElementById('tab-' + tab);
        tabBtn.style.borderBottomColor = 'var(--c-rojo)';
        tabBtn.style.color = 'white';
    }
    
    function abrirModalCrear() {
        document.getElementById('modal-crear-campana').scrollTop = 0;
        document.getElementById('modal-crear-campana').style.display = 'flex';
    }
</script>

<style>
    input[name="codigo_invitacion"]::placeholder {
        text-transform: none;
        letter-spacing: normal;
        font-size: 0.85rem;
    }
</style>

@endsection