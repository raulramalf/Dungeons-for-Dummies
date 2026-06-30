<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    @page { margin: 18px 22px; background-color: #08070a; }
    * { box-sizing: border-box; }

    body {
        font-family: Georgia, "Times New Roman", serif;
        color: #c9bb98;
        font-size: 9.6px;
        line-height: 1.4;
        background: linear-gradient(180deg, #0e0c0f 0%, #08070a 100%);
    }

    .marco { border: 1.5px solid #6e5420; border-radius: 4px; padding: 3px; }
    .marco-interior { border: 1px solid #C9A53B; border-radius: 4px; padding: 12px 16px; position: relative; }

    .esquina { position: absolute; width: 34px; height: 34px; color: #C9A53B; opacity: 0.85; }
    .esquina svg { width: 100%; height: 100%; }
    .esq-tl { top: -1px; left: -1px; }
    .esq-tr { top: -1px; right: -1px; transform: scaleX(-1); }
    .esq-bl { bottom: -1px; left: -1px; transform: scaleY(-1); }
    .esq-br { bottom: -1px; right: -1px; transform: scale(-1,-1); }

    .banner { text-align: center; margin-bottom: 4px; }
    .banner-icono { width: 34px; height: 34px; margin: 0 auto 2px; color: #C9A53B; }
    .titulo-juego {
        font-size: 19px; letter-spacing: 0.16em; color: #C9A53B; font-weight: bold;
        text-transform: uppercase;
    }
    .linea-orn { border: none; border-top: 1px solid #6e0f0f; margin: 7px 0 9px; }

    table.cab { width: 100%; border-collapse: collapse; margin-bottom: 9px; }
    table.cab td { border: 1px solid #3a2418; padding: 5px 8px; font-size: 9px; background: #120e0c; }
    .cab-label { color: #C9A53B; font-size: 7px; text-transform: uppercase; letter-spacing: 0.06em; }
    .cab-nombre { font-size: 15px; font-weight: bold; color: #f1e6c8; font-family: Georgia, serif; }

    .col-izq { float: left; width: 21%; }
    .col-mid { float: left; width: 41%; padding: 0 9px; }
    .col-der { float: left; width: 38%; }
    .clear { clear: both; }

    .car-box-wrap { position: relative; margin-bottom: 5px; }
    .car-box {
        border: 1px solid #6e0f0f; border-radius: 4px; padding: 4px 6px;
        background: #120e0c;
    }
    .car-nombre { font-size: 7.4px; text-transform: uppercase; color: #C9A53B; text-align: center; letter-spacing: 0.05em; }
    .car-mod { font-size: 17px; font-weight: bold; text-align: center; color: #f1e6c8; }
    .car-punt { font-size: 6.6px; text-align: center; color: #6e5f48; margin-bottom: 2px; }

    .titulo-sec {
        font-size: 9px; text-transform: uppercase; letter-spacing: 0.08em; color: #C9A53B;
        font-weight: bold; border-bottom: 1px solid #6e0f0f; padding-bottom: 2px; margin: 7px 0 4px;
    }

    .comp-fila { font-size: 8px; padding: 1px 0; color: #786a52; }
    .comp-fila .dot { display: inline-block; width: 6px; height: 6px; border-radius: 50%; border: 1px solid #C9A53B; margin-right: 4px; }
    .comp-fila.activa { color: #f1e6c8; font-weight: bold; }
    .comp-fila.activa .dot { background: #C9A53B; }

    table.combate-fila { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
    table.combate-fila td { border: 1px solid #3a2418; text-align: center; padding: 3px 2px; width: 16.6%; background: #120e0c; }
    table.combate-fila .lbl { font-size: 6.2px; color: #C9A53B; text-transform: uppercase; }
    table.combate-fila .val { font-size: 12px; font-weight: bold; color: #f1e6c8; }

    table.datos { width: 100%; border-collapse: collapse; margin-bottom: 6px; }
    table.datos th, table.datos td { border: 1px solid #2a1a14; padding: 3px 6px; text-align: left; font-size: 8.2px; }
    table.datos th { background: #160f0c; color: #C9A53B; }
    table.datos td { color: #c9bb98; }
    table.datos tr:nth-child(even) td { background: #0e0b09; }

    .caja-texto { border: 1px solid #2a1a14; background: #120e0c; padding: 5px 7px; margin-bottom: 6px; font-size: 8.2px; }

    .pie { margin-top: 8px; text-align: center; font-size: 6.6px; color: #4a3f30; letter-spacing: 0.05em; }
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

    $esquinaSvg = '<svg viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M1 33 C1 16 5 4 22 2" stroke="currentColor" stroke-width="1.4"/>
        <path d="M1 26 C9 26 14 21 15 12" stroke="currentColor" stroke-width="0.9"/>
        <circle cx="22" cy="2" r="2" fill="currentColor"/>
    </svg>';

    // Icono de dado d20 estilizado (hexágono con número)
    $d20Svg = '<svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <polygon points="20,2 36,12 36,28 20,38 4,28 4,12" stroke="currentColor" stroke-width="1.6"/>
        <polygon points="20,2 36,12 20,20 4,12" stroke="currentColor" stroke-width="0.8"/>
        <polygon points="4,28 20,20 36,28 20,38" stroke="currentColor" stroke-width="0.8"/>
        <polygon points="4,12 20,20 4,28" stroke="currentColor" stroke-width="0.8"/>
        <polygon points="36,12 20,20 36,28" stroke="currentColor" stroke-width="0.8"/>
        <text x="20" y="24" font-size="9" text-anchor="middle" fill="currentColor" font-family="Georgia, serif">20</text>
    </svg>';
@endphp

<div class="marco"><div class="marco-interior">

    <div class="esquina esq-tl">{!! $esquinaSvg !!}</div>
    <div class="esquina esq-tr">{!! $esquinaSvg !!}</div>
    <div class="esquina esq-bl">{!! $esquinaSvg !!}</div>
    <div class="esquina esq-br">{!! $esquinaSvg !!}</div>

    <div class="banner">
        <div class="banner-icono">{!! $d20Svg !!}</div>
        <div class="titulo-juego">Dungeons &amp; Dragons</div>
    </div>
    <hr class="linea-orn">

    <table class="cab">
        <tr>
            <td style="width:55%">
                <div class="cab-label">Nombre del personaje</div>
                <div class="cab-nombre">{{ $personaje->nombre }}</div>
            </td>
            <td style="width:20%">
                <div class="cab-label">Nivel</div>
                <div class="cab-nombre" style="font-size:13px">{{ $personaje->nivel }}</div>
            </td>
            <td style="width:25%">
                <div class="cab-label">Alineamiento</div>
                <div style="font-size:9.5px">{{ $personaje->alineamiento ?? '—' }}</div>
            </td>
        </tr>
        <tr>
            <td><span class="cab-label">Raza:</span> {{ $personaje->raza->nombre ?? '—' }}</td>
            <td colspan="2"><span class="cab-label">Clase:</span> {{ $personaje->clase->nombre ?? '—' }} &nbsp;·&nbsp; <span class="cab-label">Trasfondo:</span> {{ $personaje->trasfondo->nombre ?? '—' }}</td>
        </tr>
    </table>

    <div class="col-izq">
        @foreach($stats as $label => $attr)
            @php
                $val = $est ? ($est->$attr ?? 10) : 10;
                $mod = floor(($val - 10) / 2);
                $modStr = $mod >= 0 ? '+' . $mod : (string) $mod;
            @endphp
            <div class="car-box-wrap">
                <div class="car-box">
                    <div class="car-nombre">{{ $label }}</div>
                    <div class="car-mod">{{ $modStr }}</div>
                    <div class="car-punt">{{ $val }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="col-mid">
        <table class="combate-fila">
            <tr>
                <td><div class="lbl">CA</div><div class="val">{{ $est->clase_de_armadura ?? '—' }}</div></td>
                @php $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2); @endphp
                <td><div class="lbl">Inic.</div><div class="val">{{ $ini >= 0 ? '+' . $ini : $ini }}</div></td>
                <td><div class="lbl">Vel.</div><div class="val">{{ $est->velocidad ?? 30 }}</div></td>
            </tr>
        </table>
        <table class="combate-fila">
            <tr>
                <td><div class="lbl">PG Act.</div><div class="val">{{ $est->pg_actuales ?? '?' }}</div></td>
                <td><div class="lbl">PG Máx</div><div class="val">{{ $est->pg_maximos ?? '?' }}</div></td>
                <td><div class="lbl">Dados</div><div class="val" style="font-size:9.5px">{{ $est->dados_golpe_disponibles ?? '—' }}</div></td>
            </tr>
        </table>

        <div class="titulo-sec">⚔ Armas y Ataques</div>
        @if(count($ataques) > 0)
        <table class="datos">
            <tr><th>Nombre</th><th>Bonif.</th><th>Daño</th></tr>
            @foreach($ataques as $ataque)
            <tr>
                <td>{{ $ataque['nombre'] ?? '—' }}</td>
                <td>{{ $ataque['bonificador'] ?? '—' }}</td>
                <td>{{ $ataque['daño'] ?? '—' }}</td>
            </tr>
            @endforeach
        </table>
        @else
        <p style="font-size:8px;color:#4a3f30">Sin armas registradas.</p>
        @endif

        <div class="titulo-sec">🎒 Equipo</div>
        @if($personaje->equipo && $personaje->equipo->count() > 0)
        <table class="datos">
            <tr><th>Objeto</th><th>Cant.</th><th>Valor</th></tr>
            @foreach($personaje->equipo as $item)
            <tr><td>{{ $item->nombre }}</td><td>{{ $item->cantidad ?? 1 }}</td><td>{{ $item->valor_po ? $item->valor_po . ' PO' : '—' }}</td></tr>
            @endforeach
        </table>
        @endif
    </div>

    <div class="col-der">
        <div class="titulo-sec">Salvaciones</div>
        @foreach($stats as $label => $attr)
            @php $activa = in_array($attr, $compSal); @endphp
            <div class="comp-fila {{ $activa ? 'activa' : '' }}"><span class="dot"></span>{{ $label }}</div>
        @endforeach

        <div class="titulo-sec">Habilidades</div>
        @foreach($habilidades as $nombre => $base)
            @php $activa = in_array($nombre, $compHab); @endphp
            <div class="comp-fila {{ $activa ? 'activa' : '' }}"><span class="dot"></span>{{ $nombre }}</div>
        @endforeach
    </div>

    <div class="clear"></div>

    @if($personaje->historia || $personaje->rasgos_personalidad)
    <div class="titulo-sec">Historia y Personalidad</div>
    <div class="caja-texto">
        @if($personaje->historia){{ $personaje->historia }}<br><br>@endif
        @if($personaje->rasgos_personalidad)<strong>Rasgos:</strong> {{ $personaje->rasgos_personalidad }}<br>@endif
        @if($personaje->ideales)<strong>Ideales:</strong> {{ $personaje->ideales }}<br>@endif
        @if($personaje->vinculos)<strong>Vínculos:</strong> {{ $personaje->vinculos }}<br>@endif
        @if($personaje->defectos)<strong>Defectos:</strong> {{ $personaje->defectos }}@endif
    </div>
    @endif

    {{-- TRUCOS Y CONJUROS — al final --}}
    @if($trucosOrdenados->count() > 0)
    <div class="titulo-sec">📖 Trucos y Conjuros</div>
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

    <div class="pie">Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }}</div>

</div></div>
</body>
</html>