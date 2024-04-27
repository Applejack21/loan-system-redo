<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #subTitle>
						View and manage categories.
					</template>
					<template #button>
						<FormModal :form="form" :urlRoute="route('admin.category.store')"
							@success="(modalState) => modalState.visible = false">
							<template #open-text>Add category</template>
							<template #title>Add a new category</template>
							<template #content>
								<Form :form="form" />
							</template>
							<template #submit-text>
								Add category
							</template>
						</FormModal>
					</template>
				</CardHeader>
				<div
					class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 items-center mb-2 justify-center lg:justify-start">
					<TextInput type="search" placeholder="Search categories..." v-model="filters.search">
						<template #iconLeft>
							<MagnifyingGlassIcon />
						</template>
					</TextInput>
				</div>
				<Table :rows="categories.data" :columns="tableColumns" :paginationLinks="categories.meta"
					:only="['categories']" :border="true">
					<template #td-actions="{ row, index }">
						<DropdownMenu :links="dropdownLinks(row)">
							<template #extra>
								<DropdownItem @click="openEdit(row, createEditForm(row))">
									Edit
								</DropdownItem>
								<DropdownItem :danger="true" @click="openDelete(row)">
									Delete
								</DropdownItem>
							</template>

							<template #extraMobile>
								<AppButton colour="secondary" @click="openEdit(row, createEditForm(row))"
									class="justify-center">
									Edit
								</AppButton>
								<AppButton colour="red" @click="openDelete(row)" class="justify-center">
									Delete
								</AppButton>
							</template>
						</DropdownMenu>
					</template>
				</Table>
			</CardBody>
		</Card>

		<FormModal v-if="state.selectedItem" :form="state.editForm" :toggle="state.showEdit"
			:urlRoute="route('admin.category.update', state.selectedItem.id)"
			:submitOptions="state.editForm && { preserveScroll: true }" :button="false" @close="closeEdit()"
			@success="closeEdit()">
			<template #title>
				{{ `Updating "${state.editForm.name}"` }}
			</template>
			<template #content>
				<Form :form="state.editForm" />
			</template>
			<template #submit-text>
				Update category
			</template>
		</FormModal>

		<!-- delete modal -->
		<ConfirmDelete :action="deleteAction" :button="false" :toggle="state.showDelete" @close="closeDelete()" />

	</AppLayout>
</template>

<script setup>
import { reactive, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import throttle from "lodash/throttle";
import { Card, CardBody, CardHeader } from '@/Components/Card';
import { Table } from '@/Components/Table';
import { FormModal, ConfirmDelete } from '@/Components';
import { TextInput } from '@/Components/Form';
import { DropdownMenu, DropdownItem } from '@/Components/Dropdown';
import { useListPage } from "@/modules/listPage.js";
import Form from './Partials/Form.vue';

const props = defineProps({
	title: String,
	categories: Object,
	filters: Object,
	breadcrumbs: Object,
});

const {
	state,
	openEdit,
	closeEdit,
	openDelete,
	closeDelete,
	deleteAction,
} = useListPage("admin.category.destroy");

const tableColumns = {
	name: {
		name: 'Name',
	},
	slug: {
		name: 'Slug',
	},
	actions: {
		name: '',
		autoWidth: true,
		border: false,
	},
};

const form = useForm({
	name: '',
	slug: '',
	image: null,
});

const createEditForm = (category) => {
	return useForm({
		name: category.name,
		slug: category.slug,
		image: category.image ?? null,
	});
}

const filters = reactive({
	search: props.filters?.search,
});

watch(filters, throttle(function (value) {
	let data = { ...value };

	Object.keys(value).forEach(function (key, index) {
		if (value[key] === null || value[key] === '') {
			data[key] = undefined;
		}
	});

	router.reload({
		data: {
			...data,
			page: undefined, // reset page to find results on all pages
		},
		only: ['categories'],
	});
}, 300));

const dropdownLinks = (category) => {
	return [
		{ name: 'View', href: route('admin.category.show', category.slug) },
	]
}
</script>
