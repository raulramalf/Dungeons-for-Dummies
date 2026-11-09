@props(['nombre', 'velo' => 0.62, 'video' => true])
{{--
    Fondo de vídeo ambiental. El póster WebP (≈45 KB) se pinta al instante; el
    vídeo solo lo inyecta js/bg-media.js en escritorio, sin "reducir movimiento"
    y sin ahorro de datos. Móviles y tablets nunca descargan el MP4.
    Uso: <x-bg-media nombre="castillo-fondo" />   (public/videos/{nombre}.mp4|.webp)
    Con :video="false" solo se usa el póster (p. ej. pantallas de acceso).
--}}
<div class="bg-media" aria-hidden="true"
     @if ($video) data-bg-video="{{ asset('videos/'.$nombre.'.mp4') }}" @endif
     style="background-image:url('{{ asset('videos/'.$nombre.'.webp') }}');--bg-media-velo:{{ $velo }}"></div>
