<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PersonaSeeder::class,     // 1️⃣ primero sin ficha_id
            CentroSeeder::class,      // 2️⃣ necesita personas
            FichaSeeder::class,       // 3️⃣ necesita centros, actualiza ficha_id
            DispositivoSeeder::class, // 4️⃣ independiente
            AccesoSeeder::class,      // 5️⃣ necesita personas y dispositivos
        ]);
    }
}
