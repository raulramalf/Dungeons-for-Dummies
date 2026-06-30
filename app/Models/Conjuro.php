<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conjuro extends Model
{
    protected $table = 'conjuros';

    protected $fillable = [
        'nombre', 'nivel', 'escuela', 'tiempo_lanzamiento',
        'alcance', 'componentes', 'material', 'duracion',
        'concentracion', 'ritual', 'descripcion',
        'a_niveles_superiores', 'clases',
        // Campos añadidos con el dataset de la edición 5.5 (DnD-5.5-Spells-ES)
        'tirada_de_salvacion', 'requiere_ataque', 'requiere_objetivo_visible', 'edicion',
    ];

    protected $casts = [
        'componentes'               => 'array',
        'clases'                    => 'array',
        'concentracion'             => 'boolean',
        'ritual'                    => 'boolean',
        'requiere_ataque'           => 'boolean',
        'requiere_objetivo_visible' => 'boolean',
    ];
}