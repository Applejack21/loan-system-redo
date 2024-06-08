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
					<!-- Search -->
					<TextInput type="search" placeholder="Search loans..." v-model="filters.search">
						<template #iconLeft>
							<MagnifyingGlassIcon />
						</template>
					</TextInput>

					<!-- Status -->
					<div class="flex justify-center sm:justify-start">
						<Dropdown align="left">
							<template #trigger>
								<AppButton colour="dropdown">
									{{ state.statusFilter?.name || 'All Statuses' }}
									<template #iconRight>
										<ChevronDownIcon />
									</template>
								</AppButton>
							</template>
							<template #content>
								<span class="px-4 py-2">
									Select status
								</span>
								<template v-for="status in state.statusFilters">
									<DropdownLink as="button"
										@click="[state.statusFilter = status, filters.status = status.value]">
										{{ status.name }}
									</DropdownLink>
								</template>
							</template>
						</Dropdown>
					</div>
				</div>
				<Table :rows="loans.data" :columns="tableColumns" :paginationLinks="loans.meta" :only="['loans']"
					breakpoint="xl">

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
						<DropdownMenu :links="dropdownLinks(row)" breakpoint="xl" />
					</template>
				</Table>
			</CardBody>
		</Card>
	</AppLayout>
</template>

<script setup>
import { reactive, watch, provide } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { MagnifyingGlassIcon, XMarkIcon, ChevronDownIcon } from '@heroicons/vue/24/outline';
import throttle from "lodash/throttle";
import dayjs from 'dayjs';
import Dropdown from '@/Jetstream/Dropdown.vue';
import DropdownLink from '@/Jetstream/DropdownLink.vue'
import { Card, CardBody, CardHeader } from '@/Components/Card';
import { Table } from '@/Components/Table';
import { FormModal, UserPreview, Pill } from '@/Components';
import { TextInput } from '@/Components/Form';
import { DropdownMenu } from '@/Components/Dropdown';
import Form from './Partials/Form.vue';

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
	},
	status: {
		name: 'Status',
	},
	reference: {
		name: 'Reference',
		popper: true,
	},
	equipments_count: {
		name: 'Equipment',
	},
	start_date: {
		name: 'Start',
	},
	end_date: {
		name: 'End',
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
	date_collected: null,
	date_returned: null,
	equipments: [],
});

const state = reactive({
	statusFilters: props.statuses,
	statusFilter: null,
});

state.statusFilter = state.statusFilters.find(filter => filter.value === props.filters.status) || state.statusFilters?.[0] || null;

const filters = reactive({
	search: props.filters?.search,
	status: props.filters.status ?? state.statusFilter.name,
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
