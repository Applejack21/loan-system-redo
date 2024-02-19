<template>
	<div class="flex flex-col space-y-5 lg:flex lg:flex-row items-center space-x-0 min-h-[44px] mb-8 -mt-5">
		<div class="flex items-center flex-col space-y-2 md:flex-row w-full">
			<div>
				<slot name="title">
					<h2 class="text-2xl font-bold text-center md:text-left">
						<slot />
					</h2>

					<p class="text-base font-normal text-gray-700">
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
