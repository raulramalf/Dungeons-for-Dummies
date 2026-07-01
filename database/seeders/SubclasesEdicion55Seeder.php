<?php

namespace Database\Seeders;

use App\Models\Clase;
use App\Models\Subclase;
use Illuminate\Database\Seeder;

/**
 * Importa las subclases de la edición 5.5 (2024) en español, traducidas a
 * partir del SRD 5.2.1 (Wizards of the Coast, licencia CC BY 4.0), tomando
 * como fuente estructurada los fixtures públicos del proyecto open5e-api
 * (data/v2/wizards-of-the-coast/srd-2024/CharacterClass.json, campo
 * `subclass_of`, + ClassFeature.json para los rasgos).
 *
 * El SRD 5.2.1 libera exactamente 1 subclase por cada una de las 12 clases
 * base (Camino del Berserker, Colegio del Conocimiento, Dominio de la
 * Vida...). El resto de subclases del Player's Handbook 2024 (más de 90)
 * son propiedad protegida de Wizards; si se quieren añadir hay que
 * introducirlas manualmente respetando el copyright.
 *
 * Las clases ya están sembradas en inglés por ClasesSeeder (vienen de
 * dnd5eapi.co), así que aquí se resuelve `clase_id` buscando por el nombre
 * en inglés de la clase padre.
 *
 * Fuente: https://github.com/open5e/open5e-api
 * Es seguro ejecutarlo varias veces: usa el nombre + edición como clave
 * para actualizar en vez de duplicar (updateOrCreate).
 */
class SubclasesEdicion55Seeder extends Seeder
{
    public function run(): void
    {
        $ruta = database_path('seeders/data/subclases_5_5_es.json');

        if (!file_exists($ruta)) {
            $this->command?->error("No se encuentra el dataset en: {$ruta}");
            return;
        }

        $subclases = json_decode(file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);

        $creados = 0;
        $actualizados = 0;
        $sinClase = 0;

        foreach ($subclases as $item) {
            $clase = Clase::where('nombre', $item['clase'])->first();

            if (!$clase) {
                $this->command?->warn("Clase '{$item['clase']}' no encontrada, se omite la subclase '{$item['nombre']}'. ¿Corriste ClasesSeeder antes?");
                $sinClase++;
                continue;
            }

            $existia = Subclase::where('nombre', $item['nombre'])
                ->where('edicion', '5.5')
                ->exists();

            Subclase::updateOrCreate(
                [
                    'nombre'  => $item['nombre'],
                    'edicion' => '5.5',
                ],
                [
                    'clase_id'          => $clase->id,
                    'descripcion'       => $item['descripcion'],
                    'nivel_disponible'  => $item['nivel_disponible'],
                    'rasgos'            => $item['rasgos'],
                ]
            );

            $existia ? $actualizados++ : $creados++;
        }

        $this->command?->info("Subclases 5.5e: {$creados} creadas, {$actualizados} actualizadas, {$sinClase} omitidas por falta de clase (total dataset: " . count($subclases) . ").");
    }
}
