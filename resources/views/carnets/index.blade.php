@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Carnets</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Carnets</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h5 class="card-title mb-0">Personas registradas</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('carnets.pdf.all') }}" class="btn btn-success" target="_blank">
                                    <i class="bi bi-files-pdf"></i> Descargar todos
                                </a>
                                <a href="{{ route('carnets.lector') }}" class="btn btn-primary">
                                    <i class="bi bi-camera-video"></i> Lector
                                </a>
                                <form action="{{ route('carnets.index') }}" method="GET" class="d-flex gap-2">
                                    <input type="search" name="q" class="form-control" placeholder="Documento o nombre"
                                        value="{{ $search }}">
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Persona</th>
                                        <th>Documento</th>
                                        <th>Tipo</th>
                                        <th>Ficha</th>
                                        <th>Carnet</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($personas as $persona)
                                        @php($credencial = $persona->credenciales->first())
                                        <tr>
                                            <td>
                                                <strong class="notranslate" translate="no">{{ $persona->nombre_completo }}</strong>
                                                <div class="small text-muted">{{ $persona->centro_visible }}</div>
                                            </td>
                                            <td class="notranslate" translate="no">{{ $persona->documento }}</td>
                                            <td>{{ ucfirst($persona->tipo) }}</td>
                                            <td>{{ $persona->ficha_visible }}</td>
                                            <td>
                                                @if ($credencial)
                                                    <span class="badge bg-success">Activo</span>
                                                @else
                                                    <span class="badge bg-secondary">Sin generar</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('carnets.show', $persona) }}" class="btn btn-sm btn-outline-primary" title="Ver carnet">
                                                    <i class="bi bi-person-vcard"></i>
                                                </a>
                                                <a href="{{ route('carnets.pdf.download', $persona) }}" class="btn btn-sm btn-outline-success" title="Descargar PDF" target="_blank">
                                                    <i class="bi bi-file-pdf"></i>
                                                </a>
                                                <form action="{{ route('carnets.generate', $persona) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Generar o renovar QR">
                                                        <i class="bi bi-qr-code"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No hay personas para mostrar.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $personas->links() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registrar acceso</h5>
                        <form action="{{ route('carnets.registrar') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="codigo" class="form-label">Documento del usuario</label>
                                <input type="text" name="codigo" id="codigo"
                                    class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}"
                                    value="{{ old('codigo') }}" placeholder="Ej: 500001">
                                @error('codigo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Movimiento</label>
                                <select name="tipo" id="tipo" class="form-select">
                                    <option value="entrada" @selected(old('tipo') === 'entrada')>Entrada</option>
                                    <option value="salida" @selected(old('tipo') === 'salida')>Salida</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
