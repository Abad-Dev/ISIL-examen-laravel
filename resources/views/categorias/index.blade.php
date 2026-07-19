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
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($categorias as $categoria)
                    <article class="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
                        <div class="flex items-center gap-4">
                            <span
                                class="flex size-12 shrink-0 items-center justify-center rounded-xl text-slate-800 dark:text-white"
                                style="background-color: {{ $categoria->color_hex }}40"
                            >
                                @if ($categoria->icon)
                                    <x-dynamic-component :component="$categoria->icon" class="size-6 shrink-0" />
                                @endif
                            </span>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate font-semibold text-slate-800 dark:text-white">{{ $categoria->nombre }}</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __("category_type.{$categoria->tipo}") }}</p>
                                @if ($categoria->descripcion)
                                    <p class="mt-1 truncate text-xs text-slate-400 dark:text-slate-500">{{ $categoria->descripcion }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-2 border-t border-slate-100 pt-4 dark:border-slate-800">
                            <button
                                type="button"
                                class="inline-flex items-center gap-1.5 rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                                data-categoria-modal-open="edit"
                                data-categoria-nombre="{{ $categoria->nombre }}"
                                data-categoria-tipo="{{ $categoria->tipo }}"
                                data-categoria-descripcion="{{ $categoria->descripcion }}"
                                data-categoria-icon="{{ $categoria->icon }}"
                                data-categoria-color="{{ $categoria->color_hex }}"
                                data-categoria-update-url="{{ route('web.categorias.update', $categoria) }}"
                            >
                                <x-heroicon-o-pencil-square class="size-4" />
                                {{ __('Edit') }}
                            </button>

                            <form
                                method="POST"
                                action="{{ route('web.categorias.destroy', $categoria) }}"
                                data-confirm-submit
                                data-confirm-title="{{ __('Delete category') }}"
                                data-confirm-message="{{ __('Are you sure you want to delete this category?') }}"
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
