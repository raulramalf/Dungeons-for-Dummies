@extends('layouts.app')

@section('titulo', 'Perfil')

@section('contenido')

<div style="max-width: 1000px; margin: 0 auto;">

    <!-- MENSAJES DE ÉXITO -->
    @if (session('success'))
        <div style="background: rgba(64,72,52,0.4); border: 1px solid #4caf50; border-radius: 8px; padding: 12px 20px; margin-bottom: 20px; color: #4caf50;">
            {{ session('success') }}
        </div>
    @endif

    <!-- CABECERA PERFIL -->
    <div style="background: #2a0a18; border-radius: 10px; padding: 30px; display: flex; align-items: center; gap: 25px; margin-bottom: 25px; flex-wrap: wrap;">
        <form method="POST" action="{{ route('perfil.avatar') }}" enctype="multipart/form-data" id="formAvatar" style="position: relative; flex-shrink: 0;">
            @csrf
            <label for="inputAvatar" style="cursor: pointer; display: block; position: relative;">
                @if(auth()->user()->avatar)
                <img src="{{ str_starts_with(auth()->user()->avatar, 'http') ? auth()->user()->avatar : Storage::url(auth()->user()->avatar) }}"
                    style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--color-rojo);">
                @else
                <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--color-rojo); display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: bold; border: 3px solid var(--color-rojo);">
                    {{ strtoupper(substr(auth()->user()->nombre, 0, 1)) }}
                </div>
                @endif
                <div style="position: absolute; bottom: -2px; right: -2px; width: 26px; height: 26px; background: var(--color-naranja); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid #2a0a18;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 13px; height: 13px; stroke: white; fill: none; stroke-width: 2.5;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>
                    </svg>
                </div>
            </label>
            <input type="file" name="avatar" id="inputAvatar" accept="image/*" style="display: none;" onchange="document.getElementById('formAvatar').submit()">
        </form>
        <div>
            <div style="font-size: 1.3rem; font-weight: bold;">{{ strtoupper(auth()->user()->nombre) }}</div>
            <div style="color: var(--color-gris); font-size: 0.85rem;">
                {{ ucfirst(auth()->user()->rol) }} · Miembro desde {{ auth()->user()->created_at->format('Y') }}
            </div>
            <div style="display: flex; gap: 20px; margin-top: 12px;">
                <div style="text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: bold; color: var(--color-rojo);">{{ $personajesCount }}</div>
                    <div style="color: var(--color-gris); font-size: 0.75rem;">Personajes</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: bold; color: var(--color-rojo);">{{ $campanasCount }}</div>
                    <div style="color: var(--color-gris); font-size: 0.75rem;">Campañas</div>
                </div>
                <div style="text-align: center;">
                    <div style="font-size: 1.2rem; font-weight: bold; color: var(--color-rojo);">{{ $sesionesCount }}</div>
                    <div style="color: var(--color-gris); font-size: 0.75rem;">Sesiones</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FORMULARIO -->
    <div style="background: #2a0a18; border-radius: 10px; padding: 25px; margin-bottom: 15px;">
        <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px;">Mi Perfil</h2>
        <form method="POST" action="/perfil/actualizar">
            @csrf
            @method('PATCH')
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Nombre de usuario</label>
                    <input type="text" name="nombre" value="{{ auth()->user()->nombre }}" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
                    @error('nombre') <span style="color: var(--color-naranja); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Correo electrónico</label>
                    <input type="email" name="email" value="{{ auth()->user()->email }}" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
                    @error('email') <span style="color: var(--color-naranja); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn btn-primario" style="width: 100%; margin-top: 5px;">Guardar Cambios</button>
            </div>
        </form>
    </div>

    <!-- CAMBIAR CONTRASEÑA -->
    <div style="background: #2a0a18; border-radius: 10px; padding: 25px; margin-bottom: 15px;">
        <h2 style="color: var(--color-gris); font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 20px;">Cambiar Contraseña</h2>
        <form method="POST" action="/perfil/password">
            @csrf
            @method('PATCH')
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Contraseña actual</label>
                    <input type="password" name="current_password" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
                    @error('current_password') <span style="color: var(--color-naranja); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Nueva contraseña</label>
                    <input type="password" name="password" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
                    @error('password') <span style="color: var(--color-naranja); font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label style="color: var(--color-gris); font-size: 0.8rem; display: block; margin-bottom: 6px;">Confirmar nueva contraseña</label>
                    <input type="password" name="password_confirmation" style="width: 100%; background: #120309; border: 1px solid rgba(179,3,3,0.3); border-radius: 8px; padding: 12px 15px; color: white; font-family: Georgia, serif; font-size: 0.9rem;">
                </div>
                <button type="submit" class="btn btn-primario" style="width: 100%; margin-top: 5px;">Cambiar Contraseña</button>
            </div>
        </form>
    </div>

    <!-- OPCIONES -->
    <div style="display: flex; flex-direction: column; gap: 10px;">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-peligro" style="width: 100%;">Cerrar sesión</button>
        </form>
    </div>

</div>

@endsection