<?php

namespace Database\Seeders;

use App\Models\Conjuro;
use Illuminate\Database\Seeder;

/**
 * Importa el catálogo completo de conjuros de la edición 5.5 (2024) en español,
 * tomado del dataset público de Jtachan/DnD-5.5-Spells-ES (licencia CC BY 4.0,
 * contenido original de Wizards of the Coast).
 *
 * La API usada por ConjurosSeeder (www.dnd5eapi.co) solo cubre las reglas de
 * 2014 y no tiene traducción al español, así que este seeder complementa esos
 * datos con los 391 conjuros de la revisión 5.5 directamente desde un JSON
 * local (no requiere conexión a internet para sembrar la base de datos).
 *
 * Fuente: https://github.com/Jtachan/DnD-5.5-Spells-ES
 * Ruta del dataset: spells/ed5_5/all.json
 *
 * Es seguro ejecutarlo varias veces: usa el nombre del conjuro como clave
 * para actualizar en vez de duplicar (updateOrCreate).
 */
class ConjurosEdicion55Seeder extends Seeder
{
    /**
     * Traducción de las clases del dataset (en español) a los nombres en
     * inglés que ya existen en la tabla `clases`, sembrada desde la API
     * dnd5eapi.co. Así el selector de conjuros por clase en la ficha del
     * personaje (personajes_editar.blade.php) funciona igual para los
     * conjuros antiguos (2014) y los nuevos (5.5).
     */
    private const TRADUCCION_CLASES = [
        'Bardo'       => 'Bard',
        'Brujo'       => 'Warlock',
        'Clérigo'     => 'Cleric',
        'Druida'      => 'Druid',
        'Explorador'  => 'Ranger',
        'Hechicero'   => 'Sorcerer',
        'Mago'        => 'Wizard',
        'Paladín'     => 'Paladin',
        // Por si el dataset añade clases nuevas en el futuro (Artífice, etc.)
        // y no están en este mapa, se conserva el nombre original en español
        // en vez de perder el dato.
    ];

    public function run(): void
    {
        $ruta = database_path('seeders/data/conjuros_5_5_es.json');

        if (!file_exists($ruta)) {
            $this->command?->error("No se encuentra el dataset en: {$ruta}");
            return;
        }

        $conjuros = json_decode(file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);

        $creados = 0;
        $actualizados = 0;

        foreach ($conjuros as $item) {
            $existia = Conjuro::where('nombre', $item['nombre'])
                ->where('edicion', '5.5')
                ->exists();

            Conjuro::updateOrCreate(
                [
                    'nombre'  => $item['nombre'],
                    'edicion' => '5.5',
                ],
                [
                    'nivel'                     => $item['nivel'],
                    'escuela'                   => $item['escuela'],
                    'tiempo_lanzamiento'        => $item['tiempo_de_lanzamiento'],
                    'alcance'                   => $this->normalizarAlcance($item['alcance']),
                    'componentes'               => $item['componentes'],
                    'material'                  => $item['materiales'],
                    'duracion'                  => $item['duracion'],
                    'concentracion'             => $item['concentracion'],
                    'ritual'                    => $item['ritual'],
                    'descripcion'               => $this->normalizarDescripcion($item['descripcion']),
                    'clases'                    => $this->traducirClases($item['clases']),
                    'tirada_de_salvacion'       => $item['tirada_de_salvacion'],
                    'requiere_ataque'           => $item['requiere_ataque'],
                    'requiere_objetivo_visible' => $item['visible'],
                ]
            );

            $existia ? $actualizados++ : $creados++;
        }

        $this->command?->info("Conjuros 5.5e: {$creados} creados, {$actualizados} actualizados (total dataset: " . count($conjuros) . ").");
    }

    /**
     * El dataset guarda el alcance como string ("Toque", "Lanzador") o como
     * lista [imperial, métrico] (ej. ["150 pies", "45 m"]). Lo combinamos en
     * un único string legible para la columna `alcance`.
     */
    private function normalizarAlcance(string|array $alcance): string
    {
        if (is_array($alcance)) {
            return "{$alcance[0]} ({$alcance[1]})";
        }

        return $alcance;
    }

    /**
     * La descripción puede venir duplicada: una variante con unidades en
     * pies (imperial) y otra en metros (métrico). Nos quedamos con la
     * variante métrica por consistencia con el resto de la app en español;
     * si solo hay una variante (sin unidades que convertir), se usa tal cual.
     */
    private function normalizarDescripcion(string|array $descripcion): string
    {
        if (is_array($descripcion)) {
            return $descripcion[1] ?? $descripcion[0];
        }

        return $descripcion;
    }

    private function traducirClases(array $clases): array
    {
        return array_map(
            fn (string $clase) => self::TRADUCCION_CLASES[$clase] ?? $clase,
            $clases
        );
    }
}