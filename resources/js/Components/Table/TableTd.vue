<template>
	<div :class="[classConfig.default, classConfig.header, classConfig[breakpoint], `text-${textSize}`]">
		<span :class="[headerConfig.default, headerConfig[breakpoint]]" v-if="$slots.header">
			<slot name="header" />
		</span>
		<span v-if="$slots.default">
			<template v-if="popper">
				<Popper arrow hover>
					<!-- data to display in table -->
					<div class="lg:underline lg:decoration-dotted hover:cursor-help flex items-end">
						<slot />
					</div>
					<template #content>
						<!-- data to display on popper hover -->
						<div>
							<slot />
						</div>
					</template>
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
import { inject } from "vue"

const breakpoint = inject("tableBreakpoint", "lg")

const props = defineProps({
	autoWidth: {
		type: Boolean,
		default: false
	},
	textSize: {
		type: String,
		required: false,
		default: 'md',
	},
	popper: {
		type: Boolean,
		required: false,
		default: false,
	}
})

const classConfig = {
	default: "flex flex-col text-md text-gray-700 align-middle",
	md: [
		"md:table-cell md:py-3.5 md:px-3 md:h-[60px] md:whitespace-nowrap md:border-b md:border-gray-100",
		props.autoWidth ? "md:w-px" : "",
		props.popper ? "md:truncate md:max-w-[200px]" : "",
	].join(" "),
	lg: [
		"lg:table-cell lg:py-3.5 lg:px-3 lg:h-[60px] lg:whitespace-nowrap lg:border-b lg:border-gray-100",
		props.autoWidth ? "lg:w-px" : "",
		props.popper ? "lg:truncate lg:max-w-[200px]" : "",
	].join(" "),
	xl: [
		"xl:table-cell xl:py-3.5 xl:px-3 xl:h-[60px] xl:whitespace-nowrap xl:border-b xl:border-gray-100",
		props.autoWidth ? "xl:w-px" : "",
		props.popper ? "xl:truncate xl:max-w-[200px]" : "",
	].join(" "),
}

const headerConfig = {
	default: "font-semibold text-gray-800 mb-2",
	md: "md:hidden",
	lg: "lg:hidden",
	xl: "xl:hidden",
}
</script>
