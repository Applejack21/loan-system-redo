<template>
	<div class="flex flex-col space-y-5 md:flex md:flex-row items-center space-x-0 min-h-[44px] mb-8 -mt-5">
		<div class="flex items-center flex-col space-y-2 md:flex-row w-full">
			<div>
				<slot name="title">
					<h2 class="font-bold text-center md:text-left" :class="headerSize">
						<slot />
					</h2>

					<p class="font-normal text-gray-700" :class="subTitleSize">
						<slot name="subTitle" />
					</p>
				</slot>
			</div>
		</div>
		<div class="md:ml-auto">
			<slot name="button"></slot>
		</div>
	</div>
</template>

<script setup>
const props = defineProps({
	headerSize: {
		type: String,
		default: 'text-2xl',
	},
	subTitleSize: {
		type: String,
		default: 'text-base',
	},
});
</script>
