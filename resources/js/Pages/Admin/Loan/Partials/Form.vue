<template>
	<Wrapper :form="form" :showIndicator="true" :showErrorIndexName="false">

		<div class="bg-white gap-4 p-2 mb-14 grid grid-cols-1 md:grid-cols-2">
			<div>
				<Label for="loanee" required>
					Loanee
				</Label>
				<Multiselect id="loanee" v-model="form.loanee_id" :closeOnSelect="true" :searchable="true"
					:options="formattedUsers" placeholder="Loanee..." :canDeselect="false" :canClear="false"
					:disabled="editing" />
			</div>

			<div>
				<Label for="status" required>
					Status
				</Label>
				<Multiselect id="status" v-model="form.status" :closeOnSelect="true" :searchable="true"
					:options="statuses" placeholder="Status..." valueProp="value" label="name" :canDeselect="false"
					:canClear="false" />
			</div>

			<div v-if="form.status === 'denied'">
				<TextInput v-model="form.denied_reason" label="Denied Reason" placeholder="Denied Reason"
					id="denied_reason" required />
			</div>

			<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 col-span-full gap-x-6 gap-y-6">
				<div>
					<Label for="Start Date" required>
						Start Date
					</Label>
					<DatePicker v-model.string="form.start_date" mode="dateTime" :masks="masks" is24hr hide-time-header
						is-required />
				</div>

				<div>
					<Label for="End Date" required>
						End Date
					</Label>
					<DatePicker v-model.string="form.end_date" mode="dateTime" :masks="masks" is24hr hide-time-header
						is-required />
				</div>

				<div>
					<Label for="Approval Date">
						Approval Date
					</Label>
					<DatePicker v-model.string="form.approval_date" mode="dateTime" :masks="masks" is24hr
						hide-time-header />
				</div>
			</div>
			<Repeater :list="form.equipments" key="id" @add-item="addItem" :min="1" reorderSize="sm" removeSize="sm"
				addSize="sm" :reorder="true" rowClasses="grid grid-cols-2 md:grid-cols-3 gap-y-5 gap-x-2">
				<template #item="{ element, index }">
					<div class="w-full col-span-2 md:col-span-1">
						<Label :for="`equipment-${index}`" v-if="index === 0" required>
							Equipment
						</Label>
						<Multiselect :id="`equipment-${index}`" v-model="element.equipment_id" :closeOnSelect="true"
							:searchable="true" :options="equipments" placeholder="Equipment..." valueProp="id"
							label="name" :canDeselect="false" :canClear="false" />
					</div>

					<TextInput v-model="element.quantity" :label="index === 0 ? 'Quantity' : ''" placeholder="Quantity"
						:id="`quantity-${index}`" required />

					<div class="w-[5%]">
						<Label v-if="index === 0">
							Returned?
						</Label>
						<div class="ml-5">
							<Checkbox v-model:checked="element.returned" :id="`returned-${index}`" />
						</div>
					</div>
				</template>
				<template #no-items>
					<span class="text-md text-red-600">
						There are no equipments added for this loan. Click the button below to add one.
					</span>
				</template>
			</Repeater>
		</div>
	</Wrapper>
</template>

<script setup>
import { ref, inject } from "vue";
import { DatePicker } from 'v-calendar';
import Multiselect from '@vueform/multiselect';
import { Wrapper, Label, TextInput, Repeater, Checkbox } from "@/Components/Form";

const props = defineProps({
	form: {
		type: Object,
		required: true
	},
	editing: {
		type: Boolean,
		default: false,
	},
});

const equipments = inject('equipments', []);
let statuses = inject('statuses', []);
const users = inject('users', []);

const formattedUsers = users.map(user => {
	return {
		'value': user.id,
		'label': user.name + ' - ' + user.email,
	};
});

if (statuses[0].name === 'All') {
	statuses.splice(0, 1);
}

const masks = ref({
	modelValue: 'YYYY-MM-DD H:mm:ss',
});

const blankItem = () => {
	return {
		equipment_id: null,
		quantity: 1,
		returned: 0,
	}
}

// add a row by default
if (!props.editing) {
	props.form.equipments.push(blankItem());
}

const addItem = () => {
	props.form.equipments.push(blankItem())
}
</script>
