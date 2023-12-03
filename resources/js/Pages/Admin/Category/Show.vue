<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		{{ category }}
	</AppLayout>
</template>

<script setup>
const props = defineProps({
	title: String,
	category: Object,
	breadcrumbs: Object,
});
</script>
