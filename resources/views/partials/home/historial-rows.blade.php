@forelse ($historial as $acceso)
  <tr>
    <td scope="row">{{ $acceso->persona?->ficha_visible ?? 'N/A' }}</td>
    <td class="notranslate" translate="no">{{ $acceso->persona?->nombre_completo ?? 'N/A' }}</td>
    <td><a href="#" class="text-primary">{{ $acceso->persona?->centro_visible ?? 'N/A' }}</a></td>
    <td>{{ $acceso->tipo === 'entrada' ? $acceso->fecha_hora?->format('h:i a') : '-' }}</td>
    <td>{{ $acceso->tipo === 'salida' ? $acceso->fecha_hora?->format('h:i a') : '-' }}</td>
  </tr>
@empty
  <tr>
    <td colspan="5" class="text-center text-muted">Aun no hay accesos registrados.</td>
  </tr>
@endforelse
