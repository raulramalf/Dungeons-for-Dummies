@extends('layouts.app')

@section('titulo', 'Perfil')
@section('descripcion', 'Tu perfil: foto, datos de la cuenta y contraseña.')

@push('head')
    @vite('resources/css/pages/perfil.css')
@endpush

@section('contenido')
@php $usuario = auth()->user(); @endphp

<div class="perfil">

    {{-- CABECERA --}}
    <section class="tarjeta perfil-cabecera" aria-label="Resumen de tu cuenta">
        <form method="POST" action="{{ route('perfil.avatar') }}" enctype="multipart/form-data" class="perfil-avatar-form">
            @csrf
            <label class="perfil-avatar" for="inputAvatar" title="Cambiar foto de perfil">
                <img class="avatar-lg" src="{{ $usuario->avatar_url }}" alt="Tu foto de perfil" width="88" height="88">
                <span class="perfil-avatar-editar" aria-hidden="true">@include('partials.icon', ['name' => 'edit', 'class' => 'icon-sm'])</span>
                <span class="sr-only">Cambiar foto de perfil</span>
            </label>
            <input type="file" name="avatar" id="inputAvatar" class="sr-only" accept="image/png,image/jpeg,image/webp" data-autosubmit>
        </form>

        <div class="perfil-datos">
            <h2 class="perfil-nombre">{{ $usuario->nombre }}</h2>
            <p class="perfil-rol">{{ ucfirst(str_replace('_', ' ', $usuario->rol)) }} · Miembro desde {{ $usuario->created_at->format('Y') }}</p>

            <dl class="perfil-stats">
                <div><dt>Personajes</dt><dd>{{ $personajesCount }}</dd></div>
                <div><dt>Campañas</dt><dd>{{ $campanasCount }}</dd></div>
                <div><dt>Sesiones</dt><dd>{{ $sesionesCount }}</dd></div>
            </dl>
        </div>
    </section>

    {{-- DATOS --}}
    <section class="tarjeta" aria-labelledby="titulo-datos">
        <h2 class="seccion-titulo" id="titulo-datos">Mi perfil</h2>
        <form method="POST" action="/perfil/actualizar">
            @csrf
            @method('PATCH')

            <div class="campo">
                <label for="nombre">Nombre de usuario</label>
                <input id="nombre" type="text" name="nombre" class="control" value="{{ old('nombre', $usuario->nombre) }}" required maxlength="255" autocomplete="nickname">
                @error('nombre') <span class="campo-error">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" class="control" value="{{ old('email', $usuario->email) }}" required maxlength="255"
                       autocomplete="email" inputmode="email" autocapitalize="none" spellcheck="false">
                @error('email') <span class="campo-error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primario perfil-boton">Guardar cambios</button>
        </form>
    </section>

    {{-- CONTRASEÑA --}}
    <section class="tarjeta" aria-labelledby="titulo-password">
        <h2 class="seccion-titulo" id="titulo-password">Cambiar contraseña</h2>
        <form method="POST" action="/perfil/password">
            @csrf
            @method('PATCH')

            <div class="campo">
                <label for="current_password">Contraseña actual</label>
                <input id="current_password" type="password" name="current_password" class="control" required autocomplete="current-password">
                @error('current_password') <span class="campo-error">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="password">Nueva contraseña</label>
                <input id="password" type="password" name="password" class="control" required autocomplete="new-password">
                @error('password') <span class="campo-error">{{ $message }}</span> @enderror
            </div>

            <div class="campo">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="control" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-primario perfil-boton">Cambiar contraseña</button>
        </form>
    </section>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-peligro perfil-boton">Cerrar sesión</button>
    </form>
</div>
@endsection
