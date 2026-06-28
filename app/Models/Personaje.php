<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personaje extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'personajes';

    protected $fillable = [
        'usuario_id',
        'raza_id',
        'clase_id',
        'subclase_id',
        'trasfondo_id',
        'nombre',
        'alineamiento',
        'nivel',
        'experiencia',
        'avatar',
        'historia',
        'activo',
    ];

    // Relaciones
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function raza()
    {
        return $this->belongsTo(Raza::class);
    }

    public function clase()
    {
        return $this->belongsTo(Clase::class);
    }

    public function subclase()
    {
        return $this->belongsTo(Subclase::class);
    }

    public function trasfondo()
    {
        return $this->belongsTo(Trasfondo::class);
    }

    public function estadisticas()
    {
        return $this->hasOne(Estadistica::class);
    }

    public function equipo()
    {
        return $this->hasMany(Equipo::class);
    }

    public function trucos()
    {
        return $this->hasMany(Truco::class);
    }

    public function campanas()
    {
        return $this->belongsToMany(Campana::class, 'personaje_campana')
                    ->withPivot('estado', 'fecha_ingreso', 'fecha_salida', 'notas')
                    ->withTimestamps();
    }

    // Método para obtener la URL del avatar
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->nombre) . '&background=B30303&color=fff&size=200';
    }
}