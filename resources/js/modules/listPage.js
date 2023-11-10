import { reactive, computed } from 'vue'
import { nextTick } from "process"

export function useListPage(deleteRoute, restoreRoute) {
	const state = reactive({
		selectedItem: null,
		showCreate: false,
		showEdit: false,
		showDelete: false,
		showRestore: false,
		editForm: null,
	})

	// Create functions
	const openCreate = () => {
		state.showCreate = true;
	}

	const closeCreate = () => {
		state.showCreate = false;
	}

	// Edit functions
	const openEdit = (item, form) => {
		state.selectedItem = item;

		state.editForm = form;

		nextTick(() => state.showEdit = true)
	}

	const closeEdit = () => {
		state.showEdit = false;
		state.selectedItem = null;
	}

	// Delete functions
	const openDelete = (item) => {
		state.selectedItem = item;

		nextTick(() => state.showDelete = true)
	}

	const closeDelete = () => {
		state.showDelete = false;
		state.selectedItem = null;
	}

	const deleteAction = computed(() => {
		if (!deleteRoute || !state.selectedItem) return null;

		return route(deleteRoute, state.selectedItem)
	})

	// Restore functions
	const openRestore = (item) => {
		state.selectedItem = item;

		nextTick(() => state.showRestore = true)
	}

	const closeRestore = () => {
		state.showRestore = false;
		state.selectedItem = null;
	}

	const restoreAction = computed(() => {
		if (!restoreRoute || !state.selectedItem) return null;

		return route(restoreRoute, state.selectedItem);
	})

	return {
		state,
		openCreate,
		closeCreate,
		openEdit,
		closeEdit,
		openDelete,
		closeDelete,
		deleteAction,
		openRestore,
		closeRestore,
		restoreAction,
	}
}
