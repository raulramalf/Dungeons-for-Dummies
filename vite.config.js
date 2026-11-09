import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { globSync } from 'node:fs';

/*
 * Entradas:
 *  · app.css / app.js          → se cargan en todas las páginas (layout).
 *  · css/pages/*.css           → estilos de una página concreta.
 *  · js/pages/*.js             → lógica de una página concreta (carga diferida
 *                                de librerías pesadas como three.js o Alpine).
 * Los ficheros que empiezan por "_" son parciales y no son entrada.
 */
const paginas = [
    ...globSync('resources/css/pages/*.css'),
    ...globSync('resources/js/pages/*.js'),
]
    .map((ruta) => ruta.replaceAll('\\', '/'))
    .filter((ruta) => !ruta.split('/').pop().startsWith('_'));

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', ...paginas],
            refresh: true,
        }),
    ],
    build: {
        // El dado 3D (three.js) es un chunk aparte y pesado a propósito
        chunkSizeWarningLimit: 700,
    },
});
