<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carnet - {{ $persona->nombre_completo }}</title>
    <style>
        @page { margin: 0; size: A4 portrait; }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #1f1f1f;
            background: #fff;
        }

        .page {
            width: 210mm;
            padding-top: 25mm;
            text-align: center;
        }

        .carnet {
            display: inline-block;
            width: 68mm;
            min-height: 104mm;
            padding: 6mm 5mm 5mm;
            text-align: left;
            background: #fff;
            border: 0.4mm solid #d8d8d8;
            border-radius: 5mm;
        }

        .top-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3mm;
        }

        .top-table td {
            width: 50%;
            height: 34mm;
            vertical-align: top;
            padding: 0;
        }

        .sena-logo {
            width: 24mm;
            height: 24mm;
            display: block;
        }

        .photo,
        .photo-placeholder {
            width: 29mm;
            height: 32mm;
            margin-left: auto;
        }

        .photo {
            object-fit: cover;
            object-position: center top;
        }

        .photo-placeholder {
            padding-top: 10.5mm;
            color: #00843d;
            background: #f2f5f2;
            border: 0.35mm solid #d7ded7;
            text-align: center;
            font-size: 7mm;
            font-weight: 700;
        }

        .role {
            color: #8d8d8d;
            font-size: 5.8mm;
            font-weight: 400;
            line-height: 1;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .green-line {
            height: 0.7mm;
            margin: 1.5mm 0 3.4mm;
            background: #00843d;
        }

        .name {
            min-height: 13mm;
            color: #00843d;
            font-size: 5.5mm;
            font-weight: 700;
            line-height: 1.12;
        }

        .doc,
        .rh {
            color: #222;
            font-family: "Times New Roman", Times, serif;
            font-size: 4.8mm;
            line-height: 1.13;
        }

        .qr {
            display: block;
            width: 17mm;
            height: 17mm;
            margin-top: 2mm;
        }

        .footer {
            margin-top: 2mm;
            color: #b8b8b8;
            font-family: "Times New Roman", Times, serif;
            font-size: 3.5mm;
            line-height: 1.12;
        }
    </style>
</head>
<body>
    @php
        $nombres = trim((string) ($persona->nombres ?? ''));
        $apellidos = trim((string) ($persona->apellidos ?? ''));
        $nombreLinea1 = $nombres !== '' ? $nombres : trim((string) $persona->nombre_completo);
        $nombreLinea2 = $apellidos;
        $rol = strtoupper($persona->tipo ?: 'APRENDIZ');
        $regional = $persona->regional ?: 'Regional Caldas';
        $centro = data_get($persona, 'fichaRelacion.centro.nombre') ?: ($persona->centro_visible ?? 'Centro de Procesos Industriales y Construccion');
        $iniciales = strtoupper(substr($nombreLinea1, 0, 1) . substr($nombreLinea2, 0, 1));
    @endphp

    <div class="page">
        <div class="carnet">
            <table class="top-table">
                <tr>
                    <td>
                        <svg class="sena-logo" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" aria-label="SENA">
                            <g fill="#00843d">
                                <circle cx="60" cy="14" r="9"/>
                                <text x="60" y="42" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="27" font-weight="800">SENA</text>
                                <rect x="12" y="48" width="96" height="7"/>
                                <polygon points="12,56 35,56 18,87 8,82"/>
                                <polygon points="108,56 85,56 102,87 112,82"/>
                                <polygon points="50,56 58,56 43,112 31,108"/>
                                <polygon points="70,56 62,56 77,112 89,108"/>
                            </g>
                        </svg>
                    </td>
                    <td>
                        @if ($photoDataUri)
                            <img src="{{ $photoDataUri }}" alt="Foto" class="photo">
                        @else
                            <div class="photo-placeholder">{{ $iniciales ?: 'ID' }}</div>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="role">{{ $rol }}</div>
            <div class="green-line"></div>

            <div class="name">
                {{ $nombreLinea1 }}<br>
                {{ $nombreLinea2 }}
            </div>

            <div class="doc">Doc: {{ $persona->documento ?: 'N/A' }}</div>
            <div class="rh">RH: {{ $persona->tipo_sangre ?: 'N/A' }}</div>

            <img src="{{ $qrDataUri }}" alt="Codigo QR" class="qr">

            <div class="footer">
                {{ $regional }}<br>
                {{ $centro }}
            </div>
        </div>
    </div>
</body>
</html>
