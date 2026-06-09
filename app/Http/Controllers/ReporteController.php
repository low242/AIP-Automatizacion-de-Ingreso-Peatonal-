<?php

namespace App\Http\Controllers;

use App\Models\Acceso;
use App\Support\SimpleXlsx;
use Illuminate\Http\Response;

class ReporteController extends Controller
{
    public function registrosHoy(): Response
    {
        $hoy = now();

        $registros = Acceso::with([
            'persona.fichaRelacion.centro',
            'dispositivo',
            'autorizadoPor',
        ])
            ->whereDate('fecha_hora', $hoy->toDateString())
            ->orderBy('fecha_hora')
            ->get();

        $headers = [
            'ID registro',
            'Fecha',
            'Hora',
            'Movimiento',
            'Estado',
            'Motivo denegacion',
            'Documento',
            'Nombre completo',
            'Tipo persona',
            'Genero',
            'Telefono',
            'Ficha',
            'Centro',
            'Regional',
            'Foto',
            'Dispositivo',
            'Ubicacion dispositivo',
            'IP dispositivo',
            'Autorizado por',
            'Creado en',
            'Actualizado en',
        ];

        $rows = $registros->map(function (Acceso $registro) {
            $persona = $registro->persona;
            $dispositivo = $registro->dispositivo;
            $autorizadoPor = $registro->autorizadoPor;

            return [
                $registro->id,
                $registro->fecha_hora?->format('Y-m-d'),
                $registro->fecha_hora?->format('H:i:s'),
                ucfirst((string) $registro->tipo),
                ucfirst((string) $registro->estado),
                $registro->motivo_denegacion,
                $persona?->documento,
                $persona?->nombre_completo,
                $persona?->tipo,
                $persona?->genero,
                $persona?->telefono,
                $persona?->ficha_visible,
                $persona?->centro_visible,
                $persona?->regional,
                $persona?->foto,
                $dispositivo?->nombre,
                $dispositivo?->ubicacion,
                $dispositivo?->ip,
                $autorizadoPor?->name,
                $registro->created_at?->format('Y-m-d H:i:s'),
                $registro->updated_at?->format('Y-m-d H:i:s'),
            ];
        });

        $content = SimpleXlsx::fromRows($headers, $rows, 'Registros ' . $hoy->format('Y-m-d'));
        $filename = 'reporte-registros-' . $hoy->format('Y-m-d') . '.xlsx';

        return response($content, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Content-Length' => (string) strlen($content),
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
        ]);
    }
}
