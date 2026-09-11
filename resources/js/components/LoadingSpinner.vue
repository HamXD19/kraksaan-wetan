<template>
  <div class="flex flex-col items-center justify-center py-12 px-4" role="status">
    <div class="relative w-12 h-12">
      <div class="w-12 h-12 rounded-full border-4 border-emerald-100 border-t-emerald-600 animate-spin"></div>
      <div class="absolute inset-0 flex items-center justify-center">
        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
      </div>
    </div>
    <p v-if="text" class="mt-3 text-xs font-medium text-slate-500 animate-pulse">{{ text }}</p>
    <span class="sr-only">Memuat data...</span>
  </div>
</template>

<script setup>
defineProps({
  text: {
    type: String,
    default: 'Memuat data informasi kelurahan...'
  }
});
</script>
