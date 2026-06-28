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
    ];

    protected $casts = [
        'componentes'   => 'array',
        'clases'        => 'array',
        'concentracion' => 'boolean',
        'ritual'        => 'boolean',
    ];
}