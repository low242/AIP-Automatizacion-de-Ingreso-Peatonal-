<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
            <a class="nav-link " href="/home">
                <i class="bi bi-grid"></i>
                <span>Inicio</span>
            </a>
        </li><!-- End Dashboard Nav -->

        {{-- <li class="nav-item">
      <a class="nav-link collapsed" href="{{ route('carnets.index') }}">
        <i class="bi bi-person-badge"></i>
        <span>Carnets</span>
        </a>
        </li> --}}

        {{-- <li class="nav-item">
      <a href="#" class="nav-link collapsed" data-bs-toggle="modal" data-bs-target="#basicModal">
        <i class="bi bi-arrow-repeat"></i> <span>Reiniciar Sistema</span>
      </a>
    </li> --}}



        <li class="nav-item">
            <a class="nav-link " data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-palette"></i></i><span>Temas</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="components-nav" class="nav-content collapse show" data-bs-parent="#sidebar-nav">

                <li>
                    <a href="#" id="btnLight">
                        <i class="bi bi-brightness-high"></i>
                        <span>Claro</span>
                    </a>
                </li>

                <li>
                    <a href="#" id="btnDark">
                        <i class="bi bi-moon"></i>
                        <span>Oscuro</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->


        </li><!-- End Contact Page Nav -->

        @if (auth()->user()?->esSuperAdmin() || auth()->user()?->esAdmin())
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('reportes.registros-hoy') }}">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span>Obtener Reporte</span>
                </a>
            </li>
        @endif



        @if (auth()->user()?->esSuperAdmin() || auth()->user()?->esAdmin() || auth()->user()?->esVigilante())
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('visitantes.index') }}">
                    <i class="bi bi-person-plus"></i>
                    <span>Visitantes</span>
                </a>
            </li>
        @endif


        <li class="nav-item">
            <a class="nav-link collapsed" href="tables-data">
                <i class="bi bi-info-circle"></i>
                <span>Tabla De Información</span>
            </a>

        </li><!-- End Contact Page Nav -->
        @if (auth()->user()?->esSuperAdmin() || auth()->user()?->esAdmin())
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('users.create') }}">
                    <i i class="bi bi-shield-lock"></i>
                    <span>Crear Vigilante</span>
                </a>
            </li>

            <a class="nav-link collapsed" href="{{ route('users.index') }}">
                <i class="bi bi-shield-lock"></i>
                <span>Gestionar Vigilantes</span>
            </a>
        @endif

        <li class="nav-item">
            <a href="#" class="nav-link collapsed  text-danger" data-bs-toggle="modal"
                data-bs-target="#fullscreenModal">
                <i class="bi bi-arrow-repeat  text-danger"></i> <span>Emergencia</span>
            </a>




        </li><!-- End Contact Page Nav -->

        <!-- Full Screen Modal -->

        </div><!-- End Full Screen Modal-->
    </ul>

</aside>
