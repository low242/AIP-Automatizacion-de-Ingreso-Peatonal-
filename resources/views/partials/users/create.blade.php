@extends('layouts.app')

@section('title', 'Crear Vigilante')

@section('content')

<div class="pagetitle">
    <h1>Crear Vigilante</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Inicio</a></li>
            <li class="breadcrumb-item active">Crear Vigilante</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm">
                <div class="card-body pt-4">

                    <div class="d-flex align-items-center mb-4">
                        <div class="vigilante-icon me-3">
                            <i class="bi bi-shield-lock fs-1 text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Nuevo Vigilante</h5>
                            <small class="text-muted">El usuario será creado con el rol de <strong>vigilante</strong> automáticamente.</small>
                        </div>
                    </div>

                    {{-- Mensajes de éxito / error --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-1"></i>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Rol fijo como campo oculto --}}
                        <input type="hidden" name="role" value="vigilante">

                        <div class="row g-3">

                            {{-- Nombre --}}
                            <div class="col-12">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="bi bi-person me-1"></i> Nombre completo <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name') }}"
                                    placeholder="Ej: Juan Pérez García"
                                    required
                                >
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1"></i> Correo electrónico <span class="text-danger">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    placeholder="correo@ejemplo.com"
                                    required
                                >
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Teléfono --}}
                            <div class="col-md-6">
                                <label for="telefono" class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1"></i> Teléfono
                                </label>
                                <input
                                    type="text"
                                    id="telefono"
                                    name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono') }}"
                                    placeholder="Ej: 3001234567"
                                    maxlength="20"
                                >
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Compañía --}}
                            <div class="col-md-6">
                                <label for="compania" class="form-label fw-semibold">
                                    <i class="bi bi-building me-1"></i> Compañía
                                </label>
                                <input
                                    type="text"
                                    id="compania"
                                    name="compania"
                                    class="form-control @error('compania') is-invalid @enderror"
                                    value="{{ old('compania') }}"
                                    placeholder="Ej: Seguridad Total S.A."
                                >
                                @error('compania')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ciudad --}}
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label fw-semibold">
                                    <i class="bi bi-geo-alt me-1"></i> Ciudad
                                </label>
                                <input
                                    type="text"
                                    id="ciudad"
                                    name="ciudad"
                                    class="form-control @error('ciudad') is-invalid @enderror"
                                    value="{{ old('ciudad') }}"
                                    placeholder="Ej: Bogotá"
                                >
                                @error('ciudad')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-1"></i> Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Mínimo 8 caracteres"
                                        required
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Confirmar Password --}}
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1"></i> Confirmar contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input
                                        type="password"
                                        id="password_confirmation"
                                        name="password_confirmation"
                                        class="form-control"
                                        placeholder="Repite la contraseña"
                                        required
                                    >
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirm" tabindex="-1">
                                        <i class="bi bi-eye" id="eyeIconConfirm"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Foto --}}
                            <div class="col-12">
                                <label for="foto" class="form-label fw-semibold">
                                    <i class="bi bi-image me-1"></i> Foto de perfil
                                </label>
                                <input
                                    type="file"
                                    id="foto"
                                    name="foto"
                                    class="form-control @error('foto') is-invalid @enderror"
                                    accept="image/png, image/jpeg, image/jpg"
                                >
                                <div class="form-text">Formatos permitidos: JPG, JPEG, PNG. Máximo 2MB.</div>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                {{-- Preview de foto --}}
                                <div id="fotoPreviewContainer" class="mt-2 d-none">
                                    <img id="fotoPreview" src="#" alt="Vista previa"
                                        class="rounded-circle border"
                                        style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                            </div>


                        </div><!-- End row -->

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="/home" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-shield-check me-1"></i> Crear Vigilante
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Toggle password visibility
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });

    document.getElementById('toggleConfirm').addEventListener('click', function () {
        const input = document.getElementById('password_confirmation');
        const icon  = document.getElementById('eyeIconConfirm');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });

    // Preview de foto
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (ev) {
                document.getElementById('fotoPreview').src = ev.target.result;
                document.getElementById('fotoPreviewContainer').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush