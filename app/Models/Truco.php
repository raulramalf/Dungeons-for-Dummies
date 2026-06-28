<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Truco extends Model
{
    protected $table = 'trucos';

    protected $fillable = [
        'personaje_id', 'conjuro_id', 'nombre', 'descripcion', 'fuente',
    ];

    public function personaje()
    {
        return $this->belongsTo(Personaje::class);
    }

    public function conjuro()
    {
        return $this->belongsTo(Conjuro::class);
    }
}