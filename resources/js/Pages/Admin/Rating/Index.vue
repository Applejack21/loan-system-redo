<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #subTitle>
						View and manage ratings.
					</template>
				</CardHeader>
				<div
					class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 items-center mb-2 justify-center lg:justify-start">
					<TextInput type="search" placeholder="Search ratings..." v-model="filters.search">
						<template #iconLeft>
							<MagnifyingGlassIcon />
						</template>
					</TextInput>
				</div>
				<Table :rows="ratings.data" :columns="tableColumns" :paginationLinks="ratings.meta" :only="['ratings']">

					<template #td-created_by="{ cell }">
						<Link :href="route('admin.user.show', cell.id)">
						<UserPreview :user="cell" size="sm" />
						</Link>
					</template>

					<template #td-equipment="{ cell }">
						<Link :href="route('admin.equipment.show', cell.slug)">
						{{ cell.name }}
						</Link>
					</template>

					<template #td-actions="{ row, index }">
						<DropdownMenu>
							<template #extra>
								<DropdownItem :danger="true" @click="openDelete(row)">
									Delete
								</DropdownItem>
							</template>

							<template #extraMobile>
								<AppButton colour="red" @click="openDelete(row)" class="justify-center">
									Delete
								</AppButton>
							</template>
						</DropdownMenu>
					</template>
				</Table>
			</CardBody>
		</Card>

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
import { ConfirmDelete, UserPreview } from '@/Components';
import { TextInput } from '@/Components/Form';
import { DropdownMenu, DropdownItem } from '@/Components/Dropdown';
import { useListPage } from "@/modules/listPage.js";

const props = defineProps({
	title: String,
	ratings: Object,
	filters: Object,
	breadcrumbs: Object,
});

const {
	state,
	openDelete,
	closeDelete,
	deleteAction,
} = useListPage("admin.rating.destroy");

const tableColumns = {
	created_by: {
		name: 'Created By',
	},
	equipment: {
		name: 'Equipment',
	},
	rating: {
		name: 'Rating',
	},
	actions: {
		name: '',
		autoWidth: true,
		border: false,
	},
};

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
			page: undefined, // Reset page filter so we start back on page 1.
		},
		only: ['ratings'],
	});
}, 300));
</script>
