<x-guest-layout titulo="Nueva contraseña">
    <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="campo">
            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                   autocomplete="username" inputmode="email" autocapitalize="none" spellcheck="false" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="campo">
            <label for="password">Nueva contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="campo">
            <label for="password_confirmation">Confirmar contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <button type="submit">Restablecer contraseña</button>
    </form>
</x-guest-layout>
