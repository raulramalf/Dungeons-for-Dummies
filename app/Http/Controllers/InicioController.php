<?php

namespace App\Http\Controllers;

use App\Models\Personaje;
use App\Models\Campana;

class InicioController extends Controller
{
    public function index()
    {
        $personajesDestacados = Personaje::with(['raza', 'clase'])
            ->latest()
            ->take(10)
            ->get();

        // La tabla campanas usa columna 'estado' (string), no boolean 'activa'
        $campanasActivas = Campana::with('dungeonMaster')
            ->where('estado', 'activa')
            ->latest()
            ->take(4)
            ->get();

        $datosCuriosos = [
            [
                'titulo' => 'El primer D&D',
                'texto'  => 'Se publicó en 1974, creado por Gary Gygax y Dave Arneson a partir del wargame Chainmail.',
            ],
            [
                'titulo' => 'El d20',
                'texto'  => 'Un 20 natural es un éxito crítico. ¡El dado más temido y deseado de la mesa!',
            ],
            [
                'titulo' => 'El Ojo de Vecna',
                'texto'  => 'Uno de los artefactos más poderosos. Para usarlo debes arrancar tu propio ojo... ¿te atreves?',
            ],
            [
                'titulo' => 'Drizzt Do\'Urden',
                'texto'  => 'El elfo oscuro más famoso del multiverso, aparecido por primera vez en la novela de 1988.',
            ],
            [
                'titulo' => '+500 conjuros',
                'texto'  => 'D&D 5ª edición tiene más de 500 conjuros distintos entre todas sus fuentes oficiales.',
            ],
            [
                'titulo' => 'Tiamat',
                'texto'  => 'La Reina de los Dragones Malvados tiene cinco cabezas, una por cada color cromático.',
            ],
        ];

        return view('inicio', compact('personajesDestacados', 'campanasActivas', 'datosCuriosos'));
    }
}