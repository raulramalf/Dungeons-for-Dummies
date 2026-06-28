<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email -->
        <div>
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Contraseña -->
        <div class="mt-4">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Recuérdame -->
        <div class="mt-4" style="display: flex; align-items: center; gap: 8px;">
            <input id="remember_me" type="checkbox" name="remember">
            <label for="remember_me" style="margin: 0; cursor: pointer;">Recuérdame</label>
        </div>

        <div class="mt-4" style="display: flex; align-items: center; justify-content: space-between;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
            @endif
            <button type="submit">Iniciar sesión</button>
        </div>

        <div class="mt-4" style="text-align: center; border-top: 1px solid rgba(179,3,3,0.2); padding-top: 16px;">
            <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate</a>
        </div>
    </form>
</x-guest-layout>