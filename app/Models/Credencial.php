<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credencial extends Model
{

    protected $table = 'credenciales';

    protected $primaryKey = 'id';

    protected $fillable = [
        'persona_id',
        'tipo',
        'valor',
        'activa'
    ];

    protected $casts = [
        'activa' => 'boolean'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Una credencial pertenece a una persona
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

}
