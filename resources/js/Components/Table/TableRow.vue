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
    default: "px-5 py-6 border space-y-4 group transition-colors border-gray-500",
    md: "md:table-row bg-white md:p-0 md:rounded-0 md:border-0 md:space-y-0 md:space-y-0 odd:bg-neutral-100",
    lg: "lg:table-row bg-white lg:p-0 lg:rounded-0 lg:border-0 lg:space-y-0 lg:space-y-0 odd:bg-neutral-100",
    xl: "xl:table-row bg-white xl:p-0 xl:rounded-0 xl:border-0 xl:space-y-0 xl:space-y-0 odd:bg-neutral-100",
}
</script>
