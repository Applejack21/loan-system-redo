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
									<img :src="user.data.profile_photo_url" />
								</div>
							</div>
							<AppButton colour="red" class="flex justify-center w-1/2 sm:w-full mt-1">
								Remove
								<template #iconRight>
									<TrashIcon />
								</template>
							</AppButton>
						</div>
						<div class="sm:flex-1 sm:ml-6 sm:mt-6">
							<div>
								<div class="flex items-center">
									<h3 class="text-2xl font-bold text-neutral-dark-grey">
										{{ user.data.name }}
									</h3>
								</div>
								<p class="flex items-center space-x-2">
									<EnvelopeIcon class="w-5 h-5" />
									<span class="text-sm">
										{{ user.data.email }}
									</span>
								</p>
								<p v-if="user.data.phone_number" class="mt-1 flex items-center space-x-2">
									<PhoneIcon class="w-5 h-5" />
									<span class="text-sm">
										{{ user.data.phone_number }}
									</span>
								</p>
							</div>
							<div class="w-full flex flex-wrap mt-5 gap-x-2 gap-y-2 md:gap-y-2 lg:gap-y-0">
								<AppButton colour="secondary" is="a" :href="`mailto:${user.data.email}`">
									<template #iconRight>
										<EnvelopeIcon />
									</template>
									Email
								</AppButton>
								<AppButton v-if="user.data.phone_number" colour="secondary" is="a"
									:href="`tel:${user.data.phone_number}`">
									<template #iconRight>
										<PhoneIcon />
									</template>
									Phone
								</AppButton>
							</div>
							<div class="mt-5 flex space-x-5 justify-start">
								<AppButton colour="secondary" @click="openEdit(user.data, createEditForm(user.data))"
									v-if="user.data.id !== authUser.id">
									<template #icon>
										<PencilSquareIcon />
									</template>
								</AppButton>
								<AppButton colour="red" @click="openDelete(user.data)" v-if="user.data.id !== authUser.id">
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
									Address
								</h4>
								<p class="sm:ml-6">
									<p v-if="user.data.address && user.data.address.length > 0"
										v-html="formatAddress(user.data.address)" />
								<p v-else class="text-red-600">
									No address found...
								</p>
								</p>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Created At
								</h4>
								<p class="sm:ml-6">
									{{ user.data.created_at }}
								</p>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Updated At
								</h4>
								<p class="sm:ml-6">
									{{ user.data.updated_at }}
								</p>
							</div>
						</dl>
					</div>
				</CardBody>
			</Card>

			<Card>
				<CardBody>
					<div class="flex flex-col mt-5">
						TODO: show a list of loans this user has made
					</div>
				</CardBody>
			</Card>
		</div>

		<!-- edit modal -->
		<FormModal v-if="state.selectedItem" :form="state.editForm" method="patch" :toggle="state.showEdit"
			:urlRoute="route('admin.user.update', state.selectedItem)" :submitOptions="state.editForm" :button="false"
			@close="closeEdit()" @success="closeEdit()">
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
import { computed } from 'vue';
import { EnvelopeIcon, PhoneIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline'
import { useForm, usePage } from "@inertiajs/vue3";
import { useListPage } from '@/modules/listPage.js';
import { FormModal, ConfirmDelete } from "@/Components";
import { Card, CardBody } from "@/Components/Card";
import Form from "./Partials/Form.vue";

const props = defineProps({
	title: String,
	user: Object,
	breadcrumbs: Object,
});

const {
	state,
	openEdit,
	closeEdit,
	openDelete,
	closeDelete,
	deleteAction,
} = useListPage('admin.user.destroy');

const formatAddress = (address) => [
	address.line_one || null,
	address.line_two || null,
	address.line_three || null,
	address.city || null,
	address.postcode || null,
].filter(Boolean).join(', <br>');

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

const authUser = computed(() => usePage().props.auth.user);
</script>
