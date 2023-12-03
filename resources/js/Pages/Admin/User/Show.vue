<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<div class="flex flex-col space-y-5">
			<Card>
				<CardHeader>
					<template #button>
						<FormModal :form="form" :route="route('admin.user.update', user.data)"
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
								<Form :form="form" :newUser="false" />
							</template>
							<template #submit-text>
								Update user
							</template>
						</FormModal>
						<ConfirmDelete :action="route('admin.user.destroy', user.data)" />
					</template>
				</CardHeader>
				<div class="px-4 flow-root sm:px-6 sm:flex-end sm:flex -mt-[5.5rem] w-4/5">
					<div>
						<div class="flex -m-0.5">
							<div
								class="border-4 border-white rounded-lg overflow-hidden inline-flex lg:w-48 lg:h-48 sm:w-40 sm:h-40 flex-shrink-0 h-24 w-24">
								<img :src="user.data.profile_photo_url" />
							</div>
						</div>
						<AppButton colour="red" class="flex justify-center w-1/2 sm:w-full mt-1">
							Remove avatar - TODO: functionality
						</AppButton>
					</div>
					<div class="sm:flex-1 sm:ml-6 sm:mt-6">
						<div>
							<div class="flex items-center">
								<h3 class="sm:text-2xl font-bold text-neutral-dark-grey">
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
						<div class="flex flex-wrap mt-5 space-x-5">
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
					</div>
				</div>

				<div class="flex flex-col mt-5">
					<dl class="divide-y-2 divide-neutral-dark-grey">
						<div class="sm:py-5 sm:px-6 sm:flex">
							<dt class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
								Address
							</dt>
							<dd class="sm:m-0 sm:ml-6 text-sm">
								<p v-if="user.data.address" v-html="formatAddress(user.data.address)" />
								<p v-else class="text-red-600">
									No address found...
								</p>
							</dd>
						</div>
						<div class="sm:py-5 sm:px-6 sm:flex">
							<dt class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
								Created At
							</dt>
							<dd class="sm:m-0 sm:ml-6 text-sm">
								<p>
									{{ dayjs(user.data.created_at).format('DD/MM/YYYY @ H:m:s') }}
								</p>
							</dd>
						</div>
						<div class="sm:py-5 sm:px-6 sm:flex">
							<dt class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
								Updated At
							</dt>
							<dd class="sm:m-0 sm:ml-6 text-sm">
								<p>
									{{ dayjs(user.data.updated_at).format('DD/MM/YYYY @ H:m:s') }}
								</p>
							</dd>
						</div>
					</dl>
				</div>
			</Card>

			<Card>
				<div class="flex flex-col mt-5">
					TODO: show a list of loans this user has made
				</div>
			</Card>
		</div>
	</AppLayout>
</template>

<script setup>
import { EnvelopeIcon, PhoneIcon, PencilSquareIcon } from '@heroicons/vue/24/outline'
import dayjs from "dayjs";
import { useForm } from "@inertiajs/vue3";
import { FormModal, ConfirmDelete } from "@/Components";
import { Card, CardHeader } from "@/Components/Card";
import Form from "./Partials/Form.vue";

const props = defineProps({
	title: String,
	user: Object,
	breadcrumbs: Object,
});

const formatAddress = (address) => [
	address.line_one || null,
	address.line_two || null,
	address.line_three || null,
	address.city || null,
	address.postcode || null,
].filter(Boolean).join(', <br>');

const form = useForm({
	name: props.user.data.name,
	email: props.user.data.email,
	type: props.user.data.type,
	phone_number: props.user.data.phone_number,
	address: {
		line_one: props.user.data.address?.line_one,
		line_two: props.user.data.address?.line_two,
		line_three: props.user.data.address?.line_three,
		postcode: props.user.data.address?.postcode,
		city: props.user.data.address?.city,
	},
});
</script>
