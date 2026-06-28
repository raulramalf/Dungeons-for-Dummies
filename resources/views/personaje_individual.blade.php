@extends('layouts.app')

@section('titulo', $personaje->nombre . ' — Ficha')

@section('contenido')
<style>
    /* =============================================
       WRAPPER
    ============================================= */
    .ficha-wrapper {
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
    .ficha-header {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        align-items: center;
        background: rgba(0,0,0,0.5);
        border-bottom: 2px solid #B30303;
        padding: 1.8rem 2.2rem;
    }

    .ficha-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #B30303;
        background: #1a0a0a;
        flex-shrink: 0;
        box-shadow: 0 0 32px rgba(179,3,3,0.35);
    }

    .ficha-titulo { flex: 1; min-width: 200px; }

    .ficha-titulo h1 {
        font-family: 'Cinzel', 'Georgia', serif;
        font-size: clamp(1.8rem, 4vw, 2.8rem);
        color: #fff;
        margin: 0 0 0.2rem;
        letter-spacing: 2px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.6);
    }

    .ficha-subtitulo {
        color: #D46043;
        font-size: 1.1rem;
        margin-bottom: 0.6rem;
        font-style: italic;
    }

    .ficha-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .badge {
        display: inline-block;
        padding: 0.25rem 0.9rem;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .badge-nivel     { background: #B30303; color: #fff; }
    .badge-exp       { background: rgba(255,255,255,0.06); color: #768596; border: 1px solid rgba(118,133,150,0.2); }
    .badge-align     { background: rgba(255,255,255,0.05); color: #768596; border: 1px solid rgba(118,133,150,0.15); }
    .badge-divinidad { background: rgba(212,96,67,0.12); color: #D46043; border: 1px solid rgba(212,96,67,0.25); }

    .ficha-acciones {
        display: flex;
        gap: 0.6rem;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.5rem 1.2rem;
        border-radius: 5px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.25s;
        border: none;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-volver  { background: rgba(255,255,255,0.05); color: #768596; border: 1px solid rgba(118,133,150,0.25); }
    .btn-volver:hover  { background: rgba(255,255,255,0.1); color: #fff; }
    .btn-editar  { background: #D46043; color: #fff; }
    .btn-editar:hover  { background: #b84a30; transform: translateY(-1px); }
    .btn-eliminar { background: #6b1a1a; color: #fff; }
    .btn-eliminar:hover { background: #8a2222; transform: translateY(-1px); }

    /* =============================================
       CUERPO
    ============================================= */
    .ficha-body {
        padding: 2rem 2.5rem;
    }

    .seccion {
        margin-bottom: 2.5rem;
    }

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
        margin-bottom: 1.4rem;
    }

    /* =============================================
       GALERÍA DE IMÁGENES
    ============================================= */
    .galeria {
        display: flex;
        flex-wrap: wrap;
        gap: 0.8rem;
    }

    .galeria img {
        width: 130px;
        height: 130px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(179,3,3,0.25);
        cursor: pointer;
        transition: all 0.3s;
    }

    .galeria img:hover {
        border-color: #B30303;
        transform: scale(1.04);
        box-shadow: 0 6px 20px rgba(0,0,0,0.5);
    }

    /* Lightbox minimal */
    .lightbox {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.92);
        z-index: 1000;
        align-items: center;
        justify-content: center;
        cursor: zoom-out;
    }

    .lightbox.active { display: flex; }

    .lightbox img {
        max-width: 90vw;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 6px;
        border: 2px solid rgba(179,3,3,0.3);
    }

    /* =============================================
       ESTADÍSTICAS
    ============================================= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 1rem;
    }

    .stat-card {
        background: rgba(0,0,0,0.35);
        border-radius: 10px;
        padding: 1.2rem 0.8rem;
        text-align: center;
        border: 1px solid rgba(118,133,150,0.1);
        transition: all 0.25s;
    }

    .stat-card:hover {
        border-color: rgba(179,3,3,0.4);
        transform: translateY(-2px);
    }

    .stat-label {
        color: #768596;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
    }

    .stat-valor {
        font-size: 2.2rem;
        font-weight: 700;
        color: #fff;
        display: block;
        line-height: 1.2;
    }

    .stat-mod {
        color: #D46043;
        font-size: 0.92rem;
        font-weight: 600;
    }

    /* =============================================
       COMPETENCIAS
    ============================================= */
    .competencias-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 0.5rem;
    }

    .competencia-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.4rem 0.6rem;
        border-radius: 4px;
        font-size: 0.88rem;
    }

    .competencia-item.activa {
        background: rgba(64,72,52,0.35);
        color: #9ab090;
    }

    .competencia-item.inactiva {
        color: rgba(118,133,150,0.5);
    }

    .comp-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .comp-dot.activa  { background: #9ab090; }
    .comp-dot.inactiva { background: rgba(118,133,150,0.25); border: 1px solid rgba(118,133,150,0.3); }

    /* =============================================
       COMBATE / INFO CARDS
    ============================================= */
    .info-combat {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(145px, 1fr));
        gap: 1rem;
    }

    .combat-card {
        background: rgba(0,0,0,0.3);
        padding: 1rem 1.2rem;
        border-radius: 8px;
        border-left: 3px solid #B30303;
    }

    .combat-label {
        color: #768596;
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }

    .combat-valor {
        color: #fff;
        font-size: 1.35rem;
        font-weight: 700;
        margin-top: 0.15rem;
    }

    /* Muerte */
    .muerte-grid {
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .muerte-grupo label {
        color: #768596;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 0.5rem;
    }

    .muerte-dots {
        display: flex;
        gap: 0.5rem;
    }

    .muerte-dot {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        border: 2px solid rgba(118,133,150,0.3);
    }

    .muerte-dot.exito  { background: rgba(64,72,52,0.8); border-color: #404834; }
    .muerte-dot.fallo  { background: rgba(107,26,26,0.8); border-color: #6b1a1a; }

    /* =============================================
       ATAQUES
    ============================================= */
    .tabla-ataques {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .tabla-ataques th {
        color: #768596;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding: 0.6rem 0.8rem;
        border-bottom: 1px solid rgba(118,133,150,0.15);
        text-align: left;
        font-weight: 600;
    }

    .tabla-ataques td {
        padding: 0.7rem 0.8rem;
        color: #d0d5da;
        border-bottom: 1px solid rgba(118,133,150,0.07);
    }

    .tabla-ataques tr:hover td {
        background: rgba(179,3,3,0.04);
    }

    /* =============================================
       HISTORIA / RASGOS
    ============================================= */
    .historia-box {
        background: rgba(0,0,0,0.25);
        border-left: 4px solid #B30303;
        border-radius: 0 8px 8px 0;
        padding: 1.3rem 1.5rem;
        color: #b8c0c8;
        line-height: 1.75;
        font-style: italic;
    }

    .rasgos-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .rasgo-card {
        background: rgba(0,0,0,0.2);
        border-radius: 8px;
        padding: 1rem 1.2rem;
        border: 1px solid rgba(118,133,150,0.1);
    }

    .rasgo-label {
        color: #D46043;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: block;
        margin-bottom: 0.5rem;
        font-family: 'Cinzel', 'Georgia', serif;
    }

    .rasgo-texto {
        color: #a8b0b8;
        font-size: 0.92rem;
        line-height: 1.6;
    }

    /* =============================================
       APARIENCIA
    ============================================= */
    .apariencia-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 0.8rem;
    }

    .apariencia-item {
        background: rgba(0,0,0,0.2);
        padding: 0.7rem 1rem;
        border-radius: 6px;
    }

    .apariencia-label {
        color: #768596;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        display: block;
        margin-bottom: 0.2rem;
    }

    .apariencia-valor {
        color: #d0d5da;
        font-size: 0.92rem;
    }

    /* =============================================
       EQUIPO
    ============================================= */
    .equipo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 1rem;
    }

    .equipo-item {
        background: rgba(0,0,0,0.3);
        padding: 1rem 1.2rem;
        border-radius: 8px;
        border-left: 3px solid #D46043;
        transition: all 0.25s;
    }

    .equipo-item:hover { background: rgba(0,0,0,0.45); }

    .equipo-nombre { color: #fff; font-weight: 600; font-size: 1rem; }
    .equipo-det    { color: #768596; font-size: 0.83rem; margin-top: 0.2rem; }
    .badge-equipado { color: #9ab090; font-size: 0.75rem; font-weight: 600; margin-left: 0.4rem; }

    /* =============================================
       MONEDAS
    ============================================= */
    .monedas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 1rem;
    }

    .moneda-card {
        background: rgba(0,0,0,0.25);
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
    }

    .moneda-simbolo { font-size: 1.5rem; display: block; margin-bottom: 0.2rem; }
    .moneda-cantidad { color: #fff; font-size: 1.35rem; font-weight: 700; }
    .moneda-nombre  { color: #768596; font-size: 0.72rem; display: block; margin-top: 0.1rem; }

    /* =============================================
       RESPONSIVE
    ============================================= */
    @media (max-width: 768px) {
        .ficha-header { flex-direction: column; text-align: center; padding: 1.5rem; }
        .ficha-body { padding: 1.5rem; }
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
        .info-combat { grid-template-columns: 1fr 1fr; }
        .rasgos-grid { grid-template-columns: 1fr; }
    }

    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .info-combat { grid-template-columns: 1fr; }
        .equipo-grid { grid-template-columns: 1fr; }
        .monedas-grid { grid-template-columns: repeat(3, 1fr); }
    }
</style>

@php
    $est = $personaje->estadisticas;
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
    $compHab  = json_decode($personaje->competencias_habilidades ?? '[]', true) ?? [];
    $compSal  = json_decode($personaje->competencias_salvaciones ?? '[]', true) ?? [];
    $ataques  = json_decode($personaje->ataques ?? '[]', true) ?? [];
    $imgsPersonaje = json_decode($personaje->imagenes_personaje ?? '[]', true) ?? [];
    $imgsArmas     = json_decode($personaje->imagenes_armas ?? '[]', true) ?? [];
    $stats = ['FUE'=>'fuerza','DES'=>'destreza','CON'=>'constitucion','INT'=>'inteligencia','SAB'=>'sabiduria','CAR'=>'carisma'];
@endphp

<div class="ficha-wrapper">

    {{-- CABECERA --}}
    <div class="ficha-header">
        <img class="ficha-avatar" src="{{ $personaje->avatar_url }}" alt="{{ $personaje->nombre }}">

        <div class="ficha-titulo">
            <h1>{{ $personaje->nombre }}</h1>
            <div class="ficha-subtitulo">
                {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
                @if($personaje->subclase) ({{ $personaje->subclase->nombre }}) @endif
                @if($personaje->trasfondo) · {{ $personaje->trasfondo->nombre }} @endif
            </div>
            <div class="ficha-badges">
                <span class="badge badge-nivel">Nivel {{ $personaje->nivel }}</span>
                @if($personaje->experiencia)
                    <span class="badge badge-exp">⚡ {{ number_format($personaje->experiencia) }} XP</span>
                @endif
                @if($personaje->alineamiento)
                    <span class="badge badge-align">⚖️ {{ $personaje->alineamiento }}</span>
                @endif
                @if($personaje->divinidad)
                    <span class="badge badge-divinidad">🙏 {{ $personaje->divinidad }}</span>
                @endif
                @if($est && $est->inspiracion)
                    <span class="badge" style="background:rgba(212,96,67,0.2);color:#D46043;border:1px solid rgba(212,96,67,0.3)">✨ Inspiración</span>
                @endif
            </div>
            <div class="ficha-acciones">
                <a href="{{ route('personajes.index') }}" class="btn btn-volver">← Volver</a>
                <a href="{{ route('personajes.edit', $personaje) }}" class="btn btn-editar">✏️ Editar</a>
                <form action="{{ route('personajes.destroy', $personaje) }}" method="POST"
                      style="display:inline"
                      onsubmit="return confirm('¿Eliminar a {{ addslashes($personaje->nombre) }}? Esta acción no se puede deshacer.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-eliminar">🗑️ Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="ficha-body">

        {{-- ESTADÍSTICAS PRINCIPALES --}}
        <div class="seccion">
            <div class="seccion-titulo">🎯 Características</div>
            <div class="stats-grid">
                @foreach($stats as $label => $attr)
                    @php
                        $val    = $est ? ($est->$attr ?? 10) : 10;
                        $mod    = floor(($val - 10) / 2);
                        $modStr = $mod >= 0 ? '+' . $mod : (string)$mod;
                    @endphp
                    <div class="stat-card">
                        <span class="stat-label">{{ $label }}</span>
                        <span class="stat-valor">{{ $val }}</span>
                        <span class="stat-mod">{{ $modStr }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SALVACIONES --}}
        @if(count($compSal) > 0)
        <div class="seccion">
            <div class="seccion-titulo">🛡️ Tiradas de Salvación (competencia)</div>
            <div class="competencias-grid">
                @foreach($stats as $label => $attr)
                    @php $activa = in_array($attr, $compSal); @endphp
                    <div class="competencia-item {{ $activa ? 'activa' : 'inactiva' }}">
                        <span class="comp-dot {{ $activa ? 'activa' : 'inactiva' }}"></span>
                        {{ $label }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- HABILIDADES --}}
        @if(count($compHab) > 0)
        <div class="seccion">
            <div class="seccion-titulo">📋 Competencias en Habilidades</div>
            <div class="competencias-grid">
                @foreach($habilidades as $nombre => $base)
                    @php $activa = in_array($nombre, $compHab); @endphp
                    <div class="competencia-item {{ $activa ? 'activa' : 'inactiva' }}">
                        <span class="comp-dot {{ $activa ? 'activa' : 'inactiva' }}"></span>
                        {{ $nombre }} <span style="opacity:.5;font-size:.8em">({{ strtoupper(substr($base,0,3)) }})</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- COMBATE --}}
        @if($est)
        <div class="seccion">
            <div class="seccion-titulo">⚔️ Combate</div>
            <div class="info-combat">
                <div class="combat-card">
                    <span class="combat-label">❤️ PG Actuales / Máx</span>
                    <div class="combat-valor">{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}</div>
                </div>
                @if(($est->pg_temporales ?? 0) > 0)
                <div class="combat-card">
                    <span class="combat-label">💙 PG Temporales</span>
                    <div class="combat-valor">{{ $est->pg_temporales }}</div>
                </div>
                @endif
                <div class="combat-card">
                    <span class="combat-label">🛡️ Clase de Armadura</span>
                    <div class="combat-valor">{{ $est->clase_de_armadura ?? '—' }}</div>
                </div>
                <div class="combat-card">
                    <span class="combat-label">⚡ Velocidad</span>
                    <div class="combat-valor">{{ $est->velocidad ?? 30 }} ft</div>
                </div>
                <div class="combat-card">
                    <span class="combat-label">🎯 Bonus Competencia</span>
                    <div class="combat-valor">+{{ $est->bonus_competencia ?? 2 }}</div>
                </div>
                <div class="combat-card">
                    <span class="combat-label">⚔️ Iniciativa</span>
                    @php
                        $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2);
                    @endphp
                    <div class="combat-valor">{{ $ini >= 0 ? '+' . $ini : $ini }}</div>
                </div>
                @if($est->dados_golpe_disponibles !== null)
                <div class="combat-card">
                    <span class="combat-label">🎲 Dados de Golpe</span>
                    <div class="combat-valor">{{ $est->dados_golpe_disponibles }}</div>
                </div>
                @endif
            </div>

            {{-- Tiradas de muerte --}}
            @if(($est->exitos_muerte ?? 0) > 0 || ($est->fallos_muerte ?? 0) > 0)
            <div style="margin-top:1.2rem">
                <div class="muerte-grid">
                    <div class="muerte-grupo">
                        <label>✔ Éxitos de muerte</label>
                        <div class="muerte-dots">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="muerte-dot {{ $i <= ($est->exitos_muerte ?? 0) ? 'exito' : '' }}"></div>
                            @endfor
                        </div>
                    </div>
                    <div class="muerte-grupo">
                        <label>✖ Fallos de muerte</label>
                        <div class="muerte-dots">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="muerte-dot {{ $i <= ($est->fallos_muerte ?? 0) ? 'fallo' : '' }}"></div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- ATAQUES --}}
        @if(count($ataques) > 0)
        <div class="seccion">
            <div class="seccion-titulo">⚔️ Ataques y Conjuros</div>
            <table class="tabla-ataques">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Bonif.</th>
                        <th>Daño / Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ataques as $ataque)
                    <tr>
                        <td>{{ $ataque['nombre'] ?? '—' }}</td>
                        <td>{{ $ataque['bonificador'] ?? '—' }}</td>
                        <td>{{ $ataque['daño'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- HISTORIA --}}
        @if($personaje->historia)
        <div class="seccion">
            <div class="seccion-titulo">📖 Historia</div>
            <div class="historia-box">{{ $personaje->historia }}</div>
        </div>
        @endif

        {{-- RASGOS DE PERSONALIDAD --}}
        @if($personaje->rasgos_personalidad || $personaje->ideales || $personaje->vinculos || $personaje->defectos)
        <div class="seccion">
            <div class="seccion-titulo">💭 Personalidad</div>
            <div class="rasgos-grid">
                @if($personaje->rasgos_personalidad)
                <div class="rasgo-card">
                    <span class="rasgo-label">Rasgos</span>
                    <div class="rasgo-texto">{{ $personaje->rasgos_personalidad }}</div>
                </div>
                @endif
                @if($personaje->ideales)
                <div class="rasgo-card">
                    <span class="rasgo-label">Ideales</span>
                    <div class="rasgo-texto">{{ $personaje->ideales }}</div>
                </div>
                @endif
                @if($personaje->vinculos)
                <div class="rasgo-card">
                    <span class="rasgo-label">Vínculos</span>
                    <div class="rasgo-texto">{{ $personaje->vinculos }}</div>
                </div>
                @endif
                @if($personaje->defectos)
                <div class="rasgo-card">
                    <span class="rasgo-label">Defectos</span>
                    <div class="rasgo-texto">{{ $personaje->defectos }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- APARIENCIA --}}
        @php
            $apariencia = array_filter([
                'Edad'    => $personaje->edad,
                'Altura'  => $personaje->altura,
                'Peso'    => $personaje->peso,
                'Ojos'    => $personaje->ojos,
                'Piel'    => $personaje->piel,
                'Pelo'    => $personaje->pelo,
            ]);
        @endphp
        @if(count($apariencia) > 0)
        <div class="seccion">
            <div class="seccion-titulo">🧍 Apariencia</div>
            <div class="apariencia-grid">
                @foreach($apariencia as $label => $valor)
                <div class="apariencia-item">
                    <span class="apariencia-label">{{ $label }}</span>
                    <span class="apariencia-valor">{{ $valor }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- IDIOMAS --}}
        @if($personaje->idiomas)
        <div class="seccion">
            <div class="seccion-titulo">🌍 Idiomas</div>
            <p style="color:#a8b0b8;font-size:0.95rem">{{ $personaje->idiomas }}</p>
        </div>
        @endif

        {{-- GALERÍA — PERSONAJE --}}
        @if(count($imgsPersonaje) > 0)
        <div class="seccion">
            <div class="seccion-titulo">🎨 Imágenes del Personaje</div>
            <div class="galeria">
                @foreach($imgsPersonaje as $img)
                    <img src="{{ Storage::url($img) }}"
                         alt="Imagen del personaje"
                         onclick="abrirLightbox(this.src)">
                @endforeach
            </div>
        </div>
        @endif

        {{-- GALERÍA — ARMAS --}}
        @if(count($imgsArmas) > 0)
        <div class="seccion">
            <div class="seccion-titulo">⚔️ Imágenes de Armas</div>
            <div class="galeria">
                @foreach($imgsArmas as $img)
                    <img src="{{ Storage::url($img) }}"
                         alt="Imagen de arma"
                         onclick="abrirLightbox(this.src)">
                @endforeach
            </div>
        </div>
        @endif

        {{-- EQUIPO --}}
        <div class="seccion">
            <div class="seccion-titulo">🎒 Equipo</div>
            @if($personaje->equipo && $personaje->equipo->count() > 0)
            <div class="equipo-grid">
                @foreach($personaje->equipo as $item)
                <div class="equipo-item">
                    <span class="equipo-nombre">
                        {{ $item->nombre }}
                        @if($item->equipado) <span class="badge-equipado">✓ Equipado</span> @endif
                    </span>
                    <div class="equipo-det">{{ $item->tipo }}@if($item->magico) · ✨ Mágico @endif</div>
                    @if($item->cantidad > 1) <div class="equipo-det">×{{ $item->cantidad }}</div> @endif
                    @if($item->valor_po)     <div class="equipo-det">💰 {{ $item->valor_po }} PO</div> @endif
                    @if($item->peso)         <div class="equipo-det">⚖️ {{ $item->peso }} lb</div> @endif
                    @if($item->descripcion)  <div class="equipo-det" style="font-size:.8rem;margin-top:.3rem">{{ $item->descripcion }}</div> @endif
                </div>
                @endforeach
            </div>
            @else
            <p style="color:#768596">Este aventurero no lleva equipo.</p>
            @endif
        </div>

        {{-- MONEDAS --}}
        @if($est)
        <div class="seccion">
            <div class="seccion-titulo">💰 Tesoro</div>
            <div class="monedas-grid">
                <div class="moneda-card">
                    <span class="moneda-simbolo">🟤</span>
                    <span class="moneda-cantidad">{{ $est->monedas_cobre ?? 0 }}</span>
                    <span class="moneda-nombre">Cobre</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">⚪</span>
                    <span class="moneda-cantidad">{{ $est->monedas_plata ?? 0 }}</span>
                    <span class="moneda-nombre">Plata</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">🟡</span>
                    <span class="moneda-cantidad">{{ $est->monedas_electrum ?? 0 }}</span>
                    <span class="moneda-nombre">Electrum</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">🟠</span>
                    <span class="moneda-cantidad">{{ $est->monedas_oro ?? 0 }}</span>
                    <span class="moneda-nombre">Oro</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">⚫</span>
                    <span class="moneda-cantidad">{{ $est->monedas_platino ?? 0 }}</span>
                    <span class="moneda-nombre">Platino</span>
                </div>
            </div>
        </div>
        @endif

    </div>{{-- /.ficha-body --}}
</div>{{-- /.ficha-wrapper --}}

{{-- LIGHTBOX --}}
<div class="lightbox" id="lightbox" onclick="cerrarLightbox()">
    <img id="lightboxImg" src="" alt="Imagen ampliada">
</div>

<script>
function abrirLightbox(src) {
    document.getElementById('lightboxImg').src = src;
    document.getElementById('lightbox').classList.add('active');
}
function cerrarLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') cerrarLightbox();
});
</script>
@endsection