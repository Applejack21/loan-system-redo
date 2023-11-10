import { computed } from "vue"
import { usePage } from "@inertiajs/vue3"
import { useToast } from "vue-toastification"


function useSharedData() {

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

	const message = computed(() => {
		return usePage().props.message
	});

	if (message.value) {
		showMessage(message.value);
	}

	return {
		message,
		showMessage,
	}
}

export {
	useSharedData
}
