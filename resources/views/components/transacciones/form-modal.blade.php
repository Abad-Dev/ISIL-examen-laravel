@props([
    'cuentas',
    'categorias',
    'open' => false,
    'editTransaccionId' => null,
])

@php
    $isEdit = $editTransaccionId !== null;
    $formAction = $isEdit ? route('web.transacciones.update', $editTransaccionId) : route('web.transacciones.store');
    $selectedTipo = old('tipo', 'gasto');
@endphp

<div
    id="transaccion-modal"
    class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center {{ $open ? '' : 'hidden' }}"
    data-transaccion-modal
    data-transaccion-modal-mode="{{ $isEdit ? 'edit' : 'create' }}"
    data-create-title="{{ __('New transaction') }}"
    data-edit-title="{{ __('Edit transaction') }}"
    role="dialog"
    aria-modal="true"
    aria-labelledby="transaccion-modal-title"
>
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-transaccion-modal-close></div>

    <div class="relative z-10 max-h-[90vh] w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
        <div class="flex h-1.5">
            <span class="flex-1 bg-palette-green"></span>
            <span class="flex-1 bg-palette-yellow"></span>
            <span class="flex-1 bg-palette-orange"></span>
            <span class="flex-1 bg-palette-red"></span>
        </div>

        <div class="flex items-center justify-between border-b border-slate-100 px-6 py-4 dark:border-slate-800">
            <h2 id="transaccion-modal-title" class="text-lg font-semibold text-slate-800 dark:text-white" data-transaccion-modal-title>
                {{ $isEdit ? __('Edit transaction') : __('New transaction') }}
            </h2>
            <button
                type="button"
                class="inline-flex size-9 items-center justify-center rounded-xl text-slate-500 transition hover:bg-slate-100 hover:text-slate-800 dark:text-slate-400 dark:hover:bg-slate-800 dark:hover:text-white"
                data-transaccion-modal-close
                aria-label="{{ __('Close') }}"
            >
                <x-heroicon-o-x-mark class="size-5" />
            </button>
        </div>

        <form
            method="POST"
            action="{{ $formAction }}"
            class="overflow-y-auto px-6 py-5"
            data-transaccion-form
            data-store-url="{{ route('web.transacciones.store') }}"
        >
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="space-y-5">
                <div class="space-y-1.5">
                    <label for="transaccion-tipo" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Type') }}
                    </label>
                    <select
                        id="transaccion-tipo"
                        name="tipo"
                        class="auth-input @error('tipo') auth-input-error @enderror"
                        required
                        data-transaccion-field="tipo"
                    >
                        @foreach (['ingreso', 'gasto'] as $tipo)
                            <option value="{{ $tipo }}" @selected($selectedTipo === $tipo)>
                                {{ __("transaction_type.{$tipo}") }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="transaccion-cuenta" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Account') }}
                    </label>
                    <select
                        id="transaccion-cuenta"
                        name="cuenta_id"
                        class="auth-input @error('cuenta_id') auth-input-error @enderror"
                        required
                        data-transaccion-field="cuenta_id"
                    >
                        <option value="">{{ __('Select an account') }}</option>
                        @foreach ($cuentas as $cuenta)
                            <option value="{{ $cuenta->id }}" @selected((string) old('cuenta_id') === (string) $cuenta->id)>
                                {{ $cuenta->nombre }} ({{ __("account_type.{$cuenta->tipo}") }})
                            </option>
                        @endforeach
                    </select>
                    @error('cuenta_id')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="transaccion-categoria" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Category') }} <span class="font-normal text-slate-400">({{ __('Optional') }})</span>
                    </label>
                    <select
                        id="transaccion-categoria"
                        name="categoria_id"
                        class="auth-input @error('categoria_id') auth-input-error @enderror"
                        data-transaccion-field="categoria_id"
                    >
                        <option value="">{{ __('No category') }}</option>
                        @foreach ($categorias as $categoria)
                            <option
                                value="{{ $categoria->id }}"
                                data-categoria-tipo="{{ $categoria->tipo }}"
                                @selected((string) old('categoria_id') === (string) $categoria->id)
                            >
                                {{ $categoria->nombre }} ({{ __("category_type.{$categoria->tipo}") }})
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <x-auth.input
                    name="monto"
                    type="number"
                    step="0.01"
                    min="0.01"
                    :label="__('Amount in soles')"
                    icon="heroicon-o-banknotes"
                    value="{{ old('monto') }}"
                    required
                    data-transaccion-field="monto"
                />

                <div class="space-y-1.5">
                    <label for="transaccion-fecha" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Date') }}
                    </label>
                    <input
                        id="transaccion-fecha"
                        type="date"
                        name="fecha"
                        value="{{ old('fecha', now()->format('Y-m-d')) }}"
                        class="auth-input @error('fecha') auth-input-error @enderror"
                        required
                        data-transaccion-field="fecha"
                    />
                    @error('fecha')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <x-auth.input
                    name="descripcion"
                    :label="__('Description')"
                    icon="heroicon-o-document-text"
                    value="{{ old('descripcion') }}"
                    placeholder="{{ __('Optional') }}"
                    data-transaccion-field="descripcion"
                />
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                    data-transaccion-modal-close
                >
                    {{ __('Cancel') }}
                </button>
                <button
                    type="submit"
                    class="auth-btn-primary gap-2 sm:w-auto sm:px-6"
                    data-transaccion-submit-create
                    @class(['hidden' => $isEdit])
                >
                    <x-heroicon-o-plus class="size-5 shrink-0" />
                    {{ __('Create transaction') }}
                </button>
                <button
                    type="submit"
                    class="auth-btn-primary gap-2 sm:w-auto sm:px-6"
                    data-transaccion-submit-edit
                    @class(['hidden' => ! $isEdit])
                >
                    <x-heroicon-o-check class="size-5 shrink-0" />
                    {{ __('Save changes') }}
                </button>
            </div>
        </form>
    </div>
</div>
