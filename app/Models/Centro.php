<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $table = 'centros';

    protected $fillable = [
        'nombre'
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }

    public function fichas()
    {
        return $this->hasMany(Ficha::class);
    }
}