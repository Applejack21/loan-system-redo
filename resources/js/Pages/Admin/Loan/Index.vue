<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		{{ loans }}
	</AppLayout>
</template>

<script setup>

const props = defineProps({
	title: String,
	loans: Object,
	filters: Object,
	breadcrumbs: Object,
});
</script>
