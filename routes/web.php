<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PersonajeController;
use App\Http\Controllers\EquipoController;

// ==========================================
// RUTA DE INICIO
// ==========================================
Route::get('/', function () {
    return view('inicio');
})->name('inicio');

// ==========================================
// RUTAS DE PERSONAJES (CRUD COMPLETO)
// ==========================================
Route::get('/personajes', [PersonajeController::class, 'index'])->name('personajes.index');
Route::get('/personajes/crear', [PersonajeController::class, 'create'])->name('personajes.create');
Route::post('/personajes', [PersonajeController::class, 'store'])->name('personajes.store');
Route::get('/personajes/{personaje}', [PersonajeController::class, 'show'])->name('personajes.show');
Route::get('/personajes/{personaje}/editar', [PersonajeController::class, 'edit'])->name('personajes.edit');
Route::put('/personajes/{personaje}', [PersonajeController::class, 'update'])->name('personajes.update');
Route::delete('/personajes/{personaje}', [PersonajeController::class, 'destroy'])->name('personajes.destroy');

// ==========================================
// RUTAS PARA EQUIPO Y MONEDAS
// ==========================================
Route::prefix('personajes/{personaje}')->group(function () {
    Route::post('/equipo', [EquipoController::class, 'store'])->name('equipo.store');
    Route::delete('/equipo/{equipo}', [EquipoController::class, 'destroy'])->name('equipo.destroy');
    Route::put('/monedas', [PersonajeController::class, 'actualizarMonedas'])->name('personajes.actualizar_monedas');
});

// ==========================================
// RUTAS DEL FEED / TABERNA
// ==========================================
Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');
Route::post('/comentarios', [FeedController::class, 'storeComentario'])->name('comentarios.store');
Route::post('/like', [FeedController::class, 'toggleLike'])->name('like.toggle');

// ==========================================
// RUTAS ESTÁTICAS
// ==========================================
Route::get('/campanyas', function () {
    return view('campanyas');
})->name('campanyas.index');

Route::get('/enemigos', function () {
    return view('enemigos');
})->name('enemigos.index');

Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil.index');