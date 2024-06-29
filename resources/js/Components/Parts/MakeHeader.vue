<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  backUrl: {
    type: String,
    default: 'dashboard',
  },
  addUrl: {
    type: String,
  }
});

</script>

<template>
    <header class="bg-white shadow">
        <div class="flex justify-between max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

          <!-- back link -->
          <Link :href="route(backUrl)">
            <i class="fa-solid fa-chevron-left fa-xl"></i>
          </Link>

          <!-- タイトル -->
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              <slot />
          </h2>

          <!-- make page link -->
          <Link v-if="(addUrl)" :href="route(addUrl)">
            <i class="fa-solid fa-plus fa-xl"></i>
          </Link>
          <div v-else class="dammy"></div>
        </div>
    </header>
</template>

<style scoped>
  .dammy { width: 15px; }
</style>