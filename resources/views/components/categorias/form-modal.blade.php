@props([
    'icons',
    'colors',
    'open' => false,
    'editCategoriaId' => null,
])

@php
    $isEdit = $editCategoriaId !== null;
    $selectedIcon = old('icon', $icons[0]);
    $selectedColor = old('color_hex', $colors[0]);
    $formAction = $isEdit ? route('web.categorias.update', $editCategoriaId) : route('web.categorias.store');
@endphp

<div
    id="categoria-modal"
    class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center {{ $open ? '' : 'hidden' }}"
    data-categoria-modal
    data-categoria-modal-mode="{{ $isEdit ? 'edit' : 'create' }}"
    data-create-title="{{ __('New category') }}"
    data-edit-title="{{ __('Edit category') }}"
    role="dialog"
    aria-modal="true"
    aria-labelledby="categoria-modal-title"
>
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-categoria-modal-close></div>

    <div class="relative z-10 max-h-[90vh] w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
        <div class="flex h-1.5">
            <span class="flex-1 bg-palette-green"></span>
            <span class="flex-1 bg-palette-yellow"></span>
            <span class="flex-1 bg-palette-orange"></span>
            <span class="flex-1 bg-palette-red"></span>
        </div>

        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-800">
            <h2 id="categoria-modal-title" class="text-lg font-semibold text-slate-800 dark:text-white" data-categoria-modal-title>
                {{ $isEdit ? __('Edit category') : __('New category') }}
            </h2>
            <button
                type="button"
                class="inline-flex size-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                data-categoria-modal-close
                aria-label="{{ __('Close') }}"
            >
                <x-heroicon-o-x-mark class="size-5" />
            </button>
        </div>

        <form
            method="POST"
            action="{{ $formAction }}"
            class="overflow-y-auto px-6 py-5"
            data-categoria-form
            data-store-url="{{ route('web.categorias.store') }}"
        >
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="space-y-5">
                <x-auth.input
                    name="nombre"
                    :label="__('Name')"
                    icon="heroicon-o-pencil-square"
                    value="{{ old('nombre') }}"
                    required
                    autofocus
                    data-categoria-field="nombre"
                />

                <div class="space-y-1.5">
                    <label for="categoria-tipo" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Type') }}
                    </label>
                    <select
                        id="categoria-tipo"
                        name="tipo"
                        class="auth-input @error('tipo') auth-input-error @enderror"
                        required
                        data-categoria-field="tipo"
                    >
                        @foreach (['ingreso', 'gasto'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo', 'gasto') === $tipo)>
                                {{ __("category_type.{$tipo}") }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <x-auth.input
                    name="descripcion"
                    :label="__('Description')"
                    icon="heroicon-o-document-text"
                    value="{{ old('descripcion') }}"
                    placeholder="{{ __('Optional') }}"
                    data-categoria-field="descripcion"
                />

                <div class="space-y-2">
                    <span class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Icon') }}
                    </span>
                    <input type="hidden" name="icon" value="{{ $selectedIcon }}" data-categoria-icon-input required>
                    <div class="grid grid-cols-7 gap-2 sm:grid-cols-8" data-categoria-icon-picker>
                        @foreach ($icons as $icon)
                            <button
                                type="button"
                                data-categoria-icon-option="{{ $icon }}"
                                @class([
                                    'inline-flex aspect-square items-center justify-center rounded-xl border transition',
                                    'border-palette-green bg-palette-green/20 text-slate-800 dark:text-white' => $selectedIcon === $icon,
                                    'border-slate-200 bg-white text-slate-600 hover:border-palette-green/50 hover:bg-palette-green/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-palette-green/40' => $selectedIcon !== $icon,
                                ])
                                aria-label="{{ $icon }}"
                            >
                                <x-dynamic-component :component="$icon" class="size-5 shrink-0" />
                            </button>
                        @endforeach
                    </div>
                    @error('icon')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <span class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Color') }}
                    </span>
                    <input type="hidden" name="color_hex" value="{{ $selectedColor }}" data-categoria-color-input required>
                    <div class="flex flex-wrap gap-2" data-categoria-color-picker>
                        @foreach ($colors as $color)
                            <button
                                type="button"
                                data-categoria-color-option="{{ $color }}"
                                style="background-color: {{ $color }}"
                                @class([
                                    'size-9 rounded-full ring-2 ring-offset-2 transition dark:ring-offset-slate-900',
                                    'ring-slate-800 dark:ring-white' => $selectedColor === $color,
                                    'ring-transparent hover:ring-slate-300 dark:hover:ring-slate-600' => $selectedColor !== $color,
                                ])
                                aria-label="{{ $color }}"
                            ></button>
                        @endforeach
                    </div>
                    @error('color_hex')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                    data-categoria-modal-close
                >
                    {{ __('Cancel') }}
                </button>
                <button
                    type="submit"
                    class="auth-btn-primary gap-2 sm:w-auto sm:px-6"
                    data-categoria-submit-create
                    @class(['hidden' => $isEdit])
                >
                    <x-heroicon-o-plus class="size-5 shrink-0" />
                    {{ __('Create category') }}
                </button>
                <button
                    type="submit"
                    class="auth-btn-primary gap-2 sm:w-auto sm:px-6"
                    data-categoria-submit-edit
                    @class(['hidden' => ! $isEdit])
                >
                    <x-heroicon-o-check class="size-5 shrink-0" />
                    {{ __('Save changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
