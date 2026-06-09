<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
    @stack('styles')
</head>

<body>
    @include('partials.loader')
    <header>
        @include('partials.header')
    </header>
    @include('partials.sidebar')
    <main id="main" class="main">
        @yield('content')
    </main>
    <footer id="footer" class="footer border-0">
        @include('partials.footer')
    </footer>
    @include('partials.scripts')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>

</html>
