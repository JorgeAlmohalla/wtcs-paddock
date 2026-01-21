import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    // --- AÑADE ESTO ---
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Borra los console.log de producción
                drop_debugger: true,
            },
        },
        cssMinify: true, // Aplasta el CSS
        chunkSizeWarningLimit: 1000,
    },
});
