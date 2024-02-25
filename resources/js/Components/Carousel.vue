<template>
	<div id="gallery" class="relative w-full" data-carousel="slide">
		<div class="relative h-56 overflow-hidden rounded-lg md:h-96">
			<div v-for="item in items" class="hidden duration-700 ease-in-out" data-carousel-item>
				<img :srcset="item.src"
					class="absolute block max-w-full h-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="">
			</div>
		</div>

		<button type="button"
			class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
			data-carousel-prev>
			<span
				class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-500 dark:bg-gray-800/30 group-hover:bg-gray-600 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
				<ChevronLeftIcon class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" />
				<span class="sr-only">Previous</span>
			</span>
		</button>

		<button type="button"
			class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
			data-carousel-next>
			<span
				class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gray-500 dark:bg-gray-800/30 group-hover:bg-gray-600 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
				<ChevronRightIcon class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" />
				<span class="sr-only">Next</span>
			</span>
		</button>
	</div>
</template>

<script setup>
import { onMounted } from 'vue'
import { initCarousels } from 'flowbite'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';

onMounted(() => {
	initCarousels();
})

defineProps({
	items: {
		type: Array,
		default: () => []
	},
	options: {
		type: Object,
		default: {
			type: 'fade',
			autoplay: true,
			interval: 5000,
			rewind: true,
			perMove: 1,
			drag: true,
			pagination: false,
		}
	},
})
</script>
