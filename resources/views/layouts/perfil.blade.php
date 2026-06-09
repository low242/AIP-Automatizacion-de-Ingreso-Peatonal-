<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.head')
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
</body>

</html>
