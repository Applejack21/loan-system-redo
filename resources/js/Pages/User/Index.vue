<template>
	<AppLayout :title="title">
		{{ users }}
	</AppLayout>
</template>

<script setup>

defineProps({
	title: String,
	users: Object,
});
</script>
