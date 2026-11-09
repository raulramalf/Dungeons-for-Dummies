# Dungeons for Dummies

Compañero web para jugar a **D&D 5.5e en español**: fichas de personaje (con exportación a PDF),
campañas con calendario de sesiones, bestiario del DM, una taberna social y un reto diario.

Laravel 13 · PHP 8.5 · MySQL/MariaDB · Vite · CSS propio con tokens (de Tailwind solo se usa su capa base/reset) · JS sin framework.

## Puesta en marcha

Requisitos: PHP 8.5, Composer, Node 22+ y **MySQL o MariaDB** (las migraciones usan
`ALTER TABLE … MODIFY`, por lo que SQLite no sirve).

```bash
composer install
cp .env.example .env          # ajusta DB_DATABASE / DB_USERNAME / DB_PASSWORD
php artisan key:generate
php artisan migrate
php artisan db:seed           # clases, razas y conjuros se importan de DND_API_URL (necesita red)
npm install
npm run build                 # producción · o `npm run dev` para desarrollo con HMR
php artisan serve
```

Los seeders de contenido 5.5e (subclases, trasfondos, dotes, conjuros) leen JSON locales de
`database/seeders/data/` y no necesitan red.

## Despliegue en Vercel

Vercel no es un hosting PHP: sin configuración trata el repo como un sitio estático (por eso fallaba
con `No Output Directory named "dist"`). Con `vercel.json` + `api/index.php` + el runtime
[`vercel-php`](https://github.com/juicyfx/vercel-php) (PHP 8.5) la app corre como función serverless.

**Qué hay en el repo**

| Fichero | Para qué |
|---|---|
| `vercel.json` | `npm run build` + `public/` como salida estática, función PHP y variables no secretas (rutas en `/tmp`, logs a stderr, sesión en cookie…) |
| `api/index.php` | Entrada de la función: crea los directorios de `/tmp` y delega en `public/index.php` |
| `composer.json` → script `vercel` | Compila Vite también *dentro* de la función PHP (`@vite` lee `public/build/manifest.json` del disco) |
| `.vercelignore` | Evita subir `node_modules`, `vendor`, tests… |

**Variables que debes definir en Vercel → Settings → Environment Variables** (son secretas, no van en el repo):

| Variable | Valor |
|---|---|
| `APP_KEY` | `php artisan key:generate --show` en local |
| `APP_URL` | `https://tu-proyecto.vercel.app` |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Una base **MySQL/MariaDB accesible desde internet** (la de tu PC/XAMPP no sirve). Si exige TLS: `MYSQL_ATTR_SSL_CA=/etc/ssl/certs/ca-certificates.crt` |

Vercel **no ejecuta migraciones**: lánzalas tú contra esa base desde local
(`DB_HOST=… DB_DATABASE=… php artisan migrate --force` y los seeders que necesites).

**Limitaciones de un entorno serverless** (importantes para esta app):

- **Las subidas de archivos no persisten.** El disco es de solo lectura salvo `/tmp`, que es efímero y no se
  comparte entre instancias. Avatares de usuario, imágenes de enemigos/campañas y de personajes
  (`Storage::disk('public')`) necesitan un disco externo (S3, Cloudflare R2, Vercel Blob…).
- Sesión en cookie cifrada y caché en memoria (no hay Redis/ficheros compartidos); la cola es síncrona.
- El tiempo máximo por petición lo fija tu plan de Vercel.

Si el despliegue falla, el log de *Build* y el de *Functions* de Vercel indican qué falta.

## Estructura del frontend

```
resources/
├── css/
│   ├── app.css               punto de entrada: solo @import (Vite lo une y le pone hash)
│   ├── base/                 tokens (colores, tipografía, medidas), reset, fuentes autoalojadas
│   ├── layout/shell.css      barra lateral, barra superior y pestañas móviles, hoja "Más", toasts
│   ├── components/           botones, tarjetas, formularios, alertas, diálogos, fondo multimedia…
│   └── pages/                estilos de UNA página (se cargan solo en ella)
├── js/
│   ├── app.js                solo lo global: UI (hoja/toasts), confirmaciones, lightbox, fondo
│   ├── pages/                lógica de UNA página (Alpine, calendario, feed, editor…)
│   └── d20.js                dado 3D (three.js), carga diferida desde pages/inicio.js
└── views/
    ├── layouts/              app (con navegación) y guest (login/registro)
    ├── partials/             head común, iconos SVG, piezas del feed, dado
    └── components/           x-logo-mark, x-bg-media, x-toasts, x-input-error…
```

`vite.config.js` registra automáticamente como entrada todo `css/pages/*.css` y `js/pages/*.js`.
**Añadir una página nueva:**

1. Crea `resources/css/pages/mi-pagina.css` y/o `resources/js/pages/mi-pagina.js`.
2. En la vista: `@push('head') @vite(['resources/css/pages/mi-pagina.css', 'resources/js/pages/mi-pagina.js']) @endpush`.
3. Usa los tokens de `base/tokens.css` (`var(--c-rojo)`, `var(--r-md)`, `var(--dur)`…) en vez de colores sueltos.

### Convenciones

| Necesidad | Cómo |
|---|---|
| Mensaje tras una acción | `->with('success', '…')` desde el controlador. Se muestra solo como *toast* (no copies un bloque de alerta en la vista). Desde JS: `toast('Texto', 'exito' \| 'error')`. |
| Confirmar un borrado | `<form data-confirm="¿Eliminar X?">` o `<button data-confirm="…">`. Desde JS: `if (!(await confirmar('¿Seguro?'))) return;`. **No uses `window.confirm()`.** |
| Ampliar una imagen | `<img data-lightbox>` |
| Fondo de vídeo | `<x-bg-media nombre="castillo-fondo" />` (`public/videos/{nombre}.mp4` + `.webp`). El MP4 solo se descarga en escritorio, sin «reducir movimiento» ni ahorro de datos. |
| Avatar | `$usuario->avatar_url` / `$personaje->avatar_url` (o `App\Support\Avatar::url()`). Sin avatar genera un marcador SVG local: no hay peticiones a servicios externos. |
| Cabecera propia en una página | `@section('cabecera-oculta', '1')` mantiene el `<h1>` solo para lectores de pantalla. |
| Texto de usuario en JS | Siempre `textContent`, o escapar antes de usar `innerHTML`. |

### Móvil

Hasta 1024 px la navegación pasa a una barra superior compacta + **barra de pestañas inferior** (Inicio,
Personajes, Campañas, Taberna, Más). Respeta las zonas seguras (`env(safe-area-inset-*)`), usa objetivos
táctiles de 44 px y la app es instalable (manifiesto PWA + iconos en `public/icons/`).

## Rendimiento

- Vídeos de fondo recomprimidos (H.264 720p, sin audio): de 35 MB a ~3 MB. Para regenerarlos:
  `ffmpeg -i origen.mp4 -an -vf "scale=1280:-2,fps=24" -c:v libx264 -preset slow -crf 35 -profile:v main -movflags +faststart destino.mp4`
- Fuentes autoalojadas solo en `latin` y `latin-ext` (sin Google Fonts, sin bloqueo de renderizado).
- `three.js` (dado 3D) se descarga de forma diferida y solo en Inicio; Alpine solo en el Reto Diario.
- La Taberna pagina de 10 en 10 con carga automática y resuelve «me gusta» y contadores en la propia
  consulta (de decenas de consultas por carga a 9, constantes).
- `public/.htaccess` activa compresión y caché larga para `/build` (los ficheros llevan hash).
- Si cambias contenido de referencia (razas, clases…) con un seeder y algo parece desfasado: `php artisan cache:clear`.

## Notas

- **Tests**: `phpunit.xml` apunta a MySQL (`dungeons_test`; créala antes con `CREATE DATABASE dungeons_test`).
  `tests/Unit/AvatarTest.php` y `tests/Feature/FeedTest.php` cubren avatares, paginación de la Taberna,
  que las consultas no crezcan con los comentarios (guarda anti N+1) y el saneado de etiquetas.
  Los demás (`tests/Feature/Auth/*`, `ProfileTest`) son los de plantilla de Laravel Breeze: usan
  `App\Models\User` y el campo `name`, que no existen aquí, y fallan desde antes. Conviene reescribirlos
  contra `Usuario` (hay una `UsuarioFactory` lista).
- Las plantillas de ficha PDF (`views/personajes/ficha_pdf_*.blade.php`) mantienen su CSS en línea a
  propósito: las renderiza DomPDF en servidor, que no ejecuta Vite.
