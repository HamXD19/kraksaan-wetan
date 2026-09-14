<template>
  <article class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 shadow-xs hover:shadow-2xl hover:border-emerald-400/50 transition-all duration-300 flex flex-col group transform hover:-translate-y-1.5">
    <!-- Thumbnail Image -->
    <div class="relative h-48 sm:h-52 w-full overflow-hidden bg-slate-100">
      <img 
        :src="berita.gambar || 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80'" 
        :alt="berita.judul"
        class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 ease-out"
        loading="lazy"
      />
      <!-- Light Sweep Reflection Effect on Hover -->
      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out pointer-events-none z-10"></div>

      <div class="absolute top-3 left-3 z-20">
        <span class="px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wide bg-emerald-700/90 text-white shadow-xs backdrop-blur-xs group-hover:bg-emerald-600 transition-colors">
          {{ berita.kategori }}
        </span>
      </div>
    </div>

    <!-- Content Body -->
    <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
      <div>
        <!-- Date & Author Meta -->
        <div class="flex items-center gap-3 text-slate-400 text-xs mb-2.5">
          <span class="flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ berita.tanggal }}
          </span>
          <span>&bull;</span>
          <span class="truncate">{{ berita.penulis || 'Kelurahan' }}</span>
        </div>

        <!-- Title -->
        <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors line-clamp-2 leading-snug mb-2">
          <router-link :to="`/berita/${berita.slug}`">
            {{ berita.judul }}
          </router-link>
        </h3>

        <!-- Excerpt -->
        <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed mb-4">
          {{ berita.ringkasan }}
        </p>
      </div>

      <!-- Action Link -->
      <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
        <span class="text-[11px] text-slate-400 flex items-center gap-1">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          {{ berita.dilihat || 120 }} dibaca
        </span>
        <router-link 
          :to="`/berita/${berita.slug}`"
          class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition-all group-hover:translate-x-1 duration-200 active:scale-95"
        >
          Baca Selengkapnya
          <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </router-link>
      </div>
    </div>
  </article>
</template>

<script setup>
defineProps({
  berita: {
    type: Object,
    required: true
  }
});
</script>
