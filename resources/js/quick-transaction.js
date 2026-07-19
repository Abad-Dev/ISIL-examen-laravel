const MAX_CENTS = 999_999_999_99;

function formatCents(cents) {
    const integerPart = Math.floor(cents / 100);
    const decimalPart = cents % 100;

    return `${String(integerPart).padStart(2, '0')}.${String(decimalPart).padStart(2, '0')}`;
}

function parseAmountToCents(value) {
    if (!value) {
        return 0;
    }

    const normalized = String(value).replace(',', '.');
    const amount = Number.parseFloat(normalized);

    if (Number.isNaN(amount) || amount < 0) {
        return 0;
    }

    return Math.round(amount * 100);
}

function initCentAmountInput(displayInput, hiddenInput) {
    let cents = parseAmountToCents(hiddenInput.value);

    const sync = () => {
        displayInput.value = cents > 0 ? formatCents(cents) : '0.00';
        hiddenInput.value = cents > 0 ? (cents / 100).toFixed(2) : '';
    };

    displayInput.addEventListener('keydown', (event) => {
        if (/^\d$/.test(event.key)) {
            event.preventDefault();
            cents = Math.min(cents * 10 + Number.parseInt(event.key, 10), MAX_CENTS);
            sync();
            return;
        }

        if (event.key === 'Backspace') {
            event.preventDefault();
            cents = Math.floor(cents / 10);
            sync();
            return;
        }

        if (event.key === 'Delete') {
            event.preventDefault();
            cents = 0;
            sync();
        }
    });

    displayInput.addEventListener('input', () => {
        sync();
    });

    displayInput.addEventListener('paste', (event) => {
        event.preventDefault();
    });

    sync();
}

function initQuickTransaction() {
    const form = document.querySelector('[data-quick-transaction-form]');

    if (!form) {
        return;
    }

    const displayInput = form.querySelector('[data-quick-transaction-monto-display]');
    const hiddenInput = form.querySelector('[data-quick-transaction-monto-value]');

    if (displayInput && hiddenInput) {
        initCentAmountInput(displayInput, hiddenInput);
    }

    const categoriaField = form.querySelector('[data-quick-transaction-field="categoria_id"]');
    const categoriaOptions = categoriaField
        ? Array.from(categoriaField.querySelectorAll('option[data-categoria-tipo]'))
        : [];

    const firstMatchingCategoria = (tipo) => {
        const match = categoriaOptions.find((option) => option.dataset.categoriaTipo === tipo);

        return match ? match.value : '';
    };

    const syncCategoriaForTipo = (tipo) => {
        if (!categoriaField) {
            return;
        }

        const selectedOption = categoriaField.selectedOptions[0];

        if (selectedOption?.dataset.categoriaTipo && selectedOption.dataset.categoriaTipo !== tipo) {
            categoriaField.value = firstMatchingCategoria(tipo);
        }
    };

    form.querySelectorAll('[data-quick-transaction-submit]').forEach((button) => {
        button.addEventListener('click', () => {
            syncCategoriaForTipo(button.dataset.quickTransactionSubmit);
        });
    });

    form.addEventListener('submit', (event) => {
        const cents = parseAmountToCents(hiddenInput?.value);

        if (cents < 1) {
            event.preventDefault();
            displayInput?.focus();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initQuickTransaction);
} else {
    initQuickTransaction();
}
