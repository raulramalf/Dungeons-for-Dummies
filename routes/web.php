<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('inicio');
});

Route::get('/personajes', function () {
    return view('personajes');
});

Route::get('/campanyas', function () {
    return view('campanyas');
});

Route::get('/enemigos', function () {
    return view('enemigos');
});

Route::get('/perfil', function () {
    return view('perfil');
});

Route::get('/feed', function () {
    return view('feed');
});