<div class="modal fade emergency-modal" id="fullscreenModal" tabindex="-1" aria-labelledby="emergencyModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="emergency-hero">
        <div class="emergency-hero__content">
          <div class="emergency-status">
            <span class="emergency-status__pulse"></span>
            Consulta inmediata
          </div>
           </div>

        <button type="button" class="btn-close btn-close-white emergency-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body emergency-body">
        <div class="container-fluid">
          <div class="emergency-search">
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-search"></i></span>
              <input type="search" class="form-control" id="emergencyPersonSearch" placeholder="Documento, nombre, ficha, centro o tel&eacute;fono" autocomplete="off">
            </div>
            <span id="emergencySearchStatus">Escriba para buscar o revise las personas activas recientes.</span>
          </div>

          <div class="row g-4">
            <div class="col-12 col-xl-4">
              <div class="emergency-panel emergency-results-panel">
                <div class="emergency-section-title">
                  <i class="bi bi-person-lines-fill"></i>
                  <h3>Resultados</h3>
                </div>

                <div class="emergency-results" id="emergencyPersonResults"></div>
              </div>
            </div>

            <div class="col-12 col-xl-8">
              <div class="emergency-panel emergency-detail-panel" id="emergencyPersonDetail">
                <div class="emergency-empty">
                  <i class="bi bi-person-badge"></i>
                  <h3>Seleccione una persona</h3>
                  <p>Al elegir un resultado ver&aacute; documento, tel&eacute;fono, sangre, ficha, centro, estado y &uacute;ltimos accesos.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="modal-footer emergency-footer">
        <a href="{{ route('tables-data') }}" class="btn btn-light">
          <i class="bi bi-table me-1"></i>
          Ver tabla completa
        </a>
        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('fullscreenModal');
    const searchInput = document.getElementById('emergencyPersonSearch');
    const status = document.getElementById('emergencySearchStatus');
    const results = document.getElementById('emergencyPersonResults');
    const detail = document.getElementById('emergencyPersonDetail');

    if (!modal || !searchInput || !results || !detail) {
      return;
    }

    const endpoint = @json(route('emergencia.personas'));
    const defaultPhoto = @json(asset('assets/img/profile-img.png'));
    let controller;
    let personas = [];
    let debounce;

    const escapeText = (value) => String(value ?? 'N/A')
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');

    const renderDetail = (persona) => {
      const accesos = persona.ultimos_accesos?.length
        ? persona.ultimos_accesos.map((acceso) => `
            <div class="emergency-access">
              <span class="emergency-access__type">${escapeText(acceso.tipo)}</span>
              <strong>${escapeText(acceso.fecha)}</strong>
              <small>${escapeText(acceso.dispositivo)} - ${escapeText(acceso.estado)}</small>
            </div>
          `).join('')
        : '<p class="emergency-muted">No hay accesos recientes registrados.</p>';

      detail.innerHTML = `
        <div class="emergency-detail-header">
          <div class="emergency-person-head">
            <img src="${escapeText(persona.foto || defaultPhoto)}"
              alt="Foto de ${escapeText(persona.nombre)}"
              class="emergency-person-photo"
              onerror="this.onerror=null;this.src='${escapeText(defaultPhoto)}';">
            <div>
              <span class="emergency-kicker">Persona seleccionada</span>
              <h3 class="notranslate" translate="no">${escapeText(persona.nombre)}</h3>
              <p>${escapeText(persona.tipo)} - Documento <span class="notranslate" translate="no">${escapeText(persona.documento)}</span></p>
            </div>
          </div>
          <span class="badge bg-${escapeText(persona.estado_clase)}">${escapeText(persona.estado)}</span>
        </div>

        <div class="emergency-info-grid">
          <div><span>Tel&eacute;fono</span><strong>${escapeText(persona.telefono)}</strong></div>
          <div><span>Tipo de sangre</span><strong>${escapeText(persona.tipo_sangre)}</strong></div>
          <div><span>G&eacute;nero</span><strong>${escapeText(persona.genero)}</strong></div>
          <div><span>Ficha</span><strong>${escapeText(persona.ficha)}</strong></div>
          <div><span>Centro</span><strong>${escapeText(persona.centro)}</strong></div>
        </div>

        <div class="emergency-detail-actions">
          <a href="${escapeText(persona.carnet_url)}" class="btn btn-danger">
            <i class="bi bi-person-vcard me-1"></i>
            Abrir informaci&oacute;n completa
          </a>
        </div>

        <div class="emergency-access-list">
          <div class="emergency-section-title emergency-section-title--compact">
            <i class="bi bi-clock-history"></i>
            <h3>&Uacute;ltimos accesos</h3>
          </div>
          ${accesos}
        </div>
      `;
    };

    const renderResults = () => {
      if (!personas.length) {
        results.innerHTML = `
          <div class="emergency-empty emergency-empty--small">
            <i class="bi bi-search"></i>
            <p>No se encontraron personas.</p>
          </div>
        `;
        return;
      }

      results.innerHTML = personas.map((persona, index) => `
        <button type="button" class="emergency-result ${index === 0 ? 'is-active' : ''}" data-index="${index}">
          <img src="${escapeText(persona.foto || defaultPhoto)}"
            alt=""
            class="emergency-result-photo"
            onerror="this.onerror=null;this.src='${escapeText(defaultPhoto)}';">
          <span class="notranslate" translate="no">${escapeText(persona.nombre)}</span>
          <small><span class="notranslate" translate="no">${escapeText(persona.documento)}</span> - <span class="notranslate" translate="no">${escapeText(persona.centro)}</span></small>
        </button>
      `).join('');

      renderDetail(personas[0]);
    };

    const loadPeople = async (query = '') => {
      if (controller) {
        controller.abort();
      }

      controller = new AbortController();
      status.textContent = 'Buscando informacion...';

      try {
        const response = await fetch(`${endpoint}?q=${encodeURIComponent(query)}`, {
          headers: { 'Accept': 'application/json' },
          signal: controller.signal,
        });

        if (!response.ok) {
          throw new Error('No fue posible consultar personas.');
        }

        const data = await response.json();
        personas = data.personas ?? [];
        status.textContent = personas.length
          ? `${personas.length} resultado(s) disponibles`
          : 'Sin resultados para la busqueda';
        renderResults();
      } catch (error) {
        if (error.name === 'AbortError') {
          return;
        }

        status.textContent = 'No fue posible consultar la informacion.';
        results.innerHTML = '<p class="emergency-muted">Revise la conexion o intente de nuevo.</p>';
      }
    };

    searchInput.addEventListener('input', () => {
      clearTimeout(debounce);
      debounce = setTimeout(() => loadPeople(searchInput.value.trim()), 250);
    });

    results.addEventListener('click', (event) => {
      const button = event.target.closest('.emergency-result');
      if (!button) {
        return;
      }

      results.querySelectorAll('.emergency-result').forEach((item) => item.classList.remove('is-active'));
      button.classList.add('is-active');
      renderDetail(personas[Number(button.dataset.index)]);
    });

    modal.addEventListener('shown.bs.modal', () => {
      searchInput.focus();
      loadPeople(searchInput.value.trim());
    });
  });
</script>
