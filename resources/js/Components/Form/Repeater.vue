<template>
	<div class="w-full md:w-max">
		<draggable :list="list" :item-key="listKey" tag="transition-group" :component-data="{
			tag: 'ul',
			type: 'transition-group',
			name: 'flip-list',
		}" v-bind="dragOptions" @start="drag = true" @end="drag = false" handle=".handle" v-if="list.length">
			<template #item="{ element, index }">
				<li class="list-group-item flex space-x-6 mb-2">
					<AppButton class="handle cursor-move mt-auto" v-if="reorder" colour="secondary" :size="reorderSize">
						<template #icon>
							<QueueListIcon />
						</template>
					</AppButton>
					<div class="flex-1 grid grid-cols-12 gap-x-2">
						<slot name="item" :element="element" :index="index" />
					</div>
					<AppButton class="mt-auto" :square="true" :disabled="list.length < min + 1"
						@click="removeAt(element, index)" v-if="allowDelete" colour="red" :size="removeSize">
						<template #icon>
							<XMarkIcon />
						</template>
					</AppButton>
				</li>
			</template>
		</draggable>

		<div v-else>
			<slot name="no-items"></slot>
		</div>

		<div class="flex gap-2 items-center mt-5">
			<AppButton @click.prevent="$emit('add-item')" type="button" v-if="allowAdd" colour="primary" :size="addSize">
				Add line
				<template #iconRight>
					<PlusSmallIcon />
				</template>
			</AppButton>
			<slot name="disclosure" />
		</div>
		<div class="w-full md:w-[500px] flex">
			<slot name="explanation" />
		</div>
	</div>
</template>

<script setup>
import { computed } from 'vue'
import { PlusSmallIcon, QueueListIcon, XMarkIcon } from '@heroicons/vue/20/solid'
import draggable from 'vuedraggable';

const props = defineProps({
	list: {
		type: Array,
		default: () => []
	},
	listKey: {
		type: String,
		required: true,
	},
	reorder: Boolean,
	allowAdd: {
		type: Boolean,
		default: true,
	},
	allowDelete: {
		type: Boolean,
		default: true,
	},
	min: {
		type: Number,
		default: 1,
		validator: (val) => val >= 0
	},
	reorderSize: {
		type: String,
		default: 'md',
	},
	removeSize: {
		type: String,
		default: 'md',
	},
	addSize: {
		type: String,
		default: 'md',
	},
})

const emit = defineEmits(['add-item', 'remove-item'])

const dragOptions = computed(() => {
	return {
		animation: 200,
		disabled: false,
		ghostClass: 'opacity-30',
	}
})

const removeAt = (element, index) => {
	emit('remove-item', { item: element, index: index });

	if (props.list.length < props.min + 1) return

	props.list.splice(index, 1)
}
</script>

<style>
.flip-list-move {
	transition: transform 0.5s;
}

.no-move {
	transition: transform 0s;
}

.ghost {
	opacity: 0.5;
	@apply bg-gray-100;
}

.list-group {
	min-height: 20px;
}
</style>
