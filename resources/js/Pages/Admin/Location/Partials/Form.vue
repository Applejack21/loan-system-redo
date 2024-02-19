<template>
	<Wrapper :form="form" :showIndicator="true">

		<div class="bg-white gap-4 sm:p-6 grid grid-cols-1">

			<TextInput label="Name" v-model="form.name" id="name" name="name" required />

			<TextInput v-model="form.room_code" id="room_code" name="room_code" label="Room Code"
				popperText='If the location is a room that has a number or code (e.g. FL1/12 for "Floor 1, Room 12")' />

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
