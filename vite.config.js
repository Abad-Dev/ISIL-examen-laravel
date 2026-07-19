import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/cuenta-modal.js',
                'resources/js/categoria-modal.js',
                'resources/js/transaccion-modal.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
