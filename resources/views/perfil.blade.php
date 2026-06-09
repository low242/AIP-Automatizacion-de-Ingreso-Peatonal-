@extends('layouts.perfil')

@php
    $activeTab = session('active_tab', 'profile-overview');
    $canChangePassword = ! $user->esVigilante();

    if ($errors->profileUpdate->isNotEmpty()) {
        $activeTab = 'profile-edit';
    }

    if ($canChangePassword && $errors->passwordUpdate->isNotEmpty()) {
        $activeTab = 'profile-change-password';
    }
@endphp

@section('content')
    <div class="pagetitle">
        <h1>Perfil</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Perfil</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                        <img src="{{ $user->profile_photo_url }}" alt="Profile" class="rounded-circle profile-photo"
                            onerror="this.onerror=null;this.src='{{ asset('assets/img/profile-img.png') }}';">
                        <h2>{{ $user->name }}</h2>
                        <h3>{{ $user->role_label }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pt-3">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <ul class="nav nav-tabs nav-tabs-bordered">
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'profile-overview' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#profile-overview">
                                    Información
                                </button>
                            </li>

                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'profile-edit' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#profile-edit">
                                    Editar Perfil
                                </button>
                            </li>

                            @if ($canChangePassword)
                            <li class="nav-item">
                                <button class="nav-link {{ $activeTab === 'profile-change-password' ? 'active' : '' }}"
                                    data-bs-toggle="tab" data-bs-target="#profile-change-password">
                                    Cambiar Contraseña
                                </button>
                            </li>
                            @endif
                        </ul>

                        <div class="tab-content pt-2">
                            <div class="tab-pane fade {{ $activeTab === 'profile-overview' ? 'show active' : '' }} profile-overview"
                                id="profile-overview">
                                <h5 class="card-title">Detalles del Perfil</h5>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Nombre Completo</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->name }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Compañía</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->compania ?: 'No registrada' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Cargo</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->role_label }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Ciudad</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->ciudad ?: 'No registrada' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Teléfono</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->telefono ?: 'No registrado' }}</div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label">Email</div>
                                    <div class="col-lg-9 col-md-8">{{ $user->email }}</div>
                                </div>
                            </div>

                            <div class="tab-pane fade pt-3 {{ $activeTab === 'profile-edit' ? 'show active' : '' }}"
                                id="profile-edit">
                                <form action="{{ route('perfil.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label for="foto" class="col-md-4 col-lg-3 col-form-label">Imagen del
                                            Perfil</label>
                                        <div class="col-md-8 col-lg-9">
                                            <img class="perfil_img rounded-circle" src="{{ $user->profile_photo_url }}"
                                                alt="Profile" data-profile-preview
                                                onerror="this.onerror=null;this.src='{{ asset('assets/img/profile-img.png') }}';">
                                            <input name="foto" type="file"
                                                class="form-control mt-3 {{ $errors->profileUpdate->has('foto') ? 'is-invalid' : '' }}"
                                                id="foto" accept="image/*">
                                            @if ($errors->profileUpdate->has('foto'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->profileUpdate->first('foto') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="name" class="col-md-4 col-lg-3 col-form-label">Nombre
                                            Completo</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="name" type="text"
                                                class="form-control {{ $errors->profileUpdate->has('name') ? 'is-invalid' : '' }}"
                                                id="name" value="{{ old('name', $user->name) }}">
                                            @if ($errors->profileUpdate->has('name'))
                                                <div class="invalid-feedback">{{ $errors->profileUpdate->first('name') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="compania" class="col-md-4 col-lg-3 col-form-label">Compañía</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="compania" type="text"
                                                class="form-control {{ $errors->profileUpdate->has('compania') ? 'is-invalid' : '' }}"
                                                id="compania" value="{{ old('compania', $user->compania) }}">
                                            @if ($errors->profileUpdate->has('compania'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->profileUpdate->first('compania') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- <div class="row mb-3">
                                        <label for="job_title" class="col-md-4 col-lg-3 col-form-label">Cargo</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="job_title" type="text"
                                                class="form-control {{ $errors->profileUpdate->has('job_title') ? 'is-invalid' : '' }}"
                                                id="job_title" value="{{ old('job_title', $user->job_title) }}">
                                            @if ($errors->profileUpdate->has('job_title'))
                                                <div class="invalid-feedback">{{ $errors->profileUpdate->first('job_title') }}</div>
                                            @endif
                                        </div>
                                    </div> --}}

                                    <div class="row mb-3">
                                        <label for="ciudad" class="col-md-4 col-lg-3 col-form-label">Ciudad</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="ciudad" type="text"
                                                class="form-control {{ $errors->profileUpdate->has('ciudad') ? 'is-invalid' : '' }}"
                                                id="ciudad" value="{{ old('ciudad', $user->ciudad) }}">
                                            @if ($errors->profileUpdate->has('ciudad'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->profileUpdate->first('ciudad') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="telefono" class="col-md-4 col-lg-3 col-form-label">Teléfono</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="telefono" type="text"
                                                class="form-control {{ $errors->profileUpdate->has('telefono') ? 'is-invalid' : '' }}"
                                                id="telefono" value="{{ old('telefono', $user->telefono) }}">
                                            @if ($errors->profileUpdate->has('telefono'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->profileUpdate->first('telefono') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="email" type="email"
                                                class="form-control {{ $errors->profileUpdate->has('email') ? 'is-invalid' : '' }}"
                                                id="email" value="{{ old('email', $user->email) }}">
                                            @if ($errors->profileUpdate->has('email'))
                                                <div class="invalid-feedback">{{ $errors->profileUpdate->first('email') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </form>
                            </div>

                            @if ($canChangePassword)
                            <div class="tab-pane fade pt-3 {{ $activeTab === 'profile-change-password' ? 'show active' : '' }}"
                                id="profile-change-password">
                                <form action="{{ route('perfil.password') }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="row mb-3">
                                        <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Contraseña
                                            Actual</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="current_password" type="password"
                                                class="form-control {{ $errors->passwordUpdate->has('current_password') ? 'is-invalid' : '' }}"
                                                id="current_password">
                                            @if ($errors->passwordUpdate->has('current_password'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->passwordUpdate->first('current_password') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password" class="col-md-4 col-lg-3 col-form-label">Nueva
                                            Contraseña</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password" type="password"
                                                class="form-control {{ $errors->passwordUpdate->has('password') ? 'is-invalid' : '' }}"
                                                id="password">
                                            @if ($errors->passwordUpdate->has('password'))
                                                <div class="invalid-feedback">
                                                    {{ $errors->passwordUpdate->first('password') }}</div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password_confirmation"
                                            class="col-md-4 col-lg-3 col-form-label">Confirmar Contraseña</label>
                                        <div class="col-md-8 col-lg-9">
                                            <input name="password_confirmation" type="password" class="form-control"
                                                id="password_confirmation">
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const input = document.getElementById('foto');
            const preview = document.querySelector('[data-profile-preview]');

            if (!input || !preview) {
                return;
            }

            input.addEventListener('change', () => {
                const file = input.files?.[0];

                if (!file) {
                    return;
                }

                preview.src = URL.createObjectURL(file);
            });
        });
    </script>
@endsection
