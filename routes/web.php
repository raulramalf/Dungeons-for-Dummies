<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\EnemigoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PersonajeController;
use App\Http\Controllers\EquipoController;

// ==========================================
// RUTA DE INICIO (pública — muestra personajes y campañas)
// ==========================================
Route::get('/', [InicioController::class, 'index'])->name('inicio');

// Redirección dashboard → inicio
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// RUTAS DE PERSONAJES (requieren auth)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/personajes', [PersonajeController::class, 'index'])->name('personajes.index');
    Route::get('/personajes/crear', [PersonajeController::class, 'create'])->name('personajes.create');
    Route::post('/personajes', [PersonajeController::class, 'store'])->name('personajes.store');
    Route::get('/personajes/{personaje}', [PersonajeController::class, 'show'])->name('personajes.show');
    Route::get('/personajes/{personaje}/editar', [PersonajeController::class, 'edit'])->name('personajes.edit');
    Route::put('/personajes/{personaje}', [PersonajeController::class, 'update'])->name('personajes.update');
    Route::delete('/personajes/{personaje}', [PersonajeController::class, 'destroy'])->name('personajes.destroy');

    // Eliminar imagen de personaje
    Route::post('/personajes/{personaje}/eliminar-imagen', [PersonajeController::class, 'eliminarImagen'])
         ->name('personajes.eliminarImagen');

    // Equipo y monedas
    Route::prefix('personajes/{personaje}')->group(function () {
        Route::post('/equipo', [EquipoController::class, 'store'])->name('equipo.store');
        Route::delete('/equipo/{equipo}', [EquipoController::class, 'destroy'])->name('equipo.destroy');
        Route::put('/monedas', [PersonajeController::class, 'actualizarMonedas'])->name('personajes.actualizar_monedas');
    });
});

// ==========================================
// RUTAS DEL FEED / TABERNA (requieren auth)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');
    Route::post('/comentarios', [FeedController::class, 'storeComentario'])->name('comentarios.store');
    Route::post('/like', [FeedController::class, 'toggleLike'])->name('like.toggle');
});

// ==========================================
// RUTAS DE CAMPAÑAS (pública — vista estática)
// ==========================================
Route::get('/campanyas', function () {
    return view('campanyas');
})->name('campanyas.index');

// ==========================================
// RUTAS DE ENEMIGOS (requieren auth)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/enemigos', [EnemigoController::class, 'index'])->name('enemigos.index');
    Route::post('/enemigos', [EnemigoController::class, 'store'])->name('enemigos.store');
    Route::delete('/enemigos/{id}', [EnemigoController::class, 'destroy'])->name('enemigos.destroy');
    Route::patch('/enemigos/{id}', [EnemigoController::class, 'update'])->name('enemigos.update');
});

// Ver enemigo individual (público)
Route::get('/enemigos/{id}', [EnemigoController::class, 'show'])->name('enemigos.show');

// ==========================================
// PERFIL DE USUARIO (requiere auth)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/perfil', [PerfilController::class, 'show'])->name('perfil.index');
    Route::patch('/perfil/actualizar', [PerfilController::class, 'actualizar'])->name('perfil.actualizar');
    Route::patch('/perfil/password', [PerfilController::class, 'password'])->name('perfil.password');

    // Profile de Breeze (inglés — no eliminar, lo usa auth)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';