<template>
  <div class="group bg-white rounded-3xl overflow-hidden border border-slate-200/90 shadow-xs hover:shadow-2xl hover:border-amber-400/50 transition-all duration-300 transform hover:-translate-y-1.5 flex flex-col justify-between">
    <!-- Optional Wide Banner -->
    <div v-if="pengumuman.banner" class="w-full h-44 sm:h-52 bg-slate-100 overflow-hidden relative">
      <img 
        :src="pengumuman.banner" 
        :alt="pengumuman.judul" 
        class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-700 ease-out" 
        loading="lazy"
      />
      <!-- Light Sweep Reflection Effect on Hover -->
      <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out pointer-events-none z-10"></div>

      <div class="absolute top-3 right-3 flex items-center gap-1.5 z-20">
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
          <div 
            class="text-xs sm:text-sm text-slate-600 leading-relaxed mt-2 rich-content"
            v-html="pengumuman.isi"
          ></div>
        </div>
      </div>

      <!-- Footer / Attachment -->
      <div v-if="pengumuman.file" class="pt-3 border-t border-slate-100 flex items-center justify-between gap-3">
        <div class="flex items-center gap-2 text-xs text-emerald-900 font-semibold truncate min-w-0 flex-1">
          <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div class="truncate">
            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">Lampiran Dokumen</p>
            <p class="truncate text-xs font-bold text-slate-800">{{ getFileName(pengumuman) }}</p>
          </div>
        </div>
        <a 
          :href="getFileUrl(pengumuman)"
          :download="getDownloadName(pengumuman)"
          target="_blank"
          rel="noopener noreferrer"
          class="inline-flex items-center gap-1.5 text-xs font-bold px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white shadow-xs hover:shadow-md active:scale-95 transition-all duration-200 shrink-0 cursor-pointer"
          title="Unduh Berkas Resmi PDF"
        >
          <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
          <span>Unduh PDF</span>
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  pengumuman: {
    type: Object,
    required: true
  }
});

const getFileUrl = (p) => {
  if (!p || !p.file) return '#';
  if (p.file_url) return p.file_url;
  if (p.id) return `/api/pengumuman/${p.id}/unduh`;
  if (p.file.startsWith('http://') || p.file.startsWith('https://')) return p.file;
  if (p.file.startsWith('/')) return p.file;
  return `/storage/uploads/${p.file}`;
};

const getFileName = (p) => {
  if (!p || !p.file) return 'Dokumen.pdf';
  if (p.file_nama) return p.file_nama;
  const raw = p.file.split('/').pop()?.split('?')[0] || '';
  if (/^[A-Za-z0-9_-]{16,}\.pdf$/i.test(raw)) {
    return (p.judul ? p.judul.slice(0, 35) + '...' : 'Dokumen-Pengumuman') + '.pdf';
  }
  return raw || 'Dokumen.pdf';
};

const getDownloadName = (p) => {
  if (!p) return 'dokumen-pengumuman.pdf';
  const cleanTitle = (p.judul || 'pengumuman')
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '');
  return `${cleanTitle || 'pengumuman'}.pdf`;
};
</script>

<style scoped>
.rich-content :deep(p) {
  margin-bottom: 0.35rem;
}
.rich-content :deep(p:last-child) {
  margin-bottom: 0;
}
.rich-content :deep(ul) {
  list-style-type: disc;
  padding-left: 1.25rem;
  margin: 0.35rem 0;
}
.rich-content :deep(ol) {
  list-style-type: decimal;
  padding-left: 1.25rem;
  margin: 0.35rem 0;
}
.rich-content :deep(li) {
  margin-bottom: 0.15rem;
}
.rich-content :deep(a) {
  color: #047857;
  text-decoration: underline;
}
</style>
