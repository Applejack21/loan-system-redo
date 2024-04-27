<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #button>
						<FormModal :form="form" :urlRoute="route('admin.location.store')"
							@success="(modalState) => modalState.visible = false">
							<template #open-text>Add location</template>
							<template #title>Add a new location</template>
							<template #content>
								<Form :form="form" />
							</template>
							<template #submit-text>
								Add location
							</template>
						</FormModal>
					</template>
					<template #subTitle>
						View and manage locations.
					</template>
				</CardHeader>
				<div
					class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 items-center mb-2 justify-center lg:justify-start">
					<TextInput type="search" placeholder="Search locations..." v-model="filters.search">
						<template #iconLeft>
							<MagnifyingGlassIcon />
						</template>
					</TextInput>
				</div>
				<Table :rows="locations.data" :columns="tableColumns" :paginationLinks="locations.meta"
					:only="['locations']" :border="true">
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

		<FormModal v-if="state.selectedItem" :form="state.editForm" method="patch" :toggle="state.showEdit"
			:urlRoute="route('admin.location.update', state.selectedItem.id)"
			:submitOptions="state.editForm && { preserveScroll: true }" :button="false" @close="closeEdit()"
			@success="closeEdit()">
			<template #title>
				{{ `Updating "${state.editForm.name}"` }}
			</template>
			<template #content>
				<Form :form="state.editForm" />
			</template>
			<template #submit-text>
				Update location
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
} = useListPage("admin.location.destroy");

const tableColumns = {
	name: {
		name: 'Name',
	},
	room_code: {
		name: 'Room Code',
	},
	actions: {
		name: '',
		autoWidth: true,
		border: false,
	},
};

const form = useForm({
	name: '',
	room_code: '',
});

const createEditForm = (location) => {
	return useForm({
		name: location.name,
		room_code: location.room_code,
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
		only: ['locations'],
	});
}, 300));

const dropdownLinks = (location) => {
	return [
		{ name: 'View', href: route('admin.location.show', location.id) },
	]
}
</script>
