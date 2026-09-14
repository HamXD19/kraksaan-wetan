<template>
  <div class="group relative bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:shadow-2xl hover:border-emerald-500/50 transition-all duration-300 flex flex-col justify-between transform hover:-translate-y-1.5 overflow-hidden">
    <!-- Top Accent Line on Hover -->
    <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-emerald-500 to-amber-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

    <div>
      <!-- Icon Container with Subtle Glow -->
      <div class="w-14 h-14 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center mb-5 group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-110 group-hover:shadow-md group-hover:shadow-emerald-600/30 transition-all duration-300 shadow-inner">
        <!-- Dynamic SVG Icons based on type -->
        <svg v-if="layanan.icon === 'Users'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
        <svg v-else-if="layanan.icon === 'Home'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <svg v-else-if="layanan.icon === 'Briefcase'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <svg v-else-if="layanan.icon === 'HeartHandshake'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        <svg v-else-if="layanan.icon === 'ShieldCheck'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
        <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>

      <!-- Badge Kategori -->
      <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 mb-2">
        {{ layanan.kategori }}
      </span>

      <!-- Title & Description -->
      <h3 class="text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors mb-2">
        {{ layanan.judul }}
      </h3>
      <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-3">
        {{ layanan.deskripsi }}
      </p>
    </div>

    <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
      <span class="text-[11px] text-emerald-700 font-semibold bg-emerald-50 px-2.5 py-1 rounded-md">
        Biaya: {{ layanan.biaya || 'Gratis' }}
      </span>
      <router-link 
        :to="{ path: '/pelayanan', query: { item: layanan.slug } }"
        class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 hover:text-emerald-900 group-hover:translate-x-1 active:scale-95 transition-all duration-200"
      >
        Lihat Detail
        <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
      </router-link>
    </div>
  </div>
</template>

<script setup>
defineProps({
  layanan: {
    type: Object,
    required: true
  }
});
</script>
