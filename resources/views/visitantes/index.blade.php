@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Visitantes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Visitantes</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h5 class="card-title mb-0">Visitantes activos</h5>
                            <form action="{{ route('visitantes.index') }}" method="GET" class="d-flex gap-2">
                                <input type="search" name="q" class="form-control" placeholder="Documento o nombre"
                                    value="{{ $search }}">
                                <button type="submit" class="btn btn-primary" title="Buscar">
                                    <i class="bi bi-search"></i>
                                </button>
                            </form>
                        </div>

                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Visitante</th>
                                        <th>Documento</th>
                                        <th>Contacto</th>
                                        <th>Vigente hasta</th>
                                        <th>Registrado por</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($visitantes as $visitante)
                                        <tr>
                                            <td>
                                                <strong class="notranslate" translate="no">{{ $visitante->nombre_completo }}</strong>
                                                <div class="small text-muted">{{ $visitante->centro_visible }}</div>
                                            </td>
                                            <td class="notranslate" translate="no">{{ $visitante->documento }}</td>
                                            <td>{{ $visitante->telefono ?? 'N/A' }}</td>
                                            <td>
                                                @if ($visitante->visitante_expira_en)
                                                    <span class="badge bg-success">
                                                        {{ $visitante->visitante_expira_en->format('d/m/Y h:i A') }}
                                                    </span>
                                                @else
                                                    <span class="badge bg-secondary">Sin vigencia</span>
                                                @endif
                                            </td>
                                            <td>{{ $visitante->visitanteRegistradoPor?->name ?? 'Sistema' }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('visitantes.edit', $visitante) }}"
                                                    class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <form action="{{ route('visitantes.destroy', $visitante) }}" method="POST" class="d-inline"
                                                    onsubmit="return confirm('¿Eliminar este visitante?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No hay visitantes activos.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{ $visitantes->links() }}
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Registrar visitante</h5>
                        <p class="text-muted small">El registro queda activo por 24 horas.</p>
                        <form action="{{ route('visitantes.store') }}" method="POST" enctype="multipart/form-data">
                            @include('visitantes._form', [
                                'visitante' => null,
                                'method' => 'POST',
                                'buttonText' => 'Registrar visitante',
                            ])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
