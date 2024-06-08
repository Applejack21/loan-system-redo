<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #subTitle>
						Welcome to your dashboard.
					</template>
				</CardHeader>

				<div class="divide-y divide-primary-light">
					<div class="py-5">
						<div class="grid grid-cols-1 2xl:grid-cols-2 gap-5">
							<div>
								<Card colour="grey">
									<CardBody>
										<CardHeader headerSize="text-lg">
											Your Recent Loans
											<template #subTitle>
												Your 5 most recent loans.
											</template>
											<template #button>
												<AppButton :is="Link" :href="'#'">
													All Loans
													<template #iconRight>
														<ArrowRightIcon />
													</template>
												</AppButton>
											</template>
										</CardHeader>
										<Table :rows="loans.data" :columns="loanColumns" breakpoint="xl">
											<template #td-status="{ cell }">
												<Pill :colour="getPillColour(cell)">
													{{ cell }}
												</Pill>
											</template>
											<template #td-actions="{ row, index }">
												<DropdownMenu :links="loanDropdownLinks(row)" breakpoint="xl" />
											</template>
										</Table>
									</CardBody>
								</Card>
							</div>

							<div>
								<Card colour="grey">
									<CardBody>
										<CardHeader headerSize="text-lg">
											Overdue Loans
											<template #subTitle>
												Your 5 most overdue loans.
											</template>
											<template #button>
												<AppButton :is="Link" :href="'#'">
													All Overdue Loans
													<template #iconRight>
														<ArrowRightIcon />
													</template>
												</AppButton>
											</template>
										</CardHeader>
										<Table :rows="overdueLoans.data" :columns="loanColumns" breakpoint="xl">
											<template #emptyText>
												<div class="text-neutral-600">
													No overdue loans! 🥳
												</div>
											</template>
											<template #td-status="{ cell }">
												<Pill :colour="getPillColour(cell)">
													{{ cell }}
												</Pill>
											</template>
											<template #td-actions="{ row, index }">
												<DropdownMenu :links="loanDropdownLinks(row)" breakpoint="xl" />
											</template>
										</Table>
									</CardBody>
								</Card>
							</div>
						</div>
					</div>

					<div class="py-5">
						TODO: Show a list of products loaned by this user and returned recently, asking for rating (once
						that model is done).
					</div>
				</div>
			</CardBody>
		</Card>
	</AppLayout>
</template>

<script setup>
import { ArrowRightIcon } from '@heroicons/vue/24/outline';
import { Link } from '@inertiajs/vue3';
import { Card, CardHeader, CardBody } from "@/Components/Card";
import { Table } from "@/Components/Table";
import { DropdownMenu } from "@/Components/Dropdown";
import { Pill } from "@/Components";

const props = defineProps({
	title: String,
	breadcrumbs: Object,
	loans: Object,
	overdueLoans: Object,
});

const loanColumns = {
	status: {
		name: 'Status',
	},
	equipments_count: {
		name: 'Equipment',
	},
	end_date: {
		name: 'End Date',
	},
	actions: {
		name: '',
		autoWidth: true,
		border: false,
	},
};

const loanDropdownLinks = (loan) => {
	return [
		{ name: 'View', href: '#' },
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
