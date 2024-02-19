<template>
	<div>
		<slot name="button" :toggle-modal="toggleModal" v-if="button">
			<AppButton :size="size" :colour="colour" @click.prevent="openModal()" :loading="state.visible">
				Delete
				<template #iconRight>
					<TrashIcon />
				</template>
			</AppButton>
		</slot>

		<ConfirmationModal :show="state.visible" @close="closeModal()" max-width="md">
			<template #title>
				<h3 class="text-header-small font-bold">
					<slot name="title">
						Confirm delete
					</slot>
				</h3>
			</template>

			<template #content>
				<slot name="content">
					Are you sure you want to delete this record?
				</slot>
			</template>

			<template #footer>
				<div class="flex flex-wrap w-full justify-between">
					<AppButton colour="secondary" @click="closeModal()" :disabled="state.processing">
						<slot name="cancelText">
							Nevermind
						</slot>
						<template #iconRight>

							<XMarkIcon />
						</template>
					</AppButton>

					<AppButton @click="submit()" :loading="state.processing" :disabled="state.processing" colour="red">
						<slot name="confirmText">
							Delete
						</slot>
						<template #iconRight>

							<CheckIcon />
						</template>
					</AppButton>
				</div>
			</template>
		</ConfirmationModal>
	</div>
</template>

<script setup>
import { watch } from "vue";
import { router } from '@inertiajs/vue3';
import ConfirmationModal from "@/Jetstream/ConfirmationModal.vue";
import { CheckIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import { useModal } from "@/modules/modals.js";

const props = defineProps({
	// Route action
	action: String,
	// Size of the button
	size: {
		type: String,
		default: "md"
	},
	// Colour of the button
	colour: {
		type: String,
		default: 'red',
	},
	// Open/close the modal
	toggle: {
		type: Boolean,
		default: false
	},
	// Options for the inertia delete request
	submitOptions: {
		type: Object,
		required: false
	},
	// Show/hide the open button
	button: {
		type: Boolean,
		default: true
	}
});

const emit = defineEmits(['open', 'close', 'submit', 'success'])

const {
	state,
	openModal,
	closeModal
} = useModal(emit, props.toggle);

const submit = () => {
	state.processing = true;

	router.delete(props.action, {
		onSuccess() {
			emit('success', state);

			closeModal()
			state.processing = false
		},
		...props.submitOptions
	})
}

watch(() => props.toggle, (val) => {
	if (val) {
		openModal()
	} else {
		closeModal()
	}
})
</script>
