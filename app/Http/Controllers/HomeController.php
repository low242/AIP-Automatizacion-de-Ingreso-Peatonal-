<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Models\Credencial;
use App\Models\Dispositivo;
use App\Models\Persona;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(Request $request): View|JsonResponse
    {
        $data = $this->dashboardData($request);

        if ($this->wantsJson($request)) {
            return response()->json([
                'dashboard' => [
                    'entradasHoy' => $data['entradasHoy'],
                    'salidasHoy' => $data['salidasHoy'],
                    'ingresosGenero' => $data['ingresosGenero'],
                    'tipoPersonaLabels' => $data['tipoPersonaLabels'],
                    'tipoPersonaValores' => $data['tipoPersonaValores'],
                ],
                'html' => [
                    'historial' => view('partials.home.historial-rows', $data)->render(),
                    'actividadDispositivos' => view('partials.home.actividad-dispositivos', $data)->render(),
                    'carnets' => view('partials.home.carnet-rows', $data)->render(),
                    'dispositivosActivos' => view('partials.home.dispositivo-options', $data)->render(),
                ],
                'meta' => [
                    'carnetsResumen' => $data['carnetsActivos'] . ' carnets QR activos de ' . $data['personasActivas'] . ' personas activas',
                ],
            ]);
        }

        return view('home', $data);
    }

    private function dashboardData(Request $request): array
    {
        $today = now()->toDateString();

        $soloRegistrosPersonas = fn ($query) => $query->whereHas(
            'dispositivo',
            fn ($dispositivo) => $dispositivo->where('nombre', 'Carnet Web')
        );

        $entradasHoy = Acceso::whereDate('fecha_hora', $today)
            ->where('tipo', 'entrada')
            ->where('estado', 'permitido')
            ->tap($soloRegistrosPersonas)
            ->count();

        $salidasHoy = Acceso::whereDate('fecha_hora', $today)
            ->where('tipo', 'salida')
            ->where('estado', 'permitido')
            ->tap($soloRegistrosPersonas)
            ->count();

        $personasActivas = Persona::where('activo', true)->count();

        $columnaGenero = collect(['genero', 'sexo'])
            ->first(fn ($columna) => Schema::hasColumn('personas', $columna));

        $ingresosGenero = collect([
            'hombres' => 0,
            'mujeres' => 0,
            'otros' => 0,
        ]);

        if ($columnaGenero) {
            $ingresosGeneroRaw = Acceso::query()
                ->join('personas', 'accesos.persona_id', '=', 'personas.id')
                ->selectRaw("LOWER(personas.{$columnaGenero}) as genero, COUNT(*) as total")
                ->whereDate('accesos.fecha_hora', $today)
                ->where('accesos.tipo', 'entrada')
                ->where('accesos.estado', 'permitido')
                ->tap($soloRegistrosPersonas)
                ->groupBy('genero')
                ->pluck('total', 'genero');

            $ingresosGenero = collect([
                'hombres' => collect(['hombre', 'masculino', 'm', 'male'])
                    ->sum(fn ($genero) => (int) ($ingresosGeneroRaw[$genero] ?? 0)),
                'mujeres' => collect(['mujer', 'femenino', 'f', 'female'])
                    ->sum(fn ($genero) => (int) ($ingresosGeneroRaw[$genero] ?? 0)),
                'otros' => collect(['otro', 'otros', 'other'])
                    ->sum(fn ($genero) => (int) ($ingresosGeneroRaw[$genero] ?? 0)),
            ]);
        }

        $carnetsActivos = Credencial::where('tipo', 'qr')
            ->where('activa', true)
            ->count();

        $desde = now()->subDays(6)->startOfDay();
        $hasta = now()->endOfDay();
        $dias = collect(range(0, 6))->map(fn ($offset) => now()->subDays(6 - $offset));

        $movimientos = Acceso::query()
            ->selectRaw('DATE(fecha_hora) as fecha, tipo, COUNT(*) as total')
            ->whereBetween('fecha_hora', [$desde, $hasta])
            ->where('estado', 'permitido')
            ->tap($soloRegistrosPersonas)
            ->groupBy('fecha', 'tipo')
            ->get()
            ->groupBy(fn ($row) => $row->fecha . '-' . $row->tipo);

        $visitantes = Acceso::query()
            ->join('personas', 'accesos.persona_id', '=', 'personas.id')
            ->selectRaw('DATE(accesos.fecha_hora) as fecha, COUNT(*) as total')
            ->whereBetween('accesos.fecha_hora', [$desde, $hasta])
            ->where('accesos.estado', 'permitido')
            ->where('personas.tipo', 'visitante')
            ->tap($soloRegistrosPersonas)
            ->groupBy('fecha')
            ->pluck('total', 'fecha');

        $chartCategorias = $dias->map(fn ($date) => $date->format('d/m'))->values();
        $chartEntradas = $dias->map(function ($date) use ($movimientos) {
            return (int) optional($movimientos->get($date->toDateString() . '-entrada'))->first()?->total;
        })->values();
        $chartSalidas = $dias->map(function ($date) use ($movimientos) {
            return (int) optional($movimientos->get($date->toDateString() . '-salida'))->first()?->total;
        })->values();
        $chartVisitantes = $dias->map(fn ($date) => (int) ($visitantes[$date->toDateString()] ?? 0))->values();

        $tipoPersonaMap = [
            'Aprendiz' => ['aprendiz'],
            'Instructor' => ['instructor'],
            'Administrativo' => ['administrador', 'funcionario', 'administrativo'],
            'Visitante' => ['visitante'],
        ];
        $tiposPersona = Acceso::query()
            ->join('personas', 'accesos.persona_id', '=', 'personas.id')
            ->selectRaw("LOWER(COALESCE(personas.tipo, 'sin tipo')) as tipo_persona, COUNT(*) as total")
            ->whereDate('accesos.fecha_hora', $today)
            ->where('accesos.estado', 'permitido')
            ->tap($soloRegistrosPersonas)
            ->groupBy('tipo_persona')
            ->pluck('total', 'tipo_persona');

        $tipoPersonaValores = collect($tipoPersonaMap)
            ->map(fn ($tipos) => collect($tipos)->sum(fn ($tipo) => (int) ($tiposPersona[$tipo] ?? 0)))
            ->values();

        $traficoCentros = Persona::query()
            ->leftJoin('fichas', 'personas.ficha_id', '=', 'fichas.id')
            ->leftJoin('centros', 'fichas.centro_id', '=', 'centros.id')
            ->selectRaw("COALESCE(personas.centro, centros.nombre, 'Sin centro') as centro_nombre, COUNT(*) as total")
            ->where('personas.activo', true)
            ->groupBy('centro_nombre')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        $historial = Acceso::with(['persona.fichaRelacion.centro', 'dispositivo'])
            ->tap($soloRegistrosPersonas)
            ->latest('fecha_hora')
            ->limit(12)
            ->get();

        $actividadDispositivos = Acceso::with(['persona', 'dispositivo'])
            ->where('estado', 'permitido')
            ->whereHas('dispositivo', fn ($query) => $query->where('nombre', '<>', 'Carnet Web'))
            ->latest('fecha_hora')
            ->limit(4)
            ->get();

        $dispositivosActivos = Dispositivo::where('activo', true)
            ->orderBy('nombre')
            ->get();

        $personasCarnet = Persona::with([
            'fichaRelacion.centro',
            'credenciales' => fn ($query) => $query
                ->where('tipo', 'qr')
                ->where('activa', true),
        ])
            ->where('activo', true)
            ->orderBy('nombres')
            ->limit(10)
            ->get();

        return [
            'user' => $request->user(),
            'entradasHoy' => $entradasHoy,
            'salidasHoy' => $salidasHoy,
            'personasActivas' => $personasActivas,
            'ingresosGenero' => $ingresosGenero,
            'carnetsActivos' => $carnetsActivos,
            'tiposPersona' => $tiposPersona,
            'tipoPersonaLabels' => collect(array_keys($tipoPersonaMap))->values(),
            'tipoPersonaValores' => $tipoPersonaValores,
            'chartCategorias' => $chartCategorias,
            'chartEntradas' => $chartEntradas,
            'chartSalidas' => $chartSalidas,
            'chartVisitantes' => $chartVisitantes,
            'traficoCentroLabels' => $traficoCentros->pluck('centro_nombre')->values(),
            'traficoCentroValores' => $traficoCentros->pluck('total')->map(fn ($total) => (int) $total)->values(),
            'historial' => $historial,
            'actividadDispositivos' => $actividadDispositivos,
            'dispositivosActivos' => $dispositivosActivos,
            'personasCarnet' => $personasCarnet,
        ];
    }

    private function wantsJson(Request $request): bool
    {
        return $request->expectsJson() || $request->ajax();
    }
}
