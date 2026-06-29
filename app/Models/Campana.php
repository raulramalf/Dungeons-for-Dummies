<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Campana extends Model
{
    use SoftDeletes;

    protected $table = 'campanas';

    protected $fillable = [
        'dungeon_master_id', 'nombre', 'descripcion', 'ambientacion',
        'estado', 'nivel_inicial', 'nivel_maximo', 'imagen', 'notas_dm',
        'codigo_invitacion',
    ];

    protected $casts = [
        'notas_dm' => 'string',
    ];

    public function dungeonMaster()
    {
        return $this->belongsTo(Usuario::class, 'dungeon_master_id');
    }

    public function personajes()
    {
        return $this->belongsToMany(Personaje::class, 'personaje_campana');
    }

    public function sesiones()
    {
        return $this->hasMany(Sesion::class);
    }

    public function enemigos()
    {
        return $this->belongsToMany(Enemigo::class, 'campana_enemigo')
                    ->withPivot('visible_jugadores', 'notas_dm')
                    ->withTimestamps();
    }

    public static function generarCodigo(): string
    {
        do {
            $codigo = strtoupper(Str::random(6));
        } while (self::where('codigo_invitacion', $codigo)->exists());

        return $codigo;
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'campana_usuario', 'campana_id', 'usuario_id')
                    ->withPivot('rol')
                    ->withTimestamps();
    }

    public function notas()
    {
        return $this->hasMany(NotaCampana::class);
    }
}