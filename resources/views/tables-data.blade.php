@extends('layouts.app')

@section('content')
    <div class="pagetitle">
        <h1>Data Tables</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Tables</li>
                <li class="breadcrumb-item active">Data</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tabla De Informaci&oacute;n</h5>
                        <!-- Table with stripped rows -->
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>Programa</th>
                                        <th>#Ficha</th>
                                        <th>Nombre</th>
                                        <th>Centro</th>
                                        <th>Condici&oacute;n Especial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($personas as $persona)
                                        <tr>
                                            <td>{{ $persona->programa ?? ucfirst($persona->tipo ?? 'N/A') }}</td>
                                            <td>{{ $persona->ficha_visible !== 'N/A' ? '#' . ltrim($persona->ficha_visible, '#') : 'N/A' }}</td>
                                            <td class="notranslate" translate="no">{{ $persona->nombre_completo }}</td>
                                            <td>{{ $persona->centro_visible }}</td>
                                            <td>{{ $persona->condicion_especial ?? $persona->condicion ?? 'N/A' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                            <td>No hay personas registradas</td>
                                            <td>N/A</td>
                                            <td>N/A</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <!-- End Table with stripped rows -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
