@props([
    'title',
    'emptyMessage',
    'categorias',
    'accent' => 'gasto',
])

@php
    $isGasto = $accent === 'gasto';
@endphp

<section @class(['space-y-5'])>
    <header>
        <div class="flex flex-wrap items-end justify-between gap-3">
            <h2 @class([
                'text-xl font-bold tracking-tight sm:text-2xl',
                'text-palette-red' => $isGasto,
                'text-palette-green' => ! $isGasto,
            ])>
                {{ $title }}
            </h2>

            <span @class([
                'rounded-full px-3 py-1 text-sm font-semibold',
                'bg-palette-red/15 text-palette-red dark:bg-palette-red/20' => $isGasto,
                'bg-palette-green/20 text-slate-800 dark:text-palette-green' => ! $isGasto,
            ])>
                {{ $categorias->count() }}
            </span>
        </div>

        <div
            @class([
                'mt-3 h-1 w-full rounded-full',
                'bg-gradient-to-r from-palette-red to-palette-red/20' => $isGasto,
                'bg-gradient-to-r from-palette-green to-palette-green/20' => ! $isGasto,
            ])
            aria-hidden="true"
        ></div>
    </header>

    @if ($categorias->isEmpty())
        <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-6 py-8 text-center dark:border-slate-700 dark:bg-slate-900/40">
            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $emptyMessage }}</p>
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2">
            @foreach ($categorias as $categoria)
                <x-categorias.card :categoria="$categoria" />
            @endforeach
        </div>
    @endif
</section>
