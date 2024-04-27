<template>
	<template v-if="form.hasErrors">
		<div class="rounded-md bg-red-50 p-4 mb-2">
			<div class="flex">
				<div class="flex-shrink-0">
					<XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
				</div>
				<div class="ml-3">
					<h3 class="text-sm font-semibold text-red-800">
						There were errors with your submission. Please fix these and try again.
					</h3>
					<div class="mt-2 text-sm text-red-700">
						<ul role="list" class="list-disc pl-5 space-y-1">
							<template v-for="(errorMessage, errorName) in form.errors" :key="errorName">
								<li class="list-disc">
									<span class="capitalize" v-if="showErrorIndexName">
										{{ removeUnderscores(errorName) }}:
									</span>
									{{ removeIdFromMessage(errorMessage) }}
								</li>
							</template>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</template>
</template>

<script setup>
import { XCircleIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
	form: {
		type: Object,
		required: true
	},
	showErrorIndexName: {
		type: Boolean,
		default: true,
	}
})

const removeUnderscores = (index) => {
	// remove all underscores as well as the
	// term " id" from the error name - only id on its own and not within a word. i.e. "valid" will not be changed to "val"
	index = index.replaceAll("_", " ");
	if (index.includes(" id")) {
		index = index.replace(" id", "");
	}
	index = index.trim();
	return index;
}

const removeIdFromMessage = (message) => {
	// remove any reference to "id" from the error message
	// this will only remove any "id" that is on its own and not within a word. i.e. "valid" will not be changed to "val"
	if (message.includes(" id")) {
		message = message.replaceAll(" id", "");
	}
	message = message.trim();
	return message;
}
</script>
