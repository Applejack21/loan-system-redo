import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path, { resolve } from 'path'

export default defineConfig({
	server: {
		hmr: {
			host: 'localhost',
		},
	},
	build: {
		commonjsOptions: {
			include: ['tailwind.config.js', 'node_modules/**']
		}
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
			'custom-css': '/resources/css',
		}
	},
	optimizeDeps: {
		include: [
			'tailwind-config',
		]
	}
});
