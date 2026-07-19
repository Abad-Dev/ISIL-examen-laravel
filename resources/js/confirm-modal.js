let resolveConfirm = null;

function getConfirmModal() {
    return document.querySelector('[data-confirm-modal]');
}

function setConfirmVariant(modal, variant) {
    const confirmButton = modal.querySelector('[data-confirm-modal-confirm]');

    if (!confirmButton) {
        return;
    }

    confirmButton.classList.remove(
        'bg-palette-red',
        'text-white',
        'bg-palette-green',
        'text-slate-800',
    );

    if (variant === 'primary') {
        confirmButton.classList.add('bg-palette-green', 'text-slate-800');
    } else {
        confirmButton.classList.add('bg-palette-red', 'text-white');
    }
}

export function showConfirmModal({
    title,
    message,
    confirmLabel,
    variant = 'danger',
}) {
    const modal = getConfirmModal();

    if (!modal) {
        return Promise.resolve(false);
    }

    const titleElement = modal.querySelector('[data-confirm-modal-title]');
    const messageElement = modal.querySelector('[data-confirm-modal-message]');
    const confirmButton = modal.querySelector('[data-confirm-modal-confirm]');

    if (titleElement) {
        titleElement.textContent = title || '';
    }

    if (messageElement) {
        messageElement.textContent = message || '';
    }

    if (confirmButton) {
        confirmButton.textContent = confirmLabel || confirmButton.dataset.defaultLabel || confirmButton.textContent;
    }

    setConfirmVariant(modal, variant);

    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.classList.add('overflow-hidden');
    confirmButton?.focus();

    return new Promise((resolve) => {
        resolveConfirm = resolve;
    });
}

function closeConfirmModal(result) {
    const modal = getConfirmModal();

    if (!modal) {
        return;
    }

    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.classList.remove('overflow-hidden');

    if (resolveConfirm) {
        resolveConfirm(result);
        resolveConfirm = null;
    }
}

function initConfirmModal() {
    const modal = getConfirmModal();

    if (!modal) {
        return;
    }

    const confirmButton = modal.querySelector('[data-confirm-modal-confirm]');

    if (confirmButton) {
        confirmButton.dataset.defaultLabel = confirmButton.textContent.trim();
    }

    modal.querySelectorAll('[data-confirm-modal-cancel]').forEach((element) => {
        element.addEventListener('click', () => closeConfirmModal(false));
    });

    confirmButton?.addEventListener('click', () => closeConfirmModal(true));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && modal.classList.contains('flex')) {
            closeConfirmModal(false);
        }
    });

    document.querySelectorAll('[data-confirm-submit]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            if (form.dataset.confirmed === 'true') {
                form.dataset.confirmed = 'false';
                return;
            }

            event.preventDefault();

            const confirmed = await showConfirmModal({
                title: form.dataset.confirmTitle || '',
                message: form.dataset.confirmMessage || '',
                confirmLabel: form.dataset.confirmLabel || undefined,
                variant: form.dataset.confirmVariant || 'danger',
            });

            if (confirmed) {
                form.dataset.confirmed = 'true';
                form.requestSubmit();
            }
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initConfirmModal);
} else {
    initConfirmModal();
}
