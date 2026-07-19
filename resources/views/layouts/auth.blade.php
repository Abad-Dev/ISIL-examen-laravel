<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Inaut'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[#fafaf5] font-sans text-slate-800 antialiased">
    <div class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden px-4 py-10 sm:px-6">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -left-20 top-10 size-72 rounded-full bg-palette-green/30 blur-3xl"></div>
            <div class="absolute -right-16 top-1/3 size-64 rounded-full bg-palette-yellow/40 blur-3xl"></div>
            <div class="absolute bottom-10 left-1/3 size-56 rounded-full bg-palette-orange/30 blur-3xl"></div>
            <div class="absolute -bottom-10 right-1/4 size-48 rounded-full bg-palette-red/20 blur-3xl"></div>
        </div>

        <a href="{{ url('/') }}" class="relative mb-8 flex items-center gap-3 transition hover:opacity-90">
            <span class="flex size-12 items-center justify-center rounded-2xl bg-palette-green shadow-md shadow-palette-green/40 ring-4 ring-white">
                <x-heroicon-s-banknotes class="size-6 text-slate-800" />
            </span>
            <span class="text-xl font-semibold tracking-tight text-slate-800">{{ config('app.name', 'Inaut') }}</span>
        </a>

        <main class="relative w-full max-w-md">
            @yield('content')
        </main>

        @hasSection('footer')
            <footer class="relative mt-6 text-center text-sm text-slate-500">
                @yield('footer')
            </footer>
        @endif
    </div>
</body>
</html>
