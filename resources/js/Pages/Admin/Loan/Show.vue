<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		{{ loan }}
	</AppLayout>
</template>

<script setup>

const props = defineProps({
	title: String,
	loan: Object,
	breadcrumbs: Object,
});
</script>
