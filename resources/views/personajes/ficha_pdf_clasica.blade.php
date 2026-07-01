<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    /*
        Plantilla 'Clásica' — cabecera + panel rápido de stats/combate en
        2 columnas (contenido siempre acotado), y debajo secciones a ancho
        completo para todo lo de longitud variable (trasfondo, equipo,
        ataques, conjuros, rasgos). Esto es clave para Dompdf: una fila de
        tabla con contenido muy largo dentro no pagina bien (páginas en
        blanco / texto cortado). Las secciones sueltas sí paginan bien.
    */
    @page { margin: 32px 36px; background-color: #14161c; }
    * { box-sizing: border-box; }

    body {
        font-family: Georgia, "Times New Roman", serif;
        color: #e8e4da;
        font-size: 9px;
        line-height: 1.35;
        background: #14161c;
    }

    .titulo-top {
        text-align: center; font-weight: bold; letter-spacing: 0.28em;
        color: #c9974a; font-size: 15px; margin-bottom: 12px; text-transform: uppercase;
        padding-bottom: 8px; border-bottom: 2px solid #6b4a23; position: relative;
    }
    .titulo-top .sub-regla { display: block; width: 60%; height: 1px; background: #6b4a23; margin: 5px auto 0; }
    .titulo-top .rombo-top {
        display: inline-block; width: 7px; height: 7px; border: 1.3px solid #c9974a;
        background: #14161c; transform: rotate(45deg); vertical-align: middle; margin: 0 8px;
    }

    table.cab-top { width: 100%; border-collapse: separate; border-spacing: 5px 0; margin-bottom: 5px; }
    .cab-field {
        text-align: center; font-size: 8px; background: #1e222c;
        border: 1.2px solid #6b4a23; border-radius: 4px; padding: 4px 3px;
    }
    .cab-field b { display: block; color: #b89968; font-size: 7px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 1px; }
    .cab-field .valor { color: #fff; font-size: 10.5px; }

    table.cab-top2 { width: 100%; border-collapse: separate; border-spacing: 4px 0; margin-bottom: 12px; }

    .caja {
        border: 1.2px solid #6b4a23; border-radius: 4px; padding: 6px 8px; margin-bottom: 8px;
        background: #1e222c; page-break-inside: avoid;
    }
    .caja-titulo {
        font-size: 8.6px; text-transform: uppercase; letter-spacing: 0.08em; font-weight: bold;
        color: #c9974a; border-bottom: 1.3px solid #6b4a23; padding-bottom: 4px; margin-bottom: 6px;
        position: relative; padding-left: 10px;
    }
    .caja-titulo::before {
        content: ""; position: absolute; left: 0; top: 2px;
        width: 5px; height: 5px; border: 1.2px solid #c9974a; transform: rotate(45deg);
    }
    .caja-texto { font-size: 7.8px; line-height: 1.5; word-wrap: break-word; overflow-wrap: break-word; }
    .lineas-vacias div { border-bottom: 1px solid #2a2e38; height: 11px; margin-bottom: 2px; }

    .entrada { margin-bottom: 9px; padding-bottom: 8px; border-bottom: 1px solid #2a2e38; }
    .entrada:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .entrada-nombre { font-weight: bold; color: #c9974a; font-size: 8.6px; }
    .entrada-meta { font-size: 7.2px; color: #b89968; text-transform: uppercase; letter-spacing: 0.03em; margin: 1px 0 3px; }
    .entrada-desc { font-size: 7.8px; line-height: 1.55; color: #e8e4da; }

    /* Panel rápido superior: 2 columnas, SIEMPRE contenido acotado */
    table.panel-rapido { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 4px; }
    table.panel-rapido > tbody > tr > td { vertical-align: top; }
    .col-stats-td { width: 33%; padding-right: 8px; }
    .col-combate-td { width: 67%; }

    .stat-box {
        border: 1.2px solid #6b4a23; border-radius: 4px; padding: 4px 5px; margin-bottom: 5px;
        background: #1e222c; page-break-inside: avoid;
    }
    .stat-box .nombre { font-weight: bold; color: #c9974a; font-size: 8.4px; text-align: center; margin-bottom: 2px; }
    .stat-fila { font-size: 7.4px; padding: 0.5px 0; }
    .stat-fila .dot { display: inline-block; width: 6px; height: 6px; border: 1px solid #c9974a; margin-right: 3px; vertical-align: middle; }
    .stat-fila.activa .dot { background: #c9974a; }
    .stat-fila.activa { color: #ffffff; font-weight: bold; }

    .misc-box {
        border: 1.2px solid #6b4a23; border-radius: 4px; padding: 4px 6px; font-size: 8px; margin-bottom: 5px;
        background: #1e222c; page-break-inside: avoid;
    }
    .misc-box b { color: #c9974a; }

    table.mini3 { width: 100%; border-collapse: separate; border-spacing: 4px 0; margin: 0 0 6px -4px; }
    table.mini3 td {
        border: 1.2px solid #6b4a23; border-radius: 5px; text-align: center; padding: 4px 2px;
        background: #1e222c; width: 33.3%;
    }
    table.mini3 .lbl { font-size: 6.4px; text-transform: uppercase; color: #b89968; }
    table.mini3 .val { font-size: 13px; font-weight: bold; color: #fff; }

    .hp-panel {
        border: 1.2px solid #6b4a23; border-radius: 4px; padding: 5px 7px; background: #1e222c;
        margin-bottom: 6px; page-break-inside: avoid;
    }
    .hp-panel .titulo { font-size: 6.8px; text-transform: uppercase; color: #b89968; font-weight: bold; }
    .hp-panel .grande { font-size: 15px; font-weight: bold; color: #fff; }

    table.datos { width: 100%; border-collapse: collapse; margin-bottom: 2px; border: 1px solid #4a3a20; }
    table.datos th, table.datos td { border-bottom: 1px solid #332816; padding: 2.6px 4px; text-align: left; font-size: 7.8px; color: #e8e4da; }
    table.datos th { background: #332816; color: #c9974a; font-weight: normal; text-transform: uppercase; font-size: 6.8px; }
    table.datos tr:nth-child(even) td { background: #1e222c; }

    .pie { margin-top: 10px; padding-top: 4px; border-top: 1px solid #6b4a23; font-size: 7px; color: #7a7568; text-align: center; }

    .pip { display: inline-block; width: 7px; height: 7px; margin-right: 3px; border: 1.2px solid #c9974a; transform: rotate(45deg); vertical-align: middle; }
    .pip.lleno { background: #c9974a; }
</style>
</head>
<body>

@php
    $est = $personaje->estadisticas;
    $stats = ['FUE' => 'fuerza', 'DES' => 'destreza', 'CON' => 'constitucion', 'INT' => 'inteligencia', 'SAB' => 'sabiduria', 'CAR' => 'carisma'];
    $nombreLargoStat = ['FUE'=>'Fuerza','DES'=>'Destreza','CON'=>'Constitución','INT'=>'Inteligencia','SAB'=>'Sabiduría','CAR'=>'Carisma'];
    $habilidades = [
        'Acrobacias' => 'destreza', 'Atletismo' => 'fuerza', 'Juego de Manos' => 'destreza',
        'Sigilo' => 'destreza', 'Conocimiento arcano' => 'inteligencia', 'Historia' => 'inteligencia',
        'Investigación' => 'inteligencia', 'Naturaleza' => 'inteligencia', 'Religión' => 'inteligencia',
        'Medicina' => 'sabiduria', 'Percepción' => 'sabiduria', 'Perspicacia' => 'sabiduria',
        'Supervivencia' => 'sabiduria', 'Trato con animales' => 'sabiduria',
        'Engaño' => 'carisma', 'Intimidación' => 'carisma', 'Interpretación' => 'carisma', 'Persuasión' => 'carisma',
    ];
    $compHab = json_decode($personaje->competencias_habilidades ?? '[]', true) ?? [];
    $compSal = json_decode($personaje->competencias_salvaciones ?? '[]', true) ?? [];
    $ataques = json_decode($personaje->ataques ?? '[]', true) ?? [];
    $trucosOrdenados = $personaje->trucos->sortBy(fn($t) => $t->conjuro->nivel ?? 0)->values();

    $habPrincipal = $personaje->clase->habilidad_principal ?? null;
    $modAptitud = 0;
    if ($habPrincipal && $est) {
        $valAptitud = $est->{strtolower($habPrincipal)} ?? 10;
        $modAptitud = floor(($valAptitud - 10) / 2);
    }
    $bonoComp = $est->bonus_competencia ?? 2;
    $cdConjuros = 8 + $bonoComp + $modAptitud;
    $atqConjuros = $bonoComp + $modAptitud;
    $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2);
    $percepcionPasiva = 10 + floor((($est->sabiduria ?? 10) - 10) / 2) + (in_array('Percepción', $compHab) ? $bonoComp : 0);

    $resumen = ($tipo ?? 'completa') === 'resumen';
@endphp

<div class="titulo-top">
    <span class="rombo-top"></span>Dungeons for Dummies<span class="rombo-top"></span>
    <span class="sub-regla"></span>
</div>

<table class="cab-top">
    <tr>
        <td class="cab-field" style="width:33%"><b>Clase</b><span class="valor">{{ $personaje->clase->nombre ?? '—' }} @if($personaje->subclase)({{ $personaje->subclase->nombre }})@endif</span></td>
        <td class="cab-field" style="width:34%"><b>Nombre del Personaje</b><span class="valor" style="font-size:13px">{{ $personaje->nombre }}</span></td>
        <td class="cab-field" style="width:33%"><b>Especie</b><span class="valor">{{ $personaje->raza->nombre ?? '—' }}</span></td>
    </tr>
</table>
<table class="cab-top2">
    <tr>
        <td class="cab-field"><b>Nivel</b><span class="valor">{{ $personaje->nivel }}</span></td>
        <td class="cab-field"><b>Alineamiento</b><span class="valor">{{ $personaje->alineamiento ?? '—' }}</span></td>
        <td class="cab-field"><b>Divinidad</b><span class="valor">{{ $personaje->divinidad ?? '—' }}</span></td>
        <td class="cab-field"><b>Trasfondo</b><span class="valor">{{ $personaje->trasfondo->nombre ?? '—' }}</span></td>
        <td class="cab-field"><b>Altura</b><span class="valor">{{ $personaje->altura ?? '—' }}</span></td>
        <td class="cab-field"><b>Peso</b><span class="valor">{{ $personaje->peso ?? '—' }}</span></td>
    </tr>
</table>

{{-- ===== PANEL RÁPIDO: solo contenido acotado, siempre cabe bien ===== --}}
@unless($resumen)
<table class="panel-rapido"><tr>

<td class="col-stats-td">
    @foreach($stats as $label => $attr)
        @php
            $val = $est ? ($est->$attr ?? 10) : 10;
            $mod = floor(($val - 10) / 2);
            $modStr = $mod >= 0 ? '+' . $mod : (string) $mod;
            $activaSalv = in_array($attr, $compSal);
        @endphp
        <div class="stat-box">
            <div class="nombre">{{ $nombreLargoStat[$label] }} ({{ $modStr }} · {{ $val }})</div>
            <div class="stat-fila {{ $activaSalv ? 'activa' : '' }}"><span class="dot"></span>Salvación</div>
            @foreach($habilidades as $nombre => $base)
                @continue($base !== $attr)
                @php $activaHab = in_array($nombre, $compHab); @endphp
                <div class="stat-fila {{ $activaHab ? 'activa' : '' }}"><span class="dot"></span>{{ $nombre }}</div>
            @endforeach
        </div>
    @endforeach
</td>

<td class="col-combate-td">
    <table class="mini3">
        <tr>
            <td><div class="lbl">Clase Armad.</div><div class="val">{{ $est->clase_de_armadura ?? '—' }}</div></td>
            <td><div class="lbl">Iniciativa</div><div class="val">{{ $ini >= 0 ? '+' . $ini : $ini }}</div></td>
            <td><div class="lbl">Velocidad</div><div class="val" style="font-size:11px">{{ $est->velocidad ?? 30 }} ft</div></td>
        </tr>
    </table>

    <div class="hp-panel">
        <div class="titulo">Puntos de Golpe Máximos</div>
        <div class="grande">{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}</div>
        <div style="font-size:7px;color:#b89968">Temporales: {{ $est->pg_temporales ?? 0 }}</div>
    </div>

    <div class="misc-box">
        <b>Dados de Golpe:</b> {{ $est->dados_golpe_disponibles ?? '—' }}
        &nbsp;&nbsp; <b>Salv. Muerte —</b>
        Éxitos @for ($i = 0; $i < 3; $i++)<span class="pip {{ $i < ($est->exitos_muerte ?? 0) ? 'lleno' : '' }}"></span>@endfor
        &nbsp; Fallos @for ($i = 0; $i < 3; $i++)<span class="pip {{ $i < ($est->fallos_muerte ?? 0) ? 'lleno' : '' }}"></span>@endfor
    </div>

    <div class="misc-box"><b>Bono de Competencia:</b> +{{ $bonoComp }} &nbsp;&nbsp; <b>Sabiduría Pasiva:</b> {{ $percepcionPasiva }} &nbsp;&nbsp; <b>Inspiración:</b> {{ $personaje->inspiracion ?? '—' }}</div>

    @if($habPrincipal)
    <div class="misc-box">
        <b>Aptitud mágica:</b> {{ ucfirst($habPrincipal) }} &nbsp;
        <b>CD:</b> {{ $cdConjuros }} &nbsp;
        <b>Bono Atq.:</b> {{ $atqConjuros >= 0 ? '+' . $atqConjuros : $atqConjuros }}
    </div>
    @endif
</td>

</tr></table>
@endunless

{{-- ===== SECCIONES DE ANCHO COMPLETO: longitud variable, paginan libremente ===== --}}

<div class="caja">
    <div class="caja-titulo">Ataques</div>
    @if(count($ataques) > 0)
    <table class="datos">
        <tr><th>Nombre</th><th>Bono Atq.</th><th>Tipo Daño</th></tr>
        @foreach($ataques as $ataque)
        <tr><td>{{ $ataque['nombre'] ?? '—' }}</td><td>{{ $ataque['bonificador'] ?? '—' }}</td><td>{{ $ataque['daño'] ?? '—' }}</td></tr>
        @endforeach
    </table>
    @else
    <div class="lineas-vacias"><div></div><div></div></div>
    @endif
</div>

@if($habPrincipal)
<div class="caja">
    <div class="caja-titulo">Conjuros Preparados</div>
    @if($trucosOrdenados->count() > 0)
        @foreach($trucosOrdenados as $truco)
            @php $c = $truco->conjuro; @endphp
            <div class="entrada">
                <div class="entrada-nombre">{{ $c->nombre ?? $truco->nombre }}</div>
                <div class="entrada-meta">
                    {{ $c && $c->nivel == 0 ? 'Truco' : ('Nivel ' . ($c->nivel ?? '—')) }}
                    @if($c?->escuela) · {{ $c->escuela }} @endif
                    @if($c?->tiempo_lanzamiento) · Lanzamiento: {{ $c->tiempo_lanzamiento }} @endif
                    @if($c?->alcance) · Alcance: {{ $c->alcance }} @endif
                </div>
                @if($c?->descripcion ?? $truco->descripcion)
                    <div class="entrada-desc">{!! $c->descripcion ?? $truco->descripcion !!}</div>
                @endif
            </div>
        @endforeach
    @else
    <div class="lineas-vacias"><div></div><div></div></div>
    @endif
</div>
@endif

@php $dotes = $personaje->dotes ?? collect(); @endphp
@if($dotes->count() > 0)
<div class="caja">
    <div class="caja-titulo">Dotes</div>
    @foreach($dotes as $dote)
        <div class="entrada">
            <div class="entrada-nombre">{{ $dote->nombre }}</div>
            @if($dote->categoria)<div class="entrada-meta">{{ $dote->categoria }}</div>@endif
            @if($dote->descripcion)<div class="entrada-desc">{!! $dote->descripcion !!}</div>@endif
        </div>
    @endforeach
</div>
@endif

@unless($resumen)
<div class="caja">
    <div class="caja-titulo">Trasfondo</div>
    <div class="caja-texto">{{ $personaje->historia ?: '—' }}</div>
</div>

@if($personaje->rasgos_personalidad || $personaje->ideales)
<div class="caja">
    <div class="caja-titulo">Rasgos de Personalidad e Ideales</div>
    @if($personaje->rasgos_personalidad)<div class="caja-texto"><b>Rasgos:</b> {{ $personaje->rasgos_personalidad }}</div>@endif
    @if($personaje->ideales)<div class="caja-texto"><b>Ideales:</b> {{ $personaje->ideales }}</div>@endif
</div>
@endif

@if($personaje->vinculos || $personaje->defectos)
<div class="caja">
    <div class="caja-titulo">Vínculos y Defectos</div>
    @if($personaje->vinculos)<div class="caja-texto"><b>Vínculos:</b> {{ $personaje->vinculos }}</div>@endif
    @if($personaje->defectos)<div class="caja-texto"><b>Defectos:</b> {{ $personaje->defectos }}</div>@endif
</div>
@endif

<div class="caja">
    <div class="caja-titulo">Competencias e Idiomas</div>
    <div class="caja-texto">{{ $personaje->idiomas ?? implode(', ', $personaje->raza->idiomas ?? []) ?: '—' }}</div>
</div>

<div class="caja">
    <div class="caja-titulo">Rasgos Raciales</div>
    @if($personaje->raza && is_array($personaje->raza->rasgos) && count($personaje->raza->rasgos) > 0)
        @foreach($personaje->raza->rasgos as $rasgo)
            <div class="caja-texto" style="margin-bottom:2px">• {{ is_array($rasgo) ? ($rasgo['nombre'] ?? '') : $rasgo }}</div>
        @endforeach
    @else
        <div class="lineas-vacias"><div></div></div>
    @endif
</div>

<div class="caja">
    <div class="caja-titulo">Equipo e Inventario</div>
    @if($personaje->equipo && $personaje->equipo->count() > 0)
        @foreach($personaje->equipo as $item)
            <div class="caja-texto" style="margin-bottom:1px">• {{ $item->nombre }}{{ $item->equipado ? ' (equipado)' : '' }} @if($item->cantidad > 1) x{{ $item->cantidad }} @endif</div>
        @endforeach
    @else
        <div class="lineas-vacias"><div></div><div></div><div></div></div>
    @endif
</div>
@endunless

<div class="pie">Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }} · v5-html-fix</div>

</body>
</html>
