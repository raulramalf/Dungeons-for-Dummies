{{--
    <head> común a todos los layouts (app y guest).
    Espera: $tituloPagina (string, sin escapar) y, opcional, $descripcion.
--}}
@php
    $descripcion = $descripcion ?? 'Dungeons for Dummies: gestiona tus personajes de D&D 5.5e, organiza campañas y comparte tus hazañas en la taberna.';
    // Precarga de las fuentes críticas (si el manifiesto de Vite no las conoce, se omite sin romper)
    $fuentes = array_filter([
        rescue(fn () => Vite::asset('node_modules/@fontsource-variable/inter/files/inter-latin-wght-normal.woff2'), null, false),
        rescue(fn () => Vite::asset('node_modules/@fontsource-variable/cinzel/files/cinzel-latin-wght-normal.woff2'), null, false),
    ]);
@endphp
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="description" content="{{ $descripcion }}">
<meta name="theme-color" content="#120309">
<meta name="color-scheme" content="dark">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Dungeons">
<meta property="og:type" content="website">
<meta property="og:site_name" content="Dungeons for Dummies">
<meta property="og:title" content="{{ $tituloPagina ?: 'Dungeons for Dummies' }}">
<meta property="og:description" content="{{ $descripcion }}">

<title>{{ $tituloPagina ? $tituloPagina.' · ' : '' }}Dungeons for Dummies</title>

<link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="alternate icon" href="{{ asset('favicon.ico') }}" sizes="32x32">
<link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

@foreach ($fuentes as $fuente)
    <link rel="preload" href="{{ $fuente }}" as="font" type="font/woff2" crossorigin>
@endforeach

@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('head')
