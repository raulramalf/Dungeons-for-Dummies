<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enemigo extends Model
{
    protected $table = 'enemigos';

    protected $fillable = [
        'usuario_id',
        'nombre',
        'descripcion',
        'tipo',
        'tamaño',
        'alineamiento',
        'clase_de_desafio',
        'puntos_de_experiencia',
        'clase_de_armadura',
        'tipo_armadura',
        'puntos_de_golpe',
        'velocidad',
        'velocidades_especiales',
        'fuerza',
        'destreza',
        'constitucion',
        'inteligencia',
        'sabiduria',
        'carisma',
        'tiradas_salvacion',
        'competencias',
        'resistencias',
        'inmunidades_daño',
        'vulnerabilidades',
        'inmunidades_condicion',
        'sentidos',
        'idiomas',
        'rasgos_especiales',
        'acciones',
        'acciones_adicionales',
        'reacciones',
        'acciones_legendarias',
        'imagen',
        'visible_jugadores',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function campanas()
    {
        return $this->belongsToMany(Campana::class, 'campana_enemigo')
                    ->withPivot('visible_jugadores', 'notas_dm')
                    ->withTimestamps();
    }
}