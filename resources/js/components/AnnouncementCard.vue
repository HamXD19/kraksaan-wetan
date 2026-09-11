<template>
  <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-xs hover:shadow-md transition-all">
    <!-- Optional Wide Banner -->
    <div v-if="pengumuman.banner" class="w-full h-44 sm:h-52 bg-slate-100 overflow-hidden relative">
      <img 
        :src="pengumuman.banner" 
        :alt="pengumuman.judul" 
        class="w-full h-full object-cover object-center" 
        loading="lazy"
      />
      <div class="absolute top-3 right-3 flex items-center gap-1.5">
        <span 
          class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider shadow-xs"
          :class="pengumuman.prioritas === 'Penting' ? 'bg-rose-600 text-white' : 'bg-amber-500 text-slate-950'"
        >
          {{ pengumuman.prioritas || 'Pengumuman' }}
        </span>
      </div>
    </div>

    <div class="p-5 sm:p-6 space-y-4">
      <!-- Meta Information -->
      <div class="flex flex-wrap items-center justify-between gap-2">
        <div class="flex items-center gap-2">
          <span 
            v-if="!pengumuman.banner"
            class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider"
            :class="pengumuman.prioritas === 'Penting' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800'"
          >
            {{ pengumuman.prioritas || 'Pengumuman' }}
          </span>
          <span v-if="pengumuman.kategori" class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wider">
            {{ pengumuman.kategori }}
          </span>
          <span class="text-xs text-slate-500 font-medium flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ pengumuman.tanggal }}
          </span>
        </div>

        <span v-if="pengumuman.penyelenggara" class="text-xs text-slate-500 font-medium bg-slate-100 px-2.5 py-0.5 rounded-md">
          {{ pengumuman.penyelenggara }}
        </span>
      </div>

      <!-- Title & Thumbnail Section -->
      <div class="flex items-start gap-4">
        <img 
          v-if="pengumuman.thumbnail && !pengumuman.banner" 
          :src="pengumuman.thumbnail" 
          :alt="pengumuman.judul" 
          class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border border-slate-200 shrink-0 bg-slate-100"
          loading="lazy"
        />
        <div class="flex-1 min-w-0">
          <h4 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
            {{ pengumuman.judul }}
          </h4>
          <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-2 whitespace-pre-line">
            {{ pengumuman.isi }}
          </p>
        </div>
      </div>

      <!-- Footer / Attachment -->
      <div v-if="pengumuman.file" class="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
        <div class="flex items-center gap-2 text-xs text-emerald-800 font-semibold truncate">
          <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span class="truncate">Lampiran Dokumen: {{ pengumuman.file }}</span>
        </div>
        <button 
          @click="unduhPengumuman(pengumuman.file)"
          class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 transition shrink-0"
        >
          Unduh PDF
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  pengumuman: {
    type: Object,
    required: true
  }
});

const unduhPengumuman = (filename) => {
  alert(`Mengunduh dokumen: ${filename}\nDokumen resmi Kelurahan Kraksaan Wetan.`);
};
</script>
