function initCategoriaModal() {
    const modal = document.querySelector('[data-categoria-modal]');

    if (!modal) {
        return;
    }

    const iconInput = modal.querySelector('[data-categoria-icon-input]');
    const colorInput = modal.querySelector('[data-categoria-color-input]');

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

    const openModal = () => {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        modal.querySelector('[name="nombre"]')?.focus();
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-categoria-modal-open]').forEach((element) => {
        element.addEventListener('click', openModal);
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
