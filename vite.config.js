import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { resolve } from 'node:path';

/**
 * Build the docs styles (docs.css), the highlighter and page script (docs.js) and the script that starts Alpine (alpine.js) into resources/dist, under fixed names,
 * so the package serves them itself and needs nothing from the app's own build.
 */
export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: 'resources/dist',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                styles: resolve(import.meta.dirname, 'resources/css/docs.css'),
                docs: resolve(import.meta.dirname, 'resources/js/docs.js'),
                alpine: resolve(import.meta.dirname, 'resources/js/alpine.js'),
            },
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: 'docs[extname]',
            },
        },
    },
});
