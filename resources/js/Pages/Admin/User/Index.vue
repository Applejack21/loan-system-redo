<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #subTitle>
						View and manage users.
					</template>
					<template #button>
						<FormModal :form="form" :urlRoute="route('admin.user.store')"
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
				</CardHeader>
				<div
					class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 items-center mb-2 justify-center lg:justify-start">
					<!-- Search -->
					<TextInput type="search" placeholder="Search users..." v-model="filters.search">
						<template #iconLeft>
							<MagnifyingGlassIcon />
						</template>
					</TextInput>

					<!-- Type -->
					<div class="flex justify-center sm:justify-start">
						<Dropdown align="left">
							<template #trigger>
								<AppButton colour="dropdown">
									{{ filterState.typeFilter?.name || 'All Types' }}
									<template #iconRight>
										<ChevronDownIcon />
									</template>
								</AppButton>
							</template>
							<template #content>
								<span class="px-4 py-2">
									Select type
								</span>
								<template v-for="userType in filterState.typeFilters">
									<DropdownLink as="button"
										@click="[filterState.typeFilter = userType, filters.type = userType.value]">
										{{ userType.name }}
									</DropdownLink>
								</template>
							</template>
						</Dropdown>
					</div>
				</div>
				<Table :rows="users.data" :columns="tableColumns" :paginationLinks="users.meta" :only="['users']">
					<template #td-name="{ row }">
						<UserPreview size="sm" :user="row" />
					</template>
					<template #td-actions="{ row, index }">
						<DropdownMenu :links="dropdownLinks(row)">
							<template #extra>
								<DropdownItem @click="openEdit(row, createEditForm(row))" v-if="row.id !== authUser.id">
									Edit
								</DropdownItem>
								<DropdownItem :danger="true" @click="openDelete(row)" v-if="row.id !== authUser.id">
									Delete
								</DropdownItem>
							</template>

							<template #extraMobile>
								<AppButton colour="secondary" @click="openEdit(row, createEditForm(row))"
									class="justify-center" v-if="row.id !== authUser.id">
									Edit
								</AppButton>
								<AppButton colour="red" @click="openDelete(row)" class="justify-center"
									v-if="row.id !== authUser.id">
									Delete
								</AppButton>
							</template>
						</DropdownMenu>
					</template>
				</Table>
			</CardBody>
		</Card>

		<FormModal v-if="state.selectedItem" :form="state.editForm" method="patch" :toggle="state.showEdit"
			:urlRoute="route('admin.user.update', state.selectedItem.id)"
			:submitOptions="state.editForm && { preserveScroll: true }" :button="false" @close="closeEdit()"
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
import { computed, reactive, watch } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { MagnifyingGlassIcon, ChevronDownIcon } from '@heroicons/vue/24/outline';
import throttle from "lodash/throttle";
import Dropdown from '@/Jetstream/Dropdown.vue';
import DropdownLink from '@/Jetstream/DropdownLink.vue'
import { Card, CardBody, CardHeader } from '@/Components/Card';
import { Table } from '@/Components/Table';
import { FormModal, ConfirmDelete, UserPreview } from '@/Components';
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
} = useListPage("admin.user.destroy");

const tableColumns = {
	name: {
		name: 'Name',
		popper: true,
	},
	email: {
		name: 'Email',
		popper: true,
	},
	type: {
		name: 'Type',
	},
	phone_number: {
		name: 'Phone Number',
	},
	actions: {
		name: '',
		autoWidth: true,
		border: false,
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
		name: 'All',
		value: 'all',
	},
	{
		name: 'Admins',
		value: 'admin',
	},
	{
		name: 'Customers',
		value: 'customer',
	},
];

const filterState = reactive({
	typeFilters: types,
	typeFilter: null,
});

filterState.typeFilter = filterState.typeFilters.find(filter => filter.value === props.filters.type) || filterState.typeFilters?.[0] || null;

const filters = reactive({
	search: props.filters?.search,
	type: props.filters.type ?? filterState.typeFilter.name,
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
			page: undefined, // Reset page filter so we start back on page 1.
		},
		only: ['users'],
	});
}, 300));

const dropdownLinks = (user) => {
	return [
		{ name: 'View', href: route('admin.user.show', user.id) },
	]
}

const authUser = computed(() => usePage().props.auth.user);
</script>
