<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personaje;
use App\Models\Raza;
use App\Models\Clase;
use App\Models\Estadistica;

class PersonajeController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        
        $personajes = Personaje::with(['raza', 'clase'])
            ->where('usuario_id', $userId)
            ->latest()
            ->get();

        return view('personajes', compact('personajes'));
    }

    public function create()
    {
        $razas = Raza::all();
        $clases = Clase::all();
        
        return view('personajes_crear', compact('razas', 'clases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'nivel' => 'required|integer|min:1|max:20',
            'raza_id' => 'required|exists:razas,id',
            'clase_id' => 'required|exists:clases,id',
            'avatar' => 'nullable|url|max:255',
            'historia' => 'nullable|string',
            'fuerza' => 'required|integer|min:1|max:30',
            'destreza' => 'required|integer|min:1|max:30',
            'constitucion' => 'required|integer|min:1|max:30',
            'inteligencia' => 'required|integer|min:1|max:30',
            'sabiduria' => 'required|integer|min:1|max:30',
            'carisma' => 'required|integer|min:1|max:30',
        ]);

        $userId = auth()->id() ?? 1;

        $personaje = Personaje::create([
            'usuario_id' => $userId,
            'nombre' => $validated['nombre'],
            'nivel' => $validated['nivel'],
            'raza_id' => $validated['raza_id'],
            'clase_id' => $validated['clase_id'],
            'avatar' => $validated['avatar'] ?? null,
            'historia' => $validated['historia'] ?? null,
        ]);

        Estadistica::create([
            'personaje_id' => $personaje->id,
            'fuerza' => $validated['fuerza'],
            'destreza' => $validated['destreza'],
            'constitucion' => $validated['constitucion'],
            'inteligencia' => $validated['inteligencia'],
            'sabiduria' => $validated['sabiduria'],
            'carisma' => $validated['carisma'],
            'pg_maximos' => 10,
            'pg_actuales' => 10,
            'clase_de_armadura' => 10,
            'velocidad' => 30,
            'bonus_competencia' => 2,
        ]);

        return redirect()->route('personajes.index')
            ->with('success', '¡Personaje creado exitosamente!');
    }

    public function show(Personaje $personaje)
    {
        $personaje->load(['raza', 'clase', 'estadisticas']);
        return view('personaje_individual', compact('personaje'));
    }

    public function edit(Personaje $personaje)
    {
        $razas = Raza::all();
        $clases = Clase::all();
        return view('personajes_editar', compact('personaje', 'razas', 'clases'));
    }

    public function update(Request $request, Personaje $personaje)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'nivel' => 'required|integer|min:1|max:20',
        'raza_id' => 'required|exists:razas,id',
        'clase_id' => 'required|exists:clases,id',
        'avatar' => 'nullable|url|max:255',
        'historia' => 'nullable|string',
        'alineamiento' => 'nullable|string|max:50',
        // Estadísticas
        'fuerza' => 'required|integer|min:1|max:30',
        'destreza' => 'required|integer|min:1|max:30',
        'constitucion' => 'required|integer|min:1|max:30',
        'inteligencia' => 'required|integer|min:1|max:30',
        'sabiduria' => 'required|integer|min:1|max:30',
        'carisma' => 'required|integer|min:1|max:30',
        // Combate
        'pg_actuales' => 'nullable|integer|min:0',
        'pg_maximos' => 'nullable|integer|min:1',
        'clase_de_armadura' => 'nullable|integer|min:0',
        'velocidad' => 'nullable|integer|min:0',
        'bonus_competencia' => 'nullable|integer|min:0|max:6',
        'iniciativa' => 'nullable|integer|min:0|max:20',
    ]);

    // Actualizar datos básicos del personaje
    $personaje->update($validated);

    // Actualizar o crear estadísticas
    $estadisticas = $personaje->estadisticas;
    if ($estadisticas) {
        $estadisticas->update([
            'fuerza' => $validated['fuerza'],
            'destreza' => $validated['destreza'],
            'constitucion' => $validated['constitucion'],
            'inteligencia' => $validated['inteligencia'],
            'sabiduria' => $validated['sabiduria'],
            'carisma' => $validated['carisma'],
            'pg_actuales' => $validated['pg_actuales'] ?? $estadisticas->pg_actuales,
            'pg_maximos' => $validated['pg_maximos'] ?? $estadisticas->pg_maximos,
            'clase_de_armadura' => $validated['clase_de_armadura'] ?? $estadisticas->clase_de_armadura,
            'velocidad' => $validated['velocidad'] ?? $estadisticas->velocidad,
            'bonus_competencia' => $validated['bonus_competencia'] ?? $estadisticas->bonus_competencia,
            'iniciativa' => $validated['iniciativa'] ?? $estadisticas->iniciativa,
        ]);
    } else {
        $personaje->estadisticas()->create([
            'fuerza' => $validated['fuerza'],
            'destreza' => $validated['destreza'],
            'constitucion' => $validated['constitucion'],
            'inteligencia' => $validated['inteligencia'],
            'sabiduria' => $validated['sabiduria'],
            'carisma' => $validated['carisma'],
            'pg_actuales' => $validated['pg_actuales'] ?? 10,
            'pg_maximos' => $validated['pg_maximos'] ?? 10,
            'clase_de_armadura' => $validated['clase_de_armadura'] ?? 10,
            'velocidad' => $validated['velocidad'] ?? 30,
            'bonus_competencia' => $validated['bonus_competencia'] ?? 2,
            'iniciativa' => $validated['iniciativa'] ?? 0,
        ]);
    }

    return redirect()->route('personajes.show', $personaje)
        ->with('success', '¡Personaje actualizado correctamente!');
}

    public function destroy(Personaje $personaje)
    {
        $personaje->delete();
        
        return redirect()->route('personajes.index')
            ->with('success', 'Personaje eliminado correctamente.');
    }
    public function actualizarMonedas(Request $request, Personaje $personaje)
{
    $request->validate([
        'monedas_cobre' => 'nullable|integer|min:0',
        'monedas_plata' => 'nullable|integer|min:0',
        'monedas_electrum' => 'nullable|integer|min:0',
        'monedas_oro' => 'nullable|integer|min:0',
        'monedas_platino' => 'nullable|integer|min:0',
    ]);

    $estadisticas = $personaje->estadisticas;
    if ($estadisticas) {
        $estadisticas->update($request->only([
            'monedas_cobre', 'monedas_plata', 'monedas_electrum',
            'monedas_oro', 'monedas_platino'
        ]));
    } else {
        // Si no tiene estadísticas, las crea con valores por defecto
        $personaje->estadisticas()->create(
            $request->only([
                'monedas_cobre', 'monedas_plata', 'monedas_electrum',
                'monedas_oro', 'monedas_platino'
            ]) + [
                'pg_maximos' => 10,
                'pg_actuales' => 10,
                'clase_de_armadura' => 10,
                'velocidad' => 30,
                'bonus_competencia' => 2,
            ]
        );
    }

    return redirect()->route('personajes.show', $personaje)
        ->with('success', 'Monedas actualizadas correctamente.');
}
}