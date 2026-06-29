<?php

namespace App\Http\Controllers;

use App\Models\Personaje;
use App\Models\Campana;
use App\Models\Post;

class InicioController extends Controller
{
    public function index()
    {
        // Últimas hazañas publicadas en la Taberna
        $ultimasHazanas = Post::with(['usuario', 'likes'])
            ->withCount('likes')
            ->latest()
            ->take(5)
            ->get();

        // Campañas activas
        $campanasActivas = Campana::with('dungeonMaster')
            ->where('estado', 'activa')
            ->latest()
            ->take(4)
            ->get();

        $datosCuriosos = [
            [
                'titulo' => 'Un dado, todo un destino',
                'texto'  => 'Gary Gygax diseñó el d20 en 1974 y sin querer inventó el objeto más odiado y amado de cualquier mesa. Un 1 natural todavía duele más que un dragón.',
            ],
            [
                'titulo' => 'El Ojo de Vecna',
                'texto'  => 'Para usar este artefacto tienes que arrancarte el ojo y meter el de Vecna en la cuenca. Lo que nadie te cuenta es lo que ves después... ni siquiera el DM lo sabe seguro.',
            ],
            [
                'titulo' => 'Drizzt, el elfo que rompió moldes',
                'texto'  => 'Apareció en 1988 como secundario de un libro de Forgotten Realms y terminó siendo tan popular que su autor lleva décadas escribiendo solo sobre él. Ahí lo tienes.',
            ],
            [
                'titulo' => 'La regla del "sí, y..."',
                'texto'  => 'Los mejores DM no dicen "no". Dicen "sí, y además..." y de ahí salen las sesiones que se recuerdan veinte años después alrededor de una cerveza.',
            ],
            [
                'titulo' => 'Tiamat tiene cinco malas noticias',
                'texto'  => 'Una cabeza de cada color cromático: roja, azul, negra, blanca y verde. No elijas un favorito. Todas quieren verte muerto por razones distintas.',
            ],
            [
                'titulo' => '+500 conjuros en 5ª Ed.',
                'texto'  => 'Y aun así el mago de tu grupo siempre lanza Bola de Fuego. Sin falta. En cada sesión. Aunque el dungeon sea de madera.',
            ],
        ];

        return view('inicio', compact('ultimasHazanas', 'campanasActivas', 'datosCuriosos'));
    }
}