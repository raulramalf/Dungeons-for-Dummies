<x-guest-layout>
    <div class="mb-4" style="color: var(--color-gris); font-size: 0.85rem; line-height: 1.6;">
        Gracias por registrarte. Antes de empezar, verifica tu dirección de correo haciendo clic en el enlace que te hemos enviado. Si no lo recibiste, te enviamos otro.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4" style="color: #4caf50; font-size: 0.85rem;">
            Se ha enviado un nuevo enlace de verificación a tu correo.
        </div>
    @endif

    <div class="mt-4" style="display: flex; align-items: center; justify-content: space-between;">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit">Reenviar correo</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" style="background: transparent; color: var(--color-gris); border: 1px solid var(--color-gris); padding: 12px 24px; border-radius: 8px; cursor: pointer; font-family: Georgia, serif;">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>