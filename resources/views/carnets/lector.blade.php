@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Lector movil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('carnets.index') }}">Carnets</a></li>
                <li class="breadcrumb-item active">Lector</li>
            </ol>
        </nav>
    </div>

    <section class="section scanner-page">
        <div class="row g-3">
            <div class="col-xl-8">
                <div class="card scanner-card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <h5 class="card-title mb-0">Camara</h5>
                            <div class="btn-group" role="group" aria-label="Modo de lector">
                                <button type="button" class="btn btn-primary active" data-scan-mode="entrada">
                                    <i class="bi bi-box-arrow-in-right"></i> Entrada
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-scan-mode="salida">
                                    <i class="bi bi-box-arrow-right"></i> Salida
                                </button>
                                <button type="button" class="btn btn-outline-primary" data-scan-mode="validar">
                                    <i class="bi bi-shield-check"></i> Validar
                                </button>
                            </div>
                        </div>

                        <div class="scanner-viewport">
                            <div id="reader" class="scanner-reader"></div>
                            <div class="scanner-frame" aria-hidden="true"></div>
                        </div>

                        <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                            <button type="button" class="btn btn-success" id="startScanner">
                                <i class="bi bi-camera-video"></i> Iniciar
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="stopScanner" disabled>
                                <i class="bi bi-camera-video-off"></i> Detener
                            </button>
                            <select class="form-select scanner-camera-select" id="cameraSelect" aria-label="Camara"></select>
                        </div>

                        <div class="alert scanner-status mt-3 mb-0" id="scannerStatus" role="status">
                            Camara lista.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Codigo manual</h5>
                        <form id="manualCodeForm" class="vstack gap-3">
                            <div>
                                <label for="manualCode" class="form-label">Codigo o documento</label>
                                <input type="text" class="form-control" id="manualCode" autocomplete="off">
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Enviar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ultima lectura</h5>
                        <div class="border rounded p-3 bg-light">
                            <div class="small text-muted">Codigo</div>
                            <code id="lastCode">Sin lectura</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('styles')
    <style>
        .scanner-page .card {
            border: 0;
            box-shadow: 0 0 24px rgba(15, 23, 42, 0.08);
        }

        .scanner-viewport {
            position: relative;
            overflow: hidden;
            min-height: 320px;
            border-radius: 8px;
            background: #111827;
        }

        .scanner-reader,
        .scanner-reader video {
            width: 100% !important;
            min-height: 320px;
            object-fit: cover;
        }

        .scanner-reader > div {
            border: 0 !important;
        }

        .scanner-frame {
            position: absolute;
            inset: 16%;
            border: 3px solid rgba(255, 255, 255, 0.92);
            border-radius: 8px;
            box-shadow: 0 0 0 999px rgba(0, 0, 0, 0.24);
            pointer-events: none;
        }

        .scanner-camera-select {
            max-width: 280px;
        }

        .scanner-status {
            border: 0;
            background: #eef2ff;
            color: #1e3a8a;
        }

        .scanner-status.is-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .scanner-status.is-success {
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 575.98px) {
            .scanner-page .btn-group {
                display: grid;
                width: 100%;
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .scanner-page .btn-group .btn {
                padding-inline: 0.45rem;
                font-size: 0.85rem;
            }

            .scanner-viewport,
            .scanner-reader,
            .scanner-reader video {
                min-height: 420px;
            }

            .scanner-camera-select {
                max-width: none;
                width: 100%;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const readerId = 'reader';
            const statusEl = document.getElementById('scannerStatus');
            const startButton = document.getElementById('startScanner');
            const stopButton = document.getElementById('stopScanner');
            const cameraSelect = document.getElementById('cameraSelect');
            const lastCodeEl = document.getElementById('lastCode');
            const manualForm = document.getElementById('manualCodeForm');
            const manualInput = document.getElementById('manualCode');
            const modeButtons = document.querySelectorAll('[data-scan-mode]');
            const registerUrl = @json(route('carnets.registrar'));
            const validateUrl = @json(route('carnets.validar'));
            const csrfToken = @json(csrf_token());

            let scanner = null;
            let cameras = [];
            let currentMode = 'entrada';
            let currentCameraId = null;
            let running = false;
            let processing = false;
            let lastScanMessageAt = 0;

            const setStatus = (message, type = 'info') => {
                statusEl.textContent = message;
                statusEl.classList.toggle('is-error', type === 'error');
                statusEl.classList.toggle('is-success', type === 'success');
            };

            const setMode = (mode) => {
                currentMode = mode;
                modeButtons.forEach((button) => {
                    const active = button.dataset.scanMode === mode;
                    button.classList.toggle('active', active);
                    button.classList.toggle('btn-primary', active);
                    button.classList.toggle('btn-outline-primary', !active);
                });
            };

            const preferredCamera = () => {
                const camoCamera = cameras.find((camera) => /camo|reincubate/i.test(camera.label));
                const backCamera = cameras.find((camera) => /back|rear|trasera|environment/i.test(camera.label));
                return camoCamera?.id || backCamera?.id || cameras[cameras.length - 1]?.id || null;
            };

            const scannerConfig = () => {
                const config = {
                    fps: 15,
                    disableFlip: false,
                    qrbox: (viewfinderWidth, viewfinderHeight) => {
                        const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
                        const boxSize = Math.max(240, Math.floor(minEdge * 0.78));

                        return {
                            width: boxSize,
                            height: boxSize,
                        };
                    },
                };

                if (window.Html5QrcodeSupportedFormats?.QR_CODE) {
                    config.formatsToSupport = [Html5QrcodeSupportedFormats.QR_CODE];
                }

                return config;
            };

            const loadCameras = async () => {
                if (!window.Html5Qrcode) {
                    throw new Error('No se pudo cargar el lector QR.');
                }

                cameras = await Html5Qrcode.getCameras();
                cameraSelect.innerHTML = '';

                cameras.forEach((camera, index) => {
                    const option = document.createElement('option');
                    option.value = camera.id;
                    option.textContent = camera.label || `Camara ${index + 1}`;
                    cameraSelect.appendChild(option);
                });

                currentCameraId = preferredCamera();

                if (currentCameraId) {
                    cameraSelect.value = currentCameraId;
                }
            };

            const stopScanner = async () => {
                if (!scanner || !running) {
                    return;
                }

                await scanner.stop();
                running = false;
                processing = false;
                startButton.disabled = false;
                stopButton.disabled = true;
                setStatus('Camara detenida.');
            };

            const startScanner = async () => {
                if (!window.isSecureContext && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
                    setStatus('La camara del celular requiere HTTPS.', 'error');
                    return;
                }

                if (!window.Html5Qrcode) {
                    setStatus('No se pudo cargar el lector QR.', 'error');
                    return;
                }

                if (!scanner) {
                    scanner = new Html5Qrcode(readerId);
                }

                if (!cameras.length) {
                    await loadCameras();
                }

                currentCameraId = cameraSelect.value || currentCameraId;

                if (!currentCameraId) {
                    setStatus('No se encontro una camara disponible.', 'error');
                    return;
                }

                await scanner.start(
                    currentCameraId,
                    scannerConfig(),
                    handleCode,
                    () => {
                        const now = Date.now();

                        if (!processing && now - lastScanMessageAt > 2500) {
                            lastScanMessageAt = now;
                            setStatus('Camara activa. Buscando QR...');
                        }
                    }
                );

                running = true;
                startButton.disabled = true;
                stopButton.disabled = false;
                setStatus('Camara activa.');
            };

            const restartScanner = async () => {
                if (!scanner || !currentCameraId) {
                    return;
                }

                try {
                    await startScanner();
                } catch (error) {
                    setStatus(error.message || 'No se pudo reiniciar la camara.', 'error');
                }
            };

            const submitRegister = async (code) => {
                const formData = new FormData();
                formData.append('codigo', code);
                formData.append('tipo', currentMode);

                const response = await fetch(registerUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    throw new Error(payload.message || 'No se pudo registrar el movimiento.');
                }

                setStatus(payload.message || 'Movimiento registrado.', 'success');
            };

            const handleCode = async (decodedText) => {
                const code = String(decodedText || '').trim();

                if (!code || processing) {
                    return;
                }

                processing = true;
                lastCodeEl.textContent = code;
                const shouldRestart = running;

                if (currentMode === 'validar') {
                    window.location.href = `${validateUrl}?codigo=${encodeURIComponent(code)}`;
                    return;
                }

                try {
                    if (scanner && running) {
                        await scanner.stop();
                        running = false;
                    }

                    startButton.disabled = true;
                    stopButton.disabled = true;
                    await submitRegister(code);

                    window.setTimeout(() => {
                        processing = false;
                        if (shouldRestart) {
                            restartScanner();
                        } else {
                            startButton.disabled = false;
                            stopButton.disabled = true;
                        }
                    }, 1600);
                } catch (error) {
                    setStatus(error.message || 'Lectura rechazada.', 'error');
                    window.setTimeout(() => {
                        processing = false;
                        if (shouldRestart) {
                            restartScanner();
                        } else {
                            startButton.disabled = false;
                            stopButton.disabled = true;
                        }
                    }, 2200);
                }
            };

            modeButtons.forEach((button) => {
                button.addEventListener('click', () => setMode(button.dataset.scanMode));
            });

            startButton.addEventListener('click', () => {
                startScanner().catch((error) => {
                    setStatus(error.message || 'No se pudo acceder a la camara.', 'error');
                    startButton.disabled = false;
                    stopButton.disabled = true;
                });
            });

            stopButton.addEventListener('click', () => {
                stopScanner().catch((error) => setStatus(error.message || 'No se pudo detener la camara.', 'error'));
            });

            cameraSelect.addEventListener('change', async () => {
                currentCameraId = cameraSelect.value;

                if (running) {
                    await stopScanner();
                    await startScanner();
                }
            });

            manualForm.addEventListener('submit', (event) => {
                event.preventDefault();
                handleCode(manualInput.value);
                manualInput.value = '';
            });

            loadCameras()
                .then(() => {
                    if (!cameras.length) {
                        setStatus('No se encontro una camara disponible.', 'error');
                    }
                })
                .catch(() => setStatus('Permite el acceso a la camara para activar el lector.', 'error'));
        });
    </script>
@endpush
