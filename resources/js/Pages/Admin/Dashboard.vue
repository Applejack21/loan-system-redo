<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<Card>
			<CardBody>
				<CardHeader>
					{{ title }}
					<template #subTitle>
						Welcome to the admin dashboard.
					</template>
				</CardHeader>

				<div class="divide-y divide-primary-light">
					<div class="py-5">
						<div class="grid grid-cols-1 2xl:grid-cols-2 gap-5">
							<div>
								<Card colour="grey">
									<CardBody>
										<CardHeader headerSize="text-lg">
											Recent Requested loans
											<template #subTitle>
												The 5 most recent requested loans.
											</template>
											<template #button>
												<AppButton :is="Link" :href="route('admin.loan.index')">
													All Loans
													<template #iconRight>
														<ArrowRightIcon />
													</template>
												</AppButton>
											</template>
										</CardHeader>
										<Table :rows="recentLoans.data" :columns="loanColumns" breakpoint="xl">

											<template #td-loanee="{ cell }">
												<Link :href="route('admin.user.show', cell.id)">
												<UserPreview :user="cell" size="sm" />
												</Link>
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
											Overdue loans
											<template #subTitle>
												The 5 most oldest overdue loans.
											</template>
											<template #button>
												<AppButton :is="Link" :href="overdueLoansLink()">
													All Overdue Loans
													<template #iconRight>
														<ArrowRightIcon />
													</template>
												</AppButton>
											</template>
										</CardHeader>
										<Table :rows="overdueLoans.data" :columns="loanColumns" breakpoint="xl">

											<template #td-loanee="{ cell }">
												<Link :href="route('admin.user.show', cell.id)">
												<UserPreview :user="cell" size="sm" />
												</Link>
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
											Out of stock equipment
											<template #button>
												<AppButton :is="Link" :href="route('admin.equipment.index')">
													All Equipment
													<template #iconRight>
														<ArrowRightIcon />
													</template>
												</AppButton>
											</template>
										</CardHeader>
										<Table :rows="outOfStockEquipments.data" :columns="equipmentColumns"
											breakpoint="xl">
											<template #td-actions="{ row, index }">
												<DropdownMenu :links="equipmentDropdownLinks(row)" breakpoint="xl" />
											</template>
										</Table>
									</CardBody>
								</Card>
							</div>
						</div>
					</div>

					<div class="py-5">
						<div class="mt-5 w-full grid grid-cols-1 2xl:grid-cols-2 gap-2 items-center">
							<highcharts :options="chartOptions()" height="auto" width="auto" />
							<highcharts :options="popularChartOptions()" height="auto" width="auto" />
						</div>
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
import { UserPreview } from "@/Components";

const props = defineProps({
	title: String,
	breadcrumbs: Object,
	overdueLoans: Object,
	recentLoans: Object,
	outOfStockEquipments: Object,
	loanChartData: Object,
	userChartData: Object,
	chartDates: Array,
	equipmentChartData: Array,
});

const loanColumns = {
	loanee: {
		name: 'Loanee',
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
		{ name: 'View', href: route('admin.loan.show', loan.id) },
	]
}

const overdueLoansLink = () => {
	return route('admin.loan.index', {
		_query: {
			status: 'overdue',
		},
	})
}

const equipmentColumns = {
	name: {
		name: 'Name',
		popper: true,
	},
	code: {
		name: 'Code',
	},
	actions: {
		name: '',
		autoWidth: true,
		border: false,
	},
};

const equipmentDropdownLinks = (equipment) => {
	return [
		{ name: 'View', href: route('admin.equipment.show', equipment.slug) },
	]
}

const chartOptions = () => {
	return {
		chart: {
			type: 'line',
		},
		title: {
			text: 'Day-by-Day Data',
		},
		subtitle: {
			text: 'Over the last 30 days.',
		},
		xAxis: {
			categories: props.chartDates,
			title: {
				text: 'Date',
			},
		},
		yAxis: {
			title: {
				text: 'Amount'
			}
		},
		series: [
			{
				name: 'New Users',
				data: props.userChartData,
				color: '#001F3F',
			},
			{
				name: 'New Loans',
				data: props.loanChartData,
				color: '#3D85C6',
			},
		],
	}
}

const popularChartOptions = () => {
	return {
		chart: {
			type: 'column',
		},
		title: {
			text: 'Popular Equipment',
		},
		subtitle: {
			text: '5 most loaned out equipment in last 30 days.',
		},
		xAxis: {
			categories: props.equipmentChartData.map(equipment => equipment.label),
			title: {
				text: 'Equipment',
			},
		},
		yAxis: {
			title: {
				text: 'Amount'
			}
		},
		series: [
			{
				name: 'Quantity',
				data: props.equipmentChartData,
				color: '#3D85C6',
			},
		],
	}
}
</script>
