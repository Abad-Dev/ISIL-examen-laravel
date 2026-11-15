document.addEventListener('click', (event) => {
    const toggle = event.target.closest('[data-password-toggle]');

    if (!toggle) {
        return;
    }

    const input = document.getElementById(toggle.getAttribute('aria-controls'));

    if (!input) {
        return;
    }

    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';

    toggle.setAttribute('aria-pressed', String(visible));
    toggle.setAttribute('aria-label', visible ? toggle.dataset.labelHide : toggle.dataset.labelShow);
    toggle.querySelector('[data-password-toggle-show]')?.classList.toggle('hidden', visible);
    toggle.querySelector('[data-password-toggle-hide]')?.classList.toggle('hidden', !visible);
});
