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
        border-bottom: 2px solid #B30303;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }

    .cabecera h1 {
        font-size: 22px;
        margin: 0 0 2px;
        color: #7a0202;
    }

    .cabecera .subtitulo {
        font-size: 11px;
        color: #555;
    }

    .badges {
        margin-top: 4px;
    }

    .badge {
        display: inline-block;
        border: 1px solid #B30303;
        color: #7a0202;
        padding: 1px 7px;
        border-radius: 9px;
        font-size: 9px;
        margin-right: 4px;
    }

    .seccion-titulo {
        background: #f3e9d8;
        border-left: 3px solid #B30303;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: bold;
        color: #5a1212;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin: 10px 0 6px;
    }

    /* ----- Layout de dos columnas con floats ----- */
    .col-izq { float: left; width: 33%; padding-right: 8px; }
    .col-der { float: left; width: 67%; }
    .clear   { clear: both; }

    /* ----- Tabla de características ----- */
    table.stats {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 4px;
    }
    table.stats td {
        border: 1px solid #c9b89a;
        text-align: center;
        padding: 4px 2px;
        width: 16.6%;
    }
    table.stats .label { font-size: 8px; color: #7a0202; font-weight: bold; }
    table.stats .valor { font-size: 14px; font-weight: bold; }
    table.stats .mod   { font-size: 9px; color: #555; }

    /* ----- Tablas genéricas ----- */
    table.datos {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
    }
    table.datos th, table.datos td {
        border: 1px solid #d8cdb8;
        padding: 3px 6px;
        text-align: left;
        font-size: 9.5px;
    }
    table.datos th {
        background: #f3e9d8;
        color: #5a1212;
    }

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
@endphp

{{-- CABECERA --}}
<div class="cabecera">
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

    {{-- ATAQUES --}}
    @if(count($ataques) > 0)
    <div class="seccion-titulo">Ataques y Conjuros</div>
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

<div class="pie">
    Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }}
</div>

</body>
</html>