<template>
	<div class="relative overflow-x-auto 3xl:overflow-x-hidden thin-scrollbar"
		:class="{ 'border border-gray-100 rounded-md': border }">
		<template v-if="border">
			<div class="block h-2 bg-gradient-to-r from-accent-light to-accent-dark" />
		</template>
		<div :class="[classConfig.default, classConfig[breakpoint]]">
			<slot />
		</div>
	</div>
</template>

<script setup>
import { inject } from "vue"

defineProps({
	border: Boolean,
})

const breakpoint = inject("tableBreakpoint", "lg")
const classConfig = {
	default: "w-full border-separate",
	md: "md:table",
	lg: "lg:table",
	xl: "xl:table",
}
</script>
