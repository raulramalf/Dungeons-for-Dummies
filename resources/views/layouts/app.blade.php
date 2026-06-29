<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Dungeons for Dummies')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    <div class="overlay" id="overlay" onclick="cerrarMenu()"></div>

    {{-- TOPBAR MÓVIL --}}
    <header class="topbar">
        <button class="hamburger" id="hamburger" onclick="toggleMenu()" aria-label="Menú">
            <span></span><span></span><span></span>
        </button>
        <span class="topbar-titulo">@yield('titulo')</span>
    </header>

    {{-- SIDEBAR --}}
    @include('layouts.navigation')

    {{-- CONTENIDO --}}
    <main class="main-content">
        <div class="page-header">
            <h1>@yield('titulo')</h1>
        </div>
        @yield('contenido')
    </main>

</body>
</html>