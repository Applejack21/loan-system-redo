import { reactive } from 'vue';

export function useModal(emit) {
	const state = reactive({
		visible: false,
		processing: false
	})

	const openModal = async () => {
		emit('open')
		state.visible = true
	}

	const closeModal = async () => {
		emit('close')
		state.visible = false
	}

	return {
		state,
		emit,
		openModal,
		closeModal
	}
}
