<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Acceso;

class AccesoSeeder extends Seeder
{
    public function run(): void
    {
        Acceso::insert([
            [
                'persona_id' => 1,
                'dispositivo_id' => 1,
                'autorizado_por' => 1,
                'tipo' => 'entrada',
                'estado' => 'permitido',
                'motivo_denegacion' => null,
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'persona_id' => 2,
                'dispositivo_id' => 2,
                'autorizado_por' => 1,
                'tipo' => 'salida',
                'estado' => 'denegado',
                'motivo_denegacion' => 'Credencial vencida',
                'fecha_hora' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}