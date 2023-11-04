
<template>
	<div :class="[classConfig.default, classConfig[breakpoint]]">
		<slot />
	</div>
</template>

<script setup>
import { inject } from "vue"
const breakpoint = inject("tableBreakpoint", "lg")
const classConfig = {
	default: "hidden",
	md: "md:table-header-group",
	lg: "lg:table-header-group",
	xl: "xl:table-header-group",
}
</script>
