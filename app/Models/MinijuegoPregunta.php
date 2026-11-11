<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinijuegoPregunta extends Model
{
    use HasFactory;

    protected $fillable = ["pregunta", "opciones", "categoria", "explicacion", "dificultad", "activo"];

    protected function casts(): array
    {
        return [
            "opciones" => "array",
            "activo" => "boolean",
        ];
    }
}