<template>
	<AppLayout :title="title" :breadcrumbs="breadcrumbs">
		<div>
			<p>TODO: Dashboard with data about loans, maybe some charts etc.</p>
		</div>
	</AppLayout>
</template>

<script setup>
const props = defineProps({
	title: String,
	breadcrumbs: Object,
});
</script>
