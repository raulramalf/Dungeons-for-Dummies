@extends('layouts.app')

@section('titulo', 'Reto Diario')

@push('head')
    @vite(['resources/css/pages/minijuego.css', 'resources/js/pages/minijuego.js'])
@endpush

@section('contenido')


<div class="rd-wrap"
     x-data="retoDiario({
        estadoInicial: @js($intento->estado),
        vidaInicial: @js($intento->vida_restante),
        vidaMax: @js($enemigo->vida_max),
        rondaInicial: @js($rondaActual),
        rachaInicial: @js($racha),
        aciertosInicial: @js(collect($intento->opciones_elegidas ?? [])->where('correcta', true)->count()),
        fallosInicial: @js(collect($intento->opciones_elegidas ?? [])->where('correcta', false)->count()),
        nombreEnemigo: @js($enemigo->nombre),
        urlResponder: @js(route('minijuego.responder')),
        proximoCambioInicial: @js($proximoCambio->toIso8601String()),
     })"
     x-cloak
>
    <div class="rd-centrado">
        <span class="rd-racha">
            @include('partials.icon', ['name' => 'flame'])
            Racha: <span x-text="racha + (racha === 1 ? ' día' : ' días')"></span>
        </span>
    </div>

    <div class="rd-hero rd-fondo-{{ $enemigo->escenario ?? 'cripta' }}">
        <div class="rd-enemigo">
            @if($enemigo->imagen)
                <img src="{{ asset('storage/' . $enemigo->imagen) }}" alt="{{ $enemigo->nombre }}">
            @else
                <div class="rd-arte">
                    @include('partials.minijuego-arte', ['arte' => $enemigo->arte])
                </div>
            @endif

            <span class="rd-tipo">{{ $enemigo->titulo ?? $enemigo->tipo }}</span>
            <h2>{{ $enemigo->nombre }}</h2>
            @if($enemigo->descripcion)
                <p class="desc">{{ $enemigo->descripcion }}</p>
            @endif

            <div class="rd-vida-shell">
                <div class="rd-vida-label">
                    <span>Puntos de golpe</span>
                    <span><span x-text="vida"></span> / {{ $enemigo->vida_max }}</span>
                </div>
                <div class="rd-vida-barra">
                    <div class="rd-vida-segmentos"></div>
                    <div class="rd-vida-fill" :style="`width:${(vida / vidaMax) * 100}%`"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- MARCADOR: corazones del jugador + progreso --}}
    <div class="rd-panel">
        <div class="rd-corazones" role="img" :aria-label="`Vidas: ${4 - fallos} de 4`">
            <template x-for="n in 4" :key="n">
                <span class="rd-corazon" :class="{ 'perdido': n > 4 - fallos }">@include('partials.icon', ['name' => 'heart'])</span>
            </template>
        </div>
        <div class="rd-aciertos"><span class="rd-aciertos-n" x-text="aciertos"></span>/4 golpes</div>
    </div>

    {{-- EN CURSO --}}
    <template x-if="estado === 'en_curso' && ronda">
        <div class="rd-ronda" :class="{ 'rd-sacudida': ultimaCorrecta === false }">
            <div class="rd-ronda-cab">
                <span class="rd-chip">Pregunta <span x-text="ronda.numero"></span></span>
                <span class="rd-chip rd-chip--cat" x-show="ronda.categoria" x-text="ronda.categoria"></span>
                <span class="rd-chip rd-chip--dif" :data-dif="ronda.dificultad" x-text="ronda.dificultad"></span>
                <span class="rd-tiempo" :class="{ 'urgente': segundos <= 5 }">
                    @include('partials.icon', ['name' => 'timer', 'class' => 'icon-sm'])
                    <span x-text="segundos"></span>s
                </span>
            </div>
            <div class="rd-tiempo-barra"><div class="rd-tiempo-fill" :class="{ 'urgente': segundos <= 5 }" :style="`width:${(segundos / limite) * 100}%`"></div></div>

            <p class="rd-ronda-pregunta" x-text="ronda.pregunta"></p>

            <div class="rd-opciones">
                <template x-for="(opcion, i) in ronda.opciones" :key="i">
                    <button type="button" class="rd-opcion"
                            :class="{
                                'correcta': revelada && indiceCorrecta === i,
                                'incorrecta': revelada && marcada === i && indiceCorrecta !== i
                            }"
                            :disabled="enviando || revelada"
                            @click="responder(i)">
                        <span class="rd-letra" x-text="'ABCD'[i]"></span>
                        <span class="rd-opcion-texto" x-text="opcion.texto"></span>
                    </button>
                </template>
            </div>

            <div class="rd-explicacion" x-show="revelada" x-cloak>
                <p class="rd-feedback" :class="ultimaCorrecta ? 'ok' : 'ko'" x-text="feedback"></p>
                <p class="rd-expl-texto" x-show="explicacion">
                    @include('partials.icon', ['name' => 'lightbulb', 'class' => 'icon-sm'])
                    <span x-text="explicacion"></span>
                </p>
                <button type="button" class="btn btn-primario" x-show="revelada && !enviando" @click="siguiente()">
                    Siguiente @include('partials.icon', ['name' => 'arrow-right', 'class' => 'icon-sm'])
                </button>
            </div>
            <p class="rd-feedback ko" x-show="!revelada && feedback" x-text="feedback"></p>
        </div>
    </template>

    {{-- GANADO --}}
    <template x-if="estado === 'ganado'">
        <div class="rd-resultado ganado">
            @include('partials.icon', ['name' => 'star', 'class' => 'icon-lg'])
            <h2>¡Victoria!</h2>
            <p>Has derrotado a {{ $enemigo->nombre }} de hoy. Vuelve mañana a por un nuevo enemigo.</p>
            <p class="rd-stats"><span x-text="aciertos"></span> aciertos · <span x-text="fallos"></span> fallos · racha <span x-text="racha"></span></p>
            <button type="button" class="btn btn-secundario" @click="compartir()">@include('partials.icon', ['name' => 'share', 'class' => 'icon-sm']) Compartir resultado</button>
            <p class="rd-countdown">Nuevo reto en <span x-text="cuentaAtras"></span></p>
        </div>
    </template>

    {{-- PERDIDO --}}
    <template x-if="estado === 'perdido'">
        <div class="rd-resultado perdido">
            @include('partials.icon', ['name' => 'skull', 'class' => 'icon-lg'])
            <h2>Derrota</h2>
            <p>{{ $enemigo->nombre }} ha resistido tu ataque. Inténtalo de nuevo mañana con un nuevo enemigo.</p>
            <p class="rd-stats"><span x-text="aciertos"></span> aciertos · <span x-text="fallos"></span> fallos</p>
            <button type="button" class="btn btn-secundario" @click="compartir()">@include('partials.icon', ['name' => 'share', 'class' => 'icon-sm']) Compartir resultado</button>
            <p class="rd-countdown">Nuevo reto en <span x-text="cuentaAtras"></span></p>
        </div>
    </template>
</div>


@endsection
