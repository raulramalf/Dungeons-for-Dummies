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
        ];

        foreach ($enemigos as $enemigo) {
            MinijuegoEnemigo::updateOrCreate(
                ["nombre" => $enemigo["nombre"]],
                $enemigo,
            );
        }
    }
}
