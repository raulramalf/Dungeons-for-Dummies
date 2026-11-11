@php($tituloPagina = $attributes->get('titulo', 'Acceso'))
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @push('head')
        @vite('resources/css/pages/auth.css')
    @endpush
    @include('partials.head', ['tituloPagina' => $tituloPagina])
</head>
<body class="auth-page">
    <x-bg-media nombre="castillo-fondo" :video="false" velo="0.78" />

    <main class="auth-card libro-pagina">
        <a href="{{ url('/') }}" class="marca-logo auth-logo" aria-label="Dungeons for Dummies, ir al inicio">
            <x-logo-mark />
            <span class="marca-texto">
                <span class="marca">Dungeons</span>
                <span class="sub">for Dummies</span>
            </span>
        </a>
        <p class="auth-lema">Tu compañero de aventuras</p>

        {{ $slot }}
    </main>

    <x-toasts />
</body>
</html>
