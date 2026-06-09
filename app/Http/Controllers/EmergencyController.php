<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EmergencyController extends Controller
{
    public function personas(Request $request): JsonResponse
    {
        $search = trim((string) $request->query('q', ''));
        $searchableColumns = collect([
            'documento',
            'nombres',
            'apellidos',
            'telefono',
            'ficha',
            'centro',
            'tipo',
            'genero',
            'tipo_sangre',
            'regional',
        ])->filter(fn ($column) => Schema::hasColumn('personas', $column))->values();
        $fichaColumns = collect(['ficha', 'numero'])
            ->filter(fn ($column) => Schema::hasColumn('fichas', $column))
            ->values();

        $personas = Persona::with([
            'fichaRelacion.centro',
            'accesos' => fn ($query) => $query
                ->with('dispositivo')
                ->latest('fecha_hora'),
        ])
            ->when($search !== '', function ($query) use ($search, $searchableColumns, $fichaColumns) {
                $query->where(function ($inner) use ($search, $searchableColumns, $fichaColumns) {
                    foreach ($searchableColumns as $column) {
                        $inner->orWhere($column, 'like', "%{$search}%");
                    }

                    if ($fichaColumns->isNotEmpty()) {
                        $inner->orWhereHas('fichaRelacion', function ($ficha) use ($search, $fichaColumns) {
                            foreach ($fichaColumns as $column) {
                                $ficha->orWhere($column, 'like', "%{$search}%");
                            }
                        });
                    }

                    $inner->orWhereHas('fichaRelacion.centro', function ($centro) use ($search) {
                        $centro->where('nombre', 'like', "%{$search}%");
                    });
                });
            })
            ->when($search === '', fn ($query) => $query->where('activo', true))
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->limit(8)
            ->get()
            ->map(fn (Persona $persona) => [
                'id' => $persona->id,
                'nombre' => $persona->nombre_completo ?: 'Sin nombre',
                'documento' => $persona->documento ?? 'N/A',
                'tipo' => $this->value($persona->tipo),
                'genero' => $this->value($persona->genero),
                'telefono' => $persona->telefono ?: 'N/A',
                'tipo_sangre' => $persona->tipo_sangre ?: 'N/A',
                'foto' => $this->personaPhotoUrl($persona),
                'ficha' => $persona->ficha_visible,
                'centro' => $persona->centro_visible,
                'estado' => $persona->activo ? 'Activo' : 'Inactivo',
                'estado_clase' => $persona->activo ? 'success' : 'danger',
                'carnet_url' => route('carnets.show', $persona),
                'ultimos_accesos' => $persona->accesos->take(3)->map(fn ($acceso) => [
                    'tipo' => $this->value($acceso->tipo),
                    'estado' => $this->value($acceso->estado),
                    'fecha' => optional($acceso->fecha_hora)->format('d/m/Y H:i') ?? 'N/A',
                    'dispositivo' => $acceso->dispositivo?->nombre ?? 'N/A',
                ])->values(),
            ]);

        return response()->json([
            'personas' => $personas,
        ]);
    }

    private function value(?string $value): string
    {
        return $value ? ucfirst($value) : 'N/A';
    }

    private function personaPhotoUrl(Persona $persona): string
    {
        $foto = trim((string) $persona->getAttribute('foto'));

        if ($foto !== '') {
            if (str_starts_with($foto, 'http://') || str_starts_with($foto, 'https://')) {
                return $foto;
            }

            if (str_starts_with($foto, '/')) {
                return $foto;
            }

            if (str_starts_with($foto, 'assets/') || str_starts_with($foto, 'storage/')) {
                return asset($foto);
            }

            if (file_exists(public_path('assets/img/fotos/' . $foto))) {
                return asset('assets/img/fotos/' . $foto);
            }

            if (file_exists(public_path('assets/img/' . $foto))) {
                return asset('assets/img/' . $foto);
            }

            return asset('assets/img/' . ltrim($foto, '/'));
        }

        foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
            $path = 'assets/img/fotos/' . $persona->documento . '.' . $extension;

            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        return asset('assets/img/profile-img.png');
    }
}
