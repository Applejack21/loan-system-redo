<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<div class="flex flex-col space-y-5">
			<Card>
				<CardHeader>
					<template #button>
						<FormModal :form="form" :route="route('admin.category.update', category.data)"
							@success="(modalState) => modalState.visible = false" method="patch">
							<template #open-text>
								Edit
							</template>
							<template #open-icon>
								<PencilSquareIcon />
							</template>
							<template #title>
								{{ `Updating "${form.name}"` }}
							</template>
							<template #content>
								<Form :form="form" />
							</template>
							<template #submit-text>
								Update category
							</template>
						</FormModal>
						<ConfirmDelete :action="route('admin.category.destroy', category.data)" />
					</template>
				</CardHeader>
				<div class="px-4 flow-root sm:px-6 sm:flex-end sm:flex -mt-[5.5rem] w-4/5">
					<div>
						<div class="flex -m-0.5">
							<div
								class="border-4 border-white rounded-lg overflow-hidden inline-flex lg:w-48 lg:h-48 sm:w-40 sm:h-40 flex-shrink-0 h-24 w-24">
								<!-- <img :src="user.data.profile_photo_url" /> -->
								TODO: Show category image once media-library is done
							</div>
						</div>
					</div>
					<div class="sm:flex-1 sm:ml-6 sm:mt-6">
						<div>
							<div class="flex items-center">
								<h3 class="sm:text-2xl font-bold text-neutral-dark-grey">
									{{ category.data.name }}
								</h3>
							</div>
							<p class="flex items-center space-x-2">
								<span class="text-sm">
									{{ category.data.slug }}
								</span>
							</p>
						</div>
					</div>
				</div>

				<div class="flex flex-col mt-5">
					<dl class="divide-y-2 divide-neutral-dark-grey">
						<div class="sm:py-5 sm:px-6 sm:flex sm:items-center">
							<dt class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
								Created By
							</dt>
							<UserPreview :user="category.data.created_by" size="sm" />
						</div>
						<div class="sm:py-5 sm:px-6 sm:flex sm:items-center">
							<dt class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
								Last Updated By
							</dt>
							<UserPreview :user="category.data.updated_by" size="sm" />
						</div>
						<div class="sm:py-5 sm:px-6 sm:flex">
							<dt class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
								Created At
							</dt>
							<dd class="sm:m-0 sm:ml-6 text-sm">
								<p>
									{{ dayjs(category.data.created_at).format('DD/MM/YYYY @ H:m:s') }}
								</p>
							</dd>
						</div>
						<div class="sm:py-5 sm:px-6 sm:flex">
							<dt class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
								Updated At
							</dt>
							<dd class="sm:m-0 sm:ml-6 text-sm">
								<p>
									{{ dayjs(category.data.updated_at).format('DD/MM/YYYY @ H:m:s') }}
								</p>
							</dd>
						</div>
					</dl>
				</div>
			</Card>

			<Card>
				<div class="flex flex-col mt-5">
					TODO: show a list of products this category has linked to it
				</div>
			</Card>
		</div>
	</AppLayout>
</template>

<script setup>
import { PencilSquareIcon } from '@heroicons/vue/24/outline'
import dayjs from "dayjs";
import { useForm } from "@inertiajs/vue3";
import { FormModal, ConfirmDelete, UserPreview } from "@/Components";
import { Card, CardHeader } from "@/Components/Card";
import Form from "./Partials/Form.vue";

const props = defineProps({
	title: String,
	category: Object,
	breadcrumbs: Object,
});

const form = useForm({
	name: props.category.data.name,
	slug: props.category.data.slug,
});
</script>
