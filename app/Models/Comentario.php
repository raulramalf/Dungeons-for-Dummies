<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'contenido',
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

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    // Relación de respuestas (comentarios anidados)
    public function parent()
    {
        return $this->belongsTo(Comentario::class, 'parent_id');
    }

    public function respuestas()
    {
        return $this->hasMany(Comentario::class, 'parent_id')->with('respuestas');
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