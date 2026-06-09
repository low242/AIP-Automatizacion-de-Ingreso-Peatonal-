@extends('layouts.app')

@section('title', 'Detalle Vigilante')

@section('content')

<div class="pagetitle">
    <h1>Detalle Vigilante</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Usuarios</a></li>
            <li class="breadcrumb-item active">Detalle</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body pt-4">

                    {{-- Encabezado con foto y nombre --}}
                    <div class="text-center mb-4">
                        @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}"
                            class="rounded-circle border mb-2"
                            style="width:90px;height:90px;object-fit:cover;">
                        @else
                        <div class="rounded-circle bg-secondary d-inline-flex align-items-center
                                        justify-content-center text-white mb-2"
                            style="width:90px;height:90px;font-size:36px;">
                            <i class="bi bi-person"></i>
                        </div>
                        @endif
                        <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle mt-1">
                            <i class="bi bi-shield me-1"></i>Vigilante
                        </span>
                    </div>

                    <hr>

                    {{-- Datos --}}
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-envelope me-2"></i>Correo</span>
                            <span class="fw-semibold">{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-telephone me-2"></i>Teléfono</span>
                            <span class="fw-semibold">{{ $user->telefono ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-building me-2"></i>Compañía</span>
                            <span class="fw-semibold">{{ $user->compania ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-geo-alt me-2"></i>Ciudad</span>
                            <span class="fw-semibold">{{ $user->ciudad ?? '—' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-toggle-on me-2"></i>Estado</span>
                            @if($user->estado === 'activo')
                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                <i class="bi bi-check-circle me-1"></i>Activo
                            </span>
                            @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                <i class="bi bi-x-circle me-1"></i>Inactivo
                            </span>
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span class="text-muted"><i class="bi bi-calendar me-2"></i>Registrado</span>
                            <span class="fw-semibold">{{ $user->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>

                    <hr>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm text-white">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection