<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\EnemigoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PersonajeController;
use App\Http\Controllers\EquipoController;

// ==========================================
// RUTA DE INICIO
// ==========================================
Route::get('/', function () {
    return view('inicio');
<<<<<<< HEAD
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
=======
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

Route::middleware('auth')->group(function () {
    Route::get('/enemigos', [EnemigoController::class, 'index'])->name('enemigos');
    Route::post('/enemigos', [EnemigoController::class, 'store']);
    Route::delete('/enemigos/{id}', [EnemigoController::class, 'destroy']);
});

Route::get('/enemigos/{id}', [EnemigoController::class, 'show']);

Route::patch('/enemigos/{id}', [EnemigoController::class, 'update']);

require __DIR__.'/auth.php';
>>>>>>> origin/feature/perfil-campanyas-enemigos
