@extends('layouts.app')

@section('title', 'Editar Vigilante')

@section('content')

<div class="pagetitle">
    <h1>Editar Vigilante</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Editar</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-body pt-4">

                    <div class="d-flex align-items-center mb-4">
                        @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}"
                            class="rounded-circle me-3 border"
                            style="width:60px;height:60px;object-fit:cover;"
                            id="fotoPreview">
                        @else
                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white me-3"
                            style="width:60px;height:60px;font-size:24px;"
                            id="fotoFallback">
                            <i class="bi bi-person"></i>
                        </div>
                        <img src="#" id="fotoPreview" class="rounded-circle me-3 border d-none"
                            style="width:60px;height:60px;object-fit:cover;">
                        @endif
                        <div>
                            <h5 class="card-title mb-0">{{ $user->name }}</h5>
                            <small class="text-muted">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    <i class="bi bi-shield me-1"></i>Vigilante
                                </span>
                            </small>
                        </div>
                    </div>

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        <strong>Corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">

                            {{-- Correo --}}
                            <div class="col-12">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="bi bi-envelope me-1"></i> Correo electrónico <span class="text-danger">*</span>
                                </label>
                                <input type="email" id="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}"
                                    required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Teléfono --}}
                            <div class="col-12">
                                <label for="telefono" class="form-label fw-semibold">
                                    <i class="bi bi-telephone me-1"></i> Teléfono
                                </label>
                                <input type="text" id="telefono" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono', $user->telefono) }}"
                                    maxlength="20">
                                @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nueva contraseña --}}
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="bi bi-lock me-1"></i> Nueva contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Dejar vacío para no cambiar">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword" tabindex="-1">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Mínimo 8 caracteres. Dejar vacío si no deseas cambiarla.</div>
                            </div>

                            {{-- Confirmar contraseña --}}
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-semibold">
                                    <i class="bi bi-lock-fill me-1"></i> Confirmar contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="form-control"
                                        placeholder="Repetir nueva contraseña">
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
                                <input type="file" id="foto" name="foto"
                                    class="form-control @error('foto') is-invalid @enderror"
                                    accept="image/png, image/jpeg, image/jpg">
                                <div class="form-text">Formatos: JPG, JPEG, PNG. Máx 2MB. Dejar vacío para mantener la actual.</div>
                                @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Estado --}}
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bi bi-toggle-on me-1"></i> Estado
                                </label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estado"
                                            id="estadoActivo" value="activo"
                                            {{ old('estado', $user->estado) === 'activo' ? 'checked' : '' }}>
                                        <label class="form-check-label text-success fw-semibold" for="estadoActivo">
                                            <i class="bi bi-check-circle me-1"></i> Activo
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="estado"
                                            id="estadoInactivo" value="inactivo"
                                            {{ old('estado', $user->estado) === 'inactivo' ? 'checked' : '' }}>
                                        <label class="form-check-label text-danger fw-semibold" for="estadoInactivo">
                                            <i class="bi bi-x-circle me-1"></i> Inactivo
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr class="my-4">

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="bi bi-floppy me-1"></i> Guardar Cambios
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
    // Toggle passwords
    document.getElementById('togglePassword').addEventListener('click', function() {
        const input = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    document.getElementById('toggleConfirm').addEventListener('click', function() {
        const input = document.getElementById('password_confirmation');
        const icon = document.getElementById('eyeIconConfirm');
        input.type = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });

    // Preview foto
    document.getElementById('foto').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const preview = document.getElementById('fotoPreview');
                const fallback = document.getElementById('fotoFallback');
                preview.src = ev.target.result;
                preview.classList.remove('d-none');
                if (fallback) fallback.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush