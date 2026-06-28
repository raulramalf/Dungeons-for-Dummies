<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'likes';

    protected $fillable = [
        'user_id',
        'likeable_id',
        'likeable_type',
    ];

    // Relación polimórfica
    public function likeable()
    {
        return $this->morphTo();
    }

    // Usuario que dio el like
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}