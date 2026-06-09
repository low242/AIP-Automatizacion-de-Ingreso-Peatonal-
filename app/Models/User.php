<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'foto',
        'compania',
        'ciudad',
        'telefono',
        'estado'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed'
    ];

    public function getProfilePhotoUrlAttribute(): string
    {
        $foto = trim((string) $this->foto);

        if ($foto === '') {
            return asset('assets/img/profile-img.png');
        }

        if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
            return $foto;
        }

        if (str_starts_with($foto, '/')) {
            return $foto;
        }

        if (str_starts_with($foto, 'assets/') || str_starts_with($foto, 'storage/')) {
            return asset($foto);
        }

        if (str_starts_with($foto, 'fotos_usuarios/')) {
            return asset('storage/' . $foto);
        }

        return asset($foto);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'superadmin' => 'Super Administrador',
            'admin' => 'Administrador',
            'vigilante' => 'Vigilante',
            default => $this->role ? ucfirst((string) $this->role) : 'Sin cargo',
        };
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    // Accesos que autorizó el usuario
    public function accesosAutorizados()
    {
        return $this->hasMany(Acceso::class, 'autorizado_por');
    }

    // Logs del sistema
    public function logs()
    {
        return $this->hasMany(LogSistema::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function esSuperAdmin()
    {
        return $this->role === 'superadmin';
    }

    public function esAdmin()
    {
        return $this->role === 'admin';
    }

    public function esVigilante()
    {
        return $this->role === 'vigilante';
    }

}
