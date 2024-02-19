<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<div class="flex flex-col space-y-5">
			<Card>
				<CardBody>
					<div class="px-4 flow-root sm:px-6 sm:flex-end sm:flex w-4/5">
						<div>
							<div class="flex -m-0.5">
								<div
									class="border-4 border-white rounded-lg overflow-hidden inline-flex lg:w-48 lg:h-48 sm:w-40 sm:h-40 flex-shrink-0 h-24 w-24">
									<img :src="category.data.image"
										alt="TODO: Show category image once media-library is done" />
								</div>
							</div>
						</div>
						<div class="sm:flex-1 sm:ml-6 sm:mt-6">
							<div>
								<div class="flex items-center">
									<h3 class="text-2xl font-bold text-neutral-dark-grey">
										{{ category.data.name }}
									</h3>
								</div>
								<p class="flex items-center space-x-2">
									<span class="text-sm">
										{{ category.data.slug }}
									</span>
								</p>
							</div>
							<div class="mt-5 flex space-x-5 justify-start">
								<AppButton colour="secondary"
									@click="openEdit(category.data, createEditForm(category.data))">
									<template #icon>
										<PencilSquareIcon />
									</template>
								</AppButton>
								<AppButton colour="red" @click="openDelete(category.data)">
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
									Created By
								</h4>
								<UserPreview :user="category.data.created_by" size="sm" />
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Last Updated By
								</h4>
								<UserPreview :user="category.data.updated_by" size="sm" />
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Created At
								</h4>
								<p class="sm:ml-6">
									{{ dayjs(category.data.created_at) }}
								</p>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Updated At
								</h4>
								<p class="sm:ml-6">
									{{ dayjs(category.data.updated_at) }}
								</p>
							</div>
						</dl>
					</div>
				</CardBody>
			</Card>

			<Card>
				<CardBody>
					<div class="flex flex-col mt-5">
						TODO: show a list of products this category has linked to it
					</div>
				</CardBody>
			</Card>
		</div>

		<!-- edit modal -->
		<FormModal v-if="state.selectedItem" :form="state.editForm" method="patch" :toggle="state.showEdit"
			:urlRoute="route('admin.category.update', state.selectedItem)" :submitOptions="state.editForm" :button="false"
			@close="closeEdit()" @success="closeEdit()">
			<template #title>
				{{ `Updating "${state.editForm.name}"` }}
			</template>
			<template #content>
				<Form :form="state.editForm" />
			</template>
			<template #submit-text>
				Update category
			</template>
		</FormModal>

		<!-- delete modal -->
		<ConfirmDelete :action="deleteAction" :button="false" :toggle="state.showDelete" @close="closeDelete()" />
	</AppLayout>
</template>

<script setup>
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import dayjs from "dayjs";
import { useForm } from "@inertiajs/vue3";
import { useListPage } from '@/modules/listPage.js';
import { FormModal, ConfirmDelete, UserPreview } from "@/Components";
import { Card, CardBody } from "@/Components/Card";
import Form from "./Partials/Form.vue";

const props = defineProps({
	title: String,
	category: Object,
	breadcrumbs: Object,
});

const {
	state,
	openEdit,
	closeEdit,
	openDelete,
	closeDelete,
	deleteAction,
} = useListPage('admin.category.destroy');

const createEditForm = (category) => {
	return useForm({
		name: category.name,
		slug: category.slug,
	});
}
</script>
