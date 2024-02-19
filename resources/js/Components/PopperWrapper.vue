<template>
	<Popper v-bind="$attrs">
		<slot />
		<template #content="props">
			<slot name="content" v-bind="props" />
		</template>
	</Popper>
</template>

<script setup>
import Popper from "vue3-popper";

defineProps({
	//
})
</script>
