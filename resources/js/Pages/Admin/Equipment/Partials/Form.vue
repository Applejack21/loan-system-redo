<template>
	<Wrapper :form="form" :showIndicator="true">

		<div class="bg-white grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">

			<TextInput label="Name" v-model="form.name" id="name" name="name" placeholder="Name" required />

			<TextInput label="Slug" v-model="form.slug" id="slug" name="slug" placeholder="Slug" required
				popperText="Slug for URL." />

			<TextInput label="Code" v-model="form.code" id="code" name="code" placeholder="Code"
				popperText="If the equipment has an unique identifier." />

			<TextInput type="number" step="0.01" min="0" v-model="form.price" id="price" label="Price" name="price"
				placeholder="Price" popperText="If loanee damages the equipment, how much they owe you." required />

			<TextInput type="number" step="1" min="0" v-model="form.amount" id="amount" label="Amount" name="amount"
				placeholder="Amount" popperText="How much of this equipment you have available." required />

			<div class="lg:col-span-1">
				<Label for="location" required>
					Location
				</Label>
				<Multiselect id="location" v-model="form.location_id" :closeOnSelect="true" :searchable="true"
					:options="locations" placeholder="Location..." valueProp="id" label="name" :canDeselect="false"
					:canClear="false" />
			</div>

			<div class="lg:col-span-3">
				<Label for="categories">
					Categories
				</Label>
				<Multiselect id="categories" v-model="form.categories" :closeOnSelect="false" :searchable="true"
					:options="categories" placeholder="Categories..." valueProp="id" trackBy="name" label="name"
					mode="tags" />
			</div>

			<div class="md:col-span-2 lg:col-span-3">
				<Label for="description">
					Description
				</Label>
				<Editor v-model="form.description" placeholder="Add a description..." id="description"
					name="description" :init="options" :api-key="apiKey" />
			</div>

			<Repeater :list="form.details" key="id" @add-item="addItem" :min="0" :reorder="true" reorderSize="sm"
				removeSize="sm" addSize="sm" rowClasses="grid grid-cols-1 sm:grid-cols-2 gap-y-5 gap-x-2">
				<template #item="{ element, index }">

					<div class="col-span-1 sm:col-span-auto">
						<TextInput v-model="element.name" :label="index === 0 ? 'Detail name' : ''"
							placeholder="Detail name" />
					</div>

					<div class="col-span-1 sm:col-span-auto">
						<TextInput v-model="element.value" :label="index === 0 ? 'Detail value' : ''"
							placeholder="Detail value" />
					</div>
				</template>
				<template #no-items>
					<span class="text-md text-red-600">
						There are no extra details about this equipment. Click the button below to add one.
					</span>
				</template>
				<template #disclosure>
					<AppButton size="sm" colour="secondary" @click="showExplanation = !showExplanation">
						<template #iconRight>
							<ChevronDownIcon
								:class="showExplanation && 'rotate-180 transform transition ease-out duration-500' || 'transition ease-in duration-250'" />
						</template>
						How does this work?
					</AppButton>
				</template>
				<template #explanation v-if="showExplanation">
					<span class="text-gray-600">
						This will add a list on the equipment view page with the extra details you add here. You can
						also change it's order by using the icon on the left to drag the details higher/lower in the
						list.
						For example: You could include the equipment's brand, or the ISBN of a book.
					</span>
				</template>
			</Repeater>

			<div class="md:col-span-2 lg:col-span-3 mt-2.5">
				<Label popperText="Images will be displayed in a slider. Starting with the first image uploaded.">
					Images
				</Label>
				<Dashboard :uppy="uppy" :showProgressDetails="true" :props="uppyProps" />
			</div>
		</div>
	</Wrapper>
</template>

<script setup>
import { ref } from 'vue';
import { ChevronDownIcon } from "@heroicons/vue/24/solid";
import { Dashboard } from '@uppy/vue'
import Uppy from '@uppy/core'
import Editor from "@tinymce/tinymce-vue";
import Multiselect from '@vueform/multiselect';
import { Wrapper, TextInput, Label, Repeater } from "@/Components/Form";
import { tinymceData } from "@/modules/tinymceData.js";
import { addExistingFilesToUppy } from '@/modules/uppyHelpers';

const {
	apiKey,
	options,
} = tinymceData(500);

const props = defineProps({
	form: {
		type: Object,
		required: true
	},
	categories: {
		type: Object,
		required: true,
	},
	locations: {
		type: Object,
		required: true,
	}
});

const blankItem = () => {
	return {
		name: '',
		value: '',
	}
}

const addItem = () => {
	props.form.details.push(blankItem())
}

const showExplanation = ref(false);

const uppy = new Uppy({
	id: 'uppy',
	autoProceed: true,
	debug: false,
	restrictions: {
		allowedFileTypes: ['image/*'],
	},
});

// if there already are images for the equipment
if (props.form.images) {
	addExistingFilesToUppy(props.form.images, uppy);
}

uppy.on("complete", (result) => {
	props.form.images = result.successful;
});

uppy.on('file-removed', (file, reason) => {
	const fileId = file.id;
	const index = props.form.images.findIndex(image => image.id === fileId);

	// remove image from images
	props.form.images.splice(index, 1);
})

const uppyProps = ref({
	doneButtonHandler: null,
	showRemoveButtonAfterComplete: true,
	hideProgressAfterFinish: true,
	height: 350,
	hideUploadButton: true,
});
</script>
