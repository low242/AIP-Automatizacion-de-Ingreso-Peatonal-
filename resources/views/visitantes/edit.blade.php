@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Editar visitante</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ route('visitantes.index') }}">Visitantes</a></li>
                <li class="breadcrumb-item active notranslate" translate="no">{{ $visitante->documento }}</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Datos del visitante</h5>

                        @if ($visitante->visitante_expira_en)
                            <div class="alert alert-info">
                                Vigente hasta {{ $visitante->visitante_expira_en->format('d/m/Y h:i A') }}.
                            </div>
                        @endif

                        <form action="{{ route('visitantes.update', $visitante) }}" method="POST" enctype="multipart/form-data">
                            @include('visitantes._form', [
                                'visitante' => $visitante,
                                'method' => 'PUT',
                                'buttonText' => 'Guardar cambios',
                                'cancelUrl' => route('visitantes.index'),
                            ])
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
