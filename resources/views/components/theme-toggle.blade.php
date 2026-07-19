<button
    type="button"
    data-theme-toggle
    {{ $attributes->merge(['class' => 'theme-toggle']) }}
    aria-label="{{ __('Toggle theme') }}"
>
    <x-heroicon-o-moon class="size-5 dark:hidden" />
    <x-heroicon-o-sun class="hidden size-5 dark:block" />
</button>
