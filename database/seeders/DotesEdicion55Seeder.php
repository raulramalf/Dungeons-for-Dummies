<?php

namespace Database\Seeders;

use App\Models\Dote;
use Illuminate\Database\Seeder;

/**
 * Importa el catálogo de dotes de la edición 5.5 (2024) en español,
 * traducido a partir del SRD 5.2.1 (Wizards of the Coast, licencia CC BY
 * 4.0), tomando como fuente estructurada los fixtures públicos del proyecto
 * open5e-api (data/v2/wizards-of-the-coast/srd-2024/Feat.json +
 * FeatBenefit.json).
 *
 * El SRD 5.2.1 libera 17 de las ~80 dotes del Player's Handbook 2024: las
 * 4 dotes de Origen, 2 Generales, 4 de Estilo de Combate y 7 dones épicos
 * (nivel 19+). El resto de dotes Generales y de Épica del manual son
 * propiedad protegida de Wizards; si se quieren añadir hay que
 * introducirlas manualmente respetando el copyright (usa la 'categoria'
 * ya presente en el esquema para clasificarlas igual que estas).
 *
 * Fuente: https://github.com/open5e/open5e-api
 * Es seguro ejecutarlo varias veces: usa el nombre + edición como clave
 * para actualizar en vez de duplicar (updateOrCreate).
 */
class DotesEdicion55Seeder extends Seeder
{
    public function run(): void
    {
        $ruta = database_path('seeders/data/dotes_5_5_es.json');

        if (!file_exists($ruta)) {
            $this->command?->error("No se encuentra el dataset en: {$ruta}");
            return;
        }

        $dotes = json_decode(file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);

        $creados = 0;
        $actualizados = 0;

        foreach ($dotes as $item) {
            $existia = Dote::where('nombre', $item['nombre'])
                ->where('edicion', '5.5')
                ->exists();

            Dote::updateOrCreate(
                [
                    'nombre'  => $item['nombre'],
                    'edicion' => '5.5',
                ],
                [
                    'categoria'                  => $item['categoria'],
                    'descripcion'                => $item['descripcion'],
                    'prerequisitos'              => $item['prerequisitos'],
                    'beneficios'                 => $item['beneficios'],
                    'incremento_caracteristica'  => $item['incremento_caracteristica'],
                    'repetible'                  => $item['repetible'],
                ]
            );

            $existia ? $actualizados++ : $creados++;
        }

        $this->command?->info("Dotes 5.5e: {$creados} creadas, {$actualizados} actualizadas (total dataset: " . count($dotes) . ").");
    }
}
