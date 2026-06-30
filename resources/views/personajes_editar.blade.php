@extends('layouts.app')

@section('titulo', 'Editar — ' . $personaje->nombre)

@section('contenido')
<style>
    /* =============================================
       WRAPPER
    ============================================= */
    .edit-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        background: linear-gradient(150deg, rgba(18,8,4,0.97), rgba(36,16,8,0.99));
        border-radius: 14px;
        border: 1px solid rgba(179,3,3,0.22);
        box-shadow: 0 20px 60px rgba(0,0,0,0.75);
        overflow: hidden;
    }

    /* =============================================
       CABECERA
    ============================================= */
    .edit-header {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        align-items: center;
        background: rgba(0,0,0,0.5);
        border-bottom: 2px solid #B30303;
        padding: 1.8rem 2.2rem;
    }

    .edit-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #B30303;
        background: #1a0a0a;
        flex-shrink: 0;
        box-shadow: 0 0 28px rgba(179,3,3,0.3);
    }

    .edit-header-info { flex: 1; min-width: 200px; }

    .edit-header-info h1 {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: clamp(1.6rem, 3.5vw, 2.4rem);
        color: #fff;
        margin: 0 0 0.3rem;
        letter-spacing: 2px;
    }

    .edit-header-info .subtitulo {
        color: #D46043;
        font-size: 1rem;
        font-style: italic;
    }

    /* =============================================
       CUERPO
    ============================================= */
    .edit-body { padding: 2rem 2.5rem; }

    /* SECCIÓN */
    .seccion { margin-bottom: 2.8rem; }

    .seccion-titulo {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        color: #768596;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 2.5px;
        border-bottom: 1px solid rgba(118,133,150,0.12);
        padding-bottom: 0.6rem;
        margin-bottom: 1.5rem;
    }

    /* =============================================
       FORMULARIO
    ============================================= */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.3rem;
    }

    .form-row-3 {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.3rem;
    }

    .form-group { margin-bottom: 1.2rem; }

    .form-group label {
        display: block;
        color: #768596;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.4rem;
        font-weight: 600;
    }

    .req { color: #B30303; margin-left: 0.2rem; }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        background: rgba(0,0,0,0.28);
        border: 1px solid rgba(118,133,150,0.3);
        border-radius: 6px;
        color: #fff;
        font-family: inherit;
        font-size: 0.97rem;
        transition: border-color 0.25s;
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: #B30303;
        box-shadow: 0 0 0 3px rgba(179,3,3,0.12);
    }

    .form-control.is-error { border-color: #cc0000; }

    .error-msg {
        color: #cc4444;
        font-size: 0.8rem;
        margin-top: 0.3rem;
        display: block;
    }

    select.form-control option { background: #1a0808; color: #fff; }

    .avatar-preview {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #B30303;
        margin-top: 0.6rem;
    }

    /* =============================================
       UPLOAD DE IMÁGENES
    ============================================= */
    .upload-zone {
        border: 2px dashed rgba(179,3,3,0.3);
        border-radius: 8px;
        padding: 1.4rem;
        text-align: center;
        background: rgba(0,0,0,0.15);
        transition: border-color 0.25s;
        cursor: pointer;
    }

    .upload-zone:hover { border-color: rgba(179,3,3,0.6); }

    .upload-zone input[type="file"] {
        display: none;
    }

    .upload-label {
        color: #768596;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .upload-label span { color: #D46043; text-decoration: underline; }

    .imgs-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
        margin-top: 0.8rem;
    }

    .img-existente {
        position: relative;
        width: 100px;
        height: 100px;
    }

    .img-existente img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid rgba(179,3,3,0.25);
    }

    .img-existente .btn-del-img {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 22px;
        height: 22px;
        background: #B30303;
        border: none;
        border-radius: 50%;
        color: #fff;
        font-size: 0.75rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        line-height: 1;
        transition: background 0.2s;
    }

    .img-existente .btn-del-img:hover { background: #8a0202; }

    .contador-imgs {
        color: #768596;
        font-size: 0.8rem;
        margin-top: 0.4rem;
    }

    /* =============================================
       ESTADÍSTICAS (grid con inputs)
    ============================================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 1rem;
    }

    .stat-edit {
        background: rgba(0,0,0,0.35);
        border-radius: 10px;
        padding: 1rem 0.8rem;
        text-align: center;
        border: 1px solid rgba(118,133,150,0.1);
        transition: border-color 0.25s;
    }

    .stat-edit:focus-within { border-color: rgba(179,3,3,0.4); }

    .stat-label {
        color: #768596;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 0.3rem;
    }

    .stat-input {
        width: 75%;
        padding: 0.4rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(118,133,150,0.25);
        border-radius: 5px;
        color: #fff;
        text-align: center;
        font-size: 1.3rem;
        font-weight: 700;
        display: block;
        margin: 0 auto;
    }

    .stat-input:focus { border-color: #B30303; outline: none; }

    .stat-mod {
        color: #D46043;
        font-size: 0.88rem;
        font-weight: 600;
        margin-top: 0.3rem;
        display: block;
    }

    /* =============================================
       COMBATE
    ============================================= */
    .combat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(155px, 1fr));
        gap: 1rem;
    }

    .combat-field {
        background: rgba(0,0,0,0.3);
        padding: 1rem 1.2rem;
        border-radius: 8px;
        border-left: 3px solid #B30303;
    }

    .combat-field label {
        color: #768596;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.3rem;
        font-weight: 600;
    }

    .combat-field input {
        width: 100%;
        padding: 0.4rem 0.6rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(118,133,150,0.25);
        border-radius: 5px;
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .combat-field input:focus { border-color: #B30303; outline: none; }

    /* check-grid / check-item: estilos globales en app.css */

    /* =============================================
       ATAQUES
    ============================================= */
    .ataques-lista {
        display: flex;
        flex-direction: column;
        gap: 0.7rem;
        margin-bottom: 1rem;
    }

    .ataque-row {
        display: grid;
        grid-template-columns: 1fr 100px 1fr auto;
        gap: 0.6rem;
        align-items: center;
    }

    .ataque-row input {
        padding: 0.6rem 0.8rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(118,133,150,0.25);
        border-radius: 5px;
        color: #fff;
        font-size: 0.9rem;
        font-family: inherit;
    }

    .ataque-row input:focus { border-color: #B30303; outline: none; }

    .btn-del-ataque {
        background: none;
        border: 1px solid rgba(107,26,26,0.5);
        color: #cc4444;
        border-radius: 4px;
        width: 30px;
        height: 30px;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .btn-del-ataque:hover { background: rgba(179,3,3,0.15); }

    .btn-add-ataque {
        background: rgba(64,72,52,0.5);
        border: 1px solid #404834;
        color: #9ab090;
        padding: 0.5rem 1.2rem;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.88rem;
        font-family: inherit;
        transition: background 0.2s;
    }

    .btn-add-ataque:hover { background: rgba(64,72,52,0.8); }

    /* =============================================
       EQUIPO
    ============================================= */
    .equipo-existente {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 1rem;
        margin-bottom: 1.2rem;
    }

    .equipo-item {
        background: rgba(0,0,0,0.3);
        padding: 1rem 1.2rem;
        border-radius: 8px;
        border-left: 3px solid #D46043;
        position: relative;
    }

    .equipo-nombre { color: #fff; font-weight: 600; }
    .equipo-det    { color: #768596; font-size: 0.82rem; margin-top: 0.2rem; }

    .btn-del-equipo {
        position: absolute;
        top: 0.5rem;
        right: 0.7rem;
        background: none;
        border: none;
        color: rgba(179,3,3,0.5);
        cursor: pointer;
        font-size: 1.1rem;
        transition: color 0.2s;
        padding: 0;
    }

    .btn-del-equipo:hover { color: #B30303; }

    .form-equipo {
        background: rgba(0,0,0,0.15);
        border: 1px solid rgba(118,133,150,0.1);
        border-radius: 10px;
        padding: 1.4rem;
    }

    .form-equipo h4 {
        color: #768596;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
    }

    .equipo-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.9rem;
    }

    .equipo-inputs input,
    .equipo-inputs textarea,
    .equipo-inputs select {
        width: 100%;
        padding: 0.6rem 0.8rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(118,133,150,0.25);
        border-radius: 5px;
        color: #fff;
        font-family: inherit;
        font-size: 0.9rem;
    }

    .equipo-inputs input:focus,
    .equipo-inputs textarea:focus { border-color: #B30303; outline: none; }

    .equipo-inputs .full  { grid-column: 1 / -1; }
    .equipo-inputs .checks { grid-column: 1 / -1; display: flex; gap: 1.5rem; }

    .btn-add-equipo {
        background: #404834;
        color: #9ab090;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9rem;
        font-family: inherit;
        margin-top: 1rem;
        transition: background 0.2s;
        font-weight: 600;
    }

    .btn-add-equipo:hover { background: #2a3828; }

    /* =============================================
       MONEDAS
    ============================================= */
    .monedas-form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 1rem;
    }

    .moneda-field { text-align: center; }

    .moneda-field .simbolo {
        font-size: 1.5rem;
        display: block;
        margin-bottom: 0.3rem;
    }

    .moneda-field label {
        display: block;
        color: #768596;
        font-size: 0.7rem;
        text-transform: uppercase;
        margin-bottom: 0.3rem;
    }

    .moneda-field input {
        width: 80%;
        padding: 0.4rem;
        background: rgba(0,0,0,0.3);
        border: 1px solid rgba(118,133,150,0.25);
        border-radius: 5px;
        color: #fff;
        text-align: center;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .moneda-field input:focus { border-color: #B30303; outline: none; }

    .btn-monedas {
        background: #B30303;
        color: #fff;
        border: none;
        padding: 0.65rem 2rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.92rem;
        font-family: inherit;
        font-weight: 600;
        margin-top: 1rem;
        transition: background 0.2s;
    }

    .btn-monedas:hover { background: #8a0202; }

    /* =============================================
       ACCIONES FINALES
    ============================================= */
    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        border-top: 1px solid rgba(118,133,150,0.12);
        padding-top: 2rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.75rem 1.8rem;
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(118,133,150,0.25);
        border-radius: 6px;
        color: #768596;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.25s;
    }

    .btn-cancel:hover { background: rgba(255,255,255,0.1); color: #fff; }

    .btn-save {
        padding: 0.75rem 2.5rem;
        background: #B30303;
        border: none;
        border-radius: 6px;
        color: #fff;
        font-size: 0.97rem;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        letter-spacing: 0.5px;
        transition: all 0.25s;
    }

    .btn-save:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(179,3,3,0.35);
    }

    /* =============================================
       RESPONSIVE
    ============================================= */
    @media (max-width: 768px) {
        .edit-header { flex-direction: column; text-align: center; padding: 1.5rem; }
        .edit-body { padding: 1.5rem; }
        .form-row, .form-row-3 { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .combat-grid { grid-template-columns: 1fr 1fr; }
        .ataque-row { grid-template-columns: 1fr auto; }
        .ataque-row input:nth-child(2), .ataque-row input:nth-child(3) { display: none; }
        .equipo-inputs { grid-template-columns: 1fr; }
        .form-actions { flex-direction: column; }
        .form-actions > * { width: 100%; text-align: center; justify-content: center; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .combat-grid { grid-template-columns: 1fr; }
        .check-grid { grid-template-columns: 1fr; }
    }
</style>

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
            <div style="background:rgba(179,3,3,0.12);border:1px solid rgba(179,3,3,0.4);border-radius:6px;padding:1rem 1.3rem;margin-bottom:1.5rem;">
                <strong style="color:#cc4444">Errores de validación:</strong>
                <ul style="margin:.5rem 0 0 1.2rem;color:#cc4444;font-size:.9rem">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- ======= INFORMACIÓN BÁSICA ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'scroll']) Información Básica</div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre <span class="req">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') is-error @enderror"
                               value="{{ old('nombre', $personaje->nombre) }}" required>
                        @error('nombre') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nivel <span class="req">*</span></label>
                        <input type="number" name="nivel" class="form-control @error('nivel') is-error @enderror"
                               value="{{ old('nivel', $personaje->nivel) }}" min="1" max="20" required>
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
                        <select name="clase_id" class="form-control" required>
                            <option value="">— Selecciona una clase —</option>
                            @foreach($clases as $clase)
                            <option value="{{ $clase->id }}" {{ old('clase_id', $personaje->clase_id) == $clase->id ? 'selected' : '' }}>
                                {{ $clase->nombre }}
                            </option>
                            @endforeach
                        </select>
                    </div>
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
            </div>

            {{-- ======= IMÁGENES ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'image']) Imágenes</div>

                {{-- Imágenes del personaje --}}
                <div class="form-group">
                    <label>Imágenes del Personaje (máx. 5 — {{ count($imgsPersonaje) }}/5 actuales)</label>

                    @if(count($imgsPersonaje) > 0)
                    <div class="imgs-grid">
                        @foreach($imgsPersonaje as $i => $img)
                        <div class="img-existente">
                            <img src="{{ Storage::url($img) }}" alt="Imagen {{ $i+1 }}">
                            <form action="{{ route('personajes.eliminarImagen', $personaje) }}" method="POST" style="display:inline">
                                @csrf
                                <input type="hidden" name="tipo" value="personaje">
                                <input type="hidden" name="index" value="{{ $i }}">
                                <button type="submit" class="btn-del-img" onclick="return confirm('¿Eliminar imagen?')">✕</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if(count($imgsPersonaje) < 5)
                    <div class="upload-zone" onclick="document.getElementById('uploadPersonaje').click()">
                        <input type="file" id="uploadPersonaje" name="imagenes_personaje[]"
                               multiple accept="image/jpeg,image/png,image/webp"
                               onchange="previewImgs(this, 'previewP', {{ 5 - count($imgsPersonaje) }})">
                        <div class="upload-label">
                            📁 <span>Selecciona imágenes</span> — JPG, PNG, WEBP · Máx. 2MB por imagen
                        </div>
                    </div>
                    <div id="previewP" class="imgs-grid"></div>
                    @else
                    <p style="color:#768596;font-size:.85rem;margin-top:.4rem">Has alcanzado el límite de 5 imágenes. Elimina alguna para añadir más.</p>
                    @endif
                </div>

                {{-- Imágenes de armas --}}
                <div class="form-group">
                    <label>Imágenes de Armas (máx. 5 — {{ count($imgsArmas) }}/5 actuales)</label>

                    @if(count($imgsArmas) > 0)
                    <div class="imgs-grid">
                        @foreach($imgsArmas as $i => $img)
                        <div class="img-existente">
                            <img src="{{ Storage::url($img) }}" alt="Arma {{ $i+1 }}">
                            <form action="{{ route('personajes.eliminarImagen', $personaje) }}" method="POST" style="display:inline">
                                @csrf
                                <input type="hidden" name="tipo" value="arma">
                                <input type="hidden" name="index" value="{{ $i }}">
                                <button type="submit" class="btn-del-img" onclick="return confirm('¿Eliminar imagen?')">✕</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @if(count($imgsArmas) < 5)
                    <div class="upload-zone" onclick="document.getElementById('uploadArmas').click()">
                        <input type="file" id="uploadArmas" name="imagenes_armas[]"
                               multiple accept="image/jpeg,image/png,image/webp"
                               onchange="previewImgs(this, 'previewA', {{ 5 - count($imgsArmas) }})">
                        <div class="upload-label">
                            ⚔️ <span>Selecciona imágenes de armas</span> — JPG, PNG, WEBP · Máx. 2MB
                        </div>
                    </div>
                    <div id="previewA" class="imgs-grid"></div>
                    @endif
                </div>
            </div>

            {{-- ======= ESTADÍSTICAS ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'star']) Características</div>
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
                        @error($attr) <span class="error-msg" style="font-size:.72rem">{{ $message }}</span> @enderror
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ======= TIRADAS DE SALVACIÓN ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'shield']) Competencia en Tiradas de Salvación</div>
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
            </div>

            {{-- ======= HABILIDADES ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Competencia en Habilidades</div>
                <div class="check-grid">
                    @foreach($habilidades as $nombre => $base)
                    <label class="check-item">
                        <input type="checkbox" name="competencias_habilidades[]"
                               value="{{ $nombre }}"
                               {{ in_array($nombre, $compHab) ? 'checked' : '' }}>
                        <span>{{ $nombre }} <span style="opacity:.5;font-size:.8em">({{ strtoupper(substr($base,0,3)) }})</span></span>
                    </label>
                    @endforeach
                </div>
                <input type="hidden" name="competencias_habilidades"
                       id="hiddenHab" value="{{ old('competencias_habilidades', $personaje->competencias_habilidades ?? '[]') }}">
            </div>

            {{-- ======= COMBATE ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'sword']) Combate</div>
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
                        <label>🎯 Bonus Competencia</label>
                        <input type="number" name="bonus_competencia"
                               value="{{ old('bonus_competencia', $est->bonus_competencia ?? 2) }}" min="0" max="6">
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
                <div style="margin-top:1.2rem;display:flex;gap:2rem;flex-wrap:wrap;align-items:center">
                    <div>
                        <label style="color:#768596;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:.5rem">✔ Éxitos de muerte</label>
                        <input type="number" name="exitos_muerte" min="0" max="3"
                               value="{{ old('exitos_muerte', $est->exitos_muerte ?? 0) }}"
                               style="width:70px;padding:.4rem;background:rgba(0,0,0,.3);border:1px solid rgba(118,133,150,.25);border-radius:5px;color:#fff;text-align:center;font-size:1.1rem">
                    </div>
                    <div>
                        <label style="color:#768596;font-size:.75rem;text-transform:uppercase;letter-spacing:1px;display:block;margin-bottom:.5rem">✖ Fallos de muerte</label>
                        <input type="number" name="fallos_muerte" min="0" max="3"
                               value="{{ old('fallos_muerte', $est->fallos_muerte ?? 0) }}"
                               style="width:70px;padding:.4rem;background:rgba(0,0,0,.3);border:1px solid rgba(118,133,150,.25);border-radius:5px;color:#fff;text-align:center;font-size:1.1rem">
                    </div>
                    <div style="margin-top:auto">
                        <label class="check-item">
                            <input type="checkbox" name="inspiracion" value="1"
                                   {{ old('inspiracion', $est->inspiracion ?? false) ? 'checked' : '' }}>
                            <span>✨ Inspiración activa</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- ======= ARMAS Y ATAQUES ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'swords']) Armas y Ataques</div>
                <div style="font-size:.78rem;color:#768596;margin-bottom:.8rem">Nombre · Bonificador de ataque · Daño/Tipo</div>

                @if($personaje->equipo && $personaje->equipo->count() > 0)
                <div class="form-group" style="margin-bottom:.8rem">
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
            </div>

            {{-- ======= CONJUROS Y TRUCOS ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Conjuros y Trucos</div>

                @if($personaje->trucos && $personaje->trucos->count() > 0)
                <div class="equipo-existente" style="margin-bottom:1rem">
                    @foreach($personaje->trucos as $truco)
                    <div class="equipo-item">
                        <span class="equipo-nombre">{{ $truco->conjuro->nombre ?? $truco->nombre }}</span>
                        <div class="equipo-det">
                            {{ $truco->conjuro ? 'Nivel ' . $truco->conjuro->nivel . ' · ' . $truco->conjuro->escuela : 'Conjuro propio' }}
                        </div>
                        <form action="{{ route('trucos.destroy', ['personaje' => $personaje, 'truco' => $truco]) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del-equipo"
                                    onclick="return confirm('¿Eliminar {{ addslashes($truco->conjuro->nombre ?? $truco->nombre) }}?')">✕</button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <p style="color:#768596;margin-bottom:1rem">Este personaje no tiene conjuros ni trucos todavía.</p>
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
            </div>

            {{-- ======= PERSONALIDAD ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'user']) Personalidad</div>
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
            </div>

            {{-- ======= APARIENCIA ======= --}}
            <div class="seccion">
                <div class="seccion-titulo">@include('partials.icon', ['name' => 'helmet']) Apariencia</div>
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
            </div>

            {{-- BOTONES --}}
            <div class="form-actions">
                <a href="{{ route('personajes.show', $personaje) }}" class="btn-cancel">@include('partials.icon', ['name' => 'x', 'class' => 'icon-sm']) Cancelar</a>
                <button type="submit" class="btn-save">@include('partials.icon', ['name' => 'check', 'class' => 'icon-sm']) Guardar Personaje</button>
            </div>

        </div>{{-- /.edit-body --}}
    </form>

    {{-- ======= EQUIPO (formulario separado) ======= --}}
    <div class="edit-body" style="padding-top:0">
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'coins']) Equipo</div>

            @if($personaje->equipo && $personaje->equipo->count() > 0)
            <div class="equipo-existente">
                @foreach($personaje->equipo as $item)
                <div class="equipo-item">
                    <span class="equipo-nombre">{{ $item->nombre }}
                        @if($item->equipado) <span style="color:#9ab090;font-size:.75rem;margin-left:.3rem">✓</span> @endif
                    </span>
                    <div class="equipo-det">{{ $item->tipo }}@if($item->magico) · ✨ Mágico @endif</div>
                    @if($item->cantidad > 1) <div class="equipo-det">×{{ $item->cantidad }}</div> @endif
                    @if($item->valor_po)     <div class="equipo-det">💰 {{ $item->valor_po }} PO</div> @endif
                    <form action="{{ route('equipo.destroy', ['personaje' => $personaje, 'equipo' => $item]) }}" method="POST" style="display:inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-del-equipo"
                                onclick="return confirm('¿Eliminar {{ addslashes($item->nombre) }}?')">✕</button>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:#768596;margin-bottom:1rem">No hay equipo todavía.</p>
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
        </div>

        {{-- ======= MONEDAS (formulario separado) ======= --}}
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'coins']) Tesoro</div>
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
        </div>
    </div>

</div>{{-- /.edit-wrapper --}}

<script>
/* ===== Modificador automático al cambiar stats ===== */
function actualizarMod(input) {
    const val  = parseInt(input.value) || 10;
    const mod  = Math.floor((val - 10) / 2);
    const str  = mod >= 0 ? '+' + mod : String(mod);
    const span = document.getElementById('mod-' + input.name);
    if (span) span.textContent = str;
}

/* ===== Preview de imágenes antes de subir ===== */
function previewImgs(input, containerId, max) {
    const container = document.getElementById(containerId);
    container.innerHTML = '';
    const files = Array.from(input.files).slice(0, max);

    // Actualizar el input con solo los archivos permitidos
    const dt = new DataTransfer();
    files.forEach(f => dt.items.add(f));
    input.files = dt.files;

    files.forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'img-existente';
            div.innerHTML = `<img src="${e.target.result}" alt="preview">`;
            container.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

/* ===== Añadir fila de ataque ===== */
function addAtaque() {
    const row = document.createElement('div');
    row.className = 'ataque-row';
    row.innerHTML = `
        <input type="text" name="ataque_nombre[]" placeholder="Nombre del ataque">
        <input type="text" name="ataque_bonif[]" placeholder="+5">
        <input type="text" name="ataque_dano[]" placeholder="1d8+3 cortante">
        <button type="button" class="btn-del-ataque" onclick="this.closest('.ataque-row').remove()">✕</button>
    `;
    document.getElementById('ataquesList').appendChild(row);
}

/* ===== Añadir ataque precargado desde un objeto del equipo ===== */
function addAtaqueDesdeEquipo(select) {
    if (!select.value) return;
    const row = document.createElement('div');
    row.className = 'ataque-row';
    row.innerHTML = `
        <input type="text" name="ataque_nombre[]" value="${select.value}" placeholder="Nombre del ataque">
        <input type="text" name="ataque_bonif[]" placeholder="+5">
        <input type="text" name="ataque_dano[]" placeholder="1d8+3 cortante">
        <button type="button" class="btn-del-ataque" onclick="this.closest('.ataque-row').remove()">✕</button>
    `;
    document.getElementById('ataquesList').appendChild(row);
    select.value = '';
}

/* ===== Serializar ataques antes de enviar ===== */
document.getElementById('formPersonaje').addEventListener('submit', function () {
    // Serializar ataques
    const nombres = [...document.querySelectorAll('input[name="ataque_nombre[]"]')].map(i => i.value);
    const bonifs  = [...document.querySelectorAll('input[name="ataque_bonif[]"]')].map(i => i.value);
    const danos   = [...document.querySelectorAll('input[name="ataque_dano[]"]')].map(i => i.value);

    const ataques = nombres.map((n, i) => ({
        nombre: n,
        bonificador: bonifs[i],
        daño: danos[i],
    })).filter(a => a.nombre);

    document.getElementById('ataquesHidden').value = JSON.stringify(ataques);

    // Serializar competencias habilidades
    const habChecked = [...document.querySelectorAll('input[name="competencias_habilidades[]"]:checked')].map(c => c.value);
    document.getElementById('hiddenHab').value = JSON.stringify(habChecked);
    document.querySelectorAll('input[name="competencias_habilidades[]"]').forEach(c => c.disabled = true);

    // Serializar competencias salvaciones
    const salChecked = [...document.querySelectorAll('input[name="competencias_salvaciones[]"]:checked')].map(c => c.value);
    document.getElementById('hiddenSal').value = JSON.stringify(salChecked);
    document.querySelectorAll('input[name="competencias_salvaciones[]"]').forEach(c => c.disabled = true);

    // Serializar conjuros nuevos seleccionados en el picker
    document.getElementById('conjurosNuevosHidden').value = JSON.stringify(conjurosSeleccionados);
});

/* ===== Picker de conjuros: click para seleccionar/deseleccionar ===== */
let conjurosSeleccionados = [];

function toggleConjuro(row) {
    const id = parseInt(row.dataset.id);
    const nombre = row.dataset.nombreMostrar;
    const idx = conjurosSeleccionados.indexOf(id);

    if (idx === -1) {
        conjurosSeleccionados.push(id);
        row.classList.add('seleccionado');
    } else {
        conjurosSeleccionados.splice(idx, 1);
        row.classList.remove('seleccionado');
    }

    renderChips();
}

function quitarConjuroChip(id) {
    conjurosSeleccionados = conjurosSeleccionados.filter(x => x !== id);
    const row = document.querySelector(`.conjuro-row[data-id="${id}"]`);
    if (row) row.classList.remove('seleccionado');
    renderChips();
}

function renderChips() {
    const cont = document.getElementById('conjuroChips');
    if (conjurosSeleccionados.length === 0) {
        cont.innerHTML = '<span class="conjuro-chips-vacio">Ningún conjuro seleccionado todavía — haz click en uno de la lista</span>';
        return;
    }
    cont.innerHTML = conjurosSeleccionados.map(id => {
        const row = document.querySelector(`.conjuro-row[data-id="${id}"]`);
        const nombre = row ? row.dataset.nombreMostrar : id;
        return `<span class="conjuro-chip">${nombre}<button type="button" onclick="quitarConjuroChip(${id})">✕</button></span>`;
    }).join('');
}

/* ===== Picker de conjuros: filtro de búsqueda ===== */
function filtrarConjuros(texto) {
    const t = texto.trim().toLowerCase();
    document.querySelectorAll('.conjuro-row').forEach(row => {
        row.style.display = row.dataset.nombre.includes(t) ? '' : 'none';
    });
    document.querySelectorAll('.conjuro-grupo').forEach(grupo => {
        const visibles = [...grupo.querySelectorAll('.conjuro-row')].some(r => r.style.display !== 'none');
        grupo.style.display = visibles ? '' : 'none';
    });
}
</script>
@endsection