<?php

namespace App\Http\Controllers;

use App\Models\Campana;
use App\Models\Enemigo;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampanaController extends Controller
{
    public function index()
    {
        $campanas = Campana::where('dungeon_master_id', Auth::id())
                            ->withCount('sesiones')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('campanyas', compact('campanas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['nullable', 'string'],
            'ambientacion' => ['nullable', 'string', 'max:255'],
            'nivel_inicial'=> ['required', 'integer', 'min:1', 'max:20'],
            'nivel_maximo' => ['nullable', 'integer', 'min:1', 'max:20'],
            'estado'       => ['required', 'in:activa,pausada,finalizada'],
        ]);

        Campana::create([
            'dungeon_master_id' => Auth::id(),
            'nombre'            => $request->nombre,
            'descripcion'       => $request->descripcion,
            'ambientacion'      => $request->ambientacion,
            'estado'            => $request->estado,
            'nivel_inicial'     => $request->nivel_inicial,
            'nivel_maximo'      => $request->nivel_maximo,
            'codigo_invitacion' => Campana::generarCodigo(),
        ]);

        return redirect('/campanyas')->with('success', 'Campaña creada correctamente.');
    }

    public function show($id)
    {
        $campana = Campana::where('id', $id)
                   ->with(['sesiones', 'enemigos', 'personajes', 'usuarios'])
                   ->firstOrFail();

        $esDM = $campana->dungeon_master_id === Auth::id();
        $enemigos = $esDM ? Enemigo::where('usuario_id', Auth::id())->get() : collect();

        return view('campana-detalle', compact('campana', 'enemigos', 'esDM'));
    }

    public function update(Request $request, $id)
    {
        $campana = Campana::where('id', $id)
                          ->where('dungeon_master_id', Auth::id())
                          ->firstOrFail();

        $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'descripcion'  => ['nullable', 'string'],
            'ambientacion' => ['nullable', 'string', 'max:255'],
            'nivel_inicial'=> ['required', 'integer', 'min:1', 'max:20'],
            'nivel_maximo' => ['nullable', 'integer', 'min:1', 'max:20'],
            'estado'       => ['required', 'in:activa,pausada,finalizada'],
        ]);

        $campana->update($request->only([
            'nombre', 'descripcion', 'ambientacion',
            'estado', 'nivel_inicial', 'nivel_maximo',
        ]));

        return redirect('/campanyas')->with('success', 'Campaña actualizada correctamente.');
    }

    public function destroy($id)
    {
        $campana = Campana::where('id', $id)
                          ->where('dungeon_master_id', Auth::id())
                          ->firstOrFail();
        $campana->delete();

        return redirect('/campanyas')->with('success', 'Campaña eliminada.');
    }

    public function unirse(Request $request)
    {
        $request->validate([
            'codigo_invitacion' => ['required', 'string', 'max:6'],
        ]);

        $campana = Campana::where('codigo_invitacion', strtoupper(trim($request->codigo_invitacion)))
                        ->whereIn('estado', ['activa', 'pausada'])
                        ->first();

        if (!$campana) {
            return back()->withErrors(['codigo_invitacion' => 'Código inválido o campaña finalizada.']);
        }

        // No unirse si ya eres miembro o el DM
        if ($campana->dungeon_master_id === Auth::id()) {
            return back()->withErrors(['codigo_invitacion' => 'Ya eres el Dungeon Master de esta campaña.']);
        }

        $campana->usuarios()->syncWithoutDetaching([
            Auth::id() => ['rol' => 'jugador']
        ]);

        return redirect('/campanyas/' . $campana->id)->with('success', 'Te has unido a la campaña.');
    }

    public function añadirEnemigo(Request $request, $id)
    {
        $campana = Campana::where('id', $id)
                          ->where('dungeon_master_id', Auth::id())
                          ->firstOrFail();

        $request->validate([
            'enemigo_id' => ['required', 'exists:enemigos,id'],
        ]);

        $campana->enemigos()->syncWithoutDetaching([
            $request->enemigo_id => [
                'visible_jugadores' => $request->has('visible_jugadores'),
            ]
        ]);

        return back()->with('success', 'Enemigo añadido a la campaña.');
    }

    public function quitarEnemigo($campana_id, $enemigo_id)
    {
        $campana = Campana::where('id', $campana_id)
                          ->where('dungeon_master_id', Auth::id())
                          ->firstOrFail();

        $campana->enemigos()->detach($enemigo_id);

        return back()->with('success', 'Enemigo eliminado de la campaña.');
    }

    public function añadirSesion(Request $request, $id)
    {
        $campana = Campana::where('id', $id)
                        ->where('dungeon_master_id', Auth::id())
                        ->firstOrFail();

        $request->validate([
            'titulo'        => ['required', 'string', 'max:255'],
            'numero_sesion' => ['required', 'integer', 'min:1'],
        ]);

        $campana->sesiones()->create([
            'titulo'            => $request->titulo,
            'numero_sesion'     => $request->numero_sesion,
            'fecha_sesion'      => $request->fecha_sesion,
            'resumen'           => $request->resumen,
            'duracion_minutos'  => $request->duracion_minutos,
            'estado'            => 'completada',
        ]);

        return back()->with('success', 'Sesión añadida correctamente.');
    }

    public function editarSesion(Request $request, $id, $sesion_id)
    {
        $campana = Campana::where('id', $id)
                        ->where('dungeon_master_id', Auth::id())
                        ->firstOrFail();

        $sesion = $campana->sesiones()->findOrFail($sesion_id);

        $request->validate([
            'titulo'        => ['required', 'string', 'max:255'],
            'numero_sesion' => ['required', 'integer', 'min:1'],
        ]);

        $sesion->update([
            'titulo'           => $request->titulo,
            'numero_sesion'    => $request->numero_sesion,
            'fecha_sesion'     => $request->fecha_sesion,
            'resumen'          => $request->resumen,
            'duracion_minutos' => $request->duracion_minutos,
        ]);

        return back()->with('success', 'Sesión actualizada correctamente.');
    }

    public function eliminarSesion($id, $sesion_id)
    {
        $campana = Campana::where('id', $id)
                        ->where('dungeon_master_id', Auth::id())
                        ->firstOrFail();

        $campana->sesiones()->findOrFail($sesion_id)->delete();

        return back()->with('success', 'Sesión eliminada.');
    }

    public function expulsarJugador($id, $usuario_id)
    {
        $campana = Campana::where('id', $id)
                        ->where('dungeon_master_id', Auth::id())
                        ->firstOrFail();

        $campana->usuarios()->detach($usuario_id);

        return back()->with('success', 'Jugador expulsado de la campaña.');
    }
}