<template>
  <div>
    <!-- Category Filter Bar -->
    <div v-if="showFilter" class="flex flex-wrap items-center justify-center gap-2 mb-8">
      <button 
        v-for="kat in categories" 
        :key="kat"
        @click="selectedCategory = kat"
        class="px-4 py-2 rounded-xl text-xs font-semibold transition-all"
        :class="selectedCategory === kat 
          ? 'bg-emerald-700 text-white shadow-md shadow-emerald-700/20' 
          : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
      >
        {{ kat }}
      </button>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="item in filteredItems" 
        :key="item.id"
        @click="openLightbox(item)"
        class="group relative h-64 rounded-2xl overflow-hidden cursor-pointer shadow-xs hover:shadow-xl border border-slate-200 transition-all duration-300 transform hover:-translate-y-1"
      >
        <img 
          :src="item.gambar" 
          :alt="item.judul"
          class="w-full h-full object-cover object-center group-hover:scale-110 transition-transform duration-500 ease-out"
          loading="lazy"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/30 to-transparent opacity-80 group-hover:opacity-95 transition-opacity"></div>
        
        <!-- Category Pill -->
        <div class="absolute top-3 left-3">
          <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-700/90 text-white shadow-xs backdrop-blur-xs">
            {{ item.kategori }}
          </span>
        </div>

        <!-- Content Overlay -->
        <div class="absolute bottom-0 inset-x-0 p-5 text-white">
          <p class="text-[11px] text-emerald-300 font-medium mb-1">{{ item.tanggal }}</p>
          <h4 class="text-sm sm:text-base font-bold leading-snug group-hover:text-amber-300 transition-colors">
            {{ item.judul }}
          </h4>
          <p class="text-xs text-slate-300 line-clamp-2 mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            {{ item.deskripsi }}
          </p>
        </div>

        <!-- Zoom Icon Indicator -->
        <div class="absolute top-3 right-3 w-8 h-8 rounded-full bg-white/20 backdrop-blur-xs text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="filteredItems.length === 0" class="text-center py-12 bg-white rounded-2xl border border-slate-200">
      <p class="text-slate-500 text-sm">Tidak ada foto kegiatan pada kategori ini.</p>
    </div>

    <!-- Lightbox Modal -->
    <div 
      v-if="activeLightboxItem" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/85 backdrop-blur-sm transition-all"
      @click.self="closeLightbox"
    >
      <div class="relative max-w-4xl w-full bg-slate-900 rounded-2xl overflow-hidden shadow-2xl border border-slate-700 text-white">
        <!-- Close Button -->
        <button 
          @click="closeLightbox"
          class="absolute top-4 right-4 z-10 p-2 rounded-full bg-black/60 text-white hover:bg-black/90 transition"
          aria-label="Tutup"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Large Image -->
        <div class="max-h-[70vh] bg-black flex items-center justify-center overflow-hidden">
          <img 
            :src="activeLightboxItem.gambar" 
            :alt="activeLightboxItem.judul"
            class="max-h-[70vh] w-auto object-contain"
          />
        </div>

        <!-- Lightbox Caption -->
        <div class="p-6 bg-slate-900 border-t border-slate-800">
          <div class="flex items-center gap-3 mb-2">
            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-800 text-emerald-200">
              {{ activeLightboxItem.kategori }}
            </span>
            <span class="text-xs text-slate-400">{{ activeLightboxItem.tanggal }}</span>
          </div>
          <h3 class="text-lg font-bold text-white mb-2">{{ activeLightboxItem.judul }}</h3>
          <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">{{ activeLightboxItem.deskripsi }}</p>
        </div>
      </div>
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

const selectedCategory = ref('Semua');
const activeLightboxItem = ref(null);

const categories = computed(() => {
  const cats = ['Semua'];
  props.items.forEach(i => {
    if (i.kategori && !cats.includes(i.kategori)) {
      cats.push(i.kategori);
    }
  });
  return cats;
});

const filteredItems = computed(() => {
  if (selectedCategory.value === 'Semua') return props.items;
  return props.items.filter(i => i.kategori === selectedCategory.value);
});

const openLightbox = (item) => {
  activeLightboxItem.value = item;
};

const closeLightbox = () => {
  activeLightboxItem.value = null;
};
</script>
