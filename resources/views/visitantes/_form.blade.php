@csrf

@if (($method ?? 'POST') !== 'POST')
    @method($method)
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label for="documento" class="form-label">Documento</label>
        <input type="text" name="documento" id="documento"
            class="form-control {{ $errors->has('documento') ? 'is-invalid' : '' }}"
            value="{{ old('documento', $visitante?->documento) }}" required>
        @error('documento')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="telefono" class="form-label">Tel&eacute;fono</label>
        <input type="text" name="telefono" id="telefono"
            class="form-control {{ $errors->has('telefono') ? 'is-invalid' : '' }}"
            value="{{ old('telefono', $visitante?->telefono) }}">
        @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="nombres" class="form-label">Nombres</label>
        <input type="text" name="nombres" id="nombres"
            class="form-control {{ $errors->has('nombres') ? 'is-invalid' : '' }}"
            value="{{ old('nombres', $visitante?->nombres) }}" required>
        @error('nombres')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="apellidos" class="form-label">Apellidos</label>
        <input type="text" name="apellidos" id="apellidos"
            class="form-control {{ $errors->has('apellidos') ? 'is-invalid' : '' }}"
            value="{{ old('apellidos', $visitante?->apellidos) }}" required>
        @error('apellidos')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="genero" class="form-label">G&eacute;nero</label>
        <select name="genero" id="genero" class="form-select {{ $errors->has('genero') ? 'is-invalid' : '' }}">
            <option value="">Sin especificar</option>
            <option value="masculino" @selected(old('genero', $visitante?->genero) === 'masculino')>Masculino</option>
            <option value="femenino" @selected(old('genero', $visitante?->genero) === 'femenino')>Femenino</option>
            <option value="otro" @selected(old('genero', $visitante?->genero) === 'otro')>Otro</option>
        </select>
        @error('genero')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="tipo_sangre" class="form-label">Tipo de sangre</label>
        <input type="text" name="tipo_sangre" id="tipo_sangre"
            class="form-control {{ $errors->has('tipo_sangre') ? 'is-invalid' : '' }}"
            value="{{ old('tipo_sangre', $visitante?->tipo_sangre) }}" maxlength="3">
        @error('tipo_sangre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="centro" class="form-label">Destino / dependencia</label>
        <input type="text" name="centro" id="centro"
            class="form-control {{ $errors->has('centro') ? 'is-invalid' : '' }}"
            value="{{ old('centro', $visitante?->centro) }}">
        @error('centro')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @php
        $visitorPhotoUrl = old('foto', $visitante?->foto);
        if (
            $visitorPhotoUrl &&
            ! str_starts_with($visitorPhotoUrl, 'http://') &&
            ! str_starts_with($visitorPhotoUrl, 'https://')
        ) {
            $visitorPhotoUrl = asset($visitorPhotoUrl);
        }
    @endphp

    <div class="col-12">
        <label class="form-label">Foto</label>

        <div class="d-flex flex-column gap-2">
            <div class="d-flex flex-wrap gap-2 align-items-center">
                <button type="button" class="btn btn-outline-secondary" id="visitorOpenCameraButton">
                    <i class="bi bi-camera-video"></i> Abrir cámara
                </button>
                <button type="button" class="btn btn-outline-secondary d-none" id="visitorCapturePhotoButton">
                    <i class="bi bi-camera"></i> Tomar foto
                </button>
                <button type="button" class="btn btn-outline-secondary d-none" id="visitorCloseCameraButton">
                    <i class="bi bi-x-circle"></i> Cerrar cámara
                </button>
                <button type="button" class="btn btn-outline-secondary" id="visitorChoosePhotoButton">
                    <i class="bi bi-image"></i> Seleccionar imagen
                </button>
                <select id="visitorCameraSelect" class="form-select w-auto" style="max-width: 220px; display:none;">
                    <option>Cargando cámaras...</option>
                </select>
            </div>

            <div class="border rounded overflow-hidden" style="max-width: 280px; width: 100%; min-height: 280px; position: relative;">
                <video id="visitorPhotoVideo" autoplay playsinline class="w-100 h-100 d-none"></video>
                <img id="visitorPhotoPreview"
                    src="{{ $visitorPhotoUrl ?: asset('assets/img/profile-img.png') }}"
                    alt="Foto visitante"
                    class="w-100 h-100 object-fit-cover"
                    style="display: block; min-height: 280px; object-fit: cover;">
            </div>

            <input type="file" name="foto" id="foto" accept="image/*" capture="environment" class="form-control d-none">
            <input type="hidden" name="existing_foto" id="existing_foto"
                value="{{ old('existing_foto', $visitante?->foto) }}">
        </div>

        @error('foto')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mt-4">
    <button type="submit" class="btn btn-primary">
        <i class="bi bi-save"></i>
        {{ $buttonText }}
    </button>

    @if (!empty($cancelUrl))
        <a href="{{ $cancelUrl }}" class="btn btn-outline-secondary">Cancelar</a>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const photoInput = document.getElementById('foto');
            const openCameraButton = document.getElementById('visitorOpenCameraButton');
            const choosePhotoButton = document.getElementById('visitorChoosePhotoButton');
            const capturePhotoButton = document.getElementById('visitorCapturePhotoButton');
            const closeCameraButton = document.getElementById('visitorCloseCameraButton');
            const cameraSelect = document.getElementById('visitorCameraSelect');
            const video = document.getElementById('visitorPhotoVideo');
            const previewImage = document.getElementById('visitorPhotoPreview');

            if (!photoInput || !openCameraButton || !choosePhotoButton || !capturePhotoButton || !closeCameraButton || !cameraSelect || !video || !previewImage) {
                return;
            }

            const canvas = document.createElement('canvas');
            let stream = null;

            const stopVideoStream = function () {
                if (!stream) {
                    return;
                }

                stream.getTracks().forEach(function (track) {
                    track.stop();
                });

                stream = null;
            };

            const showVideoPreview = function () {
                video.classList.remove('d-none');
                previewImage.classList.add('d-none');
            };

            const showImagePreview = function () {
                video.classList.add('d-none');
                previewImage.classList.remove('d-none');
            };

            const updatePreviewFromFile = function (file) {
                if (!file) {
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    previewImage.src = event.target.result;
                    showImagePreview();
                };
                reader.readAsDataURL(file);
            };

            const getSelectedCameraConstraints = function () {
                const deviceId = cameraSelect.value;
                return deviceId ? { deviceId: { exact: deviceId } } : { facingMode: 'environment' };
            };

            const loadCameraDevices = async function () {
                if (!navigator.mediaDevices || !navigator.mediaDevices.enumerateDevices) {
                    cameraSelect.style.display = 'none';
                    return;
                }

                try {
                    const devices = await navigator.mediaDevices.enumerateDevices();
                    const videoDevices = devices.filter(function (device) {
                        return device.kind === 'videoinput';
                    });

                    cameraSelect.innerHTML = '';

                    if (!videoDevices.length) {
                        cameraSelect.style.display = 'none';
                        return;
                    }

                    videoDevices.forEach(function (device, index) {
                        const option = document.createElement('option');
                        option.value = device.deviceId;
                        option.textContent = device.label || 'Cámara ' + (index + 1);
                        cameraSelect.appendChild(option);
                    });

                    cameraSelect.style.display = 'inline-block';
                } catch (error) {
                    console.error(error);
                    cameraSelect.style.display = 'none';
                }
            };

            const startCamera = async function () {
                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    return photoInput.click();
                }

                try {
                    stream = await navigator.mediaDevices.getUserMedia({
                        video: getSelectedCameraConstraints(),
                        audio: false,
                    });

                    video.srcObject = stream;
                    await video.play();
                    showVideoPreview();
                    capturePhotoButton.classList.remove('d-none');
                    closeCameraButton.classList.remove('d-none');
                    openCameraButton.classList.add('d-none');
                    choosePhotoButton.classList.add('d-none');
                } catch (error) {
                    console.error(error);
                    photoInput.click();
                }
            };

            choosePhotoButton.addEventListener('click', function () {
                photoInput.click();
            });

            photoInput.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    updatePreviewFromFile(this.files[0]);
                }
            });

            openCameraButton.addEventListener('click', async function () {
                await loadCameraDevices();
                await startCamera();
            });

            cameraSelect.addEventListener('change', async function () {
                if (!stream) {
                    return;
                }

                stopVideoStream();
                await startCamera();
            });

            capturePhotoButton.addEventListener('click', async function () {
                if (!video.videoWidth || !video.videoHeight) {
                    return;
                }

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                const blob = await new Promise(function (resolve) {
                    canvas.toBlob(resolve, 'image/jpeg', 0.92);
                });

                if (!blob) {
                    return;
                }

                const photoFile = new File([blob], 'visitante-' + Date.now() + '.jpg', { type: 'image/jpeg' });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(photoFile);
                photoInput.files = dataTransfer.files;

                previewImage.src = URL.createObjectURL(blob);
                showImagePreview();
                stopVideoStream();

                capturePhotoButton.classList.add('d-none');
                closeCameraButton.classList.add('d-none');
                openCameraButton.classList.remove('d-none');
                choosePhotoButton.classList.remove('d-none');
            });

            closeCameraButton.addEventListener('click', function () {
                stopVideoStream();
                showImagePreview();
                capturePhotoButton.classList.add('d-none');
                closeCameraButton.classList.add('d-none');
                openCameraButton.classList.remove('d-none');
                choosePhotoButton.classList.remove('d-none');
            });

            window.addEventListener('beforeunload', stopVideoStream);
        });
    </script>
@endpush
