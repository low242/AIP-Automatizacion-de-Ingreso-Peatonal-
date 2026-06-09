<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@aip.test'],
            [
                'name' => 'Administrador AIP',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'estado' => 'activo',
                'compania' => 'AIP',
                'ciudad' => 'Bogota',
                'telefono' => null,
                'foto' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}
