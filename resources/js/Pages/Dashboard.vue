<template>
  <AppLayout :title="pageName">
    <template #header>
      <h2 class="font-semibold text-lg text-gray-800 leading-tight">
        Homepage
      </h2>
    </template>
    <template #main>
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="px-4 py-8 sm:px-0">
          Lorem Ipsum.
        </div>
      </div>
    </template>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";

const props = defineProps({});

const pageName = "Homepage";
</script> 