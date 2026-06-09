<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Persona;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('visitantes:limpiar-expirados', function () {
    $total = Persona::where('tipo', 'visitante')
        ->whereNotNull('visitante_expira_en')
        ->where('visitante_expira_en', '<=', now())
        ->delete();

    $this->info("Visitantes expirados eliminados: {$total}");
})->purpose('Eliminar visitantes vencidos');

Schedule::command('visitantes:limpiar-expirados')->dailyAt('12:00');
