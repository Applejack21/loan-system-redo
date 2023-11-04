<template>
	<div :class="[
		{ 'w-px md:whitespace-nowrap': autoWidth },
		'table-cell py-3.5 px-3 text-left text-gray-700 font-normal border-b border-accent bg-white align-middle'
	]">
		<span :class="`text-${textSize}`">
			<slot />
		</span>
	</div>
</template>

<script setup>
defineProps({
	autoWidth: {
		type: Boolean,
		default: false
	},
	textSize: {
		type: String,
		required: false,
		default: 'md',
	}
})
</script>
