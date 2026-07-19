function initCuentaModal() {
    const modal = document.querySelector('[data-cuenta-modal]');

    if (!modal) {
        return;
    }

    const iconInput = modal.querySelector('[data-cuenta-icon-input]');
    const colorInput = modal.querySelector('[data-cuenta-color-input]');

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

    const openModal = () => {
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

    document.querySelectorAll('[data-cuenta-modal-open]').forEach((element) => {
        element.addEventListener('click', openModal);
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
            const icon = button.dataset.cuentaIconOption;
            iconInput.value = icon;

            modal.querySelectorAll('[data-cuenta-icon-option]').forEach((option) => {
                option.classList.remove(...selectedIconClasses);
                option.classList.add(...unselectedIconClasses);
            });

            button.classList.add(...selectedIconClasses);
            button.classList.remove(...unselectedIconClasses);
        });
    });

    modal.querySelectorAll('[data-cuenta-color-option]').forEach((button) => {
        button.addEventListener('click', () => {
            const color = button.dataset.cuentaColorOption;
            colorInput.value = color;

            modal.querySelectorAll('[data-cuenta-color-option]').forEach((option) => {
                option.classList.remove(...selectedColorClasses);
                option.classList.add(...unselectedColorClasses);
            });

            button.classList.add(...selectedColorClasses);
            button.classList.remove(...unselectedColorClasses);
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCuentaModal);
} else {
    initCuentaModal();
}
