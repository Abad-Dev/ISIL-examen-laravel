@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'overflow-hidden rounded-2xl bg-white shadow-xl shadow-slate-200/60 ring-1 ring-slate-100']) }}>
    <div class="flex h-1.5">
        <span class="flex-1 bg-palette-green"></span>
        <span class="flex-1 bg-palette-yellow"></span>
        <span class="flex-1 bg-palette-orange"></span>
        <span class="flex-1 bg-palette-red"></span>
    </div>

    <div class="border-b border-slate-100 px-6 py-5">
        <h1 class="text-xl font-semibold tracking-tight text-slate-800">{{ $title }}</h1>
        @if ($subtitle)
            <p class="mt-1 text-sm text-slate-500">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="px-6 py-6">
        {{ $slot }}
    </div>
</div>
