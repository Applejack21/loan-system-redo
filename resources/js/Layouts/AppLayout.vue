<template>
  <Head :title="title" />
  <div class="min-h-full mb-40">
    <Disclosure as="nav" class="bg-primary border-b border-primary sticky top-0 z-50" v-slot="{ open }">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="hidden sm:-my-px sm:flex sm:space-x-8">
              <Link v-for="item in navigation" :key="item.name" :href="item.href" :class="[item.current ? 'border-white-500 text-white' : 'border-transparent text-white hover:border-secondary-light hover:text-white', 'inline-flex items-center px-1 pt-1 border-b-4 text-lg font-medium']" :aria-current="item.current ? 'page' : undefined">
                {{ item.name }}
              </Link>
            </div>
          </div>
          <div class="-mr-2 flex items-center sm:hidden">
            
            <!-- Mobile menu button -->
            <DisclosureButton class="bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
              <span class="sr-only">Open main menu</span>
              <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
              <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
            </DisclosureButton>
          </div>
        </div>
      </div>

      <!-- Mobile menu links -->
      <DisclosurePanel class="sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
          <Link v-for="item in navigation" :key="item.name" as="a" :href="item.href" :class="[item.current ? 'bg-secondary border-white-500 text-white' : 'border-transparent text-white hover:bg-secondary hover:text-white', 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium']" :aria-current="item.current ? 'page' : undefined">
            {{ item.name }}
          </Link>
        </div>
      </DisclosurePanel>
      
    </Disclosure>

    <div>
        <!-- Page Heading -->
        <header v-if="$slots.header" class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative">
            <slot name="main"/>
        </main>
    </div>
  </div>
</template>

<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import { ref, computed, defineComponent } from "vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  title: String,
});

const navigation = ref([
  { name: 'Homepage', href: route('dashboard'), current: route().current('dashboard') },
]);

</script>
