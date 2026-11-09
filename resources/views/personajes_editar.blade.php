@extends('layouts.app')

@section('titulo', 'Editar — ' . $personaje->nombre)

@push('head')
    @vite(['resources/css/pages/personaje-editar.css', 'resources/js/pages/personaje-editar.js'])
@endpush

@section('contenido')

@php
    $est = $personaje->estadisticas;
    $statsMap = [
        'FUE' => 'fuerza',
        'DES' => 'destreza',
        'CON' => 'constitucion',
        'INT' => 'inteligencia',
        'SAB' => 'sabiduria',
        'CAR' => 'carisma',
    ];
    $habilidades = [
        'Atletismo'       => 'fuerza',
        'Acrobacias'      => 'destreza',
        'Sigilo'          => 'destreza',
        'Prestidigitación' => 'destreza',
        'Arcana'          => 'inteligencia',
        'Historia'        => 'inteligencia',
        'Investigación'   => 'inteligencia',
        'Naturaleza'      => 'inteligencia',
        'Religión'        => 'inteligencia',
        'Medicina'        => 'sabiduria',
        'Percepción'      => 'sabiduria',
        'Perspicacia'     => 'sabiduria',
        'Supervivencia'   => 'sabiduria',
        'Trato con animales' => 'sabiduria',
        'Engaño'          => 'carisma',
        'Intimidación'    => 'carisma',
        'Actuación'       => 'carisma',
        'Persuasión'      => 'carisma',
    ];
    $compHab = json_decode($personaje->competencias_habilidades ?? '[]', true) ?? [];
    $compSal = json_decode($personaje->competencias_salvaciones ?? '[]', true) ?? [];
    $ataques = json_decode($personaje->ataques ?? '[]', true) ?? [];
    $imgsPersonaje = json_decode($personaje->imagenes_personaje ?? '[]', true) ?? [];
    $imgsArmas     = json_decode($personaje->imagenes_armas ?? '[]', true) ?? [];
@endphp

<div class="edit-wrapper">

    {{-- CABECERA --}}
    <div class="edit-header">
        <img class="edit-avatar" src="{{ $personaje->avatar_url }}" alt="{{ $personaje->nombre }}" id="avatarPreview">
        <div class="edit-header-info">
            <h1>{{ $personaje->nombre }}</h1>
            <div class="subtitulo">
                {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
            </div>
        </div>
    </div>

    {{-- FORMULARIO PRINCIPAL --}}
    <form action="{{ route('personajes.update', $personaje) }}" method="POST"
          enctype="multipart/form-data" id="formPersonaje">
        @csrf
        @method('PUT')

        <div class="edit-body">

            @if($errors->any())
            <div class="alerta alerta-error ed-errores" role="alert">
                <strong>Errores de validación:</strong>
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- ======= INFORMACIÓN BÁSICA ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'scroll']) Información Básica</summary>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre <span class="req">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-error @enderror"
                               value="{{ old('nombre', $personaje->nombre) }}" required>
                        @error('nombre') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nivel <span class="req">*</span></label>
                        <input type="number" name="nivel" id="nivelInput" class="form-control @error('nivel') is-error @enderror"
                                value="{{ old('nivel', $personaje->nivel) }}" min="1" max="20" required
                                oninput="actualizarBonusCompetencia()">
                        @error('nivel') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Raza <span class="req">*</span></label>
                        <select name="raza_id" class="form-control" required>
                            <option value="">— Selecciona una raza —</option>
                            @foreach($razas as $raza)
                            <option value="{{ $raza->id }}" {{ old('raza_id', $personaje->raza_id) == $raza->id ? 'selected' : '' }}>
                                {{ $raza->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Clase <span class="req">*</span></label>
                        <select name="clase_id" id="claseSelect" class="form-control" required onchange="filtrarSubclases()">
                            <option value="">— Selecciona una clase —</option>
                            @foreach($clases as $clase)
                            <option value="{{ $clase->id }}" {{ old('clase_id', $personaje->clase_id) == $clase->id ? 'selected' : '' }}>
                                {{ $clase->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Subclase</label>
                    <select name="subclase_id" id="subclaseSelect" class="form-control @error('subclase_id') error @enderror">
                        <option value="">Sin subclase todavía</option>
                        @foreach($subclases as $subclase)
                            <option value="{{ $subclase->id }}" data-clase="{{ $subclase->clase_id }}" {{ old('subclase_id', $personaje->subclase_id) == $subclase->id ? 'selected' : '' }}>
                                {{ $subclase->nombre }} ({{ $subclase->clase->nombre ?? '' }})
                            </option>
                        @endforeach
                    </select>
                    @error('subclase_id')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Trasfondo</label>
                        <select name="trasfondo_id" class="form-control">
                            <option value="">— Sin trasfondo —</option>
                            @foreach($trasfondos as $tf)
                            <option value="{{ $tf->id }}" {{ old('trasfondo_id', $personaje->trasfondo_id) == $tf->id ? 'selected' : '' }}>
                                {{ $tf->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Alineamiento</label>
                        <select name="alineamiento" class="form-control">
                            <option value="">— Sin alineamiento —</option>
                            @foreach(['Legal Bueno','Neutral Bueno','Caótico Bueno','Legal Neutral','Neutral','Caótico Neutral','Legal Malvado','Neutral Malvado','Caótico Malvado'] as $al)
                            <option value="{{ $al }}" {{ old('alineamiento', $personaje->alineamiento) == $al ? 'selected' : '' }}>
                                {{ $al }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Avatar (URL)</label>
                        <input type="url" name="avatar" class="form-control"
                               placeholder="https://..." value="{{ old('avatar', $personaje->avatar) }}"
                               onchange="document.getElementById('avatarPreview').src = this.value || '{{ $personaje->avatar_url }}'">
                        @if($personaje->avatar)
                        <img src="{{ $personaje->avatar }}" class="avatar-preview" alt="Avatar actual">
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Divinidad / Deidad</label>
                        <input type="text" name="divinidad" class="form-control"
                               placeholder="Ej: Torm, Selûne..." value="{{ old('divinidad', $personaje->divinidad) }}">
                    </div>
                </div>

                <div class="form-group">
                    <label>Historia del personaje</label>
                    <textarea name="historia" class="form-control" rows="4"
                              placeholder="Cuenta el origen y motivaciones de tu héroe...">{{ old('historia', $personaje->historia) }}</textarea>
                </div>
            </details>

            {{-- ======= IMÁGENES ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'image']) Imágenes</summary>

                {{-- Imágenes del personaje --}}
                <div class="form-group">
                    <label>Imágenes del Personaje (máx. 5 — {{ count($imgsPersonaje) }}/5 actuales)</label>

                    @if(count($imgsPersonaje) < 5)
                    <div class="upload-zone" onclick="document.getElementById('uploadPersonaje').click()">
                        <input type="file" id="uploadPersonaje" name="imagenes_personaje[]"
                            multiple accept="image/jpeg,image/png,image/webp"
                            onchange="previewImgs(this, 'previewP', {{ 5 - count($imgsPersonaje) }})">
                        <div class="upload-label">
                            📁 <span>Selecciona hasta {{ 5 - count($imgsPersonaje) }} imágenes de golpe</span> — JPG, PNG, WEBP · Máx. 2MB
                        </div>
                    </div>
                    <div id="previewP" class="imgs-grid"></div>
                    @else
                    <p class="ed-nota">Has alcanzado el límite de 5 imágenes. Elimina alguna para añadir más.</p>
                    @endif

                    @if(count($imgsPersonaje) > 0)
                    <div class="imgs-grid">
                        @foreach($imgsPersonaje as $i => $img)
                        <div class="img-existente">
                            <img src="{{ Storage::url($img) }}" alt="Imagen {{ $i+1 }}">
                            <button type="button" class="btn-del-img" onclick="eliminarImagen('personaje', {{ $i }})">✕</button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Imágenes de armas --}}
                <div class="form-group">
                    <label>Imágenes de Armas (máx. 5 — {{ count($imgsArmas) }}/5 actuales)</label>

                    @if(count($imgsArmas) < 5)
                    <div class="upload-zone" onclick="document.getElementById('uploadArmas').click()">
                        <input type="file" id="uploadArmas" name="imagenes_armas[]"
                            multiple accept="image/jpeg,image/png,image/webp"
                            onchange="previewImgs(this, 'previewA', {{ 5 - count($imgsArmas) }})">
                        <div class="upload-label">
                            ⚔️ <span>Selecciona hasta {{ 5 - count($imgsArmas) }} imágenes de golpe</span> — JPG, PNG, WEBP · Máx. 2MB
                        </div>
                    </div>
                    <div id="previewA" class="imgs-grid"></div>
                    @else
                    <p class="ed-nota">Has alcanzado el límite de 5 imágenes. Elimina alguna para añadir más.</p>
                    @endif

                    @if(count($imgsArmas) > 0)
                    <div class="imgs-grid">
                        @foreach($imgsArmas as $i => $img)
                        <div class="img-existente">
                            <img src="{{ Storage::url($img) }}" alt="Arma {{ $i+1 }}">
                            <button type="button" class="btn-del-img" onclick="eliminarImagen('arma', {{ $i }})">✕</button>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </details>

            {{-- ======= ESTADÍSTICAS ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'star']) Características</summary>
                <div class="stats-grid">
                    @foreach($statsMap as $label => $attr)
                    @php
                        $val    = old($attr, $est ? ($est->$attr ?? 10) : 10);
                        $mod    = floor(($val - 10) / 2);
                        $modStr = $mod >= 0 ? '+' . $mod : (string)$mod;
                    @endphp
                    <div class="stat-edit">
                        <span class="stat-label">{{ $label }}</span>
                        <input type="number" name="{{ $attr }}" class="stat-input"
                               value="{{ $val }}" min="1" max="30" required
                               oninput="actualizarMod(this)">
                        <span class="stat-mod" id="mod-{{ $attr }}">{{ $modStr }}</span>
                        @error($attr) <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    @endforeach
                </div>
            </details>

            {{-- ======= TIRADAS DE SALVACIÓN ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'shield']) Competencia en Tiradas de Salvación</summary>
                <div class="check-grid">
                    @foreach($statsMap as $label => $attr)
                    <label class="check-item">
                        <input type="checkbox" name="competencias_salvaciones[]"
                               value="{{ $attr }}"
                               {{ in_array($attr, $compSal) ? 'checked' : '' }}>
                        <span>{{ $label }} — {{ ucfirst($attr) }}</span>
                    </label>
                    @endforeach
                </div>
                <input type="hidden" name="competencias_salvaciones"
                       id="hiddenSal" value="{{ old('competencias_salvaciones', $personaje->competencias_salvaciones ?? '[]') }}">
            </details>

            {{-- ======= HABILIDADES ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Competencia en Habilidades</summary>
                <div class="check-grid">
                    @foreach($habilidades as $nombre => $base)
                    <label class="check-item">
                        <input type="checkbox" name="competencias_habilidades[]"
                               value="{{ $nombre }}"
                               {{ in_array($nombre, $compHab) ? 'checked' : '' }}>
                        <span>{{ $nombre }} <span class="ed-sigla">({{ strtoupper(substr($base,0,3)) }})</span></span>
                    </label>
                    @endforeach
                </div>
                <input type="hidden" name="competencias_habilidades"
                       id="hiddenHab" value="{{ old('competencias_habilidades', $personaje->competencias_habilidades ?? '[]') }}">
            </details>

            {{-- ======= COMBATE ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'sword']) Combate</summary>
                <div class="combat-grid">
                    @php $est = $personaje->estadisticas; @endphp
                    <div class="combat-field">
                        <label>❤️ PG Actuales</label>
                        <input type="number" name="pg_actuales"
                               value="{{ old('pg_actuales', $est->pg_actuales ?? 10) }}" min="0">
                    </div>
                    <div class="combat-field">
                        <label>❤️ PG Máximos</label>
                        <input type="number" name="pg_maximos"
                               value="{{ old('pg_maximos', $est->pg_maximos ?? 10) }}" min="1">
                    </div>
                    <div class="combat-field">
                        <label>💙 PG Temporales</label>
                        <input type="number" name="pg_temporales"
                               value="{{ old('pg_temporales', $est->pg_temporales ?? 0) }}" min="0">
                    </div>
                    <div class="combat-field">
                        <label>🛡️ Clase de Armadura</label>
                        <input type="number" name="clase_de_armadura"
                               value="{{ old('clase_de_armadura', $est->clase_de_armadura ?? 10) }}" min="0">
                    </div>
                    <div class="combat-field">
                        <label>⚡ Velocidad (ft)</label>
                        <input type="number" name="velocidad"
                               value="{{ old('velocidad', $est->velocidad ?? 30) }}" min="0">
                    </div>
                    <div class="combat-field">
                        <label>🎯 Bonus Competencia (auto)</label>
                        <input type="number" id="bonusCompetenciaDisplay"
                            value="{{ $est->bonus_competencia ?? 2 }}" disabled
                            class="campo-auto">
                    </div>
                    <div class="combat-field">
                        <label>⚔️ Iniciativa (mod)</label>
                        <input type="number" name="iniciativa"
                               value="{{ old('iniciativa', $est->iniciativa ?? '') }}"
                               placeholder="Auto">
                    </div>
                    <div class="combat-field">
                        <label>🎲 Dados de Golpe disponibles</label>
                        <input type="number" name="dados_golpe_disponibles"
                               value="{{ old('dados_golpe_disponibles', $est->dados_golpe_disponibles ?? '') }}" min="0">
                    </div>
                </div>

                {{-- Muerte / inspiración --}}
                <div class="ed-muerte">
                    <div>
                        <label class="ed-muerte-et">✔ Éxitos de muerte</label>
                        <input type="number" name="exitos_muerte" min="0" max="3"
                               value="{{ old('exitos_muerte', $est->exitos_muerte ?? 0) }}"
                               class="ed-contador">
                    </div>
                    <div>
                        <label class="ed-muerte-et">✖ Fallos de muerte</label>
                        <input type="number" name="fallos_muerte" min="0" max="3"
                               value="{{ old('fallos_muerte', $est->fallos_muerte ?? 0) }}"
                               class="ed-contador">
                    </div>
                    <div class="ed-muerte-auto">
                        <label class="check-item">
                            <input type="checkbox" name="inspiracion" value="1"
                                   {{ old('inspiracion', $est->inspiracion ?? false) ? 'checked' : '' }}>
                            <span>✨ Inspiración activa</span>
                        </label>
                    </div>
                </div>
            </details>

            {{-- ======= ARMAS Y ATAQUES ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'swords']) Armas y Ataques</summary>
                <div class="ed-nota">Nombre · Bonificador de ataque · Daño/Tipo</div>

                @if($personaje->equipo && $personaje->equipo->count() > 0)
                <div class="form-group ed-ataque-rapido">
                    <label>Añadir rápido desde tu equipo</label>
                    <select class="form-control" id="armaDesdeEquipo" onchange="addAtaqueDesdeEquipo(this)">
                        <option value="">— Elige un objeto de tu inventario —</option>
                        @foreach($personaje->equipo as $item)
                            <option value="{{ $item->nombre }}">{{ $item->nombre }} ({{ $item->tipo }})</option>
                        @endforeach
                    </select>
                </div>
                @endif

                <div class="ataques-lista" id="ataquesList">
                    @foreach($ataques as $i => $ataque)
                    <div class="ataque-row">
                        <input type="text" name="ataque_nombre[]"
                               value="{{ $ataque['nombre'] ?? '' }}" placeholder="Nombre del ataque">
                        <input type="text" name="ataque_bonif[]"
                               value="{{ $ataque['bonificador'] ?? '' }}" placeholder="+5">
                        <input type="text" name="ataque_dano[]"
                               value="{{ $ataque['daño'] ?? '' }}" placeholder="1d8+3 cortante">
                        <button type="button" class="btn-del-ataque" onclick="this.closest('.ataque-row').remove()">✕</button>
                    </div>
                    @endforeach
                </div>

                <button type="button" class="btn-add-ataque" onclick="addAtaque()">@include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir ataque</button>
                <input type="hidden" name="ataques" id="ataquesHidden">
            </details>

            {{-- ======= CONJUROS Y TRUCOS ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Conjuros y Trucos</summary>

                @if($personaje->trucos && $personaje->trucos->count() > 0)
                <div class="equipo-existente ed-sep">
                    @foreach($personaje->trucos as $truco)
                    <div class="equipo-item">
                        <span class="equipo-nombre">{{ $truco->conjuro->nombre ?? $truco->nombre }}</span>
                        <div class="equipo-det">
                            {{ $truco->conjuro ? 'Nivel ' . $truco->conjuro->nivel . ' · ' . $truco->conjuro->escuela : 'Conjuro propio' }}
                        </div>
                        <button type="button" class="btn-del-equipo"
                                onclick="eliminarTruco('{{ route('trucos.destroy', ['personaje' => $personaje, 'truco' => $truco]) }}', '{{ addslashes($truco->conjuro->nombre ?? $truco->nombre) }}')">✕</button>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="ed-vacio">Este personaje no tiene conjuros ni trucos todavía.</p>
                @endif

                @php $idsExistentes = $personaje->trucos->pluck('conjuro_id')->filter()->values(); @endphp

                <div class="conjuro-picker">
                    <div class="conjuro-picker-cab">Añadir conjuros del catálogo</div>
                    <input type="text" class="form-control conjuro-buscador"
                           placeholder="Buscar conjuro por nombre..."
                           oninput="filtrarConjuros(this.value)">

                    <div class="conjuro-chips" id="conjuroChips">
                        <span class="conjuro-chips-vacio">Ningún conjuro seleccionado todavía — haz click en uno de la lista</span>
                    </div>

                    <div class="conjuro-lista" id="conjuroLista">
                        @foreach($conjurosCatalogo->groupBy('nivel') as $nivel => $grupo)
                            @php $yaTiene = $grupo->filter(fn($c) => $idsExistentes->contains($c->id))->count() === $grupo->count(); @endphp
                            @if(!$yaTiene)
                            <div class="conjuro-grupo">
                                <div class="conjuro-grupo-titulo">{{ $nivel == 0 ? 'Trucos' : 'Nivel ' . $nivel }}</div>
                                @foreach($grupo as $c)
                                    @if(!$idsExistentes->contains($c->id))
                                    <div class="conjuro-row" data-nombre="{{ strtolower($c->nombre) }}"
                                         data-id="{{ $c->id }}" data-nombre-mostrar="{{ $c->nombre }}"
                                         onclick="toggleConjuro(this)">
                                        <span class="conjuro-row-nombre">{{ $c->nombre }}</span>
                                        <span class="conjuro-row-escuela">{{ $c->escuela }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="conjuros_nuevos" id="conjurosNuevosHidden">
            </details>

            {{-- ======= DOTES ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'star']) Dotes</summary>

                @if($personaje->dotes && $personaje->dotes->count() > 0)
                <div class="equipo-existente ed-sep">
                    @foreach($personaje->dotes as $dote)
                    <div class="equipo-item">
                        <span class="equipo-nombre">{{ $dote->nombre }}</span>
                        <div class="equipo-det">{{ $dote->categoria }}</div>
                        <button type="button" class="btn-del-equipo"
                                onclick="eliminarDote('{{ route('dotes.destroy', ['personaje' => $personaje, 'dote' => $dote]) }}', '{{ addslashes($dote->nombre) }}')">✕</button>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="ed-vacio">Este personaje no tiene dotes todavía.</p>
                @endif

                @php $doteIdsExistentes = $personaje->dotes->pluck('id')->values(); @endphp

                <div class="conjuro-picker">
                    <div class="conjuro-picker-cab">Añadir dotes del catálogo</div>
                    <input type="text" class="form-control conjuro-buscador"
                        placeholder="Buscar dote por nombre..."
                        oninput="filtrarDotes(this.value)">

                    <div class="conjuro-chips" id="doteChips">
                        <span class="conjuro-chips-vacio">Ninguna dote seleccionada todavía — haz click en una de la lista</span>
                    </div>

                    <div class="conjuro-lista" id="doteLista">
                        @foreach($dotes->groupBy('categoria') as $categoria => $grupo)
                            @php $yaTiene = $grupo->filter(fn($d) => $doteIdsExistentes->contains($d->id))->count() === $grupo->count(); @endphp
                            @if(!$yaTiene)
                            <div class="conjuro-grupo">
                                <div class="conjuro-grupo-titulo">{{ $categoria ?: 'General' }}</div>
                                @foreach($grupo as $d)
                                    @if(!$doteIdsExistentes->contains($d->id))
                                    <div class="conjuro-row" data-nombre="{{ strtolower($d->nombre) }}"
                                        data-id="{{ $d->id }}" data-nombre-mostrar="{{ $d->nombre }}"
                                        onclick="toggleDote(this)">
                                        <span class="conjuro-row-nombre">{{ $d->nombre }}</span>
                                        <span class="conjuro-row-escuela">{{ $d->categoria }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <input type="hidden" name="dotes_nuevos" id="dotesNuevosHidden">
            </details>

            {{-- ======= PERSONALIDAD ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'user']) Personalidad</summary>
                <div class="form-row">
                    <div class="form-group">
                        <label>Rasgos de personalidad</label>
                        <textarea name="rasgos_personalidad" class="form-control" rows="3"
                                  placeholder="¿Cómo se comporta tu personaje?">{{ old('rasgos_personalidad', $personaje->rasgos_personalidad) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Ideales</label>
                        <textarea name="ideales" class="form-control" rows="3"
                                  placeholder="¿En qué cree tu personaje?">{{ old('ideales', $personaje->ideales) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Vínculos</label>
                        <textarea name="vinculos" class="form-control" rows="3"
                                  placeholder="¿Qué conecta a tu personaje con el mundo?">{{ old('vinculos', $personaje->vinculos) }}</textarea>
                    </div>
                    <div class="form-group">
                        <label>Defectos</label>
                        <textarea name="defectos" class="form-control" rows="3"
                                  placeholder="¿Cuál es la debilidad de tu personaje?">{{ old('defectos', $personaje->defectos) }}</textarea>
                    </div>
                </div>
            </details>

            {{-- ======= APARIENCIA ======= --}}
            <details class="seccion" open>
                <summary class="seccion-titulo">@include('partials.icon', ['name' => 'helmet']) Apariencia</summary>
                <div class="form-row-3">
                    @foreach(['edad' => 'Edad', 'altura' => 'Altura', 'peso' => 'Peso', 'ojos' => 'Ojos', 'piel' => 'Piel', 'pelo' => 'Pelo'] as $campo => $etiq)
                    <div class="form-group">
                        <label>{{ $etiq }}</label>
                        <input type="text" name="{{ $campo }}" class="form-control"
                               value="{{ old($campo, $personaje->$campo) }}">
                    </div>
                    @endforeach
                </div>
                <div class="form-group">
                    <label>Idiomas que habla</label>
                    <input type="text" name="idiomas" class="form-control"
                           placeholder="Común, Élfico, Enano..."
                           value="{{ old('idiomas', $personaje->idiomas) }}">
                </div>
            </details>

            {{-- BOTONES --}}
            <div class="form-actions">
                <a href="{{ route('personajes.show', $personaje) }}" class="btn-cancel">@include('partials.icon', ['name' => 'x', 'class' => 'icon-sm']) Cancelar</a>
                <button type="submit" class="btn-save">@include('partials.icon', ['name' => 'check', 'class' => 'icon-sm']) Guardar Personaje</button>
            </div>

        </div>{{-- /.edit-body --}}
    </form>

    {{-- ======= EQUIPO (formulario separado) ======= --}}
    <div class="edit-body edit-body--sin-arriba">
        <details class="seccion" open>
            <summary class="seccion-titulo">@include('partials.icon', ['name' => 'coins']) Equipo</summary>

            @if($personaje->equipo && $personaje->equipo->count() > 0)
            <div class="equipo-existente">
                @foreach($personaje->equipo as $item)
                <div class="equipo-item">
                    <span class="equipo-nombre">{{ $item->nombre }}
                        @if($item->equipado) <span class="ed-equipado">✓</span> @endif
                    </span>
                    <div class="equipo-det">{{ $item->tipo }}@if($item->magico) · ✨ Mágico @endif</div>
                    @if($item->cantidad > 1) <div class="equipo-det">×{{ $item->cantidad }}</div> @endif
                    @if($item->valor_po)     <div class="equipo-det">💰 {{ $item->valor_po }} PO</div> @endif
                    <form action="{{ route('equipo.destroy', ['personaje' => $personaje, 'equipo' => $item]) }}" method="POST" class="ed-en-linea">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del-equipo"
                                data-confirm="¿Eliminar {{ $item->nombre }}?">✕</button>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <p class="ed-vacio">No hay equipo todavía.</p>
            @endif

            <div class="form-equipo">
                <h4>@include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir objeto</h4>
                <form action="{{ route('equipo.store', $personaje) }}" method="POST">
                    @csrf
                    <div class="equipo-inputs">
                        <input type="text" name="nombre" placeholder="Nombre *" required>
                        <input type="text" name="tipo" placeholder="Tipo (arma, armadura, objeto...)" required>
                        <input type="text" name="rareza" placeholder="Rareza (común, poco común, raro...)">
                        <input type="number" name="cantidad" placeholder="Cantidad" value="1" min="1">
                        <input type="number" name="valor_po" placeholder="Valor en PO" min="0">
                        <input type="number" name="peso" placeholder="Peso (lb)" step="0.1" min="0">
                        <textarea name="descripcion" placeholder="Descripción breve..." rows="2" class="full"></textarea>
                        <div class="checks">
                            <label class="check-item">
                                <input type="checkbox" name="magico" value="1"> <span>✨ Mágico</span>
                            </label>
                            <label class="check-item">
                                <input type="checkbox" name="equipado" value="1"> <span>✓ Equipado</span>
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn-add-equipo">@include('partials.icon', ['name' => 'plus', 'class' => 'icon-sm']) Añadir al inventario</button>
                </form>
            </div>
        </details>

        {{-- ======= MONEDAS (formulario separado) ======= --}}
        <details class="seccion" open>
            <summary class="seccion-titulo">@include('partials.icon', ['name' => 'coins']) Tesoro</summary>
            <form action="{{ route('personajes.actualizar_monedas', $personaje) }}" method="POST">
                @csrf @method('PUT')
                @php $est = $personaje->estadisticas; @endphp
                <div class="monedas-form-grid">
                    @foreach(['cobre' => ['🟤', 'Cobre'], 'plata' => ['⚪', 'Plata'], 'electrum' => ['🟡', 'Electrum'], 'oro' => ['🟠', 'Oro'], 'platino' => ['⚫', 'Platino']] as $tipo => [$sim, $etiq])
                    <div class="moneda-field">
                        <span class="simbolo">{{ $sim }}</span>
                        <label>{{ $etiq }}</label>
                        <input type="number" name="monedas_{{ $tipo }}"
                               value="{{ old('monedas_' . $tipo, $est ? ($est->{'monedas_' . $tipo} ?? 0) : 0) }}" min="0">
                    </div>
                    @endforeach
                </div>
                <button type="submit" class="btn-monedas">@include('partials.icon', ['name' => 'coins', 'class' => 'icon-sm']) Actualizar monedas</button>
            </form>
        </details>
    </div>

    {{-- Formularios ocultos compartidos (fuera del form principal a propósito:
         HTML no permite anidar <form>, así que estos botones de borrado que viven
         dentro de .edit-body / dentro del form de edición se controlan por JS) --}}
    <form id="formEliminarImagen" action="{{ route('personajes.eliminarImagen', $personaje) }}" method="POST" hidden>
        @csrf
        <input type="hidden" name="tipo" id="eliminarImagenTipo">
        <input type="hidden" name="index" id="eliminarImagenIndex">
    </form>

    <form id="formEliminarTruco" action="" method="POST" hidden>
        @csrf
        @method('DELETE')
    </form>

    <form id="formEliminarDote" action="" method="POST" hidden>
        @csrf
        @method('DELETE')
    </form>

</div>{{-- /.edit-wrapper --}}

@endsection
