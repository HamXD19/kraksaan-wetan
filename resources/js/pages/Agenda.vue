<template>
  <div class="pb-16 min-h-screen">
    <!-- Breadcrumb Navigasi -->
    <Breadcrumb :items="[
      { label: 'Informasi Publik', to: '/informasi-publik' },
      { label: 'Agenda Kegiatan' }
    ]" />

    <!-- Hero Banner Header Dinamis -->
    <HeroPageHeader 
      badge-text="Kalender & Jadwal Resmi"
      title="Agenda Kegiatan Kelurahan"
      description="Jadwal kegiatan pemerintahan, musyawarah warga, posyandu balita & lansia, bakti sosial, serta agenda kemasyarakatan Kelurahan Kraksaan Wetan."
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
      <!-- Toolbar: Navigasi Tab & Pencarian -->
      <div class="bg-white rounded-3xl p-4 sm:p-6 border border-slate-200/80 shadow-xs space-y-4 reveal">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <!-- Tab Filter: Aktif & Mendatang vs Riwayat Selesai -->
          <div class="inline-flex p-1.5 rounded-2xl bg-slate-100/90 border border-slate-200/60 max-w-fit">
            <button 
              type="button" 
              @click="changeTab(false)" 
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
              :class="!isRiwayat ? 'bg-white text-emerald-800 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            >
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>Agenda Aktif & Mendatang</span>
              <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold" :class="!isRiwayat ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'">
                {{ counts.aktif || 0 }}
              </span>
            </button>
            <button 
              type="button" 
              @click="changeTab(true)" 
              class="px-4 py-2 rounded-xl text-xs sm:text-sm font-bold transition-all flex items-center gap-2 cursor-pointer"
              :class="isRiwayat ? 'bg-white text-slate-800 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            >
              <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span>Riwayat Agenda Selesai</span>
              <span class="px-2 py-0.5 rounded-full text-[11px] font-extrabold" :class="isRiwayat ? 'bg-slate-200 text-slate-800' : 'bg-slate-200 text-slate-600'">
                {{ counts.riwayat || 0 }}
              </span>
            </button>
          </div>

          <!-- Keterangan Otomatis Hilang -->
          <div class="text-xs text-slate-500 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
            <span v-if="!isRiwayat">
              Agenda yang telah melewati batas tanggal selesai secara otomatis berpindah ke arsip riwayat.
            </span>
            <span v-else>
              Menampilkan arsip kegiatan kelurahan yang telah terlaksana sebelumnya.
            </span>
          </div>
        </div>

        <!-- Filter Bar: Search & Kategori -->
        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 pt-2 border-t border-slate-100">
          <div class="sm:col-span-8 lg:col-span-9 relative">
            <input 
              type="text" 
              v-model="searchQuery" 
              @input="handleSearch"
              placeholder="Cari judul kegiatan, lokasi tempat acara, atau kata kunci..." 
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-xs sm:text-sm bg-slate-50/50"
            />
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <button 
              v-if="searchQuery" 
              @click="searchQuery = ''; loadAgenda()" 
              class="absolute right-3 top-3 text-slate-400 hover:text-slate-600 cursor-pointer"
            >
              &times;
            </button>
          </div>

          <div class="sm:col-span-4 lg:col-span-3">
            <select 
              v-model="selectedKategori" 
              @change="loadAgenda"
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 focus:border-emerald-600 outline-none text-xs sm:text-sm bg-white font-medium cursor-pointer"
            >
              <option value="Semua">Semua Kategori</option>
              <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Loading State -->
      <LoadingSpinner v-if="loading" />

      <!-- Daftar Kartu Agenda Kegiatan -->
      <div v-else-if="agendaList.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="item in agendaList" 
          :key="item.id"
          class="bg-white rounded-3xl border border-slate-200/90 shadow-xs hover:shadow-lg transition-all duration-300 flex flex-col justify-between overflow-hidden group hover:border-emerald-300"
        >
          <div>
            <!-- Banner / Foto Kegiatan -->
            <router-link :to="`/agenda/${item.slug}`" class="block relative h-48 sm:h-52 bg-slate-100 overflow-hidden">
              <img 
                v-if="item.foto" 
                :src="item.foto" 
                :alt="item.judul"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
              />
              <div 
                v-else 
                class="w-full h-full bg-gradient-to-br from-emerald-900 to-emerald-950 flex items-center justify-center text-emerald-300/40 p-6"
              >
                <div class="text-center space-y-2">
                  <svg class="w-12 h-12 mx-auto text-emerald-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                  </svg>
                  <span class="text-xs font-bold text-emerald-200/60 uppercase tracking-widest block">Agenda Kelurahan</span>
                </div>
              </div>

              <!-- Lencana Kategori di Atas Foto -->
              <div class="absolute top-3 left-3">
                <span class="px-2.5 py-1 rounded-xl text-[11px] font-extrabold uppercase tracking-wider bg-slate-900/80 backdrop-blur-md text-white shadow-xs">
                  {{ item.kategori || 'Umum' }}
                </span>
              </div>

              <!-- Lencana Status Realtime di Atas Foto -->
              <div class="absolute top-3 right-3">
                <span 
                  v-if="item.status_agenda === 'berlangsung'" 
                  class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-emerald-500 text-white shadow-xs flex items-center gap-1.5"
                >
                  <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                  Sedang Berlangsung
                </span>
                <span 
                  v-else-if="item.status_agenda === 'akan_datang'" 
                  class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-blue-600 text-white shadow-xs flex items-center gap-1.5"
                >
                  <svg class="w-3 h-3 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Akan Datang
                </span>
                <span 
                  v-else 
                  class="px-2.5 py-1 rounded-xl text-[11px] font-bold bg-slate-700/90 backdrop-blur-md text-slate-200 shadow-xs"
                >
                  Telah Selesai
                </span>
              </div>
            </router-link>

            <!-- Konten Kartu -->
            <div class="p-5 sm:p-6 space-y-3.5">
              <!-- Tanggal & Waktu Acara -->
              <div class="flex items-start gap-2.5 text-xs text-emerald-800 font-bold bg-emerald-50/80 p-2.5 rounded-xl border border-emerald-100">
                <svg class="w-4 h-4 text-emerald-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <div class="leading-tight">
                  <span class="block">{{ item.formatted_jadwal }}</span>
                </div>
              </div>

              <!-- Judul Kegiatan -->
              <router-link :to="`/agenda/${item.slug}`" class="block">
                <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition line-clamp-2 leading-snug">
                  {{ item.judul }}
                </h3>
              </router-link>

              <!-- Metadata Lokasi & Penyelenggara -->
              <div class="space-y-1.5 text-xs text-slate-500">
                <div class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  <span class="truncate font-medium text-slate-700">{{ item.lokasi || 'Wilayah Kelurahan Kraksaan Wetan' }}</span>
                </div>
                <div v-if="item.penyelenggara" class="flex items-center gap-2">
                  <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                  <span class="truncate text-slate-600">{{ item.penyelenggara }}</span>
                </div>
              </div>

              <!-- Cuplikan Deskripsi -->
              <div 
                class="text-xs text-slate-600 line-clamp-2 leading-relaxed"
                v-html="stripHtml(item.deskripsi)"
              ></div>
            </div>
          </div>

          <!-- Footer Kartu: Tombol Detail Halaman -->
          <div class="p-5 sm:p-6 pt-0">
            <router-link 
              :to="`/agenda/${item.slug}`"
              class="w-full py-2.5 px-4 rounded-xl bg-slate-100 hover:bg-emerald-700 text-slate-700 hover:text-white font-bold text-xs transition duration-200 flex items-center justify-center gap-2 cursor-pointer shadow-xs hover:shadow"
            >
              <span>Lihat Selengkapnya</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 px-6 bg-white rounded-3xl border border-slate-200/80 shadow-xs max-w-xl mx-auto space-y-4">
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto shadow-inner">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
        </div>
        <div>
          <h3 class="text-lg font-bold text-slate-900">
            {{ isRiwayat ? 'Tidak Ada Riwayat Agenda' : 'Belum Ada Agenda Aktif' }}
          </h3>
          <p class="text-xs text-slate-500 mt-1 leading-relaxed">
            <span v-if="searchQuery">Tidak ditemukan kegiatan yang sesuai dengan kata kunci "{{ searchQuery }}".</span>
            <span v-else-if="!isRiwayat">Saat ini belum ada jadwal agenda kegiatan yang sedang berlangsung atau akan datang. Silakan periksa kembali berkala.</span>
            <span v-else>Belum ada arsip kegiatan yang telah selesai tercatat di sistem.</span>
          </p>
        </div>
        <button 
          v-if="searchQuery || selectedKategori !== 'Semua'" 
          @click="resetFilter"
          class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition cursor-pointer"
        >
          Reset Filter & Pencarian
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import HeroPageHeader from '../components/HeroPageHeader.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const agendaList = ref([]);
const counts = ref({ aktif: 0, riwayat: 0 });
const isRiwayat = ref(false);
const searchQuery = ref('');
const selectedKategori = ref('Semua');
const kategoriOptions = ref(['Pemerintahan', 'Pelayanan Warga', 'Sosial & Kemasyarakatan', 'Pembangunan', 'Kesehatan & Posyandu', 'Keagamaan', 'Pemuda & Olahraga']);

let searchDebounce = null;

const loadAgenda = async () => {
  loading.value = true;
  try {
    const params = {
      tampilkan_riwayat: isRiwayat.value ? 1 : 0
    };
    if (searchQuery.value.trim()) {
      params.search = searchQuery.value.trim();
    }
    if (selectedKategori.value !== 'Semua') {
      params.kategori = selectedKategori.value;
    }

    const res = await KelurahanService.getAgenda(params);
    if (res && res.data) {
      agendaList.value = res.data;
      counts.value = res.counts || { aktif: 0, riwayat: 0 };
    }
  } catch (err) {
    console.error('Gagal memuat agenda:', err);
  } finally {
    loading.value = false;
  }
};

const handleSearch = () => {
  clearTimeout(searchDebounce);
  searchDebounce = setTimeout(() => {
    loadAgenda();
  }, 350);
};

const changeTab = (riwayat) => {
  isRiwayat.value = riwayat;
  loadAgenda();
};

const resetFilter = () => {
  searchQuery.value = '';
  selectedKategori.value = 'Semua';
  loadAgenda();
};

const openDetail = (agenda) => {
  selectedAgenda.value = agenda;
};

const openImagePreview = (photo) => {
  zoomPhoto.value = photo;
};

const stripHtml = (html) => {
  if (!html) return '';
  return html.replace(/<[^>]*>?/gm, ' ');
};

onMounted(() => {
  loadAgenda();
});
</script>

<style scoped>
.rich-content :deep(p) {
  margin-bottom: 0.75rem;
}
.rich-content :deep(ul) {
  list-style-type: disc;
  padding-left: 1.25rem;
  margin-bottom: 0.75rem;
}
.rich-content :deep(ol) {
  list-style-type: decimal;
  padding-left: 1.25rem;
  margin-bottom: 0.75rem;
}
</style>
