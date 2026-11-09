@php
    // yieldContent devuelve texto ya escapado por @section('x', '...'); se decodifica
    // para que {{ }} lo escape una sola vez (si no, "D&D" saldría como "D&amp;amp;D")
    $tituloPagina = html_entity_decode(trim($__env->yieldContent('titulo')), ENT_QUOTES);
    $descripcion  = html_entity_decode(trim($__env->yieldContent('descripcion')), ENT_QUOTES) ?: null;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['tituloPagina' => $tituloPagina] + ($descripcion ? ['descripcion' => $descripcion] : []))
</head>
<body>
    <a href="#contenido" class="skip-link">Saltar al contenido</a>

    @include('layouts.navigation')

    <main class="main-content" id="contenido" tabindex="-1">
        <div class="page-header @hasSection('cabecera-oculta') page-header--oculta @endif">
            <h1>@yield('titulo')</h1>
            @hasSection('acciones')
                <div class="page-acciones">@yield('acciones')</div>
            @endif
        </div>

        @yield('contenido')
    </main>

    <x-toasts />

    @stack('scripts')
</body>
</html>
