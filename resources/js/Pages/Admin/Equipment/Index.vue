<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #subTitle>
						View and manage equipments.
					</template>
					<template #button>
						<FormModal :form="form" :urlRoute="route('admin.equipment.store')"
							@success="(modalState) => modalState.visible = false" maxWidth="4xl">
							<template #open-text>
								Add equipment
							</template>
							<template #title>
								Add a new equipment
							</template>
							<template #content>
								<Form :form="form" :categories="categories" :locations="locations" />
							</template>
							<template #submit-text>
								Add equipment
							</template>
						</FormModal>
					</template>
				</CardHeader>
				<div
					class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 items-center mb-2 justify-center lg:justify-start">
					<TextInput type="search" placeholder="Search equipments..." v-model="filters.search">
						<template #iconLeft>
							<MagnifyingGlassIcon />
						</template>
					</TextInput>
				</div>
				<Table :rows="equipments.data" :columns="tableColumns" :paginationLinks="equipments.meta"
					:only="['equipments']" :border="true">

					<template #td-amount_on_loan="{ cell }">
						{{ cell }}
					</template>

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
			:urlRoute="route('admin.equipment.update', state.selectedItem.id)" :submitOptions="state.editForm"
			:button="false" @close="closeEdit()" @success="closeEdit()" maxWidth="4xl">
			<template #title>
				{{ `Updating "${state.editForm.name}"` }}
			</template>
			<template #content>
				<Form :form="state.editForm" :categories="categories" :locations="locations" />
			</template>
			<template #submit-text>
				Update equipment
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
	equipments: Object,
	categories: Object,
	locations: Object,
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
} = useListPage("admin.equipment.destroy");

const tableColumns = {
	name: {
		name: 'Name',
		popper: true,
	},
	slug: {
		name: 'Slug',
		popper: true,
	},
	code: {
		name: 'Code',
		popper: true,
	},
	amount: {
		name: 'Amount',
		hiddenUntil: 'lg',
	},
	amount_in_stock: {
		name: 'Amount in stock',
	},
	amount_on_loan: {
		name: 'Amount on loan',
	},
	actions: {
		name: '',
		autoWidth: '',
		border: false,
	},
};

const form = useForm({
	location_id: null,
	name: '',
	slug: '',
	code: '',
	description: '',
	price: 0,
	details: [],
	amount: 0,
	categories: [],
	images: [],
});

const createEditForm = (equipment) => {
	const selectedCategories = equipment.categories.map(category => {
		return category.id;
	});

	return useForm({
		location_id: equipment?.location?.id,
		name: equipment.name,
		slug: equipment.slug,
		code: equipment.code,
		description: equipment.description,
		price: equipment.price,
		details: equipment.details,
		amount: equipment.amount,
		categories: selectedCategories,
		images: equipment.images,
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
		only: ['equipments'],
	});
}, 300));

const dropdownLinks = (equipment) => {
	return [
		{ name: 'View', href: route('admin.equipment.show', equipment.id) },
	]
}
</script>
