<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispositivo extends Model
{
    protected $table = 'dispositivos';

    protected $fillable = [
        'nombre',
        'ubicacion',
        'ip',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function accesos()
    {
        return $this->hasMany(Acceso::class);
    }
}