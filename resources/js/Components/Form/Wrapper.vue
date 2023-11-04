<template>
	<div class="space-y-4">
		<p class="text-red-600" v-if="showIndicator">* Indicates
			required</p>
		<div class="space-y-4">
			<Errors v-if="form" :form="form" class="my-2" />
			<slot />
		</div>
	</div>
</template>

<script setup>
import Errors from './Errors.vue';

defineProps({
	showIndicator: Boolean,
	form: Object,
})
</script>
