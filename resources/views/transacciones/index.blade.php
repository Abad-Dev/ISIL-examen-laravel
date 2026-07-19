@extends('layouts.app')

@section('title', __('Transactions') . ' — ' . config('app.name'))
@section('mobile-title', __('Transactions'))

@section('content')
    <div class="space-y-6">
        <x-auth.card :title="__('Transactions')" :subtitle="__('What movements did I make and from which account?')">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-slate-600 dark:text-slate-300">{{ __('Review your income and expenses and see which account each movement belongs to.') }}</p>
                @if ($cuentas->isNotEmpty() && $categorias->isNotEmpty())
                    <button
                        type="button"
                        class="auth-btn-primary shrink-0 sm:w-auto sm:px-5"
                        data-transaccion-modal-open="create"
                    >
                        <x-heroicon-o-plus class="size-5 shrink-0" />
                        {{ __('New transaction') }}
                    </button>
                @endif
            </div>
        </x-auth.card>

        @if (session('status'))
            <x-auth.alert type="success">
                {{ session('status') }}
            </x-auth.alert>
        @endif

        @if (session('error'))
            <x-auth.alert type="error">
                {{ session('error') }}
            </x-auth.alert>
        @endif

        @if ($cuentas->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/40">
                <x-heroicon-o-wallet class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('You need at least one account before recording transactions.') }}</p>
                <a href="{{ route('cuentas.index') }}" class="auth-link mt-3 inline-block text-sm">{{ __('Go to accounts') }}</a>
            </div>
        @elseif ($categorias->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/40">
                <x-heroicon-o-tag class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('You need at least one category before recording transactions.') }}</p>
                <a href="{{ route('web.categorias.index') }}" class="auth-link mt-3 inline-block text-sm">{{ __('Go to categories') }}</a>
            </div>
        @elseif ($transacciones->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/40">
                <x-heroicon-o-arrows-right-left class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('You have no transactions yet. Record your first movement.') }}</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($transacciones as $transaccion)
                    <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex min-w-0 flex-1 items-start gap-4">
                                <span
                                    class="flex size-12 shrink-0 items-center justify-center rounded-xl text-slate-800 dark:text-white"
                                    style="background-color: {{ $transaccion->cuenta->color_hex }}40"
                                >
                                    @if ($transaccion->cuenta->icon)
                                        <x-dynamic-component :component="$transaccion->cuenta->icon" class="size-6 shrink-0" />
                                    @endif
                                </span>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="truncate font-semibold text-slate-800 dark:text-white">
                                            {{ $transaccion->descripcion ?: __("transaction_type.{$transaccion->tipo}") }}
                                        </h3>
                                        <span @class([
                                            'inline-flex rounded-full px-2 py-0.5 text-xs font-medium',
                                            'bg-palette-green/20 text-slate-800 dark:text-palette-green' => $transaccion->tipo === 'ingreso',
                                            'bg-palette-red/15 text-palette-red' => $transaccion->tipo === 'gasto',
                                        ])>
                                            {{ __("transaction_type.{$transaccion->tipo}") }}
                                        </span>
                                    </div>

                                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                                        {{ $transaccion->cuenta->nombre }}
                                        ·
                                        {{ $transaccion->categoria->nombre }}
                                        ·
                                        {{ $transaccion->fecha->translatedFormat('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-4 sm:justify-end">
                                <p @class([
                                    'shrink-0 text-base font-semibold tabular-nums',
                                    'text-palette-green' => $transaccion->tipo === 'ingreso',
                                    'text-palette-red' => $transaccion->tipo === 'gasto',
                                ])>
                                    {{ $transaccion->tipo === 'ingreso' ? '+' : '−' }}<x-money :amount="$transaccion->monto" />
                                </p>

                                <div class="flex items-center gap-2">
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                                        data-transaccion-modal-open="edit"
                                        data-transaccion-cuenta-id="{{ $transaccion->cuenta_id }}"
                                        data-transaccion-categoria-id="{{ $transaccion->categoria_id }}"
                                        data-transaccion-tipo="{{ $transaccion->tipo }}"
                                        data-transaccion-monto="{{ $transaccion->monto }}"
                                        data-transaccion-descripcion="{{ $transaccion->descripcion }}"
                                        data-transaccion-fecha="{{ $transaccion->fecha->format('Y-m-d') }}"
                                        data-transaccion-update-url="{{ route('web.transacciones.update', $transaccion) }}"
                                    >
                                        <x-heroicon-o-pencil-square class="size-4" />
                                        {{ __('Edit') }}
                                    </button>

                                    <form
                                        method="POST"
                                        action="{{ route('web.transacciones.destroy', $transaccion) }}"
                                        data-confirm-submit
                                        data-confirm-title="{{ __('Delete transaction') }}"
                                        data-confirm-message="{{ __('Are you sure you want to delete this transaction?') }}"
                                        data-confirm-label="{{ __('Delete') }}"
                                        data-confirm-variant="danger"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-palette-red transition hover:bg-palette-red/10"
                                        >
                                            <x-heroicon-o-trash class="size-4" />
                                            {{ __('Delete') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    @if ($cuentas->isNotEmpty() && $categorias->isNotEmpty())
        <x-transacciones.form-modal
            :cuentas="$cuentas"
            :categorias="$categorias"
            :open="$errors->isNotEmpty()"
            :edit-transaccion-id="old('_transaccion_id')"
        />
    @endif
@endsection

@push('scripts')
    @vite('resources/js/transaccion-modal.js')
@endpush
