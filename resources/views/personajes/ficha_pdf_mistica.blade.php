<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    /*
        Plantilla 'Mística' v4 — paleta esmeralda/plata (antes morado/dorado),
        estructura de stats en tabla compacta (antes círculos apilados).
        Panel rápido en 2 columnas con contenido siempre acotado arriba;
        secciones de longitud variable apiladas a ancho completo debajo
        (necesario para que Dompdf pagine bien con textos largos).
    */
    @page { margin: 32px 36px; background-color: #0f2622; }
    * { box-sizing: border-box; }

    body {
        font-family: Georgia, "Times New Roman", serif;
        color: #22312d;
        font-size: 9.2px;
        line-height: 1.55;
        background: #0f2622;
    }

    .cabecera-orn { text-align: center; margin-bottom: 14px; }
    .cabecera-orn .linea-orn { display: inline-block; width: 30%; height: 1px; background: #b7c9a8; vertical-align: middle; }
    .cabecera-orn .rombo-orn {
        display: inline-block; width: 10px; height: 10px; border: 1.5px solid #b7c9a8;
        background: #0f2622; transform: rotate(45deg); vertical-align: middle; margin: 0 5px;
    }
    .cabecera-orn .circulo-orn {
        display: inline-block; width: 6px; height: 6px; border: 1.3px solid #b7c9a8;
        border-radius: 50%; background: #0f2622; vertical-align: middle; margin: 0 4px;
    }
    .cabecera-orn .titulo-orn {
        display: block; font-weight: bold; letter-spacing: 0.24em; color: #eef2ea;
        font-size: 14px; text-transform: uppercase; margin-bottom: 7px;
    }

    .caja {
        border: 1.4px solid #4d7a68; border-radius: 6px; padding: 11px 14px; margin-bottom: 16px;
        background: #f2f0e4;
        /* Sin page-break-inside:avoid a propósito: esta caja puede contener
           listas largas (conjuros, trasfondo) que superen el alto de una
           página. Forzar "avoid" en un bloque más alto que una página
           provoca que Dompdf pierda el contenido en vez de partirlo. */
    }
    .caja-titulo {
        font-size: 8.8px; text-transform: uppercase; letter-spacing: 0.06em;
        color: #2f5f4f; font-weight: bold; border-bottom: 1px solid #a6bfa1;
        padding-bottom: 5px; margin-bottom: 8px; position: relative; padding-left: 10px;
    }
    .caja-titulo::before {
        content: ""; position: absolute; left: 0; top: 2px;
        width: 5px; height: 5px; border: 1.2px solid #2f5f4f; transform: rotate(45deg);
    }
    .caja-texto { font-size: 8px; line-height: 1.65; color: #22312d; word-wrap: break-word; overflow-wrap: break-word; margin-bottom: 3px; }
    .lineas-vacias div { border-bottom: 1px solid #cdd6c2; height: 13px; margin-bottom: 4px; }

    /* Entradas tipo "grimorio": nombre + metadatos + descripción, para
       conjuros y dotes. Más legible que meter la descripción en una
       columna de tabla. */
    .entrada { margin-bottom: 13px; padding-bottom: 11px; border-bottom: 1px solid #dde3d3; }
    /* Sin avoid a propósito: una descripción de conjuro larga puede
       acercarse al alto de una página, y "avoid" en eso hace que Dompdf
       pierda el contenido en vez de partirlo. Preferible que, en el peor
       caso, una entrada se reparta entre dos páginas a que desaparezca. */
    .entrada:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .entrada-nombre { font-weight: bold; color: #2f5f4f; font-size: 8.6px; }
    .entrada-meta { font-size: 7.2px; color: #5c6f5a; text-transform: uppercase; letter-spacing: 0.03em; margin: 1px 0 3px; }
    .entrada-desc { font-size: 7.8px; line-height: 1.55; color: #22312d; }

    table.cab-top { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin-bottom: 12px; }
    table.cab-top td { vertical-align: top; }
    .banner-nombre {
        background: #f2f0e4; border: 1.6px solid #b7c9a8; border-radius: 4px;
        text-align: center; font-size: 18px; font-weight: bold; color: #1c3a32;
        padding: 10px 12px; letter-spacing: 0.03em;
    }
    .banner-datos { background: #f2f0e4; border: 1.4px solid #b7c9a8; border-radius: 4px; padding: 8px 10px; font-size: 8.2px; color: #22312d; line-height: 1.7; }
    .banner-datos b { color: #2f5f4f; text-transform: uppercase; font-size: 7px; }

    /* Panel rápido superior: 2 columnas, SIEMPRE contenido acotado */
    table.panel-rapido { width: 100%; border-collapse: separate; border-spacing: 0; margin-bottom: 8px; }
    table.panel-rapido > tbody > tr > td { vertical-align: top; }
    .col-stats-td { width: 36%; padding-right: 12px; }
    .col-combate-td { width: 64%; }

    /* Tabla compacta de características, en vez de círculos apilados */
    table.stats-tabla { width: 100%; border-collapse: collapse; border: 1.4px solid #4d7a68; border-radius: 4px; margin-bottom: 9px; background: #f2f0e4; }
    table.stats-tabla th {
        background: #1c3a32; color: #eef2ea; font-size: 6.6px; text-transform: uppercase;
        padding: 4px 3px; font-weight: normal; letter-spacing: 0.04em;
    }
    table.stats-tabla td { padding: 3px; text-align: center; font-size: 8px; border-top: 1px solid #dde3d3; }
    table.stats-tabla td.nombre-stat { text-align: left; font-weight: bold; color: #2f5f4f; padding-left: 6px; }
    table.stats-tabla td.mod-stat { font-weight: bold; font-size: 10px; color: #1c3a32; }
    table.stats-tabla tr:nth-child(even) td { background: #e8e6d8; }

    .skills-caja { border: 1.4px solid #4d7a68; border-radius: 4px; padding: 6px 8px; background: #f2f0e4; margin-bottom: 9px; page-break-inside: avoid; }
    .skills-caja .grupo { margin-bottom: 4px; }
    .skills-caja .grupo:last-child { margin-bottom: 0; }
    .skills-caja .grupo-stat { font-weight: bold; color: #2f5f4f; font-size: 7.4px; text-transform: uppercase; }
    .stat-fila { font-size: 7.6px; padding: 1px 0 1px 8px; }
    .stat-fila .dot { display: inline-block; width: 6px; height: 6px; border-radius: 50%; border: 1px solid #2f5f4f; margin-right: 3px; }
    .stat-fila.activa .dot { background: #2f5f4f; }
    .stat-fila.activa { font-weight: bold; color: #2f5f4f; }

    table.mini3 { width: 100%; border-collapse: separate; border-spacing: 6px 0; margin: 0 0 9px -6px; }
    table.mini3 td {
        border: 1.4px solid #2f5f4f; border-radius: 5px; text-align: center; padding: 6px 3px;
        background: #fff; width: 33.3%;
    }
    table.mini3 .lbl { font-size: 6.6px; text-transform: uppercase; color: #2f5f4f; }
    table.mini3 .val { font-size: 13px; font-weight: bold; color: #22312d; }

    .pgbox { border: 1.4px solid #2f5f4f; border-radius: 5px; padding: 8px 10px; background: #fff; margin-bottom: 9px; text-align: center; page-break-inside: avoid; }
    .pgbox .titulo { font-size: 7px; text-transform: uppercase; color: #2f5f4f; font-weight: bold; }
    .pgbox .grande { font-size: 16px; font-weight: bold; color: #22312d; }

    .misc-box { border: 1.4px solid #2f5f4f; border-radius: 5px; padding: 6px 10px; background: #fff; margin-bottom: 9px; font-size: 8.2px; color: #22312d; page-break-inside: avoid; line-height: 1.6; }
    .misc-box b { color: #2f5f4f; }

    table.datos { width: 100%; border-collapse: collapse; margin-bottom: 4px; border: 1px solid #a6bfa1; }
    table.datos th, table.datos td { border-bottom: 1px solid #dde3d3; padding: 4px 6px; text-align: left; font-size: 8px; }
    table.datos th { background: #1c3a32; color: #eef2ea; font-weight: normal; text-transform: uppercase; font-size: 6.8px; }
    table.datos tr:nth-child(even) td { background: #e8e6d8; }

    .divisor-linea { width: 100%; border-top: 1px solid #b7c9a8; margin: 10px 0 16px; position: relative; height: 1px; }
    .divisor-linea .rombo-centro {
        position: absolute; left: 50%; top: -4px; margin-left: -4px;
        width: 8px; height: 8px; border: 1.4px solid #b7c9a8; background: #0f2622; transform: rotate(45deg);
    }

    .pie { margin-top: 16px; padding-top: 7px; border-top: 1px solid #b7c9a8; font-size: 7.4px; color: #eef2ea; text-align: center; }

    .pip { display: inline-block; width: 7px; height: 7px; margin-right: 3px; border: 1.2px solid #2f5f4f; transform: rotate(45deg); vertical-align: middle; }
    .pip.lleno { background: #2f5f4f; }
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
    $dotes = $personaje->dotes ?? collect();

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

<div class="cabecera-orn">
    <span class="titulo-orn">Dungeons for Dummies</span>
    <span class="linea-orn"></span><span class="circulo-orn"></span><span class="rombo-orn"></span><span class="circulo-orn"></span><span class="linea-orn"></span>
</div>

<table class="cab-top">
    <tr>
        <td style="width:26%">
            <div class="banner-datos">
                <b>Especie</b> {{ $personaje->raza->nombre ?? '—' }}<br>
                <b>Alineamiento</b> {{ $personaje->alineamiento ?? '—' }}<br>
                <b>Trasfondo</b> {{ $personaje->trasfondo->nombre ?? '—' }}
            </div>
        </td>
        <td style="width:48%"><div class="banner-nombre">{{ $personaje->nombre }}</div></td>
        <td style="width:26%">
            <div class="banner-datos" style="text-align:right">
                <b>Clase</b> {{ $personaje->clase->nombre ?? '—' }} @if($personaje->subclase) ({{ $personaje->subclase->nombre }}) @endif<br>
                <b>Nivel</b> {{ $personaje->nivel }} @if($personaje->experiencia) · {{ number_format($personaje->experiencia) }} PX @endif
            </div>
        </td>
    </tr>
</table>

{{-- ===== PANEL RÁPIDO: solo contenido acotado, siempre cabe bien ===== --}}
@unless($resumen)
<table class="panel-rapido"><tr>

<td class="col-stats-td">
    <table class="stats-tabla">
        <tr><th>Car.</th><th>Mod.</th><th>Punt.</th></tr>
        @foreach($stats as $label => $attr)
            @php
                $val = $est ? ($est->$attr ?? 10) : 10;
                $mod = floor(($val - 10) / 2);
                $modStr = $mod >= 0 ? '+' . $mod : (string) $mod;
            @endphp
            <tr>
                <td class="nombre-stat">{{ $label }}</td>
                <td class="mod-stat">{{ $modStr }}</td>
                <td>{{ $val }}</td>
            </tr>
        @endforeach
    </table>

    <div class="skills-caja">
        @foreach($stats as $label => $attr)
            @php $activaSalv = in_array($attr, $compSal); @endphp
            <div class="grupo">
                <div class="grupo-stat">{{ $nombreLargoStat[$label] }}</div>
                <div class="stat-fila {{ $activaSalv ? 'activa' : '' }}"><span class="dot"></span>Salvación</div>
                @foreach($habilidades as $nombre => $base)
                    @continue($base !== $attr)
                    @php $activaHab = in_array($nombre, $compHab); @endphp
                    <div class="stat-fila {{ $activaHab ? 'activa' : '' }}"><span class="dot"></span>{{ $nombre }}</div>
                @endforeach
            </div>
        @endforeach
    </div>
</td>

<td class="col-combate-td">
    <table class="mini3">
        <tr>
            <td><div class="lbl">CA</div><div class="val">{{ $est->clase_de_armadura ?? '—' }}</div></td>
            <td><div class="lbl">Iniciativa</div><div class="val">{{ $ini >= 0 ? '+' . $ini : $ini }}</div></td>
            <td><div class="lbl">Velocidad</div><div class="val" style="font-size:11px">{{ $est->velocidad ?? 30 }} ft</div></td>
        </tr>
    </table>

    <div class="pgbox">
        <div class="titulo">Puntos de Golpe Máximos</div>
        <div class="grande">{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}</div>
        <div style="font-size:7px;color:#2f5f4f">Temp. {{ $est->pg_temporales ?? 0 }}</div>
    </div>

    <div class="misc-box">
        <b>Dados Golpe:</b> {{ $est->dados_golpe_disponibles ?? '—' }}
        &nbsp;&nbsp; <b>Salv. Muerte —</b>
        Éxitos @for ($i = 0; $i < 3; $i++)<span class="pip {{ $i < ($est->exitos_muerte ?? 0) ? 'lleno' : '' }}"></span>@endfor
        &nbsp; Fallos @for ($i = 0; $i < 3; $i++)<span class="pip {{ $i < ($est->fallos_muerte ?? 0) ? 'lleno' : '' }}"></span>@endfor
    </div>

    <div class="misc-box"><b>Competencia:</b> +{{ $bonoComp }} &nbsp;&nbsp; <b>Percep. Pasiva:</b> {{ $percepcionPasiva }} &nbsp;&nbsp; <b>Inspiración:</b> {{ $personaje->inspiracion ?? '—' }}</div>

    @if($habPrincipal)
    <table class="mini3">
        <tr>
            <td><div class="lbl">Bono Atq. Conjuro</div><div class="val" style="font-size:11px">{{ $atqConjuros >= 0 ? '+' . $atqConjuros : $atqConjuros }}</div></td>
            <td><div class="lbl">CD Salv. Conjuro</div><div class="val" style="font-size:11px">{{ $cdConjuros }}</div></td>
        </tr>
    </table>
    @endif
</td>

</tr></table>
@endunless

{{-- ===== SECCIONES DE ANCHO COMPLETO: longitud variable, paginan libremente ===== --}}

<div class="caja">
    <div class="caja-titulo">Ataques</div>
    @if(count($ataques) > 0)
    <table class="datos">
        <tr><th>Nombre</th><th>Bono Ataque</th><th>Daño / Tipo</th></tr>
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
    <div class="caja-titulo">Trucos y Conjuros</div>
    @if($trucosOrdenados->count() > 0)
        @foreach($trucosOrdenados as $truco)
            @php $c = $truco->conjuro; @endphp
            <div class="entrada">
                <div class="entrada-nombre">{{ $c->nombre ?? $truco->nombre }}</div>
                <div class="entrada-meta">
                    {{ $c && $c->nivel == 0 ? 'Truco' : ('Nivel ' . ($c->nivel ?? '—')) }}
                    @if($c?->escuela) · {{ $c->escuela }} @endif
                    @if($c?->alcance) · Alcance: {{ $c->alcance }} @endif
                    @if($c?->tiempo_lanzamiento) · Lanzamiento: {{ $c->tiempo_lanzamiento }} @endif
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
    <div class="caja-titulo">Apariencia y Personalidad</div>
    @php
        $apariencia = array_filter(['Edad' => $personaje->edad, 'Altura' => $personaje->altura, 'Peso' => $personaje->peso, 'Ojos' => $personaje->ojos, 'Piel' => $personaje->piel, 'Pelo' => $personaje->pelo]);
    @endphp
    @foreach($apariencia as $label => $valor)
        <div class="caja-texto"><b>{{ $label }}:</b> {{ $valor }}</div>
    @endforeach
    @if($personaje->rasgos_personalidad)<div class="caja-texto" style="margin-top:3px"><b>Rasgos:</b> {{ $personaje->rasgos_personalidad }}</div>@endif
</div>

@if($personaje->historia)
<div class="caja">
    <div class="caja-titulo">Trasfondo</div>
    <div class="caja-texto">{{ $personaje->historia }}</div>
</div>
@endif

<div class="caja">
    <div class="caja-titulo">Rasgos</div>
    @if($personaje->raza && is_array($personaje->raza->rasgos) && count($personaje->raza->rasgos) > 0)
        @foreach($personaje->raza->rasgos as $rasgo)
            <div class="caja-texto" style="margin-bottom:2px">• {{ is_array($rasgo) ? ($rasgo['nombre'] ?? '') : $rasgo }}</div>
        @endforeach
    @else
        <div class="lineas-vacias"><div></div><div></div><div></div></div>
    @endif
</div>

<div class="divisor-linea"><span class="rombo-centro"></span></div>

<div class="misc-box"><b>Idiomas:</b> {{ $personaje->idiomas ?? implode(', ', $personaje->raza->idiomas ?? []) ?: '—' }}</div>

<div class="caja">
    <div class="caja-titulo">Equipo</div>
    <table class="mini3" style="margin-bottom:9px;">
        <tr>
            <td><div class="lbl">PC</div><div class="val" style="font-size:11px">{{ $est->monedas_cobre ?? 0 }}</div></td>
            <td><div class="lbl">PP</div><div class="val" style="font-size:11px">{{ $est->monedas_plata ?? 0 }}</div></td>
            <td><div class="lbl">PO</div><div class="val" style="font-size:11px">{{ $est->monedas_oro ?? 0 }}</div></td>
        </tr>
    </table>
    @if($personaje->equipo && $personaje->equipo->count() > 0)
        @foreach($personaje->equipo as $item)
            <div class="caja-texto" style="margin-bottom:1px">• {{ $item->nombre }}{{ $item->equipado ? ' (equipado)' : '' }} @if($item->cantidad > 1) x{{ $item->cantidad }} @endif</div>
        @endforeach
    @else
        <div class="lineas-vacias"><div></div><div></div></div>
    @endif
</div>
@endunless

<div class="pie">
    <span class="circulo-orn" style="margin:0 4px 0 0"></span><span class="rombo-orn" style="margin:0 5px"></span><span class="circulo-orn" style="margin:0 0 0 4px"></span>
    <br>Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }} · v11-sin-avoid-entrada
</div>

</body>
</html>
