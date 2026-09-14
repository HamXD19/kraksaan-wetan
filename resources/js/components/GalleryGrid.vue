<template>
  <div>
    <!-- Media Type Tabs & Category Filter Bar -->
    <div v-if="showFilter" class="space-y-4 mb-8">
      <!-- Media Type Pill Switcher -->
      <div class="flex items-center justify-center">
        <div class="inline-flex p-1.5 rounded-2xl bg-white border border-slate-200 shadow-xs gap-1">
          <button
            type="button"
            @click="setType('semua')"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 cursor-pointer"
            :class="selectedType === 'semua'
              ? 'bg-slate-900 text-white shadow-sm'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <span>Semua Media</span>
            <span class="text-[10px] px-1.5 py-0.5 rounded-full" :class="selectedType === 'semua' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ items.length }}</span>
          </button>

          <button
            type="button"
            @click="setType('foto')"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 cursor-pointer"
            :class="selectedType === 'foto'
              ? 'bg-emerald-700 text-white shadow-sm'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Foto Kegiatan</span>
            <span class="text-[10px] px-1.5 py-0.5 rounded-full" :class="selectedType === 'foto' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ fotoCount }}</span>
          </button>

          <button
            type="button"
            @click="setType('video')"
            class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 flex items-center gap-1.5 cursor-pointer"
            :class="selectedType === 'video'
              ? 'bg-rose-600 text-white shadow-sm'
              : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'"
          >
            <svg class="w-3.5 h-3.5 text-rose-500" :class="selectedType === 'video' ? 'text-white' : ''" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
            <span>Video Dokumentasi</span>
            <span class="text-[10px] px-1.5 py-0.5 rounded-full" :class="selectedType === 'video' ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500'">{{ videoCount }}</span>
          </button>
        </div>
      </div>

      <!-- Category Filter Pills -->
      <div v-if="categories.length > 1" class="flex flex-wrap items-center justify-center gap-2">
        <button 
          v-for="kat in categories" 
          :key="kat"
          @click="selectedCategory = kat"
          class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition-all duration-200 active:scale-95 cursor-pointer"
          :class="selectedCategory === kat 
            ? 'bg-emerald-700 text-white shadow-md shadow-emerald-700/20 scale-105' 
            : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200 hover:border-slate-300'"
        >
          {{ kat }}
        </button>
      </div>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <router-link 
        v-for="(item, index) in filteredItems" 
        :key="item.id"
        :to="`/galeri/${item.id}`"
        :style="{ animationDelay: `${(index % 9) * 70}ms` }"
        class="animate-fade-in-up group relative h-64 rounded-2xl overflow-hidden cursor-pointer shadow-xs hover:shadow-2xl border border-slate-200/90 hover:border-emerald-500/40 transition-all duration-300 transform hover:-translate-y-2 block"
      >
        <img 
          :src="item.gambar" 
          :alt="item.judul"
          class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-700 ease-out"
          loading="lazy"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-300"></div>

        <!-- Light Sweep Reflection Effect on Hover -->
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out pointer-events-none z-10"></div>
        
        <!-- Category Pill -->
        <div class="absolute top-3 left-3 z-20">
          <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-700/90 text-white shadow-xs backdrop-blur-xs group-hover:bg-emerald-600 transition-colors">
            {{ item.kategori }}
          </span>
        </div>

        <!-- Video Badge Top Right -->
        <div v-if="item.tipe === 'video'" class="absolute top-3 right-3 z-20">
          <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-rose-600/95 text-white shadow-sm backdrop-blur-xs flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
            <span>Video</span>
          </span>
        </div>

        <!-- Center Play Button Overlay for Videos -->
        <div v-if="item.tipe === 'video'" class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
          <div class="w-12 h-12 rounded-full bg-rose-600/90 text-white flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:bg-rose-600 transition-all duration-300">
            <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
          </div>
        </div>

        <!-- Content Overlay -->
        <div class="absolute bottom-0 inset-x-0 p-5 text-white z-20">
          <p class="text-[11px] text-emerald-300 font-medium mb-1 flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full" :class="item.tipe === 'video' ? 'bg-rose-400' : 'bg-emerald-400'"></span>
            <span>{{ item.tanggal }}</span>
            <span v-if="item.tipe === 'video'" class="text-[10px] text-rose-300 font-bold ml-1">• Video Dokumentasi</span>
          </p>
          <h4 class="text-sm sm:text-base font-bold leading-snug group-hover:text-amber-300 transition-colors duration-200">
            {{ item.judul }}
          </h4>
          <p class="text-xs text-slate-300 line-clamp-2 mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            {{ item.deskripsi }}
          </p>
        </div>

        <!-- Detail Indicator Arrow (for photos) -->
        <div v-if="item.tipe !== 'video'" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/20 backdrop-blur-xs text-white flex items-center justify-center opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-300 z-20 shadow-md">
          <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </div>
      </router-link>
    </div>

    <!-- Empty State -->
    <div v-if="filteredItems.length === 0" class="text-center py-12 bg-white rounded-2xl border border-slate-200">
      <p class="text-slate-500 text-sm">Tidak ada dokumentasi kegiatan pada kategori ini.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  items: {
    type: Array,
    required: true
  },
  showFilter: {
    type: Boolean,
    default: true
  }
});

const selectedType = ref('semua');
const selectedCategory = ref('Semua');

const setType = (type) => {
  selectedType.value = type;
  selectedCategory.value = 'Semua';
};

const fotoCount = computed(() => props.items.filter(i => (i.tipe || 'foto') === 'foto').length);
const videoCount = computed(() => props.items.filter(i => i.tipe === 'video').length);

const categories = computed(() => {
  const cats = ['Semua'];
  const pool = selectedType.value === 'semua'
    ? props.items
    : props.items.filter(i => (i.tipe || 'foto') === selectedType.value);

  pool.forEach(i => {
    if (i.kategori && !cats.includes(i.kategori)) {
      cats.push(i.kategori);
    }
  });
  return cats;
});

const filteredItems = computed(() => {
  return props.items.filter(item => {
    const matchType = selectedType.value === 'semua' || (item.tipe || 'foto') === selectedType.value;
    const matchCat = selectedCategory.value === 'Semua' || item.kategori === selectedCategory.value;
    return matchType && matchCat;
  });
});
</script>
