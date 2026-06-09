<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ficha;
use App\Models\Persona;

class FichaSeeder extends Seeder
{
    public function run(): void
    {
        Ficha::insert([
            [
                'centro_id' => 1,
                'ficha' => 'F-001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'centro_id' => 2,
                'ficha' => 'F-002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 👇 Ahora actualizamos ficha_id en personas
        Persona::where('documento', '12345678')->update(['ficha_id' => 1]);
        Persona::where('documento', '87654321')->update(['ficha_id' => 2]);
    }
}