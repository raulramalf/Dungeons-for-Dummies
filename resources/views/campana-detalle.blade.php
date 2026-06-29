@extends('layouts.app')

@section('titulo', $campana->nombre)

@section('contenido')

@if (session('success'))
    <div class="alerta alerta-exito">{{ session('success') }}</div>
@endif

<div>

    <!-- CABECERA -->
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
                <button onclick="abrirModalEditar()" class="btn btn-secundario btn-sm">✏️ Editar campaña</button>
                <form method="POST" action="/campanyas/{{ $campana->id }}" onsubmit="return confirm('¿Eliminar esta campaña?')" style="display:inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-peligro btn-sm">🗑 Eliminar</button>
                </form>
            @endif
            <a href="/campanyas" class="btn btn-secundario btn-sm">← Volver</a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">

        <!-- SESIONES -->
        <section>
            <div class="seccion-titulo">📜 Sesiones ({{ $campana->sesiones->count() }})</div>
            @forelse($campana->sesiones->sortByDesc('numero_sesion') as $sesion)
            <div class="tarjeta" style="margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="flex: 1; cursor: pointer;" @if($esDM) onclick="editarSesion({{ $sesion->id }}, '{{ addslashes($sesion->titulo) }}', {{ $sesion->numero_sesion }}, '{{ $sesion->fecha_sesion }}', '{{ addslashes($sesion->resumen ?? '') }}', {{ $sesion->duracion_minutos ?? 0 }})" @endif>
                        <div style="font-weight: bold;">Sesión {{ $sesion->numero_sesion }}: {{ $sesion->titulo }}</div>
                        <div style="color: var(--t-secundario); font-size: 0.85rem;">
                            {{ $sesion->fecha_sesion ? \Carbon\Carbon::parse($sesion->fecha_sesion)->format('d/m/Y') : 'Sin fecha' }}
                            {{ $sesion->duracion_minutos ? '· '.$sesion->duracion_minutos.' min' : '' }}
                        </div>
                        @if($sesion->resumen)
                        <div style="color: var(--t-tenue); font-size: 0.85rem; margin-top: 4px;">{{ Str::limit($sesion->resumen, 80) }}</div>
                        @endif
                    </div>
                    @if($esDM)
                    <form method="POST" action="/campanyas/{{ $campana->id }}/sesiones/{{ $sesion->id }}" onsubmit="return confirm('¿Eliminar esta sesión?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-peligro btn-sm">🗑</button>
                    </form>
                    @endif
                </div>
            </div>
            @empty
            <div style="color: var(--t-tenue); font-size: 0.9rem; padding: 1rem 0;">No hay sesiones registradas aún.</div>
            @endforelse
            @if($esDM)
            <button onclick="abrirModalSesion()" class="btn btn-secundario btn-sm" style="margin-top: 10px;">+ Añadir sesión</button>
            @endif
        </section>

        <!-- ENEMIGOS -->
        <section>
            <div class="seccion-titulo">💀 Enemigos ({{ $campana->enemigos->count() }})</div>
            @forelse($campana->enemigos as $enemigo)
            @if($esDM || $enemigo->pivot->visible_jugadores)
            <div class="tarjeta" style="margin-bottom: 10px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: bold;">{{ $enemigo->nombre }}</div>
                        <div style="color: var(--t-secundario); font-size: 0.85rem;">{{ $enemigo->tipo }} · CR {{ $enemigo->clase_de_desafio }}</div>
                        @if($esDM)
                            @if($enemigo->pivot->visible_jugadores)
                            <span style="color: var(--c-verde-claro); font-size: 0.75rem;">👁 Visible para jugadores</span>
                            @else
                            <span style="color: var(--t-tenue); font-size: 0.75rem;">🔒 Solo DM</span>
                            @endif
                        @endif
                    </div>
                    @if($esDM)
                    <form method="POST" action="/campanyas/{{ $campana->id }}/enemigos/{{ $enemigo->id }}" onsubmit="return confirm('¿Quitar este enemigo?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-peligro btn-sm">✕</button>
                    </form>
                    @endif
                </div>
            </div>
            @endif
            @empty
            <div style="color: var(--t-tenue); font-size: 0.9rem; padding: 1rem 0;">No hay enemigos asignados.</div>
            @endforelse

            @if($esDM && $enemigos->count() > 0)
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
</div>

    <!-- JUGADORES -->
    <section style="margin-top: 1.5rem;">
        <div class="seccion-titulo">👥 Jugadores ({{ $campana->usuarios->count() }})</div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">

            <!-- DM -->
            <div class="tarjeta" style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--c-rojo); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                    {{ strtoupper(substr($campana->dungeonMaster->nombre, 0, 1)) }}
                </div>
                <div>
                    <div style="font-weight: bold; font-size: 0.95rem;">{{ $campana->dungeonMaster->nombre }}</div>
                    <div style="color: var(--c-rojo-claro); font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">DUNGEON MASTER</div>
                </div>
            </div>

            <!-- JUGADORES -->
            @foreach($campana->usuarios as $usuario)
            <div class="tarjeta" style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--c-verde); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                    {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: bold; font-size: 0.95rem;">{{ $usuario->nombre }}</div>
                    <div style="color: var(--t-secundario); font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">JUGADOR</div>
                </div>
                @if($esDM)
                <form method="POST" action="/campanyas/{{ $campana->id }}/usuarios/{{ $usuario->id }}" onsubmit="return confirm('¿Expulsar a {{ $usuario->nombre }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-peligro btn-sm">✕</button>
                </form>
                @endif
            </div>
            @endforeach

            @if($campana->usuarios->count() === 0)
            <div style="color: var(--t-tenue); font-size: 0.9rem; grid-column: 1/-1;">Aún no hay jugadores unidos.</div>
            @endif

        </div>
    </section>

</div>

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
                        <label style="color: var(--t-secundario); font-size: 0.8rem; display: block; margin-bottom: 6px; font-family: var(--f-titulo); text-transform: uppercase; letter-spacing: 1px;">Número de sesión *</label>
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
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
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
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                    <button type="button" onclick="document.getElementById('modal-editar-sesion').style.display='none'" class="btn btn-secundario">Cancelar</button>
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
</script>
@endif

@endsection