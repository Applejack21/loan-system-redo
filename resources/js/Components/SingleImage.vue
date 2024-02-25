<template>
	<div>
		<div class="flex -m-0.5">
			<template v-if="image">
				<div
					class="border-4 border-white rounded-lg overflow-hidden inline-flex lg:w-48 lg:h-48 sm:w-40 sm:h-40 flex-shrink-0 h-24 w-24">
					<img :srcSet="image" />
				</div>
			</template>
			<template v-else>
				<div class="h-auto max-w-full flex flex-col items-center my-5">
					<span>
						{{ noPreviewText }}
					</span>
					<PhotoIcon class="h-32 fill-neutral-dark-grey" />
				</div>
			</template>
		</div>
		<div class="my-1 text-center border rounded-full" v-if="name">
			{{ name }}
		</div>
	</div>
</template>

<script setup>
import { computed } from 'vue';
import { PhotoIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
	image: Object,
	name: String,
	noPreviewText: {
		type: String,
		default: 'No preview available',
	}
});

const image = computed(() => props.image);
</script>
