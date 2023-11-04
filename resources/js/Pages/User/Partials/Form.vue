<template>
    <Wrapper :form="form" :showIndicator="true">

        <div
            class="-mt-5 bg-white gap-4 sm:p-6 grid grid-cols-2">

            <TextInput label="Name" v-model="form.name"
                id="name" name="name" required />
            <TextInput label="Email" v-model="form.email"
                id="email" name="email" required />

            <div class="col-span-2">
                <Label for="role" required>
                    Role
                </Label>
				<select v-model="form.role">
					<option v-for="role in roles" :name="role" :value="role" :id="role">
						{{ role }}
					</option>
				</select>
            </div>

            <template v-if="newUser">
                <TextInput type="password" label="Password"
                    v-model="form.password" id="password"
                    name="password" required />
                <TextInput type="password"
                    label="Password Confirmation"
                    v-model="form.password_confirmation"
                    id="password_confirmation"
                    name="password_confirmation" required />
            </template>

        </div>
    </Wrapper>
</template>

<script setup>
import { Wrapper, TextInput, Label } from "@/Components/Form";

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

const roles = [
    'customer',
    'admin',
];
</script>
