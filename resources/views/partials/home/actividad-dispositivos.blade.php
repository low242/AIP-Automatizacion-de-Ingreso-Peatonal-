@forelse ($actividadDispositivos as $movimiento)
  <div class="activity-item d-flex">
    <div class="activite-label">{{ $movimiento->fecha_hora?->diffForHumans(null, true, true, 1) ?? '-' }}</div>
    <i class="bi bi-circle-fill activity-badge {{ $movimiento->tipo === 'entrada' ? 'text-success' : 'text-danger' }} align-self-start"></i>
    <div class="activity-content">
      <span class="notranslate" translate="no">{{ $movimiento->persona?->nombre_completo ?? 'N/A' }}</span>
      {{ $movimiento->tipo === 'entrada' ? 'ingreso' : 'salio' }}
      <a href="#" class="fw-bold text-dark">{{ $movimiento->dispositivo?->nombre ?? 'Dispositivo' }}</a>
      Verificado
    </div>
  </div>
@empty
  <div class="text-center text-muted small py-3">
    Aun no hay movimientos de dispositivos registrados.
  </div>
@endforelse
