@extends('layouts.app')

@section('title', __('Accounts') . ' — ' . config('app.name'))
@section('mobile-title', __('Accounts'))

@section('content')
    <div class="space-y-6">
        <x-auth.card :title="__('Accounts')" :subtitle="__('Where do I keep my money?')">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-slate-600 dark:text-slate-300">{{ __('Manage your cash, bank accounts and other places where you store money.') }}</p>
                <button
                    type="button"
                    class="auth-btn-primary shrink-0 sm:w-auto sm:px-5"
                    data-cuenta-modal-open
                >
                    <x-heroicon-o-plus class="size-5" />
                    {{ __('New account') }}
                </button>
            </div>
        </x-auth.card>

        @if (session('status'))
            <x-auth.alert type="success">
                {{ session('status') }}
            </x-auth.alert>
        @endif

        @if ($cuentas->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/40">
                <x-heroicon-o-wallet class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('You have no accounts yet. Create your first one.') }}</p>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($cuentas as $cuenta)
                    <article class="flex items-center gap-4 rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
                        <span
                            class="flex size-12 shrink-0 items-center justify-center rounded-xl text-slate-800 dark:text-white"
                            style="background-color: {{ $cuenta->color_hex }}40"
                        >
                            @if ($cuenta->icon)
                                <x-dynamic-component :component="$cuenta->icon" class="size-6" />
                            @endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate font-semibold text-slate-800 dark:text-white">{{ $cuenta->nombre }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ __("account_type.{$cuenta->tipo}") }}</p>
                        </div>
                        @if ($cuenta->saldo !== null)
                            <p class="shrink-0 text-sm font-semibold tabular-nums text-slate-800 dark:text-white">
                                {{ number_format($cuenta->saldo, 2) }}
                            </p>
                        @endif
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    <x-cuentas.create-modal
        :icons="$icons"
        :colors="$colors"
        :open="$errors->isNotEmpty()"
    />
@endsection

@push('scripts')
    @vite('resources/js/cuenta-modal.js')
@endpush
