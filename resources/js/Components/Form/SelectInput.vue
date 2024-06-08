<template>
	<Label v-if="label">
		{{ label }}
	</Label>
	<Dropdown align="left">
		<template #trigger>
			<template v-if="button">
				<AppButton :colour="buttonColour" :size="buttonSize">
					<slot name="selectedItem" :item="selectedItem" />
					<template #iconRight>
						<ChevronDownIcon />
					</template>
				</AppButton>
			</template>
			<template v-else>
				<slot name="trigger">
					<slot name="selectedItem" :item="selectedItem" />
				</slot>
			</template>
		</template>
		<template #content>
			<slot name="dropdownText" />

			<div class="p-2 m-2 rounded-md hover:cursor-pointer" :class="listClasses" v-for="item in items"
				@click="[selectedItem = item]">
				<slot name="item" :item="item" />
			</div>
		</template>
	</Dropdown>
</template>

<script setup>
import { ref, watch } from 'vue';
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import Dropdown from '@/Jetstream/Dropdown.vue';
import { Label } from '@/Components/Form';

const props = defineProps({
	button: {
		type: Boolean,
		default: true,
	},
	buttonColour: {
		type: String,
		default: 'dropdown',
	},
	items: Object,
	label: String,
	modelValue: [String, Object],
	listClasses: String,
	returnProperty: String,
	buttonSize: {
		type: String,
		default: 'md',
	},
});

const emit = defineEmits(['update:modelValue'])

const selectedItem = ref(props.modelValue ?? null);

watch(selectedItem, () => {
	if (selectedItem.value !== null) {
		emit('update:modelValue', selectedItem.value[props.returnProperty]);
	}
}, { immediate: true })
</script>
