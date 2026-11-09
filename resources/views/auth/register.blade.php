<x-guest-layout titulo="Crear cuenta">
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="campo">
            <label for="nombre">Nombre de usuario</label>
            <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus
                   autocomplete="nickname" />
            <x-input-error :messages="$errors->get('nombre')" />
        </div>

        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   autocomplete="username" inputmode="email" autocapitalize="none" spellcheck="false" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="campo">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="campo">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit">Crear cuenta</button>

        <div class="auth-pie">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </form>
</x-guest-layout>
