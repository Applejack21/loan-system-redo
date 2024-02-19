<template>
	<slot name="button" :state="state" v-if="button">
		<AppButton @click="state.visible = true" :size="buttonSize" :colour="buttonColour">
			<slot name="open-text">Open Form</slot>
			<template #iconRight>
				<slot name="open-icon">
					<PlusIcon />
				</slot>
			</template>
		</AppButton>
	</slot>
	<DialogModal :max-width="maxWidth" :show="state.visible" @close="closeModal">
		<template #title>
			<h1 class="text-slate-700 text-xl">
				<slot name="title" />
			</h1>
		</template>
		<template #content>
			<slot name="content" form="form" />
		</template>

		<template #footer>
			<slot names="buttons">
				<slot name="extraButtons" />

				<div class="flex flex-col space-y-1">
					<AppButton colour="red" @click="closeModal" :disabled="form.processing">
						<template #iconRight>
							<XMarkIcon />
						</template>
						Nevermind
					</AppButton>
					<span class="text-xs font-semibold text-center">
						Esc to close
					</span>
				</div>

				<div class="flex flex-col space-y-1">
					<AppButton @click.prevent="submit" colour="secondary" :disabled="form.processing || !form.isDirty"
						:loading="form.processing">
						<template #iconRight>

							<CheckIcon />
						</template>
						<slot name="subit-text">
							Save Changes
						</slot>
					</AppButton>
					<span class="text-xs font-semibold text-center">
						Ctrl + enter to submit
					</span>
				</div>
			</slot>
		</template>
	</DialogModal>
</template>

<script setup>
import { watch, onMounted, onBeforeUnmount } from "vue";
import { PlusIcon, CheckIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import DialogModal from "@/Jetstream/DialogModal.vue";
import { useModal } from "@/modules/modals.js";

const props = defineProps({
	// Inertia form object
	form: {
		type: Object,
		required: true
	},
	// Inertia route
	urlRoute: {
		type: String,
		required: true
	},
	// Form submit method
	method: {
		type: String,
		default: "post",
		validator: (val) => {
			return ['get', 'post', 'put', 'patch', 'delete'].includes(val)
		}
	},
	// Open/close the modal
	toggle: {
		type: Boolean,
		default: false
	},
	// Prevent the form from submitting within the component
	preventSubmit: {
		type: Boolean,
		default: false
	},
	// Options for the inertia submit request
	submitOptions: {
		type: Object,
		required: false
	},
	// Show/hide the open button
	button: {
		type: Boolean,
		default: true
	},
	// Size of modal button
	buttonSize: {
		type: String,
		default: 'md'
	},
	buttonColour: {
		type: String,
		default: 'secondary',
	},
	maxWidth: {
		type: String,
		default: '2xl',
	},
})

const emit = defineEmits(['open', 'close', 'submit', 'success'])

const {
	state,
	openModal,
	closeModal,
} = useModal(emit, props.toggle);

const submit = () => {
	emit('submit')

	props.form[props.method](props.urlRoute, {
		...props.form,
		onSuccess: () => {
			emit('success', state);
		},
		preserveState: (page) => Object.keys(page.props.errors).length,
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

// register the keyboard shortcut inputs
onMounted(() => {
	window.addEventListener('keyup', handleKeyPress);
});

onBeforeUnmount(() => {
	window.removeEventListener('keyup', handleKeyPress);
});

// if the user uses the keyboard shortcuts then submit or close the modal
// additionally only submit the form if it isnt being submitted already and is dirty
const handleKeyPress = (event) => {
	if (!props.form.processing && props.form.isDirty && event.keyCode === 13 && event.ctrlKey) {
		submit();
	} else if (event.keyCode === 27) {
		closeModal();
	}
};
</script>
