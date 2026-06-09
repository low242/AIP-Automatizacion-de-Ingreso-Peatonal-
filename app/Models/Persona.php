<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Persona extends Model
{
    use SoftDeletes;

    protected $table = 'personas';

    protected $primaryKey = 'id';

    protected $fillable = [
        'tipo',
        'genero',
        'centro',
        'regional',
        'tipo_sangre',
        'documento',
        'nombres',
        'apellidos',
        'telefono',
        'ficha',
        'foto',
        'ficha_id',
        'activo',
        'visitante_expira_en',
        'visitante_registrado_por'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'visitante_expira_en' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |-------------------------------------------------------------------------- 
    */

    public function credenciales()
    {
        return $this->hasMany(Credencial::class);
    }

    public function accesos()
    {
        return $this->hasMany(Acceso::class);
    }

    public function visitanteRegistradoPor()
    {
        return $this->belongsTo(User::class, 'visitante_registrado_por');
    }

    // 🔗 Persona pertenece a una ficha
    public function ficha()
    {
        return $this->belongsTo(Ficha::class);
    }

    public function fichaRelacion()
    {
        return $this->belongsTo(Ficha::class, 'ficha_id');
    }

    // 🔗 Persona pertenece a un centro
    public function getNombreCompletoAttribute(): string
    {
        return trim($this->nombres . ' ' . $this->apellidos);
    }

    public function getFichaVisibleAttribute(): string
    {
        return $this->fichaRelacion?->ficha
            ?? $this->fichaRelacion?->numero
            ?? $this->getAttribute('ficha')
            ?? 'N/A';
    }

    public function getCentroVisibleAttribute(): string
    {
        return $this->centro
            ?? $this->fichaRelacion?->centro?->nombre
            ?? 'N/A';
    }

    public function centros()
    {
        return $this->hasMany(Centro::class);
    }
}
