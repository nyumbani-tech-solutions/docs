import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'node:path';

/**
 * Build the docs styles (docs.css) and the script that starts Alpine (docs.js) into resources/dist, under fixed names,
 * so the package serves them itself and needs nothing from the app's own build.
 */
export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                docs: resolve(import.meta.dirname, 'resources/js/docs.js'),
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name][extname]',
            },
        },
    },
});
