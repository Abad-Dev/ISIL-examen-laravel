@props([
    'chart',
    'navigation',
])

@php
    $buttonClass = 'inline-flex size-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-palette-green/50 hover:bg-palette-green/10 hover:text-slate-900 disabled:pointer-events-none disabled:border-transparent disabled:bg-transparent disabled:text-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-palette-green/40 dark:hover:text-white dark:disabled:text-slate-700';
@endphp

<x-auth.card :title="__('Expenses by category')" :subtitle="__('Monthly spending breakdown by category')">
    <div
        id="expenses-chart-root"
        data-endpoint="{{ route('home.expenses-chart') }}"
        data-navigation='@json($navigation)'
        data-empty-message="{{ __('No expenses this month.') }}"
    >
        <div class="mb-5 flex items-center justify-between gap-3">
            <button
                type="button"
                data-expenses-chart-prev
                class="{{ $buttonClass }}"
                aria-label="{{ __('Previous month') }}"
            >
                <x-heroicon-o-chevron-left class="size-5" />
            </button>

            <p
                data-expenses-chart-label
                class="text-center text-sm font-semibold capitalize text-slate-800 dark:text-white"
            >
                {{ $chart['label'] }}
            </p>

            <button
                type="button"
                data-expenses-chart-next
                class="{{ $buttonClass }}"
                aria-label="{{ __('Next month') }}"
                @disabled(! $navigation['canGoNext'])
            >
                <x-heroicon-o-chevron-right class="size-5" />
            </button>
        </div>

        <div data-expenses-chart-body class="transition-opacity">
            @if ($chart['categories'] === [])
                <div data-expenses-chart-empty class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-800/40">
                    <x-heroicon-o-chart-bar class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('No expenses this month.') }}</p>
                </div>
            @else
                <div class="relative h-72 sm:h-80" data-expenses-chart-canvas-wrap>
                    <canvas
                        id="expenses-chart"
                        data-chart='@json($chart)'
                        aria-label="{{ __('Expenses by category') }}"
                        role="img"
                    ></canvas>
                </div>
            @endif
        </div>
    </div>
</x-auth.card>
