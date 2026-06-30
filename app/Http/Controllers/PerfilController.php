<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PerfilController extends Controller
{
    public function show()
    {
        $usuario = Auth::user();
        
        $personajesCount = \App\Models\Personaje::where('usuario_id', $usuario->id)->count();
        $campanasCount = \App\Models\Campana::where('dungeon_master_id', $usuario->id)
            ->orWhereHas('usuarios', fn($q) => $q->where('usuarios.id', $usuario->id))
            ->count();
        $sesionesCount = \App\Models\Sesion::whereHas('campana', function($q) use ($usuario) {
            $q->where('dungeon_master_id', $usuario->id)
            ->orWhereHas('usuarios', fn($q2) => $q2->where('usuarios.id', $usuario->id));
        })->count();

        return view('perfil', compact('personajesCount', 'campanasCount', 'sesionesCount'));
    }

    public function actualizar(Request $request)
    {
        $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'email'  => ['required', 'email', 'max:255', 'unique:usuarios,email,' . Auth::id()],
        ]);

        Auth::user()->update([
            'nombre' => $request->nombre,
            'email'  => $request->email,
        ]);

        return redirect('/perfil')->with('success', 'Perfil actualizado correctamente.');
    }

    public function password(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect('/perfil')->with('success', 'Contraseña actualizada correctamente.');
    }

    public function actualizarAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $usuario = Auth::user();

        if ($usuario->avatar && !str_starts_with($usuario->avatar, 'http')) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($usuario->avatar);
        }

        $path = $request->file('avatar')->store('avatares', 'public');

        $usuario->update(['avatar' => $path]);

        return redirect('/perfil')->with('success', 'Foto de perfil actualizada.');
    }
}