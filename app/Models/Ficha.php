<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $fillable = [
        'centro_id',
        'ficha'
    ];

    public function centro()
    {
        return $this->belongsTo(Centro::class);
    }

    public function personas()
    {
        return $this->hasMany(Persona::class);
    }
}
