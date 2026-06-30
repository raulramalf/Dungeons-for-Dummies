@extends('layouts.app')

@section('titulo', 'Enemigos')

@section('contenido')

@if (session('success'))
    <div style="background: rgba(64,72,52,0.4); border: 1px solid #4caf50; border-radius: 8px; padding: 12px 20px; margin-bottom: 20px; color: #4caf50;">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div style="background: rgba(107,26,26,0.5); border: 1px solid #B30303; border-radius: 8px; padding: 12px 20px; margin-bottom: 20px; color: #ff9999;">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
    <p style="color: var(--color-gris); font-size: 0.9rem;">{{ $enemigos->count() }} enemigos creados</p>
    <button onclick="abrirModalNuevo()" class="btn btn-primario">+ Añadir Enemigo</button>
</div>

<!-- BUSCADOR Y FILTROS -->
<div style="margin-bottom: 25px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
    <input type="text" id="buscador" onkeyup="buscarEnemigo()" placeholder="Buscar enemigo..." style="background: #2a0a18; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem; flex: 1; min-width: 200px; max-width: 400px;">
    <button onclick="ordenarPorCR()" id="btn-orden" class="btn btn-secundario">↑↓ Ordenar por CR</button>
    <button onclick="ordenarPorNombre()" id="btn-nombre" class="btn btn-secundario">↑↓ Ordenar por Nombre</button>
    <button onclick="filtrarBoss()" id="btn-boss" class="btn btn-secundario">⚠️ Solo Boss</button>
</div>

<!-- LISTA -->
<section>
    <div id="lista-enemigos" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 22px;">

        @forelse($enemigos as $enemigo)
        <div class="tarjeta-enemigo" data-cr="{{ $enemigo->clase_de_desafio }}" data-boss="{{ $enemigo->es_boss ? '1' : '0' }}" data-nombre="{{ $enemigo->nombre }}" style="background: #2a0a18; border-radius: 12px; overflow: hidden; cursor: pointer; border: 1px solid rgba(179,3,3,0.18); transition: transform 0.2s, border-color 0.2s;" onmouseover="this.style.transform='translateY(-3px)';this.style.borderColor='rgba(179,3,3,0.5)'" onmouseout="this.style.transform='translateY(0)';this.style.borderColor='rgba(179,3,3,0.18)'" onclick="verEnemigo({{ $enemigo->id }})">

            <!-- IMAGEN PÓSTER -->
            <div style="position: relative; width: 100%; height: 200px; background: linear-gradient(160deg, #3a0d1c, #1a0509);">
                @if($enemigo->imagen)
                <img src="{{ Storage::url($enemigo->imagen) }}" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 3rem; opacity: 0.3;">💀</div>
                @endif

                <!-- Degradado inferior para legibilidad -->
                <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(18,3,9,0.95) 0%, rgba(18,3,9,0.3) 45%, transparent 75%);"></div>

                @if($enemigo->es_boss)
                <span style="position: absolute; top: 10px; left: 10px; background: #B30303; color: white; padding: 3px 10px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; letter-spacing: 1px; box-shadow: 0 2px 8px rgba(0,0,0,0.5);">⚠️ BOSS</span>
                @endif

                @php
                    $cr = $enemigo->clase_de_desafio;
                    if ($cr <= 3) { $crColor = '#4caf50'; }
                    elseif ($cr <= 7) { $crColor = '#768596'; }
                    elseif ($cr <= 12) { $crColor = '#D46043'; }
                    else { $crColor = '#B30303'; }
                @endphp
                <span style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.6); color: {{ $crColor }}; padding: 3px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; border: 1px solid {{ $crColor }};">CR {{ $enemigo->clase_de_desafio }}</span>

                <!-- Nombre sobre la imagen -->
                <div style="position: absolute; bottom: 10px; left: 14px; right: 14px;">
                    <div style="font-weight: bold; font-size: 1.15rem; color: white; text-shadow: 0 2px 6px rgba(0,0,0,0.8);">{{ $enemigo->nombre }}</div>
                    <div style="color: rgba(255,255,255,0.75); font-size: 0.8rem;">{{ $enemigo->tipo }} · {{ $enemigo->tamaño }}</div>
                </div>
            </div>

            <!-- STATS -->
            <div style="padding: 14px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; text-align: center; margin-bottom: 12px;">
                    <div style="background: #120309; border-radius: 6px; padding: 8px;">
                        <div style="font-size: 1rem; font-weight: bold;">{{ $enemigo->puntos_de_golpe }}</div>
                        <div style="color: var(--color-gris); font-size: 0.7rem;">HP</div>
                    </div>
                    <div style="background: #120309; border-radius: 6px; padding: 8px;">
                        <div style="font-size: 1rem; font-weight: bold;">{{ $enemigo->clase_de_armadura }}</div>
                        <div style="color: var(--color-gris); font-size: 0.7rem;">CA</div>
                    </div>
                    <div style="background: #120309; border-radius: 6px; padding: 8px;">
                        <div style="font-size: 1rem; font-weight: bold;">+{{ floor(($enemigo->destreza - 10) / 2) }}</div>
                        <div style="color: var(--color-gris); font-size: 0.7rem;">Init</div>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end;">
                    <form method="POST" action="/enemigos/{{ $enemigo->id }}" onsubmit="event.stopPropagation(); return confirm('¿Eliminar este enemigo?')" onclick="event.stopPropagation()">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-peligro btn-sm">🗑 Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
       @empty
        <div style="grid-column: 1/-1;" class="vacio">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 60px; height: 60px; stroke: var(--c-rojo); fill: none; stroke-width: 1.5; opacity: 0.5; margin: 0 auto 1rem; display: block;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 2a7 7 0 0 1 7 7c0 2.5-1.3 4.7-3.3 6l-.7 3H9l-.7-3A7 7 0 0 1 5 9a7 7 0 0 1 7-7z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v2a1 1 0 0 0 1 1h4a1 1 0 0 0 1-1v-2"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.5 11a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1zM14.5 11a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            </svg>
            <h3 style="text-align: center; margin-bottom: 0.5rem;">Bestiario vacío</h3>
            <p style="text-align: center; margin-bottom: 1.5rem;">Aún no has registrado ningún enemigo. ¡Crea el primero!</p>
            <div style="text-align: center;">
                <button onclick="abrirModalNuevo()" class="btn btn-primario">+ Añadir primer enemigo</button>
            </div>
        </div>
        @endforelse

    </div>
</section>

<!-- MODAL AÑADIR ENEMIGO -->
<div id="modal-enemigo" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 12px; padding: 30px; width: 100%; max-width: 700px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;">Nuevo Enemigo</h2>
            <button onclick="document.getElementById('modal-enemigo').style.display='none'" style="background: none; border: none; color: var(--color-gris); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>

        <form method="POST" action="/enemigos" enctype="multipart/form-data">
            @csrf

            <!-- BÁSICOS -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div style="grid-column: 1/-1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="color: var(--color-gris); font-size: 0.8rem;">Nombre *</label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--color-gris); font-size: 0.85rem;">
                            <input type="checkbox" name="es_boss" id="nuevo-boss" style="width: 16px; height: 16px; accent-color: #B30303; cursor: pointer;">
                            ⚠️ Es un Boss
                        </label>
                    </div>
                    <input type="text" name="nombre" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Tipo *</label>
                    <input type="text" name="tipo" placeholder="Humanoide, Bestia..." required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Tamaño *</label>
                    <select name="tamaño" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                        <option value="Diminuto">Diminuto</option>
                        <option value="Pequeño">Pequeño</option>
                        <option value="Mediano" selected>Mediano</option>
                        <option value="Grande">Grande</option>
                        <option value="Enorme">Enorme</option>
                        <option value="Gargantuesco">Gargantuesco</option>
                    </select>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Alineamiento</label>
                    <input type="text" name="alineamiento" placeholder="Caótico Malvado..." style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">CR (Clase de Desafío) *</label>
                    <input type="number" name="clase_de_desafio" step="0.125" min="0" value="1" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Puntos de Experiencia *</label>
                    <input type="number" name="puntos_de_experiencia" value="0" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
            </div>

            <!-- COMBATE -->
            <h3 style="color: white; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 15px; margin-top: 35px; padding-bottom: 10px; border-bottom: 1px solid rgba(179,3,3,0.4);">Combate</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">CA *</label>
                    <input type="number" name="clase_de_armadura" value="10" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Tipo Armadura</label>
                    <input type="text" name="tipo_armadura" placeholder="Armadura natural..." style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Puntos de Golpe *</label>
                    <input type="text" name="puntos_de_golpe" placeholder="66 (12d8+12)" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Velocidad *</label>
                    <input type="text" name="velocidad" value="9 m." required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
            </div>

            <!-- CARACTERÍSTICAS -->
            <h3 style="color: white; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 15px; margin-top: 35px; padding-bottom: 10px; border-bottom: 1px solid rgba(179,3,3,0.4);">Características</h3>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; margin-bottom: 15px;">
                @foreach(['fuerza' => 'FUE', 'destreza' => 'DES', 'constitucion' => 'CON', 'inteligencia' => 'INT', 'sabiduria' => 'SAB', 'carisma' => 'CAR'] as $campo => $label)
                <div>
                    <label style="color: var(--color-gris); font-size: 0.75rem; display: block; margin-bottom: 6px; text-align: center;">{{ $label }}</label>
                    <input type="number" name="{{ $campo }}" value="10" min="1" max="30" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 5px; color: white; font-family: Georgia, serif; text-align: center;">
                </div>
                @endforeach
            </div>

            <!-- OPCIONALES -->
           <h3 style="color: white; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 15px; margin-top: 35px; padding-bottom: 10px; border-bottom: 1px solid rgba(179,3,3,0.4);">Información Adicional</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Descripción</label>
                    <textarea name="descripcion" rows="3" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Resistencias</label>
                    <textarea name="resistencias" rows="2" placeholder="Fuego, Frío..." style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Inmunidades al daño</label>
                    <textarea name="inmunidades_daño" rows="2" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Sentidos</label>
                    <textarea name="sentidos" rows="2" placeholder="Visión en la oscuridad 18 m..." style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Idiomas</label>
                    <input type="text" name="idiomas" placeholder="Común, Infernal..." style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Acciones</label>
                    <textarea name="acciones" rows="3" placeholder="Ataque con espada: +5 al ataque, 1d8+3 de daño cortante..." style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Rasgos Especiales</label>
                    <textarea name="rasgos_especiales" rows="3" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: end; margin-bottom: 15px;">
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Imagen</label>
                    <img id="nuevo-imagen-preview" src="" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(179,3,3,0.3); display: none; margin-bottom: 8px;">
                    <label style="display: flex; align-items: center; gap: 10px; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 18px; height: 18px; stroke: var(--color-gris); fill: none; stroke-width: 2; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span style="color: var(--color-gris); font-size: 0.85rem;" id="label-imagen-editar">Seleccionar imagen...</span>
                        <input type="file" name="imagen" accept="image/*" style="display: none;" onchange="this.previousElementSibling.textContent = this.files[0]?.name || 'Seleccionar imagen...'">
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="document.getElementById('modal-enemigo').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Crear Enemigo</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL VER/EDITAR ENEMIGO -->
<div id="modal-ver-enemigo" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:1000; align-items:flex-start; justify-content:center; padding: 20px; overflow-y: auto;">
    <div style="background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 12px; padding: 30px; width: 100%; max-width: 700px; margin: auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h2 style="font-size: 1.2rem; letter-spacing: 2px; text-transform: uppercase;" id="ver-titulo">Enemigo</h2>
            <button onclick="document.getElementById('modal-ver-enemigo').style.display='none'" style="background: none; border: none; color: var(--color-gris); font-size: 1.5rem; cursor: pointer;">✕</button>
        </div>

        <form id="form-editar-enemigo" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div style="grid-column: 1/-1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <label style="color: var(--color-gris); font-size: 0.8rem;">Nombre *</label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; color: var(--color-gris); font-size: 0.85rem;">
                            <input type="checkbox" name="es_boss" id="ver-boss" style="width: 16px; height: 16px; accent-color: #B30303; cursor: pointer;">
                            ⚠️ Es un Boss
                        </label>
                    </div>
                    <input type="text" name="nombre" id="ver-nombre" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Tipo *</label>
                    <input type="text" name="tipo" id="ver-tipo" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Tamaño *</label>
                    <select name="tamaño" id="ver-tamaño" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                        <option value="Diminuto">Diminuto</option>
                        <option value="Pequeño">Pequeño</option>
                        <option value="Mediano">Mediano</option>
                        <option value="Grande">Grande</option>
                        <option value="Enorme">Enorme</option>
                        <option value="Gargantuesco">Gargantuesco</option>
                    </select>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Alineamiento</label>
                    <input type="text" name="alineamiento" id="ver-alineamiento" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">CR *</label>
                    <input type="number" name="clase_de_desafio" id="ver-cr" step="0.125" min="0" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Puntos de Experiencia *</label>
                    <input type="number" name="puntos_de_experiencia" id="ver-xp" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
            </div>

            <h3 style="color: white; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 15px; margin-top: 35px; padding-bottom: 10px; border-bottom: 1px solid rgba(179,3,3,0.4);">Combate</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">CA *</label>
                    <input type="number" name="clase_de_armadura" id="ver-ca" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Tipo Armadura</label>
                    <input type="text" name="tipo_armadura" id="ver-tipo-armadura" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Puntos de Golpe *</label>
                    <input type="text" name="puntos_de_golpe" id="ver-pg" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Velocidad *</label>
                    <input type="text" name="velocidad" id="ver-velocidad" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
            </div>

            <h3 style="color: white; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 15px; margin-top: 35px; padding-bottom: 10px; border-bottom: 1px solid rgba(179,3,3,0.4);">Características</h3>
            <div style="display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; margin-bottom: 15px;">
                @foreach(['fuerza' => 'FUE', 'destreza' => 'DES', 'constitucion' => 'CON', 'inteligencia' => 'INT', 'sabiduria' => 'SAB', 'carisma' => 'CAR'] as $campo => $label)
                <div>
                    <label style="color: var(--color-gris); font-size: 0.75rem; display: block; margin-bottom: 6px; text-align: center;">{{ $label }}</label>
                    <input type="number" name="{{ $campo }}" id="ver-{{ $campo }}" min="1" max="30" required style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 5px; color: white; font-family: Georgia, serif; text-align: center;">
                </div>
                @endforeach
            </div>

           <h3 style="color: white; font-size: 0.85rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 15px; margin-top: 35px; padding-bottom: 10px; border-bottom: 1px solid rgba(179,3,3,0.4);">Información Adicional</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Descripción</label>
                    <textarea name="descripcion" id="ver-descripcion" rows="3" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Resistencias</label>
                    <textarea name="resistencias" id="ver-resistencias" rows="2" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Inmunidades al daño</label>
                    <textarea name="inmunidades_daño" id="ver-inmunidades" rows="2" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Sentidos</label>
                    <textarea name="sentidos" id="ver-sentidos" rows="2" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Idiomas</label>
                    <input type="text" name="idiomas" id="ver-idiomas" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif;">
                </div>
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Acciones</label>
                    <textarea name="acciones" id="ver-acciones" rows="3" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
                <div style="grid-column: 1/-1;">
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Rasgos Especiales</label>
                    <textarea name="rasgos_especiales" id="ver-rasgos" rows="3" style="width: 100%; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; color: white; font-family: Georgia, serif; resize: vertical;"></textarea>
                </div>
            </div>

           <div style="display: grid; grid-template-columns: 1fr auto; gap: 15px; align-items: end; margin-bottom: 15px;">
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Imagen</label>
                    <img id="ver-imagen-preview" src="" style="width: 100%; height: 120px; object-fit: cover; border-radius: 8px; border: 1px solid rgba(179,3,3,0.3); display: none; margin-bottom: 8px; cursor: zoom-in;" onclick="document.getElementById('lightbox-enemigo-img').src=this.src; document.getElementById('lightbox-enemigo').style.display='flex';">
                    <label style="display: flex; align-items: center; gap: 10px; background: #20050E; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 10px 15px; cursor: pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 18px; height: 18px; stroke: var(--color-gris); fill: none; stroke-width: 2; flex-shrink: 0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span style="color: var(--color-gris); font-size: 0.85rem;" id="label-imagen-editar">Seleccionar imagen...</span>
                        <input type="file" name="imagen" accept="image/*" style="display: none;" onchange="this.previousElementSibling.textContent = this.files[0]?.name || 'Seleccionar imagen...'">
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="document.getElementById('modal-ver-enemigo').style.display='none'" class="btn btn-secundario">Cancelar</button>
                    <button type="submit" class="btn btn-primario">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="lightbox-enemigo" onclick="this.style.display='none'" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.92); z-index:2000; align-items:center; justify-content:center; cursor:zoom-out;">
    <img id="lightbox-enemigo-img" src="" style="max-width: 90vw; max-height: 90vh; object-fit: contain; border-radius: 8px; border: 2px solid rgba(179,3,3,0.3);">
</div>

<script>
    function buscarEnemigo() {
        const texto = document.getElementById('buscador').value.toLowerCase();
        document.querySelectorAll('.tarjeta-enemigo').forEach(tarjeta => {
            const nombre = tarjeta.dataset.nombre.toLowerCase();
            const coincide = nombre.includes(texto);
            const filtroOk = !filtroBossActivo || tarjeta.dataset.boss === '1';
            tarjeta.style.display = (coincide && filtroOk) ? 'block' : 'none';
        });
    }

    let filtroBossActivo = false;

    function filtrarBoss() {
        filtroBossActivo = !filtroBossActivo;
        const btn = document.getElementById('btn-boss');
        btn.style.background = filtroBossActivo ? 'rgba(179,3,3,0.25)' : '';
        btn.style.borderColor = filtroBossActivo ? 'var(--color-rojo)' : '';
        buscarEnemigo();
    }

    function verEnemigo(id) {
        fetch(`/enemigos/${id}`)
            .then(r => r.json())
            .then(e => {
                document.getElementById('ver-titulo').textContent = e.nombre;
                document.getElementById('form-editar-enemigo').action = `/enemigos/${id}/update`;
                document.getElementById('ver-nombre').value = e.nombre;
                document.getElementById('ver-tipo').value = e.tipo;
                document.getElementById('ver-tamaño').value = e.tamaño;
                document.getElementById('ver-alineamiento').value = e.alineamiento || '';
                document.getElementById('ver-cr').value = e.clase_de_desafio;
                document.getElementById('ver-xp').value = e.puntos_de_experiencia;
                document.getElementById('ver-ca').value = e.clase_de_armadura;
                document.getElementById('ver-tipo-armadura').value = e.tipo_armadura || '';
                document.getElementById('ver-pg').value = e.puntos_de_golpe;
                document.getElementById('ver-velocidad').value = e.velocidad;
                document.getElementById('ver-fuerza').value = e.fuerza;
                document.getElementById('ver-destreza').value = e.destreza;
                document.getElementById('ver-constitucion').value = e.constitucion;
                document.getElementById('ver-inteligencia').value = e.inteligencia;
                document.getElementById('ver-sabiduria').value = e.sabiduria;
                document.getElementById('ver-carisma').value = e.carisma;
                document.getElementById('ver-descripcion').value = e.descripcion || '';
                document.getElementById('ver-resistencias').value = e.resistencias || '';
                document.getElementById('ver-inmunidades').value = e.inmunidades_daño || '';
                document.getElementById('ver-sentidos').value = e.sentidos || '';
                document.getElementById('ver-idiomas').value = e.idiomas || '';
                document.getElementById('ver-acciones').value = e.acciones || '';
                document.getElementById('ver-rasgos').value = e.rasgos_especiales || '';
                document.getElementById('ver-boss').checked = e.es_boss == 1;
                const preview = document.getElementById('ver-imagen-preview') || document.getElementById('ver-imagen-preview');
                if (e.imagen) {
                    console.log('Imagen:', e.imagen);
                    console.log('URL:', '/storage/' + e.imagen);
                    preview.src = '/storage/' + e.imagen;
                    preview.style.display = 'block';
                } else {
                    preview.style.display = 'none';
                }
                setTimeout(() => {
                    document.getElementById('modal-ver-enemigo').scrollTop = 0;
                }, 10);
                document.getElementById('modal-ver-enemigo').style.display = 'flex';
            });
    }

    let ordenAscendente = true;
    let ordenNombreAsc = true;

    function ordenarPorCR() {
        const lista = document.getElementById('lista-enemigos');
        const tarjetas = Array.from(lista.querySelectorAll('.tarjeta-enemigo'));

        tarjetas.sort((a, b) => {
            const crA = parseFloat(a.dataset.cr);
            const crB = parseFloat(b.dataset.cr);
            return ordenAscendente ? crA - crB : crB - crA;
        });

        ordenAscendente = !ordenAscendente;
        const btn = document.getElementById('btn-orden');
        btn.textContent = ordenAscendente ? '↑↓ Ordenar por CR' : '↓↑ Ordenar por CR';
        btn.style.color = ordenAscendente ? 'var(--color-gris)' : 'white';
        btn.style.borderColor = ordenAscendente ? 'var(--color-gris)' : 'var(--color-rojo)';

        // Resetear botón de nombre
        ordenNombreAsc = true;
        const btnNombre = document.getElementById('btn-nombre');
        btnNombre.textContent = '↑↓ Ordenar por Nombre';
        btnNombre.style.color = 'var(--color-gris)';
        btnNombre.style.borderColor = 'var(--color-gris)';

        tarjetas.forEach(t => lista.appendChild(t));
    }

    function ordenarPorNombre() {
        const lista = document.getElementById('lista-enemigos');
        const tarjetas = Array.from(lista.querySelectorAll('.tarjeta-enemigo'));

        tarjetas.sort((a, b) => {
            const nombreA = a.dataset.nombre.toLowerCase();
            const nombreB = b.dataset.nombre.toLowerCase();
            return ordenNombreAsc ? nombreA.localeCompare(nombreB) : nombreB.localeCompare(nombreA);
        });

        ordenNombreAsc = !ordenNombreAsc;
        const btn = document.getElementById('btn-nombre');
        btn.textContent = ordenNombreAsc ? '↑↓ Ordenar por Nombre' : '↓↑ Ordenar por Nombre';
        btn.style.color = ordenNombreAsc ? 'var(--color-gris)' : 'white';
        btn.style.borderColor = ordenNombreAsc ? 'var(--color-gris)' : 'var(--color-rojo)';

        // Resetear botón de CR
        ordenAscendente = true;
        const btnCR = document.getElementById('btn-orden');
        btnCR.textContent = '↑↓ Ordenar por CR';
        btnCR.style.color = 'var(--color-gris)';
        btnCR.style.borderColor = 'var(--color-gris)';

        tarjetas.forEach(t => lista.appendChild(t));
    }

    function abrirModalNuevo() {
        setTimeout(() => {
            document.getElementById('modal-enemigo').scrollTop = 0;
        }, 10);
        document.getElementById('modal-enemigo').style.display = 'flex';
    }
</script>

@endsection