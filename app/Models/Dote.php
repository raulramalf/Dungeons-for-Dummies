<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dote extends Model
{
    protected $table = 'dotes';

    protected $fillable = [
        'nombre', 'categoria', 'descripcion', 'prerequisitos',
        'beneficios', 'incremento_caracteristica', 'repetible', 'edicion',
    ];

    protected $casts = [
        'prerequisitos'            => 'array',
        'beneficios'               => 'array',
        'incremento_caracteristica'=> 'boolean',
        'repetible'                => 'boolean',
    ];
}