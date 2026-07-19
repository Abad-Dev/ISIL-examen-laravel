@props([
    'cuentas',
    'categorias',
    'filters' => [],
    'expanded' => false,
])

@php
    $activeFilterCount = collect($filters)->filter(fn ($value) => $value !== null && $value !== '')->count();
@endphp

<form method="GET" action="{{ route('web.transacciones.index') }}">
    <details
        @class([
            'group rounded-2xl bg-white shadow-sm ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800',
            '[&_summary::-webkit-details-marker]:hidden',
        ])
        @if ($expanded) open @endif
        data-transaccion-filters
    >
        <summary class="flex cursor-pointer list-none items-center justify-between gap-3 px-4 py-4">
            <div class="flex min-w-0 items-center gap-2">
                <x-heroicon-o-funnel class="size-5 shrink-0 text-slate-500 dark:text-slate-400" />
                <span class="text-sm font-semibold text-slate-800 dark:text-white">{{ __('Filters') }}</span>
                @if ($activeFilterCount > 0)
                    <span class="inline-flex rounded-full bg-palette-green/20 px-2 py-0.5 text-xs font-medium text-slate-700 dark:text-slate-200">
                        {{ $activeFilterCount }}
                    </span>
                @endif
            </div>

            <div class="flex shrink-0 items-center gap-3">
                @if ($activeFilterCount > 0)
                    <a
                        href="{{ route('web.transacciones.index') }}"
                        class="text-sm font-medium text-slate-500 transition hover:text-slate-800 dark:text-slate-400 dark:hover:text-white"
                        onclick="event.stopPropagation()"
                    >
                        {{ __('Clear filters') }}
                    </a>
                @endif
                <x-heroicon-o-chevron-down class="size-5 shrink-0 text-slate-400 transition group-open:rotate-180 dark:text-slate-500" />
            </div>
        </summary>

        <div class="border-t border-slate-100 px-4 pb-4 pt-4 dark:border-slate-800">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="space-y-1.5">
                    <label for="filter-fecha-desde" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Date from') }}
                    </label>
                    <input
                        id="filter-fecha-desde"
                        type="date"
                        name="fecha_desde"
                        value="{{ $filters['fecha_desde'] ?? '' }}"
                        class="auth-input @error('fecha_desde') auth-input-error @enderror"
                    />
                    @error('fecha_desde')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="filter-fecha-hasta" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Date to') }}
                    </label>
                    <input
                        id="filter-fecha-hasta"
                        type="date"
                        name="fecha_hasta"
                        value="{{ $filters['fecha_hasta'] ?? '' }}"
                        class="auth-input @error('fecha_hasta') auth-input-error @enderror"
                    />
                    @error('fecha_hasta')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="filter-cuenta" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Account') }}
                    </label>
                    <select id="filter-cuenta" name="cuenta_id" class="auth-input @error('cuenta_id') auth-input-error @enderror">
                        <option value="">{{ __('All accounts') }}</option>
                        @foreach ($cuentas as $cuenta)
                            <option value="{{ $cuenta->id }}" @selected((string) ($filters['cuenta_id'] ?? '') === (string) $cuenta->id)>
                                {{ $cuenta->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cuenta_id')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="filter-categoria" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Category') }}
                    </label>
                    <select id="filter-categoria" name="categoria_id" class="auth-input @error('categoria_id') auth-input-error @enderror">
                        <option value="">{{ __('All categories') }}</option>
                        <option value="none" @selected(($filters['categoria_id'] ?? '') === 'none')>{{ __('No category') }}</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected((string) ($filters['categoria_id'] ?? '') === (string) $categoria->id)>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria_id')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="filter-tipo" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Type') }}
                    </label>
                    <select id="filter-tipo" name="tipo" class="auth-input @error('tipo') auth-input-error @enderror">
                        <option value="">{{ __('All types') }}</option>
                        @foreach (['ingreso', 'gasto'] as $tipo)
                            <option value="{{ $tipo }}" @selected(($filters['tipo'] ?? '') === $tipo)>
                                {{ __("transaction_type.{$tipo}") }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="filter-monto-min" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Min amount') }}
                    </label>
                    <input
                        id="filter-monto-min"
                        type="number"
                        name="monto_min"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        value="{{ $filters['monto_min'] ?? '' }}"
                        class="auth-input @error('monto_min') auth-input-error @enderror"
                    />
                    @error('monto_min')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1.5">
                    <label for="filter-monto-max" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
                        {{ __('Max amount') }}
                    </label>
                    <input
                        id="filter-monto-max"
                        type="number"
                        name="monto_max"
                        step="0.01"
                        min="0"
                        placeholder="0.00"
                        value="{{ $filters['monto_max'] ?? '' }}"
                        class="auth-input @error('monto_max') auth-input-error @enderror"
                    />
                    @error('monto_max')
                        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="auth-btn-primary gap-2 sm:w-auto sm:px-5">
                    <x-heroicon-o-funnel class="size-5 shrink-0" />
                    {{ __('Apply filters') }}
                </button>
            </div>
        </div>
    </details>
</form>
