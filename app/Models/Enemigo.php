<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enemigo extends Model
{
    protected $table = 'enemigos';

    protected $fillable = [
        'usuario_id', 'nombre', 'descripcion', 'tipo', 'tamaño', 'alineamiento',
        'clase_de_desafio', 'puntos_de_experiencia', 'clase_de_armadura', 'tipo_armadura',
        'puntos_de_golpe', 'velocidad', 'velocidades_especiales',
        'fuerza', 'destreza', 'constitucion', 'inteligencia', 'sabiduria', 'carisma',
        'tiradas_salvacion', 'competencias', 'resistencias', 'inmunidades_daño',
        'vulnerabilidades', 'inmunidades_condicion', 'sentidos', 'idiomas',
        'rasgos_especiales', 'acciones', 'acciones_adicionales', 'reacciones',
        'acciones_legendarias', 'imagen', 'visible_jugadores', 'es_boss',
    ];

    /** Clase de desafío legible: 0.125 → "1/8", 0.25 → "1/4", 0.5 → "1/2", 10.00 → "10". */
    public function getCrTextoAttribute(): string
    {
        $cr = (float) $this->clase_de_desafio;

        return match (true) {
            $cr === 0.125 => '1/8',
            $cr === 0.25 => '1/4',
            $cr === 0.5 => '1/2',
            default => rtrim(rtrim(number_format($cr, 2, '.', ''), '0'), '.') ?: '0',
        };
    }

    /** Franja de peligro para colorear el CR: bajo, medio, alto o extremo. */
    public function getCrNivelAttribute(): string
    {
        $cr = (float) $this->clase_de_desafio;

        return match (true) {
            $cr <= 3 => 'bajo',
            $cr <= 7 => 'medio',
            $cr <= 12 => 'alto',
            default => 'extremo',
        };
    }

    /** Modificador de DES con signo ("+2", "-1"), usado como iniciativa. */
    public function getIniciativaAttribute(): string
    {
        $mod = (int) floor(($this->destreza - 10) / 2);

        return ($mod >= 0 ? '+' : '') . $mod;
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function campanas()
    {
        return $this->belongsToMany(Campana::class, 'campana_enemigo')
                    ->withPivot('visible_jugadores', 'notas_dm')
                    ->withTimestamps();
    }
}