<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personaje;
use App\Models\Equipo;

class EquipoController extends Controller
{
    public function store(Request $request, Personaje $personaje)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'rareza' => 'nullable|string|max:50',
            'magico' => 'boolean',
            'equipado' => 'boolean',
            'cantidad' => 'integer|min:1',
            'peso' => 'nullable|numeric',
            'valor_po' => 'nullable|integer',
            'propiedades' => 'nullable|json',
        ]);

        $personaje->equipo()->create([
            'nombre' => $request->nombre,
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'rareza' => $request->rareza,
            'magico' => $request->has('magico'),
            'equipado' => $request->has('equipado'),
            'cantidad' => $request->cantidad ?? 1,
            'peso' => $request->peso,
            'valor_po' => $request->valor_po,
            'propiedades' => $request->propiedades,
        ]);

        return redirect()->route('personajes.show', $personaje)
            ->with('success', 'Equipo añadido correctamente.');
    }

    public function destroy(Personaje $personaje, Equipo $equipo)
    {
        $equipo->delete();
        return redirect()->route('personajes.show', $personaje)
            ->with('success', 'Equipo eliminado.');
    }
}