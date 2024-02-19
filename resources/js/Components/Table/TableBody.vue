<template>
	<div
		:class="[classConfig.default, classConfig[breakpoint]]">
		<slot />
	</div>
</template>

<script setup>
import { inject } from "vue"

const breakpoint = inject("tableBreakpoint", "lg")

const classConfig = {
	default: 'space-y-5',
	md: "md:table-row-group",
	lg: "lg:table-row-group",
	xl: "xl:table-row-group",
}
</script>
