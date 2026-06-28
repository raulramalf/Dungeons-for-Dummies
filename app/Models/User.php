<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'ultimo_acceso'     => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function personajes()
{
    return $this->hasMany(\App\Models\Personaje::class, 'usuario_id');
}

public function campanas()
{
    return $this->hasMany(\App\Models\Campana::class, 'dungeon_master_id');
}

}