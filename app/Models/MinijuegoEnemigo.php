<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinijuegoEnemigo extends Model
{
    use HasFactory;

    protected $fillable = [
        "nombre",
        "titulo",
        "descripcion",
        "imagen",
        "arte",
        "escenario",
        "vida_max",
        "tipo",
        "rondas",
        "activo",
    ];

    protected function casts(): array
    {
        return [
            "rondas" => "array",
            "activo" => "boolean",
        ];
    }

    public function totalRondas(): int
    {
        return count($this->rondas ?? []);
    }
}
