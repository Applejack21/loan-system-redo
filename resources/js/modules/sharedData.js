import { computed, watch } from "vue"
import { usePage } from "@inertiajs/vue3"
import { useToast } from "vue-toastification"


function useSharedData() {

	const message = computed(() => {
		return usePage().props.message
	})

	const showMessage = (message) => {
		if (!message) return

		// It wouldn't let me do it like this:
		// useToast()[message.value.type](message.value.message)
		// So had to do a switch
		switch (message.type) {
			case "success":
				useToast().success(message.message)
				break

			case "error":
				useToast().error(message.message)
				break

			case "warning":
				useToast().warning(message.message)
				break

			case "info":
				useToast().info(message.message)
				break

			default:
				useToast().info(message.message)
		}
	}

	const watchMessage = () => {
		watch(message, (newVal, oldVal) => {
			showMessage(newVal)
		})
	}

	if (usePage().props.message) {
		showMessage(usePage().props.message);
	}

	return {
		message,
		showMessage,
		watchMessage
	}
}

export {
	useSharedData
}
