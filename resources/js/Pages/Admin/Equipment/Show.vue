<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<div class="flex flex-col space-y-5">
			<Card>
				<CardBody>
					<div class="px-4 flow-root sm:px-6 sm:flex-end sm:flex w-4/5">
						<div class="sm:flex-1 sm:ml-6 sm:mt-6">
							<div>
								<div class="flex items-center">
									<h3 class="text-2xl font-bold text-neutral-dark-grey">
										{{ equipment.data.name }}
									</h3>
								</div>
								<p class="flex items-center space-x-2">
									<span class="text-sm">
										{{ equipment.data.code }}
									</span>
								</p>
							</div>
							<div class="mt-5 flex space-x-5 justify-start">
								<AppButton colour="secondary"
									@click="openEdit(equipment.data, createEditForm(equipment.data))">
									<template #icon>
										<PencilSquareIcon />
									</template>
								</AppButton>
								<AppButton colour="red" @click="openDelete(equipment.data)">
									<template #icon>
										<TrashIcon />
									</template>
								</AppButton>
							</div>
						</div>
					</div>

					<div class="flex flex-col mt-5">
						<dl class="divide-y-2 divide-accent-light">
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Location
								</h4>
								<Link v-if="equipment.data.location"
									:href="route('admin.location.show', equipment.data.location.id)"
									class="hyperlink sm:ml-6">
								{{ equipment.data.location?.name }}
								</Link>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Price (for damages by loanee)
								</h4>
								<p class="sm:ml-6">
									{{ equipment.data.price_formatted }}
								</p>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-wrap sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Amount data
								</h4>
								<p class="sm:ml-6">
									Total amount - {{ equipment.data.amount }}
								</p>
								<p class="sm:ml-6">
									Amount in stock - {{ equipment.data.amount_in_stock }}
								</p>
								<p class="sm:ml-6">
									Amount on loan - {{ equipment.data.amount_on_loan }}
								</p>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Details
								</h4>
								<template v-if="equipment.data.details.length > 0">
									<p class="ml-4 sm:ml-6">
									<ul class="list-disc space-y-2">
										<li v-for="detail in equipment.data.details">
											Name: {{ detail.name }}<br>
											Value: {{ detail.value }}
										</li>
									</ul>
									</p>
								</template>
								<template v-else>
									<p class="sm:ml-6 text-red-600">
										No details added....
									</p>
								</template>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Created By
								</h4>
								<UserPreview :user="equipment.data.created_by" size="sm" />
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Last Updated By
								</h4>
								<UserPreview :user="equipment.data.updated_by" size="sm" />
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Created At
								</h4>
								<p class="sm:ml-6">
									{{ dayjs(equipment.data.created_at) }}
								</p>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Updated At
								</h4>
								<p class="sm:ml-6">
									{{ dayjs(equipment.data.updated_at) }}
								</p>
							</div>
						</dl>
					</div>
				</CardBody>
			</Card>

			<Card>
				<CardBody>
					<CardHeader>
						Description
					</CardHeader>
					<div class="prose" v-if="equipment.data.description" v-html="equipment.data.description" />
					<div v-else>
						<p class="text-red-600">
							No description added...
						</p>
					</div>
				</CardBody>
			</Card>

			<Card>
				<CardBody>
					<CardHeader>
						Images/Files
						<template #subTitle>
							A list of images/files for this equipment.
						</template>
					</CardHeader>
					<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
						<div>
							<template v-if="equipment.data.images.length > 0">
								<Carousel :items="equipment.data.images" />
							</template>
							<template v-else>
								<span class="text-red-600">
									No images added...
								</span>
							</template>
						</div>
						<div>
							TODO add additional downloads for equipment (manuals, guides, etc).
						</div>
					</div>
				</CardBody>
			</Card>

			<Card>
				<CardBody>
					<CardHeader>
						Categories
						<template #subTitle>
							A list of categories this equipment is linked to.
						</template>
					</CardHeader>

					<Table :rows="equipment.data.categories" :columns="tableColumns">

						<template #td-actions="{ row, index }">
							<DropdownMenu :links="dropdownLinks(row)" />
						</template>
					</Table>
				</CardBody>
			</Card>
		</div>

		<!-- edit modal -->
		<FormModal v-if="state.selectedItem" :form="state.editForm" :toggle="state.showEdit"
			:urlRoute="route('admin.equipment.update', state.selectedItem)" :submitOptions="state.editForm" :button="false"
			@close="closeEdit()" @success="closeEdit()">
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
import { useForm } from "@inertiajs/vue3";
import { useListPage } from '@/modules/listPage.js';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import dayjs from "dayjs";
import { DropdownMenu } from '@/Components/Dropdown';
import { Table } from '@/Components/Table';
import { FormModal, ConfirmDelete, UserPreview, Carousel } from "@/Components";
import { Card, CardBody, CardHeader } from "@/Components/Card";
import Form from "./Partials/Form.vue";

const props = defineProps({
	title: String,
	equipment: Object,
	breadcrumbs: Object,
	categories: Object,
	locations: Object,
});

const {
	state,
	openEdit,
	closeEdit,
	openDelete,
	closeDelete,
	deleteAction,
} = useListPage('admin.equipment.destroy');

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

const tableColumns = {
	name: {
		name: 'Name',
	},
	slug: {
		name: 'Slug',
	},
	actions: {
		name: '',
		autoWidth: '',
		border: false,
	},
};

const dropdownLinks = (category) => {
	return [
		{ name: 'View', href: route('admin.category.show', category.slug) },
	]
}
</script>
