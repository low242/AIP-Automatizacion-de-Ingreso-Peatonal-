@php($user = auth()->user())

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="/home" class="logo d-flex align-items-center">
            <img src="{{ asset('assets/img/logoaip.svg') }}" alt="">
        </a>
        <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Buscar" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div><!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->

            <li class="nav-item dropdown">

                <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell"></i>
                    <span class="badge bg-primary badge-number">4</span>
                </a><!-- End Notification Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                    <li class="dropdown-header">
                        Tiene 4 Notificaciones
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-exclamation-circle text-warning"></i>
                        <div>
                            <h4>Advertencia</h4>
                            <p>Completar Registro</p>
                            <p>Hace 30 min. </p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-x-circle text-danger"></i>
                        <div>
                            <h4>Error</h4>
                            <p>Los datos no han sido registrados</p>
                            <p>Hace 1 hr.</p>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li class="notification-item">
                        <i class="bi bi-check-circle text-success"></i>
                        <div>
                            <h4>Aprobado</h4>
                            <p>Se guardó correctamente la información</p>
                            <p>Hace 2 hrs.</p>
                        </div>
                    </li>



                </ul><!-- End Notification Dropdown Items -->

            </li><!-- End Notification Nav -->





            <li class="nav-item dropdown pe-3">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ $user?->profile_photo_url ?? asset('assets/img/profile-img.png') }}" alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ $user?->name ?? 'Usuario' }}</span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile profile-menu">
                    <li class="dropdown-header profile-menu-header">
                        <img src="{{ $user?->profile_photo_url ?? asset('assets/img/profile-img.png') }}" alt="Profile" class="profile-menu-avatar">
                        <div>
                            <h6>{{ $user?->name ?? 'Usuario' }}</h6>
                            <span>{{ $user?->role_label ?? 'Sin rol' }}</span>
                        </div>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item profile-menu-item d-flex align-items-center" href="{{ url('perfil') }}">
                            <i class="bi bi-person"></i>
                            <span>Mi Perfil</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                            @csrf
                            <button type="submit" class="dropdown-item profile-menu-item profile-menu-logout d-flex align-items-center">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Cerrar Sesi&oacute;n</span>
                            </button>
                        </form>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header>
