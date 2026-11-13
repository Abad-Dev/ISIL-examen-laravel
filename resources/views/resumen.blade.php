@extends('layouts.app')

@section('title', __('Summary') . ' — ' . config('app.name'))
@section('mobile-title', __('Summary'))

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        @if (! $hasCuentas)
            <x-auth.card :title="__('Summary')" :subtitle="__('Your income and expenses at a glance.')">
                <div class="text-center">
                    <x-heroicon-o-wallet class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('You need at least one account before recording transactions.') }}</p>
                    <a href="{{ route('cuentas.index') }}" class="auth-link mt-3 inline-block text-sm">{{ __('Go to accounts') }}</a>
                </div>
            </x-auth.card>
        @else
            <div class="grid gap-4 lg:grid-cols-3">
                <x-dashboard.today-summary
                    :total-balance="$summary['totalBalance']"
                    :ingresos="$summary['today']['ingresos']"
                    :gastos="$summary['today']['gastos']"
                    :neto="$summary['today']['neto']"
                />

                <x-dashboard.period-summary
                    :title="__('This week')"
                    :subtitle="__('Monday to Sunday')"
                    :ingresos="$summary['week']['ingresos']"
                    :gastos="$summary['week']['gastos']"
                    :neto="$summary['week']['neto']"
                />

                <x-dashboard.period-summary
                    :title="__('This month')"
                    :subtitle="\App\Support\DateFormat::monthYear(now())"
                    :ingresos="$summary['month']['ingresos']"
                    :gastos="$summary['month']['gastos']"
                    :neto="$summary['month']['neto']"
                />
            </div>

            <x-dashboard.expenses-chart
                :chart="$expensesChart"
                :navigation="$chartNavigation"
            />
        @endif
    </div>
@endsection

@if ($hasCuentas)
    @push('scripts')
        @vite('resources/js/expenses-chart.js')
    @endpush
@endif
