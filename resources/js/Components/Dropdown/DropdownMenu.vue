<template>
	<Menu as="div" class="relative inline-flex items-center text-left">
		<div>
			<MenuButton as="template" v-slot="{ open }" @click.stop>
				<slot name="button" :open="open">
					<button
						:class="['cursor-pointer rounded-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent']">
						<EllipsisVerticalIcon :class="['w-6 h-6 transition-transform', { 'rotate-90': open }]" />
					</button>
				</slot>
			</MenuButton>
		</div>

		<transition enter-active-class="transition duration-100 ease-out" enter-from-class="transform scale-95 opacity-0"
			enter-to-class="transform scale-100 opacity-100" leave-active-class="transition duration-75 ease-in"
			leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
			<MenuItems :class="['absolute z-20 w-56 origin-top-right divide-y bg-white shadow-card ring-1 ring-black ring-opacity-5 focus:outline-none',
				horizontalClasses,
				verticalClasses,
			]">

				<div class="px-1 py-1" v-if="links.length > 0">
					<MenuItem as="div" v-slot="{ active }" v-for="link in links" :key="link.name">

					<Link :href="link.href" class="text-gray-800" :class="[
						{ 'bg-accent-light ': active },
						link.danger ? '!text-red-600' : '',
						link.success ? '!text-green-800' : '',
						'group flex w-full items-center rounded-md px-2 py-2 transition-colors',
					]">
					{{ link.name }}
					</Link>

					</MenuItem>
					<slot />
				</div>

				<slot name="extra" />

			</MenuItems>
		</transition>
	</Menu>
</template>

<script setup>
import { computed } from 'vue'
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { EllipsisVerticalIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
	links: {
		type: Array,
		default: () => []
	},
	openHorizontal: {
		type: String,
		required: false,
		default: "left",
	},
	openVertical: {
		type: String,
		required: false,
		default: "top",
	}
});

const horizontalClasses = computed(() => {
	switch (props.openHorizontal) {
		case 'left': return 'right-0 mr-8'
		case 'right': return 'left-0 ml-8'
		default: return '';
	}
});

const verticalClasses = computed(() => {
	switch (props.openVertical) {
		case 'top': return 'bottom-0'
		case 'bottom': return 'top-0'
		default: return '';
	}
})
</script>
