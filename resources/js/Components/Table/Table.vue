<template>
	<TableOuter :border="border">
		<TableHeader class="sticky top-0 z-10">
			<TableRow :hover="false">
				<slot name="columns" :columns="columns">
					<TableTh v-for="column in Object.keys(columns)" :autoWidth="columns[column].autoWidth"
						:textSize="headerSize">
						<slot :name="`th-${column}`" :column="columns[column]">
							{{ columns[column].name }}
						</slot>
					</TableTh>
				</slot>
			</TableRow>
		</TableHeader>
		<TableBody>
			<TableRow v-for="(row, index) in rows" :key="row.id || index"
				@click="$emit('row-clicked', { row: row, index: index })">
				<slot name="row" :row="row" :index="index">
					<TableTd v-for="column in Object.keys(columns)" :class="{ 'cursor-pointer': clickable }"
						:textSize="textSize" :popper="columns[column].popper">
						<template #header>
							{{ columns[column].name }}
						</template>
						<div :class="columns[column].popper === true ? 'truncate' : ''">
							<slot :name="`td-${column}`" :row="row" :index="index" :cell="row[column]">
								{{ row[column] ? row[column] :
									"" }}
							</slot>
						</div>
					</TableTd>
				</slot>
			</TableRow>
		</TableBody>
	</TableOuter>
	<Pagination v-if="paginationLinks" :links="paginationLinks" :only="only" class="my-5" />
</template>

<script setup>
import { provide } from "vue"
import {
	TableOuter,
	TableHeader,
	TableBody,
	TableRow,
	TableTd,
	TableTh,
} from "@/Components/Table"
import { Pagination } from "@/Components/Pagination";

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
	headerSize: {
		type: String,
		required: false,
		default: 'md',
	},
	textSize: {
		type: String,
		required: false,
		default: 'md'
	},
	border: Boolean,
	only: {
		type: Array,
		required: false,
		default: () => [],
	}
})

provide("tableBreakpoint", props.breakpoint)
</script>
