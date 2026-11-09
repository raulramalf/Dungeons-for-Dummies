<?php

/*
 * Entrada serverless para Vercel (runtime vercel-php).
 *
 * El sistema de ficheros de una función de Vercel es de SOLO LECTURA salvo /tmp.
 * Laravel necesita escribir vistas compiladas, caché y logs, así que vercel.json
 * redirige todas esas rutas a /tmp mediante variables de entorno
 * (LARAVEL_STORAGE_PATH, VIEW_COMPILED_PATH, APP_*_CACHE) y aquí se crean los
 * directorios en cada arranque en frío. Después se delega en el index normal.
 *
 * Nota: /tmp es efímero y no se comparte entre instancias: no sirve para
 * guardar archivos subidos por los usuarios (ver README, "Despliegue en Vercel").
 */

$almacenamiento = getenv('LARAVEL_STORAGE_PATH') ?: '/tmp/storage';

foreach (['app/public', 'framework/cache/data', 'framework/sessions', 'framework/views', 'logs', 'fonts'] as $dir) {
    if (! is_dir("$almacenamiento/$dir")) {
        @mkdir("$almacenamiento/$dir", 0755, true);
    }
}

require __DIR__ . '/../public/index.php';
