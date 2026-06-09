@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')

<div class="pagetitle">
    <h1>Usuarios</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/home">Inicio</a></li>
            <li class="breadcrumb-item active">Usuarios</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body pt-4">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">Listado de Vigilantes</h5>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-shield-plus me-1"></i> Nuevo Vigilante
                        </a>
                    </div>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-1"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Foto</th>
                                    <th>Nombre</th>
                                    <th>Correo</th>
                                    <th>Teléfono</th>
                                    <th>Ciudad</th>
                                    <th>Compañía</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td class="text-muted small">{{ $loop->iteration }}</td>
                                    <td>
                                        @if($user->foto)
                                        <img src="{{ asset('storage/' . $user->foto) }}"
                                            alt="{{ $user->name }}"
                                            class="rounded-circle"
                                            style="width:40px;height:40px;object-fit:cover;">
                                        @else
                                        <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white"
                                            style="width:40px;height:40px;font-size:16px;">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td class="fw-semibold">{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->telefono ?? '—' }}</td>
                                    <td>{{ $user->ciudad ?? '—' }}</td>
                                    <td>{{ $user->compania ?? '—' }}</td>
                                    <td>
                                        @if($user->estado === 'activo')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="bi bi-check-circle me-1"></i>Activo
                                        </span>
                                        @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-x-circle me-1"></i>Inactivo
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            {{-- Ver --}}
                                            <a href="{{ route('users.show', $user->id) }}"
                                                class="btn btn-sm btn-outline-info"
                                                title="Ver detalle">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            {{-- Editar --}}
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            {{-- Eliminar --}}
                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger btn-eliminar"
                                                data-id="{{ $user->id }}"
                                                data-nombre="{{ $user->name }}"
                                                title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                            {{-- Formulario oculto --}}
                                            <form id="form-eliminar-{{ $user->id }}"
                                                action="{{ route('users.destroy', $user->id) }}"
                                                method="POST" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        <i class="bi bi-shield-slash fs-3 d-block mb-2"></i>
                                        No hay vigilantes registrados.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                const nombre = this.dataset.nombre;

                Swal.fire({
                    title: '¿Eliminar vigilante?',
                    html: `¿Estás seguro de que deseas eliminar a <strong>${nombre}</strong>? Esta acción no se puede deshacer.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-eliminar-' + id).submit();
                    }
                });
            });
        });
    });
</script>

@endsection