<template>
	<div class="relative">
		<div
			:class="[classConfig.default, classConfig[breakpoint]]">
			<slot />
		</div>
	</div>
</template>

<script setup>
import { inject } from "vue"

const breakpoint = inject("tableBreakpoint", "lg")

const classConfig = {
	default: "w-full border-separate",
	md: "md:table",
	lg: "lg:table",
	xl: "xl:table",
}
</script>
