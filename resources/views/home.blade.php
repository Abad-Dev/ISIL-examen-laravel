@extends('layouts.app')

@section('title', __('Dashboard') . ' — ' . config('app.name'))
@section('mobile-title', __('Dashboard'))

@section('content')
    <div class="mx-auto max-w-5xl space-y-6">
        @if (session('status'))
            <x-auth.alert type="success">
                {{ session('status') }}
            </x-auth.alert>
        @endif

        @if ($cuentas->isEmpty())
            <x-auth.card :title="__('Quick transaction')" :subtitle="__('Record a movement in seconds.')">
                <div class="text-center">
                    <x-heroicon-o-wallet class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                    <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('You need at least one account before recording transactions.') }}</p>
                    <a href="{{ route('cuentas.index') }}" class="auth-link mt-3 inline-block text-sm">{{ __('Go to accounts') }}</a>
                </div>
            </x-auth.card>
        @else
            <x-dashboard.quick-transaction :cuentas="$cuentas" :categorias="$categorias" />

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
                    :subtitle="now()->translatedFormat('F Y')"
                    :ingresos="$summary['month']['ingresos']"
                    :gastos="$summary['month']['gastos']"
                    :neto="$summary['month']['neto']"
                />
            </div>
        @endif
    </div>
@endsection

@if ($cuentas->isNotEmpty())
    @push('scripts')
        @vite('resources/js/quick-transaction.js')
    @endpush
@endif
