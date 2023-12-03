<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardHeader>
				{{ title }}
				<template #button>
					<FormModal :form="form" :route="route('admin.user.store')"
						@success="(modalState) => modalState.visible = false">
						<template #open-text>Add user</template>
						<template #title>Add a new user</template>
						<template #content>
							<Form :form="form" />
						</template>
						<template #submit-text>
							Add user
						</template>
					</FormModal>
				</template>
				<template #subTitle>
					View and manage users.
				</template>
			</CardHeader>
			<div class="w-full flex space-x-5 items-center mb-2">
				<TextInput type="search" placeholder="Search users..." v-model="filters.search">
					<template #iconLeft>
						<MagnifyingGlassIcon />
					</template>
				</TextInput>
				<SelectInput v-model="filters.type" :items="types" buttonColour="secondary" returnProperty="value"
					listClasses="hover:bg-gray-200">
					<template #selectedItem="{ item }">
						{{ item ? item.name : 'Filter by type' }}
					</template>
					<template #item="{ item }">
						{{ item.name }}
					</template>
				</SelectInput>
			</div>
			<Table :rows="users.data" :columns="tableColumns" :paginationLinks="users.meta" :only="['users']" :border="true">
				<template #td-actions="{ row, index }">
					<DropdownMenu :links="dropdownLinks(row)" :openVertical="index == 0 ? 'bottom' : 'top'">
						<template #extra>
							<DropdownItem @click="openEdit(row, createEditForm(row))">
								Edit
							</DropdownItem>
							<DropdownItem :danger="true" @click="openDelete(row)">
								Delete
							</DropdownItem>
						</template>
					</DropdownMenu>
				</template>
			</Table>
		</Card>

		<FormModal v-if="state.selectedItem"
            :form="state.editForm" method="patch"
            :toggle="state.showEdit"
            :route="route('admin.user.update', state.selectedItem.id)"
            :submitOptions="state.editForm && { preserveScroll: true }"
            :button="false" @close="closeEdit()"
            @success="closeEdit()">
            <template #title>
                {{ `Updating "${state.editForm.name}"` }}
            </template>
            <template #content>
                <Form :form="state.editForm" :newUser="false" />
            </template>
            <template #submit-text>
                Update user
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
import { Card, CardHeader } from '@/Components/Card';
import { Table } from '@/Components/Table';
import { FormModal, ConfirmDelete } from '@/Components';
import { TextInput, SelectInput } from '@/Components/Form';
import { DropdownMenu, DropdownItem } from '@/Components/Dropdown';
import { useListPage } from "@/modules/listPage.js";
import Form from './Partials/Form.vue';

const props = defineProps({
	title: String,
	users: Object,
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
	openRestore,
	closeRestore,
} = useListPage("admin.user.destroy");

const tableColumns = {
	name: {
		name: 'Name',
	},
	email: {
		name: 'Email',
	},
	type: {
		name: 'Type',
	},
	phone_number: {
		name: 'Phone Number',
	},
	actions: {
		name: '',
		autoWidth: '',
	},
};

const form = useForm({
	name: '',
	email: '',
	type: '',
	phone_number: '',
	address: {
		line_one: '',
		line_two: '',
		line_three: '',
		postcode: '',
		city: '',
	},
	password: '',
	password_confirmation: '',
});

const createEditForm = (user) => {
	return useForm({
		name: user.name,
		email: user.email,
		type: user.type,
		phone_number: user.phone_number,
		address: {
			line_one: user.address?.line_one,
			line_two: user.address?.line_two,
			line_three: user.address?.line_three,
			postcode: user.address?.postcode,
			city: user.address?.city,
		},
	});
}

const types = [
	{
		name: 'All types',
		value: 'all',
	},
	{
		name: 'Admin type',
		value: 'admin',
	},
	{
		name: 'Customer type',
		value: 'customer',
	},
];

const filters = reactive({
	search: props.filters?.search,
	type: props.filters?.type ? types.find(type => type.value === props.filters.type) : null,
});

watch(filters, throttle(function (value) {
	let data = { ...value };

	Object.keys(value).forEach(function (key, index) {
		if (value[key] === null || value[key] === '') {
			data[key] = undefined;
		}
	});

	router.reload({
		data: { ...data },
		only: ['users'],
	});
}, 300));

const dropdownLinks = (user) => {
	return [
		{ name: 'View', href: route('admin.user.show', user.id) },
	]
}
</script>
