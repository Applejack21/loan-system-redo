<template>
	<div :class="[
		{ 'w-px md:whitespace-nowrap': autoWidth },
		{ 'whitespace-nowrap': nowrap },
		'table-cell align-middle p-3 min-h-[49px] font-bold bg-white'
	]">
		<span>
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
	nowrap: {
		type: Boolean,
		default: false,
	}
})
</script>
