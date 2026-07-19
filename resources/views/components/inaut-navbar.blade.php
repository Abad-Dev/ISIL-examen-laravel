<header class="relative z-10 border-b border-slate-200/80 bg-white/80 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/80">
    <nav class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-4 sm:px-6">
        <a href="{{ url('/') }}" class="flex items-center gap-3 transition hover:opacity-90">
            <span class="flex size-10 items-center justify-center rounded-xl bg-palette-green shadow-md shadow-palette-green/40 ring-4 ring-white dark:ring-slate-900">
                <x-heroicon-s-banknotes class="size-5 text-slate-800" />
            </span>
            <span class="text-lg font-semibold tracking-tight text-slate-800 dark:text-white">{{ config('app.name', 'Inaut') }}</span>
        </a>

        <div class="flex items-center gap-3 sm:gap-4">
            <x-theme-toggle />

            @auth
                <a href="{{ url('/home') }}" class="hidden rounded-xl bg-palette-green px-4 py-2 text-sm font-semibold text-slate-800 shadow-sm transition hover:brightness-95 sm:inline-flex">
                    {{ __('Go to dashboard') }}
                </a>
                <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline">
                    @csrf
                    <button type="submit" class="text-sm font-medium text-slate-600 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white">
                        {{ __('Logout') }}
                    </button>
                </form>
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
