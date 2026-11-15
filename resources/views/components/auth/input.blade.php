@props([
    'label',
    'name',
    'type' => 'text',
    'icon' => null,
])

<div class="space-y-1.5">
    <label for="{{ $name }}" class="block text-sm font-medium text-slate-700 dark:text-slate-300">
        {{ $label }}
    </label>

    <div class="relative">
        @if ($icon)
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                <x-dynamic-component :component="$icon" class="size-5" />
            </span>
        @endif

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $type }}"
            @error($name) aria-invalid="true" @enderror
            {{ $attributes->class([
                'auth-input',
                'auth-input-error' => $errors->has($name),
                'pl-10' => $icon,
                'pr-11' => $type === 'password',
            ]) }}
            @if ($type === 'password') data-password-input @endif
        />

        @if ($type === 'password')
            <button
                type="button"
                class="absolute inset-y-0 right-0 flex items-center px-3 text-slate-400 transition hover:text-slate-700 focus:outline-none focus-visible:text-slate-700 dark:hover:text-slate-200 dark:focus-visible:text-slate-200"
                aria-label="{{ __('Show password') }}"
                aria-controls="{{ $name }}"
                aria-pressed="false"
                data-password-toggle
                data-label-show="{{ __('Show password') }}"
                data-label-hide="{{ __('Hide password') }}"
            >
                <x-heroicon-o-eye class="size-5" data-password-toggle-show />
                <x-heroicon-o-eye-slash class="hidden size-5" data-password-toggle-hide />
            </button>
        @endif
    </div>

    @error($name)
        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
    @enderror
</div>
