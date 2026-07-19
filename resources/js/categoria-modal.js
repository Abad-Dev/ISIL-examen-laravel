function initCategoriaModal() {
    const modal = document.querySelector('[data-categoria-modal]');

    if (!modal) {
        return;
    }

    const form = modal.querySelector('[data-categoria-form]');
    const title = modal.querySelector('[data-categoria-modal-title]');
    const iconInput = modal.querySelector('[data-categoria-icon-input]');
    const colorInput = modal.querySelector('[data-categoria-color-input]');
    const submitCreate = modal.querySelector('[data-categoria-submit-create]');
    const submitEdit = modal.querySelector('[data-categoria-submit-edit]');
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
        const selector = type === 'icon' ? '[data-categoria-icon-option]' : '[data-categoria-color-option]';
        const dataKey = type === 'icon' ? 'categoriaIconOption' : 'categoriaColorOption';
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

        modal.dataset.categoriaModalMode = mode;

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

        const nombreField = form.querySelector('[data-categoria-field="nombre"]');
        const tipoField = form.querySelector('[data-categoria-field="tipo"]');
        const descripcionField = form.querySelector('[data-categoria-field="descripcion"]');

        if (nombreField) {
            nombreField.value = isEdit ? data.nombre ?? '' : '';
        }

        if (tipoField) {
            tipoField.value = isEdit ? data.tipo ?? 'gasto' : 'gasto';
        }

        if (descripcionField) {
            descripcionField.value = isEdit ? data.descripcion ?? '' : '';
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

        submitCreate?.classList.toggle('hidden', isEdit);
        submitEdit?.classList.toggle('hidden', !isEdit);
    };

    const openModal = (mode = 'create', data = {}) => {
        setFormMode(mode, data);
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        form?.querySelector('[data-categoria-field="nombre"]')?.focus();
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-categoria-modal-open]').forEach((element) => {
        element.addEventListener('click', () => {
            const mode = element.dataset.categoriaModalOpen || 'create';

            if (mode === 'edit') {
                openModal('edit', {
                    updateUrl: element.dataset.categoriaUpdateUrl,
                    nombre: element.dataset.categoriaNombre,
                    tipo: element.dataset.categoriaTipo,
                    descripcion: element.dataset.categoriaDescripcion,
                    icon: element.dataset.categoriaIcon,
                    color: element.dataset.categoriaColor,
                });
            } else {
                openModal('create');
            }
        });
    });

    modal.querySelectorAll('[data-categoria-modal-close]').forEach((element) => {
        element.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    modal.querySelectorAll('[data-categoria-icon-option]').forEach((button) => {
        button.addEventListener('click', () => {
            iconInput.value = button.dataset.categoriaIconOption;
            setPickerSelection('icon', iconInput.value);
        });
    });

    modal.querySelectorAll('[data-categoria-color-option]').forEach((button) => {
        button.addEventListener('click', () => {
            colorInput.value = button.dataset.categoriaColorOption;
            setPickerSelection('color', colorInput.value);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCategoriaModal);
} else {
    initCategoriaModal();
}
