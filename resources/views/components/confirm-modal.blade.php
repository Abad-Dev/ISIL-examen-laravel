<div
    data-confirm-modal
    class="fixed inset-0 z-[60] hidden items-end justify-center p-4 sm:items-center"
    role="alertdialog"
    aria-modal="true"
    aria-labelledby="confirm-modal-title"
    aria-describedby="confirm-modal-message"
>
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" data-confirm-modal-cancel></div>

    <div class="relative z-10 w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-xl ring-1 ring-slate-100 dark:bg-slate-900 dark:ring-slate-800">
        <div class="flex h-1.5">
            <span class="flex-1 bg-palette-green"></span>
            <span class="flex-1 bg-palette-yellow"></span>
            <span class="flex-1 bg-palette-orange"></span>
            <span class="flex-1 bg-palette-red"></span>
        </div>

        <div class="px-6 py-5">
            <div class="flex items-start gap-4">
                <span
                    class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-palette-red/15 text-palette-red dark:bg-palette-red/20"
                    data-confirm-modal-icon
                >
                    <x-heroicon-o-exclamation-triangle class="size-6" />
                </span>

                <div class="min-w-0 flex-1">
                    <h2 id="confirm-modal-title" class="text-lg font-semibold text-slate-800 dark:text-white" data-confirm-modal-title></h2>
                    <p id="confirm-modal-message" class="mt-2 text-sm text-slate-600 dark:text-slate-300" data-confirm-modal-message></p>
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700"
                    data-confirm-modal-cancel
                >
                    {{ __('Cancel') }}
                </button>
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-xl bg-palette-red px-4 py-2.5 text-sm font-semibold text-white transition hover:brightness-95"
                    data-confirm-modal-confirm
                >
                    {{ __('Confirm') }}
                </button>
            </div>
        </div>
    </div>
</div>
