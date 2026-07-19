@props([
    'cuentas',
    'categorias',
])

@php
    $defaultCuentaId = old('cuenta_id', $cuentas->first()?->id);
    $defaultCategoriaId = old('categoria_id', $categorias->first()?->id);
@endphp

<x-auth.card :title="__('Quick transaction')" :subtitle="__('Record a movement in seconds.')">
    <form
        method="POST"
        action="{{ route('web.transacciones.store') }}"
        class="space-y-5"
        data-quick-transaction-form
    >
        @csrf
        <input type="hidden" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}">

        <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
            <div class="grid min-w-0 flex-1 gap-4 sm:grid-cols-2">
                <div class="space-y-1.5 sm:col-span-2">
                    <label for="quick-categoria" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Category') }}
                    </label>
                    <select
                        id="quick-categoria"
                        name="categoria_id"
                        class="auth-input @error('categoria_id') auth-input-error @enderror"
                        data-quick-transaction-field="categoria_id"
                    >
                        <option value="">{{ __('No category') }}</option>
                        @foreach ($categorias as $categoria)
                            <option
                                value="{{ $categoria->id }}"
                                data-categoria-tipo="{{ $categoria->tipo }}"
                                @selected((string) $defaultCategoriaId === (string) $categoria->id)
                            >
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5 sm:col-span-2">
                    <label for="quick-cuenta" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Account') }}
                    </label>
                    <select
                        id="quick-cuenta"
                        name="cuenta_id"
                        class="auth-input @error('cuenta_id') auth-input-error @enderror"
                        required
                        data-quick-transaction-field="cuenta_id"
                    >
                        @foreach ($cuentas as $cuenta)
                            <option
                                value="{{ $cuenta->id }}"
                                @selected((string) $defaultCuentaId === (string) $cuenta->id)
                            >
                                {{ $cuenta->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cuenta_id')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="shrink-0 sm:pt-7">
                <label for="quick-monto-display" class="sr-only">{{ __('Amount in soles') }}</label>
                <div class="flex items-baseline justify-end gap-1">
                    <span class="text-lg font-medium text-slate-400">S/</span>
                    <input
                        type="hidden"
                        name="monto"
                        value="{{ old('monto') }}"
                        data-quick-transaction-monto-value
                    />
                    <input
                        id="quick-monto-display"
                        type="text"
                        inputmode="numeric"
                        autocomplete="off"
                        placeholder="0.00"
                        value="0.00"
                        class="w-full min-w-[8rem] max-w-[11rem] border-0 bg-transparent p-0 text-right text-4xl font-semibold tabular-nums text-slate-800 placeholder:text-slate-300 focus:outline-none focus:ring-0 dark:text-white dark:placeholder:text-slate-600 sm:text-5xl @error('monto') text-palette-red @enderror"
                        data-quick-transaction-monto-display
                    />
                </div>
                @error('monto')
                    <p class="mt-1 text-right text-sm font-medium text-palette-red">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-1.5">
            <label for="quick-descripcion" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                {{ __('Description') }}
            </label>
            <textarea
                id="quick-descripcion"
                name="descripcion"
                rows="3"
                placeholder="{{ __('Optional') }}"
                class="auth-input min-h-[5rem] resize-y @error('descripcion') auth-input-error @enderror"
                data-quick-transaction-field="descripcion"
            >{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
            @enderror
        </div>

        @error('tipo')
            <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
        @enderror

        <div class="grid gap-3 sm:grid-cols-2">
            <button
                type="submit"
                name="tipo"
                value="gasto"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-palette-red px-4 py-3 text-sm font-semibold text-white shadow-md shadow-palette-red/30 transition hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-palette-red/40"
                data-quick-transaction-submit="gasto"
            >
                <x-heroicon-o-arrow-down-circle class="size-5 shrink-0" />
                {{ __('Register expense') }}
            </button>
            <button
                type="submit"
                name="tipo"
                value="ingreso"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-palette-green px-4 py-3 text-sm font-semibold text-slate-800 shadow-md shadow-palette-green/30 transition hover:brightness-95 focus:outline-none focus:ring-2 focus:ring-palette-green/40"
                data-quick-transaction-submit="ingreso"
            >
                <x-heroicon-o-arrow-up-circle class="size-5 shrink-0" />
                {{ __('Register income') }}
            </button>
        </div>
    </form>
</x-auth.card>
