@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Validar carnet</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item active">Validacion</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Resultado</h5>
                @if ($credencial)
                    @php($persona = $credencial->persona)
                    <div class="alert {{ $credencial->activa && $persona?->activo ? 'alert-success' : 'alert-warning' }}">
                        Carnet {{ $credencial->activa && $persona?->activo ? 'valido' : 'inactivo' }}
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Persona:</strong> <span class="notranslate" translate="no">{{ $persona->nombres }} {{ $persona->apellidos }}</span></p>
                            <p><strong>Documento:</strong> <span class="notranslate" translate="no">{{ $persona->documento }}</span></p>
                            <p><strong>Tipo:</strong> {{ ucfirst($persona->tipo) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Ficha:</strong> {{ $persona->fichaRelacion?->ficha ?? $persona->getAttribute('ficha') ?? 'N/A' }}</p>
                            <p><strong>Centro:</strong> {{ $persona->centro ?: $persona->fichaRelacion?->centro?->nombre ?: 'N/A' }}</p>
                            <p><strong>Codigo:</strong> <code>{{ $credencial->valor }}</code></p>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger">No se encontro ningun carnet con el codigo <code>{{ $codigo }}</code>.</div>
                @endif
            </div>
        </div>
    </section>
@endsection
