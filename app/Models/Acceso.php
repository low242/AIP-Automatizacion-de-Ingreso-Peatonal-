<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acceso extends Model
{

    protected $table = 'accesos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'persona_id',
        'dispositivo_id',
        'autorizado_por',
        'tipo',
        'estado',
        'motivo_denegacion',
        'fecha_hora'
    ];

    protected $casts = [
        'fecha_hora' => 'datetime'
    ];

    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // El acceso pertenece a una persona
    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    // El acceso pertenece a un dispositivo
    public function dispositivo()
    {
        return $this->belongsTo(Dispositivo::class);
    }

    // Quien autorizó el acceso
    public function autorizadoPor()
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

}