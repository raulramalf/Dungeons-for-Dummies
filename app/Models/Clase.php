<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Clase extends Model
{
    protected $table = 'clases';

    protected $fillable = [
        'nombre', 'descripcion', 'dado_golpe',
        'competencias_armadura', 'competencias_armas',
        'competencias_herramientas', 'tiradas_salvacion',
        'puntos_golpe_nivel_1', 'habilidad_principal',
    ];

    protected $casts = [
        'competencias_armadura'    => 'array',
        'competencias_armas'       => 'array',
        'competencias_herramientas'=> 'array',
        'tiradas_salvacion'        => 'array',
    ];

    public function subclases()
    {
        return $this->hasMany(Subclase::class);
    }

    public function personajes()
    {
        return $this->hasMany(Personaje::class);
    }
}