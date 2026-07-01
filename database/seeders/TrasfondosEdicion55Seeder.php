<?php

namespace Database\Seeders;

use App\Models\Trasfondo;
use Illuminate\Database\Seeder;

/**
 * Importa el catálogo de trasfondos de la edición 5.5 (2024) en español,
 * traducido a partir del SRD 5.2.1 (Wizards of the Coast, licencia CC BY
 * 4.0), tomando como fuente estructurada los fixtures públicos del proyecto
 * open5e-api (data/v2/wizards-of-the-coast/srd-2024/Background.json).
 *
 * El SRD 5.2.1 solo libera 4 de los 16 trasfondos del Player's Handbook
 * 2024 (Acólito, Criminal, Erudito y Soldado); el resto son propiedad
 * protegida de Wizards y no pueden distribuirse aquí. Si se quieren
 * añadir, hay que introducirlos manualmente respetando el copyright.
 *
 * A diferencia de 2014, en 5.5e cada trasfondo ya no da idiomas ni tabla
 * de rasgo/ideal/vínculo/defecto (eso quedó como algo narrativo libre,
 * fuera de las reglas mecánicas); en cambio otorga una mejora de
 * característica y una dote de origen obligatoria.
 *
 * Fuente: https://github.com/open5e/open5e-api
 * Es seguro ejecutarlo varias veces: usa el nombre + edición como clave
 * para actualizar en vez de duplicar (updateOrCreate).
 */
class TrasfondosEdicion55Seeder extends Seeder
{
    public function run(): void
    {
        $ruta = database_path('seeders/data/trasfondos_5_5_es.json');

        if (!file_exists($ruta)) {
            $this->command?->error("No se encuentra el dataset en: {$ruta}");
            return;
        }

        $trasfondos = json_decode(file_get_contents($ruta), true, flags: JSON_THROW_ON_ERROR);

        $creados = 0;
        $actualizados = 0;

        foreach ($trasfondos as $item) {
            $existia = Trasfondo::where('nombre', $item['nombre'])
                ->where('edicion', '5.5')
                ->exists();

            Trasfondo::updateOrCreate(
                [
                    'nombre'  => $item['nombre'],
                    'edicion' => '5.5',
                ],
                [
                    'descripcion'                => $item['descripcion'],
                    'competencias_habilidades'   => $item['competencias_habilidades'],
                    'competencias_herramientas'  => $item['competencias_herramientas'],
                    'idiomas'                    => null, // 5.5e: los idiomas vienen de la especie, no del trasfondo
                    'equipo_inicial'              => $item['equipo_inicial'],
                    'rasgo_personalidad'         => null, // 5.5e: sin tabla mecánica, queda libre para el jugador
                    'ideal'                       => null,
                    'vinculo'                     => null,
                    'defecto'                     => null,
                    'caracteristica_especial'    => null,
                    'mejora_caracteristicas'     => $item['mejora_caracteristicas'],
                    'dote_origen'                 => $item['dote_origen'],
                ]
            );

            $existia ? $actualizados++ : $creados++;
        }

        $this->command?->info("Trasfondos 5.5e: {$creados} creados, {$actualizados} actualizados (total dataset: " . count($trasfondos) . ").");
    }
}
