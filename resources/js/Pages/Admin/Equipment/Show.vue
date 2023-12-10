<template>
	{{ equipment }}
</template>

<script setup>
const props = defineProps({
	title: String,
	equipment: Object,
	breadcrumbs: Object,
});
</script>
