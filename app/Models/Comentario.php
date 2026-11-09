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

    /**
     * Total de likes. Usa `likes_count` si la consulta lo trajo con withCount()
     * (evita un COUNT(*) por fila); si no, cae a la consulta clásica.
     */
    public function getLikesCountAttribute()
    {
        return $this->attributes['likes_count'] ?? $this->likes()->count();
    }

    /**
     * ¿Le ha dado like este usuario? Usa `liked_by_me` (withExists) o la
     * relación `likes` si ya está cargada; solo consulta como último recurso.
     */
    public function isLikedBy($usuarioId): bool
    {
        if (array_key_exists('liked_by_me', $this->attributes)) {
            return (bool) $this->attributes['liked_by_me'];
        }

        if ($this->relationLoaded('likes')) {
            return $this->likes->contains('user_id', $usuarioId);
        }

        return $this->likes()->where('user_id', $usuarioId)->exists();
    }
}
