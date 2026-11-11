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

    <div class="mesa">
    <div class="libro">
    @include('layouts.navigation')

    <div class="libro-base libro-base--izq" aria-hidden="true"></div>
    <div class="libro-base" aria-hidden="true"></div>
    <main class="main-content libro-pagina" id="contenido" tabindex="-1">
        <div class="libro-cara-frente">
        @php
            $metaPagina = [
                'personajes*' => ['sword',  'Tus héroes, sus historias y sus fichas.'],
                'campanyas*'  => ['scroll', 'Organiza sesiones, invita a tu grupo y lleva las notas.'],
                'feed*'       => ['feed',   'Cuenta tus hazañas y charla con otros aventureros.'],
                'enemigos*'   => ['skull',  'Criaturas y monstruos para tus mesas.'],
                'minijuego*'  => ['dice',   'Un jefe nuevo cada día. ¿Cuánto sabes de D&D?'],
                'perfil*'     => ['user',   'Tu cuenta, tu avatar y tu seguridad.'],
                'profile*'    => ['user',   'Tu cuenta, tu avatar y tu seguridad.'],
            ];
            $iconoPagina = trim($__env->yieldContent('icono')) ?: null;
            $subPagina   = html_entity_decode(trim($__env->yieldContent('subtitulo')), ENT_QUOTES) ?: null;
            foreach ($metaPagina as $patron => [$ic, $sub]) {
                if (request()->is($patron)) { $iconoPagina ??= $ic; $subPagina ??= $sub; break; }
            }
        @endphp
        @php
            $romanos = ['I','II','III','IV','V','VI','VII'];
            $folio = null;
            foreach (['/' => 0, 'personajes*' => 1, 'campanyas*' => 2, 'feed*' => 3, 'enemigos*' => 4, 'minijuego*' => 5, 'perfil*' => 6, 'profile*' => 6] as $pat => $i) {
                if (request()->is($pat)) { $folio = '— '.$romanos[$i].' —'; break; }
            }
        @endphp
        <div class="page-header @hasSection('cabecera-oculta') page-header--oculta @endif">
            <div class="page-titulo">
                @if ($iconoPagina)
                    <span class="page-icono" aria-hidden="true">@include('partials.icon', ['name' => $iconoPagina])</span>
                @endif
                <div>
                    <h1>@yield('titulo')</h1>
                    @if ($subPagina)<p class="page-subtitulo">{{ $subPagina }}</p>@endif
                </div>
            </div>
            @hasSection('acciones')
                <div class="page-acciones">@yield('acciones')</div>
            @endif
        </div>

        @yield('contenido')

        <footer class="folio" aria-hidden="true"><span>{{ $folio ?? '' }}</span></footer>
        </div>
        <div class="libro-cara-dorso" aria-hidden="true"></div>
    </main>
    </div>
    </div>

    <x-toasts />

    @stack('scripts')
</body>
</html>
