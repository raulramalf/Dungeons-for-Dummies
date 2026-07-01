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

    {{-- PLANTILLA: selector con vista previa --}}
    <p class="seccion-subtitulo" style="margin-bottom: 0.8rem;">Elige el estilo de la plantilla</p>
    <div class="plantilla-selector" role="radiogroup" aria-label="Plantilla de ficha">
        @foreach($plantillas as $clave => $info)
        <label class="plantilla-opcion">
            <input type="radio" name="plantillaFicha" value="{{ $clave }}" {{ $loop->first ? 'checked' : '' }}
                   onchange="actualizarEnlace()">
            <div class="plantilla-preview preview-{{ $clave }}">
                @if($clave === 'oficial')
                    <div class="pv-of-titulo">Hoja de Personaje</div>
                    <div class="pv-cols">
                        <div class="pv-col-izq"><span></span><span></span><span></span></div>
                        <div class="pv-col-der">
                            <div class="pv-row"><span></span><span></span><span></span></div>
                            <div class="pv-line"></div><div class="pv-line"></div>
                        </div>
                    </div>
                @elseif($clave === 'mistica')
                    <div class="pv-mi-banner"></div>
                    <div class="pv-cols">
                        <div class="pv-col-izq"><span></span><span></span><span></span></div>
                        <div class="pv-col-cen"><span></span><span></span><span></span></div>
                        <div class="pv-col-der-mi"><span></span><span></span></div>
                    </div>
                @elseif($clave === 'clasica')
                    <div class="pv-cl-titulo">Dungeons for Dummies</div>
                    <div class="pv-cols">
                        <div class="pv-col-izq-cl"><span></span><span></span><span></span></div>
                        <div class="pv-col-cen-cl"><span></span></div>
                        <div class="pv-col-der-cl"><span></span><span></span></div>
                    </div>
                @endif
            </div>
            <div class="plantilla-opcion-info">
                <h3>{{ $info['label'] }}</h3>
                <p>{{ $info['desc'] }}</p>
            </div>
        </label>
        @endforeach
    </div>

    <div class="exportar-descarga">
        <a href="#" id="enlaceExportar" class="btn btn-primario">📄 Descargar PDF</a>
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

/* ----- Selector de plantilla ----- */
.plantilla-selector {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.6rem;
}

.plantilla-opcion {
    display: flex;
    flex-direction: row;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    border: 1.5px solid var(--b-sutil);
    border-radius: var(--r-lg);
    padding: 0.9rem 1.1rem;
    background: var(--c-superficie);
    transition: border-color 0.2s, background 0.2s;
    max-width: 340px;
}
.plantilla-opcion input { position: absolute; opacity: 0; pointer-events: none; }
.plantilla-opcion:has(input:checked) {
    border-color: var(--c-rojo);
    background: rgba(179,3,3,0.08);
}

.plantilla-opcion-info h3 {
    font-family: var(--f-titulo);
    font-size: 0.95rem;
    margin: 0 0 0.35rem;
}
.plantilla-opcion-info p {
    color: var(--t-secundario);
    font-size: 0.8rem;
    line-height: 1.4;
    margin: 0;
}

.exportar-descarga { margin-top: 0.5rem; }

/* ----- Mini-previa (representación visual, no el PDF real) ----- */
.plantilla-preview {
    width: 100px;
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
.pv-cols { display: flex; gap: 5%; }

/* -- Oficial -- */
.preview-oficial { background: #f3e9d8; border: 3px solid #8a1c1c; }
.preview-oficial .pv-of-titulo {
    text-align: center; font-family: var(--f-titulo); font-weight: 700;
    color: #8a1c1c; font-size: 0.62rem; margin-bottom: 8%; letter-spacing: 0.04em;
}
.preview-oficial .pv-col-izq { width: 30%; display: flex; flex-direction: column; gap: 8%; }
.preview-oficial .pv-col-izq span { height: 20%; border: 2px solid #8a1c1c; border-radius: 50%; background: #fff; }
.preview-oficial .pv-col-der { width: 70%; }
.preview-oficial .pv-col-der .pv-row span { background: #fff; border: 2px solid #8a1c1c; aspect-ratio: auto; height: 18%; border-radius: 3px; }
.preview-oficial .pv-col-der .pv-line { background: #e3d2ad; }

/* -- Mística -- */
.preview-mistica { background: #0f2622; border: 3px solid #4d7a68; }
.preview-mistica .pv-mi-banner {
    height: 14%; border-radius: 4px; background: #f2f0e4; border: 2px solid #b7c9a8; margin-bottom: 8%;
}
.preview-mistica .pv-col-izq, .preview-mistica .pv-col-cen, .preview-mistica .pv-col-der-mi {
    width: 33%; display: flex; flex-direction: column; gap: 8%;
}
.preview-mistica .pv-col-izq span { height: 22%; border: 2px solid #4d7a68; border-radius: 3px; background: #f2f0e4; }
.preview-mistica .pv-col-cen span { height: 18%; border: 2px solid #4d7a68; border-radius: 3px; background: #f2f0e4; }
.preview-mistica .pv-col-der-mi span { height: 30%; border: 2px solid #4d7a68; border-radius: 3px; background: #f2f0e4; }

/* -- Clásica -- */
.preview-clasica { background: #14161c; border: 3px solid #6b4a23; }
.preview-clasica .pv-cl-titulo {
    text-align: center; font-family: var(--f-titulo); font-weight: 700;
    color: #c9974a; font-size: 0.6rem; margin-bottom: 8%; letter-spacing: 0.08em;
}
.preview-clasica .pv-col-izq-cl { width: 30%; display: flex; flex-direction: column; gap: 8%; }
.preview-clasica .pv-col-izq-cl span { height: 20%; border: 1.5px solid #6b4a23; border-radius: 3px; background: #1e222c; }
.preview-clasica .pv-col-cen-cl { width: 35%; }
.preview-clasica .pv-col-cen-cl span { display: block; height: 40%; border: 1.5px solid #6b4a23; border-radius: 3px; background: #1e222c; }
.preview-clasica .pv-col-der-cl { width: 35%; display: flex; flex-direction: column; gap: 10%; }
.preview-clasica .pv-col-der-cl span { height: 28%; border: 1.5px solid #6b4a23; border-radius: 3px; background: #1e222c; }

@media (max-width: 560px) {
    .plantilla-opcion { max-width: 100%; }
}
</style>

<script>
function actualizarEnlace() {
    const tipo = document.querySelector('input[name="tipoFicha"]:checked').value;
    const plantilla = document.querySelector('input[name="plantillaFicha"]:checked').value;
    document.getElementById('enlaceExportar').href =
        `{{ route('personajes.exportar.descargar', $personaje) }}?plantilla=${plantilla}&tipo=${tipo}`;
}
actualizarEnlace();
</script>
@endsection
