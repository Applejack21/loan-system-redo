<template>
	<div class="space-y-4">
		<p class="text-red-600" v-if="showIndicator">* Indicates
			required</p>

		<div>
			<Errors v-if="form" :form="form" :showErrorIndexName="showErrorIndexName" />
			<slot />
		</div>
	</div>
</template>

<script setup>
import Errors from './Errors.vue';

defineProps({
	showIndicator: Boolean,
	form: Object,
	showErrorIndexName: {
		type: Boolean,
		default: true,
	}
})
</script>
