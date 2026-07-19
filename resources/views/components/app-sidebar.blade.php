@php
    $links = [
        ['route' => 'home', 'pattern' => 'home', 'label' => __('Dashboard'), 'icon' => 'heroicon-o-home'],
        ['route' => 'cuentas.index', 'pattern' => 'cuentas.*', 'label' => __('Accounts'), 'icon' => 'heroicon-o-wallet'],
        ['route' => 'web.transacciones.index', 'pattern' => 'web.transacciones.*', 'label' => __('Transactions'), 'icon' => 'heroicon-o-arrows-right-left'],
        ['route' => 'web.categorias.index', 'pattern' => 'web.categorias.*', 'label' => __('Categories'), 'icon' => 'heroicon-o-tag'],
    ];
@endphp

<div
    id="sidebar-backdrop"
    class="fixed inset-0 z-40 hidden bg-slate-900/50 backdrop-blur-sm transition-opacity lg:hidden"
    data-sidebar-close
    aria-hidden="true"
></div>

<aside
    id="app-sidebar"
    class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200/80 bg-white/95 shadow-xl backdrop-blur-md transition-transform duration-200 ease-in-out dark:border-slate-800 dark:bg-slate-950/95 lg:static lg:z-auto lg:w-64 lg:translate-x-0 lg:shadow-none"
>
    <div class="flex items-center justify-between gap-3 border-b border-slate-200/80 px-4 py-4 dark:border-slate-800">
        <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center gap-3 transition hover:opacity-90">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-palette-green shadow-md shadow-palette-green/40 ring-4 ring-white dark:ring-slate-900">
                <x-heroicon-s-banknotes class="size-5 text-slate-800" />
            </span>
            <span class="truncate text-lg font-semibold tracking-tight text-slate-800 dark:text-white">{{ config('app.name', 'Inaut') }}</span>
        </a>

        <div class="flex shrink-0 items-center gap-2">
            <x-theme-toggle />
            <button
                type="button"
                class="inline-flex size-10 items-center justify-center rounded-xl text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 lg:hidden dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                data-sidebar-close
                aria-label="{{ __('Close menu') }}"
            >
                <x-heroicon-o-x-mark class="size-6" />
            </button>
        </div>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4">
        <ul class="space-y-1">
            @foreach ($links as $link)
                @php
                    $active = request()->routeIs($link['pattern']);
                @endphp
                <li>
                    <a
                        href="{{ route($link['route']) }}"
                        data-sidebar-close
                        @class([
                            'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition',
                            'bg-palette-green/20 text-slate-900 dark:bg-palette-green/15 dark:text-white' => $active,
                            'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white' => ! $active,
                        ])
                        @if ($active) aria-current="page" @endif
                    >
                        <x-dynamic-component :component="$link['icon']" class="size-5 shrink-0" />
                        <span>{{ $link['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="border-t border-slate-200/80 px-4 py-4 dark:border-slate-800">
        <div class="mb-4 min-w-0">
            <p class="truncate text-sm font-medium text-slate-800 dark:text-white">{{ Auth::user()->name }}</p>
            <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button
                type="submit"
                class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
            >
                <x-heroicon-o-arrow-right-on-rectangle class="size-5 shrink-0" />
                <span>{{ __('Logout') }}</span>
            </button>
        </form>
    </div>
</aside>
