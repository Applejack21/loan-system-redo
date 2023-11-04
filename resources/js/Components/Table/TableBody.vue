<template>
	<div :class="[classConfig[breakpoint]]">
		<slot />
	</div>
</template>

<script setup>
import { inject } from "vue"
const breakpoint = inject("tableBreakpoint", "lg")
const classConfig = {
	md: "md:table-row-group",
	lg: "lg:table-row-group",
	xl: "xl:table-row-group",
}
</script>
