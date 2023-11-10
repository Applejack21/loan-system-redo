<template>
	<AppLayout :title="title">
		<div>
			<p>TODO: Dashboard with data about logged in user's loans</p>
		</div>
	</AppLayout>
</template>

<script setup>
const props = defineProps({
	title: String,
	breadcrumbs: Object,
});
</script>
