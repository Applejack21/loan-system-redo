<template>
	<component :is="is"
		:class="['inline-flex items-center justify-center w-8 h-8 rounded-md border focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-30', getButtonColours()]">
		<slot />
	</component>
</template>

<script setup>
const props = defineProps({
	active: {
		type: Boolean,
		default: false,
	},
	is: {
		type: [Object, String],
		default: 'button'
	},
})

const getButtonColours = () => {
	switch (props.active) {
		case true: return 'hover:bg-gray-400 border-accent-dark text-accent-dark focus:ring-accent-dark transition-colors'
		case false: return 'bg-neutral-light-grey hover:bg-accent-light border-gray-500 text-accent-dark focus:ring-accent-dark transition-colors'
		default: return ''
	}
}
</script>

