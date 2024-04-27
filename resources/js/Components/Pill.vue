<template>
	<component :is="is" :class="[
		'inline-flex items-center text-center rounded-full font-semibold border capitalize',
		colourClasses,
		sizeClasses
	]">
		<span class="w-5 h-5 pr-1 flex items-center" v-if="$slots.iconLeft">
			<slot name="iconLeft" />
		</span>
		<span>
			<slot />
		</span>
		<span class="w-5 h-5 pl-1 flex items-center" v-if="$slots.iconRight">
			<slot name="iconRight" />
		</span>
	</component>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
	is: {
		type: String,
		default: "span",
	},
	colour: {
		type: String,
		validator: (val) => ['red', 'green', 'amber'].includes(val),
		default: 'green'
	},
	size: {
		type: String,
		validator: (val) => ['xs', 'sm', 'md', 'lg'].includes(val),
		default: 'sm',
	}
})

const colourClasses = computed(() => {
	switch (props.colour) {
		case 'red': return 'border-red-100 text-white bg-red-600'
		case 'green': return 'border-green-600 text-green-400 bg-600'
		case 'amber': return 'border-amber-100 text-white bg-amber-600'
		default: return '';
	}
})

const sizeClasses = computed(() => {
	switch (props.size) {
		case 'xs': return 'text-xs h-6 px-[13px]'
		case 'sm': return 'text-sm h-8 px-[15px]'
		case 'md': return 'text-lg h-10 px-[17px]'
		case 'lg': return 'text-xl h-12 px-[19px]'
	}
})
</script>
