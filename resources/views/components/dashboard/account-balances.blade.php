@props([
    'cuentas',
    'totalBalance',
])

<x-auth.card :title="__('My accounts')" :subtitle="__('What is left in each account.')">
    <div class="mb-4 flex items-baseline justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3 dark:bg-slate-800/60">
        <span class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ __('Total balance') }}</span>
        <span class="text-xl font-semibold tabular-nums text-slate-800 dark:text-white">
            <x-money :amount="$totalBalance" />
        </span>
    </div>

    <ul class="divide-y divide-slate-100 dark:divide-slate-800">
        @foreach ($cuentas as $cuenta)
            <li class="flex items-center gap-3 py-3">
                <span
                    class="flex size-10 shrink-0 items-center justify-center rounded-xl text-slate-800 dark:text-white"
                    style="background-color: {{ $cuenta->color_hex }}40"
                >
                    @if ($cuenta->icon)
                        <x-dynamic-component :component="$cuenta->icon" class="size-5" />
                    @endif
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-sm font-semibold text-slate-800 dark:text-white">{{ $cuenta->nombre }}</p>
                    <p class="truncate text-xs text-slate-500 dark:text-slate-400">
                        @if ($cuenta->principal)
                            <x-heroicon-o-link class="inline size-3.5 align-[-2px]" />
                            {{ __('Shares balance with :name', ['name' => $cuenta->principal->nombre]) }}
                        @else
                            {{ __("account_type.{$cuenta->tipo}") }}
                        @endif
                    </p>
                </div>
                <p @class([
                    'shrink-0 text-sm font-semibold tabular-nums',
                    'text-slate-800 dark:text-white' => (float) $cuenta->saldo_actual >= 0,
                    'text-palette-red' => (float) $cuenta->saldo_actual < 0,
                ])>
                    <x-money :amount="$cuenta->saldo_actual" />
                </p>
            </li>
        @endforeach
    </ul>
</x-auth.card>
