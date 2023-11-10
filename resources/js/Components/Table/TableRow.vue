<template>
	<div :class="[classConfig.default, classConfig[breakpoint]]">
		<slot />
	</div>
</template>

<script setup>
import { inject } from "vue"

const breakpoint = inject("tableBreakpoint", "lg")

const classConfig = {
	default: "px-5 py-6 bg-white border-b first:border-t odd:bg-neutral-light-grey border-neutral-light-grey space-y-6 group transition-colors",
	md: "md:table-row md:p-0 md:rounded-0 md:border-b-0 md:odd:bg-neutral-light-grey md:hover:bg-gray-200 md:space-y-0 md:space-y-0",
	lg: "lg:table-row lg:p-0 lg:rounded-0 lg:border-b-0 lg:odd:bg-neutral-light-grey lg:hover:bg-gray-200 lg:space-y-0 lg:space-y-0",
	xl: "xl:table-row xl:p-0 xl:rounded-0 xl:border-b-0 xl:odd:bg-neutral-light-grey xl:hover:bg-gray-200 xl:space-y-0 xl:space-y-0",
}
</script>
