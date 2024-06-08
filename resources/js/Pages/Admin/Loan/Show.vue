<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<div class="flex flex-col space-y-5">
			<Card>
				<CardBody>
					<div class="px-4 flow-root sm:px-6 sm:flex-end sm:flex w-4/5">
						<div class="sm:flex-1 sm:ml-6 sm:mt-6">
							<div>
								<div class="flex flex-col">
									<h3 class="text-2xl font-bold text-neutral-dark-grey">
										{{ title }}
									</h3>
									<span class="text-sm text-gray-600">
										{{ loan.data.reference }}
									</span>
								</div>
							</div>
							<Pill class="mt-2" :colour="getPillColour(loan.data.status)">
								{{ loan.data.status }}
							</Pill>
							<div class="mt-2 flex space-x-5 justify-start">
								<AppButton colour="secondary" @click="openEdit(loan.data, createEditForm(loan.data))">
									<template #icon>
										<PencilSquareIcon />
									</template>
								</AppButton>
								<AppButton colour="red" @click="openDelete(loan.data)">
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
									Loanee
								</h4>
								<UserPreview :user="loan.data.loanee" size="sm" />
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Equipment Loaned
								</h4>
								<template v-if="loanEquipment.length > 0">
									<p class="ml-4">
									<ol class="space-y-2">
										<template v-for="equipment in loanEquipment">
											<li class="list-disc">
												<span>
													Name:
												</span>
												<Link class="hyperlink"
													:href="route('admin.equipment.show', equipment.slug)">
												{{ equipment.name }}
												</Link>
												<ul class="list-[square] pl-4">
													<li>
														Quantity: {{ equipment.pivot.quantity }}
													</li>
													<li>
														Returned All?
														<span class="font-semibold">
															{{ equipment.pivot.returned === 0 ? 'No.' : 'Yes' }}
														</span>
													</li>
												</ul>
											</li>
										</template>
									</ol>

									</p>
								</template>
								<template v-else>
									<p class="text-red-600">
										No equipment added....
									</p>
								</template>
							</div>
							<div v-if="loan.data.approved_by" class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Approved By
								</h4>
								<div class="flex flex-col gap-y-1">
									<UserPreview :user="loan.data.approved_by" size="sm" />
									<span class="text-sm text-gray-600">
										{{ loan.data.approval_date }}
									</span>
								</div>
							</div>
							<div v-if="loan.data.denied_by" class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Denied By
								</h4>
								<div class="flex flex-col">
									<UserPreview :user="loan.data.denied_by" size="sm" />
									<span class="text-sm text-gray-600">
										{{ loan.data.denied_reason }}
									</span>
								</div>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-wrap sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Dates
								</h4>
								<ul class="list-disc ml-4">
									<li>
										Start Date: {{ loan.data.start_date }}
									</li>
									<li>
										End Date: {{ loan.data.end_date }}
									</li>
									<li>
										Date Returned:
										<template v-if="loan.data.date_returned">
											{{ loan.data.date_returned }}
										</template>
										<template v-else>
											<span class="text-red-600">
												N/A
											</span>
										</template>
									</li>
									<li>
										Date Collected:
										<template v-if="loan.data.date_collected">
											{{ loan.data.date_collected }}
										</template>
										<template v-else>
											<span class="text-red-600">
												N/A
											</span>
										</template>
									</li>
								</ul>
							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Created By
								</h4>
								<div class="flex flex-col">
									<UserPreview :user="loan.data.created_by" size="sm" />
									<span class="text-sm text-gray-600">
										{{ loan.data.created_at }}
									</span>
								</div>

							</div>
							<div class="sm:py-5 sm:px-6 sm:flex items-center py-2">
								<h4 class="lg:w-48 sm:flex-shrink-0 sm:w-40 text-neutral-dark-grey font-medium text-sm">
									Last Updated By
								</h4>
								<div class="flex flex-col">
									<UserPreview :user="loan.data.updated_by" size="sm" />
									<span class="text-sm text-gray-600">
										{{ loan.data.updated_at }}
									</span>
								</div>
							</div>
						</dl>
					</div>
				</CardBody>
			</Card>
		</div>

		<!-- edit modal -->
		<FormModal v-if="state.selectedItem" :form="state.editForm" :toggle="state.showEdit"
			:urlRoute="route('admin.loan.update', state.selectedItem)" :submitOptions="state.editForm" :button="false"
			@close="closeEdit()" @success="closeEdit()" maxWidth="4xl" method="patch">
			<template #title>
				{{ `Updating Loan For "${loan.data.loanee.name}"` }}
			</template>
			<template #content>
				<Form :form="state.editForm" :editing=true />
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
import { provide } from 'vue';
import { useForm } from "@inertiajs/vue3";
import { useListPage } from '@/modules/listPage.js';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import dayjs from "dayjs";
import { FormModal, ConfirmDelete, UserPreview, Pill } from "@/Components";
import { Card, CardBody } from "@/Components/Card";
import Form from "./Partials/Form.vue";

const props = defineProps({
	title: String,
	loan: Object,
	loanEquipment: Object,
	equipments: Object,
	statuses: Array,
	users: Object,
	breadcrumbs: Object,
});

provide('equipments', props.equipments);
provide('statuses', props.statuses);
provide('users', props.users);

const {
	state,
	openEdit,
	closeEdit,
	openDelete,
	closeDelete,
	deleteAction,
} = useListPage('admin.loan.destroy');

const createEditForm = (loan) => {
	const equipment = props.loanEquipment.map(equipment => {
		return {
			equipment_id: equipment.id,
			quantity: equipment.pivot.quantity,
			returned: equipment.pivot.returned === 0 ? false : true,
		}
	});

	console.log(loan);

	return useForm({
		loanee_id: loan.loanee.id,
		status: loan.status,
		denied_reason: loan.denied_reason,
		approval_date: loan.approval_date_no_format ? dayjs(loan.approval_date_no_format).format('YYYY-MM-DD HH:mm:ss') : null,
		date_collected: loan.date_collected_no_format ? dayjs(loan.date_collected_no_format).format('YYYY-MM-DD HH:mm:ss') : null,
		date_returned: loan.date_returned_no_format ? dayjs(loan.date_returned_no_format).format('YYYY-MM-DD HH:mm:ss') : null,
		start_date: dayjs(loan.start_date_no_format).format('YYYY-MM-DD HH:mm:ss'),
		end_date: dayjs(loan.end_date_no_format).format('YYYY-MM-DD HH:mm:ss'),
		equipments: equipment,
	});
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
