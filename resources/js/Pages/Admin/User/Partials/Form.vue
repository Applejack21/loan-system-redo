<template>
	<Wrapper :form="form" :showIndicator="true">

		<div class="bg-white gap-4 sm:p-6 grid grid-cols-1 md:grid-cols-2">

			<TextInput label="Name" v-model="form.name" id="name" name="name" required />
			<TextInput label="Email" v-model="form.email" id="email" name="email" required />
			<div>
				<Label for="type" required>
					Type
				</Label>
				<SelectInput v-model="form.type" :items="types" returnProperty="value" listClasses="hover:bg-gray-200">
					<template #selectedItem="{ item }">
						{{ item ? item.name : 'Choose a type' }}
					</template>
					<template #item="{ item }">
						{{ item.name }}
					</template>
				</SelectInput>
			</div>

			<TextInput label="Phone Number" v-model="form.phone_number" id="phone_number" name="phone_number" />

			<TextInput label="Address Line 1" v-model=form.address.line_one id="line_one" name="line_one" />
			<TextInput label="Address Line 2" v-model=form.address.line_two id="line_two" name="line_two" />
			<TextInput label="Address Line 3" v-model=form.address.line_three id="line_three" name="line_three" />
			<TextInput label="Postcode" v-model=form.address.postcode id="postcode" name="postcode" />
			<TextInput label="City" v-model=form.address.city id="city" name="city" />

			<template v-if="newUser">
				<TextInput type="password" label="Password" v-model="form.password" id="password" name="password"
					required />
				<TextInput type="password" label="Password Confirmation" v-model="form.password_confirmation"
					id="password_confirmation" name="password_confirmation" required />
			</template>

		</div>
	</Wrapper>
</template>

<script setup>
import { Wrapper, TextInput, Label, SelectInput } from "@/Components/Form";

const props = defineProps({
	form: {
		type: Object,
		required: true
	},
	newUser: {
		type: Boolean,
		default: true,
	},
});

const types = [
	{
		name: 'Admin',
		value: 'admin',
	},
	{
		name: 'Customer',
		value: 'customer',
	},
];

// if editing a user
if (props.form.type) {
	props.form.type = types.find(type => type.value === props.form.type);
}
</script>
