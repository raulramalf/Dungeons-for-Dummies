<?php

namespace App\Http\Controllers;

use App\Models\Personaje;
use App\Models\Campana;
use App\Models\Post;
use App\Models\Sesion;

class InicioController extends Controller
{
   public function index()
    {
        // Tu próxima sesión (como DM o como jugador) — una sola, destacada aparte
        $proximaSesion = null;
        $sesionesCalendario = collect();

        if (auth()->check()) {
            $campanaIds = Campana::where('dungeon_master_id', auth()->id())
                ->orWhereHas('usuarios', fn ($q) => $q->where('usuarios.id', auth()->id()))
                ->pluck('id');

            $proximaSesion = Sesion::with('campana.dungeonMaster')
                ->whereIn('campana_id', $campanaIds)
                ->where('estado', 'planificada')
                ->where('fecha_sesion', '>=', now())
                ->orderBy('fecha_sesion')
                ->first();

            // Todas las sesiones (pasadas y futuras) con fecha, para el calendario
            $sesionesCalendario = Sesion::with('campana')
                ->whereIn('campana_id', $campanaIds)
                ->whereNotNull('fecha_sesion')
                ->get()
                ->map(fn ($s) => [
                    'id'      => $s->id,
                    'fecha'   => $s->fecha_sesion->format('Y-m-d'),
                    'titulo'  => $s->titulo,
                    'campana' => $s->campana->nombre,
                    'numero'  => $s->numero_sesion,
                ]);
        }

        return view('inicio', compact('proximaSesion', 'sesionesCalendario'));
    }
}