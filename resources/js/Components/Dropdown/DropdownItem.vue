<template>
    <div class="px-1 py-1">
        <component :is="is"
            class="text-gray-800 w-full text-left group flex items-center rounded-md px-2 py-2 transition-colors hover:bg-accent-light hover:cursor-pointer">
            <span class="w-1em h-1em mr-3" v-if="$slots.iconLeft">
                <slot name="iconLeft" />
            </span>
            <div class="flex-1 text-gray-800" :class="[success ? '!text-green-600' : '', danger ? '!text-red-600' : '']">
                <slot />
            </div>
            <span class="w-1em h-1em ml-3" v-if="$slots.iconRight">
                <slot name="iconRight" />
            </span>
        </component>
    </div>
</template>

<script setup>
defineProps({
    is: {
        type: String,
        default: "div"
    },
    danger: {
        type: Boolean,
        default: false,
    },
    success: {
        type: Boolean,
        default: false,
    }
})
</script>
