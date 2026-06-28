<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subclase extends Model
{
    protected $table = 'subclases';

    protected $fillable = [
        'clase_id', 'nombre', 'descripcion',
        'nivel_disponible', 'rasgos',
    ];

    protected $casts = [
        'rasgos' => 'array',
    ];

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function personajes()
    {
        return $this->hasMany(Personaje::class);
    }
}