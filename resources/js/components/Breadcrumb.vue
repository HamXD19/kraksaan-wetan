<template>
  <nav aria-label="Breadcrumb" class="bg-emerald-50/70 border-b border-emerald-100 py-3 px-4">
    <div class="max-w-7xl mx-auto flex items-center space-x-2 text-xs text-slate-600">
      <router-link to="/" class="flex items-center gap-1 hover:text-emerald-700 font-medium transition">
        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>Beranda</span>
      </router-link>
      <template v-for="(crumb, idx) in items" :key="idx">
        <span class="text-slate-400">/</span>
        <router-link 
          v-if="crumb.to && idx !== items.length - 1" 
          :to="crumb.to" 
          class="hover:text-emerald-700 font-medium transition"
        >
          {{ crumb.label }}
        </router-link>
        <span v-else class="text-emerald-900 font-semibold truncate max-w-xs md:max-w-md">
          {{ crumb.label }}
        </span>
      </template>
    </div>
  </nav>
</template>

<script setup>
defineProps({
  items: {
    type: Array,
    default: () => []
  }
});
</script>
