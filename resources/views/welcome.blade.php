<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Inaut') }}</title>

    <script>
        (function () {
            var theme = localStorage.getItem('inaut-theme') || 'system';
            var dark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (dark) document.documentElement.classList.add('dark');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#fafaf5] font-sans text-slate-800 antialiased transition-colors dark:bg-slate-950 dark:text-slate-100">
    <div class="relative flex min-h-screen flex-col overflow-hidden">
        <div aria-hidden="true" class="pointer-events-none absolute inset-0">
            <div class="absolute -left-20 top-10 size-72 rounded-full bg-palette-green/30 blur-3xl dark:bg-palette-green/15"></div>
            <div class="absolute -right-16 top-1/3 size-64 rounded-full bg-palette-yellow/40 blur-3xl dark:bg-palette-yellow/10"></div>
            <div class="absolute bottom-10 left-1/3 size-56 rounded-full bg-palette-orange/30 blur-3xl dark:bg-palette-orange/10"></div>
        </div>

        <header class="relative z-10 border-b border-slate-200/80 bg-white/80 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/80">
            <nav class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
                <a href="{{ url('/') }}" class="flex items-center gap-3 transition hover:opacity-90">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-palette-green shadow-md shadow-palette-green/40 ring-4 ring-white dark:ring-slate-900">
                        <x-heroicon-s-banknotes class="size-5 text-slate-800" />
                    </span>
                    <span class="text-lg font-semibold tracking-tight">{{ config('app.name', 'Inaut') }}</span>
                </a>

                <div class="flex items-center gap-3 sm:gap-4">
                    <label for="theme-selector" class="sr-only">{{ __('Theme') }}</label>
                    <select id="theme-selector" data-theme-selector class="theme-selector">
                        <option value="light">{{ __('Light mode') }}</option>
                        <option value="dark">{{ __('Dark mode') }}</option>
                        <option value="system">{{ __('System theme') }}</option>
                    </select>

                    @auth
                        <a href="{{ url('/home') }}" class="hidden rounded-xl bg-palette-green px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:brightness-95 sm:inline-flex">
                            {{ __('Go to dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden text-sm font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white sm:inline">
                            {{ __('Sign in') }}
                        </a>
                        <a href="{{ route('register') }}" class="hidden rounded-xl bg-palette-green px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:brightness-95 sm:inline-flex">
                            {{ __('Register') }}
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <main class="relative z-10 flex flex-1 items-center justify-center px-4 py-12 sm:px-6">
            <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-100 dark:bg-slate-900 dark:shadow-black/30 dark:ring-slate-800">
                <div class="flex h-1.5">
                    <span class="flex-1 bg-palette-green"></span>
                    <span class="flex-1 bg-palette-yellow"></span>
                    <span class="flex-1 bg-palette-orange"></span>
                    <span class="flex-1 bg-palette-red"></span>
                </div>

                <div class="px-6 py-10 text-center sm:px-10">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-800 dark:text-white">
                        {{ config('app.name', 'Inaut') }}
                    </h1>
                    <p class="mx-auto mt-3 max-w-sm text-slate-500 dark:text-slate-400">
                        {{ __('Organize your income, expenses and budgets in one place.') }}
                    </p>

                    <div class="mt-8 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        @auth
                            <a href="{{ url('/home') }}" class="auth-btn-primary w-full sm:w-auto sm:px-8">
                                {{ __('Go to dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="auth-btn-primary w-full sm:w-auto sm:px-8">
                                {{ __('Sign in') }}
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-palette-orange hover:bg-palette-orange/10 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:border-palette-orange/50 sm:w-auto sm:px-8">
                                {{ __('Register') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
