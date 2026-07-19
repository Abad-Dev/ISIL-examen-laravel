function initQuickTransaction() {
    const form = document.querySelector('[data-quick-transaction-form]');

    if (!form) {
        return;
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
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initQuickTransaction);
} else {
    initQuickTransaction();
}
