@extends('layouts.app')

@section('titulo', 'Exportar ficha — ' . $personaje->nombre)

@push('head')
    @vite(['resources/css/pages/exportar.css', 'resources/js/pages/exportar.js'])
@endpush

@section('contenido')
<div class="contenedor">

    <div class="exportar-cabecera">
        <a href="{{ route('personajes.show', $personaje) }}" class="btn btn-volver">← Volver a {{ $personaje->nombre }}</a>
        <h1 class="seccion-titulo exp-titulo">Exportar ficha de {{ $personaje->nombre }}</h1>
        <p class="seccion-subtitulo">Elige qué quieres incluir en el PDF</p>
    </div>

    {{-- TIPO DE FICHA --}}
    <div class="tipo-ficha-selector" role="radiogroup" aria-label="Tipo de ficha">
        @foreach($tipos as $clave => $info)
        <label class="tipo-ficha-opcion">
            <input type="radio" name="tipoFicha" value="{{ $clave }}" {{ $loop->first ? 'checked' : '' }}
                   data-actualiza-enlace>
            <span class="tipo-ficha-titulo">{{ $info['label'] }}</span>
            <span class="tipo-ficha-desc">{{ $info['desc'] }}</span>
        </label>
        @endforeach
    </div>

    {{-- PLANTILLA: selector con vista previa --}}
    <p class="seccion-subtitulo exp-subtitulo">Elige el estilo de la plantilla</p>
    <div class="plantilla-selector" role="radiogroup" aria-label="Plantilla de ficha">
        @foreach($plantillas as $clave => $info)
        <label class="plantilla-opcion">
            <input type="radio" name="plantillaFicha" value="{{ $clave }}" {{ $loop->first ? 'checked' : '' }}
                   data-actualiza-enlace>
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
        <a href="#" id="enlaceExportar" class="btn btn-primario" data-base="{{ route('personajes.exportar.descargar', $personaje) }}">@include('partials.icon', ['name' => 'scroll', 'class' => 'icon-sm']) Descargar PDF</a>
    </div>

</div>


@endsection
