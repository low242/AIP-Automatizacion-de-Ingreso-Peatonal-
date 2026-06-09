@extends('layouts.app')

@section('content')
<div class="pagetitle no-print">
    <h1>Informaci&oacute;n de persona</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('carnets.index') }}">Personas</a></li>
            <li class="breadcrumb-item active notranslate" translate="no">{{ $persona->documento }}</li>
        </ol>
    </nav>
</div>

<style>
    .person-detail-layout {
        row-gap: 24px;
    }

    .aip-carnet {
        background: #fff;
        border: 1px solid rgba(217, 226, 239, .9);
        border-radius: 8px;
        box-shadow: 0 10px 28px rgba(1, 41, 112, .12);
        max-width: 100%;
        overflow: hidden;
        width: 100%;
    }

    .aip-carnet-header {
        align-items: center;
        background: #0b8f4d;
        color: #fff;
        display: flex;
        gap: 18px;
        padding: 22px;
    }

    .aip-carnet-header>div {
        min-width: 0;
    }

    .aip-carnet-header h3 {
        overflow-wrap: anywhere;
    }

    .aip-carnet-body {
        padding: 24px;
    }

    .aip-carnet-photo {
        background: #fff;
        border: 4px solid rgba(255, 255, 255, .35);
        border-radius: 50%;
        flex: 0 0 auto;
        height: 104px;
        object-fit: cover;
        width: 104px;
    }

    .person-status {
        align-items: center;
        border: 1px solid rgba(255, 255, 255, .45);
        border-radius: 999px;
        color: #fff;
        display: inline-flex;
        font-size: .8rem;
        gap: 6px;
        line-height: 1;
        padding: 7px 10px;
    }

    .person-info-grid {
        display: grid;
        gap: 16px 22px;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .person-info-item {
        border-bottom: 1px solid #edf2f7;
        padding-bottom: 12px;
    }

    .person-info-label {
        color: #6c757d;
        display: block;
        font-size: .78rem;
        font-weight: 700;
        letter-spacing: .02em;
        margin-bottom: 4px;
        text-transform: uppercase;
    }

    .person-info-value {
        color: #212529;
        font-size: .98rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .credential-strip {
        align-items: center;
        background: #f6f9ff;
        border: 1px solid #dce7f7;
        border-radius: 8px;
        display: flex;
        gap: 12px;
        justify-content: space-between;
        margin-top: 20px;
        padding: 14px 16px;
    }

    .credential-strip>div {
        min-width: 0;
    }

    .credential-strip code {
        display: block;
        overflow-wrap: anywhere;
        white-space: normal;
    }

    .credential-qr {
        align-items: center;
        background: #fff;
        border: 1px solid #e5edf8;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-top: 20px;
        padding: 16px;
        text-align: center;
    }

    .credential-qr-box {
        align-items: center;
        background: #fff;
        border: 12px solid #fff;
        display: flex;
        height: 304px;
        justify-content: center;
        width: 304px;
    }

    .credential-qr-box canvas,
    .credential-qr-box img {
        display: block;
        height: 280px;
        image-rendering: pixelated;
        width: 280px;
    }

    .credential-qr-label {
        color: #6c757d;
        font-size: .82rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .person-action-buttons {
        align-items: center;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .person-action-buttons form {
        margin: 0;
    }

    .person-action-buttons .btn {
        align-items: center;
        display: inline-flex;
        gap: 4px;
        justify-content: center;
        min-height: 40px;
        white-space: normal;
    }

    body.dark-mode .aip-carnet {
        background: #1f1f1f;
        border-color: #353535;
    }

    body.dark-mode .person-info-item {
        border-bottom-color: #353535;
    }

    body.dark-mode .person-info-value {
        color: #f5f5f5;
    }

    body.dark-mode .credential-strip {
        background: #242424;
        border-color: #353535;
    }

    body.dark-mode .credential-qr {
        background: #fff;
        border-color: #353535;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .print-area,
        .print-area * {
            visibility: visible;
        }

        .print-area {
            position: absolute;
            inset: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding-top: 20px;
        }

        .no-print {
            display: none !important;
        }
    }

    @media (max-width: 575.98px) {
        .aip-carnet-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 14px;
            padding: 18px;
        }

        .aip-carnet-body {
            padding: 18px;
        }

        .aip-carnet-photo {
            height: 88px;
            width: 88px;
        }

        .person-info-grid {
            gap: 14px;
        }

        .credential-strip {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
        }

        .credential-qr-box {
            height: 248px;
            width: 248px;
        }

        .credential-qr-box canvas,
        .credential-qr-box img {
            height: 224px;
            width: 224px;
        }

        .person-action-buttons,
        .person-action-buttons form,
        .person-action-buttons .btn {
            width: 100%;
        }
    }

    @media (min-width: 576px) and (max-width: 991.98px) {
        .person-action-buttons .btn {
            flex: 1 1 140px;
        }
    }
</style>

<section class="section">
    <div class="row person-detail-layout">
        <div class="col-lg-7 print-area">
            <div class="aip-carnet">
                <div class="aip-carnet-header">
                    <img src="{{ $photoUrl }}" alt="Foto de {{ $persona->nombre_completo }}" class="aip-carnet-photo">
                    <div>
                        <div class="person-status mb-2">
                            <i class="bi {{ $persona->activo ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                            {{ $persona->activo ? 'Persona activa' : 'Persona inactiva' }}
                        </div>
                        <h3 class="mb-1 text-white notranslate" translate="no">{{ $persona->nombre_completo }}</h3>
                        <div class="text-white-50">{{ ucfirst($persona->tipo ?: 'Sin tipo') }}</div>
                    </div>
                </div>
                <div class="aip-carnet-body">
                    <div class="person-info-grid">
                        <div class="person-info-item">
                            <span class="person-info-label">ID interno</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->id }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Documento</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->documento ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Nombres</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->nombres ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Apellidos</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->apellidos ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Genero</span>
                            <span class="person-info-value">{{ ucfirst($persona->genero ?: 'N/A') }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Telefono</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->telefono ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Tipo de sangre</span>
                            <span class="person-info-value">{{ $persona->tipo_sangre ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Ficha</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->ficha_visible }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Centro</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->centro_visible }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Regional</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->regional ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Foto</span>
                            <span class="person-info-value notranslate" translate="no">{{ $persona->foto ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Fecha de registro</span>
                            <span class="person-info-value">{{ $persona->created_at?->format('Y-m-d H:i') ?: 'N/A' }}</span>
                        </div>
                        <div class="person-info-item">
                            <span class="person-info-label">Ultima actualizacion</span>
                            <span class="person-info-value">{{ $persona->updated_at?->format('Y-m-d H:i') ?: 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="credential-strip">
                        <div>
                            <span class="person-info-label">Credencial QR activa</span>
                            <code class="notranslate" translate="no">{{ $credencial->valor }}</code>
                        </div>
                        <span class="badge bg-success">Activa</span>
                    </div>

                    <div class="credential-qr">
                        <div class="credential-qr-box" data-qr-box data-qr-value="{{ $credencial->valor }}"></div>
                        <div class="credential-qr-label">Codigo QR para escanear</div>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->role !== 'vigilante')
        <div class="col-lg-5 no-print">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Acciones de la persona</h5>
                    @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                    @endif
                    <p class="text-muted">La informacion de la persona queda conectada a su credencial QR y se puede validar o registrar desde el home.</p>
                    <div class="person-action-buttons">
                        <button type="button" class="btn btn-primary" onclick="window.print()">
                            <i class="bi bi-printer"></i> Imprimir informacion
                        </button>
                        <a href="{{ route('carnets.pdf.download', $persona) }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-file-pdf"></i> Descargar carnet
                        </a>
                        <a href="{{ route('carnets.pdf.all') }}" class="btn btn-outline-success" target="_blank">
                            <i class="bi bi-files"></i> Descargar todos
                        </a>
                        <form action="{{ route('carnets.generate', $persona) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-repeat"></i> Renovar QR
                            </button>
                        </form>
                        <a href="{{ route('carnets.validar', ['codigo' => $credencial->valor]) }}" class="btn btn-outline-info">
                            <i class="bi bi-shield-check"></i> Validar
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-qr-box]').forEach((box) => {
            const value = box.dataset.qrValue || '';

            if (!value || !window.QRCode) {
                box.textContent = value ? 'No se pudo cargar el QR.' : 'Sin codigo QR.';
                return;
            }

            box.innerHTML = '';
            new QRCode(box, {
                text: value,
                width: 280,
                height: 280,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.M,
            });
        });
    });
</script>
@endpush