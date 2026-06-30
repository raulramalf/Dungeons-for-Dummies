<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    /*
        Plantilla PDF — compatible con Dompdf (sin flexbox/grid: tablas y floats).
        Nota: usa fuentes serif del sistema (Georgia/Times) en vez de Cinzel/EB Garamond,
        porque Dompdf necesita los .ttf registrados localmente para incrustar fuentes web.
        Si se quiere fidelidad total con la tipografía de la app, añadir los .ttf de
        Cinzel y EB Garamond a storage/fonts y registrarlos en config/dompdf.php.
    */
    @page {
        margin: 28px 32px;
    }

    * { box-sizing: border-box; }

    body {
        font-family: Georgia, "Times New Roman", serif;
        color: #2a1410;
        font-size: 10.5px;
        line-height: 1.4;
    }

    .cabecera {
        background: linear-gradient(135deg, #7a0202 0%, #4d0101 100%);
        border-radius: 6px;
        padding: 12px 16px;
        margin-bottom: 14px;
        color: #f1e6c8;
        position: relative;
        overflow: hidden;
    }

    .cabecera-fila { width: 100%; }
    .cabecera-escudo { width: 36px; height: 36px; flex-shrink: 0; }

    .cabecera h1 {
        font-size: 22px;
        margin: 0 0 2px;
        color: #fff;
        letter-spacing: 0.02em;
    }

    .cabecera .subtitulo {
        font-size: 10.5px;
        color: #e8c9a0;
    }

    .badges {
        margin-top: 6px;
    }

    .badge {
        display: inline-block;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(241,230,200,0.5);
        color: #f1e6c8;
        padding: 1.5px 8px;
        border-radius: 9px;
        font-size: 8.6px;
        margin-right: 4px;
    }

    .seccion-titulo {
        background: linear-gradient(90deg, #f3e9d8, #fdf9ef);
        border-left: 3px solid #B30303;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: bold;
        color: #5a1212;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin: 10px 0 6px;
        border-radius: 0 4px 4px 0;
    }

    /* ----- Layout de dos columnas con floats ----- */
    .col-izq { float: left; width: 33%; padding-right: 8px; }
    .col-der { float: left; width: 67%; }
    .clear   { clear: both; }

    /* ----- Tabla de características ----- */
    table.stats {
        width: 100%;
        border-collapse: separate;
        border-spacing: 4px;
        margin-bottom: 4px;
    }
    table.stats td {
        border: 1.5px solid #B30303;
        border-radius: 6px;
        background: #fdf9ef;
        text-align: center;
        padding: 5px 2px;
        width: 16.6%;
    }
    table.stats .label { font-size: 8px; color: #7a0202; font-weight: bold; letter-spacing: 0.04em; }
    table.stats .valor { font-size: 15px; font-weight: bold; }
    table.stats .mod   { font-size: 9px; color: #777; }

    /* ----- Tablas genéricas ----- */
    table.datos {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
        border: 1px solid #d8cdb8;
        border-radius: 4px;
        overflow: hidden;
    }
    table.datos th, table.datos td {
        border-bottom: 1px solid #e7decb;
        padding: 4px 6px;
        text-align: left;
        font-size: 9.5px;
    }
    table.datos th {
        background: linear-gradient(90deg, #7a0202, #9c1010);
        color: #fdf3e3;
        font-weight: normal;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-size: 8.3px;
    }
    table.datos tr:nth-child(even) td { background: #faf4e6; }

    /* ----- Lista de competencias ----- */
    .comp-item {
        display: block;
        font-size: 9.5px;
        padding: 1px 0;
    }
    .comp-on  { font-weight: bold; color: #7a0202; }
    .comp-off { color: #999; }
    .comp-dot {
        display: inline-block;
        width: 7px; height: 7px;
        border-radius: 50%;
        margin-right: 4px;
        background: #ccc;
    }
    .comp-on .comp-dot { background: #B30303; }

    .caja-texto {
        border: 1px solid #d8cdb8;
        background: #fbf7ef;
        padding: 5px 7px;
        margin-bottom: 6px;
        font-size: 9.5px;
        min-height: 12px;
    }

    .pie {
        margin-top: 14px;
        padding-top: 4px;
        border-top: 1px solid #d8cdb8;
        font-size: 8px;
        color: #999;
        text-align: center;
    }
</style>
</head>
<body>

@php
    $est = $personaje->estadisticas;
    $stats = ['FUE' => 'fuerza', 'DES' => 'destreza', 'CON' => 'constitucion', 'INT' => 'inteligencia', 'SAB' => 'sabiduria', 'CAR' => 'carisma'];
    $habilidades = [
        'Acrobacias' => 'destreza', 'Atletismo' => 'fuerza', 'Juego de Manos' => 'destreza',
        'Sigilo' => 'destreza', 'Prestidigitación' => 'destreza', 'Arcana' => 'inteligencia',
        'Historia' => 'inteligencia', 'Investigación' => 'inteligencia', 'Naturaleza' => 'inteligencia',
        'Religión' => 'inteligencia', 'Medicina' => 'sabiduria', 'Percepción' => 'sabiduria',
        'Perspicacia' => 'sabiduria', 'Supervivencia' => 'sabiduria', 'Trato con animales' => 'sabiduria',
        'Engaño' => 'carisma', 'Intimidación' => 'carisma', 'Actuación' => 'carisma', 'Persuasión' => 'carisma',
    ];
    $compHab = json_decode($personaje->competencias_habilidades ?? '[]', true) ?? [];
    $compSal = json_decode($personaje->competencias_salvaciones ?? '[]', true) ?? [];
    $ataques = json_decode($personaje->ataques ?? '[]', true) ?? [];

    $apariencia = array_filter([
        'Edad' => $personaje->edad, 'Altura' => $personaje->altura, 'Peso' => $personaje->peso,
        'Ojos' => $personaje->ojos, 'Piel' => $personaje->piel, 'Pelo' => $personaje->pelo,
    ]);
    $trucosOrdenados = $personaje->trucos->sortBy(fn($t) => $t->conjuro->nivel ?? 0)->values();

    $escudoSvg = '<svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M20 3 L35 9 V19 C35 28 29 34 20 37 C11 34 5 28 5 19 V9 Z" stroke="#f1e6c8" stroke-width="2"/>
        <path d="M13 19 L18 24 L28 13" stroke="#f1e6c8" stroke-width="2" fill="none"/>
    </svg>';
@endphp

{{-- CABECERA --}}
<div class="cabecera">
    <table class="cabecera-fila"><tr>
        <td style="width:46px;vertical-align:top">{!! $escudoSvg !!}</td>
        <td style="vertical-align:top">
            <h1>{{ $personaje->nombre }}</h1>
            <div class="subtitulo">
                {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
                @if($personaje->subclase) ({{ $personaje->subclase->nombre }}) @endif
                @if($personaje->trasfondo) · {{ $personaje->trasfondo->nombre }} @endif
            </div>
            <div class="badges">
                <span class="badge">Nivel {{ $personaje->nivel }}</span>
                @if($personaje->alineamiento)<span class="badge">{{ $personaje->alineamiento }}</span>@endif
                @if($personaje->experiencia)<span class="badge">{{ number_format($personaje->experiencia) }} XP</span>@endif
                @if($personaje->divinidad)<span class="badge">{{ $personaje->divinidad }}</span>@endif
            </div>
        </td>
    </tr></table>
</div>

{{-- CARACTERÍSTICAS --}}
<table class="stats">
    <tr>
        @foreach($stats as $label => $attr)
            @php
                $val = $est ? ($est->$attr ?? 10) : 10;
                $mod = floor(($val - 10) / 2);
                $modStr = $mod >= 0 ? '+' . $mod : (string) $mod;
            @endphp
            <td>
                <div class="label">{{ $label }}</div>
                <div class="valor">{{ $val }}</div>
                <div class="mod">{{ $modStr }}</div>
            </td>
        @endforeach
    </tr>
</table>

<div class="col-izq">

    {{-- SALVACIONES --}}
    <div class="seccion-titulo">Salvaciones</div>
    @foreach($stats as $label => $attr)
        @php $activa = in_array($attr, $compSal); @endphp
        <span class="comp-item {{ $activa ? 'comp-on' : 'comp-off' }}">
            <span class="comp-dot"></span>{{ $label }}
        </span>
    @endforeach

    {{-- HABILIDADES --}}
    <div class="seccion-titulo">Habilidades</div>
    @foreach($habilidades as $nombre => $base)
        @php $activa = in_array($nombre, $compHab); @endphp
        <span class="comp-item {{ $activa ? 'comp-on' : 'comp-off' }}">
            <span class="comp-dot"></span>{{ $nombre }}
            <span style="font-size:8px;color:#999">({{ strtoupper(substr($base, 0, 3)) }})</span>
        </span>
    @endforeach

</div>

<div class="col-der">

    {{-- COMBATE --}}
    <div class="seccion-titulo">Combate</div>
    <table class="datos">
        <tr>
            <th>PG Actuales / Máx</th>
            <td>{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}{{ ($est->pg_temporales ?? 0) > 0 ? ' (+' . $est->pg_temporales . ' temp.)' : '' }}</td>
            <th>Clase de Armadura</th>
            <td>{{ $est->clase_de_armadura ?? '—' }}</td>
        </tr>
        <tr>
            <th>Velocidad</th>
            <td>{{ $est->velocidad ?? 30 }} ft</td>
            <th>Bono Competencia</th>
            <td>+{{ $est->bonus_competencia ?? 2 }}</td>
        </tr>
        <tr>
            @php $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2); @endphp
            <th>Iniciativa</th>
            <td>{{ $ini >= 0 ? '+' . $ini : $ini }}</td>
            <th>Dados de Golpe</th>
            <td>{{ $est->dados_golpe_disponibles ?? '—' }}</td>
        </tr>
        @if(($est->exitos_muerte ?? 0) > 0 || ($est->fallos_muerte ?? 0) > 0)
        <tr>
            <th>Éxitos de muerte</th>
            <td>{{ str_repeat('●', $est->exitos_muerte ?? 0) }}{{ str_repeat('○', 3 - ($est->exitos_muerte ?? 0)) }}</td>
            <th>Fallos de muerte</th>
            <td>{{ str_repeat('●', $est->fallos_muerte ?? 0) }}{{ str_repeat('○', 3 - ($est->fallos_muerte ?? 0)) }}</td>
        </tr>
        @endif
    </table>

    {{-- ARMAS Y ATAQUES --}}
    @if(count($ataques) > 0)
    <div class="seccion-titulo">Armas y Ataques</div>
    <table class="datos">
        <tr><th>Nombre</th><th>Bonif.</th><th>Daño / Tipo</th></tr>
        @foreach($ataques as $ataque)
        <tr>
            <td>{{ $ataque['nombre'] ?? '—' }}</td>
            <td>{{ $ataque['bonificador'] ?? '—' }}</td>
            <td>{{ $ataque['daño'] ?? '—' }}</td>
        </tr>
        @endforeach
    </table>
    @endif

    {{-- TESORO --}}
    @if($est)
    <div class="seccion-titulo">Tesoro</div>
    <table class="datos">
        <tr>
            <th>PC</th><td>{{ $est->monedas_cobre ?? 0 }}</td>
            <th>PP</th><td>{{ $est->monedas_plata ?? 0 }}</td>
            <th>PE</th><td>{{ $est->monedas_electrum ?? 0 }}</td>
            <th>PO</th><td>{{ $est->monedas_oro ?? 0 }}</td>
            <th>PPT</th><td>{{ $est->monedas_platino ?? 0 }}</td>
        </tr>
    </table>
    @endif

</div>

<div class="clear"></div>

{{-- EQUIPO --}}
<div class="seccion-titulo">Equipo</div>
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
<p style="font-size:9.5px;color:#777">Este aventurero no lleva equipo registrado.</p>
@endif

{{-- PERSONALIDAD --}}
@if($personaje->rasgos_personalidad || $personaje->ideales || $personaje->vinculos || $personaje->defectos)
<div class="seccion-titulo">Personalidad</div>
<table class="datos">
    @if($personaje->rasgos_personalidad)<tr><th style="width:90px">Rasgos</th><td>{{ $personaje->rasgos_personalidad }}</td></tr>@endif
    @if($personaje->ideales)<tr><th>Ideales</th><td>{{ $personaje->ideales }}</td></tr>@endif
    @if($personaje->vinculos)<tr><th>Vínculos</th><td>{{ $personaje->vinculos }}</td></tr>@endif
    @if($personaje->defectos)<tr><th>Defectos</th><td>{{ $personaje->defectos }}</td></tr>@endif
</table>
@endif

{{-- APARIENCIA E IDIOMAS --}}
@if(count($apariencia) > 0 || $personaje->idiomas)
<div class="seccion-titulo">Apariencia e Idiomas</div>
<table class="datos">
    @if(count($apariencia) > 0)
    <tr>
        @foreach($apariencia as $label => $valor)
            <th>{{ $label }}</th><td>{{ $valor }}</td>
        @endforeach
    </tr>
    @endif
    @if($personaje->idiomas)
    <tr><th style="width:90px">Idiomas</th><td colspan="{{ max(1, count($apariencia) * 2 - 1) }}">{{ $personaje->idiomas }}</td></tr>
    @endif
</table>
@endif

{{-- HISTORIA --}}
@if($personaje->historia)
<div class="seccion-titulo">Historia</div>
<div class="caja-texto">{{ $personaje->historia }}</div>
@endif

{{-- TRUCOS Y CONJUROS (al final, como en la ficha oficial) --}}
@if($trucosOrdenados->count() > 0)
<div class="seccion-titulo">Trucos y Conjuros</div>
<table class="datos">
    <tr><th>Nivel</th><th>Nombre</th><th>Escuela</th><th>Tiempo</th><th>Alcance</th><th>Notas</th></tr>
    @foreach($trucosOrdenados as $truco)
        @php $c = $truco->conjuro; @endphp
        <tr>
            <td>{{ $c ? ($c->nivel == 0 ? 'Truco' : $c->nivel) : 'Truco' }}</td>
            <td>{{ $c->nombre ?? $truco->nombre }}</td>
            <td>{{ $c->escuela ?? '—' }}</td>
            <td>{{ $c->tiempo_lanzamiento ?? '—' }}</td>
            <td>{{ $c->alcance ?? '—' }}</td>
            <td>{{ $truco->fuente ?? ($truco->descripcion ?? '—') }}</td>
        </tr>
    @endforeach
</table>
@endif

<div class="pie">
    Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }}
</div>

</body>
</html>