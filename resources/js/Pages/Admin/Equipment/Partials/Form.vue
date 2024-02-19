<template>
	<Wrapper :form="form" :showIndicator="true">

		<div class="bg-white grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">

			<TextInput label="Name" v-model="form.name" id="name" name="name" required />
			<TextInput label="Code" v-model="form.code" id="code" name="code" />

			<TextInput type="number" v-model="form.price" id="price" label="Price" name="price"
				popperText="If loanee damages the equipment, how much they owe you." />

			<div class="lg:col-span-1">
				<Label for="location" required>
					Location
				</Label>
				<Multiselect id="location" v-model="form.location_id" :close-on-select="true" :searchable="true"
					:options="locations" placeholder="Location..." valueProp="id" label="name" :canDeselect="false"
					:canClear="false" />
			</div>

			<div class="lg:col-span-2">
				<Label for="categories">
					Categories
				</Label>
				<Multiselect id="categories" v-model="form.categories" :close-on-select="false" :searchable="true"
					:options="categories" placeholder="Categories..." valueProp="id" trackBy="name" label="name" mode="tags" />
			</div>

			<div class="md:col-span-2 lg:col-span-3">
				<Label for="description">
					Description
				</Label>
				<Editor v-model="form.description" placeholder="Add a description..." id="description" name="description"
					:init="options" :api-key="apiKey" />
			</div>

			<Repeater :list="form.details" key="id" @add-item="addItem" :min="0" :reorder="true" reorderSize="sm"
				removeSize="sm" addSize="sm">
				<template #item="{ element, index }">
					<div class="col-span-6 w-full">
						<TextInput v-model="element.name" :label="index === 0 ? 'Detail Name' : ''" />
					</div>
					<div class="col-span-6 w-full">
						<TextInput v-model="element.value" :label="index === 0 ? 'Detail Value' : ''" />
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
		</div>
	</Wrapper>
</template>

<script setup>
import { ref } from 'vue';
import Editor from "@tinymce/tinymce-vue";
import Multiselect from '@vueform/multiselect';
import { ChevronDownIcon } from "@heroicons/vue/24/solid";
import { Wrapper, TextInput, Label, Repeater } from "@/Components/Form";
import { tinymceData } from "@/modules/tinymceData.js";

const {
	apiKey,
	options,
} = tinymceData();

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
</script>
