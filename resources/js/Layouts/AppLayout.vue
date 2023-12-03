<template>
	<Head>
		<title>
			{{ title }} - {{ appName }}
		</title>
	</Head>
	<div class="bg-white">

		<!-- mobile navbar -->
		<TransitionRoot as="template" :show="sidebarOpen">
			<Dialog as="div" class="relative z-40 md:hidden" @close="sidebarOpen = false">
				<TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0"
					enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
					leave-to="opacity-0">
					<div class="fixed inset-0 bg-gray-600 bg-opacity-75" />
				</TransitionChild>

				<div class="fixed inset-0 z-40 flex">
					<TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
						enter-from="-translate-x-full" enter-to="translate-x-0"
						leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
						leave-to="-translate-x-full">
						<DialogPanel class="relative flex w-full max-w-xs flex-1 flex-col bg-primary pt-5">
							<TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
								enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100"
								leave-to="opacity-0">
								<div class="absolute top-0 right-0 -mr-12 pt-2">
									<button type="button"
										class="ml-1 flex h-10 w-10 items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
										@click="sidebarOpen = false">
										<span class="sr-only">
											Close sidebar
										</span>
										<XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
									</button>
								</div>
							</TransitionChild>
							<div class="px-6 bg-primary overflow-y-auto gap-y-5 flex flex-col grow">
								<!-- logo -->
								<div class="flex items-center justify-center shrink-0 space-x-2">
									<img class="w-auto h-8"
										src="https://tailwindui.com/img/logos/mark.svg?color=indigo&amp;shade=500"
										alt="Loan System">
									<span class="text-neutral-light-grey">
										Loan System
									</span>
								</div>

								<!-- menu items -->
								<nav class="flex flex-1 flex-col">
									<ul class="flex flex-1 flex-col gap-y-7" role="list">
										<!-- normal items -->
										<li>
											<ul class="-mx-2 flex flex-1 flex-col gap-y-2" role="list">
												<li v-for="item in navigation">
													<Link :href="item.href"
														:class="['text-neutral-light-grey leading-6 font-semibold text-sm p-2 rounded-md flex items-center gap-x-3 hover:text-neutral-dark-grey hover:bg-neutral-light-grey', { '!text-neutral-dark-grey bg-neutral-light-grey': item.current }]">
													<component v-if="item.icon" :is="item.icon"
														:class="[item.current ? 'text-neutral-dark-grey' : '', 'mr-3 ml-1 flex-shrink-0 h-6 w-6']"
														aria-hidden="true" />
													{{ item.name }}
													</Link>
												</li>
											</ul>
										</li>
										<!-- admin items -->
										<li>
											<div class="text-neutral-light-grey font-semibold leading-6 text-sm">
												Admin Area
											</div>
											<ul class="-mx-2 flex flex-1 flex-col gap-y-2 mt-2" role="list">
												<li v-for="item in adminNavigation">
													<Link :href="item.href"
														:class="['text-neutral-light-grey leading-6 font-semibold text-sm p-2 rounded-md flex items-center gap-x-3 hover:text-neutral-dark-grey hover:bg-neutral-light-grey', { '!text-neutral-dark-grey bg-neutral-light-grey': item.current }]">
													<component v-if="item.icon" :is="item.icon"
														:class="[item.current ? 'text-neutral-dark-grey' : '', 'mr-3 ml-1 flex-shrink-0 h-6 w-6']"
														aria-hidden="true" />
													{{ item.name }}
													</Link>
												</li>
											</ul>
										</li>
										<!-- user profile section -->
										<div class="mt-auto -mx-6 hover:cursor-pointer" @click="toggleDropdown">
											<!-- dropdown items themselves -->
											<transition name="slide-fade">
												<div v-if="showDropdown"
													class="mx-6 w-48 mt-1 rounded-md shadow-lg bg-neutral-light-grey ring-1 ring-black ring-opacity-5 focus:outline-none">
													<div v-for="item in userNavigation" :key="item.name"
														class="text-neutral-dark-grey  hover:bg-gray-300 p-4">
														<Link :href="item.href" :method="item.method ?? 'get'"
															class="block text-sm text-neutral-grey-dark">
														{{ item.name }}
														</Link>
													</div>
												</div>
											</transition>

											<!-- user picture/name -->
											<div
												class="border-t border-t-neutral-light-grey text-neutral-light-grey hover:bg-neutral-light-grey hover:text-neutral-dark-grey">
												<div class="flex items-center text-sm focus:outline-none w-full">
													<span class="sr-only">Open user menu</span>
													<div
														class="leading-6 font-semibold text-sm py-3 px-6 gap-x-4 items-center flex">
														<img class="rounded-full w-8 h-8"
															src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80"
															alt="Profile Photo" />
														<span aria-hidden="true">Tom Cook</span>
													</div>
												</div>
											</div>
										</div>
									</ul>
								</nav>
							</div>
						</DialogPanel>
					</TransitionChild>
					<div class="w-14 flex-shrink-0" aria-hidden="true">
						<!-- dummy element to force sidebar to shrink to fit close icon -->
					</div>
				</div>
			</Dialog>
		</TransitionRoot>

		<!-- desktop navbar -->
		<div class="hidden md:flex md:flex-col md:w-72 md:z-50 md:top-0 md:bottom-0 md:fixed">
			<div class="px-6 bg-primary overflow-y-auto gap-y-5 flex flex-col grow pt-2">
				<!-- logo -->
				<div class="flex items-center justify-center shrink-0 space-x-2">
					<img class="w-auto h-8" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&amp;shade=500"
						alt="Loan System">
					<span class="text-neutral-light-grey">
						Loan System
					</span>
				</div>

				<!-- menu items -->
				<nav class="flex flex-1 flex-col">
					<ul class="flex flex-1 flex-col gap-y-7" role="list">
						<!-- normal items -->
						<li>
							<ul class="-mx-2 flex flex-1 flex-col gap-y-2" role="list">
								<li v-for="item in navigation">
									<Link :href="item.href"
										:class="['text-neutral-light-grey leading-6 font-semibold text-sm p-2 rounded-md flex items-center gap-x-3 hover:text-neutral-dark-grey hover:bg-neutral-light-grey', { '!text-neutral-dark-grey bg-neutral-light-grey': item.current }]">
									<component v-if="item.icon" :is="item.icon"
										:class="[item.current ? 'text-neutral-dark-grey' : '', 'mr-3 ml-1 flex-shrink-0 h-6 w-6']"
										aria-hidden="true" />
									{{ item.name }}
									</Link>
								</li>
							</ul>
						</li>
						<!-- admin items -->
						<li v-if="isAdmin">
							<div class="relative flex pt-2 items-center">
								<div class="flex-grow border-t border-neutral-light-grey"></div>
								<span class="flex-shrink mx-4 text-neutral-light-grey">Admin Area</span>
								<div class="flex-grow border-t border-neutral-light-grey"></div>
							</div>
							<ul class="-mx-2 flex flex-1 flex-col gap-y-2 mt-2" role="list">
								<li v-for="item in adminNavigation">
									<Link :href="item.href"
										:class="['text-neutral-light-grey leading-6 font-semibold text-sm p-2 rounded-md flex items-center gap-x-3 hover:text-neutral-dark-grey hover:bg-neutral-light-grey', { '!text-neutral-dark-grey bg-neutral-light-grey': item.current }]">
									<component v-if="item.icon" :is="item.icon"
										:class="[item.current ? 'text-neutral-dark-grey' : '', 'mr-3 ml-1 flex-shrink-0 h-6 w-6']"
										aria-hidden="true" />
									{{ item.name }}
									</Link>
								</li>
							</ul>
						</li>
						<!-- user profile section -->
						<div class="mt-auto -mx-6 hover:cursor-pointer" @click="toggleDropdown">
							<!-- dropdown items themselves -->
							<transition name="slide-fade">
								<div v-if="showDropdown"
									class="ml-2 w-48 mt-1 rounded-md shadow-lg bg-neutral-light-grey ring-1 ring-black ring-opacity-5 focus:outline-none">
									<div v-for="item in userNavigation" :key="item.name"
										class="text-neutral-dark-grey  hover:bg-gray-300 p-4">
										<Link :href="item.href" :method="item.method ?? 'get'"
											class="block text-sm text-neutral-grey-dark">
										{{ item.name }}
										</Link>
									</div>
								</div>
							</transition>

							<!-- user picture/name -->
							<div
								class="border-t border-t-neutral-light-grey text-neutral-light-grey hover:bg-neutral-light-grey hover:text-neutral-dark-grey">
								<div class="flex items-center text-sm focus:outline-none w-full">
									<span class="sr-only">
										Open user menu
									</span>
									<div class="leading-6 font-semibold text-sm py-3 px-6 gap-x-4 items-center flex">
										<img class="rounded-full w-8 h-8" :src="user.profile_photo_url"
											alt="Profile Photo" />
										<span aria-hidden="true">
											{{ user.name }}
										</span>
									</div>
								</div>
							</div>
						</div>
					</ul>
				</nav>
			</div>
		</div>

		<div class="md:pl-72">
			<!-- top row -->
			<div
				class="px-8 gap-x-6 shadow-sm bg-neutral-white border border-gray-200 items-center h-16 flex z-40 top-0 sticky">

				<!-- mobile button to open sidebar -->
				<button type="button"
					class="border-r border-gray-200 px-4 text-gray-700 focus:outline-none md:hidden -ml-5 mr-5"
					@click="sidebarOpen = true">
					<span class="sr-only">
						Open sidebar
					</span>
					<Bars3BottomLeftIcon class="h-6 w-6" aria-hidden="true" />
				</button>

				<!-- search/request a loan button -->
				<div class="gap-x-6 self-stretch flex-1 sm:flex sm:divide-x-2">
					<form class="hidden sm:flex flex-1 relative w-full">
						<input class="mt-2 text-sm text-neutral-dark-grey pr-0 pl-8 pt-0 border-0 w-full block focus:ring-0"
							type="search" placeholder="Search equipment..." name="search" />
						<MagnifyingGlassIcon class="h-full w-5 left-0 top-0 bottom-0 absolute" />
					</form>
					<div class="mt-2 sm:mt-0 gap-x-6 flex items-center pl-5">
						<div class="ml-auto">
							<AppButton>
								<template #iconRight>
									<PlusIcon />
								</template>
								Request a Loan
							</AppButton>
						</div>
					</div>
				</div>
			</div>

			<!-- page content -->
			<main>
				<div>
					<header
						class="bg-white shadow font-semibold text-lg text-gray-800 leading-tight py-6 px-9 border-b-2 border-b-secondary">
						<div v-if="breadcrumbs">
							<Breadcrumbs :breadcrumbs="breadcrumbs" />
						</div>
						<div v-else>
							{{ title }}
						</div>
					</header>

					<div class="p-5 bg-neutral-light-grey">
						<slot />
					</div>
				</div>
			</main>
		</div>
	</div>
</template>

<script setup>
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { ref, computed } from 'vue'
import { usePage, Head } from '@inertiajs/vue3'
import { HomeIcon, MagnifyingGlassIcon, Bars3BottomLeftIcon, XMarkIcon, UserGroupIcon, PlusIcon, Squares2X2Icon } from '@heroicons/vue/24/outline'
import { useSharedData } from "@/modules/sharedData.js";
import { Breadcrumbs } from '@/Components';

const {
	message,
	watchMessage
} = useSharedData()

const props = defineProps({
	breadcrumbs: Object,
	title: {
		type: String,
		default: 'Title',
	},
})

const navigation = [
	{ name: 'Dashboard', icon: HomeIcon, href: route('dashboard.index'), current: route().current('dashboard.index') },
];

const adminNavigation = [
	{ name: 'Dashboard', icon: HomeIcon, href: route('admin.dashboard.index'), current: route().current('admin.dashboard.index') },
	{ name: 'Categories', icon: Squares2X2Icon, href: route('admin.category.index'), current: route().current('admin.category.*') },
	{ name: 'Users', icon: UserGroupIcon, href: route('admin.user.index'), current: route().current('admin.user.*') },
]

const userNavigation = [
	{ name: 'Sign out', method: 'post', href: route('logout') },
	{ name: 'Your Profile', href: route('profile.show') },
]

const user = computed(() => usePage().props.auth.user);

const isAdmin = computed(() => usePage().props.isAdmin);

const sidebarOpen = ref(false);

const showDropdown = ref(false);

const toggleDropdown = () => {
	showDropdown.value = !showDropdown.value;
}

const appName = computed(() => import.meta.env.VITE_APP_NAME);
</script>

<style>
.slide-fade-enter-active,
.slide-fade-leave-active {
	transition: transform 0.2s, opacity 0.2s;
}

.slide-fade-enter,
.slide-fade-leave-to {
	transform: translate(-20px);
	opacity: 0;
}
</style>
