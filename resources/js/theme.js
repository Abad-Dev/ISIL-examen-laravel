const STORAGE_KEY = 'inaut-theme';

export function getTheme() {
    return localStorage.getItem(STORAGE_KEY) || 'system';
}

export function resolveDark(theme) {
    if (theme === 'dark') {
        return true;
    }

    if (theme === 'light') {
        return false;
    }

    return window.matchMedia('(prefers-color-scheme: dark)').matches;
}

export function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', resolveDark(theme));
    localStorage.setItem(STORAGE_KEY, theme);

    document.querySelectorAll('[data-theme-selector]').forEach((select) => {
        select.value = theme;
    });
}

export function initTheme() {
    applyTheme(getTheme());

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (getTheme() === 'system') {
            applyTheme('system');
        }
    });

    document.querySelectorAll('[data-theme-selector]').forEach((select) => {
        select.value = getTheme();
        select.addEventListener('change', (event) => {
            applyTheme(event.target.value);
        });
    });
}

// Apply before paint when imported on pages that load JS at end of body.
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTheme);
} else {
    initTheme();
}
