<template>
	<AppLayout :title="title">
		<Card>
			<CardHeader>
				{{ title }}
				<template #button>
					<FormModal :form="form" :route="route('user.store')"
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
			<div class="w-full flex space-x-5 my-2">
				<TextInput type="search" placeholder="Search users..." v-model="filters.search">
					<template #iconLeft>
						<MagnifyingGlassIcon />
					</template>
				</TextInput>
			</div>
			<Table :rows="users.data" :columns="tableColumns" :paginationLinks="users.meta" :border="true" />
		</Card>
	</AppLayout>
</template>

<script setup>
import { reactive, watch } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline';
import throttle from "lodash/throttle";
import { Card, CardHeader } from '@/Components/Card';
import { Table } from '@/Components/Table';
import { FormModal } from '@/Components';
import { TextInput } from '@/Components/Form';
import Form from './Partials/Form.vue';

const props = defineProps({
	title: String,
	users: Object,
	filters: Object,
});

const tableColumns = {
	name: {
		name: 'Name',
	},
	email: {
		name: 'Email',
	},
	role: {
		name: 'Role',
	},
};

const form = useForm({
	name: '',
	email: '',
	role: '',
	password: '',
	password_confirmation: '',
});

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
		data: { ...data },
		only: ['users'],
	});
}, 300));
</script>
