<x-guest-layout titulo="Recuperar contraseña">
    <p class="auth-texto">
        ¿Olvidaste tu contraseña? No hay problema. Indícanos tu correo electrónico y te enviaremos un enlace para restablecerla.
    </p>

    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   inputmode="email" autocapitalize="none" spellcheck="false" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <button type="submit">Enviar enlace</button>

        <div class="auth-pie">
            <a href="{{ route('login') }}">Volver al inicio de sesión</a>
        </div>
    </form>
</x-guest-layout>
