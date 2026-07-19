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
}

export function toggleTheme() {
    const isDark = document.documentElement.classList.contains('dark');
    applyTheme(isDark ? 'light' : 'dark');
}

export function initTheme() {
    applyTheme(getTheme());

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        if (getTheme() === 'system') {
            applyTheme('system');
        }
    });

    window.addEventListener('storage', (event) => {
        if (event.key === STORAGE_KEY) {
            applyTheme(getTheme());
        }
    });

    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        if (button.dataset.themeBound === 'true') {
            return;
        }

        button.dataset.themeBound = 'true';
        button.addEventListener('click', toggleTheme);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTheme);
} else {
    initTheme();
}
