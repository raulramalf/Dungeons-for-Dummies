<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Personaje extends Model
{
    // Esta línea es obligatoria para que el formulario pueda guardar estos datos
    protected $fillable = [
        'usuario_id',
        'nombre',
        'nivel',
        'raza_id',
        'clase_id',
        'imagen_url',
        'fuerza',
        'destreza',
        'constitucion',
        'inteligencia',
        'sabiduria',
        'carisma'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function usuario()   { return $this->belongsTo(User::class, 'usuario_id'); }
    public function raza()      { return $this->belongsTo(Raza::class); }
    public function clase()     { return $this->belongsTo(Clase::class); }
    public function subclase()  { return $this->belongsTo(Subclase::class); }
    public function trasfondo() { return $this->belongsTo(Trasfondo::class); }
    public function estadisticas() { return $this->hasOne(Estadistica::class); }
    public function equipo()    { return $this->hasMany(Equipo::class); }
    public function trucos()    { return $this->hasMany(Truco::class); }
    public function campanas()  { return $this->belongsToMany(Campana::class, 'personaje_campana'); }
}