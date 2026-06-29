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
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1rem;">

            <!-- DM -->
            <div class="tarjeta" style="border-top: 3px solid var(--c-rojo);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                    <div style="width: 42px; height: 42px; border-radius: 50%; background: var(--c-rojo); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.1rem; flex-shrink: 0;">
                        {{ strtoupper(substr($campana->dungeonMaster->nombre, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight: bold; font-size: 0.95rem;">{{ $campana->dungeonMaster->nombre }}</div>
                        <div style="color: var(--c-rojo-claro); font-size: 0.78rem; font-family: var(--f-titulo); letter-spacing: 1px;">DUNGEON MASTER</div>
                    </div>
                </div>
            </div>

            <!-- JUGADORES -->
            @foreach($campana->usuarios as $usuario)
            @php $personajeUsuario = isset($personajesPorUsuario[$usuario->id]) ? $personajesPorUsuario[$usuario->id]->first() : null; @endphp
            <div class="tarjeta" style="border-top: 3px solid var(--c-verde);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
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

                @if($personajeUsuario)
                <!-- PERSONAJE VINCULADO -->
                <div style="background: rgba(0,0,0,0.2); border-radius: 8px; padding: 12px; cursor: pointer;" onclick="verPersonaje({{ $personajeUsuario->id }})">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="{{ $personajeUsuario->avatar_url }}" style="width: 36px; height: 36px; border-radius: 50%; object-fit: cover; border: 2px solid var(--c-rojo);">
                        <div>
                            <div style="font-weight: bold; font-size: 0.9rem;">{{ $personajeUsuario->nombre }}</div>
                            <div style="color: var(--t-secundario); font-size: 0.78rem;">
                                {{ $personajeUsuario->clase->nombre ?? '—' }} · Nivel {{ $personajeUsuario->nivel }}
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <!-- SIN PERSONAJE -->
                @if(Auth::id() === $usuario->id)
                <div style="background: rgba(0,0,0,0.2); border-radius: 8px; padding: 10px;">
                    <p style="color: var(--t-tenue); font-size: 0.82rem; margin-bottom: 8px;">Sin personaje asignado</p>
                    @if($misPersonajes->count() > 0)
                    <form method="POST" action="/campanyas/{{ $campana->id }}/personaje">
                        @csrf
                        <select name="personaje_id" style="width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--b-neutro); border-radius: 6px; padding: 8px; color: white; font-family: var(--f-cuerpo); font-size: 0.85rem; margin-bottom: 8px;">
                            @foreach($misPersonajes as $p)
                            <option value="{{ $p->id }}">{{ $p->nombre }} (Nv. {{ $p->nivel }})</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primario btn-sm" style="width: 100%;">+ Añadir personaje</button>
                    </form>
                    @else
                    <p style="color: var(--t-tenue); font-size: 0.8rem;">No tienes personajes activos.</p>
                    @endif
                </div>
                @else
                <div style="color: var(--t-tenue); font-size: 0.82rem;">Sin personaje asignado</div>
                @endif
                @endif
            </div>
            @endforeach

            @if($campana->usuarios->count() === 0)
            <div style="color: var(--t-tenue); font-size: 0.9rem; grid-column: 1/-1;">Aún no hay jugadores unidos.</div>
            @endif

        </div>
    </section>

</div>

    <!-- NOTAS -->
    <section style="margin-top: 1.5rem;">
        <div class="seccion-titulo" style="display: flex; justify-content: space-between; align-items: center;">
            <span>📋 Notas de la campaña ({{ $campana->notas->count() }})</span>
            @if($esDM)
            <button onclick="abrirModalNota()" class="btn btn-secundario btn-sm">+ Añadir nota</button>
            @endif
        </div>

        @php
            $notasVisibles = $esDM ? $campana->notas : $campana->notas->where('visible_jugadores', true);
        @endphp

        @forelse($notasVisibles->sortByDesc('created_at') as $nota)
        <div class="tarjeta" style="margin-bottom: 10px; border-left: 4px solid var(--c-oro); @if($esDM) cursor: pointer; @endif"
            @if($esDM)
            data-nota-id="{{ $nota->id }}"
            data-nota-titulo="{{ $nota->titulo }}"
            data-nota-contenido="{{ $nota->contenido }}"
            data-nota-visible="{{ $nota->visible_jugadores ? 'true' : 'false' }}"
            onclick="editarNotaDesdeCard(this)"
            @endif>
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="flex: 1;">
                    <div style="font-weight: bold; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                        {{ $nota->titulo }}
                        @if($esDM)
                            @if($nota->visible_jugadores)
                            <span style="color: var(--c-verde-claro); font-size: 0.75rem;">👁 Visible</span>
                            @else
                            <span style="color: var(--t-tenue); font-size: 0.75rem;">🔒 Solo DM</span>
                            @endif
                        @endif
                    </div>
                    <div style="color: #b8c0c8; font-size: 0.9rem; line-height: 1.7; white-space: pre-wrap;">{{ $nota->contenido }}</div>
                    <div style="color: var(--t-tenue); font-size: 0.78rem; margin-top: 8px;">{{ $nota->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @if($esDM)
                <form method="POST" action="/campanyas/{{ $campana->id }}/notas/{{ $nota->id }}" onsubmit="return confirm('¿Eliminar esta nota?')" style="margin-left: 10px;" onclick="event.stopPropagation()">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-peligro btn-sm">🗑</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div style="color: var(--t-tenue); font-size: 0.9rem; padding: 1rem 0;">
            @if($esDM) No hay notas aún. @else El DM no ha publicado notas aún. @endif
        </div>
        @endforelse
    </section>

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

    <!-- MODAL VER PERSONAJE -->
    <div id="modal-personaje" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
        <div style="background: linear-gradient(150deg, rgba(18,8,4,0.97), rgba(36,16,8,0.99)); border: 1px solid rgba(179,3,3,0.3); border-radius: 14px; width: 100%; max-width: 900px; margin: auto; overflow: hidden;">
            
            <!-- CABECERA -->
            <div id="modal-personaje-header" style="display: flex; align-items: center; gap: 20px; background: rgba(0,0,0,0.5); border-bottom: 2px solid var(--c-rojo); padding: 25px;">
                <img id="modal-p-avatar" src="" style="width: 90px; height: 90px; border-radius: 50%; object-fit: cover; border: 3px solid var(--c-rojo); box-shadow: 0 0 20px rgba(179,3,3,0.3); flex-shrink: 0;">
                <div>
                    <h2 id="modal-p-nombre" style="font-size: 1.6rem; letter-spacing: 2px; margin-bottom: 4px;"></h2>
                    <div id="modal-p-subtitulo" style="color: var(--c-naranja); font-style: italic; margin-bottom: 8px;"></div>
                    <div id="modal-p-badges" style="display: flex; gap: 8px; flex-wrap: wrap;"></div>
                </div>
                <button onclick="document.getElementById('modal-personaje').style.display='none'" style="background: none; border: none; color: var(--t-secundario); font-size: 1.5rem; cursor: pointer; margin-left: auto; align-self: flex-start;">✕</button>
            </div>

            <!-- CUERPO -->
            <div style="padding: 25px;">

                <!-- STATS -->
                <div style="margin-bottom: 25px;">
                    <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">🎯 Características</div>
                    <div id="modal-p-stats" style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 8px;"></div>
                </div>

                <!-- COMBATE -->
                <div style="margin-bottom: 25px;">
                    <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">⚔️ Combate</div>
                    <div id="modal-p-combate" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px;"></div>
                </div>

                <!-- HISTORIA -->
                <div id="modal-p-historia-sec" style="margin-bottom: 25px; display: none;">
                    <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">📖 Historia</div>
                    <div id="modal-p-historia" style="background: rgba(0,0,0,0.25); border-left: 4px solid var(--c-rojo); border-radius: 0 8px 8px 0; padding: 15px 20px; color: #b8c0c8; line-height: 1.75; font-style: italic;"></div>
                </div>

                <!-- PERSONALIDAD -->
                <div id="modal-p-personalidad-sec" style="margin-bottom: 25px; display: none;">
                    <div style="color: var(--t-secundario); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; border-bottom: 1px solid rgba(118,133,150,0.12); padding-bottom: 8px; margin-bottom: 15px;">💭 Personalidad</div>
                    <div id="modal-p-personalidad" style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;"></div>
                </div>

                <!-- EQUIPO -->
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

        fetch(`/personajes/${id}/json`)
            .then(r => r.json())
            .then(p => {
                // Cabecera
                document.getElementById('modal-p-avatar').src = p.avatar_url;
                document.getElementById('modal-p-avatar').style.cursor = 'zoom-in';
                document.getElementById('modal-p-avatar').onclick = (e) => {
                    e.stopPropagation();
                    document.getElementById('lightbox-personaje-img').src = p.avatar_url;
                    document.getElementById('lightbox-personaje').style.display = 'flex';
                };
                document.getElementById('modal-p-nombre').textContent = p.nombre;
                document.getElementById('modal-p-subtitulo').textContent = 
                    [p.raza, p.clase, p.subclase].filter(Boolean).join(' · ');
                
                document.getElementById('modal-p-badges').innerHTML = `
                    <span style="background: var(--c-rojo); color: white; padding: 3px 10px; border-radius: 20px; font-size: 0.78rem;">Nivel ${p.nivel}</span>
                    ${p.alineamiento ? `<span style="background: rgba(255,255,255,0.05); color: var(--t-secundario); border: 1px solid rgba(118,133,150,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.78rem;">${p.alineamiento}</span>` : ''}
                    ${p.trasfondo ? `<span style="background: rgba(255,255,255,0.05); color: var(--t-secundario); border: 1px solid rgba(118,133,150,0.2); padding: 3px 10px; border-radius: 20px; font-size: 0.78rem;">${p.trasfondo}</span>` : ''}
                `;

                // Stats
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

                // Combate
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

                // Historia
                if (p.historia) {
                    document.getElementById('modal-p-historia-sec').style.display = 'block';
                    document.getElementById('modal-p-historia').textContent = p.historia;
                } else {
                    document.getElementById('modal-p-historia-sec').style.display = 'none';
                }

                // Personalidad
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

                // Equipo
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

@endsection