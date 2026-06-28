<x-guest-layout>
    <div class="mb-4" style="color: var(--color-gris); font-size: 0.85rem; line-height: 1.6;">
        Esta es una zona segura. Por favor confirma tu contraseña antes de continuar.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div>
            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4" style="display: flex; justify-content: flex-end;">
            <button type="submit">Confirmar</button>
        </div>
    </form>
</x-guest-layout>