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
        $personajes = Personaje::with(["raza", "clase"])
            ->where("usuario_id", auth()->id())
            ->latest()
            ->get();

        return view("personajes", compact("personajes"));
    }

    public function create()
    {
        $razas = Raza::all();
        $clases = Clase::all();
        $trasfondos = Trasfondo::all();
        $subclases = \App\Models\Subclase::with("clase")
            ->orderBy("nombre")
            ->get();
        $dotes = \App\Models\Dote::orderBy("categoria")
            ->orderBy("nombre")
            ->get();

        return view(
            "personajes_crear",
            compact("razas", "clases", "trasfondos", "subclases", "dotes"),
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "nombre" => "required|string|max:255",
            "nivel" => "required|integer|min:1|max:20",
            "raza_id" => "required|exists:razas,id",
            "clase_id" => "required|exists:clases,id",
            "subclase_id" => "nullable|exists:subclases,id",
            "trasfondo_id" => "nullable|exists:trasfondos,id",
            "alineamiento" => "nullable|string|max:50",
            "avatar" => "nullable|url|max:500",
            "historia" => "nullable|string",
            "fuerza" => "required|integer|min:1|max:30",
            "destreza" => "required|integer|min:1|max:30",
            "constitucion" => "required|integer|min:1|max:30",
            "inteligencia" => "required|integer|min:1|max:30",
            "sabiduria" => "required|integer|min:1|max:30",
            "carisma" => "required|integer|min:1|max:30",
        ]);

        $personaje = Personaje::create([
            "usuario_id" => auth()->id(),
            "nombre" => $validated["nombre"],
            "nivel" => $validated["nivel"],
            "raza_id" => $validated["raza_id"],
            "clase_id" => $validated["clase_id"],
            "subclase_id" => $validated["subclase_id"] ?? null,
            "trasfondo_id" => $validated["trasfondo_id"] ?? null,
            "alineamiento" => $validated["alineamiento"] ?? null,
            "avatar" => $validated["avatar"] ?? null,
            "historia" => $validated["historia"] ?? null,
        ]);

        Estadistica::create([
            "personaje_id" => $personaje->id,
            "fuerza" => $validated["fuerza"],
            "destreza" => $validated["destreza"],
            "constitucion" => $validated["constitucion"],
            "inteligencia" => $validated["inteligencia"],
            "sabiduria" => $validated["sabiduria"],
            "carisma" => $validated["carisma"],
            "pg_maximos" => 10,
            "pg_actuales" => 10,
            "clase_de_armadura" => 10,
            "velocidad" => 30,
            "bonus_competencia" => $this->calcularBonusCompetencia(
                $validated["nivel"],
            ),
        ]);

        return redirect()
            ->route("personajes.index")
            ->with("success", "¡Personaje creado exitosamente!");
    }

    public function show(Personaje $personaje)
    {
        // Solo el propietario puede ver su personaje
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403, "No tienes permiso para ver este personaje.");
        }

        $personaje->load([
            "raza",
            "clase",
            "subclase",
            "trasfondo",
            "estadisticas",
            "equipo",
            "trucos.conjuro",
            "dotes",
        ]);

        return view("personaje_individual", compact("personaje"));
    }

    // Plantillas de ficha PDF disponibles
    const PLANTILLAS_PDF = [
        "oficial" => [
            "label" => "Estilo Oficial 2024",
            "vista" => "personajes.ficha_pdf_oficial",
            "desc" =>
                "Maquetación fiel a la disposición estándar de una hoja de personaje de 5e: mismas cajas, círculos y dos páginas.",
        ],
        "mistica" => [
            "label" => "Mística",
            "vista" => "personajes.ficha_pdf_mistica",
            "desc" =>
                "Columnas con círculos de característica, panel de combate central y rasgos a la derecha. Marco morado y dorado.",
        ],
        "clasica" => [
            "label" => "Clásica Oscura",
            "vista" => "personajes.ficha_pdf_clasica",
            "desc" =>
                "Cabecera con nombre/clase/especie, stats con casillas a la izquierda y trasfondo/rasgos a la derecha. Fondo granate oscuro.",
        ],
    ];

    // Tipos de ficha: completa (todo) o resumen (solo combate/conjuros/equipo)
    const TIPOS_FICHA = [
        "completa" => [
            "label" => "Ficha de personaje completa",
            "desc" =>
                "Características, salvaciones, habilidades, combate, historia, equipo, armas y conjuros.",
        ],
        "resumen" => [
            "label" => "Ficha de conjuros, armas y equipo",
            "desc" =>
                "Solo lo que necesitas en mesa: armas y ataques, trucos y conjuros, y equipo. Sin historia ni personalidad.",
        ],
    ];

    public function elegirPlantilla(Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        return view("personajes.exportar_elegir", [
            "personaje" => $personaje,
            "plantillas" => self::PLANTILLAS_PDF,
            "tipos" => self::TIPOS_FICHA,
        ]);
    }

    public function exportarFicha(Request $request, Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403, "No tienes permiso para exportar este personaje.");
        }

        $plantilla = $request->query("plantilla", "oficial");
        if (!array_key_exists($plantilla, self::PLANTILLAS_PDF)) {
            $plantilla = "oficial";
        }

        $tipo = $request->query("tipo", "completa");
        if (!array_key_exists($tipo, self::TIPOS_FICHA)) {
            $tipo = "completa";
        }

        $personaje->load([
            "raza",
            "clase",
            "subclase",
            "trasfondo",
            "estadisticas",
            "equipo",
            "trucos.conjuro",
            "dotes",
        ]);

        $vista = self::PLANTILLAS_PDF[$plantilla]["vista"];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            $vista,
            compact("personaje", "tipo"),
        )->setPaper("a4", "portrait");

        $sufijo = $tipo === "resumen" ? "-resumen" : "";
        $nombreArchivo =
            "ficha-" .
            \Illuminate\Support\Str::slug($personaje->nombre) .
            $sufijo .
            ".pdf";

        return $pdf->download($nombreArchivo);
    }

    public function edit(Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $personaje->load([
            "estadisticas",
            "equipo",
            "raza",
            "clase",
            "subclase",
            "trasfondo",
            "trucos.conjuro",
            "dotes",
        ]);

        $razas = Raza::all();
        $clases = Clase::all();
        $trasfondos = Trasfondo::all();
        $subclases = \App\Models\Subclase::with("clase")
            ->orderBy("nombre")
            ->get();
        $dotes = \App\Models\Dote::orderBy("categoria")
            ->orderBy("nombre")
            ->get();

        // Catálogo completo de conjuros, sin filtrar por clase
        // (hay rasgos, dotes y trasfondos que dan acceso a conjuros de otras clases)
        $conjurosCatalogo = \App\Models\Conjuro::orderBy("nivel")
            ->orderBy("nombre")
            ->get();

        return view(
            "personajes_editar",
            compact(
                "personaje",
                "razas",
                "clases",
                "trasfondos",
                "subclases",
                "dotes",
                "conjurosCatalogo",
            ),
        );
    }

    /**
     * Calcula el bonus de competencia según el nivel del personaje (regla estándar de 5e).
     */
    private function calcularBonusCompetencia(int $nivel): int
    {
        return 2 + intdiv(max($nivel, 1) - 1, 4);
    }

    public function update(Request $request, Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            // Básicos
            "nombre" => "required|string|max:255",
            "nivel" => "required|integer|min:1|max:20",
            "raza_id" => "required|exists:razas,id",
            "clase_id" => "required|exists:clases,id",
            "subclase_id" => "nullable|exists:subclases,id",
            "trasfondo_id" => "nullable|exists:trasfondos,id",
            "alineamiento" => "nullable|string|max:50",
            "avatar" => "nullable|url|max:500",
            "historia" => "nullable|string",
            "dotes_nuevos" => "nullable|json",
            // Rasgos de personalidad
            "rasgos_personalidad" => "nullable|string",
            "ideales" => "nullable|string",
            "vinculos" => "nullable|string",
            "defectos" => "nullable|string",
            // Apariencia
            "edad" => "nullable|string|max:50",
            "altura" => "nullable|string|max:50",
            "peso" => "nullable|string|max:50",
            "ojos" => "nullable|string|max:100",
            "piel" => "nullable|string|max:100",
            "pelo" => "nullable|string|max:100",
            "divinidad" => "nullable|string|max:255",
            // Estadísticas
            "fuerza" => "required|integer|min:1|max:30",
            "destreza" => "required|integer|min:1|max:30",
            "constitucion" => "required|integer|min:1|max:30",
            "inteligencia" => "required|integer|min:1|max:30",
            "sabiduria" => "required|integer|min:1|max:30",
            "carisma" => "required|integer|min:1|max:30",
            // Combate
            "pg_actuales" => "nullable|integer|min:0",
            "pg_maximos" => "nullable|integer|min:1",
            "pg_temporales" => "nullable|integer|min:0",
            "clase_de_armadura" => "nullable|integer|min:0",
            "velocidad" => "nullable|integer|min:0",
            "iniciativa" => "nullable|integer",
            // Dados de golpe y muerte
            "dados_golpe_disponibles" => "nullable|integer|min:0",
            "exitos_muerte" => "nullable|integer|min:0|max:3",
            "fallos_muerte" => "nullable|integer|min:0|max:3",
            "inspiracion" => "nullable|boolean",
            // Imágenes
            "imagenes_personaje.*" => "nullable|image|max:2048",
            "imagenes_armas.*" => "nullable|image|max:2048",
            // Competencias (JSON)
            "competencias_habilidades" => "nullable|json",
            "competencias_salvaciones" => "nullable|json",
            "idiomas" => "nullable|string",
            // Ataques (JSON)
            "ataques" => "nullable|json",
            // Conjuros nuevos seleccionados desde el catálogo (JSON con ids)
            "conjuros_nuevos" => "nullable|json",
        ]);

        // ——— Actualizar datos básicos del personaje ———
        $personaje->update([
            "nombre" => $validated["nombre"],
            "nivel" => $validated["nivel"],
            "raza_id" => $validated["raza_id"],
            "clase_id" => $validated["clase_id"],
            "subclase_id" => $validated["subclase_id"] ?? null,
            "trasfondo_id" => $validated["trasfondo_id"] ?? null,
            "alineamiento" => $validated["alineamiento"] ?? null,
            "avatar" => $validated["avatar"] ?? $personaje->avatar,
            "historia" => $validated["historia"] ?? null,
            "rasgos_personalidad" => $validated["rasgos_personalidad"] ?? null,
            "ideales" => $validated["ideales"] ?? null,
            "vinculos" => $validated["vinculos"] ?? null,
            "defectos" => $validated["defectos"] ?? null,
            "edad" => $validated["edad"] ?? null,
            "altura" => $validated["altura"] ?? null,
            "peso" => $validated["peso"] ?? null,
            "ojos" => $validated["ojos"] ?? null,
            "piel" => $validated["piel"] ?? null,
            "pelo" => $validated["pelo"] ?? null,
            "divinidad" => $validated["divinidad"] ?? null,
            "idiomas" => $validated["idiomas"] ?? null,
            "competencias_habilidades" =>
                $validated["competencias_habilidades"] ?? null,
            "competencias_salvaciones" =>
                $validated["competencias_salvaciones"] ?? null,
            "ataques" => $validated["ataques"] ?? null,
        ]);

        // ——— Añadir dotes nuevas seleccionadas en el desplegable ———
        $dotesNuevos =
            json_decode($validated["dotes_nuevos"] ?? "[]", true) ?? [];
        if (count($dotesNuevos) > 0) {
            $yaTiene = $personaje->dotes()->pluck("dotes.id")->toArray();
            $aAnadir = array_diff($dotesNuevos, $yaTiene);
            if (!empty($aAnadir)) {
                $personaje->dotes()->attach($aAnadir);
            }
        }

        // ——— Subida de imágenes del personaje (máx. 5) ———
        if ($request->hasFile("imagenes_personaje")) {
            $paths = json_decode($personaje->imagenes_personaje ?? "[]", true);
            foreach (
                array_slice(
                    $request->file("imagenes_personaje"),
                    0,
                    5 - count($paths),
                )
                as $file
            ) {
                $paths[] = $file->store(
                    "personajes/" . $personaje->id,
                    "public",
                );
            }
            $personaje->imagenes_personaje = json_encode(
                array_slice($paths, 0, 5),
            );
            $personaje->save();
        }

        // ——— Subida de imágenes de armas (máx. 5) ———
        if ($request->hasFile("imagenes_armas")) {
            $paths = json_decode($personaje->imagenes_armas ?? "[]", true);
            foreach (
                array_slice(
                    $request->file("imagenes_armas"),
                    0,
                    5 - count($paths),
                )
                as $file
            ) {
                $paths[] = $file->store("armas/" . $personaje->id, "public");
            }
            $personaje->imagenes_armas = json_encode(array_slice($paths, 0, 5));
            $personaje->save();
        }

        // ——— Actualizar o crear estadísticas ———
        $estadisticas = $personaje->estadisticas;
        $statsData = [
            "fuerza" => $validated["fuerza"],
            "destreza" => $validated["destreza"],
            "constitucion" => $validated["constitucion"],
            "inteligencia" => $validated["inteligencia"],
            "sabiduria" => $validated["sabiduria"],
            "carisma" => $validated["carisma"],
            "pg_actuales" =>
                $validated["pg_actuales"] ?? ($estadisticas->pg_actuales ?? 10),
            "pg_maximos" =>
                $validated["pg_maximos"] ?? ($estadisticas->pg_maximos ?? 10),
            "pg_temporales" => $validated["pg_temporales"] ?? 0,
            "clase_de_armadura" =>
                $validated["clase_de_armadura"] ??
                ($estadisticas->clase_de_armadura ?? 10),
            "velocidad" =>
                $validated["velocidad"] ?? ($estadisticas->velocidad ?? 30),
            "bonus_competencia" => $this->calcularBonusCompetencia($validated["nivel"]),
            "iniciativa" => $validated["iniciativa"] ?? null,
            "dados_golpe_disponibles" =>
                $validated["dados_golpe_disponibles"] ?? null,
            "exitos_muerte" => $validated["exitos_muerte"] ?? 0,
            "fallos_muerte" => $validated["fallos_muerte"] ?? 0,
            "inspiracion" => $request->boolean("inspiracion"),
        ];

        if ($estadisticas) {
            $estadisticas->update($statsData);
        } else {
            $personaje->estadisticas()->create($statsData);
        }

        // ——— Añadir conjuros seleccionados en el desplegable ———
        $conjurosNuevos =
            json_decode($validated["conjuros_nuevos"] ?? "[]", true) ?? [];
        if (count($conjurosNuevos) > 0) {
            $yaTiene = $personaje
                ->trucos()
                ->pluck("conjuro_id")
                ->filter()
                ->toArray();
            $aAnadir = \App\Models\Conjuro::whereIn(
                "id",
                array_diff($conjurosNuevos, $yaTiene),
            )->get();

            foreach ($aAnadir as $conjuro) {
                $personaje->trucos()->create([
                    "conjuro_id" => $conjuro->id,
                    "nombre" => $conjuro->nombre,
                ]);
            }
        }

        return redirect()
            ->route("personajes.show", $personaje)
            ->with("success", "¡Personaje actualizado correctamente!");
    }

    public function destroy(Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $personaje->delete();

        return redirect()
            ->route("personajes.index")
            ->with("success", "Personaje eliminado correctamente.");
    }

    public function actualizarMonedas(Request $request, Personaje $personaje)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            "monedas_cobre" => "nullable|integer|min:0",
            "monedas_plata" => "nullable|integer|min:0",
            "monedas_electrum" => "nullable|integer|min:0",
            "monedas_oro" => "nullable|integer|min:0",
            "monedas_platino" => "nullable|integer|min:0",
        ]);

        $estadisticas = $personaje->estadisticas;
        if ($estadisticas) {
            $estadisticas->update(
                $request->only([
                    "monedas_cobre",
                    "monedas_plata",
                    "monedas_electrum",
                    "monedas_oro",
                    "monedas_platino",
                ]),
            );
        } else {
            $personaje->estadisticas()->create(
                $request->only([
                    "monedas_cobre",
                    "monedas_plata",
                    "monedas_electrum",
                    "monedas_oro",
                    "monedas_platino",
                ]) + [
                    "pg_maximos" => 10,
                    "pg_actuales" => 10,
                    "clase_de_armadura" => 10,
                    "velocidad" => 30,
                    "bonus_competencia" => 2,
                ],
            );
        }

        return redirect()
            ->route("personajes.show", $personaje)
            ->with("success", "Monedas actualizadas correctamente.");
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
            "tipo" => "required|in:personaje,arma",
            "index" => "required|integer|min:0",
        ]);

        $campo =
            $request->tipo === "personaje"
                ? "imagenes_personaje"
                : "imagenes_armas";
        $paths = json_decode($personaje->$campo ?? "[]", true);

        if (isset($paths[$request->index])) {
            Storage::disk("public")->delete($paths[$request->index]);
            array_splice($paths, $request->index, 1);
            $personaje->$campo = json_encode(array_values($paths));
            $personaje->save();
        }

        return back()->with("success", "Imagen eliminada.");
    }

    /**
     * Quita una dote concreta del personaje (equivalente a eliminarTruco, pero vía detach en la pivot)
     */
    public function eliminarDote(Personaje $personaje, \App\Models\Dote $dote)
    {
        if ($personaje->usuario_id !== auth()->id()) {
            abort(403);
        }

        $personaje->dotes()->detach($dote->id);

        return back()->with("success", "Dote eliminada.");
    }

    public function json($id)
    {
        $personaje = \App\Models\Personaje::with([
            "clase",
            "raza",
            "subclase",
            "trasfondo",
            "estadisticas",
            "equipo",
            "campanas",
            "dotes",
        ])->findOrFail($id);

        $ataques = json_decode($personaje->ataques ?? "[]", true) ?? [];

        // Determinar si la historia es visible
        $campanaId = request("campana_id");
        $historiaVisible = true; // por defecto visible (vista propia o sin contexto de campaña)

        if ($campanaId && $personaje->usuario_id !== auth()->id()) {
            // Comprobar si el usuario actual es DM de la campaña
            $campana = \App\Models\Campana::find($campanaId);
            $esDM = $campana && $campana->dungeon_master_id === auth()->id();

            if (!$esDM) {
                $pivot = $personaje->campanas->where("id", $campanaId)->first()
                    ?->pivot;
                $historiaVisible = $pivot?->historia_visible ?? false;
            }
        }

        return response()->json([
            "nombre" => $personaje->nombre,
            "avatar_url" => $personaje->avatar_url,
            "clase" => $personaje->clase->nombre ?? null,
            "raza" => $personaje->raza->nombre ?? null,
            "subclase" => $personaje->subclase->nombre ?? null,
            "trasfondo" => $personaje->trasfondo->nombre ?? null,
            "dotes" => $personaje->dotes->pluck("nombre"),
            "alineamiento" => $personaje->alineamiento,
            "nivel" => $personaje->nivel,
            "historia" => $historiaVisible ? $personaje->historia : null,
            "rasgos_personalidad" => $personaje->rasgos_personalidad,
            "ideales" => $personaje->ideales,
            "vinculos" => $personaje->vinculos,
            "defectos" => $personaje->defectos,
            "idiomas" => $personaje->idiomas,
            "fuerza" => $personaje->estadisticas->fuerza ?? 10,
            "destreza" => $personaje->estadisticas->destreza ?? 10,
            "constitucion" => $personaje->estadisticas->constitucion ?? 10,
            "inteligencia" => $personaje->estadisticas->inteligencia ?? 10,
            "sabiduria" => $personaje->estadisticas->sabiduria ?? 10,
            "carisma" => $personaje->estadisticas->carisma ?? 10,
            "pg_actuales" => $personaje->estadisticas->pg_actuales ?? null,
            "pg_maximos" => $personaje->estadisticas->pg_maximos ?? null,
            "clase_de_armadura" =>
                $personaje->estadisticas->clase_de_armadura ?? null,
            "iniciativa" => $personaje->estadisticas->iniciativa ?? null,
            "velocidad" => $personaje->estadisticas->velocidad ?? 30,
            "bonus_competencia" =>
                $personaje->estadisticas->bonus_competencia ?? 2,
            "equipo" => $personaje->equipo->map(
                fn($e) => [
                    "nombre" => $e->nombre,
                    "tipo" => $e->tipo,
                    "equipado" => $e->equipado,
                    "cantidad" => $e->cantidad,
                ],
            ),
            "ataques" => $ataques,
        ]);
    }
}
