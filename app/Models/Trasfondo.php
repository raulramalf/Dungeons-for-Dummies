<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trasfondo extends Model
{
    protected $table = 'trasfondos';

    protected $fillable = [
        'nombre', 'descripcion', 'competencias_habilidades',
        'competencias_herramientas', 'idiomas', 'equipo_inicial',
        'rasgo_personalidad', 'ideal', 'vinculo',
        'defecto', 'caracteristica_especial',
        'mejora_caracteristicas', 'dote_origen', 'edicion',
    ];

    protected $casts = [
        'competencias_habilidades'  => 'array',
        'competencias_herramientas' => 'array',
        'idiomas'                   => 'array',
        'equipo_inicial'            => 'array',
        'mejora_caracteristicas'    => 'array',
    ];

    public function personajes()
    {
        return $this->hasMany(Personaje::class);
    }
}