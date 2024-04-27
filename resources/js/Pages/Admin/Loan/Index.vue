<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #subTitle>
						View and manage loans.
					</template>
					<template #button>
						<FormModal :form="form" :urlRoute="route('admin.loan.store')"
							@success="(modalState) => modalState.visible = false" maxWidth="4xl">
							<template #open-text>
								Add loan
							</template>
							<template #title>
								Add a new loan
							</template>
							<template #content>
								<Form :form="form" />
							</template>
							<template #submit-text>
								Add loan
							</template>
						</FormModal>
					</template>
				</CardHeader>
				<div
					class="w-full grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 items-center mb-2 justify-center lg:justify-start">
					<TextInput type="search" placeholder="Search loans..." v-model="filters.search">
						<template #iconLeft>
							<MagnifyingGlassIcon />
						</template>
					</TextInput>
					<div class="flex justify-center sm:justify-start">
						<SelectInput v-model="filters.status" :items="statuses" buttonColour="secondary"
							returnProperty="value" listClasses="hover:bg-gray-200">
							<template #selectedItem="{ item }">
								{{ item ? item.name : 'Filter by status' }}
							</template>
							<template #item="{ item }">
								{{ item.name }}
							</template>
						</SelectInput>
					</div>
				</div>
				<Table :rows="loans.data" :columns="tableColumns" :paginationLinks="loans.meta" :only="['loans']"
					:border="true">

					<template #td-loanee="{ cell }">
						<Link :href="route('admin.user.show', cell.id)">
						<UserPreview :user="cell" size="sm" />
						</Link>
					</template>

					<template #td-approved_by="{ cell }">
						<template v-if="cell">
							<UserPreview :user="cell" size="sm" />
						</template>
						<template v-else>
							<XMarkIcon class="w-5 h-5 text-red-600" />
						</template>
					</template>

					<template #td-status="{ cell }">
						<Pill :colour="getPillColour(cell)">
							{{ cell }}
						</Pill>
					</template>

					<template #td-actions="{ row, index }">
						<DropdownMenu :links="dropdownLinks(row)" />
					</template>
				</Table>
			</CardBody>
		</Card>
	</AppLayout>
</template>

<script setup>
import { reactive, watch, provide } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { MagnifyingGlassIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import throttle from "lodash/throttle";
import { Card, CardBody, CardHeader } from '@/Components/Card';
import { Table } from '@/Components/Table';
import { FormModal, UserPreview, Pill } from '@/Components';
import { TextInput, SelectInput } from '@/Components/Form';
import { DropdownMenu } from '@/Components/Dropdown';
import Form from './Partials/Form.vue';
import dayjs from 'dayjs';

const props = defineProps({
	title: String,
	loans: Object,
	equipments: Object,
	statuses: Array,
	users: Object,
	filters: Object,
	breadcrumbs: Object,
});

// provide data for forms
provide('equipments', props.equipments);
provide('statuses', props.statuses);
provide('users', props.users);

const tableColumns = {
	loanee: {
		name: 'Loanee',
		popper: true,
	},
	status: {
		name: 'Status',
	},
	reference: {
		name: 'Reference',
	},
	equipments_count: {
		name: 'Equipment Loaned',
	},
	start_date: {
		name: 'Start date',
	},
	end_date: {
		name: 'End date',
	},
	actions: {
		name: '',
		autoWidth: true,
		border: false,
	},
};

const form = useForm({
	loanee_id: null,
	status: props.statuses.find(status => status.value.toLowerCase() === 'requested')?.value ?? props.statuses[0].name,
	denied_reason: '',
	approval_date: null,
	start_date: dayjs().format('YYYY-MM-DD HH:mm:ss'),
	end_date: dayjs().add(1, 'day').format('YYYY-MM-DD HH:mm:ss'),
	equipments: [],
});

const filters = reactive({
	search: props.filters?.search,
	status: props.filters?.status ? props.statuses.find(status => status.value === props.filters.status) : null,
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
		only: ['loans'],
	});
}, 300));

const dropdownLinks = (loan) => {
	return [
		{ name: 'View', href: route('admin.loan.show', loan.id) },
	]
}

const getPillColour = (text) => {
	switch (text.toLowerCase()) {
		case 'requested': return 'amber'
		case 'denied': return 'red'
		case 'approved': return 'green'
		case 'out on loan': return 'amber'
		case 'overdue': return 'red'
		case 'partially returned': return 'amber'
		case 'complete': return 'green'
		default: return ''
	}
}
</script>
