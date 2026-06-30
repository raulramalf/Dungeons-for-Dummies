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
        }

        // Hazañas recientes de la Taberna
        $hazanas = Post::with('usuario')
            ->withCount('likes')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'tipo'  => 'hazana',
                'fecha' => $p->created_at,
                'data'  => $p,
            ]);

        // Personajes nuevos de la comunidad
        $personajes = Personaje::with(['usuario', 'raza', 'clase'])
            ->where('activo', true)
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($p) => [
                'tipo'  => 'personaje',
                'fecha' => $p->created_at,
                'data'  => $p,
            ]);

        // Campañas que han abierto mesa recientemente
        $campanas = Campana::with('dungeonMaster')
            ->where('estado', 'activa')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($c) => [
                'tipo'  => 'campana',
                'fecha' => $c->created_at,
                'data'  => $c,
            ]);

        // La Bitácora: todo lo anterior intercalado por fecha, lo más reciente primero
        $bitacora = $hazanas->concat($personajes)->concat($campanas)
            ->sortByDesc('fecha')
            ->take(8)
            ->values();

        return view('inicio', compact('proximaSesion', 'bitacora'));
    }
}