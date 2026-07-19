@extends('layouts.app')

@section('title', __('Dashboard') . ' — ' . config('app.name'))
@section('mobile-title', __('Dashboard'))

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
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
        @endif
    </div>
@endsection

@if ($cuentas->isNotEmpty())
    @push('scripts')
        @vite('resources/js/quick-transaction.js')
    @endpush
@endif
