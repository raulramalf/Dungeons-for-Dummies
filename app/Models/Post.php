<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['user_id', 'contenido'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function comentarios() {
        return $this->hasMany(Comentario::class)->latest();
    }

    public function likes() {
        return $this->morphMany(Like::class, 'likeable');
    }
}