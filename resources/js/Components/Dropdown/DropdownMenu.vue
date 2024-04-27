<template>
	<Menu as="div" class="relative items-center text-left hidden lg:inline-flex">
		<div class="flex items-center">
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
				alignmentClasses,
			]">

				<div class="px-1 py-1" v-if="links.length > 0">
					<MenuItem as="div" v-slot="{ active }" v-for="link in links" :key="link.name">

					<Link :href="link.href" class="text-gray-800" :class="[
						{ 'bg-accent-light ': active },
						{ 'text-red-600': link.danger },
						{ 'text-green-800': link.success },
						'group flex w-full items-center rounded-md px-2 py-2 xl:py-2 transition-colors',
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
	<div class="lg:hidden grid grid-cols-1 lg:grid-cols-2 justify-start gap-x-4 gap-y-3" v-if="links.length > 0">
		<AppButton class="justify-center" v-for="link in links" :is="Link" :key="link.name" :href="link.href" :colour="link.colour ?? 'primary'" :size="link.size ?? 'md'">
			{{ link.name }}
		</AppButton>

		<slot name="extraMobile" />
	</div>
</template>

<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue'
import { EllipsisVerticalIcon } from '@heroicons/vue/24/solid';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
	links: {
		type: Array,
		default: () => []
	},
	alignmentClasses: {
		type: String,
		default: 'left-0 top-full lg:right-0 lg:left-unset mt-2'
	},
	breakpoint: {
		type: String,
		default: 'lg',
	}
});
</script>
