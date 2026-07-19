@props([
    'totalBalance',
    'ingresos',
    'gastos',
    'neto',
])

<x-auth.card :title="__('Today')" :subtitle="__('Your accounts and today\'s activity')">
    <div class="space-y-5">
        <div class="rounded-2xl bg-slate-50 px-4 py-4 dark:bg-slate-800/60">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('Total balance') }}</p>
            <p class="mt-1 text-3xl font-semibold tabular-nums tracking-tight text-slate-800 dark:text-white">
                <x-money :amount="$totalBalance" />
            </p>
        </div>

        <dl class="space-y-4">
            <div class="flex items-center justify-between gap-4">
                <dt class="text-sm text-slate-500 dark:text-slate-400">{{ __('Income') }}</dt>
                <dd class="text-base font-semibold tabular-nums text-palette-green">
                    +<x-money :amount="$ingresos" />
                </dd>
            </div>
            <div class="flex items-center justify-between gap-4">
                <dt class="text-sm text-slate-500 dark:text-slate-400">{{ __('Expenses') }}</dt>
                <dd class="text-base font-semibold tabular-nums text-palette-red">
                    −<x-money :amount="$gastos" />
                </dd>
            </div>
            <div class="border-t border-slate-100 pt-4 dark:border-slate-800">
                <div class="flex items-center justify-between gap-4">
                    <dt class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Net balance') }}</dt>
                    <dd @class([
                        'text-xl font-semibold tabular-nums',
                        'text-palette-green' => (float) $neto >= 0,
                        'text-palette-red' => (float) $neto < 0,
                    ])>
                        {{ (float) $neto >= 0 ? '+' : '−' }}<x-money :amount="abs((float) $neto)" />
                    </dd>
                </div>
            </div>
        </dl>
    </div>
</x-auth.card>
