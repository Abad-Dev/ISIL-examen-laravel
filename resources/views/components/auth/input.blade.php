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
            ]) }}
        />
    </div>

    @error($name)
        <p class="text-sm font-medium text-palette-red">{{ $message }}</p>
    @enderror
</div>
