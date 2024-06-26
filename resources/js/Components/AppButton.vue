<template>
	<component :is="is" :class="classes">
		<div class="flex items-center space-x-1">
			<span class="w-5 h-5 -ml-1" v-if="$slots.iconLeft">
				<slot name="iconLeft" v-if="!loading" />
				<ArrowPathIcon class="w-5 h-5 animate-spin" v-if="loading" />
			</span>

			<span class="w-5 h-5 justify-center" v-if="$slots.icon">
				<slot name="icon" />
			</span>

			<span v-if="!$slots.icon">
				<slot />
			</span>

			<span class="w-5 h-5" v-if="$slots.iconRight">
				<slot name="iconRight" v-if="!loading" />
				<ArrowPathIcon class="w-5 h-5 animate-spin" v-if="loading" />
			</span>
		</div>
	</component>
</template>

<script setup>
import { computed } from "vue"
import { ArrowPathIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
	colour: {
		type: String,
		default: 'primary',
		validator: (value) => ['primary', 'secondary', 'accent', 'light-grey', 'dark-grey', 'red', 'dropdown'].includes(value)
	},
	size: {
		type: String,
		default: 'md',
		validator: (value) => ['sm', 'md'].includes(value)
	},
	is: {
		type: [String, Object],
		default: 'button'
	},
	loading: Boolean,
})

const getButtonColours = (colour) => {
	switch (colour) {
		case 'primary': return 'bg-primary text-white hover:bg-primary-dark focus:ring-primary-light'
		case 'secondary': return 'bg-secondary text-white hover:bg-secondary-dark focus:ring-secondary-light'
		case 'accent': return 'bg-accent text-white hover:bg-accent-dark focus:ring-accent-light'
		case 'light-grey': return 'bg-neutral-light-grey text-white hover:bg-neutral-dark-grey focus:ring-neutral-light-grey'
		case 'dark-grey': return 'bg-neutral-dark-grey text-white hover:bg-neutral-light-grey focus:ring-neutral-dark-grey'
		case 'red': return 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-700'
		case 'dropdown': return 'h-[37px] rounded-md !border-neutral-200 focus:!border-secondary focus:!ring-0'
		default: return ''
	}
}

const getButtonSizes = (size) => {
	switch (size) {
		case 'sm': return 'h-6 px-2 text-sm'
		case 'md': return 'h-11 px-4 text-md'
		default: return ''
	}
}

const classes = computed(() => {
	return [
		getButtonColours(props.colour),
		getButtonSizes(props.size),
		"inline-flex items-center space-x-1 rounded-lg border border-transparent font-semibold whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-30 transition-colors",
	]
})
</script>
