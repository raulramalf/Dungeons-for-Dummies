@extends('layouts.app')

@section('titulo', $personaje->nombre . ' - Ficha')

@section('contenido')
<style>
    /* ==========================================
       Estilos generales de la ficha
       ========================================== */
    .ficha-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        background: linear-gradient(145deg, rgba(20, 10, 5, 0.95), rgba(40, 20, 10, 0.98));
        border-radius: 16px;
        border: 1px solid rgba(179, 3, 3, 0.25);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
        overflow: hidden;
        backdrop-filter: blur(4px);
    }

    .ficha-header {
        display: flex;
        flex-wrap: wrap;
        background: rgba(0, 0, 0, 0.5);
        border-bottom: 2px solid var(--color-rojo);
        padding: 1.5rem 2rem;
        gap: 2rem;
        align-items: center;
    }

    .ficha-avatar {
        width: 140px;
        height: 140px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--color-rojo);
        background: #1a0a0a;
        flex-shrink: 0;
        box-shadow: 0 0 30px rgba(179, 3, 3, 0.3);
    }

    .ficha-titulo {
        flex: 1;
    }

    .ficha-titulo h1 {
        font-size: 2.8rem;
        color: #fff;
        margin: 0;
        letter-spacing: 2px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
    }

    .ficha-titulo .subtitulo {
        color: var(--color-naranja);
        font-size: 1.2rem;
        margin: 0.2rem 0 0.5rem;
    }

    .ficha-titulo .meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
    }

    .badge-nivel {
        background: var(--color-rojo);
        color: #fff;
        padding: 0.3rem 1.2rem;
        border-radius: 20px;
        font-weight: bold;
        font-size: 0.95rem;
    }

    .badge-experiencia {
        color: var(--color-gris);
        font-size: 0.9rem;
    }

    .badge-alineamiento {
        background: rgba(255, 255, 255, 0.08);
        padding: 0.3rem 1rem;
        border-radius: 20px;
        color: var(--color-gris);
        font-size: 0.85rem;
        border: 1px solid rgba(118, 133, 150, 0.2);
    }

    .acciones-header {
        display: flex;
        gap: 0.8rem;
        flex-wrap: wrap;
        margin-top: 0.5rem;
    }

    .btn-accion {
        padding: 0.5rem 1.2rem;
        border-radius: 6px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
        font-family: inherit;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .btn-accion.volver {
        background: rgba(255, 255, 255, 0.06);
        color: var(--color-gris);
        border: 1px solid var(--color-gris);
    }
    .btn-accion.volver:hover {
        background: rgba(255, 255, 255, 0.12);
        color: #fff;
    }

    .btn-accion.editar {
        background: var(--color-naranja);
        color: #fff;
    }
    .btn-accion.editar:hover {
        background: #b84a30;
        transform: translateY(-2px);
    }

    .btn-accion.eliminar {
        background: #6b1a1a;
        color: #fff;
    }
    .btn-accion.eliminar:hover {
        background: #8a2222;
        transform: translateY(-2px);
    }

    .ficha-body {
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

    /* Estadísticas */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
        gap: 1.2rem;
    }

    .stat-card {
        background: rgba(0, 0, 0, 0.35);
        border-radius: 10px;
        padding: 1.2rem 0.8rem;
        text-align: center;
        border: 1px solid rgba(118, 133, 150, 0.1);
        transition: all 0.3s;
    }
    .stat-card:hover {
        border-color: var(--color-rojo);
        transform: translateY(-3px);
    }

    .stat-card .label {
        color: var(--color-gris);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .stat-card .value {
        font-size: 2.2rem;
        font-weight: 700;
        color: #fff;
        display: block;
        line-height: 1.2;
    }
    .stat-card .mod {
        color: var(--color-naranja);
        font-size: 0.95rem;
        font-weight: 600;
    }

    /* Info combate */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 1.2rem;
    }

    .info-card {
        background: rgba(0, 0, 0, 0.3);
        padding: 1rem 1.2rem;
        border-radius: 8px;
        border-left: 3px solid var(--color-rojo);
    }
    .info-card label {
        color: var(--color-gris);
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }
    .info-card .valor {
        color: #fff;
        font-size: 1.3rem;
        font-weight: 600;
        margin-top: 0.2rem;
    }

    /* Historia */
    .historia-box {
        background: rgba(0, 0, 0, 0.3);
        padding: 1.5rem;
        border-radius: 10px;
        border-left: 4px solid var(--color-rojo);
        color: #c8c8c8;
        line-height: 1.7;
        font-style: italic;
    }

    /* Equipo (solo lectura) */
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

    /* Monedas (solo lectura) */
    .monedas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
        gap: 1rem;
    }

    .moneda-card {
        background: rgba(0, 0, 0, 0.25);
        padding: 0.8rem;
        border-radius: 8px;
        text-align: center;
    }
    .moneda-card .simbolo {
        font-size: 1.4rem;
        display: block;
    }
    .moneda-card .cantidad {
        color: #fff;
        font-size: 1.4rem;
        font-weight: 600;
    }

    @media (max-width: 768px) {
        .ficha-header {
            flex-direction: column;
            text-align: center;
            padding: 1.5rem;
        }
        .ficha-avatar {
            width: 120px;
            height: 120px;
        }
        .ficha-titulo h1 {
            font-size: 2rem;
        }
        .ficha-body {
            padding: 1.5rem;
        }
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
        .info-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .info-grid {
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

<div class="ficha-wrapper">
    <!-- CABECERA -->
    <div class="ficha-header">
        <img class="ficha-avatar" src="{{ $personaje->avatar_url }}" alt="{{ $personaje->nombre }}">

        <div class="ficha-titulo">
            <h1>{{ $personaje->nombre }}</h1>
            <div class="subtitulo">
                {{ $personaje->raza->nombre ?? 'Raza' }} · {{ $personaje->clase->nombre ?? 'Clase' }}
                @if($personaje->subclase)
                    ({{ $personaje->subclase->nombre }})
                @endif
            </div>
            <div class="meta">
                <span class="badge-nivel">Nivel {{ $personaje->nivel }}</span>
                @if($personaje->experiencia)
                    <span class="badge-experiencia">⚡ {{ number_format($personaje->experiencia) }} XP</span>
                @endif
                @if($personaje->alineamiento)
                    <span class="badge-alineamiento">⚖️ {{ $personaje->alineamiento }}</span>
                @endif
            </div>
            <div class="acciones-header">
                <a href="{{ route('personajes.index') }}" class="btn-accion volver">← Volver</a>
                <a href="{{ route('personajes.edit', $personaje) }}" class="btn-accion editar">✏️ Editar</a>
                <form action="{{ route('personajes.destroy', $personaje) }}" method="POST" style="display:inline;" onsubmit="return confirm('¿Eliminar a {{ $personaje->nombre }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-accion eliminar">🗑️ Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <!-- CUERPO -->
    <div class="ficha-body">

        <!-- ESTADÍSTICAS -->
        <div class="seccion">
            <div class="seccion-titulo"><span class="icono">🎯</span> Características</div>
            <div class="stats-grid">
                @php
                    $stats = ['FUE'=>'fuerza','DES'=>'destreza','CON'=>'constitucion','INT'=>'inteligencia','SAB'=>'sabiduria','CAR'=>'carisma'];
                    $est = $personaje->estadisticas ?? null;
                @endphp
                @foreach($stats as $label => $attr)
                    @php
                        $valor = $est ? ($est->$attr ?? 10) : 10;
                        $mod = floor(($valor - 10) / 2);
                        $modStr = $mod >= 0 ? '+' . $mod : $mod;
                    @endphp
                    <div class="stat-card">
                        <div class="label">{{ $label }}</div>
                        <span class="value">{{ $valor }}</span>
                        <div class="mod">{{ $modStr }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- COMBATE -->
        @if($est)
        <div class="seccion">
            <div class="seccion-titulo"><span class="icono">⚔️</span> Combate</div>
            <div class="info-grid">
                <div class="info-card"><label>❤️ Puntos de Golpe</label><div class="valor">{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}</div></div>
                <div class="info-card"><label>🛡️ Clase de Armadura</label><div class="valor">{{ $est->clase_de_armadura ?? '?' }}</div></div>
                <div class="info-card"><label>⚡ Velocidad</label><div class="valor">{{ $est->velocidad ?? '30' }} ft</div></div>
                <div class="info-card"><label>🎯 Bonus Competencia</label><div class="valor">+{{ $est->bonus_competencia ?? '2' }}</div></div>
                <div class="info-card"><label>⚔️ Iniciativa</label><div class="valor">+{{ $est->iniciativa ?? 0 }}</div></div>
            </div>
        </div>
        @endif

        <!-- HISTORIA -->
        @if($personaje->historia)
        <div class="seccion">
            <div class="seccion-titulo"><span class="icono">📖</span> Historia</div>
            <div class="historia-box">{{ $personaje->historia }}</div>
        </div>
        @endif

        <!-- EQUIPO (solo lectura) -->
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
                        </div>
                    @endforeach
                </div>
            @else
                <p style="color: var(--color-gris);">No tiene equipo.</p>
            @endif
        </div>

        <!-- MONEDAS (solo lectura) -->
        <div class="seccion">
            <div class="seccion-titulo"><span class="icono">💰</span> Tesoro</div>
            @if($est)
                <div class="monedas-grid">
                    <div class="moneda-card"><span class="simbolo">🟤</span><span class="cantidad">{{ $est->monedas_cobre ?? 0 }}</span></div>
                    <div class="moneda-card"><span class="simbolo">⚪</span><span class="cantidad">{{ $est->monedas_plata ?? 0 }}</span></div>
                    <div class="moneda-card"><span class="simbolo">🟡</span><span class="cantidad">{{ $est->monedas_electrum ?? 0 }}</span></div>
                    <div class="moneda-card"><span class="simbolo">🟠</span><span class="cantidad">{{ $est->monedas_oro ?? 0 }}</span></div>
                    <div class="moneda-card"><span class="simbolo">⚫</span><span class="cantidad">{{ $est->monedas_platino ?? 0 }}</span></div>
                </div>
            @else
                <p style="color:var(--color-gris);">No hay información de monedas.</p>
            @endif
        </div>

    </div><!-- /.ficha-body -->
</div><!-- /.ficha-wrapper -->
@endsection