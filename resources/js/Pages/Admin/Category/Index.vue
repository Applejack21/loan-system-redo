<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		{{ categories }}
	</AppLayout>
</template>

<script setup>
const props = defineProps({
	title: String,
	categories: Object,
	breadcrumbs: Object,
});
</script>
