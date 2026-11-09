<x-guest-layout titulo="Iniciar sesión">
    <x-auth-session-status :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   autocomplete="username" inputmode="email" autocapitalize="none" spellcheck="false" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="campo">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="campo-recordar">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me">Recuérdame</label>
        </div>

        <button type="submit">Iniciar sesión</button>

        <div class="auth-enlaces">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
        </div>

        <div class="auth-pie">
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </div>
    </form>
</x-guest-layout>
