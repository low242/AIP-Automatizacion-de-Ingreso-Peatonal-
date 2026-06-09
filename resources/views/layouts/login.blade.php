<!DOCTYPE html>
<html lang="es">

<head>
    @include('partials.head')
</head>

<body class="login-layout">
    <main>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @yield('content')
    </main>
    {{-- <footer  id="footerLog" class="footerLog border-0">
        @include('partials.footer')
    </footer> --}}
    @include('partials.scripts')
</body>


</html>
