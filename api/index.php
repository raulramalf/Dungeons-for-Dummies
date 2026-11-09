<?php

/*
 * Entrada serverless para Vercel (runtime vercel-php).
 *
 * El sistema de ficheros de una función de Vercel es de SOLO LECTURA salvo /tmp.
 * Laravel necesita escribir vistas compiladas, caché y logs, así que aquí se
 * redirigen esas rutas a /tmp ANTES de arrancar el framework.
 *
 * Los valores se fijan aquí (y no solo en el bloque "env" de vercel.json) para que
 * la app arranque aunque Vercel no pase ese bloque a la función. Cualquier variable
 * que definas en Vercel → Settings → Environment Variables tiene prioridad.
 *
 * Nota: /tmp es efímero y no se comparte entre instancias: no sirve para
 * guardar archivos subidos por los usuarios (ver README, "Despliegue en Vercel").
 */

$predeterminadas = [
    'APP_ENV' => 'production',
    'APP_DEBUG' => 'false',
    'LOG_CHANNEL' => 'stderr',
    'LOG_LEVEL' => 'warning',
    'TRUSTED_PROXIES' => '*',
    'SESSION_DRIVER' => 'cookie',
    'SESSION_SECURE_COOKIE' => 'true',
    'CACHE_STORE' => 'array',
    'QUEUE_CONNECTION' => 'sync',
    'LARAVEL_STORAGE_PATH' => '/tmp/storage',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'APP_EVENTS_CACHE' => '/tmp/events.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_ROUTES_CACHE' => '/tmp/routes.php',
    'APP_SERVICES_CACHE' => '/tmp/services.php',
];

foreach ($predeterminadas as $clave => $valor) {
    if (getenv($clave) === false && ! isset($_ENV[$clave]) && ! isset($_SERVER[$clave])) {
        putenv("$clave=$valor");
        $_ENV[$clave] = $_SERVER[$clave] = $valor;
    }
}

$almacenamiento = getenv('LARAVEL_STORAGE_PATH') ?: '/tmp/storage';

foreach (['app/public', 'framework/cache/data', 'framework/sessions', 'framework/views', 'logs', 'fonts'] as $dir) {
    if (! is_dir("$almacenamiento/$dir")) {
        @mkdir("$almacenamiento/$dir", 0755, true);
    }
}

/*
 * MODO DIAGNÓSTICO: define DIAGNOSTICO=1 en las variables de entorno de Vercel,
 * redespliega y abre la web. Comprueba lo imprescindible y, si todo está, arranca
 * Laravel con APP_DEBUG=true para ver el error real en pantalla en vez de un 500
 * genérico. Quita la variable cuando termines: muestra detalles internos.
 */
if (getenv('DIAGNOSTICO') === '1') {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);

    $raiz = dirname(__DIR__);
    $problemas = [];

    foreach (['vendor/autoload.php' => 'composer install no generó vendor/', 'bootstrap/app.php' => 'falta bootstrap/app.php', 'public/index.php' => 'falta public/index.php'] as $fichero => $motivo) {
        if (! is_file("$raiz/$fichero")) {
            $problemas[] = "$fichero: $motivo";
        }
    }
    if (! is_file("$raiz/public/build/manifest.json")) {
        $problemas[] = 'public/build/manifest.json: no existe en la función (el script "vercel" de composer.json no compiló Vite)';
    }
    if (! getenv('APP_KEY')) {
        $problemas[] = 'APP_KEY: no está definida en Vercel → Settings → Environment Variables';
    }
    if (! is_writable($almacenamiento)) {
        $problemas[] = "$almacenamiento: no se puede escribir";
    }

    if ($problemas) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
        echo "DIAGNÓSTICO — PHP " . PHP_VERSION . "\n\nProblemas detectados:\n - " . implode("\n - ", $problemas) . "\n";
        exit;
    }

    putenv('APP_DEBUG=true');
    $_ENV['APP_DEBUG'] = $_SERVER['APP_DEBUG'] = 'true';
}

require __DIR__ . '/../public/index.php';
