<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Models\Credencial;
use App\Models\Dispositivo;
use App\Models\Persona;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CarnetController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $personas = Persona::with([
            'fichaRelacion.centro',
            'credenciales' => function ($query) {
                $query->where('tipo', 'qr')->where('activa', true);
            }
        ])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('documento', 'like', "%{$search}%")
                        ->orWhere('nombres', 'like', "%{$search}%")
                        ->orWhere('apellidos', 'like', "%{$search}%")
                        ->orWhere('ficha', 'like', "%{$search}%")
                        ->orWhere('centro', 'like', "%{$search}%")
                        ->orWhere('regional', 'like', "%{$search}%");
                });
            })
            ->orderBy('nombres')
            ->paginate(12)
            ->withQueryString();

        return view('carnets.index', compact('personas', 'search'));
    }

    public function show(Persona $persona): View
    {
        $persona->load(['fichaRelacion.centro', 'credenciales']);
        $credencial = $this->qrCredential($persona);
        $photoUrl = $this->personaPhotoUrl($persona);

        return view('carnets.show', compact('persona', 'credencial', 'photoUrl'));
    }

    public function generate(Persona $persona): RedirectResponse
    {
        $this->qrCredential($persona, true);

        return redirect()
            ->route('carnets.show', $persona)
            ->with('status', 'Carnet generado correctamente.');
    }

    public function download(Persona $persona)
    {
        $persona->load(['fichaRelacion.centro']);
        $credencial = $this->qrCredential($persona);
        $photoUrl = $this->personaPhotoUrl($persona);
        $canRenderPngImages = extension_loaded('gd');
        $photoDataUri = $canRenderPngImages ? $this->imageDataUri($photoUrl) : null;
        $qrDataUri = $this->qrDataUri($credencial->valor);
        $barcodeDataUri = $this->barcodeDataUri($persona->documento ?: (string) $persona->id);

        $pdf = Pdf::loadView('carnets.pdf.single', compact('persona', 'credencial', 'photoDataUri', 'qrDataUri', 'barcodeDataUri', 'canRenderPngImages'));

        return $pdf->setPaper('a4', 'portrait')
            ->download(sprintf('carnet-%s.pdf', $persona->documento ?: $persona->id));
    }

    public function downloadAll()
    {
        $personas = Persona::with('fichaRelacion.centro')
            ->orderBy('nombres')
            ->orderBy('apellidos')
            ->get();

        $personas = $personas->map(function (Persona $persona) {
            $persona->loadMissing('credenciales');
            $persona->credencial = $this->qrCredential($persona);
            $persona->photoUrl = $this->personaPhotoUrl($persona);
            $persona->photoDataUri = extension_loaded('gd') ? $this->imageDataUri($persona->photoUrl) : null;
            $persona->qrDataUri = $this->qrDataUri($persona->credencial->valor);
            $persona->barcodeDataUri = $this->barcodeDataUri($persona->documento ?: (string) $persona->id);
            return $persona;
        });

        $pdf = Pdf::loadView('carnets.pdf.all', compact('personas'));

        return $pdf->setPaper('a4', 'portrait')
            ->download('carnets-todos.pdf');
    }

    public function lector(): View
    {
        return view('carnets.lector');
    }

    public function registrar(Request $request)
    {
        $data = $request->validate([
            'codigo' => ['required', 'string', 'max:500'],
            'tipo' => ['required', 'in:entrada,salida'],
        ]);

        $codigo = $this->extractCode($data['codigo']);

        $credencial = Credencial::with('persona')
            ->where('tipo', 'qr')
            ->where('valor', $codigo)
            ->first();

        $persona = $credencial?->persona;

        if (!$persona) {
            $persona = Persona::where('documento', $codigo)->first();
        }

        if (!$persona) {
            return $this->registroError($request, 'No existe una persona registrada con ese documento.');
        }

        $dispositivo = Dispositivo::firstOrCreate(
            ['nombre' => 'Carnet Web'],
            ['ubicacion' => 'Registro desde home', 'ip' => $request->ip(), 'activo' => true]
        );

        $hoy = now()->toDateString();

        $ultimoAcceso = Acceso::where('persona_id', $persona->id)
            ->where('dispositivo_id', $dispositivo->id)
            ->whereDate('fecha_hora', $hoy)
            ->orderBy('fecha_hora', 'desc')
            ->first();

        $entradasHoy = Acceso::where('persona_id', $persona->id)
            ->where('dispositivo_id', $dispositivo->id)
            ->whereDate('fecha_hora', $hoy)
            ->where('tipo', 'entrada')
            ->count();

        $salidasHoy = Acceso::where('persona_id', $persona->id)
            ->where('dispositivo_id', $dispositivo->id)
            ->whereDate('fecha_hora', $hoy)
            ->where('tipo', 'salida')
            ->count();

        if ($data['tipo'] === 'entrada') {
            if ($ultimoAcceso && $ultimoAcceso->tipo === 'entrada') {
                return $this->registroError($request, 'Ya tiene una entrada activa. Debe registrar la salida antes de una nueva entrada.');
            }

            // if ($entradasHoy >= 3) {
            //     return $this->registroError($request, 'Ha alcanzado el limite maximo de entradas permitidas por dia (3).');
            // }
        }

        if ($data['tipo'] === 'salida') {
            if (!$ultimoAcceso || $ultimoAcceso->tipo !== 'entrada') {
                return $this->registroError($request, 'No hay una entrada registrada para registrar la salida.');
            }

            if ($salidasHoy >= $entradasHoy) {
                return $this->registroError($request, 'Ya registro todas las salidas correspondientes a sus entradas.');
            }
        }

        $permitido = (bool) $persona->activo && (!$credencial || (bool) $credencial->activa);
        $fechaHora = now();

        Acceso::create([
            'persona_id' => $persona->id,
            'dispositivo_id' => $dispositivo->id,
            'autorizado_por' => $request->user()?->id,
            'tipo' => $data['tipo'],
            'estado' => $permitido ? 'permitido' : 'denegado',
            'motivo_denegacion' => $permitido ? null : 'Persona inactiva',
            'fecha_hora' => $fechaHora,
        ]);

        $mensajeAccion = $data['tipo'] === 'entrada' ? 'Entrada' : 'Salida';
        $modalData = $this->accessModalData($persona, $data['tipo'], $mensajeAccion, $fechaHora, $permitido);
        $status = "{$mensajeAccion} registrada para {$persona->nombre_completo}.";

        if ($this->wantsJson($request)) {
            return response()->json([
                'message' => $status,
                'access_modal' => $modalData,
                'dashboard' => $this->dashboardCounters(),
            ]);
        }

        return redirect()
            ->route('home')
            ->with('status', $status)
            ->with('access_modal', $modalData);
    }

    public function registrarDispositivo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'dispositivo_documento' => ['required', 'string', 'max:50'],
            'dispositivo_nombre' => ['required', 'string', 'max:100'],
            'ubicacion' => ['nullable', 'string', 'max:150'],
            'tipo' => ['required', 'in:entrada,salida'],
        ]);

        $persona = Persona::where('documento', trim($data['dispositivo_documento']))->first();

        if (!$persona) {
            return back()
                ->withErrors(['dispositivo_documento' => 'No existe una persona registrada con ese documento.'])
                ->withInput();
        }

        $dispositivo = Dispositivo::firstOrCreate(
            ['nombre' => trim($data['dispositivo_nombre'])],
            [
                'ubicacion' => $data['ubicacion'] ?: 'Herramienta externa',
                'ip' => null,
                'activo' => true,
            ]
        );

        Acceso::create([
            'persona_id' => $persona->id,
            'dispositivo_id' => $dispositivo->id,
            'autorizado_por' => $request->user()?->id,
            'tipo' => $data['tipo'],
            'estado' => $persona->activo ? 'permitido' : 'denegado',
            'motivo_denegacion' => $persona->activo ? null : 'Persona inactiva',
            'fecha_hora' => now(),
        ]);

        return redirect()
            ->route('home')
            ->with('status', 'Movimiento registrado para ' . $dispositivo->nombre . '.');
    }

    public function crearDispositivo(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nuevo_dispositivo_nombre' => ['required', 'string', 'max:100'],
            'nuevo_dispositivo_ubicacion' => ['required', 'string', 'max:150'],
            'nuevo_dispositivo_ip' => ['nullable', 'ip', 'max:45'],
        ]);

        $dispositivo = Dispositivo::updateOrCreate(
            ['nombre' => trim($data['nuevo_dispositivo_nombre'])],
            [
                'ubicacion' => trim($data['nuevo_dispositivo_ubicacion']),
                'ip' => $data['nuevo_dispositivo_ip'] ?? null,
                'activo' => true,
            ]
        );

        return redirect()
            ->route('home')
            ->with('status', 'Dispositivo guardado: ' . $dispositivo->nombre . '.');
    }

    public function validar(Request $request): View
    {
        $codigo = $this->extractCode((string) $request->query('codigo', ''));

        $credencial = Credencial::with('persona.fichaRelacion.centro')
            ->where('tipo', 'qr')
            ->where('valor', $codigo)
            ->first();

        return view('carnets.validar', compact('credencial', 'codigo'));
    }

    private function qrCredential(Persona $persona, bool $rotate = false): Credencial
    {
        $current = $persona->credenciales()
            ->where('tipo', 'qr')
            ->where('activa', true)
            ->first();

        if ($current && !$rotate) {
            return $current;
        }

        if ($rotate) {
            $persona->credenciales()
                ->where('tipo', 'qr')
                ->update(['activa' => false]);
        }

        return $persona->credenciales()->create([
            'tipo' => 'qr',
            'valor' => 'AIP-' . $persona->id . '-' . Str::upper(Str::random(10)),
            'activa' => true,
        ]);
    }

    private function extractCode(string $value): string
    {
        $value = trim($value);
        $query = parse_url($value, PHP_URL_QUERY);

        if ($query) {
            parse_str($query, $params);

            if (!empty($params['codigo'])) {
                return trim((string) $params['codigo']);
            }
        }

        return $value;
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

    private function qrDataUri(string $value): string
    {
        $qrCode = new \Endroid\QrCode\QrCode(
            data: $value,
            encoding: new Encoding('UTF-8'),
            size: 280,
            margin: 8
        );

        return (new \Endroid\QrCode\Writer\SvgWriter())->write($qrCode)->getDataUri();
    }

    private function imageDataUri(string $imageUrl): string
    {
        if (str_starts_with($imageUrl, 'data:')) {
            return $imageUrl;
        }

        $parsed = parse_url($imageUrl);
        $localPath = public_path(ltrim($parsed['path'] ?? '', '/'));
        $contents = null;

        if ($localPath && file_exists($localPath)) {
            $contents = file_get_contents($localPath);
        } elseif (isset($parsed['scheme']) && in_array($parsed['scheme'], ['http', 'https'], true)) {
            try {
                $contents = @file_get_contents($imageUrl);
            } catch (\Throwable $e) {
                $contents = null;
            }
        }

        if ($contents !== false && $contents !== null) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? finfo_buffer($finfo, $contents) : 'image/png';
            if ($finfo) {
                finfo_close($finfo);
            }

            return 'data:' . ($mime ?: 'image/png') . ';base64,' . base64_encode($contents);
        }

        return $imageUrl;
    }

    private function barcodeDataUri(string $value): string
    {
        $patterns = [
            '0' => 'nnnwwnwnn', '1' => 'wnnwnnnnw', '2' => 'nnwwnnnnw', '3' => 'wnwwnnnnn',
            '4' => 'nnnwwnnnw', '5' => 'wnnwwnnnn', '6' => 'nnwwwnnnn', '7' => 'nnnwnnwnw',
            '8' => 'wnnwnnwnn', '9' => 'nnwwnnwnn', 'A' => 'wnnnnwnnw', 'B' => 'nnwnnwnnw',
            'C' => 'wnwnnwnnn', 'D' => 'nnnnwwnnw', 'E' => 'wnnnwwnnn', 'F' => 'nnwnwwnnn',
            'G' => 'nnnnnwwnw', 'H' => 'wnnnnwwnn', 'I' => 'nnwnnwwnn', 'J' => 'nnnnwwwnn',
            'K' => 'wnnnnnnww', 'L' => 'nnwnnnnww', 'M' => 'wnwnnnnwn', 'N' => 'nnnnwnnww',
            'O' => 'wnnnwnnwn', 'P' => 'nnwnwnnwn', 'Q' => 'nnnnnnwww', 'R' => 'wnnnnnwwn',
            'S' => 'nnwnnnwwn', 'T' => 'nnnnwnwwn', 'U' => 'wwnnnnnnw', 'V' => 'nwwnnnnnw',
            'W' => 'wwwnnnnnn', 'X' => 'nwnnwnnnw', 'Y' => 'wwnnwnnnn', 'Z' => 'nwwnwnnnn',
            '-' => 'nwnnnnwnw', '.' => 'wwnnnnwnn', ' ' => 'nwwnnnwnn', '$' => 'nwnwnwnnn',
            '/' => 'nwnwnnnwn', '+' => 'nwnnnwnwn', '%' => 'nnnwnwnwn', '*' => 'nwnnwnwnn',
        ];

        $text = preg_replace('/[^A-Z0-9 \-\.\$\/\+\%]/', '', Str::upper($value));
        $text = $text !== '' ? $text : '0';
        $encoded = '*' . $text . '*';
        $narrow = 2;
        $wide = 5;
        $height = 72;
        $quiet = 12;
        $x = $quiet;
        $bars = '';

        foreach (str_split($encoded) as $char) {
            $pattern = $patterns[$char] ?? $patterns['0'];

            foreach (str_split($pattern) as $index => $widthType) {
                $width = $widthType === 'w' ? $wide : $narrow;

                if ($index % 2 === 0) {
                    $bars .= '<rect x="' . $x . '" y="0" width="' . $width . '" height="' . $height . '" fill="#111"/>';
                }

                $x += $width;
            }

            $x += $narrow;
        }

        $width = $x + $quiet;
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $width . '" height="' . $height . '" viewBox="0 0 ' . $width . ' ' . $height . '">' . $bars . '</svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }

    private function registroError(Request $request, string $message)
    {
        if ($this->wantsJson($request)) {
            return response()->json([
                'message' => $message,
                'errors' => [
                    'codigo' => [$message],
                ],
            ], 422);
        }

        return back()
            ->withErrors(['codigo' => $message])
            ->withInput();
    }

    private function wantsJson(Request $request): bool
    {
        return $request->expectsJson() || $request->ajax();
    }

    private function accessModalData(Persona $persona, string $tipo, string $mensajeAccion, $fechaHora, bool $permitido): array
    {
        $persona->loadMissing('fichaRelacion.centro');

        return [
            'tipo' => $tipo,
            'titulo' => $mensajeAccion . ' registrada',
            'nombre' => $persona->nombre_completo,
            'documento' => $persona->documento,
            'foto' => $this->personaPhotoUrl($persona),
            'genero' => $this->genderLabel($persona->genero ?? null),
            'centro' => $persona->centro_visible,
            'ficha' => $persona->ficha_visible,
            'hora' => $fechaHora->format('h:i a'),
            'fecha' => $fechaHora->format('d/m/Y'),
            'estado' => $permitido ? 'Permitido' : 'Denegado',
        ];
    }

    private function dashboardCounters(): array
    {
        $today = now()->toDateString();

        $soloRegistrosPersonas = fn ($query) => $query->whereHas(
            'dispositivo',
            fn ($dispositivo) => $dispositivo->where('nombre', 'Carnet Web')
        );

        return [
            'entradasHoy' => Acceso::whereDate('fecha_hora', $today)
                ->where('tipo', 'entrada')
                ->where('estado', 'permitido')
                ->tap($soloRegistrosPersonas)
                ->count(),
            'salidasHoy' => Acceso::whereDate('fecha_hora', $today)
                ->where('tipo', 'salida')
                ->where('estado', 'permitido')
                ->tap($soloRegistrosPersonas)
                ->count(),
            'ingresosGenero' => $this->genderCounters($today, $soloRegistrosPersonas),
            'tipoPersonaLabels' => collect($this->tipoPersonaKeys())
                ->map(fn ($tipo) => ucfirst($tipo))
                ->values(),
            'tipoPersonaValores' => $this->tipoPersonaCounters($today, $soloRegistrosPersonas),
        ];
    }

    private function tipoPersonaKeys(): array
    {
        return ['aprendiz', 'instructor', 'administrativo', 'visitante'];
    }

    private function tipoPersonaCounters(string $today, $soloRegistrosPersonas)
    {
        $tiposPersona = Acceso::query()
            ->join('personas', 'accesos.persona_id', '=', 'personas.id')
            ->selectRaw("LOWER(COALESCE(personas.tipo, 'sin tipo')) as tipo_persona, COUNT(*) as total")
            ->whereDate('accesos.fecha_hora', $today)
            ->where('accesos.estado', 'permitido')
            ->tap($soloRegistrosPersonas)
            ->groupBy('tipo_persona')
            ->pluck('total', 'tipo_persona');

        return collect($this->tipoPersonaKeys())
            ->map(fn ($tipo) => (int) ($tiposPersona[$tipo] ?? 0))
            ->values();
    }

    private function genderLabel(?string $gender): string
    {
        return match ($gender) {
            'masculino' => 'Masculino',
            'femenino' => 'Femenino',
            'otro' => 'Otro',
            default => 'N/A',
        };
    }

    private function genderCounters(string $today, $soloRegistrosPersonas): array
    {
        if (!Schema::hasColumn('personas', 'genero')) {
            return [
                'hombres' => 0,
                'mujeres' => 0,
                'otros' => 0,
            ];
        }

        $ingresosGeneroRaw = Acceso::query()
            ->join('personas', 'accesos.persona_id', '=', 'personas.id')
            ->selectRaw('LOWER(personas.genero) as genero, COUNT(*) as total')
            ->whereDate('accesos.fecha_hora', $today)
            ->where('accesos.tipo', 'entrada')
            ->where('accesos.estado', 'permitido')
            ->tap($soloRegistrosPersonas)
            ->groupBy('genero')
            ->pluck('total', 'genero');

        return [
            'hombres' => (int) ($ingresosGeneroRaw['masculino'] ?? 0),
            'mujeres' => (int) ($ingresosGeneroRaw['femenino'] ?? 0),
            'otros' => (int) ($ingresosGeneroRaw['otro'] ?? 0),
        ];
    }
}
