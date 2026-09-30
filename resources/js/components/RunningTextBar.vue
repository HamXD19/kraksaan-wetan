<template>
  <div 
    v-if="runningItems.length && !dismissed" 
    class="bg-emerald-950 text-white border-b border-emerald-800/80 text-xs py-2 px-3 sm:px-6 relative z-30 overflow-hidden flex items-center shadow-xs"
  >
    <!-- Left Badge: WARTA TERKINI -->
    <div class="flex items-center gap-2 shrink-0 pr-3 sm:pr-4 border-r border-emerald-800/80 bg-emerald-950 z-10">
      <span class="relative flex h-2 w-2">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
      </span>
      <span class="font-black uppercase tracking-wider text-[10px] sm:text-[11px] text-amber-300 whitespace-nowrap">
        Warta Terkini
      </span>
    </div>

    <!-- Scrolling Text Ticker -->
    <div 
      class="marquee-wrapper flex-1 overflow-hidden whitespace-nowrap px-3 cursor-pointer"
      @mouseenter="isPaused = true"
      @mouseleave="isPaused = false"
    >
      <div 
        class="marquee-content inline-flex items-center gap-8"
        :class="{ 'marquee-paused': isPaused }"
      >
        <!-- Loop items twice for smooth infinite loop -->
        <template v-for="loop in [1, 2]" :key="'loop-' + loop">
          <router-link
            v-for="item in runningItems"
            :key="'rt-' + loop + '-' + item.id"
            :to="'/berita/' + item.slug"
            class="inline-flex items-center gap-2 text-emerald-100 hover:text-amber-300 transition-colors group"
          >
            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-800 text-emerald-200 uppercase">
              {{ item.kategori }}
            </span>
            <span class="font-medium text-xs group-hover:underline">
              {{ item.judul }}
            </span>
            <span class="text-emerald-600 font-bold ml-2">&bull;</span>
          </router-link>
        </template>
      </div>
    </div>

    <!-- Close Button -->
    <button 
      type="button" 
      @click="dismissed = true" 
      class="text-emerald-400 hover:text-white p-1 rounded-lg ml-2 shrink-0 cursor-pointer transition"
      title="Sembunyikan Warta Terkini"
    >
      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { KelurahanService } from '../services/api';

const runningItems = ref([]);
const isPaused = ref(false);
const dismissed = ref(false);

const loadRunningText = async () => {
  try {
    const res = await KelurahanService.getRunningText();
    if (Array.isArray(res) && res.length > 0) {
      runningItems.value = res;
    } else if (res?.data && Array.isArray(res.data)) {
      runningItems.value = res.data;
    }
  } catch (err) {
    console.warn('Gagal memuat running text warta:', err);
  }
};

onMounted(loadRunningText);
</script>

<style scoped>
.marquee-wrapper {
  mask-image: linear-gradient(to right, transparent, black 2%, black 98%, transparent);
}

.marquee-content {
  display: inline-flex;
  animation: scrollMarquee 35s linear infinite;
}

.marquee-content:hover,
.marquee-paused {
  animation-play-state: paused;
}

@keyframes scrollMarquee {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}
</style>
