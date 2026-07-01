@extends('layouts.app')
@include('partials.d20-button')

@section('titulo', 'Inicio')

@section('contenido')

<video autoplay muted loop playsinline id="bg-video-inicio">
    <source src="{{ asset('videos/castillo-fondo.mp4') }}" type="video/mp4">
</video>

<div class="contenedor">

    @if(session('success'))
        <div class="alerta alerta-exito">
            @include('partials.icon', ['name' => 'check']) {{ session('success') }}
        </div>
    @endif

    {{-- HERO --}}
    <section class="hero">
        <h1 class="hero-titulo">Forja tu Leyenda</h1>
        <p class="hero-sub">
            Lleva tus personajes, organiza tus campañas y cuenta lo que pasó en partida.
        </p>
        <div class="hero-acciones">
            @auth
                <a href="{{ route('personajes.create') }}" class="btn btn-primario">Crear Personaje</a>
                <a href="{{ route('feed.index') }}" class="btn btn-secundario">Entrar a la Taberna</a>
            @else
                <a href="{{ route('register') }}" class="btn btn-primario">Comenzar la Aventura</a>
                <a href="{{ route('login') }}" class="btn btn-secundario">Iniciar Sesión</a>
            @endauth
        </div>
    </section>

    {{-- TU PRÓXIMA SESIÓN --}}
    @auth
        @if($proximaSesion)
        <section class="bloque">
            <div class="proxima-sesion">
                <div class="proxima-sesion-icono">
                    @include('partials.icon', ['name' => 'scroll', 'class' => 'icon-lg'])
                </div>
                <div class="proxima-sesion-cuerpo">
                    <span class="proxima-sesion-eyebrow">Tu próxima mesa</span>
                    <h3>{{ $proximaSesion->campana->nombre }} — {{ $proximaSesion->titulo }}</h3>
                    <p>
                        {{ $proximaSesion->fecha_sesion->translatedFormat('l d \d\e F, H:i') }}
                        · DM: {{ $proximaSesion->campana->dungeonMaster->nombre ?? 'Desconocido' }}
                    </p>
                </div>
            </div>
        </section>
        @endif
    @endauth

    {{-- CALENDARIO DE SESIONES --}}
    @auth
    <section class="bloque">
        <h2 class="seccion-titulo">Tu Calendario</h2>
        <p class="seccion-subtitulo">Sesiones de tus campañas, pasadas y futuras</p>

        <div class="calendario-wrap">
            <div class="calendario-header">
                <button type="button" class="cal-nav" onclick="cambiarMes(-1)">‹</button>
                <h3 id="cal-mes-titulo"></h3>
                <button type="button" class="cal-nav" onclick="cambiarMes(1)">›</button>
            </div>
            <div class="calendario-dias-semana">
                <span>Lun</span><span>Mar</span><span>Mié</span><span>Jue</span><span>Vie</span><span>Sáb</span><span>Dom</span>
            </div>
            <div class="calendario-grid" id="cal-grid"></div>
        </div>

        <div id="cal-sesiones-dia" class="cal-sesiones-dia"></div>
    </section>

    <script>
    const sesionesCalendario = {!! $sesionesCalendario->groupBy('fecha')->toJson() !!};
    let calFecha = new Date();

    const mesesNombre = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];

    function cambiarMes(delta) {
        calFecha.setMonth(calFecha.getMonth() + delta);
        renderCalendario();
    }

    function renderCalendario() {
        const year = calFecha.getFullYear();
        const month = calFecha.getMonth();

        document.getElementById('cal-mes-titulo').textContent = `${mesesNombre[month]} ${year}`;

        const primerDia = new Date(year, month, 1);
        const ultimoDia = new Date(year, month + 1, 0);
        let diaSemanaInicio = primerDia.getDay();
        diaSemanaInicio = diaSemanaInicio === 0 ? 6 : diaSemanaInicio - 1;

        const grid = document.getElementById('cal-grid');
        grid.innerHTML = '';

        for (let i = 0; i < diaSemanaInicio; i++) {
            const celda = document.createElement('div');
            celda.className = 'cal-celda cal-vacia';
            grid.appendChild(celda);
        }

        const hoy = new Date();
        const hoyStr = hoy.toISOString().split('T')[0];

        for (let dia = 1; dia <= ultimoDia.getDate(); dia++) {
            const fechaStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(dia).padStart(2, '0')}`;
            const celda = document.createElement('div');
            celda.className = 'cal-celda';
            if (fechaStr === hoyStr) celda.classList.add('cal-hoy');

            const numero = document.createElement('span');
            numero.className = 'cal-numero';
            numero.textContent = dia;
            celda.appendChild(numero);

           if (sesionesCalendario[fechaStr]) {
                celda.classList.add('cal-con-sesion');
                const nombreCampana = sesionesCalendario[fechaStr][0].campana;
                const label = document.createElement('span');
                label.className = 'cal-campana-label';
                label.textContent = nombreCampana;
                celda.appendChild(label);
                celda.style.cursor = 'pointer';
                celda.onclick = () => mostrarSesionesDia(fechaStr);
            }

            grid.appendChild(celda);
        }
    }

    function mostrarSesionesDia(fechaStr) {
        const cont = document.getElementById('cal-sesiones-dia');
        const sesiones = sesionesCalendario[fechaStr] || [];

        if (sesiones.length === 0) {
            cont.innerHTML = '';
            return;
        }

        const fechaFormateada = new Date(fechaStr + 'T00:00:00').toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' });

        cont.innerHTML = `
            <div class="cal-sesiones-titulo">${fechaFormateada}</div>
            ${sesiones.map(s => `
                <div class="cal-sesion-item">
                    <strong>${s.campana}</strong> — Sesión ${s.numero}: ${s.titulo}
                </div>
            `).join('')}
        `;
    }

    renderCalendario();
    </script>
    @endauth

</div>

<style>
/* ----- PRÓXIMA SESIÓN ----- */
.proxima-sesion {
    display: flex;
    align-items: flex-start;
    gap: 1.1rem;
    background: rgba(179,3,3,0.07);
    border-left: 3px solid var(--c-rojo);
    border-radius: 0 var(--r-md) var(--r-md) 0;
    padding: 1.2rem 1.5rem;
}

.proxima-sesion-icono {
    color: var(--c-rojo);
    flex-shrink: 0;
    margin-top: 0.15rem;
}

.proxima-sesion-eyebrow {
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.72rem;
    color: var(--t-tenue);
    margin-bottom: 0.25rem;
}

.proxima-sesion-cuerpo h3 {
    font-family: var(--f-titulo);
    font-size: 1.1rem;
    margin: 0 0 0.3rem;
}

.proxima-sesion-cuerpo p {
    color: var(--t-secundario);
    font-size: 0.92rem;
    margin: 0;
}

/* ----- LA BITÁCORA (timeline) ----- */
.bitacora {
    position: relative;
    margin-top: 0.5rem;
}

.bitacora::before {
    content: '';
    position: absolute;
    left: 17px;
    top: 6px;
    bottom: 6px;
    width: 1px;
    background: var(--b-sutil);
}

.bitacora-fila {
    position: relative;
    display: flex;
    gap: 1.1rem;
    padding: 0.85rem 0;
}

.bitacora-marcador {
    position: relative;
    z-index: 1;
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--c-superficie);
    border: 1px solid var(--b-medio);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--c-rojo-claro);
}

.bitacora-contenido {
    flex: 1;
    padding-top: 0.2rem;
}

.bitacora-eyebrow {
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    font-size: 0.7rem;
    color: var(--t-tenue);
    margin-bottom: 0.2rem;
}

.bitacora-texto {
    color: var(--t-principal);
    font-size: 0.96rem;
    line-height: 1.55;
    margin: 0 0 0.2rem;
}

.bitacora-texto strong {
    color: #D46043;
}

.bitacora-fecha {
    color: var(--t-tenue);
    font-size: 0.78rem;
}

@media (max-width: 600px) {
    .proxima-sesion { flex-direction: column; }
}

/* ----- CALENDARIO ----- */
.calendario-wrap {
    background: var(--c-superficie);
    border: 1px solid var(--b-sutil);
    border-radius: var(--r-lg);
    padding: 2rem;
    max-width: 920px;
    margin: 0 auto;
}

.calendario-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.calendario-header h3 {
    font-family: var(--f-titulo);
    font-size: 1.5rem;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.cal-nav {
    background: rgba(255,255,255,0.05);
    border: 1px solid var(--b-neutro);
    color: var(--t-principal);
    width: 42px;
    height: 42px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 1.4rem;
    transition: background 0.2s;
}

.cal-nav:hover { background: rgba(179,3,3,0.15); }

.calendario-dias-semana {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
    margin-bottom: 6px;
}

.calendario-dias-semana span {
    text-align: center;
    color: var(--t-tenue);
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.calendario-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 4px;
}

.cal-celda {
    position: relative;
    aspect-ratio: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    padding-top: 12px;
    border-radius: 8px;
    background: rgba(0,0,0,0.15);
    transition: background 0.2s;
    overflow: hidden;
}

.cal-vacia { background: transparent; }

.cal-numero {
    font-size: 1.2rem;
    color: var(--t-secundario);
}

.cal-hoy {
    border: 1px solid var(--c-rojo);
}

.cal-hoy .cal-numero {
    color: var(--c-rojo-claro);
    font-weight: bold;
}

.cal-con-sesion {
    background: rgba(179,3,3,0.12);
}

.cal-con-sesion:hover {
    background: rgba(179,3,3,0.22);
}

.cal-punto {
    position: absolute;
    bottom: 4px;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: var(--c-rojo);
}

.cal-sesiones-dia {
    margin-top: 1.3rem;
    max-width: 920px;
    margin-left: auto;
    margin-right: auto;
}

.cal-sesiones-titulo {
    color: var(--t-secundario);
    font-size: 0.85rem;
    text-transform: capitalize;
    margin-bottom: 0.6rem;
}

.cal-sesion-item {
    background: rgba(0,0,0,0.2);
    border-left: 3px solid var(--c-rojo);
    border-radius: 0 6px 6px 0;
    padding: 0.7rem 1rem;
    margin-bottom: 0.5rem;
    font-size: 0.92rem;
    color: var(--t-secundario);
}

.cal-sesion-item strong {
    color: var(--c-naranja);
}

.cal-campana-label {
    font-size: 0.82rem;
    color: var(--c-rojo-claro);
    text-align: center;
    line-height: 1.2;
    margin-top: 6px;
    padding: 0 4px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 100%;
    font-weight: 600;
}

.cal-punto {
    display: none;
}

.hero-video {
    position: relative;
    overflow: hidden;
    background: none;
}

.hero-video-bg {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    transform: translate(-50%, -50%);
    z-index: 0;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg, rgba(26,5,9,0.88), rgba(179,3,3,0.18));
    z-index: 1;
}

.hero-contenido {
    position: relative;
    z-index: 2;
}

.hero-video .icon-xl {
    width: 2.4em;
    height: 2.4em;
    max-width: 60px;
    max-height: 60px;
}

#bg-video-inicio {
    position: fixed;
    top: 0;
    left: var(--sidebar-w);
    width: calc(100% - var(--sidebar-w));
    height: 100%;
    object-fit: cover;
    z-index: -2;
    filter: brightness(0.4);
}

@media (max-width: 1024px) {
    #bg-video-inicio {
        left: 0;
        width: 100%;
    }
}

.hero {
    background: none;
    border: none;
    box-shadow: none;
    padding: 4.5rem 3rem;
    text-align: center;
    margin-bottom: 3.5rem;
}

.hero::before {
    display: none;
}
</style>
@endsection
