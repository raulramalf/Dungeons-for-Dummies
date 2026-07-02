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
                "rondas" => [
                    [
                        "pregunta" =>
                            "El goblin se abalanza sobre ti. ¿Qué haces?",
                        "opciones" => [
                            [
                                "texto" => "Le atacas con tu espada corta",
                                "daño" => 10,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "¿Cuál es el dado de golpe de un Goblin en 5e? → d6",
                                "daño" => 15,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿Cuál es el dado de golpe de un Goblin en 5e? → d10",
                                "daño" => 3,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                            [
                                "texto" => "Lanzas Rayo de Escarcha",
                                "daño" => 8,
                                "es_trivia" => false,
                            ],
                        ],
                    ],
                    [
                        "pregunta" =>
                            "El goblin retrocede y saca una honda. ¿Cómo respondes?",
                        "opciones" => [
                            [
                                "texto" =>
                                    '¿Los goblins tienen la característica "Fuga Astuta"? → Sí',
                                "daño" => 14,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    '¿Los goblins tienen la característica "Fuga Astuta"? → No',
                                "daño" => 2,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                            [
                                "texto" => "Le lanzas una daga arrojadiza",
                                "daño" => 9,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "Cargas cuerpo a cuerpo con desventaja",
                                "daño" => 6,
                                "es_trivia" => false,
                            ],
                        ],
                    ],
                    [
                        "pregunta" =>
                            "Herido, el goblin se refugia tras una roca. ¿Último golpe?",
                        "opciones" => [
                            [
                                "texto" => "Golpe certero con tu arma",
                                "daño" => 12,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" => "Lanzas Proyectil de Fuego",
                                "daño" => 16,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "¿La CD (Clase de Desafío) de un Goblin es 1/4? → Sí",
                                "daño" => 13,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿La CD (Clase de Desafío) de un Goblin es 1/4? → No, es 2",
                                "daño" => 2,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                        ],
                    ],
                ],
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
                "rondas" => [
                    [
                        "pregunta" =>
                            "El esqueleto levanta su espada oxidada. ¿Cómo actúas?",
                        "opciones" => [
                            [
                                "texto" =>
                                    "¿Los no-muertos son inmunes al veneno? → Sí",
                                "daño" => 15,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿Los no-muertos son inmunes al veneno? → No",
                                "daño" => 3,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                            [
                                "texto" => "Le atacas con tu maza",
                                "daño" => 11,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" => "Lanzas Luz Solar Radiante",
                                "daño" => 18,
                                "es_trivia" => false,
                            ],
                        ],
                    ],
                    [
                        "pregunta" =>
                            "Los huesos del esqueleto crujen mientras avanza. ¿Siguiente movimiento?",
                        "opciones" => [
                            [
                                "texto" => "Le das con tu arco",
                                "daño" => 10,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "¿El daño contundente es efectivo contra esqueletos? → Sí",
                                "daño" => 17,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿El daño contundente es efectivo contra esqueletos? → No",
                                "daño" => 2,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                            [
                                "texto" => "Lanzas Golpe de Escudo",
                                "daño" => 7,
                                "es_trivia" => false,
                            ],
                        ],
                    ],
                    [
                        "pregunta" =>
                            "El esqueleto tambalea, casi hecho pedazos. ¿Golpe final?",
                        "opciones" => [
                            [
                                "texto" => "Estocada final con tu espada",
                                "daño" => 13,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "¿Un Esqueleto tiene CA 13 en el Manual de Monstruos? → Sí",
                                "daño" => 16,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿Un Esqueleto tiene CA 13 en el Manual de Monstruos? → No, CA 18",
                                "daño" => 4,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                            [
                                "texto" => "Lanzas Bola de Fuego",
                                "daño" => 20,
                                "es_trivia" => false,
                            ],
                        ],
                    ],
                ],
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
                "rondas" => [
                    [
                        "pregunta" =>
                            "El lobo gruñe y muestra sus colmillos. ¿Qué haces?",
                        "opciones" => [
                            [
                                "texto" => "Le atacas con tu lanza",
                                "daño" => 11,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "¿Los lobos tienen ventaja en ataques si un aliado está adyacente? → Sí (Táctica de Manada)",
                                "daño" => 14,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿Los lobos tienen ventaja en ataques si un aliado está adyacente? → No",
                                "daño" => 3,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                            [
                                "texto" => "Lanzas Toque Helado",
                                "daño" => 9,
                                "es_trivia" => false,
                            ],
                        ],
                    ],
                    [
                        "pregunta" =>
                            "El lobo rodea intentando derribarte. ¿Cómo reaccionas?",
                        "opciones" => [
                            [
                                "texto" => "Te apartas y contraatacas",
                                "daño" => 10,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "¿La percepción pasiva típica de un lobo es 13? → Sí",
                                "daño" => 15,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿La percepción pasiva típica de un lobo es 13? → No, es 8",
                                "daño" => 2,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                            [
                                "texto" => "Disparas una flecha",
                                "daño" => 12,
                                "es_trivia" => false,
                            ],
                        ],
                    ],
                    [
                        "pregunta" =>
                            "El lobo, malherido, aúlla desesperado. ¿Último ataque?",
                        "opciones" => [
                            [
                                "texto" => "Golpe decisivo cuerpo a cuerpo",
                                "daño" => 14,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" => "Lanzas Descarga Eléctrica",
                                "daño" => 17,
                                "es_trivia" => false,
                            ],
                            [
                                "texto" =>
                                    "¿La velocidad de un lobo es de 40 pies? → Sí",
                                "daño" => 13,
                                "es_trivia" => true,
                                "correcta" => true,
                            ],
                            [
                                "texto" =>
                                    "¿La velocidad de un lobo es de 40 pies? → No, 25 pies",
                                "daño" => 4,
                                "es_trivia" => true,
                                "correcta" => false,
                            ],
                        ],
                    ],
                ],
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
