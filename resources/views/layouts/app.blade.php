<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/bootstrap-5.0.2-dist/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/fontawesome-free-5.15.4-web/css/all.min.css')}}">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <main class="d-flex justify-content-center align-items-center vh-100">
            @yield('content')
        </main>
    </div>
    <script src="{{asset('assets/plugins/bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/adminlte.min.js')}}"></script>
    <script src="{{asset('assets/plugins/fontawesome-free-5.15.4-web/js/all.min.js')}}""></script>

</body>
</html>
