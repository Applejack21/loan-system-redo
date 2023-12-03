<template>
	<Wrapper :form="form" :showIndicator="true">

		<div class="-mt-5 bg-white gap-4 sm:p-6 grid grid-cols-2">

			<TextInput label="Name" v-model="form.name" id="name" name="name" required />
			<TextInput label="Slug" v-model="form.slug" id="slug" name="slug" required />

		</div>
	</Wrapper>
</template>

<script setup>
import { Wrapper, TextInput } from "@/Components/Form";

const props = defineProps({
	form: {
		type: Object,
		required: true
	},
});
</script>
