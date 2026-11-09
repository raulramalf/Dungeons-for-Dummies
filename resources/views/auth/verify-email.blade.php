<x-guest-layout titulo="Verifica tu correo">
    <p class="auth-texto">
        Gracias por registrarte. Antes de empezar, verifica tu dirección de correo haciendo clic en el enlace que te hemos enviado. Si no lo recibiste, te enviamos otro.
    </p>

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status status="Se ha enviado un nuevo enlace de verificación a tu correo." />
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit">Reenviar correo</button>
    </form>

    <div class="auth-pie">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="auth-enlace-boton">Cerrar sesión</button>
        </form>
    </div>
</x-guest-layout>
