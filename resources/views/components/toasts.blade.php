{{--
    Mensajes flash como toasts accesibles (role=status). Un único sitio en lugar
    de un bloque de alerta copiado en cada vista. js/ui.js los cierra solos.
    Desde JS: window.toast('Texto', 'exito' | 'error').
--}}
<div class="toasts" id="toasts" role="region" aria-label="Notificaciones" aria-live="polite">
    @if (session('success'))
        <div class="toast toast-exito" role="status">
            @include('partials.icon', ['name' => 'check', 'class' => 'toast-icono'])
            <span class="toast-texto">{{ session('success') }}</span>
            <button type="button" class="toast-cerrar" data-toast-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x', 'class' => 'icon-sm'])</button>
        </div>
    @endif
    @if (session('error'))
        <div class="toast toast-error" role="alert">
            @include('partials.icon', ['name' => 'x', 'class' => 'toast-icono'])
            <span class="toast-texto">{{ session('error') }}</span>
            <button type="button" class="toast-cerrar" data-toast-cerrar aria-label="Cerrar">@include('partials.icon', ['name' => 'x', 'class' => 'icon-sm'])</button>
        </div>
    @endif
</div>
