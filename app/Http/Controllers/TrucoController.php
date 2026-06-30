<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personaje;
use App\Models\Truco;
use App\Models\Conjuro;

class TrucoController extends Controller
{
    public function store(Request $request, Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'conjuro_id' => 'required|exists:conjuros,id',
        ]);

        // Evitar duplicados: si el personaje ya tiene ese conjuro, no lo añadimos de nuevo
        $yaLoTiene = $personaje->trucos()->where('conjuro_id', $request->conjuro_id)->exists();
        if ($yaLoTiene) {
            return redirect()->route('personajes.edit', $personaje)
                ->with('success', 'Ese conjuro ya estaba en la lista del personaje.');
        }

        $conjuro = Conjuro::findOrFail($request->conjuro_id);

        $personaje->trucos()->create([
            'conjuro_id' => $conjuro->id,
            'nombre'     => $conjuro->nombre,
        ]);

        return redirect()->route('personajes.edit', $personaje)
            ->with('success', 'Conjuro añadido al personaje.');
    }

    public function destroy(Personaje $personaje, Truco $truco)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $truco->delete();

        return redirect()->route('personajes.edit', $personaje)
            ->with('success', 'Conjuro eliminado.');
    }
}