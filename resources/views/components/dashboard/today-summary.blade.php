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

        <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3">
            <div class="rounded-xl bg-palette-green/10 px-4 py-3 dark:bg-palette-green/10">
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Income today') }}</dt>
                <dd class="mt-1 text-lg font-semibold tabular-nums text-palette-green">
                    +<x-money :amount="$ingresos" />
                </dd>
            </div>
            <div class="rounded-xl bg-palette-red/10 px-4 py-3 dark:bg-palette-red/10">
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Expenses today') }}</dt>
                <dd class="mt-1 text-lg font-semibold tabular-nums text-palette-red">
                    −<x-money :amount="$gastos" />
                </dd>
            </div>
            <div @class([
                'rounded-xl px-4 py-3',
                'bg-palette-green/15' => (float) $neto >= 0,
                'bg-palette-red/10' => (float) $neto < 0,
            ])>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-500 dark:text-slate-400">{{ __('Net today') }}</dt>
                <dd @class([
                    'mt-1 text-lg font-semibold tabular-nums',
                    'text-palette-green' => (float) $neto >= 0,
                    'text-palette-red' => (float) $neto < 0,
                ])>
                    {{ (float) $neto >= 0 ? '+' : '−' }}<x-money :amount="abs((float) $neto)" />
                </dd>
            </div>
        </dl>
    </div>
</x-auth.card>
