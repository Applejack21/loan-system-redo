import './bootstrap';
import '../css/app.css';
import "vue-toastification/dist/index.css";

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { createInertiaApp, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import AppButton from '@/Components/AppButton.vue';
import Toast from "vue-toastification";
import Highcharts from 'highcharts'
import HighchartsVue from 'highcharts-vue'

const chartOptions = {
	chart: {
		style: {
			fontFamily: 'Exo 2',
		}
	},
	title: {
		text: '',
		style: {
			fontWeight: 'normal',
			fontSize: '1rem',
			color: '#001F3F',
		}
	}
}

Highcharts.setOptions(chartOptions);

createInertiaApp({
	resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
	setup({ el, App, props, plugin }) {
		return createApp({ render: () => h(App, props) })
			.use(plugin)
			.use(Toast)
			.use(HighchartsVue)
			.mixin({
				methods: { route },
				components: {
					Link,
					AppLayout,
					AppButton,
				}
			})
			.mount(el);
	},
	progress: {
		color: '#FF5733',
	},
});
