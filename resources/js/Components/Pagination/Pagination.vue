<template>
	<div class="flex justify-center space-x-2 mt-5" v-if="links && links.last_page > 1">
		<template v-for="link in links.links" :key="link.url">
			<!-- set default of the divider to the component we have for it -->
			<template v-if="link.label == '...'">
				<PaginationDivider />
			</template>
			<template v-else>
				<Link :href="link.url" :only="only">
				<!-- by default prev and next have their own icons, but replace these with just heroicon arrows -->
				<template v-if="link.label.includes('Previous')">
					<PaginationButton>
						<ArrowLeftIcon class="w-5 h-5" />
					</PaginationButton>
				</template>
				<template v-else-if="link.label.includes('Next')">
					<PaginationButton>
						<ArrowRightIcon class="w-5 h-5" />
					</PaginationButton>
				</template>
				<template v-else>
					<PaginationButton :active="link.active" :disabled="link.active" class="disabled:opacity-50">
						{{ link.label }}
					</PaginationButton>
				</template>
				</Link>
			</template>
		</template>
	</div>
	<div class="flex justify-start space-x-2 mt-2.5">
		<p class="text-sm text-gray-700">
			Showing
			{{ ' ' }}
			<span class="font-medium">{{ links.from ?? 0 }}</span>
			{{ ' ' }}
			to
			{{ ' ' }}
			<span class="font-medium">{{ links.to ?? 0 }}</span>
			{{ ' ' }}
			of
			{{ ' ' }}
			<span class="font-medium">{{ links.total ?? 0 }}</span>
			{{ ' ' }}
			results
		</p>
	</div>
</template>

<script setup>
import { PaginationButton, PaginationDivider } from '@/Components/Pagination'
import { ArrowLeftIcon, ArrowRightIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
	links: {
		type: Object,
		required: false,
	},
	only: {
		type: Array,
		required: false,
	}
});
</script>
