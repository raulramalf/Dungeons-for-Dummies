<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ficha de {{ $personaje->nombre }}</title>
<style>
    @page { margin: 18px 22px; background-color: #ddc99a; }
    * { box-sizing: border-box; }

    body {
        font-family: Georgia, "Times New Roman", serif;
        color: #3a2410;
        font-size: 10px;
        line-height: 1.4;
        background: radial-gradient(ellipse at 50% 0%, #f1e4c2 0%, #ddc99a 70%, #c9b073 100%);
    }

    .lienzo { position: relative; padding: 16px; }

    .marco-doble {
        border: 1.5px solid #7a0202;
        border-radius: 4px;
        padding: 3px;
    }
    .marco-interior {
        border: 3px double #8a6a2f;
        border-radius: 4px;
        padding: 14px 18px;
        position: relative;
    }

    .esquina { position: absolute; width: 46px; height: 46px; color: #7a0202; }
    .esquina svg { width: 100%; height: 100%; }
    .esq-tl { top: -3px; left: -3px; }
    .esq-tr { top: -3px; right: -3px; transform: scaleX(-1); }
    .esq-bl { bottom: -3px; left: -3px; transform: scaleY(-1); }
    .esq-br { bottom: -3px; right: -3px; transform: scale(-1,-1); }

    .cabecera-perg { text-align: center; margin-bottom: 8px; }
    .cabecera-perg h1 {
        font-family: Georgia, serif;
        font-size: 24px;
        letter-spacing: 0.06em;
        color: #6e0f0f;
        margin: 0 0 2px;
        text-shadow: 0 1px 0 #f1e4c2;
    }
    .cabecera-perg .sub { font-size: 9.5px; color: #5a4324; letter-spacing: 0.02em; }

    .divisor { text-align: center; margin: 8px 0; color: #8a6a2f; font-size: 11px; letter-spacing: 0.3em; }

    table.medallones { width: 100%; margin: 10px 0; }
    table.medallones td { text-align: center; width: 16.6%; padding: 0 2px; }
    .medallon-wrap { position: relative; width: 60px; height: 60px; margin: 0 auto 4px; }
    .medallon-wrap svg { position: absolute; top: 0; left: 0; width: 60px; height: 60px; }
    .medallon-valor {
        position: absolute; top: 0; left: 0; width: 60px; height: 60px;
        text-align: center; line-height: 60px; font-size: 17px; font-weight: bold; color: #6e0f0f;
    }
    .medallon-label { font-size: 7.6px; text-transform: uppercase; color: #5a4324; letter-spacing: 0.05em; margin-top: 2px; }
    .medallon-punt { font-size: 7px; color: #8a7752; }

    .col-izq { float: left; width: 33%; padding-right: 10px; }
    .col-der { float: left; width: 67%; }
    .clear { clear: both; }

    .titulo-orn {
        text-align: center; font-size: 10px; text-transform: uppercase; letter-spacing: 0.1em;
        color: #6e0f0f; font-weight: bold; margin: 9px 0 5px;
    }
    .titulo-orn .ala { color: #8a6a2f; font-size: 9px; }

    .comp-fila { font-size: 8.5px; padding: 1.4px 0; color: #5a4324; }
    .comp-fila .dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; border: 1px solid #7a0202; margin-right: 4px; }
    .comp-fila.activa .dot { background: #7a0202; }
    .comp-fila.activa { font-weight: bold; color: #6e0f0f; }

    table.datos { width: 100%; border-collapse: collapse; margin-bottom: 7px; }
    table.datos th, table.datos td { border: 1px solid #b8924f; padding: 3px 6px; text-align: left; font-size: 8.6px; }
    table.datos th { background: #ecdfb8; color: #6e0f0f; font-family: Georgia, serif; }
    table.datos tr:nth-child(even) td { background: rgba(255,255,255,0.35); }

    table.combate-fila { width: 100%; border-collapse: collapse; margin-bottom: 7px; }
    table.combate-fila td {
        border: 1.5px solid #7a0202; border-radius: 5px; text-align: center;
        padding: 4px 2px; width: 16.6%; background: #f1e4c2;
    }
    table.combate-fila .lbl { font-size: 6.6px; text-transform: uppercase; color: #6e0f0f; letter-spacing: 0.03em; }
    table.combate-fila .val { font-size: 13px; font-weight: bold; color: #3a2410; }

    .caja-texto { border: 1px solid #b8924f; background: rgba(255,255,255,0.4); padding: 5px 7px; margin-bottom: 6px; font-size: 8.6px; }

    .pie { margin-top: 8px; text-align: center; font-size: 7px; color: #8a7752; letter-spacing: 0.04em; }
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

    // Ornamento de esquina reutilizable (filigrana sencilla, vector propio)
    $esquinaSvg = '<svg viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 44 C2 22 6 6 28 3" stroke="currentColor" stroke-width="2"/>
        <path d="M2 44 C18 44 30 38 32 22" stroke="currentColor" stroke-width="1.2"/>
        <circle cx="28" cy="3" r="3" fill="currentColor"/>
        <circle cx="2" cy="44" r="2.4" fill="currentColor"/>
    </svg>';

    // Medallón con doble anillo para cada característica
    $medallonSvg = '<svg viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="30" cy="30" r="28" stroke="#7a0202" stroke-width="2.5"/>
        <circle cx="30" cy="30" r="23" stroke="#b8924f" stroke-width="1"/>
    </svg>';
@endphp

<div class="lienzo">
<div class="marco-doble"><div class="marco-interior">

    <div class="esquina esq-tl">{!! $esquinaSvg !!}</div>
    <div class="esquina esq-tr">{!! $esquinaSvg !!}</div>
    <div class="esquina esq-bl">{!! $esquinaSvg !!}</div>
    <div class="esquina esq-br">{!! $esquinaSvg !!}</div>

    <div class="cabecera-perg">
        <h1>{{ $personaje->nombre }}</h1>
        <div class="sub">
            {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
            @if($personaje->subclase) ({{ $personaje->subclase->nombre }}) @endif
            @if($personaje->trasfondo) · {{ $personaje->trasfondo->nombre }} @endif
            · Nivel {{ $personaje->nivel }}
            @if($personaje->alineamiento) · {{ $personaje->alineamiento }} @endif
        </div>
    </div>
    <div class="divisor">❦ ❦ ❦</div>

    {{-- MEDALLONES DE CARACTERÍSTICAS --}}
    <table class="medallones">
        <tr>
            @foreach($stats as $label => $attr)
                @php
                    $nombreLargo = ['FUE'=>'Fuerza','DES'=>'Destreza','CON'=>'Constitución','INT'=>'Inteligencia','SAB'=>'Sabiduría','CAR'=>'Carisma'][$label];
                    $val = $est ? ($est->$attr ?? 10) : 10;
                    $mod = floor(($val - 10) / 2);
                    $modStr = $mod >= 0 ? '+' . $mod : (string) $mod;
                @endphp
                <td>
                    <div class="medallon-wrap">
                        {!! $medallonSvg !!}
                        <div class="medallon-valor">{{ $modStr }}</div>
                    </div>
                    <div class="medallon-label">{{ $nombreLargo }}</div>
                    <div class="medallon-punt">Punt. {{ $val }}</div>
                </td>
            @endforeach
        </tr>
    </table>

    {{-- COMBATE --}}
    <table class="combate-fila">
        <tr>
            <td><div class="lbl">CA</div><div class="val">{{ $est->clase_de_armadura ?? '—' }}</div></td>
            @php $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2); @endphp
            <td><div class="lbl">Iniciativa</div><div class="val">{{ $ini >= 0 ? '+' . $ini : $ini }}</div></td>
            <td><div class="lbl">Velocidad</div><div class="val">{{ $est->velocidad ?? 30 }}</div></td>
            <td><div class="lbl">PG Act./Máx</div><div class="val" style="font-size:11px">{{ $est->pg_actuales ?? '?' }}/{{ $est->pg_maximos ?? '?' }}</div></td>
            <td><div class="lbl">Bono Comp.</div><div class="val">+{{ $est->bonus_competencia ?? 2 }}</div></td>
            <td><div class="lbl">Dados Golpe</div><div class="val" style="font-size:11px">{{ $est->dados_golpe_disponibles ?? '—' }}</div></td>
        </tr>
    </table>

    <div class="col-izq">
        <div class="titulo-orn"><span class="ala">❧</span> Salvaciones <span class="ala">❧</span></div>
        @foreach($stats as $label => $attr)
            @php $activa = in_array($attr, $compSal); @endphp
            <div class="comp-fila {{ $activa ? 'activa' : '' }}"><span class="dot"></span>{{ $label }}</div>
        @endforeach

        <div class="titulo-orn"><span class="ala">❧</span> Habilidades <span class="ala">❧</span></div>
        @foreach($habilidades as $nombre => $base)
            @php $activa = in_array($nombre, $compHab); @endphp
            <div class="comp-fila {{ $activa ? 'activa' : '' }}"><span class="dot"></span>{{ $nombre }}</div>
        @endforeach
    </div>

    <div class="col-der">
        <div class="titulo-orn"><span class="ala">❧</span> Armas y Ataques <span class="ala">❧</span></div>
        @if(count($ataques) > 0)
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
        @else
        <p style="font-size:8.6px;color:#8a7752">Sin armas registradas.</p>
        @endif

        <div class="titulo-orn"><span class="ala">❧</span> Equipo <span class="ala">❧</span></div>
        @if($personaje->equipo && $personaje->equipo->count() > 0)
        <table class="datos">
            <tr><th>Objeto</th><th>Cant.</th><th>Valor</th></tr>
            @foreach($personaje->equipo as $item)
            <tr>
                <td>{{ $item->nombre }}{{ $item->magico ? ' ✨' : '' }}</td>
                <td>{{ $item->cantidad ?? 1 }}</td>
                <td>{{ $item->valor_po ? $item->valor_po . ' PO' : '—' }}</td>
            </tr>
            @endforeach
        </table>
        @endif

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

    @if($personaje->historia || $personaje->rasgos_personalidad)
    <div class="titulo-orn"><span class="ala">❧</span> Historia y Personalidad <span class="ala">❧</span></div>
    <div class="caja-texto">
        @if($personaje->historia){{ $personaje->historia }}<br><br>@endif
        @if($personaje->rasgos_personalidad)<strong>Rasgos:</strong> {{ $personaje->rasgos_personalidad }}<br>@endif
        @if($personaje->ideales)<strong>Ideales:</strong> {{ $personaje->ideales }}<br>@endif
        @if($personaje->vinculos)<strong>Vínculos:</strong> {{ $personaje->vinculos }}<br>@endif
        @if($personaje->defectos)<strong>Defectos:</strong> {{ $personaje->defectos }}@endif
    </div>
    @endif

    @if(count($apariencia) > 0 || $personaje->idiomas)
    <div class="caja-texto">
        @foreach($apariencia as $label => $valor)<strong>{{ $label }}:</strong> {{ $valor }} &nbsp; @endforeach
        @if($personaje->idiomas)<br><strong>Idiomas:</strong> {{ $personaje->idiomas }}@endif
    </div>
    @endif

    {{-- TRUCOS Y CONJUROS — al final, junto a las armas en su propio bloque --}}
    @if($trucosOrdenados->count() > 0)
    <div class="titulo-orn"><span class="ala">❧</span> Trucos y Conjuros <span class="ala">❧</span></div>
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

    <div class="pie">✦ Dungeons for Dummies · Ficha generada el {{ now()->translatedFormat('d \d\e F \d\e Y') }} ✦</div>

</div></div>
</div>
</body>
</html>