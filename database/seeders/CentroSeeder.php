<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Centro;

class CentroSeeder extends Seeder
{
    public function run(): void
    {
        Centro::insert([
            [
                'nombre' => 'Centro Principal',
                'persona_id' => 1, // 👈 Juan
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Centro Norte',
                'persona_id' => 2, // 👈 Ana
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}