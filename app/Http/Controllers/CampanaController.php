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
        $campanasDM = Campana::where('dungeon_master_id', Auth::id())
                            ->withCount('sesiones')
                            ->orderBy('created_at', 'desc')
                            ->get();

        $campanasJugador = Campana::whereHas('usuarios', function($q) {
                                        $q->where('usuario_id', Auth::id());
                                })
                                ->withCount('sesiones')
                                ->orderBy('created_at', 'desc')
                                ->get();

        $misPersonajes = \App\Models\Personaje::where('usuario_id', Auth::id())
                                            ->where('activo', 1)
                                            ->get();

        return view('campanyas', compact('campanasDM', 'campanasJugador', 'misPersonajes'));
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
            ->with(['sesiones', 'enemigos', 'personajes.usuario', 'usuarios', 'notas'])
            ->firstOrFail();

        $esDM = $campana->dungeon_master_id === Auth::id();
        $enemigos = $esDM ? Enemigo::where('usuario_id', Auth::id())->get() : collect();
        
        // Personajes agrupados por usuario
        $personajesPorUsuario = $campana->personajes->groupBy('usuario_id');
        
        // Mis personajes disponibles para añadir (si soy jugador)
        $misPersonajes = !$esDM ? \App\Models\Personaje::where('usuario_id', Auth::id())
                                                        ->where('activo', 1)
                                                        ->get() : collect();

        return view('campana-detalle', compact('campana', 'enemigos', 'esDM', 'personajesPorUsuario', 'misPersonajes'));
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
            'personaje_id'      => ['nullable', 'exists:personajes,id'],
        ]);

        $campana = Campana::where('codigo_invitacion', strtoupper(trim($request->codigo_invitacion)))
                        ->whereIn('estado', ['activa', 'pausada'])
                        ->first();

        if (!$campana) {
            return back()->withErrors(['codigo_invitacion' => 'Código inválido o campaña finalizada.']);
        }

        if ($campana->dungeon_master_id === Auth::id()) {
            return back()->withErrors(['codigo_invitacion' => 'Ya eres el Dungeon Master de esta campaña.']);
        }

        $campana->usuarios()->syncWithoutDetaching([
            Auth::id() => ['rol' => 'jugador']
        ]);

        // Vincular personaje si se seleccionó uno
        if ($request->personaje_id) {
            $campana->personajes()->syncWithoutDetaching([
                $request->personaje_id => ['estado' => 'activo']
            ]);
        }

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

    public function añadirPersonaje(Request $request, $id)
    {
        $campana = Campana::findOrFail($id);

        $request->validate([
            'personaje_id' => ['required', 'exists:personajes,id'],
        ]);

        $campana->personajes()->syncWithoutDetaching([
            $request->personaje_id => ['estado' => 'activo']
        ]);

        return back()->with('success', 'Personaje añadido a la campaña.');
    }

    public function guardarNotas(Request $request, $id)
    {
        $campana = Campana::where('id', $id)
                        ->where('dungeon_master_id', Auth::id())
                        ->firstOrFail();

        $campana->update(['notas_dm' => $request->notas_dm]);

        return back()->with('success', 'Notas guardadas correctamente.');
    }

    public function crearNota(Request $request, $id)
{
    $campana = Campana::where('id', $id)
                      ->where('dungeon_master_id', Auth::id())
                      ->firstOrFail();

    $request->validate([
        'titulo'    => ['required', 'string', 'max:255'],
        'contenido' => ['required', 'string'],
    ]);

    $campana->notas()->create([
            'titulo'             => $request->titulo,
            'contenido'          => $request->contenido,
            'visible_jugadores'  => $request->has('visible_jugadores'),
        ]);

        return back()->with('success', 'Nota añadida correctamente.');
    }

    public function eliminarNota($id, $nota_id)
    {
        $campana = Campana::where('id', $id)
                        ->where('dungeon_master_id', Auth::id())
                        ->firstOrFail();

        $campana->notas()->findOrFail($nota_id)->delete();

        return back()->with('success', 'Nota eliminada.');
    }

    public function editarNota(Request $request, $id, $nota_id)
    {
        $campana = Campana::where('id', $id)
                        ->where('dungeon_master_id', Auth::id())
                        ->firstOrFail();

        $request->validate([
            'titulo'    => ['required', 'string', 'max:255'],
            'contenido' => ['required', 'string'],
        ]);

        $campana->notas()->findOrFail($nota_id)->update([
            'titulo'            => $request->titulo,
            'contenido'         => $request->contenido,
            'visible_jugadores' => $request->has('visible_jugadores'),
        ]);

        return back()->with('success', 'Nota actualizada correctamente.');
    }
}