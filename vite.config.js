import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite'; // <-- Plugin oficial de Tailwind v4

export default defineConfig({
    plugins: [
        tailwindcss(), // <-- Se registra el plugin aquí
        laravel({
            input: [
                'resources/css/app.css', // <-- Apuntando correctamente a tu CSS principal
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});