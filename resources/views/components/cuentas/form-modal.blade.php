@props([
    'icons',
    'colors',
    'open' => false,
    'editCuentaId' => null,
])

@php
    $isEdit = $editCuentaId !== null;
    $selectedIcon = old('icon', $icons[0]);
    $selectedColor = old('color_hex', $colors[0]);
    $formAction = $isEdit ? route('cuentas.update', $editCuentaId) : route('cuentas.store');
@endphp

<div
    id="cuenta-modal"
    class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center {{ $open ? '' : 'hidden' }}"
    data-cuenta-modal
    data-cuenta-modal-mode="{{ $isEdit ? 'edit' : 'create' }}"
    data-create-title="{{ __('New account') }}"
    data-edit-title="{{ __('Edit account') }}"
    role="dialog"
    aria-modal="true"
    aria-labelledby="cuenta-modal-title"
>
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-cuenta-modal-close></div>

    <div class="relative z-10 max-h-[90vh] w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
        <div class="flex h-1.5">
            <span class="flex-1 bg-palette-green"></span>
            <span class="flex-1 bg-palette-yellow"></span>
            <span class="flex-1 bg-palette-orange"></span>
            <span class="flex-1 bg-palette-red"></span>
        </div>

        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-800">
            <h2 id="cuenta-modal-title" class="text-lg font-semibold text-slate-800 dark:text-white" data-cuenta-modal-title>
                {{ $isEdit ? __('Edit account') : __('New account') }}
            </h2>
            <button
                type="button"
                class="inline-flex size-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                data-cuenta-modal-close
                aria-label="{{ __('Close') }}"
            >
                <x-heroicon-o-x-mark class="size-5" />
            </button>
        </div>

        <form
            method="POST"
            action="{{ $formAction }}"
            class="overflow-y-auto px-6 py-5"
            data-cuenta-form
            data-store-url="{{ route('cuentas.store') }}"
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
                    data-cuenta-field="nombre"
                />

                <div class="space-y-1.5">
                    <label for="cuenta-tipo" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Type') }}
                    </label>
                    <select
                        id="cuenta-tipo"
                        name="tipo"
                        class="auth-input @error('tipo') auth-input-error @enderror"
                        required
                        data-cuenta-field="tipo"
                    >
                        @foreach (['efectivo', 'billetera_digital', 'banco', 'otro'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo', 'efectivo') === $tipo)>
                                {{ __("account_type.{$tipo}") }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <x-auth.input
                    name="saldo"
                    type="number"
                    step="0.01"
                    min="0"
                    :label="__('Balance in soles')"
                    icon="heroicon-o-banknotes"
                    value="{{ old('saldo') }}"
                    placeholder="0.00"
                    required
                    data-cuenta-field="saldo"
                />
                <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('All amounts in the app are in Peruvian soles (PEN).') }}</p>

                <div class="space-y-2">
                    <span class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Icon') }}
                    </span>
                    <input type="hidden" name="icon" value="{{ $selectedIcon }}" data-cuenta-icon-input required>
                    <div class="grid grid-cols-7 gap-2 sm:grid-cols-8" data-cuenta-icon-picker>
                        @foreach ($icons as $icon)
                            <button
                                type="button"
                                data-cuenta-icon-option="{{ $icon }}"
                                @class([
                                    'inline-flex aspect-square items-center justify-center rounded-xl border transition',
                                    'border-palette-green bg-palette-green/20 text-slate-800 dark:text-white' => $selectedIcon === $icon,
                                    'border-slate-200 bg-white text-slate-600 hover:border-palette-green/50 hover:bg-palette-green/10 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:border-palette-green/40' => $selectedIcon !== $icon,
                                ])
                                aria-label="{{ $icon }}"
                            >
                                <x-dynamic-component :component="$icon" class="size-5" />
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
                    <input type="hidden" name="color_hex" value="{{ $selectedColor }}" data-cuenta-color-input required>
                    <div class="flex flex-wrap gap-2" data-cuenta-color-picker>
                        @foreach ($colors as $color)
                            <button
                                type="button"
                                data-cuenta-color-option="{{ $color }}"
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
                    data-cuenta-modal-close
                >
                    {{ __('Cancel') }}
                </button>
                <button type="submit" class="auth-btn-primary sm:w-auto sm:px-6" data-cuenta-submit>
                    <x-heroicon-o-plus class="size-4 shrink-0" data-cuenta-submit-icon-create @class(['hidden' => $isEdit]) />
                    <span data-cuenta-submit-label-create @class(['hidden' => $isEdit])>{{ __('Create account') }}</span>
                    <span data-cuenta-submit-label-edit @class(['hidden' => ! $isEdit])>{{ __('Save changes') }}</span>
                </button>
            </div>
        </form>
    </div>
</div>
