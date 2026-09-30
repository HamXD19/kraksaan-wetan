<template>
  <div class="pb-20">
    <!-- Breadcrumb Nav -->
    <Breadcrumb :items="[{ label: 'Informasi Publik', to: '/informasi-publik' }, { label: 'Transparansi & Akuntabilitas Anggaran' }]" />

    <!-- Hero Header -->
    <HeroPageHeader 
      badge-text="Keterbukaan Anggaran & Tata Kelola"
      title="Transparansi & Akuntabilitas Anggaran"
      description="Portal pelaporan resmi publikasi Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan. Wujud akuntabilitas dan transparansi tata kelola keuangan demi pembangunan masyarakat."
    />

    <!-- Main Container -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-8">
      <!-- Toolbar: Selector Tahun & Pencarian -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-4 sm:p-5 bg-white rounded-2xl border border-slate-200 shadow-xs">
        <!-- Tahun Selector -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 md:pb-0">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wider shrink-0">Tahun:</span>
          <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl">
            <button 
              @click="selectYear('Semua')"
              class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer"
              :class="selectedTahun === 'Semua' ? 'bg-[#2073B7] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Semua
            </button>
            <button 
              v-for="y in (summary.daftar_tahun || [])" 
              :key="y"
              @click="selectYear(y)"
              class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer"
              :class="selectedTahun === y ? 'bg-[#2073B7] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            >
              {{ y }}
            </button>
          </div>
        </div>

        <!-- Search Input -->
        <div class="relative w-full md:w-72">
          <input 
            type="text" 
            v-model="searchQuery" 
            @input="filterDebounce"
            placeholder="Cari dokumen anggaran..." 
            class="w-full pl-9 pr-4 py-2 text-xs rounded-xl border border-slate-200 focus:ring-2 focus:ring-[#2073B7] focus:border-[#2073B7] outline-none bg-slate-50/50"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <LoadingSpinner v-if="loading" />

      <template v-else>
        <!-- Card List Publikasi Anggaran (Horizontal Cards Sesuai Spesifikasi) -->
        <div v-if="filteredBudgets.length > 0" class="space-y-5">
          <div 
            v-for="item in filteredBudgets" 
            :key="item.id"
            class="bg-white rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition overflow-hidden flex flex-col md:flex-row group"
          >
            <!-- Sisi Kiri: Thumbnail / Fallback Placeholder "No Image Available" -->
            <div class="w-full md:w-72 lg:w-80 shrink-0 bg-slate-100 relative min-h-[180px] md:min-h-full flex items-center justify-center overflow-hidden border-b md:border-b-0 md:border-r border-slate-100">
              <img 
                v-if="item.gambar" 
                :src="getImageUrl(item.gambar)" 
                :alt="item.judul"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
              />
              <div v-else class="flex flex-col items-center justify-center p-6 text-center text-slate-400 space-y-2">
                <div class="w-14 h-14 rounded-2xl bg-slate-200/80 flex items-center justify-center text-slate-500">
                  <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="text-xs font-bold text-slate-500 tracking-wide uppercase">No Image Available</span>
                <span class="text-[10px] text-slate-400">Arsip APBD TA {{ item.tahun }}</span>
              </div>
              <div class="absolute top-3 left-3">
                <span class="px-2.5 py-1 rounded-lg bg-slate-900/80 backdrop-blur-xs text-white text-[11px] font-bold">
                  TA {{ item.tahun }}
                </span>
              </div>
            </div>

            <!-- Sisi Kanan: Konten & Aksi -->
            <div class="p-6 sm:p-8 flex flex-col justify-between flex-grow space-y-4">
              <div class="space-y-2.5">
                <!-- Meta Tanggal -->
                <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                  <svg class="w-4 h-4 text-[#2073B7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <span>Tanggal Rilis: {{ formatTanggal(item.tanggal_publikasi) }}</span>
                </div>

                <!-- Judul Publikasi (Link ke Detail) -->
                <router-link 
                  :to="`/transparansi/${item.slug || item.id}`"
                  class="block text-lg sm:text-xl font-bold text-slate-900 group-hover:text-[#2073B7] transition leading-snug"
                >
                  {{ item.judul }}
                </router-link>

                <!-- Cuplikan Singkat Narasi / Deskripsi -->
                <p class="text-xs sm:text-sm text-slate-600 line-clamp-3 leading-relaxed">
                  {{ item.deskripsi || 'Rincian publikasi dokumen Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan. Klik tombol selengkapnya untuk meninjau rincian Pendapatan, Belanja, dan Pembiayaan secara lengkap.' }}
                </p>

                <!-- Mini Stat Pills -->
                <div class="flex flex-wrap items-center gap-2 pt-1">
                  <span class="px-3 py-1 rounded-xl bg-blue-50 text-[#2073B7] border border-blue-100 text-[11px] font-bold">
                    Pendapatan: {{ formatRupiah(item.total_pendapatan_realisasi || item.total_pendapatan_rencana) }}
                  </span>
                  <span class="px-3 py-1 rounded-xl bg-rose-50 text-rose-700 border border-rose-100 text-[11px] font-bold">
                    Belanja: {{ formatRupiah(item.total_belanja_realisasi || item.total_belanja_rencana) }}
                  </span>
                  <span 
                    class="px-3 py-1 rounded-xl text-[11px] font-bold"
                    :class="(item.surplus_defisit_realisasi ?? 0) >= 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100'"
                  >
                    Surplus/Defisit: {{ formatRupiah(item.surplus_defisit_realisasi ?? (item.total_pendapatan_realisasi - item.total_belanja_realisasi)) }}
                  </span>
                </div>
              </div>

              <!-- Tombol Aksi: "selengkapnya ➔" -->
              <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400 font-medium">
                  {{ item.items_count || 0 }} Pos Rincian Anggaran
                </span>

                <router-link 
                  :to="`/transparansi/${item.slug || item.id}`"
                  class="inline-flex items-center gap-1.5 text-xs font-bold text-[#2073B7] hover:text-blue-800 transition group-hover:translate-x-1 duration-300"
                >
                  <span>selengkapnya</span>
                  <span class="text-sm font-black">➔</span>
                </router-link>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State (Ketika belum ada data yang diinput oleh admin) -->
        <div v-else class="text-center py-16 px-6 bg-white rounded-3xl border border-slate-200 space-y-3">
          <div class="w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <h3 class="text-base font-bold text-slate-800">Belum Ada Dokumen Anggaran</h3>
          <p class="text-xs text-slate-500 max-w-md mx-auto">
            Data anggaran belum dipublikasikan atau tidak ada hasil yang cocok dengan filter pencarian Anda.
          </p>
          <button 
            v-if="selectedTahun !== 'Semua' || searchQuery"
            @click="resetFilter" 
            class="mt-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
          >
            Reset Filter
          </button>
        </div>
      </template>

      <!-- Banner Keterbukaan Informasi & Pengaduan Warga -->
      <div class="bg-gradient-to-r from-slate-900 via-[#1b3d5b] to-slate-900 rounded-3xl p-8 sm:p-10 text-white shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 border border-slate-700">
        <div class="space-y-2 text-center md:text-left">
          <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider">
            PPID & Partisipasi Masyarakat
          </span>
          <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight">
            Punya Tanggapan atau Pertanyaan Terkait Anggaran?
          </h3>
          <p class="text-xs sm:text-sm text-slate-200 max-w-2xl leading-relaxed">
            Masyarakat berhak mengajukan permohonan informasi publik atau menyampaikan aspirasi pembangunan melalui layanan Pejabat Pengelola Informasi dan Dokumentasi (PPID) Kelurahan Kraksaan Wetan.
          </p>
        </div>

        <router-link 
          to="/kontak" 
          class="px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs shadow-md transition whitespace-nowrap self-center md:self-auto cursor-pointer"
        >
          Kirim Aspirasi / Pengaduan
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import HeroPageHeader from '../components/HeroPageHeader.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const selectedTahun = ref('Semua');
const searchQuery = ref('');
const budgetsList = ref([]);
const summary = ref({
  daftar_tahun: [],
});

let debounceTimer = null;
const filterDebounce = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    loadData();
  }, 350);
};

const filteredBudgets = computed(() => {
  return budgetsList.value;
});

const formatRupiah = (val) => {
  const num = Number(val) || 0;
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(num);
};

const formatTanggal = (dateStr) => {
  if (!dateStr) return '-';
  try {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    }).format(d);
  } catch {
    return dateStr;
  }
};

const getImageUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  const clean = path.replace(/^\/+/, '');
  if (clean.startsWith('storage/')) return '/' + clean;
  return '/storage/' + clean;
};

const selectYear = (year) => {
  selectedTahun.value = year;
  loadData();
};

const resetFilter = () => {
  selectedTahun.value = 'Semua';
  searchQuery.value = '';
  loadData();
};

const loadData = async () => {
  loading.value = true;
  try {
    const params = {};
    if (selectedTahun.value !== 'Semua') {
      params.tahun = selectedTahun.value;
    }
    if (searchQuery.value?.trim()) {
      params.search = searchQuery.value.trim();
    }

    const res = await KelurahanService.getTransparansi(params);
    summary.value = res?.summary || summary.value;
    budgetsList.value = res?.budgets || [];
  } catch (err) {
    console.error('Gagal mengambil data transparansi publik:', err);
    budgetsList.value = [];
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadData();
});
</script>
