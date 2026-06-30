<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    /*
        Plantilla 'Estilo Oficial 2024' — maquetación propia inspirada en la
        disposición estándar de una hoja de personaje de 5e (las mismas cajas
        y orden de secciones que cualquier jugador reconoce), con ornamentación
        vectorial original. Dos páginas (combate / conjuros).
        Compatible con Dompdf: tablas/floats, sin flexbox/grid.
    */
    @page { margin: 22px 26px; }
    * { box-sizing: border-box; }

    body {
        font-family: Georgia, "Times New Roman", serif;
        color: #241008;
        font-size: 9.4px;
        line-height: 1.3;
        background: #fffdf8;
    }

    .caja {
        border: 1.6px solid #8a1c1c;
        border-radius: 6px;
        padding: 7px 9px;
        margin-bottom: 9px;
        background: #fffdf8;
    }

    .divisor-orn {
        text-align: center;
        color: #c9a86a;
        font-size: 9px;
        letter-spacing: 0.4em;
        margin: 2px 0 9px;
    }

    .caja-titulo {
        font-size: 8.4px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #8a1c1c;
        font-weight: bold;
        border-bottom: 1px solid #d8b98a;
        padding-bottom: 3px;
        margin-bottom: 5px;
    }

    .lineas-vacias div { border-bottom: 1px solid #e3d2ad; height: 11px; margin-bottom: 2px; }

    /* ----- Cabecera superior: identidad + nivel/CA/PG/dados/muerte ----- */
    table.cab-top { width: 100%; border-collapse: separate; border-spacing: 5px 0; margin-bottom: 6px; }
    table.cab-top td { vertical-align: top; }

    .identidad-box {
        border: 1.6px solid #8a1c1c; border-radius: 6px; padding: 6px 9px; background: #fffdf8;
    }
    .identidad-nombre { font-size: 17px; font-weight: bold; color: #6e0f0f; margin-bottom: 2px; }
    .identidad-fila { font-size: 8.6px; color: #4a3520; }
    .identidad-fila b { color: #8a1c1c; text-transform: uppercase; font-size: 7.4px; }

    .mini-circulo-wrap { text-align: center; }
    .mini-circulo {
        width: 46px; height: 46px; border: 2px solid #8a1c1c; border-radius: 50%;
        margin: 0 auto 2px; text-align: center; line-height: 46px; font-size: 18px; font-weight: bold; color: #6e0f0f;
        background: #fffdf8;
    }
    .mini-label { font-size: 6.6px; text-transform: uppercase; color: #8a1c1c; letter-spacing: 0.04em; }

    .mini-escudo-wrap { text-align: center; }
    .mini-escudo svg { width: 50px; height: 50px; }
    .mini-escudo-valor { font-size: 17px; font-weight: bold; color: #6e0f0f; margin-top: -36px; position: relative; }

    .pgbox { border: 1.6px solid #8a1c1c; border-radius: 6px; padding: 5px 7px; background: #fffdf8; text-align: center; }
    .pgbox .titulo { font-size: 6.8px; text-transform: uppercase; color: #8a1c1c; font-weight: bold; }
    .pgbox .grande { font-size: 16px; font-weight: bold; color: #3a1c08; }
    .pgbox .fila2 { font-size: 7px; color: #4a3520; margin-top: 2px; }

    .muerte-box { border: 1.6px solid #8a1c1c; border-radius: 6px; padding: 5px 7px; background: #fffdf8; text-align: center; }
    .muerte-box .titulo { font-size: 6.2px; text-transform: uppercase; color: #8a1c1c; font-weight: bold; line-height: 1.1; }
    .diamantes { font-size: 11px; letter-spacing: 2px; color: #8a1c1c; }
    .muerte-fila { font-size: 6.6px; color: #4a3520; text-align: left; }

    /* ----- Fila secundaria: bono competencia / iniciativa / velocidad / tamaño / percep pasiva ----- */
    table.fila-sec { width: 100%; border-collapse: separate; border-spacing: 5px 0; margin-bottom: 7px; }
    table.fila-sec td {
        border: 1.6px solid #8a1c1c; border-radius: 6px; text-align: center; padding: 4px 2px;
        width: 20%; background: #fffdf8;
    }
    table.fila-sec .lbl { font-size: 6.4px; text-transform: uppercase; color: #8a1c1c; letter-spacing: 0.02em; }
    table.fila-sec .val { font-size: 14px; font-weight: bold; color: #3a1c08; }

    /* ----- Columna de características ----- */
    table.layout-2col { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 8px; }
    table.layout-2col > tbody > tr > td { vertical-align: top; }
    .col-car-td { width: 21%; padding-right: 9px; }
    .col-resto-td { width: 79%; }

    .car-box { border: 1.4px solid #8a1c1c; border-radius: 6px; padding: 5px 6px; margin-bottom: 7px; background: #fffdf8; }
    .car-nombre { font-size: 8.4px; font-weight: bold; text-transform: uppercase; color: #6e0f0f; text-align: center; letter-spacing: 0.02em; }
    .car-mod { font-size: 18px; font-weight: bold; text-align: center; line-height: 1.1; color: #3a1c08; }
    .car-punt { font-size: 7.4px; text-align: center; color: #8a7752; margin-bottom: 3px; }
    .car-fila { font-size: 7.8px; padding: 0.8px 0; }
    .car-fila .dot { display: inline-block; width: 6px; height: 6px; border-radius: 50%; border: 1px solid #8a1c1c; margin-right: 3px; }
    .car-fila.activa .dot { background: #8a1c1c; }
    .car-fila.activa { font-weight: bold; color: #6e0f0f; }

    table.datos { width: 100%; border-collapse: collapse; margin-bottom: 7px; border: 1px solid #d8b98a; }
    table.datos th, table.datos td { border-bottom: 1px solid #e9d8b3; padding: 2.6px 5px; text-align: left; font-size: 8.2px; }
    table.datos th { background: linear-gradient(90deg, #8a1c1c, #a83333); color: #fdf3e3; font-weight: normal; text-transform: uppercase; letter-spacing: 0.02em; font-size: 7.2px; }
    table.datos tr:nth-child(even) td { background: #f8efd9; }

    table.layout-2col-equis { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin: 0 0 9px -8px; width: calc(100% + 8px); }
    table.layout-2col-equis > tbody > tr > td { width: 50%; vertical-align: top; }

    .check-equipo { font-size: 7.6px; }
    .check-equipo span { margin-right: 8px; }
    .check-equipo .marca { border: 1px solid #8a1c1c; transform: rotate(45deg); display: inline-block; width: 6px; height: 6px; margin-right: 2px; }

    .pagina-2 { page-break-before: always; }

    .mag-box { border: 1.6px solid #8a1c1c; border-radius: 6px; padding: 6px 8px; margin-bottom: 7px; background: #fffdf8; }
    .mag-fila { display: block; font-size: 8px; padding: 1.5px 0; }
    .mag-fila b { color: #8a1c1c; text-transform: uppercase; font-size: 7px; display: inline-block; width: 62%; }

    table.espacios { width: 100%; border-collapse: collapse; margin-bottom: 7px; }
    table.espacios td { border: 1px solid #d8b98a; padding: 3px 5px; font-size: 7.6px; text-align: center; width: 11.11%; }
    table.espacios .nivel-lbl { background: #f3e3c8; color: #8a1c1c; font-weight: bold; }

    .pie { margin-top: 8px; padding-top: 4px; border-top: 1px solid #d8b98a; font-size: 7px; color: #999; text-align: center; }

    .desc-conjuros { margin-top: 8px; }
    .desc-conjuro-item {
        border-left: 2.5px solid #8a1c1c;
        padding: 3px 0 3px 8px;
        margin-bottom: 6px;
    }
    .desc-conjuro-titulo {
        font-size: 8.6px;
        font-weight: bold;
        color: #6e0f0f;
        margin-bottom: 1.5px;
    }
    .desc-conjuro-meta {
        font-weight: normal;
        font-style: italic;
        color: #8a7752;
        font-size: 7.4px;
        margin-left: 4px;
    }
    .desc-conjuro-texto {
        font-size: 7.8px;
        color: #3a2a18;
        line-height: 1.4;
        text-align: justify;
        margin-top: 1px;
    }

    .marca-agua-titulo {
        text-align: center; font-family: Georgia, serif; font-weight: bold; letter-spacing: 0.2em;
        color: #8a1c1c; font-size: 13px; margin-bottom: 6px; text-transform: uppercase;
        border-bottom: 2px solid #8a1c1c; padding-bottom: 4px;
    }
</style>
</head>
<body>

@php
    $est = $personaje->estadisticas;
    $stats = ['FUE' => 'fuerza', 'DES' => 'destreza', 'CON' => 'constitucion', 'INT' => 'inteligencia', 'SAB' => 'sabiduria', 'CAR' => 'carisma'];
    $habilidades = [
        'Acrobacias' => 'destreza', 'Atletismo' => 'fuerza', 'Juego de Manos' => 'destreza',
        'Sigilo' => 'destreza', 'Prestidigitación' => 'destreza', 'Conocimiento arcano' => 'inteligencia',
        'Historia' => 'inteligencia', 'Investigación' => 'inteligencia', 'Naturaleza' => 'inteligencia',
        'Religión' => 'inteligencia', 'Medicina' => 'sabiduria', 'Percepción' => 'sabiduria',
        'Perspicacia' => 'sabiduria', 'Supervivencia' => 'sabiduria', 'Trato con animales' => 'sabiduria',
        'Engaño' => 'carisma', 'Intimidación' => 'carisma', 'Interpretación' => 'carisma', 'Persuasión' => 'carisma',
    ];
    $compHab = json_decode($personaje->competencias_habilidades ?? '[]', true) ?? [];
    $compSal = json_decode($personaje->competencias_salvaciones ?? '[]', true) ?? [];
    $ataques = json_decode($personaje->ataques ?? '[]', true) ?? [];
    $trucosOrdenados = $personaje->trucos->sortBy(fn($t) => $t->conjuro->nivel ?? 0)->values();
    $apariencia = array_filter([
        'Edad' => $personaje->edad, 'Altura' => $personaje->altura, 'Peso' => $personaje->peso,
        'Ojos' => $personaje->ojos, 'Piel' => $personaje->piel, 'Pelo' => $personaje->pelo,
    ]);
    $percepcionPasiva = 10 + floor((($est->sabiduria ?? 10) - 10) / 2) + (in_array('Percepción', $compHab) ? ($est->bonus_competencia ?? 2) : 0);

    $habPrincipal = $personaje->clase->habilidad_principal ?? null;
    $modAptitud = 0;
    if ($habPrincipal && $est) {
        $valAptitud = $est->{strtolower($habPrincipal)} ?? 10;
        $modAptitud = floor(($valAptitud - 10) / 2);
    }
    $bonoComp = $est->bonus_competencia ?? 2;
    $cdConjuros = 8 + $bonoComp + $modAptitud;
    $atqConjuros = $bonoComp + $modAptitud;

    $resumen = ($tipo ?? 'completa') === 'resumen';

    $escudoSvg = '<svg viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M25 4 L43 11 V24 C43 35 36 42 25 46 C14 42 7 35 7 24 V11 Z" stroke="#8a1c1c" stroke-width="2.2"/>
    </svg>';
@endphp

{{-- ====================== PÁGINA 1 ====================== --}}

<div class="marca-agua-titulo">Hoja de Personaje · Dungeons for Dummies</div>

{{-- CABECERA: identidad + nivel + CA + PG + dados + muerte --}}
<table class="cab-top">
    <tr>
        <td style="width:38%">
            <div class="identidad-box">
                <div class="identidad-nombre">{{ $personaje->nombre }}</div>
                <div class="identidad-fila"><b>Trasfondo:</b> {{ $personaje->trasfondo->nombre ?? '—' }}</div>
                <div class="identidad-fila"><b>Clase:</b> {{ $personaje->clase->nombre ?? '—' }} @if($personaje->subclase) ({{ $personaje->subclase->nombre }}) @endif</div>
                <div class="identidad-fila"><b>Especie:</b> {{ $personaje->raza->nombre ?? '—' }}</div>
                @if($personaje->divinidad)<div class="identidad-fila"><b>Divinidad:</b> {{ $personaje->divinidad }}</div>@endif
            </div>
        </td>
        <td style="width:10%">
            <div class="mini-circulo-wrap">
                <div class="mini-circulo">{{ $personaje->nivel }}</div>
                <div class="mini-label">Nivel @if($personaje->experiencia) · {{ number_format($personaje->experiencia) }} PX @endif</div>
            </div>
        </td>
        <td style="width:13%">
            <div class="mini-escudo-wrap">
                <div class="mini-escudo">{!! $escudoSvg !!}</div>
                <div class="mini-escudo-valor">{{ $est->clase_de_armadura ?? '—' }}</div>
                <div class="mini-label">Clase de Armadura</div>
            </div>
        </td>
        <td style="width:13%">
            <div class="pgbox">
                <div class="titulo">Puntos de Golpe</div>
                <div class="grande">{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}</div>
                <div class="fila2">Temp. {{ $est->pg_temporales ?? 0 }}</div>
            </div>
        </td>
        <td style="width:13%">
            <div class="pgbox">
                <div class="titulo">Dados de Golpe</div>
                <div class="grande" style="font-size:13px">{{ $est->dados_golpe_disponibles ?? '—' }}</div>
                <div class="fila2">Gastados —</div>
            </div>
        </td>
        <td style="width:13%">
            <div class="muerte-box">
                <div class="titulo">Salvaciones contra muerte</div>
                <div class="muerte-fila">Éxitos: <span class="diamantes">{{ str_repeat('◆', $est->exitos_muerte ?? 0) }}{{ str_repeat('◇', 3 - ($est->exitos_muerte ?? 0)) }}</span></div>
                <div class="muerte-fila">Fallos: <span class="diamantes">{{ str_repeat('◆', $est->fallos_muerte ?? 0) }}{{ str_repeat('◇', 3 - ($est->fallos_muerte ?? 0)) }}</span></div>
            </div>
        </td>
    </tr>
</table>

@unless($resumen)
{{-- FILA SECUNDARIA --}}
<table class="fila-sec">
    <tr>
        <td><div class="lbl">Bono Competencia</div><div class="val">+{{ $bonoComp }}</div></td>
        @php $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2); @endphp
        <td><div class="lbl">Iniciativa</div><div class="val">{{ $ini >= 0 ? '+' . $ini : $ini }}</div></td>
        <td><div class="lbl">Velocidad</div><div class="val">{{ $est->velocidad ?? 30 }} ft</div></td>
        <td><div class="lbl">Tamaño</div><div class="val" style="font-size:11px">{{ $personaje->raza->tamaño ?? 'Mediano' }}</div></td>
        <td><div class="lbl">Percepción Pasiva</div><div class="val">{{ $percepcionPasiva }}</div></td>
    </tr>
</table>

{{-- COLUMNAS: características | resto --}}
<table class="layout-2col"><tr>
<td class="col-car-td">
    @foreach($stats as $label => $attr)
        @php
            $nombreLargo = ['FUE'=>'Fuerza','DES'=>'Destreza','CON'=>'Constitución','INT'=>'Inteligencia','SAB'=>'Sabiduría','CAR'=>'Carisma'][$label];
            $val = $est ? ($est->$attr ?? 10) : 10;
            $mod = floor(($val - 10) / 2);
            $modStr = $mod >= 0 ? '+' . $mod : (string) $mod;
            $activaSalv = in_array($attr, $compSal);
        @endphp
        <div class="car-box">
            <div class="car-nombre">{{ $nombreLargo }}</div>
            <div class="car-mod">{{ $modStr }}</div>
            <div class="car-punt">Puntuación {{ $val }}</div>
            <div class="car-fila {{ $activaSalv ? 'activa' : '' }}"><span class="dot"></span>Tirada de salvación</div>
            @foreach($habilidades as $nombre => $base)
                @continue($base !== $attr)
                @php $activaHab = in_array($nombre, $compHab); @endphp
                <div class="car-fila {{ $activaHab ? 'activa' : '' }}"><span class="dot"></span>{{ $nombre }}</div>
            @endforeach
        </div>
    @endforeach
</td>
<td class="col-resto-td">
@endunless

@if($resumen) <div style="margin-top:8px"></div> @endif

    {{-- ARMAS Y TRUCOS DE DAÑO --}}
    <div class="caja">
        <div class="caja-titulo">Armas y Trucos de Daño</div>
        @if(count($ataques) > 0)
        <table class="datos">
            <tr><th>Nombre</th><th>Bonif. atq./CD</th><th>Daño y tipo</th><th>Notas</th></tr>
            @foreach($ataques as $ataque)
            <tr>
                <td>{{ $ataque['nombre'] ?? '—' }}</td>
                <td>{{ $ataque['bonificador'] ?? '—' }}</td>
                <td>{{ $ataque['daño'] ?? '—' }}</td>
                <td></td>
            </tr>
            @endforeach
        </table>
        @else
        <div class="lineas-vacias"><div></div><div></div><div></div></div>
        @endif
    </div>

    @unless($resumen)
    {{-- RASGOS DE CLASE --}}
    <div class="caja">
        <div class="caja-titulo">Rasgos de Clase</div>
        <div class="lineas-vacias"><div></div><div></div><div></div></div>
    </div>

    {{-- ATRIBUTOS DE ESPECIE / DOTES --}}
    <table class="layout-2col-equis"><tr>
        <td>
            <div class="caja">
                <div class="caja-titulo">Atributos de Especie</div>
                @if($personaje->raza && is_array($personaje->raza->rasgos) && count($personaje->raza->rasgos) > 0)
                    @foreach($personaje->raza->rasgos as $rasgo)
                        <div style="font-size:7.8px;margin-bottom:2px">• {{ is_array($rasgo) ? ($rasgo['nombre'] ?? '') : $rasgo }}</div>
                    @endforeach
                @else
                    <div class="lineas-vacias"><div></div><div></div></div>
                @endif
            </div>
        </td>
        <td>
            <div class="caja">
                <div class="caja-titulo">Dotes</div>
                <div class="lineas-vacias"><div></div><div></div></div>
            </div>
        </td>
    </tr></table>

    {{-- ENTRENAMIENTO Y COMPETENCIAS CON EQUIPO --}}
    <div class="caja">
        <div class="caja-titulo">Entrenamiento y Competencias con Equipo</div>
        @php $armadurasClase = $personaje->clase->competencias_armadura ?? []; @endphp
        <div class="check-equipo">
            <strong>Entrenamiento con armaduras:</strong>
            <span><span class="marca" style="background:{{ in_array('Ligeras', $armadurasClase) ? '#8a1c1c' : 'transparent' }}"></span>Ligeras</span>
            <span><span class="marca" style="background:{{ in_array('Medias', $armadurasClase) ? '#8a1c1c' : 'transparent' }}"></span>Medias</span>
            <span><span class="marca" style="background:{{ in_array('Pesadas', $armadurasClase) ? '#8a1c1c' : 'transparent' }}"></span>Pesadas</span>
            <span><span class="marca" style="background:{{ in_array('Escudos', $armadurasClase) ? '#8a1c1c' : 'transparent' }}"></span>Escudos</span>
        </div>
        <div style="font-size:7.8px;margin-top:4px"><strong>Armas:</strong> {{ implode(', ', $personaje->clase->competencias_armas ?? []) ?: '—' }}</div>
        <div style="font-size:7.8px;margin-top:2px"><strong>Herramientas:</strong> {{ implode(', ', $personaje->clase->competencias_herramientas ?? []) ?: '—' }}</div>
    </div>
    @endunless

@unless($resumen)
</td>
</tr></table>
@endunless

<div class="pie">Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }} · Página 1</div>

{{-- ====================== PÁGINA 2 — TRUCOS, CONJUROS Y EQUIPO ====================== --}}
<div class="pagina-2">

@unless($resumen)
    {{-- APTITUD MÁGICA --}}
    @if($habPrincipal)
    <table class="cab-top"><tr>
        <td style="width:60%">
            <div class="mag-box">
                <div class="caja-titulo" style="margin-bottom:4px">Aptitud Mágica</div>
                <span class="mag-fila"><b>Aptitud mágica:</b> {{ ucfirst($habPrincipal) }}</span>
            </div>
        </td>
        <td style="width:13%"><div class="pgbox"><div class="titulo">Mod. Aptitud</div><div class="grande">{{ $modAptitud >= 0 ? '+' . $modAptitud : $modAptitud }}</div></div></td>
        <td style="width:13%"><div class="pgbox"><div class="titulo">CD Salv. Conjuros</div><div class="grande">{{ $cdConjuros }}</div></div></td>
        <td style="width:13%"><div class="pgbox"><div class="titulo">Bonif. Atq. Conjuros</div><div class="grande">{{ $atqConjuros >= 0 ? '+' . $atqConjuros : $atqConjuros }}</div></div></td>
    </tr></table>
    @endif
@endunless

    {{-- TRUCOS Y CONJUROS PREPARADOS --}}
    <div class="caja">
        <div class="caja-titulo">Trucos y Conjuros Preparados</div>
        @if($trucosOrdenados->count() > 0)
        <table class="datos">
            <tr>
                <th style="width:30px">Nivel</th><th>Nombre</th><th>Tiempo de lanzamiento</th>
                <th>Alcance</th><th>Escuela</th><th>Componentes</th>
            </tr>
            @foreach($trucosOrdenados as $truco)
                @php
                    $c = $truco->conjuro;
                    $compTxt = $c ? collect($c->componentes ?? [])->implode(', ') : '—';
                @endphp
                <tr>
                    <td>{{ $c ? ($c->nivel == 0 ? 'Truco' : $c->nivel) : 'Truco' }}</td>
                    <td>{{ $c->nombre ?? $truco->nombre }}</td>
                    <td>{{ $c->tiempo_lanzamiento ?? '—' }}</td>
                    <td>{{ $c->alcance ?? '—' }}</td>
                    <td>{{ $c->escuela ?? '—' }}</td>
                    <td>{{ $compTxt ?: '—' }}{{ ($c->concentracion ?? false) ? ' · Conc.' : '' }}{{ ($c->ritual ?? false) ? ' · Ritual' : '' }}</td>
                </tr>
            @endforeach
        </table>

        {{-- DESCRIPCIÓN COMPLETA DE CADA CONJURO --}}
        <div class="desc-conjuros">
            @foreach($trucosOrdenados as $truco)
                @php $c = $truco->conjuro; @endphp
                @if($c && $c->descripcion)
                <div class="desc-conjuro-item">
                    <div class="desc-conjuro-titulo">
                        {{ $c->nombre }}
                        <span class="desc-conjuro-meta">{{ $c->nivel == 0 ? 'Truco' : 'Nivel ' . $c->nivel }} · {{ $c->escuela }}{{ ($c->duracion ?? null) ? ' · Duración: ' . $c->duracion : '' }}</span>
                    </div>
                    <div class="desc-conjuro-texto">{{ $c->descripcion }}</div>
                    @if($c->a_niveles_superiores)
                    <div class="desc-conjuro-texto"><strong>A niveles superiores.</strong> {{ $c->a_niveles_superiores }}</div>
                    @endif
                </div>
                @endif
            @endforeach
        </div>
        @else
        <div class="lineas-vacias"><div></div><div></div><div></div></div>
        @endif
    </div>

    {{-- EQUIPO --}}
    <div class="caja">
        <div class="caja-titulo">Equipo</div>
        @if($personaje->equipo && $personaje->equipo->count() > 0)
        <table class="datos">
            <tr><th>Objeto</th><th>Tipo</th><th>Cant.</th><th>Peso</th><th>Valor</th></tr>
            @foreach($personaje->equipo as $item)
            <tr>
                <td>{{ $item->nombre }}{{ $item->equipado ? ' (equipado)' : '' }}{{ $item->magico ? ' ✨' : '' }}</td>
                <td>{{ $item->tipo }}</td>
                <td>{{ $item->cantidad ?? 1 }}</td>
                <td>{{ $item->peso ? $item->peso . ' lb' : '—' }}</td>
                <td>{{ $item->valor_po ? $item->valor_po . ' PO' : '—' }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <div class="lineas-vacias"><div></div><div></div></div>
        @endif
    </div>

    {{-- MONEDAS --}}
    @if($est)
    <table class="fila-sec">
        <tr>
            <td><div class="lbl">PC</div><div class="val">{{ $est->monedas_cobre ?? 0 }}</div></td>
            <td><div class="lbl">PP</div><div class="val">{{ $est->monedas_plata ?? 0 }}</div></td>
            <td><div class="lbl">PE</div><div class="val">{{ $est->monedas_electrum ?? 0 }}</div></td>
            <td><div class="lbl">PO</div><div class="val">{{ $est->monedas_oro ?? 0 }}</div></td>
            <td><div class="lbl">PPT</div><div class="val">{{ $est->monedas_platino ?? 0 }}</div></td>
        </tr>
    </table>
    @endif

@unless($resumen)
    <div class="divisor-orn">❧ ❧ ❧</div>

    {{-- HISTORIA Y PERSONALIDAD --}}
    <div class="caja">
        <div class="caja-titulo">Historia y Personalidad</div>
        @if($personaje->historia || $personaje->rasgos_personalidad || $personaje->ideales || $personaje->vinculos || $personaje->defectos || $personaje->alineamiento)
            @if($personaje->alineamiento)<div style="font-size:8px;margin-bottom:3px"><strong>Alineamiento:</strong> {{ $personaje->alineamiento }}</div>@endif
            @if($personaje->historia)<div style="font-size:8px;margin-bottom:3px">{{ $personaje->historia }}</div>@endif
            @if($personaje->rasgos_personalidad)<div style="font-size:8px"><strong>Rasgos:</strong> {{ $personaje->rasgos_personalidad }}</div>@endif
            @if($personaje->ideales)<div style="font-size:8px"><strong>Ideales:</strong> {{ $personaje->ideales }}</div>@endif
            @if($personaje->vinculos)<div style="font-size:8px"><strong>Vínculos:</strong> {{ $personaje->vinculos }}</div>@endif
            @if($personaje->defectos)<div style="font-size:8px"><strong>Defectos:</strong> {{ $personaje->defectos }}</div>@endif
        @else
            <div class="lineas-vacias"><div></div><div></div><div></div></div>
        @endif
    </div>

    {{-- ASPECTO --}}
    <div class="caja">
        <div class="caja-titulo">Aspecto</div>
        @if(count($apariencia) > 0)
            @foreach($apariencia as $label => $valor)<span style="font-size:8px;margin-right:10px"><strong>{{ $label }}:</strong> {{ $valor }}</span>@endforeach
        @else
            <div class="lineas-vacias"><div></div></div>
        @endif
    </div>

    {{-- IDIOMAS --}}
    <div class="caja">
        <div class="caja-titulo">Idiomas</div>
        <div style="font-size:8px">{{ $personaje->idiomas ?? implode(', ', $personaje->raza->idiomas ?? []) ?: '—' }}</div>
    </div>
@endunless

</div>

<div class="pie">Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }} · Página 2</div>

</body>
</html>