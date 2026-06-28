<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    protected $table = 'equipo';

    protected $fillable = [
        'personaje_id', 'nombre', 'tipo', 'descripcion', 'rareza',
        'magico', 'requiere_sintonizacion', 'sintonizado', 'equipado',
        'cantidad', 'peso', 'valor_po', 'propiedades',
    ];

    protected $casts = [
        'magico'                 => 'boolean',
        'requiere_sintonizacion' => 'boolean',
        'sintonizado'            => 'boolean',
        'equipado'               => 'boolean',
        'propiedades'            => 'array',
    ];

    public function personaje()
    {
        return $this->belongsTo(Personaje::class);
    }
}