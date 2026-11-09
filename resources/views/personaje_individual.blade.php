@extends('layouts.app')

@section('titulo', $personaje->nombre . ' — Ficha')

@push('head')
    @vite('resources/css/pages/personaje-ficha.css')
@endpush

@section('contenido')

@php
    $est = $personaje->estadisticas;
    $habilidades = [
        'Atletismo'       => 'fuerza',
        'Acrobacias'      => 'destreza',
        'Sigilo'          => 'destreza',
        'Prestidigitación' => 'destreza',
        'Arcana'          => 'inteligencia',
        'Historia'        => 'inteligencia',
        'Investigación'   => 'inteligencia',
        'Naturaleza'      => 'inteligencia',
        'Religión'        => 'inteligencia',
        'Medicina'        => 'sabiduria',
        'Percepción'      => 'sabiduria',
        'Perspicacia'     => 'sabiduria',
        'Supervivencia'   => 'sabiduria',
        'Trato con animales' => 'sabiduria',
        'Engaño'          => 'carisma',
        'Intimidación'    => 'carisma',
        'Actuación'       => 'carisma',
        'Persuasión'      => 'carisma',
    ];
    $compHab  = json_decode($personaje->competencias_habilidades ?? '[]', true) ?? [];
    $compSal  = json_decode($personaje->competencias_salvaciones ?? '[]', true) ?? [];
    $ataques  = json_decode($personaje->ataques ?? '[]', true) ?? [];
    $imgsPersonaje = json_decode($personaje->imagenes_personaje ?? '[]', true) ?? [];
    $imgsArmas     = json_decode($personaje->imagenes_armas ?? '[]', true) ?? [];
    $stats = ['FUE'=>'fuerza','DES'=>'destreza','CON'=>'constitucion','INT'=>'inteligencia','SAB'=>'sabiduria','CAR'=>'carisma'];
    $trucosOrdenados = $personaje->trucos->sortBy(fn($t) => $t->conjuro->nivel ?? 0)->values();
@endphp

<div class="ficha-wrapper">

    {{-- CABECERA --}}
    <div class="ficha-header">
        <img class="ficha-avatar" src="{{ $personaje->avatar_url }}" alt="{{ $personaje->nombre }}"
            data-lightbox>

        <div class="ficha-titulo">
            <h1>{{ $personaje->nombre }}</h1>
            <div class="ficha-subtitulo">
                {{ $personaje->raza->nombre ?? '—' }} · {{ $personaje->clase->nombre ?? '—' }}
                @if($personaje->trasfondo) · {{ $personaje->trasfondo->nombre }} @endif
            </div>
            <div class="ficha-badges">
                <span class="badge badge-nivel">Nivel {{ $personaje->nivel }}</span>
                @if($personaje->experiencia)
                    <span class="badge badge-exp">@include('partials.icon', ['name' => 'star', 'class' => 'icon-sm']) {{ number_format($personaje->experiencia) }} XP</span>
                @endif
                @if($personaje->alineamiento)
                    <span class="badge badge-align">@include('partials.icon', ['name' => 'scroll', 'class' => 'icon-sm']) {{ $personaje->alineamiento }}</span>
                @endif
                @if($personaje->divinidad)
                    <span class="badge badge-divinidad">@include('partials.icon', ['name' => 'shield', 'class' => 'icon-sm']) {{ $personaje->divinidad }}</span>
                @endif
                @if($est && $est->inspiracion)
                    <span class="badge badge-naranja">@include('partials.icon', ['name' => 'star', 'class' => 'icon-sm']) Inspiración</span>
                @endif
            </div>
            <div class="ficha-acciones">
                <a href="{{ route('personajes.index') }}" class="btn btn-volver">@include('partials.icon', ['name' => 'arrow-left', 'class' => 'icon-sm']) Volver</a>
                <a href="{{ route('personajes.edit', $personaje) }}" class="btn btn-editar">@include('partials.icon', ['name' => 'edit', 'class' => 'icon-sm']) Editar</a>
                <a href="{{ route('personajes.exportar', $personaje) }}" class="btn btn-editar">@include('partials.icon', ['name' => 'scroll', 'class' => 'icon-sm']) Exportar Ficha</a>
                <form action="{{ route('personajes.destroy', $personaje) }}" method="POST" class="en-linea"
                    data-confirm="¿Eliminar a {{ $personaje->nombre }}? Esta acción no se puede deshacer.">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-eliminar">@include('partials.icon', ['name' => 'trash', 'class' => 'icon-sm']) Eliminar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="ficha-body">

        {{-- ESTADÍSTICAS PRINCIPALES --}}
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'star']) Características</div>
            <div class="stats-grid">
                @foreach($stats as $label => $attr)
                    @php
                        $val    = $est ? ($est->$attr ?? 10) : 10;
                        $mod    = floor(($val - 10) / 2);
                        $modStr = $mod >= 0 ? '+' . $mod : (string)$mod;
                    @endphp
                    <div class="stat-card">
                        <span class="stat-label">{{ $label }}</span>
                        <span class="stat-valor">{{ $val }}</span>
                        <span class="stat-mod">{{ $modStr }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- SALVACIONES --}}
        @if(count($compSal) > 0)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'shield']) Tiradas de Salvación (competencia)</div>
            <div class="competencias-grid">
                @foreach($stats as $label => $attr)
                    @php $activa = in_array($attr, $compSal); @endphp
                    <div class="competencia-item {{ $activa ? 'activa' : 'inactiva' }}">
                        <span class="comp-dot {{ $activa ? 'activa' : 'inactiva' }}"></span>
                        {{ $label }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- HABILIDADES --}}
        @if(count($compHab) > 0)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Competencias en Habilidades</div>
            <div class="competencias-grid">
                @foreach($habilidades as $nombre => $base)
                    @php $activa = in_array($nombre, $compHab); @endphp
                    <div class="competencia-item {{ $activa ? 'activa' : 'inactiva' }}">
                        <span class="comp-dot {{ $activa ? 'activa' : 'inactiva' }}"></span>
                        {{ $nombre }} <span class="sigla">({{ strtoupper(substr($base,0,3)) }})</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- COMBATE --}}
        @if($est)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'sword']) Combate</div>
            <div class="info-combat">
                <div class="combat-card">
                    <span class="combat-label">❤️ PG Actuales / Máx</span>
                    <div class="combat-valor">{{ $est->pg_actuales ?? '?' }} / {{ $est->pg_maximos ?? '?' }}</div>
                </div>
                @if(($est->pg_temporales ?? 0) > 0)
                <div class="combat-card">
                    <span class="combat-label">💙 PG Temporales</span>
                    <div class="combat-valor">{{ $est->pg_temporales }}</div>
                </div>
                @endif
                <div class="combat-card">
                    <span class="combat-label">🛡️ Clase de Armadura</span>
                    <div class="combat-valor">{{ $est->clase_de_armadura ?? '—' }}</div>
                </div>
                <div class="combat-card">
                    <span class="combat-label">⚡ Velocidad</span>
                    <div class="combat-valor">{{ $est->velocidad ?? 30 }} ft</div>
                </div>
                <div class="combat-card">
                    <span class="combat-label">🎯 Bonus Competencia</span>
                    <div class="combat-valor">+{{ $est->bonus_competencia ?? 2 }}</div>
                </div>
                <div class="combat-card">
                    <span class="combat-label">⚔️ Iniciativa</span>
                    @php
                        $ini = $est->iniciativa ?? floor((($est->destreza ?? 10) - 10) / 2);
                    @endphp
                    <div class="combat-valor">{{ $ini >= 0 ? '+' . $ini : $ini }}</div>
                </div>
                @if($est->dados_golpe_disponibles !== null)
                <div class="combat-card">
                    <span class="combat-label">🎲 Dados de Golpe</span>
                    <div class="combat-valor">{{ $est->dados_golpe_disponibles }}</div>
                </div>
                @endif
            </div>

            {{-- Tiradas de muerte --}}
            @if(($est->exitos_muerte ?? 0) > 0 || ($est->fallos_muerte ?? 0) > 0)
            <div class="mt-bloque">
                <div class="muerte-grid">
                    <div class="muerte-grupo">
                        <label>✔ Éxitos de muerte</label>
                        <div class="muerte-dots">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="muerte-dot {{ $i <= ($est->exitos_muerte ?? 0) ? 'exito' : '' }}"></div>
                            @endfor
                        </div>
                    </div>
                    <div class="muerte-grupo">
                        <label>✖ Fallos de muerte</label>
                        <div class="muerte-dots">
                            @for($i = 1; $i <= 3; $i++)
                                <div class="muerte-dot {{ $i <= ($est->fallos_muerte ?? 0) ? 'fallo' : '' }}"></div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- ARMAS Y ATAQUES --}}
        @if(count($ataques) > 0)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'swords']) Armas y Ataques</div>
            <table class="tabla-ataques">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Bonif.</th>
                        <th>Daño / Tipo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ataques as $ataque)
                    <tr>
                        <td>{{ $ataque['nombre'] ?? '—' }}</td>
                        <td>{{ $ataque['bonificador'] ?? '—' }}</td>
                        <td>{{ $ataque['daño'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- HISTORIA --}}
        @if($personaje->historia)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Historia</div>
            <div class="historia-box">{{ $personaje->historia }}</div>
        </div>
        @endif

        {{-- RASGOS DE PERSONALIDAD --}}
        @if($personaje->rasgos_personalidad || $personaje->ideales || $personaje->vinculos || $personaje->defectos)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'user']) Personalidad</div>
            <div class="rasgos-grid">
                @if($personaje->rasgos_personalidad)
                <div class="rasgo-card">
                    <span class="rasgo-label">Rasgos</span>
                    <div class="rasgo-texto">{{ $personaje->rasgos_personalidad }}</div>
                </div>
                @endif
                @if($personaje->ideales)
                <div class="rasgo-card">
                    <span class="rasgo-label">Ideales</span>
                    <div class="rasgo-texto">{{ $personaje->ideales }}</div>
                </div>
                @endif
                @if($personaje->vinculos)
                <div class="rasgo-card">
                    <span class="rasgo-label">Vínculos</span>
                    <div class="rasgo-texto">{{ $personaje->vinculos }}</div>
                </div>
                @endif
                @if($personaje->defectos)
                <div class="rasgo-card">
                    <span class="rasgo-label">Defectos</span>
                    <div class="rasgo-texto">{{ $personaje->defectos }}</div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- APARIENCIA --}}
        @php
            $apariencia = array_filter([
                'Edad'    => $personaje->edad,
                'Altura'  => $personaje->altura,
                'Peso'    => $personaje->peso,
                'Ojos'    => $personaje->ojos,
                'Piel'    => $personaje->piel,
                'Pelo'    => $personaje->pelo,
            ]);
        @endphp
        @if(count($apariencia) > 0)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'helmet']) Apariencia</div>
            <div class="apariencia-grid">
                @foreach($apariencia as $label => $valor)
                <div class="apariencia-item">
                    <span class="apariencia-label">{{ $label }}</span>
                    <span class="apariencia-valor">{{ $valor }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- IDIOMAS --}}
        @if($personaje->idiomas)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'map']) Idiomas</div>
            <p class="texto-suave">{{ $personaje->idiomas }}</p>
        </div>
        @endif

        {{-- GALERÍA — PERSONAJE --}}
        @if(count($imgsPersonaje) > 0)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'image']) Imágenes del Personaje</div>
            <div class="galeria">
                @foreach($imgsPersonaje as $img)
                    <img src="{{ Storage::url($img) }}"
                         alt="Imagen del personaje"
                         data-lightbox>
                @endforeach
            </div>
        </div>
        @endif

        {{-- GALERÍA — ARMAS --}}
        @if(count($imgsArmas) > 0)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'swords']) Imágenes de Armas</div>
            <div class="galeria">
                @foreach($imgsArmas as $img)
                    <img src="{{ Storage::url($img) }}"
                         alt="Imagen de arma"
                         data-lightbox>
                @endforeach
            </div>
        </div>
        @endif

        {{-- EQUIPO --}}
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'coins']) Equipo</div>
            @if($personaje->equipo && $personaje->equipo->count() > 0)
            <div class="equipo-grid">
                @foreach($personaje->equipo as $item)
                <div class="equipo-item">
                    <span class="equipo-nombre">
                        {{ $item->nombre }}
                        @if($item->equipado) <span class="badge-equipado">✓ Equipado</span> @endif
                    </span>
                    <div class="equipo-det">{{ $item->tipo }}@if($item->magico) · ✨ Mágico @endif</div>
                    @if($item->cantidad > 1) <div class="equipo-det">×{{ $item->cantidad }}</div> @endif
                    @if($item->valor_po)     <div class="equipo-det">💰 {{ $item->valor_po }} PO</div> @endif
                    @if($item->peso)         <div class="equipo-det">⚖️ {{ $item->peso }} lb</div> @endif
                    @if($item->descripcion)  <div class="equipo-det equipo-desc">{!! nl2br(e($item->descripcion)) !!}</div> @endif                </div>
                @endforeach
            </div>
            @else
            <p class="texto-vacio">Este aventurero no lleva equipo.</p>
            @endif
        </div>

        {{-- MONEDAS --}}
        @if($est)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'coins']) Tesoro</div>
            <div class="monedas-grid">
                <div class="moneda-card">
                    <span class="moneda-simbolo">🟤</span>
                    <span class="moneda-cantidad">{{ $est->monedas_cobre ?? 0 }}</span>
                    <span class="moneda-nombre">Cobre</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">⚪</span>
                    <span class="moneda-cantidad">{{ $est->monedas_plata ?? 0 }}</span>
                    <span class="moneda-nombre">Plata</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">🟡</span>
                    <span class="moneda-cantidad">{{ $est->monedas_electrum ?? 0 }}</span>
                    <span class="moneda-nombre">Electrum</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">🟠</span>
                    <span class="moneda-cantidad">{{ $est->monedas_oro ?? 0 }}</span>
                    <span class="moneda-nombre">Oro</span>
                </div>
                <div class="moneda-card">
                    <span class="moneda-simbolo">⚫</span>
                    <span class="moneda-cantidad">{{ $est->monedas_platino ?? 0 }}</span>
                    <span class="moneda-nombre">Platino</span>
                </div>
            </div>
        </div>
        @endif

        {{-- TRUCOS Y CONJUROS (al final de la página) --}}
        @if($trucosOrdenados->count() > 0)
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'book']) Trucos y Conjuros</div>
            <table class="tabla-ataques">
                <thead>
                    <tr>
                        <th>Nivel</th>
                        <th>Nombre</th>
                        <th>Escuela</th>
                        <th>Tiempo</th>
                        <th>Alcance</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>
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
                </tbody>
            </table>
        </div>
        @endif

        {{-- DOTES (justo debajo de trucos y conjuros) --}}
        @if($personaje->dotes->isNotEmpty())
        <div class="seccion">
            <div class="seccion-titulo">@include('partials.icon', ['name' => 'star']) Dotes</div>
            <table class="tabla-ataques">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($personaje->dotes as $dote)
                    <tr>
                        <td>{{ $dote->nombre }}</td>
                        <td>{{ $dote->categoria ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>{{-- /.ficha-body --}}
</div>{{-- /.ficha-wrapper --}}

@endsection
