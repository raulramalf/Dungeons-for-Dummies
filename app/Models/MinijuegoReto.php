<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinijuegoReto extends Model
{
    use HasFactory;

    protected $fillable = ["fecha", "minijuego_enemigo_id"];

    protected function casts(): array
    {
        return ["fecha" => "datetime"];
    }

    public function enemigo()
    {
        return $this->belongsTo(
            MinijuegoEnemigo::class,
            "minijuego_enemigo_id",
        );
    }

    public function intentos()
    {
        return $this->hasMany(MinijuegoIntento::class);
    }
}
