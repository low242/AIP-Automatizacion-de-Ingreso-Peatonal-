@extends('layouts.app')



@section('content')

<div class="pagetitle">
  <h1>Inicio</h1>
  <nav></nav>
</div>
<section class="section dashboard">
  <div class="row">

        <!-- Left side columns -->
        <div class="col-12 col-xxl-8">
          <div class="row">

            <!-- Sales Card -->
            <div class="col-12 col-md-6">
              <div class="card info-card sales-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filtro</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Hoy</a></li>
                    <li><a class="dropdown-item" href="#">Mes</a></li>
                    <li><a class="dropdown-item" href="#">AÃ±o</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Entrada</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-door-open"></i>
                    </div>
                    <div class="ps-3">
                      <h6 data-dashboard-count="entradasHoy">{{ $entradasHoy }}</h6>
                      <span class="text-success small pt-1 fw-bold">Hoy</span> <span class="text-muted small pt-2 ps-1">Ingresos</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Sales Card -->

            <!-- Revenue Card -->
            <div class="col-12 col-md-6">
              <div class="card info-card revenue-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filtro</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Hoy</a></li>
                    <li><a class="dropdown-item" href="#">Mes</a></li>
                    <li><a class="dropdown-item" href="#">AÃ±o</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Salida</h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-door-closed"></i>
                    </div>
                    <div class="ps-3">
                      <h6 data-dashboard-count="salidasHoy">{{ $salidasHoy }}</h6>
                      <span class="text-success small pt-1 fw-bold">Hoy</span> <span class="text-muted small pt-2 ps-1">Salidas</span>

                    </div>
                  </div>
                </div>

              </div>
            </div><!-- End Revenue Card -->

            <!-- Customers Card -->
            <div class="col-12">

              <div class="card info-card customers-card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filtro</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Hoy</a></li>
                    <li><a class="dropdown-item" href="#">Mes</a></li>
                    <li><a class="dropdown-item" href="#">AÃ±o</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Ingresos por género</h5>

                  <div class="gender-metrics">
                    <div class="metric-item">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-gender-male"></i>
                      </div>
                      <div class="metric-copy">
                        <span class="metric-label">Hombres</span>
                        <span class="metric-period">Hoy</span>
                      </div>
                      <div class="metric-count">
                        <h6 data-dashboard-count="ingresosGenero.hombres">{{ $ingresosGenero['hombres'] ?? 0 }}</h6>
                      </div>
                    </div>
                    <div class="metric-item">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-gender-female"></i>
                      </div>
                      <div class="metric-copy">
                        <span class="metric-label">Mujeres</span>
                        <span class="metric-period">Hoy</span>
                      </div>
                      <div class="metric-count">
                        <h6 data-dashboard-count="ingresosGenero.mujeres">{{ $ingresosGenero['mujeres'] ?? 0 }}</h6>
                      </div>
                    </div>
                    <div class="metric-item">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="bi bi-gender-ambiguous"></i>
                      </div>
                      <div class="metric-copy">
                        <span class="metric-label">Otros</span>
                        <span class="metric-period">Hoy</span>
                      </div>
                      <div class="metric-count">
                        <h6 data-dashboard-count="ingresosGenero.otros">{{ $ingresosGenero['otros'] ?? 0 }}</h6>
                      </div>
                    </div>
                  </div>

                </div>
              </div>

            </div><!-- End Customers Card -->

            <!-- Reports -->
            <div class="col-12">
              <div class="card">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filtro</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Hoy</a></li>
                    <li><a class="dropdown-item" href="#">Mes</a></li>
                    <li><a class="dropdown-item" href="#">AÃ±o</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Entrada / Salida / Visitantes</h5>

                  <!-- Line Chart -->
                  <div id="reportsChart"></div>

                  <script>
                    document.addEventListener("DOMContentLoaded", () => {
                      new ApexCharts(document.querySelector("#reportsChart"), {
                        series: [{
                          name: 'Entrada',
                          data: @json($chartEntradas),
                        }, {
                          name: 'Salida',
                          data: @json($chartSalidas)
                        }, {
                          name: 'Visitantes',
                          data: @json($chartVisitantes)
                        }],
                        chart: {
                          height: 350,
                          type: 'area',
                          toolbar: {
                            show: false
                          },
                        },
                        markers: {
                          size: 4
                        },
                        colors: ['#33CC33', '#FF0000', '#FFCC00'],
                        fill: {
                          type: "gradient",
                          gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.3,
                            opacityTo: 0.4,
                            stops: [0, 90, 100]
                          }
                        },
                        dataLabels: {
                          enabled: false
                        },
                        stroke: {
                          curve: 'smooth',
                          width: 2
                        },
                        xaxis: {
                          categories: @json($chartCategorias)
                        },
                        tooltip: {
                          x: {
                            format: 'dd/MM'
                          },
                        }
                      }).render();
                    });
                  </script>
                  <!-- End Line Chart -->

                </div>

              </div>
            </div>
            <!-- End Reports -->
            <div class="col-12">
              <div class="card recent-sales overflow-auto">

                <div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Hoy</a></li>
                    <li><a class="dropdown-item" href="#">Mes</a></li>
                    <li><a class="dropdown-item" href="#">AÃ±o</a></li>
                  </ul>
                </div>

                <div class="card-body">
                  <h5 class="card-title">Historial</h5>

                  <div class="datatable-wrapper datatable-loading no-footer sortable searchable fixed-columns">

                    <div class="datatable-container">
                      <table class="table table-borderless datatable datatable-table">
                        <thead>
                          <tr>
                            <th scope="col" data-sortable="true" style="width: 11.708253358925145%;"><button class="datatable-sorter">ID Ficha</button></th>
                            <th scope="col" data-sortable="true" style="width: 23.608445297504797%;"><button class="datatable-sorter">Nombre</button></th>
                            <th scope="col" data-sortable="true" aria-sort="descending" class="datatable-descending" style="width: 35.70057581573896%;"><button class="datatable-sorter">Centro</button></th>
                            <th scope="col" data-sortable="true" style="width: 12.859884836852206%;"><button class="datatable-sorter">Entrada</button></th>
                            <th scope="col" data-sortable="true" class="red" style="width: 16.122840690978887%;"><button class="datatable-sorter">Salida</button></th>
                          </tr>
                        </thead>
                        <tbody data-live-table="historial">
                          @include('partials.home.historial-rows', ['historial' => $historial])
                        </tbody>
                      </table>
                    </div>


                    <div class="modal fade" id="basicModal" tabindex="-1">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">Reiniciar Sistema</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            Â¿EstÃ¡s seguro que deseas reiniciar el sistema?
                          </div>
                          <div class="modal-footer">
                            <a href=""class="btn btn-secondary" type="button" data-bs-dismiss="modal">cancelar</a>
                            <a href="pages-login.php" type="button" class="btn btn-primary">Reiniciar</a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

              </div>
            </div>






          </div>
        </div>
        <!-- End Left side columns -->

        <!-- Right side columns -->
        <div class="col-12 col-xxl-4">

          <!-- Recent Activity -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filtro</h6>
                </li>

                <li><a class="dropdown-item" href="#">Hoy</a></li>
                <li><a class="dropdown-item" href="#">Mes</a></li>
                <li><a class="dropdown-item" href="#">AÃ±o</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Herramientas externas</h5>

              <div class="activity" data-live-table="actividad-dispositivos">
                @include('partials.home.actividad-dispositivos', ['actividadDispositivos' => $actividadDispositivos])
              </div>

            </div>
          </div><!-- End Recent Activity -->

          <!-- Budget Report -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filtro</h6>
                </li>
                <li><a class="dropdown-item" href="#">Hoy</a></li>
                <li><a class="dropdown-item" href="#">Mes</a></li>
                <li><a class="dropdown-item" href="#">AÃ±o</a></li>
              </ul>
            </div>
            <div class="card-body pb-0">
              <h5 class="card-title">Tipos de persona <span>| Registro</span></h5>
              <div id="budgetChart" style="min-height: 400px;" class="echart"></div>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  const tipoPersonaLabels = @json($tipoPersonaLabels);
                  const tipoPersonaValores = @json($tipoPersonaValores);
                  const tipoPersonaChartElement = document.querySelector("#budgetChart");

                  function tipoPersonaChartOptions(labels, values) {
                    const maxValue = Math.max(1, ...values.map((value) => Number(value) || 0)) + 5;

                    return {
                    legend: {
                      data: ['Registros'],
                      textStyle: {
                        color: '#000', // Color del texto de la leyenda
                      }
                    },
                    radar: {
                      indicator: labels.map((label) => ({
                        name: label,
                        max: maxValue
                      })),
                      axisLine: {
                        lineStyle: {
                          color: '#333' // Color de las lÃ­neas de los ejes
                        }
                      },
                      axisLabel: {
                        color: '#999' // Color de las etiquetas de los ejes
                      }
                    },
                    series: [{
                      name: 'Registros por tipo',
                      type: 'radar',
                      label: {
                        show: true,
                        color: '#1f2937',
                        fontWeight: 600,
                        formatter: (params) => Number(params.value).toLocaleString('es-CO')
                      },
                      data: [{
                        value: values,
                        name: 'Registros',
                        itemStyle: {
                          color: '#3366CC'
                        }
                      }],
                      areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [ // Estilo de Ã¡rea con gradiente
                          {
                            offset: 0,
                            color: 'rgba(255, 99, 71, 0.3)'
                          }, // Color de fondo para 'Hombre'
                          {
                            offset: 1,
                            color: 'rgba(255, 99, 71, 0.1)'
                          }
                        ])
                      }
                    }],
                    colors: ['#00FFFF', '#FF0000'], // Colores de las series, pero se sobrescriben dentro de cada serie
                    };
                  }

                  if (tipoPersonaChartElement) {
                    window.tipoPersonaChart = echarts.init(tipoPersonaChartElement);
                    window.updateTipoPersonaChart = (labels, values) => {
                      window.tipoPersonaChart.setOption(tipoPersonaChartOptions(labels, values), true);
                    };
                    window.updateTipoPersonaChart(tipoPersonaLabels, tipoPersonaValores);
                  }
                });
              </script>

            </div>
          </div><!-- End Budget Report -->

          <!-- Website Traffic -->
          <div class="card">
            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filtro</h6>
                </li>

                <li><a class="dropdown-item" href="#">Hoy</a></li>
                <li><a class="dropdown-item" href="#">Mes</a></li>
                <li><a class="dropdown-item" href="#">AÃ±o</a></li>
              </ul>
            </div>

            {{-- <div class="card-body pb-0">
              <h5 class="card-title">Personas por centro</h5>

              <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  echarts.init(document.querySelector("#trafficChart")).setOption({
                    tooltip: {
                      trigger: 'item'
                    },
                    legend: {
                      top: '5%',
                      left: 'center'
                    },
                    color: ['#6699CC', '#33CC99', '#9966FF', '#FFFFCC', '#FFCC00', '#FFCCFF'], // Colores personalizados
                    series: [{
                      name: 'Access From',
                      type: 'pie',
                      radius: ['40%', '70%'],
                      avoidLabelOverlap: false,
                      label: {
                        show: false,
                        position: 'center'
                      },
                      emphasis: {
                        label: {
                          show: true,
                          fontSize: '18',
                          fontWeight: 'bold'
                        }
                      },
                      labelLine: {
                        show: false
                      },
                      data: @json($traficoCentroLabels->zip($traficoCentroValores)->map(fn ($item) => ['name' => $item[0], 'value' => $item[1]])->values())
                    }]
                  });
                });
              </script>


            </div> --}}
          </div><!-- End Website Traffic -->


        </div><!-- End Right side columns -->

      </div>
    </section>

<style>
  .carnet-floating-menu {
    bottom: 24px;
    position: fixed;
    right: 24px;
    z-index: 1050;
  }

  .carnet-floating-menu .btn-main {
    align-items: center;
    border-radius: 50%;
    box-shadow: 0 12px 30px rgba(13, 110, 253, 0.28);
    display: inline-flex;
    height: 58px;
    justify-content: center;
    width: 58px;
  }

  .carnet-floating-menu .dropdown-menu {
    min-width: 220px;
  }

  .home-scanner-viewport {
    position: relative;
    overflow: hidden;
    min-height: 360px;
    border-radius: 8px;
    background: #111827;
  }

  .home-scanner-reader,
  .home-scanner-reader video {
    width: 100% !important;
    min-height: 360px;
    object-fit: cover;
  }

  .home-scanner-reader > div {
    border: 0 !important;
  }

  .home-scanner-frame {
    position: absolute;
    inset: 16%;
    border: 3px solid rgba(255, 255, 255, .92);
    border-radius: 8px;
    box-shadow: 0 0 0 999px rgba(0, 0, 0, .24);
    pointer-events: none;
  }

  .home-scanner-status {
    border: 0;
    background: #eef2ff;
    color: #1e3a8a;
  }

  .home-scanner-status.is-error {
    background: #fee2e2;
    color: #991b1b;
  }

  .home-scanner-status.is-success {
    background: #dcfce7;
    color: #166534;
  }

  @media (max-width: 575.98px) {
    .home-scanner-viewport,
    .home-scanner-reader,
    .home-scanner-reader video {
      min-height: 420px;
    }

    .home-scanner-mode-group {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      width: 100%;
    }
  }
</style>

<div class="dropup carnet-floating-menu">
  <button class="btn btn-primary btn-main" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Lector de carnets">
    <i class="bi bi-camera-video fs-4"></i>
  </button>
  <ul class="dropdown-menu dropdown-menu-end shadow">
    <li>
      <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#homeCarnetScannerModal">
        <i class="bi bi-camera-video me-2"></i>Lector con camara
      </button>
    </li>
    <li>
      <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#carnetListModal">
        <i class="bi bi-table me-2"></i>Tabla de carnets
      </button>
    </li>
    <li>
      <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#deviceAccessModal">
        <i class="bi bi-arrow-left-right me-2"></i>Registrar movimiento
      </button>
    </li>
    <li>
      <button class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#deviceCreateModal">
        <i class="bi bi-hdd-network me-2"></i>Agregar dispositivo
      </button>
    </li>
    <li><hr class="dropdown-divider"></li>
    <li>
      <a class="dropdown-item" href="{{ route('carnets.index') }}">
        <i class="bi bi-box-arrow-up-right me-2"></i>Abrir modulo
      </a>
    </li>
  </ul>
</div>

<div class="modal fade" id="homeCarnetScannerModal" tabindex="-1" aria-labelledby="homeCarnetScannerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title" id="homeCarnetScannerModalLabel">Lector con camara</h5>
          <div class="text-muted small">Escanea el QR del carnet para registrar entrada o salida.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-lg-8">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
              <div class="btn-group home-scanner-mode-group" role="group" aria-label="Movimiento">
                <button type="button" class="btn btn-success active" data-home-scan-mode="entrada">
                  <i class="bi bi-box-arrow-in-right"></i> Entrada
                </button>
                <button type="button" class="btn btn-outline-danger" data-home-scan-mode="salida">
                  <i class="bi bi-box-arrow-right"></i> Salida
                </button>
              </div>
              <select class="form-select w-auto flex-grow-1" id="homeScannerCameraSelect" aria-label="Camara"></select>
            </div>

            <div class="home-scanner-viewport">
              <div id="homeScannerReader" class="home-scanner-reader"></div>
              <div class="home-scanner-frame" aria-hidden="true"></div>
            </div>
          </div>

          <div class="col-lg-4">
            <div class="border rounded p-3 mb-3">
              <div class="small text-muted">Ultima lectura</div>
              <code id="homeScannerLastCode">Sin lectura</code>
            </div>

            <form id="homeScannerManualForm" class="vstack gap-3">
              <div>
                <label for="homeScannerManualCode" class="form-label">Codigo manual</label>
                <input type="text" class="form-control" id="homeScannerManualCode" autocomplete="off">
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i> Registrar
              </button>
            </form>

            <div class="row g-2 mt-3">
              <div class="col-6">
                <div class="border rounded p-3 h-100">
                  <div class="text-muted small">Entradas hoy</div>
                  <div class="fs-4 fw-bold text-success" data-dashboard-count="entradasHoy">{{ $entradasHoy }}</div>
                </div>
              </div>
              <div class="col-6">
                <div class="border rounded p-3 h-100">
                  <div class="text-muted small">Salidas hoy</div>
                  <div class="fs-4 fw-bold text-danger" data-dashboard-count="salidasHoy">{{ $salidasHoy }}</div>
                </div>
              </div>
            </div>

            <div class="alert home-scanner-status mt-3 mb-0" id="homeScannerStatus" role="status">
              Camara lista.
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="homeScannerStart">
          <i class="bi bi-camera-video"></i> Iniciar
        </button>
        <button type="button" class="btn btn-outline-secondary" id="homeScannerStop" disabled>
          <i class="bi bi-camera-video-off"></i> Detener
        </button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="carnetAccessModal" tabindex="-1" aria-labelledby="carnetAccessModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="carnetAccessModalLabel">Registrar acceso con carnet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('carnets.registrar') }}" method="POST" id="carnetAccessForm" data-ajax-access-form>
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="codigoCarnet" class="form-label">Documento del usuario</label>
            <input type="text" name="codigo" id="codigoCarnet"
              class="form-control {{ $errors->has('codigo') ? 'is-invalid' : '' }}"
              value="{{ old('codigo') }}" placeholder="Ej: 500001">
            @error('codigo')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="row g-3">
            <div class="col-6">
              <div class="border rounded p-3 h-100">
                <div class="text-muted small">Entradas hoy</div>
                <div class="fs-4 fw-bold text-success" data-dashboard-count="entradasHoy">{{ $entradasHoy }}</div>
              </div>
            </div>
            <div class="col-6">
              <div class="border rounded p-3 h-100">
                <div class="text-muted small">Salidas hoy</div>
                <div class="fs-4 fw-bold text-danger" data-dashboard-count="salidasHoy">{{ $salidasHoy }}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="tipo" value="entrada" class="btn btn-success">
            <i class="bi bi-door-open"></i> Entrada
          </button>
          <button type="submit" name="tipo" value="salida" class="btn btn-danger">
            <i class="bi bi-door-closed"></i> Salida
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deviceCreateModal" tabindex="-1" aria-labelledby="deviceCreateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deviceCreateModalLabel">Agregar dispositivo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dispositivos.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="newDeviceName" class="form-label">Nombre</label>
            <input type="text" name="nuevo_dispositivo_nombre" id="newDeviceName"
              class="form-control {{ $errors->has('nuevo_dispositivo_nombre') ? 'is-invalid' : '' }}"
              value="{{ old('nuevo_dispositivo_nombre') }}" placeholder="Ej: Laptop HP 14-DQ5015LA">
            @error('nuevo_dispositivo_nombre')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="newDeviceLocation" class="form-label">Ubicacion</label>
            <input type="text" name="nuevo_dispositivo_ubicacion" id="newDeviceLocation"
              class="form-control {{ $errors->has('nuevo_dispositivo_ubicacion') ? 'is-invalid' : '' }}"
              value="{{ old('nuevo_dispositivo_ubicacion') }}" placeholder="Ej: Herramientas externas">
            @error('nuevo_dispositivo_ubicacion')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="newDeviceIp" class="form-label">IP</label>
            <input type="text" name="nuevo_dispositivo_ip" id="newDeviceIp"
              class="form-control {{ $errors->has('nuevo_dispositivo_ip') ? 'is-invalid' : '' }}"
              value="{{ old('nuevo_dispositivo_ip') }}" placeholder="Opcional">
            @error('nuevo_dispositivo_ip')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Guardar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="deviceAccessModal" tabindex="-1" aria-labelledby="deviceAccessModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deviceAccessModalLabel">Registrar movimiento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="{{ route('dispositivos.registrar') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="devicePersonaDocumento" class="form-label">Documento del responsable</label>
            <input type="text" name="dispositivo_documento" id="devicePersonaDocumento"
              class="form-control {{ $errors->has('dispositivo_documento') ? 'is-invalid' : '' }}"
              value="{{ old('dispositivo_documento') }}" placeholder="Ej: 500001">
            @error('dispositivo_documento')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="deviceName" class="form-label">Dispositivo o herramienta</label>
            <input type="text" name="dispositivo_nombre" id="deviceName"
              class="form-control {{ $errors->has('dispositivo_nombre') ? 'is-invalid' : '' }}"
              value="{{ old('dispositivo_nombre') }}" list="deviceOptions" placeholder="Ej: Laptop HP 14-DQ5015LA">
            <datalist id="deviceOptions" data-live-table="dispositivos-activos">
              @include('partials.home.dispositivo-options', ['dispositivosActivos' => $dispositivosActivos])
            </datalist>
            @error('dispositivo_nombre')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="deviceLocation" class="form-label">Ubicacion</label>
            <input type="text" name="ubicacion" id="deviceLocation"
              class="form-control {{ $errors->has('ubicacion') ? 'is-invalid' : '' }}"
              value="{{ old('ubicacion') }}" placeholder="Ej: Porteria principal">
            @error('ubicacion')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" name="tipo" value="entrada" class="btn btn-success">
            <i class="bi bi-box-arrow-in-right"></i> Entrada
          </button>
          <button type="submit" name="tipo" value="salida" class="btn btn-danger">
            <i class="bi bi-box-arrow-right"></i> Salida
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="carnetListModal" tabindex="-1" aria-labelledby="carnetListModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title" id="carnetListModalLabel">Tabla de carnets activos</h5>
          <div class="text-muted small" data-live-carnet-summary>{{ $carnetsActivos }} carnets QR activos de {{ $personasActivas }} personas activas</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>Persona</th>
                <th>Documento</th>
                <th>Ficha</th>
                <th>Centro</th>
                <th>Carnet</th>
                <th class="text-end">Accion</th>
              </tr>
            </thead>
            <tbody data-live-table="carnets">
              @include('partials.home.carnet-rows', ['personasCarnet' => $personasCarnet])
            </tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <a href="{{ route('carnets.index') }}" class="btn btn-primary">
          <i class="bi bi-person-vcard"></i> Gestionar carnets
        </a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('carnetAccessForm');
    const defaultAccessPhoto = @json(asset('assets/img/profile-img.png'));
    const homeLiveUrl = @json(route('home'));
    let refreshingHomeTables = false;

    if (!form || !window.bootstrap) {
      return;
    }

    const codeInput = form.querySelector('[name="codigo"]');
    const submitButtons = form.querySelectorAll('button[type="submit"]');

    function setButtonsDisabled(disabled) {
      submitButtons.forEach((button) => {
        button.disabled = disabled;
      });
    }

    function clearFieldError() {
      if (!codeInput) {
        return;
      }

      codeInput.classList.remove('is-invalid');
      const feedback = codeInput.parentElement.querySelector('.invalid-feedback');

      if (feedback) {
        feedback.textContent = '';
      }
    }

    function showFieldError(message) {
      if (!codeInput) {
        return;
      }

      let feedback = codeInput.parentElement.querySelector('.invalid-feedback');

      if (!feedback) {
        feedback = document.createElement('div');
        feedback.className = 'invalid-feedback';
        codeInput.insertAdjacentElement('afterend', feedback);
      }

      codeInput.classList.add('is-invalid');
      feedback.textContent = message;
      codeInput.focus();
    }

    function showStatus(message) {
      const existing = document.querySelector('[data-live-access-status]');

      if (existing) {
        existing.remove();
      }

      const pageTitle = document.querySelector('.pagetitle');

      if (!pageTitle) {
        return;
      }

      pageTitle.insertAdjacentHTML('afterend', `
        <div class="alert alert-success alert-dismissible fade show" role="alert" data-live-access-status>
          <span></span>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      `);

      const alert = document.querySelector('[data-live-access-status]');
      alert.querySelector('span').textContent = message;
    }

    function updateDashboardCounters(counters, prefix = '') {
      Object.entries(counters || {}).forEach(([key, value]) => {
        const counterKey = prefix ? `${prefix}.${key}` : key;

        if (value && typeof value === 'object' && !Array.isArray(value)) {
          updateDashboardCounters(value, counterKey);
          return;
        }

        document.querySelectorAll(`[data-dashboard-count="${counterKey}"]`).forEach((element) => {
          element.textContent = value;
        });
      });
    }

    function updateHomeLiveSections(payload) {
      updateDashboardCounters(payload.dashboard);

      if (
        typeof window.updateTipoPersonaChart === 'function'
        && Array.isArray(payload.dashboard?.tipoPersonaLabels)
        && Array.isArray(payload.dashboard?.tipoPersonaValores)
      ) {
        window.updateTipoPersonaChart(payload.dashboard.tipoPersonaLabels, payload.dashboard.tipoPersonaValores);
      }

      const html = payload.html || {};
      const targets = {
        historial: document.querySelector('[data-live-table="historial"]'),
        actividadDispositivos: document.querySelector('[data-live-table="actividad-dispositivos"]'),
        carnets: document.querySelector('[data-live-table="carnets"]'),
        dispositivosActivos: document.querySelector('[data-live-table="dispositivos-activos"]'),
      };

      Object.entries(targets).forEach(([key, target]) => {
        if (target && typeof html[key] === 'string') {
          target.innerHTML = html[key];
        }
      });

      const carnetSummary = document.querySelector('[data-live-carnet-summary]');

      if (carnetSummary && payload.meta?.carnetsResumen) {
        carnetSummary.textContent = payload.meta.carnetsResumen;
      }
    }

    async function refreshHomeTables() {
      if (refreshingHomeTables || document.hidden) {
        return;
      }

      refreshingHomeTables = true;

      try {
        const response = await fetch(homeLiveUrl, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
        });

        if (response.ok) {
          updateHomeLiveSections(await response.json());
        }
      } catch (error) {
        // El siguiente intervalo intentara sincronizar de nuevo.
      } finally {
        refreshingHomeTables = false;
      }
    }

    function ensureAccessResultModal() {
      let modal = document.getElementById('personAccessResultModal');

      if (modal && modal.querySelector('[data-access-title]')) {
        return modal;
      }

      if (modal) {
        modal.remove();
      }

      document.body.insertAdjacentHTML('beforeend', `
        <div class="modal fade" id="personAccessResultModal" tabindex="-1" aria-labelledby="personAccessResultModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content access-result-modal">
              <div class="modal-header border-0 pb-0">
                <div class="d-flex align-items-center gap-2">
                  <span class="access-result-icon" data-access-icon>
                    <i class="bi"></i>
                  </span>
                  <div>
                    <h5 class="modal-title" id="personAccessResultModalLabel" data-access-title></h5>
                    <div class="small text-muted" data-access-status></div>
                  </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="access-person">
                  <img src="" alt="" class="access-person-photo" data-access-photo>
                  <div class="access-person-info">
                    <h4 data-access-name></h4>
                    <div class="text-muted" data-access-document></div>
                  </div>
                </div>
                <div class="access-detail-grid">
                  <div class="access-detail">
                    <span>Centro de formacion</span>
                    <strong data-access-center></strong>
                  </div>
                  <div class="access-detail">
                    <span>Ficha</span>
                    <strong data-access-sheet></strong>
                  </div>
                  <div class="access-detail">
                    <span>Genero</span>
                    <strong data-access-gender></strong>
                  </div>
                  <div class="access-detail access-detail--time">
                    <span data-access-time-label></span>
                    <strong data-access-time></strong>
                    <small data-access-date></small>
                  </div>
                </div>
              </div>
              <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
      `);

      return document.getElementById('personAccessResultModal');
    }

    let accessResultTimer = null;

    function showAccessResultModal(data, options = {}) {
      const modal = ensureAccessResultModal();
      const autoCloseMs = Number(options.autoCloseMs || 0);
      const isEntrada = data.tipo === 'entrada';
      const icon = modal.querySelector('[data-access-icon]');
      const iconElement = icon.querySelector('i');
      const photo = modal.querySelector('[data-access-photo]');

      icon.className = `access-result-icon ${isEntrada ? 'access-result-icon--in' : 'access-result-icon--out'}`;
      iconElement.className = `bi ${isEntrada ? 'bi-door-open' : 'bi-door-closed'}`;
      photo.onerror = () => {
        photo.onerror = null;
        photo.src = defaultAccessPhoto;
      };
      photo.src = data.foto || defaultAccessPhoto;
      photo.alt = `Foto de ${data.nombre || 'persona'}`;

      modal.querySelector('[data-access-title]').textContent = data.titulo || 'Registro realizado';
      modal.querySelector('[data-access-status]').textContent = data.estado || '';
      modal.querySelector('[data-access-name]').textContent = data.nombre || '';
      modal.querySelector('[data-access-document]').textContent = data.documento || '';
      modal.querySelector('[data-access-center]').textContent = data.centro || 'N/A';
      modal.querySelector('[data-access-sheet]').textContent = data.ficha || 'N/A';
      modal.querySelector('[data-access-gender]').textContent = data.genero || 'N/A';
      modal.querySelector('[data-access-time-label]').textContent = `Hora de ${isEntrada ? 'entrada' : 'salida'}`;
      modal.querySelector('[data-access-time]').textContent = data.hora || '';
      modal.querySelector('[data-access-date]').textContent = data.fecha || '';

      if (accessResultTimer) {
        window.clearTimeout(accessResultTimer);
      }

      if (typeof options.onHidden === 'function') {
        modal.addEventListener('hidden.bs.modal', options.onHidden, { once: true });
      }

      const modalInstance = bootstrap.Modal.getOrCreateInstance(modal);
      modalInstance.show();

      if (autoCloseMs > 0) {
        accessResultTimer = window.setTimeout(() => {
          modalInstance.hide();
        }, autoCloseMs);
      }
    }

    const scannerModal = document.getElementById('homeCarnetScannerModal');

    if (scannerModal) {
      const readerId = 'homeScannerReader';
      const statusEl = document.getElementById('homeScannerStatus');
      const startScannerButton = document.getElementById('homeScannerStart');
      const stopScannerButton = document.getElementById('homeScannerStop');
      const cameraSelect = document.getElementById('homeScannerCameraSelect');
      const lastCodeEl = document.getElementById('homeScannerLastCode');
      const manualForm = document.getElementById('homeScannerManualForm');
      const manualInput = document.getElementById('homeScannerManualCode');
      const modeButtons = scannerModal.querySelectorAll('[data-home-scan-mode]');
      const registerUrl = @json(route('carnets.registrar'));
      const csrfToken = @json(csrf_token());

      let scanner = null;
      let cameras = [];
      let currentMode = 'entrada';
      let currentCameraId = null;
      let scannerRunning = false;
      let processingScan = false;
      let lastScanMessageAt = 0;

      function setScannerStatus(message, type = 'info') {
        statusEl.textContent = message;
        statusEl.classList.toggle('is-error', type === 'error');
        statusEl.classList.toggle('is-success', type === 'success');
      }

      function setScannerButtons(isRunning) {
        startScannerButton.disabled = isRunning;
        stopScannerButton.disabled = !isRunning;
      }

      function setScannerMode(mode) {
        currentMode = mode;

        modeButtons.forEach((button) => {
          const active = button.dataset.homeScanMode === mode;
          button.classList.toggle('active', active);

          if (button.dataset.homeScanMode === 'entrada') {
            button.classList.toggle('btn-success', active);
            button.classList.toggle('btn-outline-success', !active);
          } else {
            button.classList.toggle('btn-danger', active);
            button.classList.toggle('btn-outline-danger', !active);
          }
        });
      }

      function preferredCamera() {
        const camoCamera = cameras.find((camera) => /camo|reincubate/i.test(camera.label));
        const backCamera = cameras.find((camera) => /back|rear|trasera|environment/i.test(camera.label));

        return camoCamera?.id || backCamera?.id || cameras[cameras.length - 1]?.id || null;
      }

      function scannerConfig() {
        const config = {
          fps: 15,
          disableFlip: false,
          qrbox: (viewfinderWidth, viewfinderHeight) => {
            const minEdge = Math.min(viewfinderWidth, viewfinderHeight);
            const boxSize = Math.max(240, Math.floor(minEdge * 0.78));

            return {
              width: boxSize,
              height: boxSize,
            };
          },
        };

        if (window.Html5QrcodeSupportedFormats?.QR_CODE) {
          config.formatsToSupport = [Html5QrcodeSupportedFormats.QR_CODE];
        }

        return config;
      }

      async function loadScannerCameras() {
        if (!window.Html5Qrcode) {
          throw new Error('No se pudo cargar el lector QR.');
        }

        cameras = await Html5Qrcode.getCameras();
        cameraSelect.innerHTML = '';

        cameras.forEach((camera, index) => {
          const option = document.createElement('option');
          option.value = camera.id;
          option.textContent = camera.label || `Camara ${index + 1}`;
          cameraSelect.appendChild(option);
        });

        currentCameraId = cameraSelect.value || preferredCamera();

        if (currentCameraId) {
          cameraSelect.value = currentCameraId;
        }
      }

      async function stopHomeScanner() {
        if (!scanner || !scannerRunning) {
          setScannerButtons(false);
          return;
        }

        await scanner.stop();
        scannerRunning = false;
        setScannerButtons(false);
        setScannerStatus('Camara detenida.');
      }

      async function startHomeScanner() {
        if (scannerRunning) {
          return;
        }

        if (!window.isSecureContext && location.hostname !== 'localhost' && location.hostname !== '127.0.0.1') {
          setScannerStatus('La camara requiere HTTPS o localhost.', 'error');
          return;
        }

        if (!window.Html5Qrcode) {
          setScannerStatus('No se pudo cargar el lector QR.', 'error');
          return;
        }

        if (!scanner) {
          scanner = new Html5Qrcode(readerId);
        }

        if (!cameras.length) {
          await loadScannerCameras();
        }

        currentCameraId = cameraSelect.value || currentCameraId;

        if (!currentCameraId) {
          setScannerStatus('No se encontro una camara disponible.', 'error');
          return;
        }

        await scanner.start(
          currentCameraId,
          scannerConfig(),
          handleHomeScannerCode,
          () => {
            const now = Date.now();

            if (!processingScan && now - lastScanMessageAt > 2500) {
              lastScanMessageAt = now;
              setScannerStatus('Camara activa. Buscando QR...');
            }
          }
        );

        scannerRunning = true;
        setScannerButtons(true);
        setScannerStatus('Camara activa.');
      }

      async function registerScannerCode(code) {
        const formData = new FormData();
        formData.append('codigo', code);
        formData.append('tipo', currentMode);

        const response = await fetch(registerUrl, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: formData,
        });

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
          throw new Error(payload.errors?.codigo?.[0] || payload.message || 'No se pudo registrar el acceso.');
        }

        return payload;
      }

      async function handleHomeScannerCode(decodedText) {
        const code = String(decodedText || '').trim();

        if (!code || processingScan) {
          return;
        }

        processingScan = true;
        lastCodeEl.textContent = code;
        setScannerStatus('Registrando lectura...');

        try {
          if (scanner && scannerRunning) {
            await scanner.stop();
            scannerRunning = false;
          }

          setScannerButtons(false);
          const payload = await registerScannerCode(code);

          updateDashboardCounters(payload.dashboard);

          if (
            typeof window.updateTipoPersonaChart === 'function'
            && Array.isArray(payload.dashboard?.tipoPersonaLabels)
            && Array.isArray(payload.dashboard?.tipoPersonaValores)
          ) {
            window.updateTipoPersonaChart(payload.dashboard.tipoPersonaLabels, payload.dashboard.tipoPersonaValores);
          }

          refreshHomeTables();
          showStatus(payload.message || 'Acceso registrado correctamente.');
          setScannerStatus(payload.message || 'Acceso registrado correctamente.', 'success');

          if (payload.access_modal) {
            showAccessResultModal(payload.access_modal, {
              autoCloseMs: 5000,
              onHidden: () => {
                if (scannerModal.classList.contains('show')) {
                  startHomeScanner().catch((error) => {
                    setScannerStatus(error.message || 'No se pudo reactivar la camara.', 'error');
                    setScannerButtons(false);
                  });
                }
              },
            });
          }
        } catch (error) {
          setScannerStatus(error.message || 'Lectura rechazada.', 'error');
        } finally {
          processingScan = false;
          setScannerButtons(false);
        }
      }

      modeButtons.forEach((button) => {
        button.addEventListener('click', () => setScannerMode(button.dataset.homeScanMode));
      });

      startScannerButton.addEventListener('click', () => {
        startHomeScanner().catch((error) => {
          setScannerStatus(error.message || 'No se pudo acceder a la camara.', 'error');
          setScannerButtons(false);
        });
      });

      stopScannerButton.addEventListener('click', () => {
        stopHomeScanner().catch((error) => setScannerStatus(error.message || 'No se pudo detener la camara.', 'error'));
      });

      cameraSelect.addEventListener('change', async () => {
        currentCameraId = cameraSelect.value;

        if (scannerRunning) {
          await stopHomeScanner();
          await startHomeScanner();
        }
      });

      manualForm.addEventListener('submit', (event) => {
        event.preventDefault();
        handleHomeScannerCode(manualInput.value);
        manualInput.value = '';
      });

      scannerModal.addEventListener('shown.bs.modal', () => {
        setScannerStatus('Activando camara...');
        loadScannerCameras()
          .then(() => startHomeScanner())
          .catch((error) => setScannerStatus(error.message || 'Permite el acceso a la camara.', 'error'));
      });

      scannerModal.addEventListener('hidden.bs.modal', () => {
        stopHomeScanner().catch(() => {});
      });
    }

    form.addEventListener('submit', async (event) => {
      event.preventDefault();
      clearFieldError();
      setButtonsDisabled(true);

      const formData = new FormData(form);
      const submitter = event.submitter;

      if (submitter?.name) {
        formData.set(submitter.name, submitter.value);
      }

      try {
        const response = await fetch(form.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          body: formData,
        });

        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
          const message = payload.errors?.codigo?.[0] || payload.message || 'No se pudo registrar el acceso.';
          showFieldError(message);
          return;
        }

        updateDashboardCounters(payload.dashboard);
        if (
          typeof window.updateTipoPersonaChart === 'function'
          && Array.isArray(payload.dashboard?.tipoPersonaLabels)
          && Array.isArray(payload.dashboard?.tipoPersonaValores)
        ) {
          window.updateTipoPersonaChart(payload.dashboard.tipoPersonaLabels, payload.dashboard.tipoPersonaValores);
        }
        refreshHomeTables();
        showStatus(payload.message || 'Acceso registrado correctamente.');
        bootstrap.Modal.getInstance(document.getElementById('carnetAccessModal'))?.hide();
        form.reset();

        if (payload.access_modal) {
          showAccessResultModal(payload.access_modal);
        }
      } catch (error) {
        showFieldError('No se pudo conectar con el servidor. Intenta de nuevo.');
      } finally {
        setButtonsDisabled(false);
      }
    });

    window.addEventListener('focus', refreshHomeTables);
    setInterval(refreshHomeTables, 5000);
  });
</script>

@if (session('access_modal'))
  @php
    $accessModal = session('access_modal');
    $isEntrada = ($accessModal['tipo'] ?? '') === 'entrada';
  @endphp

  <div class="modal fade" id="personAccessResultModal" tabindex="-1" aria-labelledby="personAccessResultModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content access-result-modal">
        <div class="modal-header border-0 pb-0">
          <div class="d-flex align-items-center gap-2">
            <span class="access-result-icon {{ $isEntrada ? 'access-result-icon--in' : 'access-result-icon--out' }}">
              <i class="bi {{ $isEntrada ? 'bi-door-open' : 'bi-door-closed' }}"></i>
            </span>
            <div>
              <h5 class="modal-title" id="personAccessResultModalLabel">{{ $accessModal['titulo'] }}</h5>
              <div class="small text-muted">{{ $accessModal['estado'] }}</div>
            </div>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <div class="access-person">
            <img
              src="{{ $accessModal['foto'] ?? asset('assets/img/profile-img.png') }}"
              alt="Foto de {{ $accessModal['nombre'] }}"
              class="access-person-photo"
              onerror="this.onerror=null;this.src='{{ asset('assets/img/profile-img.png') }}';"
            >
            <div class="access-person-info">
              <h4>{{ $accessModal['nombre'] }}</h4>
              <div class="text-muted">{{ $accessModal['documento'] }}</div>
            </div>
          </div>

          <div class="access-detail-grid">
            <div class="access-detail">
              <span>Centro de formacion</span>
              <strong>{{ $accessModal['centro'] }}</strong>
            </div>
            <div class="access-detail">
              <span>Ficha</span>
              <strong>{{ $accessModal['ficha'] }}</strong>
            </div>
            <div class="access-detail">
              <span>Genero</span>
              <strong>{{ $accessModal['genero'] ?? 'N/A' }}</strong>
            </div>
            <div class="access-detail access-detail--time">
              <span>Hora de {{ $isEntrada ? 'entrada' : 'salida' }}</span>
              <strong>{{ $accessModal['hora'] }}</strong>
              <small>{{ $accessModal['fecha'] }}</small>
            </div>
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('personAccessResultModal');

      if (modal && window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(modal).show();
      }
    });
  </script>
@endif

@if ($errors->has('codigo') || $errors->has('dispositivo_documento') || $errors->has('dispositivo_nombre') || $errors->has('ubicacion') || $errors->has('nuevo_dispositivo_nombre') || $errors->has('nuevo_dispositivo_ubicacion') || $errors->has('nuevo_dispositivo_ip'))
  @php
    $modalConError = 'deviceAccessModal';

    if ($errors->has('codigo')) {
        $modalConError = 'carnetAccessModal';
    } elseif ($errors->has('nuevo_dispositivo_nombre') || $errors->has('nuevo_dispositivo_ubicacion') || $errors->has('nuevo_dispositivo_ip')) {
        $modalConError = 'deviceCreateModal';
    }
  @endphp
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('{{ $modalConError }}');
      if (modal && window.bootstrap) {
        bootstrap.Modal.getOrCreateInstance(modal).show();
      }
    });
  </script>
@endif
@include('partials.modal-emergencia')
@endsection
