@extends('layouts.app')

@section('content')
<div class="pagetitle">
  <h1>Registros</h1>
</div>
<section class="section">
  <div class="container">
    <a href="#" class="btn btn-success mb-3">Nuevo Registro</a>
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Usuario</th>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Datos QR</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach($registros as $registro)
          <tr>
            <td>{{ $registro->id }}</td>
            <td>{{ $registro->usuario->nombre ?? 'N/A' }}</td>
            <td>{{ $registro->tipo }}</td>
            <td>{{ $registro->fecha }}</td>
            <td>{{ $registro->datos_qr }}</td>
            <td>
              <form action="{{ route('registros.destroy', $registro->id) }}" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</section>
@endsection
