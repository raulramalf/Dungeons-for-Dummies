<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Personaje extends Model
{
    use SoftDeletes;

    protected $table = 'personajes';

    protected $fillable = [
        'usuario_id', 'raza_id', 'clase_id', 'subclase_id',
        'trasfondo_id', 'nombre', 'alineamiento',
        'nivel', 'experiencia', 'avatar', 'activo',
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