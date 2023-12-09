<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardHeader>
				{{ title }}
				<template #button>
					<FormModal :form="form" :route="route('admin.location.store')"
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
			<div class="w-full flex space-x-5 items-center mb-2">
				<TextInput type="search" placeholder="Search locations..." v-model="filters.search">
					<template #iconLeft>
						<MagnifyingGlassIcon />
					</template>
				</TextInput>
			</div>
			<Table :rows="locations.data" :columns="tableColumns" :paginationLinks="locations.meta" :only="['locations']" :border="true">
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
            :route="route('admin.location.update', state.selectedItem.id)"
            :submitOptions="state.editForm && { preserveScroll: true }"
            :button="false" @close="closeEdit()"
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
import { Card, CardHeader } from '@/Components/Card';
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
	openRestore,
	closeRestore,
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
		autoWidth: '',
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
