<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinijuegoIntento extends Model
{
    use HasFactory;

    protected $fillable = [
        "usuario_id",
        "minijuego_reto_id",
        "ronda_actual",
        "vida_restante",
        "opciones_elegidas",
        "estado",
        "completado_en",
        "pregunta_actual_id",
        "pregunta_actual_opciones",
    ];

    protected function casts(): array
    {
        return [
            "opciones_elegidas" => "array",
            "pregunta_actual_opciones" => "array",
            "completado_en" => "datetime",
        ];
    }

    public function reto()
    {
        return $this->belongsTo(MinijuegoReto::class, "minijuego_reto_id");
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, "usuario_id");
    }

    public function preguntaActual()
    {
        return $this->belongsTo(MinijuegoPregunta::class, "pregunta_actual_id");
    }
}