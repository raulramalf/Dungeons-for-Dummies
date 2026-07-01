<?php
namespace Database\Seeders;

use App\Models\Clase;
use App\Models\Raza;
use Illuminate\Database\Seeder;

class TraduccionRazasSeeder extends Seeder
{
    public function run(): void
    {
        $traduccionRazas = [
            "Dwarf" => "Enano",
            "Elf" => "Elfo",
            "Halfling" => "Mediano",
            "Human" => "Humano",
            "Dragonborn" => "Dracónido",
            "Gnome" => "Gnomo",
            "Half-Elf" => "Semielfo",
            "Half-Orc" => "Semiorco",
            "Tiefling" => "Tiflin",
        ];

        $traducidas = 0;
        $sinTraduccion = 0;

        foreach (Raza::all() as $raza) {
            if (isset($traduccionRazas[$raza->nombre])) {
                $raza->update(["nombre" => $traduccionRazas[$raza->nombre]]);
                $traducidas++;
            } else {
                $this->command?->warn(
                    "Raza '{$raza->nombre}' no está en el mapa de traducción, se deja tal cual.",
                );
                $sinTraduccion++;
            }
        }

        $this->command?->info(
            "Razas traducidas: {$traducidas}, sin traducir: {$sinTraduccion}.",
        );

        // El Aasimar es una especie nueva del SRD 2024 (no existía en el SRD 2014
        // que siembra RazasSeeder), así que se inserta directamente en vez de traducirse.
        Raza::firstOrCreate(
            ["nombre" => "Aasimar"],
            [
                "velocidad" => 30,
                "tamaño" => "Mediano o Pequeño (a elección)",
                "bonificadores_caracteristica" => null,
                "idiomas" => ["Común"],
                "vision" => ["Visión en la Oscuridad 18 m"],
                "rasgos" => [
                    "Resistencia Celestial" =>
                        "Tienes resistencia al daño necrótico y al daño radiante.",
                    "Manos Sanadoras" =>
                        "Como acción mágica, tocas a una criatura y tiras tantos d4 como tu bonificador de competencia; la criatura recupera esos puntos de golpe. Una vez por descanso largo.",
                    "Portador de Luz" =>
                        "Conoces el truco Luz. El Carisma es tu característica lanzadora para él.",
                    "Revelación Celestial" =>
                        "A partir de nivel 3, como acción adicional te transformas durante 1 minuto (una vez por descanso largo), eligiendo entre Alas Celestiales (velocidad de vuelo), Resplandor Interior (daño radiante en área) o Manto Necrótico (asusta y añade daño). Mientras dura, cada turno inflige daño extra igual a tu bonificador de competencia.",
                ],
                "descripcion" =>
                    "Mortales que llevan una chispa de poder celestial en su interior, heredada de un antepasado angélico o de una bendición divina.",
            ],
        );

        $this->command?->info("Aasimar añadido (o ya existente).");

        // --- Traducción de clases ---
        $traduccionClases = [
            "Barbarian" => "Bárbaro",
            "Bard" => "Bardo",
            "Cleric" => "Clérigo",
            "Druid" => "Druida",
            "Fighter" => "Guerrero",
            "Monk" => "Monje",
            "Paladin" => "Paladín",
            "Ranger" => "Explorador",
            "Rogue" => "Pícaro",
            "Sorcerer" => "Hechicero",
            "Warlock" => "Brujo",
            "Wizard" => "Mago",
        ];

        $clasesTraducidas = 0;
        $clasesSinTraduccion = 0;

        foreach (Clase::all() as $clase) {
            if (isset($traduccionClases[$clase->nombre])) {
                $clase->update(["nombre" => $traduccionClases[$clase->nombre]]);
                $clasesTraducidas++;
            } else {
                $this->command?->warn(
                    "Clase '{$clase->nombre}' no está en el mapa de traducción, se deja tal cual.",
                );
                $clasesSinTraduccion++;
            }
        }

        $this->command?->info(
            "Clases traducidas: {$clasesTraducidas}, sin traducir: {$clasesSinTraduccion}.",
        );
    }
}
