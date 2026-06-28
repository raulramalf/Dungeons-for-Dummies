<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raza extends Model
{
    protected $table = 'razas';

    protected $fillable = [
        'nombre', 'descripcion', 'velocidad', 'tamaño',
        'bonificadores_caracteristica', 'rasgos', 'idiomas', 'vision',
    ];

    protected $casts = [
        'bonificadores_caracteristica' => 'array',
        'rasgos'   => 'array',
        'idiomas'  => 'array',
        'vision'   => 'array',
    ];

    public function personajes()
    {
        return $this->hasMany(Personaje::class);
    }
}