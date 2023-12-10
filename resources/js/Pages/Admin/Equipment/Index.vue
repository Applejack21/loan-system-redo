<template>
	{{ equipments }}
</template>

<script setup>
const props = defineProps({
	title: String,
	equipments: Object,
	filters: Object,
	breadcrumbs: Object,
});
</script>
