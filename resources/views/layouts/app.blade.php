<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - AdminLTE</title>
    @vite(['resources/sass/app.scss'])
    @stack('styles')
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        
        {{-- Navbar --}}
        @include('partials.navbar')
        
        {{-- Sidebar --}}
        @include('partials.sidebar')
        
        {{-- Content Wrapper --}}
        <div class="content-wrapper p-4">
            @yield('content')
        </div>
        
    </div>
    @stack('js')
    @stack('scripts')
    @vite(['resources/js/app.js'])
</body>

</html>