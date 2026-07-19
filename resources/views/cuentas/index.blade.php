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
                    data-cuenta-modal-open="create"
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

        @if (session('error'))
            <x-auth.alert type="error">
                {{ session('error') }}
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
                    <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
                        <div class="flex items-center gap-4">
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
                            <p class="shrink-0 text-sm font-semibold tabular-nums text-slate-800 dark:text-white">
                                <x-money :amount="$cuenta->saldo" />
                            </p>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-4 dark:border-slate-800">
                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                                data-cuenta-modal-open="edit"
                                data-cuenta-id="{{ $cuenta->id }}"
                                data-cuenta-nombre="{{ $cuenta->nombre }}"
                                data-cuenta-tipo="{{ $cuenta->tipo }}"
                                data-cuenta-saldo="{{ $cuenta->saldo }}"
                                data-cuenta-icon="{{ $cuenta->icon }}"
                                data-cuenta-color="{{ $cuenta->color_hex }}"
                                data-cuenta-update-url="{{ route('cuentas.update', $cuenta) }}"
                            >
                                <x-heroicon-o-pencil-square class="size-4" />
                                {{ __('Edit') }}
                            </button>

                            <form
                                method="POST"
                                action="{{ route('cuentas.destroy', $cuenta) }}"
                                data-confirm-submit
                                data-confirm-title="{{ __('Delete account') }}"
                                data-confirm-message="{{ __('Are you sure you want to delete this account?') }}"
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
                    </article>
                @endforeach
            </div>
        @endif
    </div>

    <x-cuentas.form-modal
        :icons="$icons"
        :colors="$colors"
        :open="$errors->isNotEmpty()"
        :edit-cuenta-id="old('_cuenta_id')"
    />
@endsection

@push('scripts')
    @vite('resources/js/cuenta-modal.js')
@endpush
