<template>
	<AppLayout :title="title">
		{{ user }}
	</AppLayout>
</template>

<script setup>

defineProps({
	title: String,
	user: Object,
});
</script>
