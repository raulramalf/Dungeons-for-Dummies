<?php

namespace Database\Seeders;

use App\Models\MinijuegoPregunta;
use Illuminate\Database\Seeder;

class MinijuegoPreguntasSeeder extends Seeder
{
    public function run(): void
    {
        $preguntas = [
            [
                "pregunta" => "¿Cuántos lados tiene el dado que más se usa en D&D, el d20?",
                "opciones" => [
                    ["texto" => "20", "correcta" => true],
                    ["texto" => "12", "correcta" => false],
                    ["texto" => "10", "correcta" => false],
                    ["texto" => "100", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Cómo se llama el manual que contiene las reglas básicas para crear personajes?",
                "opciones" => [
                    ["texto" => "Manual del Jugador", "correcta" => true],
                    ["texto" => "Manual de Personaje", "correcta" => false],
                    ["texto" => "Guía del Dungeon Master", "correcta" => false],
                    ["texto" => "Xanathar's Guide", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Cuál es el máximo nivel de personaje en D&D 5e?",
                "opciones" => [
                    ["texto" => "20", "correcta" => true],
                    ["texto" => "10", "correcta" => false],
                    ["texto" => "25, solo de manera excepcional si se cumplen ciertos requisitos", "correcta" => false],
                    ["texto" => "15", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Qué característica determina cuántos puntos de golpe extra gana un personaje al subir de nivel (además del dado de golpe)?",
                "opciones" => [
                    ["texto" => "Constitución", "correcta" => true],
                    ["texto" => "Fuerza", "correcta" => false],
                    ["texto" => "Destreza", "correcta" => false],
                    ["texto" => "Sabiduría", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Cómo se llama la tirada que determina el orden de actuación en combate?",
                "opciones" => [
                    ["texto" => "Iniciativa", "correcta" => true],
                    ["texto" => "Percepción", "correcta" => false],
                    ["texto" => "Reflejos", "correcta" => false],
                    ["texto" => "Sigilo", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "En una tirada de ataque, ¿qué significa sacar un 20 natural en el d20?",
                "opciones" => [
                    ["texto" => "Golpe crítico (impacto automático y daño extra)", "correcta" => true],
                    ["texto" => "Pífia (fallo automático)", "correcta" => false],
                    ["texto" => "No pasa nada especial", "correcta" => false],
                    ["texto" => "Se repite la tirada", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Qué característica suele usar un Mago como aptitud mágica principal?",
                "opciones" => [
                    ["texto" => "Inteligencia", "correcta" => true],
                    ["texto" => "Carisma", "correcta" => false],
                    ["texto" => "Sabiduría", "correcta" => false],
                    ["texto" => "Fuerza", "correcta" => false],
                ],
                "categoria" => "clases",
            ],
            [
                "pregunta" => "¿Qué característica suele usar un Clérigo como aptitud mágica principal?",
                "opciones" => [
                    ["texto" => "Sabiduría", "correcta" => true],
                    ["texto" => "Inteligencia", "correcta" => false],
                    ["texto" => "Carisma", "correcta" => false],
                    ["texto" => "Constitución", "correcta" => false],
                ],
                "categoria" => "clases",
            ],
            [
                "pregunta" => "¿Qué característica suele usar un Hechicero o un Brujo como aptitud mágica principal?",
                "opciones" => [
                    ["texto" => "Carisma", "correcta" => true],
                    ["texto" => "Inteligencia", "correcta" => false],
                    ["texto" => "Sabiduría", "correcta" => false],
                    ["texto" => "Destreza", "correcta" => false],
                ],
                "categoria" => "clases",
            ],
            [
                "pregunta" => "¿Cómo se llama la clase especializada en lanzar conjuros mientras lucha cuerpo a cuerpo con armadura pesada?",
                "opciones" => [
                    ["texto" => "Paladín", "correcta" => true],
                    ["texto" => "Clérigo", "correcta" => false],
                    ["texto" => "Bárbaro", "correcta" => false],
                    ["texto" => "Pícaro", "correcta" => false],
                ],
                "categoria" => "clases",
            ],
            [
                "pregunta" => "¿Qué clase entra en 'Furia' para ganar resistencia a cierto daño y bonificación al daño cuerpo a cuerpo?",
                "opciones" => [
                    ["texto" => "Bárbaro", "correcta" => true],
                    ["texto" => "Monje", "correcta" => false],
                    ["texto" => "Druida", "correcta" => false],
                    ["texto" => "Guerrero", "correcta" => false],
                ],
                "categoria" => "clases",
            ],
            [
                "pregunta" => "¿Qué es la 'Clase de Armadura' (CA) de un personaje o criatura?",
                "opciones" => [
                    ["texto" => "Lo difícil que es impactarle en combate", "correcta" => true],
                    ["texto" => "Cuánto daño puede aguantar antes de morir", "correcta" => false],
                    ["texto" => "La velocidad a la que se mueve", "correcta" => false],
                    ["texto" => "El número de conjuros que puede lanzar", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Qué determina si un personaje tiene 'ventaja' en una tirada?",
                "opciones" => [
                    ["texto" => "Se tiran dos d20 y se queda con el resultado más alto", "correcta" => true],
                    ["texto" => "Se suma +5 automáticamente", "correcta" => false],
                    ["texto" => "Se tira un d20 y un d4 extra", "correcta" => false],
                    ["texto" => "Se repite la tirada si falla", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Qué pasa cuando un personaje tira con 'desventaja'?",
                "opciones" => [
                    ["texto" => "Se tiran dos d20 y se queda con el resultado más bajo", "correcta" => true],
                    ["texto" => "Se resta 5 al resultado final", "correcta" => false],
                    ["texto" => "No puede volver a tirar en ese turno", "correcta" => false],
                    ["texto" => "Se tira con un dado de menos caras", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Cuántas tiradas de salvación contra la muerte necesitas fallar para morir (si tus puntos de golpe llegan a 0)?",
                "opciones" => [
                    ["texto" => "3", "correcta" => true],
                    ["texto" => "1", "correcta" => false],
                    ["texto" => "5", "correcta" => false],
                    ["texto" => "10", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Cómo se llama el equipo de dados estándar que se usa en D&D (además del d20)?",
                "opciones" => [
                    ["texto" => "d4, d6, d8, d10, d12", "correcta" => true],
                    ["texto" => "d3, d9, d15", "correcta" => false],
                    ["texto" => "Solo dados de 6 caras, como en el parchís", "correcta" => false],
                    ["texto" => "d20 y d100 únicamente", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Cuántas veces se puede lanzar un truco al día?",
                "opciones" => [
                    ["texto" => "Las que quieras, no consume espacios de conjuro", "correcta" => true],
                    ["texto" => "Una vez por descanso corto", "correcta" => false],
                    ["texto" => "Tres veces por descanso largo", "correcta" => false],
                    ["texto" => "Solo una vez al día en total", "correcta" => false],
                ],
                "categoria" => "magia",
            ],
            [
                "pregunta" => "¿Qué significa que un conjuro requiera 'concentración'?",
                "opciones" => [
                    ["texto" => "Se cancela si el lanzador sufre daño y falla una salvación de Constitución, o si lanza otro conjuro de concentración", "correcta" => true],
                    ["texto" => "Solo se puede lanzar de noche", "correcta" => false],
                    ["texto" => "Necesita que dos lanzadores lo lancen a la vez", "correcta" => false],
                    ["texto" => "Tarda 10 minutos en lanzarse", "correcta" => false],
                ],
                "categoria" => "magia",
            ],
            [
                "pregunta" => "¿Quién suele encarnar a los monstruos y narrar el mundo en una partida de D&D?",
                "opciones" => [
                    ["texto" => "El Dungeon Master (DM)", "correcta" => true],
                    ["texto" => "El jugador con más experiencia", "correcta" => false],
                    ["texto" => "Se decide por votación cada sesión", "correcta" => false],
                    ["texto" => "Un dado especial llamado 'dado narrador'", "correcta" => false],
                ],
                "categoria" => "lore",
            ],
            [
                "pregunta" => "¿Cómo se llama el mundo de campaña oficial más asociado a los Reinos Olvidados?",
                "opciones" => [
                    ["texto" => "Faerûn", "correcta" => true],
                    ["texto" => "Eberron", "correcta" => false],
                    ["texto" => "Ravenloft", "correcta" => false],
                    ["texto" => "Krynn", "correcta" => false],
                ],
                "categoria" => "lore",
            ],
            [
                "pregunta" => "¿Qué característica mide la fuerza física de un personaje y su capacidad de cargar peso?",
                "opciones" => [
                    ["texto" => "Fuerza", "correcta" => true],
                    ["texto" => "Constitución", "correcta" => false],
                    ["texto" => "Destreza", "correcta" => false],
                    ["texto" => "Sabiduría", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Qué característica afecta a la iniciativa, la Clase de Armadura (con armadura ligera) y la habilidad de Sigilo?",
                "opciones" => [
                    ["texto" => "Destreza", "correcta" => true],
                    ["texto" => "Carisma", "correcta" => false],
                    ["texto" => "Inteligencia", "correcta" => false],
                    ["texto" => "Fuerza", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Qué es un 'dado de golpe'?",
                "opciones" => [
                    ["texto" => "El dado que se tira para recuperar puntos de golpe en un descanso corto, y que suma PG al subir de nivel", "correcta" => true],
                    ["texto" => "El dado con el que se tiran los ataques físicos", "correcta" => false],
                    ["texto" => "Un dado que solo usan los monstruos", "correcta" => false],
                    ["texto" => "El dado de iniciativa", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "Un personaje con Destreza 8 tira su salvación de Destreza. ¿Qué modificador aplica a la tirada?",
                "opciones" => [
                    ["texto" => "-1", "correcta" => true],
                    ["texto" => "+1", "correcta" => false],
                    ["texto" => "0", "correcta" => false],
                    ["texto" => "-4", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Qué es el 'Bono de Competencia'?",
                "opciones" => [
                    ["texto" => "Un bonificador que sube con el nivel y se aplica a habilidades, salvaciones y ataques en los que el personaje es competente", "correcta" => true],
                    ["texto" => "Un objeto mágico que da +1 a todo", "correcta" => false],
                    ["texto" => "El daño extra por golpe crítico", "correcta" => false],
                    ["texto" => "La cantidad de oro inicial de un personaje", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "Una criatura 'Paralizada' sufre varios efectos graves. ¿Cuál de estos NO es uno de ellos?",
                "opciones" => [
                    ["texto" => "Puede seguir moviéndose con normalidad", "correcta" => true],
                    ["texto" => "Falla automáticamente las salvaciones de Fuerza y Destreza", "correcta" => false],
                    ["texto" => "Los ataques cuerpo a cuerpo contra ella son críticos automáticos si impactan", "correcta" => false],
                    ["texto" => "No puede realizar acciones ni reacciones", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "Si un personaje está 'Envenenado', ¿qué desventaja mecánica sufre?",
                "opciones" => [
                    ["texto" => "Desventaja en tiradas de ataque y en pruebas de característica", "correcta" => true],
                    ["texto" => "Pierde 1 punto de golpe máximo cada turno", "correcta" => false],
                    ["texto" => "No puede hablar ni lanzar conjuros con componente verbal", "correcta" => false],
                    ["texto" => "Se vuelve invisible para los demás", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "¿Qué le impide hacer a una criatura estar 'Hechizada'?",
                "opciones" => [
                    ["texto" => "Atacar a quien la hechizó o dirigirle capacidades dañinas", "correcta" => true],
                    ["texto" => "Moverse de ningún modo", "correcta" => false],
                    ["texto" => "Ver ni oír nada de su entorno", "correcta" => false],
                    ["texto" => "Lanzar cualquier tipo de conjuro", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "Una criatura 'Asustada' tiene desventaja en pruebas y ataques mientras la fuente del miedo esté a la vista, y además...",
                "opciones" => [
                    ["texto" => "No puede acercarse voluntariamente a la fuente del miedo", "correcta" => true],
                    ["texto" => "Pierde su turno completo", "correcta" => false],
                    ["texto" => "Queda automáticamente derribada", "correcta" => false],
                    ["texto" => "No puede recibir curación", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "¿Qué ventaja obtienen los ataques cuerpo a cuerpo contra una criatura 'Invisible'... o mejor dicho, qué desventaja sufren?",
                "opciones" => [
                    ["texto" => "Los ataques contra ella tienen desventaja, y los suyos propios tienen ventaja", "correcta" => true],
                    ["texto" => "No afecta en nada a las tiradas de ataque", "correcta" => false],
                    ["texto" => "La criatura invisible no puede atacar en absoluto", "correcta" => false],
                    ["texto" => "Los ataques contra ella son siempre fallos automáticos", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "Una criatura 'Inconsciente' que recibe un ataque cuerpo a cuerpo mientras está a 1,5 metros del atacante sufre...",
                "opciones" => [
                    ["texto" => "Un golpe crítico automático si el ataque impacta", "correcta" => true],
                    ["texto" => "Solo la mitad del daño", "correcta" => false],
                    ["texto" => "Ningún efecto especial adicional", "correcta" => false],
                    ["texto" => "Inmunidad total a ese ataque", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "¿Qué diferencia principal hay entre estar 'Agarrado' y estar 'Apresado'?",
                "opciones" => [
                    ["texto" => "Apresado también reduce la velocidad a 0 e impone desventaja en ataques, además de no poder moverse", "correcta" => true],
                    ["texto" => "No hay ninguna diferencia real entre ambas", "correcta" => false],
                    ["texto" => "Agarrado es mucho peor que Apresado", "correcta" => false],
                    ["texto" => "Apresado solo lo pueden causar los conjuros, nunca criaturas", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "Una criatura 'Aturdida' queda incapacitada, no puede moverse, y además...",
                "opciones" => [
                    ["texto" => "Falla automáticamente las salvaciones de Fuerza y Destreza", "correcta" => true],
                    ["texto" => "Recibe daño extra de fuego en cada turno", "correcta" => false],
                    ["texto" => "Se vuelve inmune a todo el daño", "correcta" => false],
                    ["texto" => "Puede seguir hablando con total normalidad", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "¿Qué significa que una criatura esté 'Incapacitada'?",
                "opciones" => [
                    ["texto" => "No puede realizar acciones ni reacciones, aunque sí podría desplazarse si nada más se lo impide", "correcta" => true],
                    ["texto" => "Está muerta permanentemente", "correcta" => false],
                    ["texto" => "Solo pierde la capacidad de hablar", "correcta" => false],
                    ["texto" => "Se vuelve inmune a la magia", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "¿A qué característica suele afectar principalmente la condición 'Ensordecido'?",
                "opciones" => [
                    ["texto" => "No puede oír, y falla automáticamente pruebas que dependan del oído", "correcta" => true],
                    ["texto" => "Reduce a la mitad los puntos de golpe máximos", "correcta" => false],
                    ["texto" => "Impide moverse por completo", "correcta" => false],
                    ["texto" => "Provoca desventaja en tiradas de salvación de Constitución", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "¿Cuánto tiempo dura habitualmente el efecto de la condición 'Exhausto' nivel 1, y cómo se elimina en general?",
                "opciones" => [
                    ["texto" => "Persiste hasta que se elimina con un descanso largo (según la regla estándar de niveles de agotamiento)", "correcta" => true],
                    ["texto" => "Desaparece automáticamente al final de cada combate", "correcta" => false],
                    ["texto" => "Dura exactamente 1 minuto siempre", "correcta" => false],
                    ["texto" => "No se puede eliminar de ninguna forma", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "Si el DM te dice que tu personaje está 'Aturdido' durante un ataque, ¿puedes hacer tu reacción de Ataque de Oportunidad?",
                "opciones" => [
                    ["texto" => "No, porque Aturdido impide realizar reacciones", "correcta" => true],
                    ["texto" => "Sí, sin ninguna restricción", "correcta" => false],
                    ["texto" => "Sí, pero con desventaja", "correcta" => false],
                    ["texto" => "Solo si tienes competencia en Percepción", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "Un Pícaro de nivel 5 con Destreza 16 (+3) ataca con Ataque Furtivo activo. ¿Cuántos dados de Ataque Furtivo añade aproximadamente a ese nivel?",
                "opciones" => [
                    ["texto" => "3d6", "correcta" => true],
                    ["texto" => "5d6", "correcta" => false],
                    ["texto" => "1d6", "correcta" => false],
                    ["texto" => "10d6", "correcta" => false],
                ],
                "categoria" => "clases",
            ],
            [
                "pregunta" => "¿Qué ocurre si dos criaturas ocupan el mismo espacio de forma temporal (por ejemplo, un aliado invisible pasa junto a un enemigo)? Como regla general en 5e...",
                "opciones" => [
                    ["texto" => "Una criatura puede mover a través del espacio de otra si es al menos dos tamaños más pequeña o más grande, pero no puede terminar su movimiento ahí", "correcta" => true],
                    ["texto" => "Nunca es posible bajo ninguna circunstancia", "correcta" => false],
                    ["texto" => "Solo lo permiten los conjuros de teletransporte", "correcta" => false],
                    ["texto" => "Ambas criaturas reciben daño automáticamente", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "En un combate, si fallas una tirada de salvación de Constitución para mantener la concentración tras recibir daño, ¿qué ocurre con el conjuro que estabas concentrando?",
                "opciones" => [
                    ["texto" => "Termina inmediatamente", "correcta" => true],
                    ["texto" => "Sigue activo pero con la mitad de efecto", "correcta" => false],
                    ["texto" => "Se puede reintentar la salvación en el siguiente turno", "correcta" => false],
                    ["texto" => "No pasa nada, la concentración no depende de esa salvación", "correcta" => false],
                ],
                "categoria" => "magia",
            ],
            [
                "pregunta" => "¿Qué significa que un rasgo o conjuro tenga alcance 'Toque'?",
                "opciones" => [
                    ["texto" => "Debes tocar físicamente al objetivo (o su equipo) para que el efecto se aplique", "correcta" => true],
                    ["texto" => "Solo funciona si el objetivo está a más de 30 metros", "correcta" => false],
                    ["texto" => "El efecto se aplica sin importar la distancia", "correcta" => false],
                    ["texto" => "Requiere que dos lanzadores lo canalicen juntos", "correcta" => false],
                ],
                "categoria" => "magia",
            ],
            [
                "pregunta" => "¿Cuál de estas razas NO suele tener Visión en la Oscuridad de forma innata en el SRD?",
                "opciones" => [
                    ["texto" => "Humano", "correcta" => true],
                    ["texto" => "Enano", "correcta" => false],
                    ["texto" => "Elfo", "correcta" => false],
                    ["texto" => "Semiorco", "correcta" => false],
                ],
                "categoria" => "razas",
            ],
            [
                "pregunta" => "¿Qué es 'Resistencia' al daño y cómo afecta a una criatura que la tiene contra el fuego, por ejemplo?",
                "opciones" => [
                    ["texto" => "Recibe la mitad del daño de fuego que le inflijan", "correcta" => true],
                    ["texto" => "Es completamente inmune al daño de fuego", "correcta" => false],
                    ["texto" => "Recibe el doble de daño de fuego", "correcta" => false],
                    ["texto" => "Solo afecta a conjuros, no a ataques con arma", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "¿Y qué diferencia hay entre 'Resistencia' y 'Vulnerabilidad' al daño?",
                "opciones" => [
                    ["texto" => "Resistencia reduce el daño a la mitad; Vulnerabilidad lo dobla", "correcta" => true],
                    ["texto" => "Son exactamente lo mismo con distinto nombre", "correcta" => false],
                    ["texto" => "Vulnerabilidad reduce el daño y Resistencia lo aumenta", "correcta" => false],
                    ["texto" => "Ambas anulan el daño por completo", "correcta" => false],
                ],
                "categoria" => "reglas",
            ],
            [
                "pregunta" => "Si un Mago de nivel 3 tiene 4 espacios de conjuro de nivel 1 y 2 de nivel 2, ¿puede lanzar un conjuro de nivel 1 usando un espacio de nivel 2?",
                "opciones" => [
                    ["texto" => "Sí, y muchos conjuros mejoran su efecto al lanzarse con un espacio superior", "correcta" => true],
                    ["texto" => "No, cada conjuro solo puede lanzarse con el espacio de su nivel exacto", "correcta" => false],
                    ["texto" => "Solo los Clérigos pueden hacer eso", "correcta" => false],
                    ["texto" => "Sí, pero el conjuro pierde efecto por completo", "correcta" => false],
                ],
                "categoria" => "magia",
            ],
            [
                "pregunta" => "¿Qué es un 'Familiar' invocado con el conjuro Encontrar Familiar?",
                "opciones" => [
                    ["texto" => "Un espíritu con forma de animal que ayuda al lanzador, pudiendo entregar hechizos con contacto a través de él", "correcta" => true],
                    ["texto" => "Un aliado humano contratado con oro", "correcta" => false],
                    ["texto" => "Un arma mágica que aparece en la mano del lanzador", "correcta" => false],
                    ["texto" => "Una versión débil del propio lanzador", "correcta" => false],
                ],
                "categoria" => "magia",
            ],
            [
                "pregunta" => "En términos de reglas, ¿qué tienen en común las condiciones 'Cegado' y 'Ensordecido' respecto a las tiradas que dependen de ese sentido?",
                "opciones" => [
                    ["texto" => "Ambas provocan fallo automático en pruebas que requieran el sentido perdido", "correcta" => true],
                    ["texto" => "Ninguna afecta a las tiradas, solo son narrativas", "correcta" => false],
                    ["texto" => "Solo Cegado afecta a las tiradas, Ensordecido no tiene ningún efecto mecánico", "correcta" => false],
                    ["texto" => "Provocan la muerte instantánea del personaje", "correcta" => false],
                ],
                "categoria" => "condiciones",
            ],
            [
                "pregunta" => "¿Qué le ocurre a un personaje que cae a 0 PG sin morir al instante?",
                "opciones" => [
                    ["texto" => "Está inconsciente y debe hacer salvaciones de muerte", "correcta" => true],
                    ["texto" => "Muere automáticamente", "correcta" => false],
                    ["texto" => "Se levanta con 1 PG", "correcta" => false],
                    ["texto" => "Pierde un nivel", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "media",
                "explicacion" => "Al llegar a 0 PG quedas inconsciente y haces salvaciones de muerte al inicio de cada turno: tres éxitos te estabilizan, tres fallos te matan.",
            ],
            [
                "pregunta" => "¿Qué obtienes al sacar un 20 natural en una tirada de ataque?",
                "opciones" => [
                    ["texto" => "Un golpe crítico", "correcta" => true],
                    ["texto" => "Un fallo automático", "correcta" => false],
                    ["texto" => "Ventaja en el siguiente ataque", "correcta" => false],
                    ["texto" => "Daño máximo sin tirar", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "facil",
                "explicacion" => "Un 20 natural es un impacto crítico: tiras los dados de daño dos veces.",
            ],
            [
                "pregunta" => "¿Cuánto dura un descanso largo?",
                "opciones" => [
                    ["texto" => "Al menos 8 horas", "correcta" => true],
                    ["texto" => "1 hora", "correcta" => false],
                    ["texto" => "4 horas", "correcta" => false],
                    ["texto" => "24 horas", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "facil",
                "explicacion" => "Un descanso largo requiere 8 horas, de las cuales al menos 6 durmiendo; recuperas todos los PG y la mitad de tus dados de golpe.",
            ],
            [
                "pregunta" => "¿Qué significa tener ventaja en una tirada?",
                "opciones" => [
                    ["texto" => "Tiras dos d20 y te quedas con el más alto", "correcta" => true],
                    ["texto" => "Sumas +2 al resultado", "correcta" => false],
                    ["texto" => "Tiras dos d20 y los sumas", "correcta" => false],
                    ["texto" => "Repites la tirada si fallas", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "facil",
                "explicacion" => "Con ventaja tiras 2d20 y usas el mayor; con desventaja, el menor.",
            ],
            [
                "pregunta" => "¿Qué característica usa normalmente un mago para lanzar conjuros?",
                "opciones" => [
                    ["texto" => "Inteligencia", "correcta" => true],
                    ["texto" => "Sabiduría", "correcta" => false],
                    ["texto" => "Carisma", "correcta" => false],
                    ["texto" => "Constitución", "correcta" => false],
                ],
                "categoria" => "clases",
                "dificultad" => "facil",
                "explicacion" => "El mago usa Inteligencia como característica de lanzamiento; el clérigo y el druida usan Sabiduría, y el bardo y el hechicero Carisma.",
            ],
            [
                "pregunta" => "¿Qué clase puede usar Furia?",
                "opciones" => [
                    ["texto" => "Bárbaro", "correcta" => true],
                    ["texto" => "Guerrero", "correcta" => false],
                    ["texto" => "Paladín", "correcta" => false],
                    ["texto" => "Monje", "correcta" => false],
                ],
                "categoria" => "clases",
                "dificultad" => "facil",
                "explicacion" => "La Furia es el rasgo distintivo del bárbaro: resistencia al daño físico y daño extra cuerpo a cuerpo.",
            ],
            [
                "pregunta" => "¿Cuántos conjuros de nivel 1 puede lanzar un mago de nivel 1 antes de descansar (hueco de conjuro)?",
                "opciones" => [
                    ["texto" => "2", "correcta" => true],
                    ["texto" => "1", "correcta" => false],
                    ["texto" => "4", "correcta" => false],
                    ["texto" => "Ilimitados", "correcta" => false],
                ],
                "categoria" => "magia",
                "dificultad" => "media",
                "explicacion" => "Un mago de nivel 1 dispone de 2 huecos de conjuro de nivel 1 que recupera con un descanso largo.",
            ],
            [
                "pregunta" => "¿Qué conjuro es un truco (nivel 0) de la lista del mago?",
                "opciones" => [
                    ["texto" => "Rayo de fuego", "correcta" => true],
                    ["texto" => "Bola de fuego", "correcta" => false],
                    ["texto" => "Misil mágico", "correcta" => false],
                    ["texto" => "Escudo", "correcta" => false],
                ],
                "categoria" => "magia",
                "dificultad" => "media",
                "explicacion" => "Rayo de fuego es un truco: se puede lanzar a voluntad sin gastar huecos de conjuro.",
            ],
            [
                "pregunta" => "¿Qué condición impide realizar acciones y reacciones?",
                "opciones" => [
                    ["texto" => "Aturdido", "correcta" => true],
                    ["texto" => "Asustado", "correcta" => false],
                    ["texto" => "Envenenado", "correcta" => false],
                    ["texto" => "Derribado", "correcta" => false],
                ],
                "categoria" => "condiciones",
                "dificultad" => "media",
                "explicacion" => "Aturdido incluye la condición incapacitado: no puedes actuar ni reaccionar. Asustado, envenenado o derribado te penalizan, pero no te quitan las acciones.",
            ],
            [
                "pregunta" => "¿Qué efecto principal tiene la condición Envenenado?",
                "opciones" => [
                    ["texto" => "Desventaja en tiradas de ataque y pruebas de característica", "correcta" => true],
                    ["texto" => "No puedes moverte", "correcta" => false],
                    ["texto" => "Pierdes tu reacción", "correcta" => false],
                    ["texto" => "Recibes daño al inicio de cada turno", "correcta" => false],
                ],
                "categoria" => "condiciones",
                "dificultad" => "media",
                "explicacion" => "Envenenado impone desventaja en tiradas de ataque y pruebas de característica, pero no inmoviliza.",
            ],
            [
                "pregunta" => "¿Qué valor tiene la Clase de Armadura base de alguien sin armadura?",
                "opciones" => [
                    ["texto" => "10 + modificador de Destreza", "correcta" => true],
                    ["texto" => "8 + modificador de Fuerza", "correcta" => false],
                    ["texto" => "12", "correcta" => false],
                    ["texto" => "15", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "media",
                "explicacion" => "Sin armadura la CA es 10 más tu modificador de Destreza.",
            ],
            [
                "pregunta" => "¿Cuál es el bonus de competencia de un personaje de nivel 1?",
                "opciones" => [
                    ["texto" => "+2", "correcta" => true],
                    ["texto" => "+1", "correcta" => false],
                    ["texto" => "+3", "correcta" => false],
                    ["texto" => "+4", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "facil",
                "explicacion" => "El bonus de competencia empieza en +2 y sube a +3 en el nivel 5, +4 en el 9, +5 en el 13 y +6 en el 17.",
            ],
            [
                "pregunta" => "¿Qué dado de golpe usa un mago?",
                "opciones" => [
                    ["texto" => "d6", "correcta" => true],
                    ["texto" => "d8", "correcta" => false],
                    ["texto" => "d10", "correcta" => false],
                    ["texto" => "d12", "correcta" => false],
                ],
                "categoria" => "clases",
                "dificultad" => "dificil",
                "explicacion" => "El mago usa d6 como dado de golpe; el bárbaro d12; el guerrero y el paladín d10; el clérigo y el pícaro d8.",
            ],
            [
                "pregunta" => "¿Qué tipo de criatura es normalmente un liche?",
                "opciones" => [
                    ["texto" => "No-muerto", "correcta" => true],
                    ["texto" => "Constructo", "correcta" => false],
                    ["texto" => "Aberración", "correcta" => false],
                    ["texto" => "Dragón", "correcta" => false],
                ],
                "categoria" => "lore",
                "dificultad" => "dificil",
                "explicacion" => "Los liches son magos no-muertos que ligaron su alma a un filacteria para evitar la muerte definitiva.",
            ],
            [
                "pregunta" => "¿Qué criatura se disfraza de cofre del tesoro para atrapar a sus víctimas?",
                "opciones" => [
                    ["texto" => "Mímico", "correcta" => true],
                    ["texto" => "Gelatina ocre", "correcta" => false],
                    ["texto" => "Gárgola", "correcta" => false],
                    ["texto" => "Doppelganger", "correcta" => false],
                ],
                "categoria" => "lore",
                "dificultad" => "facil",
                "explicacion" => "El mímico es un monstruoso cambiaformas que imita objetos, normalmente cofres o puertas, y sujeta con su pseudópodo adhesivo.",
            ],
            [
                "pregunta" => "¿Qué debilidad tienen clásicamente los trolls?",
                "opciones" => [
                    ["texto" => "El fuego y el ácido", "correcta" => true],
                    ["texto" => "El frío", "correcta" => false],
                    ["texto" => "El daño contundente", "correcta" => false],
                    ["texto" => "La luz solar", "correcta" => false],
                ],
                "categoria" => "lore",
                "dificultad" => "dificil",
                "explicacion" => "Los trolls regeneran PG cada turno, pero no si reciben daño de fuego o ácido.",
            ],
            [
                "pregunta" => "¿Cuántas salvaciones de muerte fallidas matan a un personaje?",
                "opciones" => [
                    ["texto" => "3", "correcta" => true],
                    ["texto" => "1", "correcta" => false],
                    ["texto" => "5", "correcta" => false],
                    ["texto" => "2", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "media",
                "explicacion" => "Tres fallos (acumulables, no seguidos) y mueres; tres éxitos y te estabilizas. Un 20 natural te devuelve 1 PG.",
            ],
            [
                "pregunta" => "¿Qué mide la característica Sabiduría?",
                "opciones" => [
                    ["texto" => "Percepción e intuición", "correcta" => true],
                    ["texto" => "Labia y encanto", "correcta" => false],
                    ["texto" => "Agilidad", "correcta" => false],
                    ["texto" => "Memoria", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "media",
                "explicacion" => "Sabiduría refleja percepción, intuición y entendimiento del mundo; Carisma mide la personalidad y el encanto.",
            ],
            [
                "pregunta" => "¿Cuántas acciones tienes normalmente en tu turno?",
                "opciones" => [
                    ["texto" => "Una acción, una acción adicional y movimiento", "correcta" => true],
                    ["texto" => "Tres acciones", "correcta" => false],
                    ["texto" => "Dos acciones", "correcta" => false],
                    ["texto" => "Una acción solo", "correcta" => false],
                ],
                "categoria" => "reglas",
                "dificultad" => "facil",
                "explicacion" => "En tu turno tienes movimiento, una acción y, si algo te lo da, una acción adicional; además una reacción por ronda.",
            ],
        ];

        foreach ($preguntas as $p) {
            MinijuegoPregunta::updateOrCreate(
                ["pregunta" => $p["pregunta"]],
                $p,
            );
        }
    }
}