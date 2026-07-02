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
        if (
            $intento->estado === "en_curso" &&
            $intento->ronda_actual < $enemigo->totalRondas()
        ) {
            $ronda = $enemigo->rondas[$intento->ronda_actual];
            $rondaActual = [
                "pregunta" => $ronda["pregunta"],
                "opciones" => collect($ronda["opciones"])
                    ->map(
                        fn($o, $i) => [
                            "indice" => $i,
                            "texto" => $o["texto"],
                            "es_trivia" => $o["es_trivia"] ?? false,
                        ],
                    )
                    ->values(),
            ];
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
            "opcion" => ["required", "integer", "min:0"],
        ]);

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

        if ($intento->ronda_actual >= $enemigo->totalRondas()) {
            return response()->json(["error" => "No quedan más rondas."], 422);
        }

        $ronda = $enemigo->rondas[$intento->ronda_actual];
        $opciones = $ronda["opciones"];
        $indice = $request->integer("opcion");

        if (!isset($opciones[$indice])) {
            return response()->json(["error" => "Opción inválida."], 422);
        }

        $opcionElegida = $opciones[$indice];
        $daño = (int) ($opcionElegida["daño"] ?? 0);

        $vidaRestante = max(0, $intento->vida_restante - $daño);
        $historial = $intento->opciones_elegidas ?? [];
        $historial[] = [
            "ronda" => $intento->ronda_actual,
            "opcion" => $indice,
            "daño" => $daño,
        ];

        $siguienteRonda = $intento->ronda_actual + 1;

        $estado = "en_curso";
        if ($vidaRestante <= 0) {
            $estado = "ganado";
        } elseif ($siguienteRonda >= $enemigo->totalRondas()) {
            $estado = "perdido";
        }

        $intento->update([
            "ronda_actual" => $siguienteRonda,
            "vida_restante" => $vidaRestante,
            "opciones_elegidas" => $historial,
            "estado" => $estado,
            "completado_en" => $estado !== "en_curso" ? now() : null,
        ]);

        $siguienteRondaPayload = null;
        if ($estado === "en_curso") {
            $ronda = $enemigo->rondas[$siguienteRonda];
            $siguienteRondaPayload = [
                "pregunta" => $ronda["pregunta"],
                "opciones" => collect($ronda["opciones"])
                    ->map(
                        fn($o, $i) => [
                            "indice" => $i,
                            "texto" => $o["texto"],
                            "es_trivia" => $o["es_trivia"] ?? false,
                        ],
                    )
                    ->values(),
            ];
        }

        return response()->json([
            "daño" => $daño,
            "es_trivia" => $opcionElegida["es_trivia"] ?? false,
            "correcta" => $opcionElegida["correcta"] ?? null,
            "vida_restante" => $vidaRestante,
            "vida_max" => $enemigo->vida_max,
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
}
