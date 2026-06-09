<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistroController;  
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarnetController;
use App\Http\Controllers\EmergencyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\UserController;
use App\Models\Persona;

Route::get('/', function () {
    return redirect()->route('login');
});



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\WebAuthController;

// Rutas de autenticación web
Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login'])->name('login.attempt');
});

Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Grupo de rutas protegidas (requieren autenticación)
Route::middleware('auth')->group(function () {
    Route::get('/home', HomeController::class)->name('home');
    Route::get('/emergencia/personas', [EmergencyController::class, 'personas'])->name('emergencia.personas');

    Route::resource('users', UserController::class);

    Route::get('/tables-data', function () {
        $personas = Persona::with('fichaRelacion.centro')
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get();

        return view('tables-data', compact('personas'));
    })->name('tables-data');

    Route::get('/carnets', [CarnetController::class, 'index'])->name('carnets.index');
    Route::post('/carnets/registrar', [CarnetController::class, 'registrar'])->name('carnets.registrar');
    Route::post('/dispositivos', [CarnetController::class, 'crearDispositivo'])->name('dispositivos.store');
    Route::post('/dispositivos/registrar', [CarnetController::class, 'registrarDispositivo'])->name('dispositivos.registrar');
    Route::get('/carnets/lector', [CarnetController::class, 'lector'])->name('carnets.lector');
    Route::get('/carnets/validar', [CarnetController::class, 'validar'])->name('carnets.validar');
    Route::get('/carnets/pdf/todos', [CarnetController::class, 'downloadAll'])->name('carnets.pdf.all');
    Route::get('/carnets/{persona}/pdf', [CarnetController::class, 'download'])->name('carnets.pdf.download');
    Route::get('/carnets/{persona}', [CarnetController::class, 'show'])->name('carnets.show');
    Route::post('/carnets/{persona}/generar', [CarnetController::class, 'generate'])->name('carnets.generate');
    Route::resource('visitantes', VisitanteController::class)
        ->except(['create', 'show'])
        ->middleware('role:superadmin,admin,vigilante');

    Route::get('/perfil', [ProfileController::class, 'show'])->name('perfil');
    Route::put('/perfil', [ProfileController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/password', [ProfileController::class, 'updatePassword'])->name('perfil.password');
    Route::get('/reportes/registros-hoy', [ReporteController::class, 'registrosHoy'])
        ->middleware('role:superadmin,admin')
        ->name('reportes.registros-hoy');

    Route::resource('registros', RegistroController::class);  // Esto también queda protegido
});
