<x-guest-layout>
    <div class="mb-4" style="color: var(--color-gris); font-size: 0.85rem; line-height: 1.6;">
        ¿Olvidaste tu contraseña? No hay problema. Indícanos tu correo electrónico y te enviaremos un enlace para restablecerla.
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4" style="display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('login') }}">Volver al login</a>
            <button type="submit">Enviar enlace</button>
        </div>
    </form>
</x-guest-layout>