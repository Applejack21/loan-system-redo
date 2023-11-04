<template>
	<div class="block">
		<Label v-if="label" :for="$attrs.id" :required="required">
			{{ label }}
		</Label>
		<div class="relative block">

			<div class="pointer-events-none absolute inset-y-0 flex items-center w-4 h-4 top-1/2 -translate-y-1/2 left-0 ml-2"
				v-if="$slots.iconLeft">
				<slot name="iconLeft" :inputRef="$refs.input" />
			</div>

			<input ref="input" :class="[
				'block w-full h-[37px] px-[15px] rounded-md focus:outline-0 focus:ring-0',
				'bg-white border border-neutral-200 focus:border-secondary',
				placeholderClasses,
				'disabled:opacity-50',
				{ 'pl-7': $slots.iconLeft },
				{ 'pr-7': $slots.iconRight },
			]" v-bind="$attrs" :type="type" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)"
				@focus="$emit('focused')">

			<div class="pointer-events-none absolute inset-y-0 flex items-center w-4 h-4 top-1/2 -translate-y-1/2 right-0 mr-2"
				v-if="$slots.iconRight">
				<slot name="iconRight" :inputRef="$refs.input" />
			</div>
		</div>
	</div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue'
import { Label } from '@/Components/Form'

const props = defineProps({
	modelValue: [String, Number],
	type: {
		type: String,
		default: 'text'
	},
	label: {
		type: String,
		required: false,
	},
	size: {
		type: String,
		validator: (val) => ['md', 'lg'].includes(val),
		default: 'md'
	},
	placeholderClasses: {
		type: String,
	},
	required: Boolean,
});

const input = ref(null);

onMounted(() => {
	if (input.value.hasAttribute('autofocus')) {
		input.value.focus();
	}
});

defineExpose({ focus: () => input.value.focus() });
defineEmits(['focused'])

const getInputRef = () => input
</script>

<script>
export default {
	inheritAttrs: false,
}
</script>
