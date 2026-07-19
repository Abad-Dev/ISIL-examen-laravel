<script>
    (function () {
        var theme = localStorage.getItem('inaut-theme') || 'system';
        var dark = theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (dark) document.documentElement.classList.add('dark');
    })();
</script>
