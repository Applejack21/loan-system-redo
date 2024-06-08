<template>
	<div :class="[
		classConfig.default,
		classConfig[computedBreakpoint],
		{ 'border-b border-grey-300 pb-1': border },
		borderClass[computedBreakpoint],
	]">
		<span :class="[
		headerConfig.default,
		headerConfig[computedBreakpoint]
	]" v-if="$slots.header">
			<slot name="header" />
		</span>
		<span v-if="$slots.default">
			<template v-if="popper">
				<!-- Data to display in the table. -->
				<Popper arrow hover class="max-w-full" :content="popperContent">
					<div class="underline decoration-dotted hover:cursor-help">
						<slot />
					</div>
				</Popper>
			</template>
			<template v-else>
				<slot />
			</template>
		</span>
	</div>
</template>

<script setup>
import Popper from "vue3-popper";
import { computed, inject } from "vue"

const props = defineProps({
	autoWidth: {
		type: Boolean,
		default: false
	},
	popper: {
		type: Boolean,
		required: false,
		default: false,
	},
	breakpoint: String,
	border: {
		type: Boolean,
		default: true,
	},
	popperContent: {
		type: String,
		required: false,
	},
});

const computedBreakpoint = computed(() => {
	const injectedValue = inject("tableBreakpoint", "lg")

	return props.breakpoint || injectedValue
})

const classConfig = {
	default: "grid grid-cols-1 sm:grid-cols-2 text-md align-middle",
	md: [
		"md:table-cell md:px-3 md:py-2.5 xl:h-[49px] md:whitespace-nowrap",
		props.autoWidth ? "md:w-px" : "",
		props.popper ? "md:truncate md:w-1/4 md:max-w-[1px]" : "",
	].join(" "),
	lg: [
		"lg:table-cell lg:px-3 lg:py-2.5 xl:h-[49px] lg:whitespace-nowrap",
		props.autoWidth ? "lg:w-px" : "",
		props.popper ? "lg:truncate lg:w-1/4 lg:max-w-[1px]" : "",
	].join(" "),
	xl: [
		"xl:table-cell xl:px-3 xl:py-2.5 xl:h-[49px] xl:whitespace-nowrap",
		props.autoWidth ? "xl:w-px" : "",
		props.popper ? "xl:truncate xl:w-1/4 xl:max-w-[1px]" : "",
	].join(" "),
}

const headerConfig = {
	default: "mb-2",
	md: "md:hidden",
	lg: "lg:hidden",
	xl: "xl:hidden",
}

const borderClass = {
	md: 'md:border-none',
	lg: 'lg:border-none',
	xl: 'xl:border-none',
}
</script>
