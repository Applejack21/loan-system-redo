import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path, { resolve } from 'path'

export default defineConfig({
    build: {
        commonjsOptions: {
            include: ['tailwind.config.js', 'node_modules/**']
        }
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel([
            'resources/js/app.js',
        ]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@root': path.resolve(__dirname),
            '@': '/resources/js',
            'tailwind-config': path.resolve(__dirname, 'tailwind.config.js'),
        }
    },
    optimizeDeps: {
        include: [
            'tailwind-config',
        ]
    }
});
