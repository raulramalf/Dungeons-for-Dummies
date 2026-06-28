<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'contenido',
        'etiquetas',
    ];

    protected $casts = [
        'etiquetas' => 'array',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    // Alias para compatibilidad
    public function user()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Método para contar likes
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    // Método para verificar si un usuario ha dado like
    public function isLikedBy($usuarioId)
    {
        return $this->likes()->where('user_id', $usuarioId)->exists();
    }
}