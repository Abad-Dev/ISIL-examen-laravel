function initSidebar() {
    const sidebar = document.getElementById('app-sidebar');
    const backdrop = document.getElementById('sidebar-backdrop');

    if (!sidebar) {
        return;
    }

    const open = () => {
        sidebar.classList.remove('-translate-x-full');
        backdrop?.classList.remove('hidden');
    };

    const close = () => {
        sidebar.classList.add('-translate-x-full');
        backdrop?.classList.add('hidden');
    };

    document.querySelectorAll('[data-sidebar-open]').forEach((element) => {
        element.addEventListener('click', open);
    });

    document.querySelectorAll('[data-sidebar-close]').forEach((element) => {
        element.addEventListener('click', close);
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            close();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initSidebar);
} else {
    initSidebar();
}
