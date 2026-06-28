<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Personaje;
use App\Models\Raza;
use App\Models\Clase;
use App\Models\Trasfondo;
use App\Models\Estadistica;
use Illuminate\Support\Facades\Storage;

class PersonajeController extends Controller
{
    public function index()
    {
        $personajes = Personaje::with(['raza', 'clase'])
            ->where('usuario_id', auth()->id())
            ->latest()
            ->get();

        return view('personajes', compact('personajes'));
    }

    public function create()
    {
        $razas      = Raza::all();
        $clases     = Clase::all();
        $trasfondos = Trasfondo::all();

        return view('personajes_crear', compact('razas', 'clases', 'trasfondos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'nivel'        => 'required|integer|min:1|max:20',
            'raza_id'      => 'required|exists:razas,id',
            'clase_id'     => 'required|exists:clases,id',
            'trasfondo_id' => 'nullable|exists:trasfondos,id',
            'alineamiento' => 'nullable|string|max:50',
            'avatar'       => 'nullable|url|max:500',
            'historia'     => 'nullable|string',
            'fuerza'       => 'required|integer|min:1|max:30',
            'destreza'     => 'required|integer|min:1|max:30',
            'constitucion' => 'required|integer|min:1|max:30',
            'inteligencia' => 'required|integer|min:1|max:30',
            'sabiduria'    => 'required|integer|min:1|max:30',
            'carisma'      => 'required|integer|min:1|max:30',
        ]);

        $personaje = Personaje::create([
            'usuario_id'   => auth()->id(),
            'nombre'       => $validated['nombre'],
            'nivel'        => $validated['nivel'],
            'raza_id'      => $validated['raza_id'],
            'clase_id'     => $validated['clase_id'],
            'trasfondo_id' => $validated['trasfondo_id'] ?? null,
            'alineamiento' => $validated['alineamiento'] ?? null,
            'avatar'       => $validated['avatar'] ?? null,
            'historia'     => $validated['historia'] ?? null,
        ]);

        Estadistica::create([
            'personaje_id'      => $personaje->id,
            'fuerza'            => $validated['fuerza'],
            'destreza'          => $validated['destreza'],
            'constitucion'      => $validated['constitucion'],
            'inteligencia'      => $validated['inteligencia'],
            'sabiduria'         => $validated['sabiduria'],
            'carisma'           => $validated['carisma'],
            'pg_maximos'        => 10,
            'pg_actuales'       => 10,
            'clase_de_armadura' => 10,
            'velocidad'         => 30,
            'bonus_competencia' => 2,
        ]);

        return redirect()->route('personajes.index')
            ->with('success', '¡Personaje creado exitosamente!');
    }

    public function show(Personaje $personaje)
    {
        // Solo el propietario puede ver su personaje
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para ver este personaje.');
        }

        $personaje->load([
            'raza',
            'clase',
            'subclase',
            'trasfondo',
            'estadisticas',
            'equipo',
            'trucos',
        ]);

        return view('personaje_individual', compact('personaje'));
    }

    public function edit(Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $personaje->load(['estadisticas', 'equipo', 'raza', 'clase', 'trasfondo']);

        $razas      = Raza::all();
        $clases     = Clase::all();
        $trasfondos = Trasfondo::all();

        return view('personajes_editar', compact('personaje', 'razas', 'clases', 'trasfondos'));
    }

    public function update(Request $request, Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            // Básicos
            'nombre'       => 'required|string|max:255',
            'nivel'        => 'required|integer|min:1|max:20',
            'raza_id'      => 'required|exists:razas,id',
            'clase_id'     => 'required|exists:clases,id',
            'trasfondo_id' => 'nullable|exists:trasfondos,id',
            'alineamiento' => 'nullable|string|max:50',
            'avatar'       => 'nullable|url|max:500',
            'historia'     => 'nullable|string',
            // Rasgos de personalidad
            'rasgos_personalidad' => 'nullable|string',
            'ideales'             => 'nullable|string',
            'vinculos'            => 'nullable|string',
            'defectos'            => 'nullable|string',
            // Apariencia
            'edad'    => 'nullable|string|max:50',
            'altura'  => 'nullable|string|max:50',
            'peso'    => 'nullable|string|max:50',
            'ojos'    => 'nullable|string|max:100',
            'piel'    => 'nullable|string|max:100',
            'pelo'    => 'nullable|string|max:100',
            'divinidad' => 'nullable|string|max:255',
            // Estadísticas
            'fuerza'       => 'required|integer|min:1|max:30',
            'destreza'     => 'required|integer|min:1|max:30',
            'constitucion' => 'required|integer|min:1|max:30',
            'inteligencia' => 'required|integer|min:1|max:30',
            'sabiduria'    => 'required|integer|min:1|max:30',
            'carisma'      => 'required|integer|min:1|max:30',
            // Combate
            'pg_actuales'       => 'nullable|integer|min:0',
            'pg_maximos'        => 'nullable|integer|min:1',
            'pg_temporales'     => 'nullable|integer|min:0',
            'clase_de_armadura' => 'nullable|integer|min:0',
            'velocidad'         => 'nullable|integer|min:0',
            'bonus_competencia' => 'nullable|integer|min:0|max:6',
            'iniciativa'        => 'nullable|integer',
            // Dados de golpe y muerte
            'dados_golpe_disponibles' => 'nullable|integer|min:0',
            'exitos_muerte'           => 'nullable|integer|min:0|max:3',
            'fallos_muerte'           => 'nullable|integer|min:0|max:3',
            'inspiracion'             => 'nullable|boolean',
            // Imágenes
            'imagenes_personaje.*' => 'nullable|image|max:2048',
            'imagenes_armas.*'     => 'nullable|image|max:2048',
            // Competencias (JSON)
            'competencias_habilidades' => 'nullable|json',
            'competencias_salvaciones' => 'nullable|json',
            'idiomas'                  => 'nullable|string',
            // Ataques (JSON)
            'ataques' => 'nullable|json',
        ]);

        // ——— Actualizar datos básicos del personaje ———
        $personaje->update([
            'nombre'              => $validated['nombre'],
            'nivel'               => $validated['nivel'],
            'raza_id'             => $validated['raza_id'],
            'clase_id'            => $validated['clase_id'],
            'trasfondo_id'        => $validated['trasfondo_id'] ?? null,
            'alineamiento'        => $validated['alineamiento'] ?? null,
            'avatar'              => $validated['avatar'] ?? $personaje->avatar,
            'historia'            => $validated['historia'] ?? null,
            'rasgos_personalidad' => $validated['rasgos_personalidad'] ?? null,
            'ideales'             => $validated['ideales'] ?? null,
            'vinculos'            => $validated['vinculos'] ?? null,
            'defectos'            => $validated['defectos'] ?? null,
            'edad'                => $validated['edad'] ?? null,
            'altura'              => $validated['altura'] ?? null,
            'peso'                => $validated['peso'] ?? null,
            'ojos'                => $validated['ojos'] ?? null,
            'piel'                => $validated['piel'] ?? null,
            'pelo'                => $validated['pelo'] ?? null,
            'divinidad'           => $validated['divinidad'] ?? null,
            'idiomas'             => $validated['idiomas'] ?? null,
            'competencias_habilidades' => $validated['competencias_habilidades'] ?? null,
            'competencias_salvaciones' => $validated['competencias_salvaciones'] ?? null,
            'ataques'             => $validated['ataques'] ?? null,
        ]);

        // ——— Subida de imágenes del personaje (máx. 5) ———
        if ($request->hasFile('imagenes_personaje')) {
            $paths = json_decode($personaje->imagenes_personaje ?? '[]', true);
            foreach (array_slice($request->file('imagenes_personaje'), 0, 5 - count($paths)) as $file) {
                $paths[] = $file->store('personajes/' . $personaje->id, 'public');
            }
            $personaje->imagenes_personaje = json_encode(array_slice($paths, 0, 5));
            $personaje->save();
        }

        // ——— Subida de imágenes de armas (máx. 5) ———
        if ($request->hasFile('imagenes_armas')) {
            $paths = json_decode($personaje->imagenes_armas ?? '[]', true);
            foreach (array_slice($request->file('imagenes_armas'), 0, 5 - count($paths)) as $file) {
                $paths[] = $file->store('armas/' . $personaje->id, 'public');
            }
            $personaje->imagenes_armas = json_encode(array_slice($paths, 0, 5));
            $personaje->save();
        }

        // ——— Actualizar o crear estadísticas ———
        $estadisticas = $personaje->estadisticas;
        $statsData = [
            'fuerza'                  => $validated['fuerza'],
            'destreza'                => $validated['destreza'],
            'constitucion'            => $validated['constitucion'],
            'inteligencia'            => $validated['inteligencia'],
            'sabiduria'               => $validated['sabiduria'],
            'carisma'                 => $validated['carisma'],
            'pg_actuales'             => $validated['pg_actuales'] ?? ($estadisticas->pg_actuales ?? 10),
            'pg_maximos'              => $validated['pg_maximos'] ?? ($estadisticas->pg_maximos ?? 10),
            'pg_temporales'           => $validated['pg_temporales'] ?? 0,
            'clase_de_armadura'       => $validated['clase_de_armadura'] ?? ($estadisticas->clase_de_armadura ?? 10),
            'velocidad'               => $validated['velocidad'] ?? ($estadisticas->velocidad ?? 30),
            'bonus_competencia'       => $validated['bonus_competencia'] ?? ($estadisticas->bonus_competencia ?? 2),
            'iniciativa'              => $validated['iniciativa'] ?? null,
            'dados_golpe_disponibles' => $validated['dados_golpe_disponibles'] ?? null,
            'exitos_muerte'           => $validated['exitos_muerte'] ?? 0,
            'fallos_muerte'           => $validated['fallos_muerte'] ?? 0,
            'inspiracion'             => $request->boolean('inspiracion'),
        ];

        if ($estadisticas) {
            $estadisticas->update($statsData);
        } else {
            $personaje->estadisticas()->create($statsData);
        }

        return redirect()->route('personajes.show', $personaje)
            ->with('success', '¡Personaje actualizado correctamente!');
    }

    public function destroy(Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $personaje->delete();

        return redirect()->route('personajes.index')
            ->with('success', 'Personaje eliminado correctamente.');
    }

    public function actualizarMonedas(Request $request, Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'monedas_cobre'    => 'nullable|integer|min:0',
            'monedas_plata'    => 'nullable|integer|min:0',
            'monedas_electrum' => 'nullable|integer|min:0',
            'monedas_oro'      => 'nullable|integer|min:0',
            'monedas_platino'  => 'nullable|integer|min:0',
        ]);

        $estadisticas = $personaje->estadisticas;
        if ($estadisticas) {
            $estadisticas->update($request->only([
                'monedas_cobre', 'monedas_plata', 'monedas_electrum',
                'monedas_oro', 'monedas_platino',
            ]));
        } else {
            $personaje->estadisticas()->create(
                $request->only([
                    'monedas_cobre', 'monedas_plata', 'monedas_electrum',
                    'monedas_oro', 'monedas_platino',
                ]) + [
                    'pg_maximos'        => 10,
                    'pg_actuales'       => 10,
                    'clase_de_armadura' => 10,
                    'velocidad'         => 30,
                    'bonus_competencia' => 2,
                ]
            );
        }

        return redirect()->route('personajes.show', $personaje)
            ->with('success', 'Monedas actualizadas correctamente.');
    }

    /**
     * Elimina una imagen concreta del personaje
     */
    public function eliminarImagen(Request $request, Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'tipo'  => 'required|in:personaje,arma',
            'index' => 'required|integer|min:0',
        ]);

        $campo = $request->tipo === 'personaje' ? 'imagenes_personaje' : 'imagenes_armas';
        $paths = json_decode($personaje->$campo ?? '[]', true);

        if (isset($paths[$request->index])) {
            Storage::disk('public')->delete($paths[$request->index]);
            array_splice($paths, $request->index, 1);
            $personaje->$campo = json_encode(array_values($paths));
            $personaje->save();
        }

        return back()->with('success', 'Imagen eliminada.');
    }
}