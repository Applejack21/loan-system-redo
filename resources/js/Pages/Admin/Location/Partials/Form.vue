<template>
	<Wrapper :form="form" :showIndicator="true">

		<div class="-mt-5 bg-white gap-4 sm:p-6 grid grid-cols-2">

			<TextInput label="Name" v-model="form.name" id="name" name="name" required />

			<div>
				<div class="flex -mb-2.5">
					<Label for="room_code">
						Room Code
					</Label>
					<PopperWrapper hover arrow placement="top">
						<QuestionMarkCircleIcon class="w-5 h-5 fill-primary-light" />
						<template #content>
							<div>
								<span>
									If the location is a room that has a number or code (e.g. FL1/12 for "Floor 1, Room 12")
								</span>
							</div>
						</template>
					</PopperWrapper>
				</div>
				<TextInput v-model="form.room_code" id="room_code" name="room_code" />
			</div>

		</div>
	</Wrapper>
</template>

<script setup>
import { QuestionMarkCircleIcon } from "@heroicons/vue/24/solid";
import { PopperWrapper } from "@/Components";
import { Wrapper, Label, TextInput } from "@/Components/Form";

const props = defineProps({
	form: {
		type: Object,
		required: true
	},
});
</script>
