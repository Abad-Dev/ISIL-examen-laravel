<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Inaut'))</title>

    @include('partials.theme-script')

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#fafaf5] font-sans text-slate-800 antialiased transition-colors dark:bg-slate-950 dark:text-slate-100">
    <div class="relative flex min-h-screen overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -left-20 top-10 size-72 rounded-full bg-palette-green/30 blur-3xl dark:bg-palette-green/15"></div>
            <div class="absolute -right-16 top-1/3 size-64 rounded-full bg-palette-yellow/40 blur-3xl dark:bg-palette-yellow/10"></div>
            <div class="absolute bottom-10 left-1/3 size-56 rounded-full bg-palette-orange/30 blur-3xl dark:bg-palette-orange/10"></div>
        </div>

        <x-app-sidebar />

        <div class="relative z-10 flex min-h-screen min-w-0 flex-1 flex-col">
            @include('components.app-mobile-header')

            <main class="mx-auto w-full max-w-5xl flex-1 px-4 py-6 sm:px-6 sm:py-8">
                @yield('content')
            </main>
        </div>
    </div>

    <x-confirm-modal />

    @stack('scripts')
</body>
</html>
