@props([
    'cuentas',
    'categorias',
])

@php
    $tipos = [
        'gasto' => ['label' => __('movement_type.gasto'), 'icon' => 'heroicon-o-arrow-down-circle', 'checked' => 'has-checked:bg-palette-red has-checked:text-white has-checked:shadow-md has-checked:shadow-palette-red/30'],
        'ingreso' => ['label' => __('movement_type.ingreso'), 'icon' => 'heroicon-o-arrow-up-circle', 'checked' => 'has-checked:bg-palette-green has-checked:text-slate-800 has-checked:shadow-md has-checked:shadow-palette-green/30'],
        'transferencia' => ['label' => __('movement_type.transferencia'), 'icon' => 'heroicon-o-arrows-right-left', 'checked' => 'has-checked:bg-sky-500 has-checked:text-white has-checked:shadow-md has-checked:shadow-sky-500/30'],
    ];
    $selectedTipo = old('tipo', 'gasto');
    $defaultCuentaId = old('cuenta_id', $cuentas->first()?->id);
    $defaultCategoriaId = old('categoria_id', $categorias->firstWhere('tipo', $selectedTipo === 'transferencia' ? 'gasto' : $selectedTipo)?->id);
    $nuevaCategoria = \App\Http\Requests\StoreTransaccionRequest::NUEVA_CATEGORIA;
@endphp

<x-auth.card :title="__('Quick transaction')" :subtitle="__('Record a movement in seconds.')">
    <form
        method="POST"
        action="{{ route('web.transacciones.store') }}"
        class="group space-y-5"
        data-quick-transaction-form
        data-tipo="{{ $selectedTipo }}"
    >
        @csrf
        <input type="hidden" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}">

        {{-- 1. Cuenta --}}
        <div class="space-y-1.5">
            <label for="quick-cuenta" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                <span class="group-data-[tipo=transferencia]:hidden">{{ __('Account') }}</span>
                <span class="hidden group-data-[tipo=transferencia]:inline">{{ __('Debited account') }}</span>
            </label>
            <select
                id="quick-cuenta"
                name="cuenta_id"
                class="auth-input @error('cuenta_id') auth-input-error @enderror"
                required
                data-quick-transaction-field="cuenta_id"
            >
                @foreach ($cuentas as $cuenta)
                    <option value="{{ $cuenta->id }}" @selected((string) $defaultCuentaId === (string) $cuenta->id)>
                        {{ $cuenta->nombre }}
                    </option>
                @endforeach
            </select>
            @error('cuenta_id')
                <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
            @enderror
        </div>

        {{-- 2. Tipo de movimiento --}}
        <fieldset class="space-y-1.5">
            <legend class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Type') }}</legend>
            <div class="grid grid-cols-3 gap-1 rounded-xl bg-slate-100 p-1 dark:bg-slate-800">
                @foreach ($tipos as $tipo => $config)
                    <label @class([
                        'flex cursor-pointer items-center justify-center gap-1.5 rounded-lg px-2 py-2.5 text-sm font-semibold text-slate-600 transition hover:text-slate-900 dark:text-slate-300 dark:hover:text-white',
                        'has-focus-visible:ring-2 has-focus-visible:ring-slate-400',
                        $config['checked'],
                    ])>
                        <input
                            type="radio"
                            name="tipo"
                            value="{{ $tipo }}"
                            class="sr-only"
                            @checked($selectedTipo === $tipo)
                            data-quick-transaction-tipo
                        >
                        <x-dynamic-component :component="$config['icon']" class="size-5 shrink-0" />
                        <span class="truncate">{{ $config['label'] }}</span>
                    </label>
                @endforeach
            </div>
            @error('tipo')
                <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
            @enderror
        </fieldset>

        {{-- 3. Monto --}}
        <div>
            <label for="quick-monto-display" class="block text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Amount in soles') }}</label>
            <div class="mt-1 flex items-baseline gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm focus-within:border-palette-green focus-within:ring-2 focus-within:ring-palette-green/30 dark:border-slate-700 dark:bg-slate-900/60 @error('monto') border-palette-red/70 @enderror">
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
                    class="w-full min-w-0 border-0 bg-transparent p-0 text-right text-4xl font-semibold tabular-nums text-slate-800 placeholder:text-slate-300 focus:outline-none focus:ring-0 dark:text-white dark:placeholder:text-slate-600"
                    data-quick-transaction-monto-display
                />
            </div>
            @error('monto')
                <p class="mt-1 text-sm font-medium text-palette-red">{{ $message }}</p>
            @enderror
        </div>

        {{-- 4. Categoría (ingreso / egreso) --}}
        <div class="space-y-1.5 group-data-[tipo=transferencia]:hidden" data-quick-transaction-categoria-wrap>
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
                <option value="{{ $nuevaCategoria }}" @selected(old('categoria_id') === $nuevaCategoria)>＋ {{ __('New category…') }}</option>
            </select>
            @error('categoria_id')
                <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
            @enderror

            <div @class(['pt-1', 'hidden' => old('categoria_id') !== $nuevaCategoria]) data-quick-transaction-nueva-categoria>
                <label for="quick-nueva-categoria" class="sr-only">{{ __('New category') }}</label>
                <input
                    id="quick-nueva-categoria"
                    type="text"
                    name="nueva_categoria"
                    maxlength="80"
                    value="{{ old('nueva_categoria') }}"
                    placeholder="{{ __('Name of the new category') }}"
                    class="auth-input @error('nueva_categoria') auth-input-error @enderror"
                    @disabled(old('categoria_id') !== $nuevaCategoria)
                >
                @error('nueva_categoria')
                    <p class="mt-1 text-sm font-medium text-palette-red">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- 4. Cuenta destino (transferencia) --}}
        <div class="hidden space-y-1.5 group-data-[tipo=transferencia]:block">
            <label for="quick-cuenta-destino" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                {{ __('Destination account') }}
            </label>
            <select
                id="quick-cuenta-destino"
                name="cuenta_destino_id"
                class="auth-input @error('cuenta_destino_id') auth-input-error @enderror"
                data-quick-transaction-field="cuenta_destino_id"
            >
                <option value="">{{ __('Select an account') }}</option>
                @foreach ($cuentas as $cuenta)
                    <option value="{{ $cuenta->id }}" @selected((string) old('cuenta_destino_id') === (string) $cuenta->id)>
                        {{ $cuenta->nombre }}
                    </option>
                @endforeach
            </select>
            @error('cuenta_destino_id')
                <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
            @enderror
        </div>

        {{-- 5. Descripción --}}
        <div class="space-y-1.5">
            <label for="quick-descripcion" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                {{ __('Description') }} <span class="font-normal text-slate-400">({{ __('Optional') }})</span>
            </label>
            <textarea
                id="quick-descripcion"
                name="descripcion"
                rows="2"
                class="auth-input min-h-[3.5rem] resize-y @error('descripcion') auth-input-error @enderror"
                data-quick-transaction-field="descripcion"
            >{{ old('descripcion') }}</textarea>
            @error('descripcion')
                <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
            @enderror
        </div>

        {{-- 6. Botón único de registro --}}
        <button
            type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold shadow-md transition hover:brightness-95 focus:outline-none focus:ring-2
                group-data-[tipo=gasto]:bg-palette-red group-data-[tipo=gasto]:text-white group-data-[tipo=gasto]:shadow-palette-red/30 group-data-[tipo=gasto]:focus:ring-palette-red/40
                group-data-[tipo=ingreso]:bg-palette-green group-data-[tipo=ingreso]:text-slate-800 group-data-[tipo=ingreso]:shadow-palette-green/30 group-data-[tipo=ingreso]:focus:ring-palette-green/40
                group-data-[tipo=transferencia]:bg-sky-500 group-data-[tipo=transferencia]:text-white group-data-[tipo=transferencia]:shadow-sky-500/30 group-data-[tipo=transferencia]:focus:ring-sky-500/40"
        >
            <x-heroicon-o-check-circle class="size-5 shrink-0" />
            <span class="hidden group-data-[tipo=gasto]:inline">{{ __('Register expense') }}</span>
            <span class="hidden group-data-[tipo=ingreso]:inline">{{ __('Register income') }}</span>
            <span class="hidden group-data-[tipo=transferencia]:inline">{{ __('Register transfer') }}</span>
        </button>
    </form>
</x-auth.card>
