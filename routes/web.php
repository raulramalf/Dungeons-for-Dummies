<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PersonajeController;

// ==========================================
// RUTA DE INICIO
// ==========================================
Route::get('/', function () {
    return view('inicio');
})->name('inicio');

// ==========================================
// RUTAS DE PERSONAJES (Dinámicas)
// ==========================================
// Enlazadas a PersonajeController para cargar datos y guardar nuevos personajes
Route::get('/personajes', [PersonajeController::class, 'index'])->name('personajes.index');
Route::post('/personajes', [PersonajeController::class, 'store'])->name('personajes.store');
Route::get('/personajes/{personaje}', [PersonajeController::class, 'show'])->name('personajes.show');

// ==========================================
// RUTAS DEL FEED / TABERNA (Dinámicas)
// ==========================================
// Enlazadas a FeedController para manejar publicaciones, comentarios y likes
Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');
Route::post('/comentarios', [FeedController::class, 'storeComentario'])->name('comentarios.store');
Route::post('/like', [FeedController::class, 'toggleLike'])->name('like.toggle');

// ==========================================
// RUTAS ESTÁTICAS (Como las tenías)
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