function initCuentaModal() {
    const modal = document.querySelector('[data-cuenta-modal]');

    if (!modal) {
        return;
    }

    const form = modal.querySelector('[data-cuenta-form]');
    const title = modal.querySelector('[data-cuenta-modal-title]');
    const iconInput = modal.querySelector('[data-cuenta-icon-input]');
    const colorInput = modal.querySelector('[data-cuenta-color-input]');
    const submitLabelCreate = modal.querySelector('[data-cuenta-submit-label-create]');
    const submitLabelEdit = modal.querySelector('[data-cuenta-submit-label-edit]');
    const submitIconCreate = modal.querySelector('[data-cuenta-submit-icon-create]');
    const storeUrl = form?.dataset.storeUrl || '';
    const createTitle = modal.dataset.createTitle || '';
    const editTitle = modal.dataset.editTitle || '';

    const selectedIconClasses = ['border-palette-green', 'bg-palette-green/20', 'text-slate-800', 'dark:text-white'];
    const unselectedIconClasses = [
        'border-slate-200',
        'bg-white',
        'text-slate-600',
        'hover:border-palette-green/50',
        'hover:bg-palette-green/10',
        'dark:border-slate-700',
        'dark:bg-slate-800',
        'dark:text-slate-300',
        'dark:hover:border-palette-green/40',
    ];

    const selectedColorClasses = ['ring-slate-800', 'dark:ring-white'];
    const unselectedColorClasses = ['ring-transparent', 'hover:ring-slate-300', 'dark:hover:ring-slate-600'];

    const setPickerSelection = (type, value) => {
        const selector = type === 'icon' ? '[data-cuenta-icon-option]' : '[data-cuenta-color-option]';
        const dataKey = type === 'icon' ? 'cuentaIconOption' : 'cuentaColorOption';
        const selectedClasses = type === 'icon' ? selectedIconClasses : selectedColorClasses;
        const unselectedClasses = type === 'icon' ? unselectedIconClasses : unselectedColorClasses;

        modal.querySelectorAll(selector).forEach((option) => {
            const isSelected = option.dataset[dataKey] === value;
            option.classList.remove(...selectedClasses, ...unselectedClasses);
            option.classList.add(...(isSelected ? selectedClasses : unselectedClasses));
        });
    };

    const setFormMode = (mode, data = {}) => {
        if (!form) {
            return;
        }

        const isEdit = mode === 'edit';

        modal.dataset.cuentaModalMode = mode;

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

        const nombreField = form.querySelector('[data-cuenta-field="nombre"]');
        const tipoField = form.querySelector('[data-cuenta-field="tipo"]');
        const saldoField = form.querySelector('[data-cuenta-field="saldo"]');

        if (nombreField) {
            nombreField.value = isEdit ? data.nombre ?? '' : '';
        }

        if (tipoField) {
            tipoField.value = isEdit ? data.tipo ?? 'efectivo' : 'efectivo';
        }

        if (saldoField) {
            saldoField.value = isEdit ? data.saldo ?? '' : '';
        }

        const icon = isEdit ? data.icon : iconInput?.value;
        const color = isEdit ? data.color : colorInput?.value;

        if (iconInput && icon) {
            iconInput.value = icon;
            setPickerSelection('icon', icon);
        }

        if (colorInput && color) {
            colorInput.value = color;
            setPickerSelection('color', color);
        }

        submitLabelCreate?.classList.toggle('hidden', isEdit);
        submitLabelEdit?.classList.toggle('hidden', !isEdit);
        submitIconCreate?.classList.toggle('hidden', isEdit);
    };

    const openModal = (mode = 'create', data = {}) => {
        if (mode === 'create') {
            setFormMode('create');
        } else {
            setFormMode('edit', data);
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        form?.querySelector('[data-cuenta-field="nombre"]')?.focus();
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-cuenta-modal-open]').forEach((element) => {
        element.addEventListener('click', () => {
            const mode = element.dataset.cuentaModalOpen || 'create';

            if (mode === 'edit') {
                openModal('edit', {
                    updateUrl: element.dataset.cuentaUpdateUrl,
                    nombre: element.dataset.cuentaNombre,
                    tipo: element.dataset.cuentaTipo,
                    saldo: element.dataset.cuentaSaldo,
                    icon: element.dataset.cuentaIcon,
                    color: element.dataset.cuentaColor,
                });
            } else {
                openModal('create');
            }
        });
    });

    modal.querySelectorAll('[data-cuenta-modal-close]').forEach((element) => {
        element.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    modal.querySelectorAll('[data-cuenta-icon-option]').forEach((button) => {
        button.addEventListener('click', () => {
            iconInput.value = button.dataset.cuentaIconOption;
            setPickerSelection('icon', iconInput.value);
        });
    });

    modal.querySelectorAll('[data-cuenta-color-option]').forEach((button) => {
        button.addEventListener('click', () => {
            colorInput.value = button.dataset.cuentaColorOption;
            setPickerSelection('color', colorInput.value);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCuentaModal);
} else {
    initCuentaModal();
}
