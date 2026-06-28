@extends('layouts.app')

@section('titulo', 'Editar ' . $personaje->nombre)

@section('contenido')
<style>
    .edit-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        background: linear-gradient(145deg, rgba(20, 10, 5, 0.95), rgba(40, 20, 10, 0.98));
        border-radius: 16px;
        border: 1px solid rgba(179, 3, 3, 0.25);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
        overflow: hidden;
    }

    .edit-header {
        display: flex;
        flex-wrap: wrap;
        background: rgba(0, 0, 0, 0.5);
        border-bottom: 2px solid var(--color-rojo);
        padding: 1.5rem 2rem;
        gap: 2rem;
        align-items: center;
    }

    .edit-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--color-rojo);
        background: #1a0a0a;
        flex-shrink: 0;
        box-shadow: 0 0 30px rgba(179, 3, 3, 0.3);
    }

    .edit-titulo {
        flex: 1;
    }

    .edit-titulo h1 {
        font-size: 2.8rem;
        color: #fff;
        margin: 0;
        letter-spacing: 2px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    .edit-titulo .subtitulo {
        color: var(--color-naranja);
        font-size: 1.2rem;
        margin: 0.2rem 0 0.5rem;
    }

    .edit-body {
        padding: 2rem 2.5rem;
    }

    .seccion {
        margin-bottom: 2.5rem;
    }

    .seccion-titulo {
        color: var(--color-gris);
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 2px;
        border-bottom: 1px solid rgba(118, 133, 150, 0.15);
        padding-bottom: 0.6rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .seccion-titulo .icono {
        font-size: 1.2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        color: var(--color-gris);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 0.4rem;
        font-weight: 600;
    }

    .form-group .required {
        color: var(--color-rojo);
        margin-left: 0.2rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 8px;
        color: #fff;
        font-family: inherit;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--color-rojo);
        box-shadow: 0 0 0 3px rgba(179, 3, 3, 0.15);
    }

    .form-control.error {
        border-color: #cc0000;
    }

    .error-text {
        color: #cc0000;
        font-size: 0.85rem;
        margin-top: 0.3rem;
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2.5rem;
        justify-content: flex-end;
        border-top: 1px solid rgba(118, 133, 150, 0.15);
        padding-top: 2rem;
    }

    .btn-submit {
        background: var(--color-rojo);
        color: #fff;
        padding: 0.8rem 2.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s;
        font-family: inherit;
    }

    .btn-submit:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(179, 3, 3, 0.3);
    }

    .btn-cancel {
        background: rgba(255, 255, 255, 0.06);
        color: var(--color-gris);
        padding: 0.8rem 2rem;
        border: 1px solid var(--color-gris);
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.3s;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-cancel:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--color-rojo);
        margin-top: 0.8rem;
        background: #1a0a0a;
    }

    /* Estadísticas en edición (igual que en ver pero con inputs) */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 1.2rem;
    }

    .stat-edit {
        background: rgba(0, 0, 0, 0.35);
        border-radius: 10px;
        padding: 1rem 0.8rem;
        text-align: center;
        border: 1px solid rgba(118, 133, 150, 0.1);
    }

    .stat-edit .label {
        color: var(--color-gris);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
    }

    .stat-edit input {
        width: 80%;
        margin: 0.3rem auto 0;
        padding: 0.4rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 6px;
        color: #fff;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 700;
        display: block;
    }

    .stat-edit input:focus {
        border-color: var(--color-rojo);
        outline: none;
    }

    .stat-edit .mod {
        color: var(--color-naranja);
        font-size: 0.9rem;
        font-weight: 600;
        margin-top: 0.2rem;
        display: block;
    }

    .info-grid-edit {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1.2rem;
    }

    .info-edit {
        background: rgba(0, 0, 0, 0.3);
        padding: 1rem 1.2rem;
        border-radius: 8px;
        border-left: 3px solid var(--color-rojo);
    }

    .info-edit label {
        color: var(--color-gris);
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }

    .info-edit input {
        width: 100%;
        padding: 0.4rem 0.6rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 6px;
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 0.2rem;
    }

    .info-edit input:focus {
        border-color: var(--color-rojo);
        outline: none;
    }

    .historia-edit textarea {
        width: 100%;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 8px;
        color: #c8c8c8;
        font-family: inherit;
        font-size: 1rem;
        line-height: 1.7;
        resize: vertical;
        min-height: 120px;
    }

    .historia-edit textarea:focus {
        border-color: var(--color-rojo);
        outline: none;
    }

    /* Equipo en edición (igual que en ver pero con formulario de añadir y eliminar) */
    .equipo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1rem;
    }

    .equipo-item {
        background: rgba(0, 0, 0, 0.3);
        padding: 1rem 1.2rem;
        border-radius: 8px;
        border-left: 3px solid var(--color-naranja);
        position: relative;
        transition: all 0.3s;
    }

    .equipo-item:hover {
        background: rgba(0, 0, 0, 0.45);
    }

    .equipo-item .nombre {
        color: #fff;
        font-weight: 600;
        font-size: 1.05rem;
    }
    .equipo-item .detalle {
        color: var(--color-gris);
        font-size: 0.85rem;
        margin-top: 0.2rem;
    }
    .equipo-item .badge-equipado {
        color: var(--color-verde);
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    .equipo-item .eliminar-btn {
        position: absolute;
        top: 0.5rem;
        right: 0.8rem;
        background: none;
        border: none;
        color: #6b1a1a;
        cursor: pointer;
        font-size: 1.2rem;
        transition: color 0.3s;
    }
    .equipo-item .eliminar-btn:hover {
        color: #cc0000;
    }

    .form-equipo {
        background: rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1.5rem;
        border: 1px solid rgba(118, 133, 150, 0.1);
    }

    .form-equipo .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-equipo .grid .full-width {
        grid-column: 1 / -1;
    }

    .form-equipo .grid .checkbox-group {
        grid-column: 1 / -1;
        display: flex;
        gap: 1.5rem;
    }

    .form-equipo .grid .checkbox-group label {
        color: var(--color-gris);
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .form-equipo .grid input,
    .form-equipo .grid textarea {
        width: 100%;
        padding: 0.6rem 0.8rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 6px;
        color: #fff;
        font-family: inherit;
        font-size: 0.95rem;
    }

    .form-equipo .grid input:focus,
    .form-equipo .grid textarea:focus {
        border-color: var(--color-rojo);
        outline: none;
    }

    .btn-agregar-equipo {
        background: var(--color-verde);
        color: #fff;
        padding: 0.5rem 1.5rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-family: inherit;
        margin-top: 1rem;
    }

    .btn-agregar-equipo:hover {
        background: #2a4a2a;
        transform: translateY(-2px);
    }

    /* Monedas en edición */
    .monedas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 1rem;
    }

    .moneda-edit {
        background: rgba(0, 0, 0, 0.25);
        padding: 0.8rem;
        border-radius: 8px;
        text-align: center;
    }

    .moneda-edit .simbolo {
        font-size: 1.4rem;
        display: block;
    }

    .moneda-edit input {
        width: 80%;
        padding: 0.3rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 6px;
        color: #fff;
        text-align: center;
        font-size: 1.2rem;
        font-weight: 600;
        display: block;
        margin: 0.2rem auto 0;
    }

    .moneda-edit input:focus {
        border-color: var(--color-rojo);
        outline: none;
    }

    .form-moneda {
        background: rgba(0, 0, 0, 0.15);
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1.5rem;
        border: 1px solid rgba(118, 133, 150, 0.1);
    }

    .form-moneda .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 1rem;
    }

    .form-moneda .grid label {
        color: var(--color-gris);
        font-size: 0.75rem;
        text-transform: uppercase;
        display: block;
        margin-bottom: 0.2rem;
    }

    .form-moneda .grid input {
        width: 100%;
        padding: 0.5rem;
        background: rgba(0, 0, 0, 0.3);
        border: 1px solid var(--color-gris);
        border-radius: 6px;
        color: #fff;
        font-size: 1rem;
    }

    .form-moneda .grid input:focus {
        border-color: var(--color-rojo);
        outline: none;
    }

    .btn-actualizar-moneda {
        background: var(--color-rojo);
        color: #fff;
        padding: 0.6rem 2rem;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-family: inherit;
        margin-top: 1rem;
    }

    .btn-actualizar-moneda:hover {
        background: #8a0202;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(179, 3, 3, 0.3);
    }

    @media (max-width: 768px) {
        .edit-header {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }
        .edit-avatar {
            width: 120px;
            height: 120px;
        }
        .edit-titulo h1 {
            font-size: 2rem;
        }
        .edit-body {
            padding: 1.5rem;
        }
        .form-row {
            grid-template-columns: 1fr;
        }
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .info-grid-edit {
            grid-template-columns: 1fr 1fr;
        }
        .form-equipo .grid {
            grid-template-columns: 1fr;
        }
        .form-equipo .grid .full-width {
            grid-column: 1;
        }
        .form-equipo .grid .checkbox-group {
            grid-column: 1;
            flex-wrap: wrap;
        }
        .form-actions {
            flex-direction: column;
        }
        .form-actions .btn-submit,
        .form-actions .btn-cancel {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .info-grid-edit {
            grid-template-columns: 1fr;
        }
        .equipo-grid {
            grid-template-columns: 1fr;
        }
        .monedas-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>

<div class="edit-wrapper">
    <!-- CABECERA -->
    <div class="edit-header">
        <img class="edit-avatar" src="{{ $personaje->avatar_url }}" alt="{{ $personaje->nombre }}">

        <div class="edit-titulo">
            <h1>{{ $personaje->nombre }}</h1>
            <div class="subtitulo">
                {{ $personaje->raza->nombre ?? 'Raza' }} · {{ $personaje->clase->nombre ?? 'Clase' }}
                @if($personaje->subclase)
                    ({{ $personaje->subclase->nombre }})
                @endif
            </div>
            <div style="display:flex; flex-wrap:wrap; gap:0.5rem; align-items:center; margin-top:0.3rem;">
                <span class="badge-nivel" style="background:var(--color-rojo); color:#fff; padding:0.3rem 1.2rem; border-radius:20px; font-weight:bold; font-size:0.95rem;">Nivel {{ $personaje->nivel }}</span>
                @if($personaje->experiencia)
                    <span style="color:var(--color-gris); font-size:0.9rem;">⚡ {{ number_format($personaje->experiencia) }} XP</span>
                @endif
                @if($personaje->alineamiento)
                    <span style="background:rgba(255,255,255,0.08); padding:0.3rem 1rem; border-radius:20px; color:var(--color-gris); font-size:0.85rem; border:1px solid rgba(118,133,150,0.2);">⚖️ {{ $personaje->alineamiento }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- CUERPO DEL FORMULARIO -->
    <form action="{{ route('personajes.update', $personaje) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="edit-body">

            <!-- ====== INFORMACIÓN BÁSICA ====== -->
            <div class="seccion">
                <div class="seccion-titulo"><span class="icono">📋</span> Información Básica</div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre <span class="required">*</span></label>
                        <input type="text" name="nombre" class="form-control @error('nombre') error @enderror" value="{{ old('nombre', $personaje->nombre) }}" required>
                        @error('nombre') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nivel <span class="required">*</span></label>
                        <input type="number" name="nivel" class="form-control @error('nivel') error @enderror" value="{{ old('nivel', $personaje->nivel) }}" min="1" max="20" required>
                        @error('nivel') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Raza <span class="required">*</span></label>
                        <select name="raza_id" class="form-control @error('raza_id') error @enderror" required>
                            <option value="">Selecciona una raza</option>
                            @foreach($razas as $raza)
                                <option value="{{ $raza->id }}" {{ old('raza_id', $personaje->raza_id) == $raza->id ? 'selected' : '' }}>
                                    {{ $raza->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('raza_id') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label>Clase <span class="required">*</span></label>
                        <select name="clase_id" class="form-control @error('clase_id') error @enderror" required>
                            <option value="">Selecciona una clase</option>
                            @foreach($clases as $clase)
                                <option value="{{ $clase->id }}" {{ old('clase_id', $personaje->clase_id) == $clase->id ? 'selected' : '' }}>
                                    {{ $clase->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('clase_id') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Avatar (URL)</label>
                        <input type="text" name="avatar" class="form-control @error('avatar') error @enderror" placeholder="https://ejemplo.com/imagen.jpg" value="{{ old('avatar', $personaje->avatar) }}">
                        @error('avatar') <span class="error-text">{{ $message }}</span> @enderror
                        @if($personaje->avatar)
                            <img src="{{ $personaje->avatar }}" alt="Avatar actual" class="avatar-preview">
                        @endif
                    </div>
                    <div class="form-group">
                        <label>Alineamiento</label>
                        <select name="alineamiento" class="form-control @error('alineamiento') error @enderror">
                            <option value="">Sin alineamiento</option>
                            @foreach(['Legal Bueno','Neutral Bueno','Caótico Bueno','Legal Neutral','Neutral','Caótico Neutral','Legal Malvado','Neutral Malvado','Caótico Malvado'] as $al)
                                <option value="{{ $al }}" {{ old('alineamiento', $personaje->alineamiento) == $al ? 'selected' : '' }}>{{ $al }}</option>
                            @endforeach
                        </select>
                        @error('alineamiento') <span class="error-text">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group historia-edit">
                    <label>Historia del personaje</label>
                    <textarea name="historia" class="form-control @error('historia') error @enderror" rows="5" placeholder="Cuenta la historia de tu héroe...">{{ old('historia', $personaje->historia) }}</textarea>
                    @error('historia') <span class="error-text">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- ====== ESTADÍSTICAS ====== -->
            <div class="seccion">
                <div class="seccion-titulo"><span class="icono">🎯</span> Características</div>
                <div class="stats-grid">
                    @php
                        $stats = ['FUE'=>'fuerza','DES'=>'destreza','CON'=>'constitucion','INT'=>'inteligencia','SAB'=>'sabiduria','CAR'=>'carisma'];
                        $est = $personaje->estadisticas ?? null;
                    @endphp
                    @foreach($stats as $label => $attr)
                        @php
                            $valor = old($attr, $est ? ($est->$attr ?? 10) : 10);
                            $mod = floor(($valor - 10) / 2);
                            $modStr = $mod >= 0 ? '+' . $mod : $mod;
                        @endphp
                        <div class="stat-edit">
                            <span class="label">{{ $label }}</span>
                            <input type="number" name="{{ $attr }}" value="{{ $valor }}" min="1" max="30" required>
                            <span class="mod">{{ $modStr }}</span>
                            @error($attr) <span class="error-text">{{ $message }}</span> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ====== COMBATE ====== -->
            @if($est)
            <div class="seccion">
                <div class="seccion-titulo"><span class="icono">⚔️</span> Combate</div>
                <div class="info-grid-edit">
                    <div class="info-edit">
                        <label>❤️ Puntos de Golpe</label>
                        <input type="number" name="pg_actuales" value="{{ old('pg_actuales', $est->pg_actuales ?? 10) }}" min="0">
                        <input type="number" name="pg_maximos" value="{{ old('pg_maximos', $est->pg_maximos ?? 10) }}" min="1" style="margin-top:0.3rem;" placeholder="Máximos">
                    </div>
                    <div class="info-edit">
                        <label>🛡️ Clase de Armadura</label>
                        <input type="number" name="clase_de_armadura" value="{{ old('clase_de_armadura', $est->clase_de_armadura ?? 10) }}" min="0">
                    </div>
                    <div class="info-edit">
                        <label>⚡ Velocidad</label>
                        <input type="number" name="velocidad" value="{{ old('velocidad', $est->velocidad ?? 30) }}" min="0">
                    </div>
                    <div class="info-edit">
                        <label>🎯 Bonus Competencia</label>
                        <input type="number" name="bonus_competencia" value="{{ old('bonus_competencia', $est->bonus_competencia ?? 2) }}" min="0" max="6">
                    </div>
                    <div class="info-edit">
                        <label>⚔️ Iniciativa</label>
                        <input type="number" name="iniciativa" value="{{ old('iniciativa', $est->iniciativa ?? 0) }}" min="0" max="20">
                    </div>
                </div>
            </div>
            @endif

            <!-- ====== EQUIPO (editable) ====== -->
            <div class="seccion">
                <div class="seccion-titulo"><span class="icono">⚔️</span> Equipo</div>

                @if($personaje->equipo && $personaje->equipo->count() > 0)
                    <div class="equipo-grid">
                        @foreach($personaje->equipo as $item)
                            <div class="equipo-item">
                                <span class="nombre">{{ $item->nombre }}</span>
                                @if($item->equipado)
                                    <span class="badge-equipado">✓ Equipado</span>
                                @endif
                                <div class="detalle">{{ $item->tipo }} @if($item->magico) ✨ Mágico @endif</div>
                                @if($item->cantidad > 1) <div class="detalle">x{{ $item->cantidad }}</div> @endif
                                @if($item->valor_po) <div class="detalle">💰 {{ $item->valor_po }} PO</div> @endif
                                @if($item->peso) <div class="detalle">⚖️ {{ $item->peso }} lb</div> @endif
                                @if($item->descripcion) <div class="detalle" style="font-size:0.8rem;margin-top:0.3rem;">{{ $item->descripcion }}</div> @endif
                                <form action="{{ route('equipo.destroy', ['personaje' => $personaje, 'equipo' => $item]) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="eliminar-btn" onclick="return confirm('¿Eliminar {{ $item->nombre }}?')">✕</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: var(--color-gris);">No tiene equipo.</p>
                @endif

                <!-- Formulario para añadir equipo -->
                <div class="form-equipo">
                    <h4 style="color:var(--color-gris); margin:0 0 1rem 0;">➕ Añadir equipo</h4>
                    <form action="{{ route('equipo.store', $personaje) }}" method="POST">
                        @csrf
                        <div class="grid">
                            <input type="text" name="nombre" placeholder="Nombre *" required>
                            <input type="text" name="tipo" placeholder="Tipo (arma, armadura...)" required>
                            <input type="text" name="rareza" placeholder="Rareza (común, raro...)">
                            <input type="number" name="cantidad" placeholder="Cantidad" value="1" min="1">
                            <input type="number" name="valor_po" placeholder="Valor en PO">
                            <input type="number" name="peso" placeholder="Peso (lb)" step="0.01">
                            <textarea name="descripcion" placeholder="Descripción" rows="2" class="full-width"></textarea>
                            <div class="checkbox-group">
                                <label><input type="checkbox" name="magico" value="1"> Mágico</label>
                                <label><input type="checkbox" name="equipado" value="1"> Equipado</label>
                            </div>
                        </div>
                        <button type="submit" class="btn-agregar-equipo">➕ Añadir</button>
                    </form>
                </div>
            </div>

            <!-- ====== MONEDAS (editable) ====== -->
            <div class="seccion">
                <div class="seccion-titulo"><span class="icono">💰</span> Tesoro</div>

                @if($est)
                    <div class="monedas-grid">
                        <div class="moneda-edit">
                            <span class="simbolo">🟤</span>
                            <input type="number" name="monedas_cobre_show" value="{{ $est->monedas_cobre ?? 0 }}" disabled style="opacity:0.6; cursor:not-allowed;">
                        </div>
                        <div class="moneda-edit">
                            <span class="simbolo">⚪</span>
                            <input type="number" name="monedas_plata_show" value="{{ $est->monedas_plata ?? 0 }}" disabled style="opacity:0.6; cursor:not-allowed;">
                        </div>
                        <div class="moneda-edit">
                            <span class="simbolo">🟡</span>
                            <input type="number" name="monedas_electrum_show" value="{{ $est->monedas_electrum ?? 0 }}" disabled style="opacity:0.6; cursor:not-allowed;">
                        </div>
                        <div class="moneda-edit">
                            <span class="simbolo">🟠</span>
                            <input type="number" name="monedas_oro_show" value="{{ $est->monedas_oro ?? 0 }}" disabled style="opacity:0.6; cursor:not-allowed;">
                        </div>
                        <div class="moneda-edit">
                            <span class="simbolo">⚫</span>
                            <input type="number" name="monedas_platino_show" value="{{ $est->monedas_platino ?? 0 }}" disabled style="opacity:0.6; cursor:not-allowed;">
                        </div>
                    </div>

                    <div class="form-moneda">
                        <h4 style="color:var(--color-gris); margin:0 0 1rem 0;">💱 Actualizar monedas</h4>
                        <form action="{{ route('personajes.actualizar_monedas', $personaje) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="grid">
                                <div>
                                    <label>Cobre</label>
                                    <input type="number" name="monedas_cobre" value="{{ old('monedas_cobre', $est->monedas_cobre ?? 0) }}" min="0">
                                </div>
                                <div>
                                    <label>Plata</label>
                                    <input type="number" name="monedas_plata" value="{{ old('monedas_plata', $est->monedas_plata ?? 0) }}" min="0">
                                </div>
                                <div>
                                    <label>Electrum</label>
                                    <input type="number" name="monedas_electrum" value="{{ old('monedas_electrum', $est->monedas_electrum ?? 0) }}" min="0">
                                </div>
                                <div>
                                    <label>Oro</label>
                                    <input type="number" name="monedas_oro" value="{{ old('monedas_oro', $est->monedas_oro ?? 0) }}" min="0">
                                </div>
                                <div>
                                    <label>Platino</label>
                                    <input type="number" name="monedas_platino" value="{{ old('monedas_platino', $est->monedas_platino ?? 0) }}" min="0">
                                </div>
                            </div>
                            <button type="submit" class="btn-actualizar-moneda">💾 Actualizar monedas</button>
                        </form>
                    </div>
                @else
                    <p style="color:var(--color-gris);">No hay información de monedas.</p>
                @endif
            </div>

            <!-- ====== BOTONES DE ACCIÓN ====== -->
            <div class="form-actions">
                <a href="{{ route('personajes.show', $personaje) }}" class="btn-cancel">❌ Cancelar</a>
                <button type="submit" class="btn-submit">💾 Actualizar Personaje</button>
            </div>

        </div><!-- /.edit-body -->
    </form>
</div><!-- /.edit-wrapper -->
@endsection