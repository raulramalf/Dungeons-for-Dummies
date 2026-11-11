<?php

namespace App\Http\Controllers;

use App\Models\MinijuegoEnemigo;
use App\Models\MinijuegoIntento;
use App\Models\MinijuegoReto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MinijuegoController extends Controller
{
    private const INTERVALO_MINUTOS = 1440;

    public function index()
    {
        $reto = $this->retoDeHoy();
        $enemigo = $reto->enemigo;

        $intento = MinijuegoIntento::firstOrCreate(
            [
                "usuario_id" => Auth::id(),
                "minijuego_reto_id" => $reto->id,
            ],
            [
                "ronda_actual" => 0,
                "vida_restante" => $enemigo->vida_max,
                "opciones_elegidas" => [],
                "estado" => "en_curso",
            ],
        );

        $rondaActual = null;
        if ($intento->estado === "en_curso") {
            if ($intento->pregunta_actual_id && $intento->pregunta_actual_opciones) {
                $preguntaActual = \App\Models\MinijuegoPregunta::find($intento->pregunta_actual_id);
                $rondaActual = $preguntaActual
                    ? $this->payloadRonda($preguntaActual, $intento->pregunta_actual_opciones, $intento)
                    : $this->asignarNuevaPregunta($intento);
            } else {
                $rondaActual = $this->asignarNuevaPregunta($intento);
            }
        }

        $racha = $this->calcularRacha();

        $proximoCambio = $this->proximoCambio();

        return view(
            "minijuego",
            compact(
                "reto",
                "enemigo",
                "intento",
                "rondaActual",
                "racha",
                "proximoCambio",
            ),
        );
    }

    public function responder(Request $request)
    {
        $request->validate([
            "opcion" => ["required_without:tiempo_agotado", "nullable", "integer", "min:0"],
            "tiempo_agotado" => ["sometimes", "boolean"],
        ]);
        $tiempoAgotado = $request->boolean("tiempo_agotado");

        $reto = $this->retoDeHoy();
        $enemigo = $reto->enemigo;

        $intento = MinijuegoIntento::where("usuario_id", Auth::id())
            ->where("minijuego_reto_id", $reto->id)
            ->firstOrFail();

        if ($intento->estado !== "en_curso") {
            return response()->json(
                ["error" => "Este reto ya ha terminado por hoy."],
                422,
            );
        }

        $opciones = $intento->pregunta_actual_opciones;
        $indice = $tiempoAgotado ? null : $request->integer("opcion");

        if (!$opciones || (!$tiempoAgotado && !isset($opciones[$indice]))) {
            return response()->json(["error" => "Opción inválida."], 422);
        }

        $correcta = !$tiempoAgotado && (bool) ($opciones[$indice]["correcta"] ?? false);
        $indiceCorrecta = collect($opciones)->search(fn($o) => !empty($o["correcta"]));
        $preguntaRespondida = \App\Models\MinijuegoPregunta::find($intento->pregunta_actual_id);

        $historial = $intento->opciones_elegidas ?? [];
        $historial[] = [
            "pregunta_id" => $intento->pregunta_actual_id,
            "opcion" => $indice,
            "correcta" => $correcta,
        ];

        $aciertos = collect($historial)->where("correcta", true)->count();
        $fallos = collect($historial)->where("correcta", false)->count();

        $vidaRestante = max(0, (int) round($enemigo->vida_max * (1 - $aciertos / 4)));

        $estado = "en_curso";
        if ($aciertos >= 4) {
            $estado = "ganado";
            $vidaRestante = 0;
        } elseif ($fallos >= 4) {
            $estado = "perdido";
        }

        $intento->update([
            "ronda_actual" => count($historial),
            "vida_restante" => $vidaRestante,
            "opciones_elegidas" => $historial,
            "estado" => $estado,
            "pregunta_actual_id" => $estado === "en_curso" ? $intento->pregunta_actual_id : null,
            "pregunta_actual_opciones" => $estado === "en_curso" ? $intento->pregunta_actual_opciones : null,
            "completado_en" => $estado !== "en_curso" ? now() : null,
        ]);

        $siguienteRondaPayload = null;
        if ($estado === "en_curso") {
            $siguienteRondaPayload = $this->asignarNuevaPregunta($intento);
        }

        return response()->json([
            "correcta" => $correcta,
            "tiempo_agotado" => $tiempoAgotado,
            "indice_correcta" => $indiceCorrecta === false ? null : $indiceCorrecta,
            "explicacion" => $preguntaRespondida?->explicacion,
            "danio" => $correcta ? (int) round($enemigo->vida_max / 4) : 0,
            "vida_restante" => $vidaRestante,
            "vida_max" => $enemigo->vida_max,
            "aciertos" => $aciertos,
            "fallos" => $fallos,
            "estado" => $estado,
            "ronda" => $siguienteRondaPayload,
            "racha" => $this->calcularRacha(),
            "proximo_cambio" => $this->proximoCambio()->toIso8601String(),
        ]);
    }

    private function retoDeHoy(): MinijuegoReto
    {
        $inicioPeriodo = $this->inicioPeriodoActual();
        $claveFecha = $inicioPeriodo->toDateTimeString();

        return DB::transaction(function () use ($claveFecha) {
            $reto = MinijuegoReto::where("fecha", $claveFecha)->first();

            if ($reto) {
                return $reto;
            }

            $enemigos = MinijuegoEnemigo::where("activo", true)
                ->orderBy("id")
                ->pluck("id");

            if ($enemigos->isEmpty()) {
                abort(
                    500,
                    "No hay enemigos configurados para el minijuego diario.",
                );
            }

            $seed = crc32($claveFecha);
            $indice = $seed % $enemigos->count();
            $enemigoId = $enemigos[$indice];

            return MinijuegoReto::create([
                "fecha" => $claveFecha,
                "minijuego_enemigo_id" => $enemigoId,
            ]);
        });
    }

    private function inicioPeriodoActual(): \Illuminate\Support\Carbon
    {
        $intervalo = self::INTERVALO_MINUTOS;
        $minutosDesdeEpoch = intdiv(now()->timestamp, 60);
        $bucket = intdiv($minutosDesdeEpoch, $intervalo) * $intervalo;

        return \Illuminate\Support\Carbon::createFromTimestamp($bucket * 60);
    }

    private function proximoCambio(): \Illuminate\Support\Carbon
    {
        return $this->inicioPeriodoActual()
            ->copy()
            ->addMinutes(self::INTERVALO_MINUTOS);
    }

    private function calcularRacha(): int
    {
        $intentos = MinijuegoIntento::where("usuario_id", Auth::id())
            ->where("estado", "ganado")
            ->join(
                "minijuego_retos",
                "minijuego_retos.id",
                "=",
                "minijuego_intentos.minijuego_reto_id",
            )
            ->orderBy("minijuego_retos.fecha", "desc")
            ->pluck("minijuego_retos.fecha");

        if ($intentos->isEmpty()) {
            return 0;
        }

        $racha = 0;
        $esperado = now()->startOfDay();

        foreach ($intentos as $fecha) {
            $fecha = \Illuminate\Support\Carbon::parse($fecha)->startOfDay();

            if ($fecha->equalTo($esperado)) {
                $racha++;
                $esperado = $esperado->copy()->subDay();
            } elseif ($fecha->lt($esperado)) {
                break;
            }
        }

        return $racha;
    }

    private function elegirPreguntaAleatoria(array $idsExcluidos): ?\App\Models\MinijuegoPregunta
    {
        $pregunta = \App\Models\MinijuegoPregunta::where("activo", true)
            ->when(!empty($idsExcluidos), fn($q) => $q->whereNotIn("id", $idsExcluidos))
            ->inRandomOrder()
            ->first();

        // Si el pool se agota (raro, solo con muy pocas preguntas), reintenta sin excluir
        return $pregunta ?? \App\Models\MinijuegoPregunta::where("activo", true)->inRandomOrder()->first();
    }

    private function asignarNuevaPregunta(MinijuegoIntento $intento): ?array
    {
        $idsUsados = collect($intento->opciones_elegidas ?? [])
            ->pluck("pregunta_id")
            ->filter()
            ->all();

        $pregunta = $this->elegirPreguntaAleatoria($idsUsados);
        if (!$pregunta) {
            return null;
        }

        $opcionesMezcladas = collect($pregunta->opciones)->shuffle()->values()->all();

        $intento->update([
            "pregunta_actual_id" => $pregunta->id,
            "pregunta_actual_opciones" => $opcionesMezcladas,
        ]);

        return $this->payloadRonda($pregunta, $opcionesMezcladas, $intento);
    }

    private function payloadRonda($pregunta, array $opciones, MinijuegoIntento $intento): array
    {
        return [
            "numero" => count($intento->opciones_elegidas ?? []) + 1,
            "pregunta" => $pregunta->pregunta,
            "categoria" => $pregunta->categoria,
            "dificultad" => $pregunta->dificultad ?? "media",
            "opciones" => collect($opciones)
                ->map(fn($o, $i) => ["indice" => $i, "texto" => $o["texto"]])
                ->values(),
        ];
    }
}
