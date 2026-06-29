<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotaCampana extends Model
{
    protected $table = 'notas_campana';

    protected $fillable = ['campana_id', 'titulo', 'contenido', 'visible_jugadores'];

    public function campana()
    {
        return $this->belongsTo(Campana::class);
    }
}