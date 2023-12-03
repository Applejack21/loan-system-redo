<template>
	<div class="flex items-center space-x-[7px]">
		<img class="rounded-full bg-gray-200" :class="sizeClasses" :src="user?.profile_photo_url" />

		<span :class="textSize">
			{{ user?.name }}
		</span>
	</div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
	user: Object,
	size: {
		type: String,
		default: 'md',
		validator: (val) => ['sm', 'md', 'lg'].includes(val)
	}
})

const sizeClasses = computed(() => {
	switch (props.size) {
		case 'sm': return 'w-10 h-10'
		case 'md': return 'w-14 h-14'
		case 'lg': return 'w-16 h-16'
		default: return ''
	}
})

const textSize = computed(() => {
	switch (props.size) {
		case 'sm': return 'text-sm'
		case 'md': return 'text-md'
		case 'lg': return 'text-lg'
		default: return ''
	}
});
</script>
