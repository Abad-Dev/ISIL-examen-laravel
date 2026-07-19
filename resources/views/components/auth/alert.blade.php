@props([
    'type' => 'success',
])

@php
    $styles = match ($type) {
        'success' => 'border-palette-green/50 bg-palette-green/20 text-slate-700',
        'error' => 'border-palette-red/50 bg-palette-red/15 text-slate-800',
        default => 'border-slate-200 bg-white text-slate-700',
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-xl border px-4 py-3 text-sm {$styles}"]) }} role="alert">
    {{ $slot }}
</div>
