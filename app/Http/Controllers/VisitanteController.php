<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Models\Dispositivo;
use App\Models\Persona;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class VisitanteController extends Controller
{
    private const VIGENCIA_HORAS = 24;

    public function index(Request $request): View
    {
        $this->eliminarVisitantesExpirados();

        $search = trim((string) $request->query('q', ''));

        $visitantes = Persona::with('visitanteRegistradoPor')
            ->where('tipo', 'visitante')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('documento', 'like', "%{$search}%")
                        ->orWhere('nombres', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%")
                        ->orWhere('telefono', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('visitantes.index', compact('visitantes', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $documento = trim($data['documento']);
        $existing = Persona::withTrashed()->where('documento', $documento)->first();

        if ($existing && !$existing->trashed() && $existing->tipo !== 'visitante') {
            return back()
                ->withErrors(['documento' => 'Este documento ya pertenece a una persona registrada.'])
                ->withInput();
        }

        $payload = $this->visitorPayload($data) + [
            'visitante_expira_en' => now()->addHours(self::VIGENCIA_HORAS),
            'visitante_registrado_por' => $request->user()?->id,
        ];

        if ($existing) {
            if ($existing->trashed()) {
                $existing->restore();
            }

            $existing->forceFill($payload);
            $existing->created_at = now();
            $existing->updated_at = now();
            $existing->save();
            $persona = $existing;
        } else {
            $persona = Persona::create($payload);
        }

        $this->registrarEntradaVisitante($persona, $request);

        return redirect()
            ->route('visitantes.index')
            ->with('status', 'Visitante registrado por 24 horas y entrada registrada.');
    }

    public function edit(Persona $visitante): View
    {
        $this->ensureVisitante($visitante);

        return view('visitantes.edit', compact('visitante'));
    }

    public function update(Request $request, Persona $visitante): RedirectResponse
    {
        $this->ensureVisitante($visitante);

        $data = $this->validatedData($request, $visitante);
        $duplicate = Persona::withTrashed()
            ->where('documento', trim($data['documento']))
            ->where('id', '!=', $visitante->id)
            ->first();

        if ($duplicate) {
            return back()
                ->withErrors(['documento' => 'Este documento ya está registrado.'])
                ->withInput();
        }

        $visitante->update($this->visitorPayload($data));

        return redirect()
            ->route('visitantes.index')
            ->with('status', 'Visitante actualizado.');
    }

    public function destroy(Persona $visitante): RedirectResponse
    {
        $this->ensureVisitante($visitante);
        $visitante->delete();

        return redirect()
            ->route('visitantes.index')
            ->with('status', 'Visitante eliminado.');
    }

    private function validatedData(Request $request, ?Persona $visitante = null): array
    {
        return $request->validate([
            'documento' => [
                'required',
                'string',
                'max:50',
                Rule::unique('personas', 'documento')
                    ->ignore($visitante?->id)
                    ->whereNull('deleted_at'),
            ],
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'genero' => ['nullable', Rule::in(['masculino', 'femenino', 'otro'])],
            'tipo_sangre' => ['nullable', 'string', 'max:3'],
            'centro' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:2048'],
            'existing_foto' => ['nullable', 'string', 'max:255'],
        ]);
    }

    private function visitorPayload(array $data): array
    {
        return [
            'tipo' => 'visitante',
            'documento' => trim($data['documento']),
            'nombres' => trim($data['nombres']),
            'apellidos' => trim($data['apellidos']),
            'telefono' => $data['telefono'] ? trim($data['telefono']) : null,
            'genero' => $data['genero'] ?? null,
            'tipo_sangre' => $data['tipo_sangre'] ? strtoupper(trim($data['tipo_sangre'])) : null,
            'centro' => $data['centro'] ? trim($data['centro']) : null,
            'foto' => $this->resolveVisitorPhoto($data),
            'ficha' => null,
            'ficha_id' => null,
            'activo' => true,
        ];
    }

    private function resolveVisitorPhoto(array $data): ?string
    {
        if (!empty($data['foto']) && $data['foto'] instanceof UploadedFile) {
            return $this->storeVisitorPhoto($data['foto'], $data['existing_foto'] ?? null);
        }

        if (is_string($data['foto']) && trim($data['foto']) !== '') {
            return trim($data['foto']);
        }

        return $data['existing_foto'] ?? null;
    }

    private function storeVisitorPhoto(UploadedFile $file, ?string $existingFoto = null): string
    {
        $photosPath = public_path('assets/img/visitantes');
        File::ensureDirectoryExists($photosPath);

        if ($existingFoto && str_starts_with($existingFoto, 'assets/img/visitantes/')) {
            File::delete(public_path($existingFoto));
        }

        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move($photosPath, $filename);

        return 'assets/img/visitantes/' . $filename;
    }

    private function registrarEntradaVisitante(Persona $persona, Request $request): void
    {
        $dispositivo = Dispositivo::firstOrCreate(
            ['nombre' => 'Carnet Web'],
            [
                'ubicacion' => 'Registro de visitantes',
                'ip' => $request->ip(),
                'activo' => true,
            ]
        );

        $ultimoAcceso = Acceso::where('persona_id', $persona->id)
            ->where('dispositivo_id', $dispositivo->id)
            ->whereDate('fecha_hora', now()->toDateString())
            ->orderBy('fecha_hora', 'desc')
            ->first();

        if ($ultimoAcceso && $ultimoAcceso->tipo === 'entrada') {
            return;
        }

        $permitido = (bool) $persona->activo;

        Acceso::create([
            'persona_id' => $persona->id,
            'dispositivo_id' => $dispositivo->id,
            'autorizado_por' => $request->user()?->id,
            'tipo' => 'entrada',
            'estado' => $permitido ? 'permitido' : 'denegado',
            'motivo_denegacion' => $permitido ? null : 'Persona inactiva',
            'fecha_hora' => now(),
        ]);
    }

    private function ensureVisitante(Persona $visitante): void
    {
        abort_if($visitante->tipo !== 'visitante', 404);
    }

    private function eliminarVisitantesExpirados(): void
    {
        Persona::where('tipo', 'visitante')
            ->whereNotNull('visitante_expira_en')
            ->where('visitante_expira_en', '<=', now())
            ->delete();
    }
}
