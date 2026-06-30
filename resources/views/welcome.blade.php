<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body text-center p-5">
                        <h1 class="mb-3">{{ config('app.name', 'Laravel') }}</h1>
                        <p class="text-muted mb-4">Aplicación Laravel con autenticación Bootstrap.</p>

                        @auth
                            <a href="{{ url('/home') }}" class="btn btn-primary">Ir al panel</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary me-2">Iniciar sesión</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary">Registrarse</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
