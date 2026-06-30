<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    /*
        Plantilla 'Estilo Oficial' — inspirada en la maquetación de la ficha
        oficial de D&D (2024): página 1 con datos de combate y armas,
        página 2 dedicada a trucos y conjuros, igual que el documento real.
        Compatible con Dompdf: tablas/floats, sin flexbox/grid.
    */
    @page {
        margin: 26px 30px;
    }

    * { box-sizing: border-box; }

    body {
        font-family: Georgia, "Times New Roman", serif;
        color: #241008;
        font-size: 10px;
        line-height: 1.35;
    }

    .marco {
        border: 2px solid #8a1c1c;
        border-radius: 6px;
        padding: 8px 10px;
        margin-bottom: 8px;
    }

    .titulo-marco {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #8a1c1c;
        font-weight: bold;
        border-bottom: 1px solid #d8b98a;
        padding-bottom: 3px;
        margin-bottom: 5px;
    }

    /* ----- Cabecera tipo ficha oficial ----- */
    table.cab {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    table.cab td {
        border: 2px solid #8a1c1c;
        border-radius: 4px;
        padding: 6px 8px;
        vertical-align: top;
        background: linear-gradient(180deg, #fffdf7, #f7ecd4);
    }
    .cab-nombre { font-size: 18px; font-weight: bold; color: #6e0f0f; }
    .cab-sub { font-size: 9.5px; color: #555; margin-top: 2px; }
    .cab-dato-label { font-size: 7.5px; text-transform: uppercase; color: #8a1c1c; }
    .cab-dato-valor { font-size: 16px; font-weight: bold; text-align: center; }

    /* ----- Columna de características (estilo ficha oficial) ----- */
    .col-car { float: left; width: 22%; }
    .col-resto { float: left; width: 78%; padding-left: 10px; }
    .clear { clear: both; }

    .car-box {
        border: 1.5px solid #8a1c1c;
        border-radius: 6px;
        padding: 5px 6px;
        margin-bottom: 6px;
        background: linear-gradient(180deg, #fffdf7, #f3e3c8);
    }
    .car-nombre {
        font-size: 9px;
        font-weight: bold;
        text-transform: uppercase;
        color: #6e0f0f;
        text-align: center;
        letter-spacing: 0.03em;
    }
    .car-mod {
        font-size: 20px;
        font-weight: bold;
        text-align: center;
        line-height: 1.1;
        color: #3a1c08;
    }
    .car-punt {
        font-size: 8px;
        text-align: center;
        color: #8a7752;
        margin-bottom: 4px;
    }
    .car-fila {
        font-size: 8.3px;
        padding: 1px 0;
    }
    .car-fila .dot {
        display: inline-block;
        width: 6px; height: 6px;
        border-radius: 50%;
        border: 1px solid #8a1c1c;
        margin-right: 3px;
    }
    .car-fila.activa .dot { background: #8a1c1c; }
    .car-fila.activa { font-weight: bold; color: #6e0f0f; }

    /* ----- Fila de stats de combate (CA, iniciativa, velocidad...) ----- */
    table.combate-fila {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
    }
    table.combate-fila td {
        border: 1.5px solid #8a1c1c;
        border-radius: 4px;
        text-align: center;
        padding: 4px 2px;
        width: 16.6%;
        background: linear-gradient(180deg, #fffdf7, #f3e3c8);
    }
    table.combate-fila .lbl { font-size: 7px; text-transform: uppercase; color: #8a1c1c; letter-spacing: 0.03em; }
    table.combate-fila .val { font-size: 15px; font-weight: bold; color: #3a1c08; }

    table.datos {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
        border: 1px solid #d8b98a;
    }
    table.datos th, table.datos td {
        border-bottom: 1px solid #e9d8b3;
        padding: 3px 6px;
        text-align: left;
        font-size: 9px;
    }
    table.datos th {
        background: linear-gradient(90deg, #8a1c1c, #a83333);
        color: #fdf3e3;
        font-weight: normal;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-size: 7.8px;
    }
    table.datos tr:nth-child(even) td { background: #f8efd9; }

    .caja-texto {
        border: 1px solid #d8b98a;
        background: #fbf5e8;
        padding: 5px 7px;
        margin-bottom: 6px;
        font-size: 9px;
        min-height: 12px;
    }

    .pagina-2 { page-break-before: always; }

    .pie {
        margin-top: 10px;
        padding-top: 4px;
        border-top: 1px solid #d8b98a;
        font-size: 7.5px;
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
@endphp

{{-- ====================== PÁGINA 1 ====================== --}}

{{-- CABECERA --}}
<table class="cab">
    <tr>
        <td style="width:55%">
            <div class="cab-nombre">{{ $personaje->nombre }}</div>
            <div class="cab-sub">
                Trasfondo: {{ $personaje->trasfondo->nombre ?? '—' }} &nbsp;·&nbsp;
                Clase: {{ $personaje->clase->nombre ?? '—' }} &nbsp;·&nbsp;
                Especie: {{ $personaje->raza->nombre ?? '—' }}
                @if($personaje->subclase) &nbsp;·&nbsp; Subclase: {{ $personaje->subclase->nombre }} @endif
            </div>
        </td>
        <td style="width:15%">
            <div class="cab-dato-label">Nivel / PX</div>
            <div class="cab-dato-valor">{{ $personaje->nivel }} @if($personaje->experiencia) <span style="font-size:9px">({{ number_format($personaje->experiencia) }} PX)</span> @endif</div>
        </td>
        <td style="width:15%">
            <div class="cab-dato-label">Clase de Armadura</div>
            <div class="cab-dato-valor">{{ $est->clase_de_armadura ?? '—' }}</div>
        </td>
        <td style="width:15%">
            <div class="cab-dato-label">PG Máx / Dados de Golpe</div>
            <div class="cab-dato-valor" style="font-size:13px">{{ $est->pg_maximos ?? '?' }} / {{ $est->dados_golpe_disponibles ?? '—' }}</div>
        </td>
    </tr>
</table>

{{-- COLUMNA CARACTERÍSTICAS + RESTO --}}
<div class="col-car">
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
            <div class="car-fila {{ $activaSalv ? 'activa' : '' }}">
                <span class="dot"></span>Tirada de salvación
            </div>
            @foreach($habilidades as $nombre => $base)
                @continue($base !== $attr)
                @php $activaHab = in_array($nombre, $compHab); @endphp
                <div class="car-fila {{ $activaHab ? 'activa' : '' }}">
                    <span class="dot"></span>{{ $nombre }}
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<div class="col-resto">

    {{-- FILA DE COMBATE --}}
    <table class="combate-fila">
        <tr>
            @php $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2); @endphp
            <td><div class="lbl">Iniciativa</div><div class="val">{{ $ini >= 0 ? '+' . $ini : $ini }}</div></td>
            <td><div class="lbl">Velocidad</div><div class="val">{{ $est->velocidad ?? 30 }} ft</div></td>
            <td><div class="lbl">Bono Comp.</div><div class="val">+{{ $est->bonus_competencia ?? 2 }}</div></td>
            <td><div class="lbl">Percepción Pasiva</div><div class="val">{{ $percepcionPasiva }}</div></td>
            <td><div class="lbl">PG Actuales</div><div class="val">{{ $est->pg_actuales ?? '?' }}</div></td>
            <td><div class="lbl">Inspiración</div><div class="val">{{ ($est->inspiracion ?? false) ? '✦' : '—' }}</div></td>
        </tr>
    </table>

    {{-- TIRADAS DE MUERTE --}}
    @if(($est->exitos_muerte ?? 0) > 0 || ($est->fallos_muerte ?? 0) > 0)
    <table class="datos">
        <tr>
            <th style="width:120px">Salvaciones contra muerte</th>
            <td>Éxitos: {{ str_repeat('● ', $est->exitos_muerte ?? 0) }}{{ str_repeat('○ ', 3 - ($est->exitos_muerte ?? 0)) }}
                &nbsp;&nbsp; Fallos: {{ str_repeat('● ', $est->fallos_muerte ?? 0) }}{{ str_repeat('○ ', 3 - ($est->fallos_muerte ?? 0)) }}</td>
        </tr>
    </table>
    @endif

    {{-- ARMAS Y ATAQUES --}}
    <div class="titulo-marco">Armas y Trucos de Daño</div>
    @if(count($ataques) > 0)
    <table class="datos">
        <tr><th>Nombre</th><th>Bonif. atq./CD</th><th>Daño y tipo</th></tr>
        @foreach($ataques as $ataque)
        <tr>
            <td>{{ $ataque['nombre'] ?? '—' }}</td>
            <td>{{ $ataque['bonificador'] ?? '—' }}</td>
            <td>{{ $ataque['daño'] ?? '—' }}</td>
        </tr>
        @endforeach
    </table>
    @else
    <p style="font-size:9px;color:#777">Sin armas registradas.</p>
    @endif

    {{-- EQUIPO --}}
    <div class="titulo-marco">Equipo</div>
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
    @endif

    {{-- MONEDAS --}}
    @if($est)
    <table class="combate-fila">
        <tr>
            <td><div class="lbl">PC</div><div class="val">{{ $est->monedas_cobre ?? 0 }}</div></td>
            <td><div class="lbl">PP</div><div class="val">{{ $est->monedas_plata ?? 0 }}</div></td>
            <td><div class="lbl">PE</div><div class="val">{{ $est->monedas_electrum ?? 0 }}</div></td>
            <td><div class="lbl">PO</div><div class="val">{{ $est->monedas_oro ?? 0 }}</div></td>
            <td><div class="lbl">PPT</div><div class="val">{{ $est->monedas_platino ?? 0 }}</div></td>
        </tr>
    </table>
    @endif

</div>
<div class="clear"></div>

{{-- RASGOS Y PERSONALIDAD --}}
@if($personaje->historia || $personaje->rasgos_personalidad || $personaje->ideales || $personaje->vinculos || $personaje->defectos)
<div class="titulo-marco">Historia y Personalidad</div>
<div class="caja-texto">
    @if($personaje->historia){{ $personaje->historia }}<br><br>@endif
    @if($personaje->rasgos_personalidad)<strong>Rasgos:</strong> {{ $personaje->rasgos_personalidad }}<br>@endif
    @if($personaje->ideales)<strong>Ideales:</strong> {{ $personaje->ideales }}<br>@endif
    @if($personaje->vinculos)<strong>Vínculos:</strong> {{ $personaje->vinculos }}<br>@endif
    @if($personaje->defectos)<strong>Defectos:</strong> {{ $personaje->defectos }}@endif
</div>
@endif

@if(count($apariencia) > 0 || $personaje->idiomas)
<div class="titulo-marco">Aspecto e Idiomas</div>
<div class="caja-texto">
    @foreach($apariencia as $label => $valor)<strong>{{ $label }}:</strong> {{ $valor }} &nbsp; @endforeach
    @if($personaje->idiomas)<br><strong>Idiomas:</strong> {{ $personaje->idiomas }}@endif
</div>
@endif

<div class="pie">
    Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }} · Página 1
</div>

{{-- ====================== PÁGINA 2 — TRUCOS Y CONJUROS ====================== --}}
@if($trucosOrdenados->count() > 0)
<div class="pagina-2">
    <div class="titulo-marco" style="font-size:12px;text-align:center;margin-bottom:8px">
        Trucos y Conjuros Preparados — {{ $personaje->nombre }}
    </div>
    <table class="datos">
        <tr>
            <th style="width:40px">Nivel</th>
            <th>Nombre</th>
            <th>Tiempo de lanzamiento</th>
            <th>Alcance</th>
            <th>Escuela</th>
            <th>Notas</th>
        </tr>
        @foreach($trucosOrdenados as $truco)
            @php $c = $truco->conjuro; @endphp
            <tr>
                <td>{{ $c ? ($c->nivel == 0 ? 'Truco' : $c->nivel) : 'Truco' }}</td>
                <td>{{ $c->nombre ?? $truco->nombre }}</td>
                <td>{{ $c->tiempo_lanzamiento ?? '—' }}</td>
                <td>{{ $c->alcance ?? '—' }}</td>
                <td>{{ $c->escuela ?? '—' }}</td>
                <td>{{ $truco->fuente ?? ($truco->descripcion ?? '—') }}</td>
            </tr>
        @endforeach
    </table>

    @if($est)
    <table class="combate-fila">
        <tr>
            <td><div class="lbl">Aptitud mágica</div><div class="val">{{ ucfirst($personaje->clase->habilidad_principal ?? '—') }}</div></td>
            <td><div class="lbl">Bono Competencia</div><div class="val">+{{ $est->bonus_competencia ?? 2 }}</div></td>
        </tr>
    </table>
    @endif

    <div class="pie">
        Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }} · Página 2
    </div>
</div>
@endif

</body>
</html>