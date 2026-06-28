<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sesion extends Model
{
    protected $table = 'sesiones';

    protected $fillable = [
        'campana_id', 'titulo', 'numero_sesion', 'resumen',
        'notas_dm', 'fecha_sesion', 'duracion_minutos', 'estado',
        'personajes_presentes', 'enemigos_encontrados', 'experiencia_otorgada',
    ];

    protected $casts = [
        'fecha_sesion'          => 'datetime',
        'personajes_presentes'  => 'array',
        'enemigos_encontrados'  => 'array',
    ];

    public function campana()
    {
        return $this->belongsTo(Campana::class);
    }
}