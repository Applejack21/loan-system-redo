<template>
    <label class="block mb-2.5">
        <span class="block mb-2.5">
            <slot />
			<span v-if="required" class="text-red-600 -ml-1">
				*
			</span>
		</span>
    </label>
</template>

<script setup>
defineProps({
	required: Boolean,
})
</script>
