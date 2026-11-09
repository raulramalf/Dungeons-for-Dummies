<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * Resuelve la URL de un avatar y genera marcadores de posición locales.
 *
 * Antes cada vista repetía `str_starts_with($a, 'http') ? $a : Storage::url($a)`
 * y, sin avatar, pedía una imagen a ui-avatars.com (una petición externa por
 * tarjeta). El marcador ahora es un SVG en línea: cero peticiones de red.
 */
class Avatar
{
    /** Devuelve la URL pública del avatar o, si no hay, un marcador con la inicial. */
    public static function url(?string $ruta, string $nombre, string $fondo = '#b30303'): string
    {
        if (! $ruta) {
            return self::marcador($nombre, $fondo);
        }

        return str_starts_with($ruta, 'http') ? $ruta : Storage::url($ruta);
    }

    /** SVG (data URI) con la inicial del nombre en dorado sobre un degradado del color indicado. */
    public static function marcador(string $nombre, string $fondo = '#b30303'): string
    {
        $inicial = mb_strtoupper(mb_substr(trim($nombre), 0, 1)) ?: '?';
        $inicial = htmlspecialchars($inicial, ENT_XML1 | ENT_QUOTES, 'UTF-8');

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">'
            . '<defs><radialGradient id="g" cx="35%" cy="25%" r="90%">'
            . '<stop offset="0" stop-color="' . $fondo . '"/><stop offset="1" stop-color="#2a0409"/>'
            . '</radialGradient></defs>'
            . '<rect width="100" height="100" fill="url(#g)"/>'
            . '<text x="50" y="50" dy=".35em" text-anchor="middle" '
            . 'font-family="Georgia,serif" font-size="46" font-weight="700" fill="#e3c25a">'
            . $inicial . '</text></svg>';

        return 'data:image/svg+xml,' . rawurlencode($svg);
    }
}
