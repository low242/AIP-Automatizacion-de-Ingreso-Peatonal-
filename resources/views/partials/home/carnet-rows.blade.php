@forelse ($personasCarnet as $persona)
  @php
    $credencial = $persona->credenciales->first();
  @endphp
  <tr>
    <td class="notranslate" translate="no">{{ $persona->nombre_completo }}</td>
    <td class="notranslate" translate="no">{{ $persona->documento }}</td>
    <td>{{ $persona->ficha_visible }}</td>
    <td>{{ $persona->centro_visible }}</td>
    <td>
      @if ($credencial)
        <span class="badge bg-success">Activo</span>
      @else
        <span class="badge bg-secondary">Sin QR</span>
      @endif
    </td>
    <td class="text-end">
      <div class="d-inline-flex gap-2">
        <a href="{{ route('carnets.show', $persona) }}" class="btn btn-sm btn-outline-primary">
          <i class="bi bi-eye"></i>
        </a>
        <form action="{{ route('carnets.generate', $persona) }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-sm btn-outline-success">
            <i class="bi bi-qr-code"></i>
          </button>
        </form>
      </div>
    </td>
  </tr>
@empty
  <tr>
    <td colspan="6" class="text-center text-muted">No hay personas activas para mostrar.</td>
  </tr>
@endforelse
