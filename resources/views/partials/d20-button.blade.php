{{--
    Dado d20 flotante. Solo marcado: estilos en css/pages/d20.css y lógica 3D en
    js/d20.js (se carga de forma diferida desde js/pages/inicio.js).
    Mientras three.js llega, un d20 SVG ocupa su sitio (sin saltos de layout).
--}}
@once
    @push('head')
        @vite('resources/css/pages/d20.css')
    @endpush
@endonce

<div id="d20-container" role="button" tabindex="0" aria-label="Lanzar dado de 20 caras">
    <div id="d20-canvas-wrapper">
        <x-logo-mark class="d20-placeholder" />
        <div id="result-badge" aria-live="polite">0</div>
    </div>
    <div id="d20-shadow"></div>
</div>

<div id="d20-crit-overlay" aria-hidden="true">
    <span id="d20-crit-text">¡CRÍTICO!</span>
</div>
