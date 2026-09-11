<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Berita & Pengumuman' }]" />

    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Warta Kraksaan Wetan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Pusat Berita & Pengumuman</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl">
          Publikasi resmi seputar agenda kegiatan kelurahan, kebijakan pemerintah, giat masyarakat, dan pengumuman kedinasan.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <!-- Search & Tab Bar -->
      <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-xs mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Main Tab Switcher -->
        <div class="flex items-center gap-2 w-full md:w-auto">
          <button 
            @click="activeMainTab = 'berita'" 
            class="flex-1 md:flex-initial px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all"
            :class="activeMainTab === 'berita' 
              ? 'bg-emerald-700 text-white shadow-sm' 
              : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            Berita Kelurahan
          </button>
          <button 
            @click="activeMainTab = 'pengumuman'" 
            class="flex-1 md:flex-initial px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold transition-all"
            :class="activeMainTab === 'pengumuman' 
              ? 'bg-amber-500 text-slate-950 shadow-sm' 
              : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            Pengumuman Resmi
          </button>
        </div>

        <!-- Search Bar -->
        <div class="w-full md:w-72 relative">
          <input 
            type="text" 
            v-model="searchQuery" 
            @input="handleSearch"
            placeholder="Cari kata kunci..." 
            class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 focus:bg-white"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <!-- Categories for Berita -->
      <div v-if="activeMainTab === 'berita'" class="flex flex-wrap items-center gap-2 mb-8">
        <button 
          v-for="k in kategoriList" 
          :key="k"
          @click="selectCategory(k)"
          class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition"
          :class="selectedCategory === k 
            ? 'bg-emerald-800 text-white' 
            : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50'"
        >
          {{ k }}
        </button>
      </div>

      <!-- Content Views -->
      <LoadingSpinner v-if="loading" />
      <template v-else>
        <!-- Berita List -->
        <div v-if="activeMainTab === 'berita'">
          <div v-if="beritaList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <NewsCard 
              v-for="b in beritaList" 
              :key="b.id" 
              :berita="b" 
            />
          </div>
          <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200">
            <p class="text-slate-500 text-sm">Tidak ada berita yang sesuai dengan pencarian Anda.</p>
          </div>
        </div>

        <!-- Pengumuman List -->
        <div v-else class="space-y-4 max-w-4xl mx-auto">
          <div v-if="pengumumanList.length > 0">
            <div class="space-y-4">
              <AnnouncementCard 
                v-for="p in pengumumanList" 
                :key="p.id" 
                :pengumuman="p" 
              />
            </div>
          </div>
          <div v-else class="text-center py-16 bg-white rounded-2xl border border-slate-200">
            <p class="text-slate-500 text-sm">Tidak ada pengumuman saat ini.</p>
          </div>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Breadcrumb from '../components/Breadcrumb.vue';
import NewsCard from '../components/NewsCard.vue';
import AnnouncementCard from '../components/AnnouncementCard.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const route = useRoute();
const loading = ref(true);
const activeMainTab = ref(route.query.tab === 'pengumuman' ? 'pengumuman' : 'berita');
const searchQuery = ref('');
const selectedCategory = ref('Semua');

const beritaList = ref([]);
const pengumumanList = ref([]);
const kategoriList = ref(['Semua', 'Pemerintahan', 'Masyarakat', 'Kesehatan']);

const fetchData = async () => {
  loading.value = true;
  try {
    const [b, pg] = await Promise.all([
      KelurahanService.getBerita({
        kategori: selectedCategory.value,
        search: searchQuery.value
      }),
      KelurahanService.getPengumuman()
    ]);
    beritaList.value = b;
    pengumumanList.value = pg;
  } finally {
    loading.value = false;
  }
};

const selectCategory = (cat) => {
  selectedCategory.value = cat;
  fetchData();
};

let searchTimer = null;
const handleSearch = () => {
  clearTimeout(searchTimer);
  searchTimer = setTimeout(() => {
    fetchData();
  }, 300);
};

onMounted(async () => {
  fetchData();
  try {
    const kats = await KelurahanService.getKategori('berita');
    if (kats && kats.length) {
      kategoriList.value = ['Semua', ...kats.map(k => k.nama)];
    }
  } catch (e) {
    // fallback
  }
});
</script>
