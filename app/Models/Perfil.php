<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfil';

    protected $fillable = [
        'usuario_id', 'nombre_display', 'biografia', 'avatar',
        'pais', 'idioma_preferido', 'preferencias',
        'partidas_jugadas', 'partidas_dm',
    ];

    protected $casts = [
        'preferencias' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}