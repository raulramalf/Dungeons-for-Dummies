<?php

namespace Database\Seeders;

use App\Models\MinijuegoEnemigo;
use Illuminate\Database\Seeder;

class MinijuegoEnemigosSeeder extends Seeder
{
    public function run(): void
    {
        $enemigos = [
            [
                "nombre" => "Goblin Merodeador",
                "titulo" => "Terror de los Caminos",
                "descripcion" =>
                    "Un goblin astuto que ha estado emboscando caravanas en el bosque.",
                "vida_max" => 40,
                "tipo" => "Humanoide",
                "arte" => "goblin",
                "escenario" => "bosque",
            ],
            [
                "nombre" => "Esqueleto Guardián",
                "titulo" => "Centinela de la Cripta",
                "descripcion" =>
                    "Un esqueleto animado que custodia una cripta olvidada.",
                "vida_max" => 55,
                "tipo" => "No-muerto",
                "arte" => "esqueleto",
                "escenario" => "cripta",
            ],
            [
                "nombre" => "Lobo de las Sombras",
                "titulo" => "Cazador Nocturno",
                "descripcion" =>
                    "Un lobo corrompido por magia oscura que acecha en la niebla.",
                "vida_max" => 48,
                "tipo" => "Bestia",
                "arte" => "lobo",
                "escenario" => "bosque_nocturno",
            ],
            [
                "nombre" => "Dragón Rojo Joven",
                "titulo" => "Azote de las Montañas",
                "descripcion" => "Un joven dragón que ya guarda un pequeño tesoro y un gran apetito.",
                "vida_max" => 80,
                "tipo" => "Dragón",
                "arte" => "dragon",
                "escenario" => "cripta",
            ],
            [
                "nombre" => "Liche Olvidado",
                "titulo" => "Señor de los Huesos",
                "descripcion" => "Un hechicero que burló a la muerte y aún guarda su filacteria.",
                "vida_max" => 70,
                "tipo" => "No-muerto",
                "arte" => "liche",
                "escenario" => "cripta",
            ],
            [
                "nombre" => "Mímico Glotón",
                "titulo" => "El Cofre Hambriento",
                "descripcion" => "Parece un tesoro. No lo es.",
                "vida_max" => 52,
                "tipo" => "Monstruosidad",
                "arte" => "mimico",
                "escenario" => "cripta",
            ],
            [
                "nombre" => "Troll del Pantano",
                "titulo" => "Regenerador Voraz",
                "descripcion" => "Sus heridas se cierran solas, a menos que uses fuego.",
                "vida_max" => 64,
                "tipo" => "Gigante",
                "arte" => "troll",
                "escenario" => "bosque_nocturno",
            ],
        ];

        foreach ($enemigos as $enemigo) {
            MinijuegoEnemigo::updateOrCreate(
                ["nombre" => $enemigo["nombre"]],
                $enemigo,
            );
        }
    }
}
