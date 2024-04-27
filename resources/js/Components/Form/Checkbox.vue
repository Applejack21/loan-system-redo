<template>
	<div class="block">
		<Label v-if="label" :for="$attrs.id" :required="required" :popperText="popperText">
			{{ label }}
		</Label>
		<div class="relative block">

			<div class="pointer-events-none absolute inset-y-0 flex items-center w-4 h-4 top-1/2 -translate-y-1/2 left-0 ml-2"
				v-if="$slots.iconLeft">
				<slot name="iconLeft" :inputRef="$refs.input" />
			</div>

			<input v-model="proxyChecked" type="checkbox" :value="value" v-bind="$attrs" :class="[
			'focus:ring focus:ring-secondary focus:ring-opacity-50 focus:border-secondary',
			'disabled:opacity-50',
			'rounded-md border-grey-100',
			getSizeClasses(size),
			{ 'border-secondary-light !bg-secondary-light': proxyChecked }
		]">

			<div class="pointer-events-none absolute inset-y-0 flex items-center w-4 h-4 top-1/2 -translate-y-1/2 right-0 mr-2"
				v-if="$slots.iconRight">
				<slot name="iconRight" :inputRef="$refs.input" />
			</div>
		</div>
	</div>
</template>

<script setup>
import { computed } from 'vue';
import { Label } from '@/Components/Form'

const emit = defineEmits(['update:checked']);

const props = defineProps({
	checked: {
		type: [Array, Boolean],
		default: false,
	},
	value: {
		type: String,
		default: null,
	},
	label: {
		type: String,
		required: false,
	},
	size: {
		type: String,
		validator: (val) => ['sm', 'md', 'lg'].includes(val),
		default: 'md'
	},
	placeholderClasses: {
		type: String,
	},
	required: Boolean,
	popperText: String,
});

const proxyChecked = computed({
	get() {
		return props.checked;
	},

	set(val) {
		emit('update:checked', val);
	},
})

const getSizeClasses = (size) => {
	switch (size) {
		case 'sm': return 'w-5 h-5'
		case 'md': return 'w-7 h-7'
		case 'lg': return 'w-9 h-9'
	}
}
</script>

<script>
export default {
	inheritAttrs: false,
}
</script>
