@extends('layouts.app')

@section('titulo', 'Exportar ficha — ' . $personaje->nombre)

@section('contenido')
<div class="contenedor" style="color-scheme: dark light">

    <div class="exportar-cabecera">
        <a href="{{ route('personajes.show', $personaje) }}" class="btn btn-volver">← Volver a {{ $personaje->nombre }}</a>
        <h1 class="seccion-titulo" style="margin-top:1rem">Exportar ficha de {{ $personaje->nombre }}</h1>
        <p class="seccion-subtitulo">Elige qué quieres incluir en el PDF</p>
    </div>

    {{-- TIPO DE FICHA --}}
    <div class="tipo-ficha-selector" role="radiogroup" aria-label="Tipo de ficha">
        @foreach($tipos as $clave => $info)
        <label class="tipo-ficha-opcion">
            <input type="radio" name="tipoFicha" value="{{ $clave }}" {{ $loop->first ? 'checked' : '' }}
                   onchange="actualizarEnlace()">
            <span class="tipo-ficha-titulo">{{ $info['label'] }}</span>
            <span class="tipo-ficha-desc">{{ $info['desc'] }}</span>
        </label>
        @endforeach
    </div>

    {{-- PLANTILLA ÚNICA: vista previa + descarga --}}
    <div class="plantilla-unica">
        <div class="plantilla-preview preview-oficial">
            <div class="pv-of-titulo">Hoja de Personaje</div>
            <div class="pv-cols">
                <div class="pv-col-izq"><span></span><span></span><span></span></div>
                <div class="pv-col-der">
                    <div class="pv-row"><span></span><span></span><span></span></div>
                    <div class="pv-line"></div><div class="pv-line"></div>
                </div>
            </div>
        </div>
        <div class="plantilla-unica-info">
            <h3>{{ $plantillas['oficial']['label'] }}</h3>
            <p>{{ $plantillas['oficial']['desc'] }}</p>
            <a href="#" id="enlaceExportar" class="btn btn-primario">📄 Descargar PDF</a>
        </div>
    </div>

</div>

<style>
.exportar-cabecera { margin-bottom: 1.5rem; }

/* ----- Selector de tipo de ficha ----- */
.tipo-ficha-selector {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.tipo-ficha-opcion {
    display: flex;
    flex-direction: column;
    gap: 0.3rem;
    border: 1.5px solid var(--b-sutil);
    border-radius: var(--r-md);
    padding: 0.9rem 1.1rem;
    cursor: pointer;
    background: var(--c-superficie);
    transition: border-color 0.2s, background 0.2s;
}

.tipo-ficha-opcion:has(input:checked) {
    border-color: var(--c-rojo);
    background: rgba(179,3,3,0.08);
}

.tipo-ficha-opcion input { accent-color: var(--c-rojo); margin-right: 0.4rem; }
.tipo-ficha-titulo { font-family: var(--f-titulo); font-size: 0.95rem; color: var(--t-principal); }
.tipo-ficha-desc { font-size: 0.8rem; color: var(--t-secundario); padding-left: 1.4rem; }

/* ----- Plantilla única ----- */
.plantilla-unica {
    display: flex;
    gap: 1.6rem;
    align-items: center;
    background: var(--c-superficie);
    border: 1px solid var(--b-sutil);
    border-radius: var(--r-lg);
    padding: 1.4rem;
    max-width: 560px;
}

.plantilla-unica-info h3 {
    font-family: var(--f-titulo);
    font-size: 1.1rem;
    margin: 0 0 0.5rem;
}

.plantilla-unica-info p {
    color: var(--t-secundario);
    font-size: 0.88rem;
    line-height: 1.45;
    margin-bottom: 1rem;
}

/* ----- Mini-previa (representación visual, no el PDF real) ----- */
.plantilla-preview {
    width: 160px;
    flex-shrink: 0;
    aspect-ratio: 210 / 297;
    border-radius: 6px;
    overflow: hidden;
    position: relative;
    padding: 10%;
}

.pv-line { height: 6%; border-radius: 2px; margin-bottom: 6%; }
.pv-row { display: flex; gap: 6%; margin-bottom: 8%; }
.pv-row span { flex: 1; aspect-ratio: 1; border-radius: 50%; }

.preview-oficial { background: #f3e9d8; border: 3px solid #8a1c1c; }
.preview-oficial .pv-of-titulo {
    text-align: center; font-family: var(--f-titulo); font-weight: 700;
    color: #8a1c1c; font-size: 0.62rem; margin-bottom: 8%; letter-spacing: 0.04em;
}
.preview-oficial .pv-cols { display: flex; gap: 6%; }
.preview-oficial .pv-col-izq { width: 30%; display: flex; flex-direction: column; gap: 8%; }
.preview-oficial .pv-col-izq span { height: 20%; border: 2px solid #8a1c1c; border-radius: 50%; background: #fff; }
.preview-oficial .pv-col-der { width: 70%; }
.preview-oficial .pv-col-der .pv-row span { background: #fff; border: 2px solid #8a1c1c; aspect-ratio: auto; height: 18%; border-radius: 3px; }
.preview-oficial .pv-col-der .pv-line { background: #e3d2ad; }

@media (max-width: 560px) {
    .plantilla-unica { flex-direction: column; align-items: flex-start; }
    .plantilla-preview { width: 130px; }
}
</style>

<script>
function actualizarEnlace() {
    const tipo = document.querySelector('input[name="tipoFicha"]:checked').value;
    document.getElementById('enlaceExportar').href =
        `{{ route('personajes.exportar.descargar', $personaje) }}?plantilla=oficial&tipo=${tipo}`;
}
actualizarEnlace();
</script>
@endsection