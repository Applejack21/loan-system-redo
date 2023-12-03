<template>
	<nav class="flex max-w-lg">
		<ol class="flex items-center text-sm">
			<li v-for="(route, key, index) in breadcrumbs" :class="classes(index)" class="inline-flex items-center">
				<Link :href="route" v-if="route"
					class="capitalize hover:text-secondary-dark">
				{{ key }}
				</Link>
				<span v-else class="capitalize">
					{{ key }}
				</span>
			</li>
		</ol>
	</nav>
</template>

<script setup>
const props = defineProps({
	breadcrumbs: Object,
});

const classes = index => {
	if (index === 0) return ''

	return `before:content-['/'] before:inline-flex before:px-1.5 before:text-md before:-mt-0.5`
}
</script>
