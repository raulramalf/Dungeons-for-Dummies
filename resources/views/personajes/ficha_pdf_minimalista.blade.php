<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    @page { margin: 34px 40px; }
    * { box-sizing: border-box; }

    body {
        font-family: Helvetica, Arial, sans-serif;
        color: #1a1a1a;
        font-size: 10px;
        line-height: 1.5;
    }

    h1 {
        font-size: 26px;
        font-weight: 300;
        letter-spacing: 0.03em;
        margin: 0 0 4px;
    }

    .acento {
        width: 38px;
        height: 3px;
        background: #B30303;
        margin-bottom: 8px;
    }

    .sub {
        font-size: 10px;
        color: #777;
        margin-bottom: 14px;
    }

    hr {
        border: none;
        border-top: 1px solid #ddd;
        margin: 14px 0;
    }

    .titulo-sec {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #999;
        margin-bottom: 6px;
    }

    table.stats {
        width: 100%;
        margin-bottom: 4px;
    }
    table.stats td {
        text-align: center;
        padding: 4px 0;
        border-bottom: 1px solid #eee;
        width: 16.6%;
    }
    table.stats .lbl { font-size: 8px; color: #999; text-transform: uppercase; letter-spacing: 0.06em; }
    table.stats .val { font-size: 16px; font-weight: 300; }
    table.stats .mod { font-size: 9px; color: #555; }

    table.linea {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    table.linea td {
        padding: 4px 0;
        border-bottom: 1px solid #eee;
        font-size: 9.5px;
    }
    table.linea .lbl { color: #999; width: 35%; }

    .comp-fila {
        display: inline-block;
        width: 48%;
        font-size: 9px;
        padding: 1.5px 0;
        color: #555;
    }
    .comp-fila.activa { color: #000; font-weight: bold; }
    .comp-fila .marca { display: inline-block; width: 10px; }

    table.datos {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    table.datos th, table.datos td {
        padding: 4px 2px;
        border-bottom: 1px solid #eee;
        text-align: left;
        font-size: 9px;
    }
    table.datos th { color: #999; font-weight: normal; text-transform: uppercase; font-size: 7.5px; letter-spacing: 0.05em; }

    .texto-libre { font-size: 9.5px; color: #333; margin-bottom: 10px; }

    .pie { margin-top: 16px; font-size: 7.5px; color: #bbb; }
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
    $trucosOrdenados = $personaje->trucos->sortBy(fn($t) => $t->conjuro->nivel ?? 0)->values();
    $apariencia = array_filter([
        'Edad' => $personaje->edad, 'Altura' => $personaje->altura, 'Peso' => $personaje->peso,
        'Ojos' => $personaje->ojos, 'Piel' => $personaje->piel, 'Pelo' => $personaje->pelo,
    ]);
@endphp

<h1>{{ $personaje->nombre }}</h1>
<div class="acento"></div>
<div class="sub">
    {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
    @if($personaje->subclase) ({{ $personaje->subclase->nombre }}) @endif
    @if($personaje->trasfondo) · {{ $personaje->trasfondo->nombre }} @endif
    · Nivel {{ $personaje->nivel }}
    @if($personaje->alineamiento) · {{ $personaje->alineamiento }} @endif
</div>

<table class="stats">
    <tr>
        @foreach($stats as $label => $attr)
            @php
                $val = $est ? ($est->$attr ?? 10) : 10;
                $mod = floor(($val - 10) / 2);
                $modStr = $mod >= 0 ? '+' . $mod : (string) $mod;
            @endphp
            <td>
                <div class="lbl">{{ $label }}</div>
                <div class="val">{{ $modStr }}</div>
                <div class="mod">{{ $val }}</div>
            </td>
        @endforeach
    </tr>
</table>

<table class="linea">
    <tr><td class="lbl">Clase de Armadura</td><td>{{ $est->clase_de_armadura ?? '—' }}</td>
        <td class="lbl">Velocidad</td><td>{{ $est->velocidad ?? 30 }} ft</td></tr>
    <tr><td class="lbl">PG Actuales / Máx</td><td>{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}</td>
        <td class="lbl">Bono Competencia</td><td>+{{ $est->bonus_competencia ?? 2 }}</td></tr>
</table>

<div class="titulo-sec">Salvaciones y habilidades</div>
<div style="margin-bottom:10px">
    @foreach($stats as $label => $attr)
        @php $activa = in_array($attr, $compSal); @endphp
        <span class="comp-fila {{ $activa ? 'activa' : '' }}"><span class="marca">{{ $activa ? '●' : '○' }}</span>{{ $label }}</span>
    @endforeach
    <br>
    @foreach($habilidades as $nombre => $base)
        @php $activa = in_array($nombre, $compHab); @endphp
        <span class="comp-fila {{ $activa ? 'activa' : '' }}"><span class="marca">{{ $activa ? '●' : '○' }}</span>{{ $nombre }}</span>
    @endforeach
</div>

<div class="titulo-sec">Armas y ataques</div>
@if(count($ataques) > 0)
<table class="datos">
    <tr><th>Nombre</th><th>Bonif.</th><th>Daño / Tipo</th></tr>
    @foreach($ataques as $ataque)
    <tr><td>{{ $ataque['nombre'] ?? '—' }}</td><td>{{ $ataque['bonificador'] ?? '—' }}</td><td>{{ $ataque['daño'] ?? '—' }}</td></tr>
    @endforeach
</table>
@else
<p class="texto-libre" style="color:#999">Sin armas registradas.</p>
@endif

<div class="titulo-sec">Equipo</div>
@if($personaje->equipo && $personaje->equipo->count() > 0)
<table class="datos">
    <tr><th>Objeto</th><th>Tipo</th><th>Cant.</th><th>Valor</th></tr>
    @foreach($personaje->equipo as $item)
    <tr><td>{{ $item->nombre }}</td><td>{{ $item->tipo }}</td><td>{{ $item->cantidad ?? 1 }}</td><td>{{ $item->valor_po ? $item->valor_po . ' PO' : '—' }}</td></tr>
    @endforeach
</table>
@endif

@if($personaje->historia)
<div class="titulo-sec">Historia</div>
<p class="texto-libre">{{ $personaje->historia }}</p>
@endif

@if($personaje->rasgos_personalidad || $personaje->ideales || $personaje->vinculos || $personaje->defectos)
<div class="titulo-sec">Personalidad</div>
<p class="texto-libre">
    @if($personaje->rasgos_personalidad)<strong>Rasgos:</strong> {{ $personaje->rasgos_personalidad }}<br>@endif
    @if($personaje->ideales)<strong>Ideales:</strong> {{ $personaje->ideales }}<br>@endif
    @if($personaje->vinculos)<strong>Vínculos:</strong> {{ $personaje->vinculos }}<br>@endif
    @if($personaje->defectos)<strong>Defectos:</strong> {{ $personaje->defectos }}@endif
</p>
@endif

{{-- TRUCOS Y CONJUROS — al final --}}
@if($trucosOrdenados->count() > 0)
<div class="titulo-sec">Trucos y conjuros</div>
<table class="datos">
    <tr><th>Nivel</th><th>Nombre</th><th>Escuela</th><th>Notas</th></tr>
    @foreach($trucosOrdenados as $truco)
        @php $c = $truco->conjuro; @endphp
        <tr>
            <td>{{ $c ? ($c->nivel == 0 ? 'Truco' : $c->nivel) : 'Truco' }}</td>
            <td>{{ $c->nombre ?? $truco->nombre }}</td>
            <td>{{ $c->escuela ?? '—' }}</td>
            <td>{{ $truco->fuente ?? ($truco->descripcion ?? '—') }}</td>
        </tr>
    @endforeach
</table>
@endif

<div class="pie">Dungeons for Dummies · {{ now()->translatedFormat('d \d\e F \d\e Y') }}</div>

</body>
</html>