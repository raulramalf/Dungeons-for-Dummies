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
        return view('perfil');
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
}