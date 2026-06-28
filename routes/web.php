<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfilController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('inicio');
})->middleware(['auth'])->name('inicio');

Route::get('/feed', function () {
    return view('feed');
})->middleware(['auth'])->name('feed');

Route::get('/personajes', function () {
    return view('personajes');
})->middleware(['auth'])->name('personajes');

Route::get('/campanyas', function () {
    return view('campanyas');
})->middleware(['auth'])->name('campanyas');

Route::get('/enemigos', function () {
    return view('enemigos');
})->middleware(['auth'])->name('enemigos');

Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil');
    Route::patch('/perfil/actualizar', [PerfilController::class, 'actualizar']);
    Route::patch('/perfil/password', [PerfilController::class, 'password']);

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';