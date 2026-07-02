@extends('layouts.app')

@section('titulo', 'Reto Diario')

@section('contenido')

<style>
    .rd-wrap { max-width: 780px; margin: 0 auto; }

    .rd-racha {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(201, 162, 39, 0.12);
        border: 1px solid rgba(201, 162, 39, 0.35);
        color: var(--c-oro-claro);
        font-family: var(--f-titulo);
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        padding: 6px 14px;
        border-radius: 999px;
        margin-bottom: 22px;
    }

    /* ----- HERO / ESCENARIO SEGÚN EL ENEMIGO DEL DÍA ----- */
    .rd-hero {
        position: relative;
        border-radius: var(--r-lg);
        border: 1px solid var(--b-medio);
        overflow: hidden;
        padding: 46px 20px 34px;
        margin-bottom: 26px;
        text-align: center;
        box-shadow: var(--sombra-md);
    }

    .rd-hero::before {
        content: "";
        position: absolute;
        inset: 0;
        opacity: .9;
    }

    .rd-hero::after {
        content: "";
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(18,3,9,.15) 0%, rgba(18,3,9,.85) 100%);
    }

    .rd-hero > * { position: relative; z-index: 1; }

    .rd-fondo-bosque::before {
        background:
            radial-gradient(ellipse at 20% 100%, rgba(90,107,63,.35), transparent 55%),
            radial-gradient(ellipse at 80% 100%, rgba(90,107,63,.3), transparent 55%),
            repeating-linear-gradient(70deg, rgba(90,107,63,.12) 0 18px, transparent 18px 42px),
            linear-gradient(180deg, #14170d 0%, #1a0509 100%);
    }

    .rd-fondo-cripta::before {
        background:
            radial-gradient(circle at 50% 15%, rgba(201,162,39,.10), transparent 45%),
            repeating-linear-gradient(0deg, rgba(168,154,146,.06) 0 2px, transparent 2px 26px),
            repeating-linear-gradient(90deg, rgba(168,154,146,.06) 0 2px, transparent 2px 26px),
            linear-gradient(180deg, #25090f 0%, #120309 100%);
    }

    .rd-fondo-bosque_nocturno::before {
        background:
            radial-gradient(circle at 78% 20%, rgba(227,194,90,.25) 0 10px, rgba(227,194,90,.06) 11px 30px, transparent 42px),
            radial-gradient(ellipse at 30% 100%, rgba(74,45,61,.5), transparent 55%),
            linear-gradient(180deg, #180a14 0%, #100208 100%);
    }

    .rd-hero .rd-tipo {
        font-family: var(--f-titulo);
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--c-rojo-claro);
    }

    .rd-enemigo {
        text-align: center;
        margin-bottom: 0;
    }

    .rd-enemigo img,
    .rd-arte {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        border: 2px solid var(--b-fuerte);
        box-shadow: var(--brillo-rojo);
        margin: 0 auto 14px;
        overflow: hidden;
    }

    .rd-enemigo img { object-fit: cover; }
    .rd-arte svg { width: 100%; height: 100%; display: block; }

    .rd-enemigo h2 {
        font-family: var(--f-gotica);
        font-size: 1.7rem;
        color: var(--t-principal);
    }

    .rd-enemigo .rd-tipo {
        font-family: var(--f-titulo);
        font-size: 0.75rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--c-rojo-claro);
    }

    .rd-enemigo p.desc {
        color: var(--t-secundario);
        font-family: var(--f-cuerpo);
        font-size: 1rem;
        max-width: 520px;
        margin: 8px auto 0;
    }

    .rd-vida-shell {
        max-width: 520px;
        margin: 18px auto 0;
    }

    .rd-vida-label {
        display: flex;
        justify-content: space-between;
        font-family: var(--f-titulo);
        font-size: 0.78rem;
        letter-spacing: 1px;
        color: var(--t-secundario);
        margin-bottom: 6px;
        text-transform: uppercase;
    }

    .rd-vida-barra {
        height: 16px;
        border-radius: 999px;
        background: var(--c-superficie);
        border: 1px solid var(--b-medio);
        overflow: hidden;
    }

    .rd-vida-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--c-rojo-osc), var(--c-rojo));
        transition: width 0.5s ease;
    }

    .rd-ronda-pregunta {
        font-family: var(--f-titulo);
        font-size: 1.1rem;
        color: var(--t-principal);
        text-align: center;
        margin: 30px 0 20px;
    }

    .rd-opciones {
        display: grid;
        gap: 12px;
    }

    .rd-opcion {
        display: flex;
        align-items: center;
        gap: 12px;
        text-align: left;
        width: 100%;
        background: var(--c-superficie);
        border: 1px solid var(--b-sutil);
        border-radius: var(--r-md);
        padding: 14px 18px;
        color: var(--t-principal);
        font-family: var(--f-cuerpo);
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .rd-opcion:hover:not(:disabled) {
        border-color: var(--c-rojo);
        background: var(--c-superficie-2);
        transform: translateY(-1px);
    }

    .rd-opcion:disabled { cursor: default; opacity: 0.6; }

    .rd-opcion .badge-trivia {
        flex-shrink: 0;
        font-family: var(--f-titulo);
        font-size: 0.65rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        background: rgba(201, 162, 39, 0.15);
        color: var(--c-oro-claro);
        border: 1px solid rgba(201, 162, 39, 0.4);
        padding: 3px 8px;
        border-radius: 999px;
    }

    .rd-opcion.correcta { border-color: #5a6b3f; background: rgba(90, 107, 63, 0.18); }
    .rd-opcion.incorrecta { border-color: #6b1a1a; background: rgba(107, 26, 26, 0.18); }

    .rd-feedback {
        text-align: center;
        font-family: var(--f-titulo);
        font-size: 0.95rem;
        color: var(--c-oro-claro);
        min-height: 24px;
        margin-top: 14px;
    }

    .rd-resultado {
        text-align: center;
        padding: 40px 20px;
    }

    .rd-resultado h2 {
        font-family: var(--f-gotica);
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .rd-resultado.ganado h2 { color: var(--c-oro-claro); }
    .rd-resultado.perdido h2 { color: var(--c-rojo-claro); }

    .rd-resultado p { color: var(--t-secundario); font-family: var(--f-cuerpo); font-size: 1.05rem; }

    .rd-countdown {
        margin-top: 22px;
        font-family: var(--f-titulo);
        font-size: 0.8rem;
        letter-spacing: 1px;
        color: var(--t-tenue);
        text-transform: uppercase;
    }
</style>

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

<script>
function retoDiario({ estadoInicial, vidaInicial, vidaMax, rondaInicial, rachaInicial, urlResponder, proximoCambioInicial }) {
    return {
        estado: estadoInicial,
        vida: vidaInicial,
        vidaMax: vidaMax,
        ronda: rondaInicial,
        racha: rachaInicial,
        proximoCambio: proximoCambioInicial,
        enviando: false,
        feedback: '',
        marcada: null,
        ultimaCorrecta: null,
        cuentaAtras: '',
        _timer: null,

        init() {
            if (this.estado !== 'en_curso') {
                this.iniciarCuentaAtras();
            }
        },

        async responder(indice) {
            if (this.enviando) return;
            this.enviando = true;
            this.marcada = indice;
            this.feedback = '';

            try {
                const res = await fetch(urlResponder, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ opcion: indice }),
                });

                const data = await res.json();

                if (!res.ok) {
                    this.feedback = data.error || 'Ha ocurrido un error.';
                    this.enviando = false;
                    return;
                }

                this.ultimaCorrecta = data.correcta;
                this.vida = data.vida_restante;
                this.racha = data.racha;
                this.proximoCambio = data.proximo_cambio;

                this.feedback = data.correcta
                    ? `¡Correcto! (${data.aciertos}/4 aciertos)`
                    : `Incorrecto (${data.fallos}/4 fallos)`;

                await this.esperar(900);

                this.estado = data.estado;
                this.ronda = data.ronda;
                this.marcada = null;
                this.ultimaCorrecta = null;
                this.feedback = '';

                if (this.estado !== 'en_curso') {
                    this.iniciarCuentaAtras();
                }
            } catch (e) {
                this.feedback = 'No se pudo conectar. Inténtalo de nuevo.';
            } finally {
                this.enviando = false;
            }
        },

        esperar(ms) {
            return new Promise((resolve) => setTimeout(resolve, ms));
        },

        iniciarCuentaAtras() {
            if (this._timer) clearInterval(this._timer);

            const actualizar = () => {
                const objetivo = new Date(this.proximoCambio);
                const diff = objetivo - new Date();

                if (diff <= 1000) {
                    window.location.reload();
                    return;
                }

                const h = String(Math.floor(diff / 3600000)).padStart(2, '0');
                const m = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                const s = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');

                this.cuentaAtras = `${h}:${m}:${s}`;
            };

            actualizar();
            this._timer = setInterval(actualizar, 1000);
        },
    };
}
</script>

@endsection
