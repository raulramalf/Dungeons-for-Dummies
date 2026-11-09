<x-guest-layout titulo="Confirmar contraseña">
    <p class="auth-texto">
        Esta es una zona segura. Por favor confirma tu contraseña antes de continuar.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="campo">
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autofocus autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <button type="submit">Confirmar</button>
    </form>
</x-guest-layout>
