@extends('layouts.app')

@section('titulo', 'Exportar ficha — ' . $personaje->nombre)

@section('contenido')
<div class="contenedor">

    <div class="exportar-cabecera">
        <a href="{{ route('personajes.show', $personaje) }}" class="btn btn-volver">← Volver a {{ $personaje->nombre }}</a>
        <h1 class="seccion-titulo" style="margin-top:1rem">Elige una plantilla para tu ficha</h1>
        <p class="seccion-subtitulo">Todas incluyen los mismos datos de {{ $personaje->nombre }}, solo cambia el estilo</p>
    </div>

    <div class="plantillas-grid">

        {{-- CLÁSICA --}}
        <div class="plantilla-card">
            <div class="plantilla-preview preview-clasica">
                <div class="pv-header"></div>
                <div class="pv-row"><span></span><span></span><span></span></div>
                <div class="pv-line"></div><div class="pv-line"></div><div class="pv-line short"></div>
            </div>
            <h3>{{ $plantillas['clasica']['label'] }}</h3>
            <p>{{ $plantillas['clasica']['desc'] }}</p>
            <a href="{{ route('personajes.exportar.descargar', $personaje) }}?plantilla=clasica" class="btn btn-primario">Exportar esta</a>
        </div>

        {{-- ESTILO OFICIAL --}}
        <div class="plantilla-card">
            <div class="plantilla-preview preview-oficial">
                <div class="pv-cols">
                    <div class="pv-col-izq"><span></span><span></span><span></span></div>
                    <div class="pv-col-der">
                        <div class="pv-row"><span></span><span></span><span></span></div>
                        <div class="pv-line"></div><div class="pv-line"></div>
                    </div>
                </div>
            </div>
            <h3>{{ $plantillas['oficial']['label'] }}</h3>
            <p>{{ $plantillas['oficial']['desc'] }}</p>
            <a href="{{ route('personajes.exportar.descargar', $personaje) }}?plantilla=oficial" class="btn btn-primario">Exportar esta</a>
        </div>

        {{-- PERGAMINO DRACÓNICO --}}
        <div class="plantilla-card">
            <div class="plantilla-preview preview-pergamino">
                <div class="pv-marco">
                    <div class="pv-circulos"><i></i><i></i><i></i><i></i></div>
                    <div class="pv-line"></div><div class="pv-line short"></div>
                </div>
            </div>
            <h3>{{ $plantillas['pergamino']['label'] }}</h3>
            <p>{{ $plantillas['pergamino']['desc'] }}</p>
            <a href="{{ route('personajes.exportar.descargar', $personaje) }}?plantilla=pergamino" class="btn btn-primario">Exportar esta</a>
        </div>

        {{-- OSCURA GÓTICA --}}
        <div class="plantilla-card">
            <div class="plantilla-preview preview-gotica">
                <div class="pv-header-gotica">D&amp;D</div>
                <div class="pv-row"><span></span><span></span><span></span></div>
                <div class="pv-line"></div><div class="pv-line short"></div>
            </div>
            <h3>{{ $plantillas['gotica']['label'] }}</h3>
            <p>{{ $plantillas['gotica']['desc'] }}</p>
            <a href="{{ route('personajes.exportar.descargar', $personaje) }}?plantilla=gotica" class="btn btn-primario">Exportar esta</a>
        </div>

        {{-- MINIMALISTA --}}
        <div class="plantilla-card">
            <div class="plantilla-preview preview-minimalista">
                <div class="pv-titulo-min"></div>
                <div class="pv-line thin"></div><div class="pv-line thin"></div><div class="pv-line thin short"></div>
            </div>
            <h3>{{ $plantillas['minimalista']['label'] }}</h3>
            <p>{{ $plantillas['minimalista']['desc'] }}</p>
            <a href="{{ route('personajes.exportar.descargar', $personaje) }}?plantilla=minimalista" class="btn btn-primario">Exportar esta</a>
        </div>

    </div>
</div>

<style>
.exportar-cabecera { margin-bottom: 1.5rem; }

.plantillas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
    gap: 1.4rem;
}

.plantilla-card {
    background: var(--c-superficie);
    border: 1px solid var(--b-sutil);
    border-radius: var(--r-lg);
    padding: 1rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    transition: border-color 0.25s, transform 0.25s;
}

.plantilla-card:hover {
    border-color: var(--b-medio);
    transform: translateY(-4px);
}

.plantilla-card h3 {
    font-family: var(--f-titulo);
    font-size: 1rem;
    margin: 0.9rem 0 0.4rem;
}

.plantilla-card p {
    color: var(--t-secundario);
    font-size: 0.85rem;
    line-height: 1.4;
    margin-bottom: 1rem;
    min-height: 3.4em;
}

/* ----- Mini-previas (representación visual, no el PDF real) ----- */
.plantilla-preview {
    width: 100%;
    aspect-ratio: 210 / 297;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
    padding: 10%;
}

.pv-line {
    height: 6%;
    border-radius: 2px;
    margin-bottom: 6%;
}

.pv-line.short { width: 60%; }
.pv-line.thin { height: 3%; }

.pv-row { display: flex; gap: 6%; margin-bottom: 8%; }
.pv-row span { flex: 1; aspect-ratio: 1; border-radius: 50%; }

/* Clásica: papel claro, acentos granate */
.preview-clasica { background: #efe6d6; }
.preview-clasica .pv-header { height: 16%; background: #7a0202; border-radius: 4px; margin-bottom: 8%; }
.preview-clasica .pv-row span { background: #d8cdb8; border: 2px solid #B30303; border-radius: 4px; aspect-ratio: auto; height: 16%; }
.preview-clasica .pv-line { background: #d8cdb8; }

/* Estilo oficial: columnas, marco rojo */
.preview-oficial { background: #f3e9d8; border: 3px solid #8a1c1c; }
.preview-oficial .pv-cols { display: flex; gap: 6%; height: 100%; }
.preview-oficial .pv-col-izq { width: 30%; display: flex; flex-direction: column; gap: 8%; }
.preview-oficial .pv-col-izq span { height: 20%; border: 2px solid #8a1c1c; border-radius: 4px; background: #fff; }
.preview-oficial .pv-col-der { width: 70%; }
.preview-oficial .pv-col-der .pv-row span { background: #fff; border: 2px solid #8a1c1c; aspect-ratio: auto; height: 18%; border-radius: 3px; }
.preview-oficial .pv-col-der .pv-line { background: #e3d2ad; }

/* Pergamino dracónico */
.preview-pergamino { background: radial-gradient(circle at 50% 20%, #e8dcc0, #cdb98c); }
.preview-pergamino .pv-marco { border: 3px double #7a0202; border-radius: 50% 50% 8px 8px / 20% 20% 8px 8px; height: 100%; padding: 12%; }
.preview-pergamino .pv-circulos { display: flex; gap: 6%; margin-bottom: 12%; }
.preview-pergamino .pv-circulos i { flex: 1; aspect-ratio: 1; border-radius: 50%; border: 2px solid #7a0202; background: #f4ecd8; display: block; }
.preview-pergamino .pv-line { background: #b89e6e; }

/* Oscura gótica */
.preview-gotica { background: #0c0a08; border: 2px solid #C9A53B; }
.preview-gotica .pv-header-gotica {
    text-align: center; color: #C9A53B; font-family: var(--f-titulo);
    font-weight: 700; font-size: 1rem; margin-bottom: 10%;
    border-bottom: 1px solid #6e0f0f; padding-bottom: 8%;
}
.preview-gotica .pv-row span { background: #1a1410; border: 2px solid #6e0f0f; aspect-ratio: auto; height: 16%; border-radius: 4px; }
.preview-gotica .pv-line { background: #3a1414; }

/* Minimalista */
.preview-minimalista { background: #fdfdfb; border: 1px solid #ddd; }
.preview-minimalista .pv-titulo-min { height: 10%; width: 50%; background: #222; margin-bottom: 14%; }
.preview-minimalista .pv-line.thin { background: #ddd; }
</style>
@endsection