<template>
	<label class="block mb-2.5" :class="[
		{ 'flex space-x-2 -mb-2.5 items-start': popperText }
	]">
		<span class="block">
			<slot />
			<span v-if="required" class="text-red-600 -ml-1">
				*
			</span>
		</span>
		<PopperWrapper hover arrow placement="top" v-if="popperText">
			<QuestionMarkCircleIcon class="w-5 h-5 fill-primary-light" />
			<template #content>
				<div>
					<span>
						{{ popperText }}
					</span>
				</div>
			</template>
		</PopperWrapper>
	</label>
</template>

<script setup>
import { QuestionMarkCircleIcon } from '@heroicons/vue/24/solid';
import { PopperWrapper } from '@/Components';

defineProps({
	required: Boolean,
	popperText: String,
})
</script>
