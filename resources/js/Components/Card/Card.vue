<template>
	<div class="w-full shadow-md rounded-[11px]" :class="[
		colourClasses,
	]">
		<slot />
	</div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
	colour: {
		type: String,
		default: 'white',
		validator: val => ['white', 'grey'].includes(val)
	},
})

const colourClasses = computed(() => {
	switch (props.colour) {
		case 'white': return 'bg-white border-gray-500'
		case 'grey': return 'bg-gray-100 border-gray-500'
	}
})
</script>
