function initTransaccionModal() {
    const modal = document.querySelector('[data-transaccion-modal]');

    if (!modal) {
        return;
    }

    const form = modal.querySelector('[data-transaccion-form]');
    const title = modal.querySelector('[data-transaccion-modal-title]');
    const submitCreate = modal.querySelector('[data-transaccion-submit-create]');
    const submitEdit = modal.querySelector('[data-transaccion-submit-edit]');
    const storeUrl = form?.dataset.storeUrl || '';
    const createTitle = modal.dataset.createTitle || '';
    const editTitle = modal.dataset.editTitle || '';
    const tipoField = form?.querySelector('[data-transaccion-field="tipo"]');
    const categoriaField = form?.querySelector('[data-transaccion-field="categoria_id"]');
    const categoriaOptions = categoriaField
        ? Array.from(categoriaField.querySelectorAll('option[data-categoria-tipo]'))
        : [];

    const filterCategorias = () => {
        if (!tipoField || !categoriaField) {
            return;
        }

        const selectedTipo = tipoField.value;
        let hasVisibleSelection = false;

        categoriaOptions.forEach((option) => {
            const matches = option.dataset.categoriaTipo === selectedTipo;
            option.hidden = !matches;
            option.disabled = !matches;

            if (matches && option.value === categoriaField.value) {
                hasVisibleSelection = true;
            }
        });

        if (!hasVisibleSelection) {
            categoriaField.value = '';
        }
    };

    const setFormMode = (mode, data = {}) => {
        if (!form) {
            return;
        }

        const isEdit = mode === 'edit';

        modal.dataset.transaccionModalMode = mode;

        if (title) {
            title.textContent = isEdit ? editTitle : createTitle;
        }

        form.setAttribute('action', isEdit ? data.updateUrl : storeUrl);

        let methodField = form.querySelector('input[name="_method"]');

        if (isEdit) {
            if (!methodField) {
                methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                form.appendChild(methodField);
            }
            methodField.value = 'PUT';
        } else if (methodField) {
            methodField.remove();
        }

        const cuentaField = form.querySelector('[data-transaccion-field="cuenta_id"]');
        const montoField = form.querySelector('[data-transaccion-field="monto"]');
        const fechaField = form.querySelector('[data-transaccion-field="fecha"]');
        const descripcionField = form.querySelector('[data-transaccion-field="descripcion"]');

        if (tipoField) {
            tipoField.value = isEdit ? data.tipo ?? 'gasto' : 'gasto';
        }

        filterCategorias();

        if (cuentaField) {
            cuentaField.value = isEdit ? data.cuentaId ?? '' : '';
        }

        if (categoriaField) {
            categoriaField.value = isEdit ? data.categoriaId ?? '' : '';
        }

        if (montoField) {
            montoField.value = isEdit ? data.monto ?? '' : '';
        }

        if (fechaField) {
            fechaField.value = isEdit ? data.fecha ?? '' : new Date().toISOString().slice(0, 10);
        }

        if (descripcionField) {
            descripcionField.value = isEdit ? data.descripcion ?? '' : '';
        }

        submitCreate?.classList.toggle('hidden', isEdit);
        submitEdit?.classList.toggle('hidden', !isEdit);
    };

    const openModal = (mode = 'create', data = {}) => {
        setFormMode(mode, data);
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        tipoField?.focus();
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-transaccion-modal-open]').forEach((element) => {
        element.addEventListener('click', () => {
            const mode = element.dataset.transaccionModalOpen || 'create';

            if (mode === 'edit') {
                openModal('edit', {
                    updateUrl: element.dataset.transaccionUpdateUrl,
                    cuentaId: element.dataset.transaccionCuentaId,
                    categoriaId: element.dataset.transaccionCategoriaId,
                    tipo: element.dataset.transaccionTipo,
                    monto: element.dataset.transaccionMonto,
                    descripcion: element.dataset.transaccionDescripcion,
                    fecha: element.dataset.transaccionFecha,
                });
            } else {
                openModal('create');
            }
        });
    });

    modal.querySelectorAll('[data-transaccion-modal-close]').forEach((element) => {
        element.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    tipoField?.addEventListener('change', filterCategorias);

    filterCategorias();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTransaccionModal);
} else {
    initTransaccionModal();
}
