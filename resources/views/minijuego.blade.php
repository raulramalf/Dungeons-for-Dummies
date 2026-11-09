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
        urlResponder: @js(route('minijuego.responder')),
        proximoCambioInicial: @js($proximoCambio->toIso8601String()),
     })"
     x-cloak
>
    <div style="text-align:center;">
        <span class="rd-racha">
            @include('partials.icon', ['name' => 'flame'])
            Racha: <span x-text="racha"></span> día<span x-show="racha !== 1">s</span>
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
                    <div class="rd-vida-fill" :style="`width:${(vida / vidaMax) * 100}%`"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- EN CURSO --}}
    <template x-if="estado === 'en_curso' && ronda">
        <div>
            <p class="rd-ronda-pregunta" x-text="ronda.pregunta"></p>

            <div class="rd-opciones">
                <template x-for="(opcion, i) in ronda.opciones" :key="i">
                    <button
                        type="button"
                        class="rd-opcion"
                        :class="{
                            'correcta': marcada === i && ultimaCorrecta === true,
                            'incorrecta': marcada === i && ultimaCorrecta === false
                        }"
                        :disabled="enviando"
                        @click="responder(i)"
                    >
                        <span x-text="opcion.texto"></span>
                    </button>
                </template>
            </div>

            <p class="rd-feedback" x-text="feedback"></p>
        </div>
    </template>

    {{-- GANADO --}}
    <template x-if="estado === 'ganado'">
        <div class="rd-resultado ganado">
            @include('partials.icon', ['name' => 'star', 'class' => 'icon-lg'])
            <h2>¡Victoria!</h2>
            <p>Has derrotado a {{ $enemigo->nombre }} de hoy. Vuelve mañana a por un nuevo enemigo.</p>
            <p class="rd-countdown">Nuevo reto en <span x-text="cuentaAtras"></span></p>
        </div>
    </template>

    {{-- PERDIDO --}}
    <template x-if="estado === 'perdido'">
        <div class="rd-resultado perdido">
            @include('partials.icon', ['name' => 'skull', 'class' => 'icon-lg'])
            <h2>Derrota</h2>
            <p>{{ $enemigo->nombre }} ha resistido tu ataque. Inténtalo de nuevo mañana con un nuevo enemigo.</p>
            <p class="rd-countdown">Nuevo reto en <span x-text="cuentaAtras"></span></p>
        </div>
    </template>
</div>


@endsection
