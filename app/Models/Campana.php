<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campana extends Model
{
    use SoftDeletes;

    protected $table = 'campanas';

    protected $fillable = [
        'dungeon_master_id', 'nombre', 'descripcion', 'ambientacion',
        'estado', 'nivel_inicial', 'nivel_maximo', 'imagen', 'notas_dm',
    ];

    protected $casts = [
        'notas_dm' => 'array',
    ];

    public function dungeonMaster()
    {
        return $this->belongsTo(User::class, 'dungeon_master_id');
    }

    public function personajes()
    {
        return $this->belongsToMany(Personaje::class, 'personaje_campana');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesion::class);
    }
}