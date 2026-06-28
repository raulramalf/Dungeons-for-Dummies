<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estadistica extends Model
{
    protected $table = 'estadisticas';

    protected $fillable = [
        'personaje_id', 'fuerza', 'destreza', 'constitucion',
        'inteligencia', 'sabiduria', 'carisma',
        'pg_maximos', 'pg_actuales', 'pg_temporales',
        'clase_de_armadura', 'iniciativa', 'velocidad', 'bonus_competencia',
        'monedas_cobre', 'monedas_plata', 'monedas_electrum',
        'monedas_oro', 'monedas_platino', 'inspiracion',
        'dados_golpe_disponibles', 'exitos_muerte', 'fallos_muerte',
    ];

    protected $casts = [
        'inspiracion' => 'boolean',
    ];

    public function personaje()
    {
        return $this->belongsTo(Personaje::class);
    }
}