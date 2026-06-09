<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dispositivo;

class DispositivoSeeder extends Seeder
{
    public function run(): void
    {
        $dispositivos = [
            [
                'nombre' => 'Lector Entrada Principal',
                'ubicacion' => 'Puerta principal',
                'ip' => '192.168.1.10',
                'activo' => 1,
            ],
            [
                'nombre' => 'Lector Salida Principal',
                'ubicacion' => 'Puerta salida',
                'ip' => '192.168.1.11',
                'activo' => 1,
            ],
            [
                'nombre' => 'Balon Baloncesto',
                'ubicacion' => 'Herramientas externas',
                'ip' => null,
                'activo' => 1,
            ],
            [
                'nombre' => 'Laptop HP 14-DQ5015LA',
                'ubicacion' => 'Herramientas externas',
                'ip' => null,
                'activo' => 1,
            ],
            [
                'nombre' => 'Carnet Web',
                'ubicacion' => 'Registro desde home',
                'ip' => '127.0.0.1',
                'activo' => 1,
            ],
        ];

        foreach ($dispositivos as $dispositivo) {
            Dispositivo::updateOrCreate(
                ['nombre' => $dispositivo['nombre']],
                $dispositivo
            );
        }
    }
}
