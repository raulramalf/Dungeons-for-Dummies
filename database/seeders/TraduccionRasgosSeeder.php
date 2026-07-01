<?php

namespace Database\Seeders;

use App\Models\Raza;
use Illuminate\Database\Seeder;

class TraduccionRasgosSeeder extends Seeder
{
    /**
     * Traduce los nombres de rasgos raciales (campo `rasgos`) que
     * RazasSeeder importó en inglés desde DndApiService.
     *
     * IMPORTANTE: este seeder debe ejecutarse DESPUÉS de RazasSeeder y
     * TraduccionRazasSeeder (que traduce el nombre de la raza, no los
     * rasgos). Si se ejecuta dos veces no pasa nada: los rasgos que ya
     * estén en español (no aparecen en el mapa) simplemente se dejan
     * igual y se avisan como "sin traducir" en el log.
     *
     * NOTA: esto solo traduce el NOMBRE del rasgo. El campo sigue siendo
     * un array plano de strings (sin descripción), igual que lo deja
     * RazasSeeder. El Aasimar (añadido a mano en TraduccionRazasSeeder)
     * usa un formato distinto (nombre => descripción) — si más adelante
     * queréis descripciones para todas las razas, hay que unificar el
     * formato y traer las descripciones de /api/traits/{index} del SRD.
     */
    public function run(): void
    {
        $traduccionRasgos = [
            // Comunes / varias razas
            "Darkvision" => "Visión en la Oscuridad",
            "Superior Darkvision" => "Visión en la Oscuridad Superior",
            "Age" => "Edad",
            "Alignment" => "Alineamiento",
            "Size" => "Tamaño",
            "Speed" => "Velocidad",
            "Languages" => "Idiomas",

            // Enano (Dwarf)
            "Dwarven Resilience" => "Resiliencia Enana",
            "Dwarven Combat Training" => "Entrenamiento de Combate Enano",
            "Tool Proficiency" => "Competencia con Herramientas",
            "Stonecunning" => "Conocimiento de la Piedra",
            "Dwarven Toughness" => "Robustez Enana",
            "Dwarven Armor Training" => "Entrenamiento con Armadura Enana",

            // Elfo (Elf)
            "Keen Senses" => "Sentidos Agudos",
            "Fey Ancestry" => "Ascendencia Feérica",
            "Trance" => "Trance",
            "Elf Weapon Training" => "Entrenamiento con Armas Élfico",
            "Cantrip" => "Truco",
            "Extra Language" => "Idioma Adicional",
            "Mask of the Wild" => "Máscara de lo Salvaje",
            "Sunlight Sensitivity" => "Sensibilidad a la Luz Solar",
            "Drow Magic" => "Magia Drow",
            "Drow Weapon Training" => "Entrenamiento con Armas Drow",

            // Mediano (Halfling)
            "Lucky" => "Afortunado",
            "Brave" => "Valiente",
            "Halfling Nimbleness" => "Agilidad de Mediano",
            "Naturally Stealthy" => "Sigilo Natural",
            "Stout Resilience" => "Resiliencia Robusta",

            // Humano (Human)
            "Skills" => "Habilidades",
            "Feat" => "Dote",

            // Dracónido (Dragonborn)
            "Draconic Ancestry" => "Ascendencia Dracónica",
            "Breath Weapon" => "Arma de Aliento",
            "Damage Resistance" => "Resistencia al Daño",

            // Gnomo (Gnome)
            "Gnome Cunning" => "Astucia Gnoma",
            "Artificer's Lore" => "Saber del Artífice",
            "Tinker" => "Manitas",
            "Natural Illusionist" => "Ilusionista Natural",
            "Speak with Small Beasts" => "Hablar con Bestias Pequeñas",

            // Semielfo (Half-Elf)
            "Skill Versatility" => "Versatilidad de Habilidad",

            // Semiorco (Half-Orc)
            "Menacing" => "Amenazador",
            "Relentless Endurance" => "Resistencia Implacable",
            "Savage Attacks" => "Ataques Salvajes",

            // Tiflin (Tiefling)
            "Hellish Resistance" => "Resistencia Infernal",
            "Infernal Legacy" => "Legado Infernal",
        ];

        $totalTraducidos = 0;
        $totalSinTraducir = 0;
        $rasgosSinTraducir = [];

        foreach (Raza::all() as $raza) {
            if (!is_array($raza->rasgos) || count($raza->rasgos) === 0) {
                continue;
            }

            $rasgosTraducidos = [];
            $huboCambios = false;

            foreach ($raza->rasgos as $clave => $valor) {
                // Formato "asociativo" (nombre => descripción), como el Aasimar:
                // si la clave ya es texto (no un índice numérico), se asume que
                // ya está en el formato correcto/español y se deja tal cual.
                if (!is_int($clave)) {
                    $rasgosTraducidos[$clave] = $valor;
                    continue;
                }

                // Formato "lista plana" (solo nombres), el que deja RazasSeeder.
                $nombreOriginal = $valor;
                if (isset($traduccionRasgos[$nombreOriginal])) {
                    $rasgosTraducidos[] = $traduccionRasgos[$nombreOriginal];
                    $totalTraducidos++;
                    $huboCambios = true;
                } else {
                    $rasgosTraducidos[] = $nombreOriginal;
                    $totalSinTraducir++;
                    $rasgosSinTraducir[$nombreOriginal] = true;
                }
            }

            if ($huboCambios) {
                $raza->update(["rasgos" => $rasgosTraducidos]);
            }
        }

        $this->command?->info(
            "Rasgos raciales traducidos: {$totalTraducidos}, sin traducir: {$totalSinTraducir}.",
        );

        if (count($rasgosSinTraducir) > 0) {
            $this->command?->warn(
                'Rasgos sin entrada en el mapa de traducción (añádelos a $traduccionRasgos si hace falta): ' .
                    implode(", ", array_keys($rasgosSinTraducir)),
            );
        }
    }
}
