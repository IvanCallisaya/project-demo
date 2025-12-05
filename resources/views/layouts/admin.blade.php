<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AdminLTE + Laravel 10</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        @include('partials.navbar')
        @include('partials.sidebar')

        <div class="content-wrapper">
            <section class="content p-3">
                @yield('content')
            </section>
        </div>

    </div>
</body>

</html>