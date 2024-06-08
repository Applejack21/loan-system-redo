<template>
	<div class="rounded-t-[10px]" :class="[{ 'border-t-[10px] border-accent-light': border }]">
		<TableOuter>
			<TableHeader class="sticky top-0 z-10">
				<TableRow :hover="false">
					<slot name="columns" :columns="columns">
						<TableTh v-for="column in Object.keys(columns)" :autoWidth="columns[column].autoWidth"
							:nowrap="columns[column].nowrap" :class="[visibilityClasses(columns[column])]">
							<slot :name="`th-${column}`" :column="columns[column]">

								<span is="span" :class="columns[column].headerColour ?? headerColour">
									{{ columns[column].name }}
								</span>
							</slot>
						</TableTh>
					</slot>
				</TableRow>
			</TableHeader>
			<TableBody>
				<TableRow v-for="(row, index) in   rows  " :key="row.id || index"
					@click="$emit('row-clicked', { row: row, index: index })">
					<slot name="row" :row="row" :index="index">
						<TableTd v-for="column in   Object.keys(columns)  "
							:class="[{ 'cursor-pointer': clickable }, visibilityClasses(columns[column])]"
							:popper="columns[column]?.popper" :breakpoint="columns[column]?.hiddenUntil"
							:border="columns[column]?.border" :popperContent="row[column]">
							<!-- For mobile headers. -->
							<template #header>
								<span
									:class="[visibilityClasses(columns[column]), columns[column].colour ?? 'text-neutral-600']">
									{{ columns[column].name }}
								</span>
							</template>
							<div :class="[{ 'flex': columns[column].popper === true },]">
								<slot :name="`td-${column}`" :row="row" :cell="row[column]">

									<span
										:class="[visibilityClasses(columns[column]), columns[column].colour ?? 'text-neutral-600', { 'truncate': columns[column].popper === true }]">
										{{ row[column] }}
									</span>
								</slot>
							</div>
						</TableTd>
					</slot>
				</TableRow>
			</TableBody>
			<slot name="extraBody" />
		</TableOuter>
		<div v-if="!rows.length" class="w-full table">
			<TableRow>
				<TableTd>
					<slot name="emptyText">
						<div class="text-neutral-600">
							No data found.
						</div>
					</slot>
				</TableTd>
			</TableRow>
		</div>
	</div>
	<Pagination :links="paginationLinks" v-if="paginationLinks" :only="only" class="mt-[30px] flex justify-center" />
</template>

<script setup>
import { provide } from "vue";
import { TableBody, TableHeader, TableOuter, TableRow, TableTd, TableTh } from "@/Components/Table";
import { Pagination } from '@/Components/Pagination';

const props = defineProps({
	breakpoint: {
		type: String,
		default: "lg",
	},
	columns: {
		type: Object,
	},
	rows: {
		type: Array,
		default: () => []
	},
	clickable: {
		type: Boolean,
		default: false,
	},
	paginationLinks: {
		type: Object,
		required: false,
		default: () => { },
	},
	border: {
		type: Boolean,
		default: true,
	},
	only: {
		type: Array,
		required: false,
		default: () => [],
	},
	headerColour: {
		type: String,
		default: 'text-neutral-600',
	}
})

provide("tableBreakpoint", props.breakpoint)

const visibilityClasses = (column) => {
	if (!column.hiddenUntil) return

	return `hidden ${column.hiddenUntil}:table-cell`;
}
</script>
