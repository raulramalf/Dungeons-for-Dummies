<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $table = 'usuarios';

   protected $fillable = [
        'nombre',
        'email',
        'email_verified_at',
        'password',
        'rol',
        'avatar',
        'ultimo_acceso',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'ultimo_acceso' => 'datetime',
        ];
    }

<<<<<<< HEAD
    // Relaciones
    public function personajes()
    {
        return $this->hasMany(Personaje::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function perfil()
    {
        return $this->hasOne(Perfil::class);
    }

    public function campanasComoDM()
    {
        return $this->hasMany(Campana::class, 'dungeon_master_id');
    }
=======
    public function personajes()
{
    return $this->hasMany(\App\Models\Personaje::class, 'usuario_id');
}

public function campanas()
{
    return $this->hasMany(\App\Models\Campana::class, 'dungeon_master_id');
}

>>>>>>> origin/feature/perfil-campanyas-enemigos
}