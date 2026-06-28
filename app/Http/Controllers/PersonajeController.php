<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personaje;
use App\Models\Raza;
use App\Models\Clase;

class PersonajeController extends Controller
{
    public function index()
    {
        // Obtiene todos los personajes, razas y clases de la base de datos
        $personajes = Personaje::with(['raza', 'clase'])->latest()->get();
        $razas = Raza::all();
        $clases = Clase::all();

        return view('personajes', compact('personajes', 'razas', 'clases'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nombre'       => 'required|string|max:255',
            'nivel'        => 'required|integer|min:1|max:20',
            'raza_id'      => 'required|exists:razas,id',
            'clase_id'     => 'required|exists:clases,id',
            'imagen_url'   => 'nullable|url|max:2048',
            'fuerza'       => 'required|integer|min:1|max:30',
            'destreza'     => 'required|integer|min:1|max:30',
            'constitucion' => 'required|integer|min:1|max:30',
            'inteligencia' => 'required|integer|min:1|max:30',
            'sabiduria'    => 'required|integer|min:1|max:30',
            'carisma'      => 'required|integer|min:1|max:30',
        ]);

        // Cambio clave: 'usuario_id' en lugar de 'user_id'
        // El '?? 1' asigna el usuario 1 temporalmente si no has iniciado sesión
        $validatedData['usuario_id'] = auth()->id() ?? 1;

        Personaje::create($validatedData);

        return redirect()->route('personajes.index');
    }

    public function show(Personaje $personaje)
{
    $personaje->load(['raza', 'clase']);
    
    // Cambiamos a 'personajes.personaje_detalle'
    return view('personajes.personaje_detalle', compact('personaje'));
}
}   