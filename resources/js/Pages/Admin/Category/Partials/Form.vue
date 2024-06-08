<template>
	<Wrapper :form="form" :showIndicator="true">

		<div class="bg-white gap-4 sm:p-6 grid grid-cols-1">

			<TextInput label="Name" v-model="form.name" id="name" name="name" placeholder="Name" required />
			<TextInput label="Slug (for URL)" v-model="form.slug" id="slug" name="slug" placeholder="Slug" required />
			<div>
				<Label>
					Image
				</Label>
				<Dashboard :uppy="uppy" :showProgressDetails="true" :props="{
		doneButtonHandler: null,
		showRemoveButtonAfterComplete: true,
		hideProgressAfterFinish: true,
		height: 350,
		hideUploadButton: true,
	}" />
			</div>
		</div>
	</Wrapper>
</template>

<script setup>
import { Dashboard } from '@uppy/vue'
import Uppy from '@uppy/core'
import { Wrapper, TextInput, Label } from "@/Components/Form";
import { addExistingFilesToUppy } from '@/modules/uppyHelpers';

const props = defineProps({
	form: {
		type: Object,
		required: true
	},
});

const uppy = new Uppy({
	id: 'uppy',
	autoProceed: true,
	debug: false,
	restrictions: {
		maxNumberOfFiles: 1,
		allowedFileTypes: ['image/*'],
	},
});

// if there already is an image for the category
if (props.form.image) {
	addExistingFilesToUppy([props.form.image], uppy);
}

uppy.on("complete", (result) => {
	props.form.image = result.successful[0];
});

uppy.on('file-removed', (file, reason) => {
	// reset form image
	props.form.image = [];
})
</script>
