<header class="sticky top-0 z-30 flex items-center gap-3 border-b border-slate-200/80 bg-white/80 px-4 py-3 backdrop-blur-md lg:hidden dark:border-slate-800 dark:bg-slate-950/80">
    <button
        type="button"
        class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
        data-sidebar-open
        aria-label="{{ __('Open menu') }}"
    >
        <x-heroicon-o-bars-3 class="size-6" />
    </button>

    <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center gap-2 transition hover:opacity-90">
        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-palette-green shadow-md shadow-palette-green/40 ring-2 ring-white dark:ring-slate-900">
            <x-heroicon-s-banknotes class="size-4 text-slate-800" />
        </span>
        <span class="truncate text-base font-semibold text-slate-800 dark:text-white">{{ config('app.name', 'Inaut') }}</span>
    </a>

    <x-theme-toggle />
</header>
