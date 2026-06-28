<?php

namespace App\Http\Controllers;

use App\Models\Enemigo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnemigoController extends Controller
{
    public function index()
    {
        $enemigos = Enemigo::where('usuario_id', Auth::id())->orderBy('nombre')->get();
        return view('enemigos', compact('enemigos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'              => ['required', 'string', 'max:255'],
            'tipo'                => ['required', 'string', 'max:255'],
            'tamaño'              => ['required', 'string', 'max:255'],
            'clase_de_desafio'    => ['required', 'numeric'],
            'puntos_de_experiencia' => ['required', 'integer'],
            'clase_de_armadura'   => ['required', 'integer'],
            'puntos_de_golpe'     => ['required', 'string'],
            'velocidad'           => ['required', 'string'],
            'fuerza'              => ['required', 'integer'],
            'destreza'            => ['required', 'integer'],
            'constitucion'        => ['required', 'integer'],
            'inteligencia'        => ['required', 'integer'],
            'sabiduria'           => ['required', 'integer'],
            'carisma'             => ['required', 'integer'],
        ]);

        Enemigo::create([
            'usuario_id'            => Auth::id(),
            'nombre'                => $request->nombre,
            'descripcion'           => $request->descripcion,
            'tipo'                  => $request->tipo,
            'tamaño'                => $request->tamaño,
            'alineamiento'          => $request->alineamiento,
            'clase_de_desafio'      => $request->clase_de_desafio,
            'puntos_de_experiencia' => $request->puntos_de_experiencia,
            'clase_de_armadura'     => $request->clase_de_armadura,
            'tipo_armadura'         => $request->tipo_armadura,
            'puntos_de_golpe'       => $request->puntos_de_golpe,
            'velocidad'             => $request->velocidad,
            'velocidades_especiales' => $request->velocidades_especiales,
            'fuerza'                => $request->fuerza,
            'destreza'              => $request->destreza,
            'constitucion'          => $request->constitucion,
            'inteligencia'          => $request->inteligencia,
            'sabiduria'             => $request->sabiduria,
            'carisma'               => $request->carisma,
            'tiradas_salvacion'     => $request->tiradas_salvacion,
            'competencias'          => $request->competencias,
            'resistencias'          => $request->resistencias,
            'inmunidades_daño'      => $request->inmunidades_daño,
            'vulnerabilidades'      => $request->vulnerabilidades,
            'inmunidades_condicion' => $request->inmunidades_condicion,
            'sentidos'              => $request->sentidos,
            'idiomas'               => $request->idiomas,
            'rasgos_especiales'     => $request->rasgos_especiales,
            'acciones'              => $request->acciones,
            'acciones_adicionales'  => $request->acciones_adicionales,
            'reacciones'            => $request->reacciones,
            'acciones_legendarias'  => $request->acciones_legendarias,
            'visible_jugadores'     => $request->has('visible_jugadores'),
        ]);

        return redirect('/enemigos')->with('success', 'Enemigo creado correctamente.');
    }

    public function destroy($id)
    {
        $enemigo = Enemigo::where('id', $id)->where('usuario_id', Auth::id())->firstOrFail();
        $enemigo->delete();
        return redirect('/enemigos')->with('success', 'Enemigo eliminado.');
    }

    public function show($id)
    {
        $enemigo = Enemigo::where('id', $id)->where('usuario_id', Auth::id())->firstOrFail();
        return response()->json($enemigo);
    }

    public function update(Request $request, $id)
    {
        $enemigo = Enemigo::where('id', $id)->where('usuario_id', Auth::id())->firstOrFail();

        $request->validate([
            'nombre'                => ['required', 'string', 'max:255'],
            'tipo'                  => ['required', 'string', 'max:255'],
            'tamaño'                => ['required', 'string', 'max:255'],
            'clase_de_desafio'      => ['required', 'numeric'],
            'puntos_de_experiencia' => ['required', 'integer'],
            'clase_de_armadura'     => ['required', 'integer'],
            'puntos_de_golpe'       => ['required', 'string'],
            'velocidad'             => ['required', 'string'],
            'fuerza'                => ['required', 'integer'],
            'destreza'              => ['required', 'integer'],
            'constitucion'          => ['required', 'integer'],
            'inteligencia'          => ['required', 'integer'],
            'sabiduria'             => ['required', 'integer'],
            'carisma'               => ['required', 'integer'],
        ]);

        $enemigo->update([
            'nombre'                => $request->nombre,
            'descripcion'           => $request->descripcion,
            'tipo'                  => $request->tipo,
            'tamaño'                => $request->tamaño,
            'alineamiento'          => $request->alineamiento,
            'clase_de_desafio'      => $request->clase_de_desafio,
            'puntos_de_experiencia' => $request->puntos_de_experiencia,
            'clase_de_armadura'     => $request->clase_de_armadura,
            'tipo_armadura'         => $request->tipo_armadura,
            'puntos_de_golpe'       => $request->puntos_de_golpe,
            'velocidad'             => $request->velocidad,
            'fuerza'                => $request->fuerza,
            'destreza'              => $request->destreza,
            'constitucion'          => $request->constitucion,
            'inteligencia'          => $request->inteligencia,
            'sabiduria'             => $request->sabiduria,
            'carisma'               => $request->carisma,
            'resistencias'          => $request->resistencias,
            'inmunidades_daño'      => $request->inmunidades_daño,
            'sentidos'              => $request->sentidos,
            'idiomas'               => $request->idiomas,
            'acciones'              => $request->acciones,
            'rasgos_especiales'     => $request->rasgos_especiales,
            'visible_jugadores'     => $request->has('visible_jugadores'),
        ]);

        return redirect('/enemigos')->with('success', 'Enemigo actualizado correctamente.');
    }
}