<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enemigo extends Model
{
    protected $table = 'enemigos';

    protected $fillable = [
        'nombre', 'descripcion', 'tipo', 'tamaño', 'alineamiento',
        'clase_de_desafio', 'puntos_de_experiencia', 'clase_de_armadura',
        'tipo_armadura', 'puntos_de_golpe', 'velocidad', 'velocidades_especiales',
        'fuerza', 'destreza', 'constitucion', 'inteligencia', 'sabiduria', 'carisma',
        'tiradas_salvacion', 'competencias', 'resistencias', 'inmunidades_daño',
        'vulnerabilidades', 'inmunidades_condicion', 'sentidos', 'idiomas',
        'rasgos_especiales', 'acciones', 'acciones_adicionales',
        'reacciones', 'acciones_legendarias', 'imagen',
    ];

    protected $casts = [
        'velocidades_especiales' => 'array',
        'tiradas_salvacion'      => 'array',
        'competencias'           => 'array',
        'resistencias'           => 'array',
        'inmunidades_daño'       => 'array',
        'vulnerabilidades'       => 'array',
        'inmunidades_condicion'  => 'array',
        'sentidos'               => 'array',
        'rasgos_especiales'      => 'array',
        'acciones'               => 'array',
        'acciones_adicionales'   => 'array',
        'reacciones'             => 'array',
        'acciones_legendarias'   => 'array',
    ];
}