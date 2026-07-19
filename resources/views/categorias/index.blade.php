@extends('layouts.app')

@section('title', __('Categories') . ' — ' . config('app.name'))
@section('mobile-title', __('Categories'))

@section('content')
    <div class="space-y-6">
        <x-auth.card :title="__('Categories')" :subtitle="__('What did I spend on or where did income come from?')">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <p class="text-slate-600 dark:text-slate-300">{{ __('Organize your movements by type to understand how you use your money.') }}</p>
                <button
                    type="button"
                    class="auth-btn-primary shrink-0 sm:w-auto sm:px-5"
                    data-categoria-modal-open="create"
                >
                    <x-heroicon-o-plus class="size-5 shrink-0" />
                    {{ __('New category') }}
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

        @if ($categorias->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-200 bg-white/60 px-6 py-12 text-center dark:border-slate-700 dark:bg-slate-900/40">
                <x-heroicon-o-tag class="mx-auto size-12 text-slate-300 dark:text-slate-600" />
                <p class="mt-4 text-sm text-slate-500 dark:text-slate-400">{{ __('You have no categories yet. Create your first one.') }}</p>
            </div>
        @else
            <div>
                <x-categorias.section
                    :title="__('Expense categories')"
                    :empty-message="__('You have no expense categories yet.')"
                    :categorias="$categoriasGasto"
                    accent="gasto"
                />

                <div class="mt-12 pt-8 sm:mt-16 sm:pt-10">
                    <x-categorias.section
                        :title="__('Income categories')"
                        :empty-message="__('You have no income categories yet.')"
                        :categorias="$categoriasIngreso"
                        accent="ingreso"
                    />
                </div>
            </div>
        @endif
    </div>

    <x-categorias.form-modal
        :icons="$icons"
        :colors="$colors"
        :open="$errors->isNotEmpty()"
        :edit-categoria-id="old('_categoria_id')"
    />
@endsection

@push('scripts')
    @vite('resources/js/categoria-modal.js')
@endpush
