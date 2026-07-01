<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PerfilController;
use App\Http\Controllers\EnemigoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PersonajeController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\TrucoController;
use App\Http\Controllers\CampanaController;

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
    Route::get('/personajes/{personaje}/exportar', [PersonajeController::class, 'elegirPlantilla'])->name('personajes.exportar');
    Route::get('/personajes/{personaje}/exportar/descargar', [PersonajeController::class, 'exportarFicha'])->name('personajes.exportar.descargar');
    Route::put('/personajes/{personaje}', [PersonajeController::class, 'update'])->name('personajes.update');
    Route::delete('/personajes/{personaje}', [PersonajeController::class, 'destroy'])->name('personajes.destroy');
    Route::delete('/personajes/{personaje}/dotes/{dote}', [PersonajeController::class, 'eliminarDote'])->name('dotes.destroy');

    // Eliminar imagen de personaje
    Route::post('/personajes/{personaje}/eliminar-imagen', [PersonajeController::class, 'eliminarImagen'])
         ->name('personajes.eliminarImagen');

    // Equipo y monedas
    Route::prefix('personajes/{personaje}')->group(function () {
        Route::post('/equipo', [EquipoController::class, 'store'])->name('equipo.store');
        Route::delete('/equipo/{equipo}', [EquipoController::class, 'destroy'])->name('equipo.destroy');
        Route::post('/trucos', [TrucoController::class, 'store'])->name('trucos.store');
        Route::delete('/trucos/{truco}', [TrucoController::class, 'destroy'])->name('trucos.destroy');
        Route::put('/monedas', [PersonajeController::class, 'actualizarMonedas'])->name('personajes.actualizar_monedas');
    });
});

// ==========================================
// RUTAS DEL FEED / TABERNA (requieren auth)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/feed', [FeedController::class, 'index'])->name('feed.index');
    Route::post('/feed', [FeedController::class, 'store'])->name('feed.store');
    Route::delete('/feed/{post}', [FeedController::class, 'destroy'])->name('feed.destroy');
    Route::post('/comentarios', [FeedController::class, 'storeComentario'])->name('comentarios.store');
    Route::delete('/comentarios/{comentario}', [FeedController::class, 'destroyComentario'])->name('comentarios.destroy');
    Route::post('/like', [FeedController::class, 'toggleLike'])->name('like.toggle');
});

// ==========================================
// RUTAS DE ENEMIGOS (requieren auth)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/enemigos', [EnemigoController::class, 'index'])->name('enemigos.index');
    Route::post('/enemigos', [EnemigoController::class, 'store'])->name('enemigos.store');
    Route::delete('/enemigos/{id}', [EnemigoController::class, 'destroy'])->name('enemigos.destroy');
    Route::post('/enemigos/{id}/update', [EnemigoController::class, 'update'])->name('enemigos.update');
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
    Route::post('/perfil/avatar', [PerfilController::class, 'actualizarAvatar'])->name('perfil.avatar');

    // Profile de Breeze (inglés — no eliminar, lo usa auth)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// RUTAS DE CAMPAÑAS (requiere auth)
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/campanyas', [CampanaController::class, 'index'])->name('campanyas');
    Route::post('/campanyas', [CampanaController::class, 'store']);
    Route::post('/unirse-campana', [CampanaController::class, 'unirse'])->name('campana.unirse');
    Route::get('/campanyas/{id}', [CampanaController::class, 'show'])->name('campana.show');
    Route::patch('/campanyas/{id}', [CampanaController::class, 'update']);
    Route::delete('/campanyas/{id}', [CampanaController::class, 'destroy']);
    Route::post('/campanyas/{id}/enemigos', [CampanaController::class, 'añadirEnemigo']);
    Route::delete('/campanyas/{id}/enemigos/{enemigo_id}', [CampanaController::class, 'quitarEnemigo']);
    Route::post('/campanyas/{id}/sesiones', [CampanaController::class, 'añadirSesion']);
    Route::patch('/campanyas/{id}/sesiones/{sesion_id}', [CampanaController::class, 'editarSesion']);
    Route::delete('/campanyas/{id}/sesiones/{sesion_id}', [CampanaController::class, 'eliminarSesion']);
    Route::delete('/campanyas/{id}/usuarios/{usuario_id}', [CampanaController::class, 'expulsarJugador']);
    Route::post('/campanyas/{id}/personaje', [CampanaController::class, 'añadirPersonaje']);
    Route::get('/personajes/{id}/json', [PersonajeController::class, 'json'])->middleware('auth');
    Route::post('/campanyas/{id}/notas', [CampanaController::class, 'crearNota']);
    Route::delete('/campanyas/{id}/notas/{nota_id}', [CampanaController::class, 'eliminarNota']);
    Route::patch('/campanyas/{id}/notas/{nota_id}', [CampanaController::class, 'editarNota']);
    Route::post('/campanyas/{id}/personaje/historia-visible', [CampanaController::class, 'toggleHistoriaVisible'])->name('campanyas.historia_visible');
});

require __DIR__.'/auth.php';