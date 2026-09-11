<template>
  <div class="pb-16">
    <!-- Breadcrumb Nav -->
    <Breadcrumb :items="[{ label: 'Informasi Publik', to: '/informasi-publik' }, { label: 'Transparansi & Akuntabilitas' }]" />

    <!-- Hero Header -->
    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 border border-emerald-700 text-amber-300 text-xs font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-amber-400"></span>
          Keterbukaan Anggaran & Tata Kelola
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Transparansi & Akuntabilitas Anggaran</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-3xl leading-relaxed">
          Portal pelaporan real-time penyerapan anggaran, program pembangunan sarana prasarana, pemberdayaan ekonomi, serta penyaluran bantuan sosial bagi segenap warga Kelurahan Kraksaan Wetan.
        </p>
      </div>
    </section>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-12">
      <!-- Selector Tahun & Update Info -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-2">
          <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Tahun Anggaran:</span>
          <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl">
            <button 
              v-for="y in (summary.daftar_tahun || [2026, 2025])" 
              :key="y"
              @click="selectYear(y)"
              class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition"
              :class="selectedTahun === y ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            >
              {{ y }}
            </button>
            <button 
              @click="selectYear('Semua')"
              class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition"
              :class="selectedTahun === 'Semua' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'"
            >
              Semua
            </button>
          </div>
        </div>

        <div class="text-xs text-slate-500 flex items-center gap-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          <span>Data Real-time Transparansi Pemerintahan Kelurahan</span>
        </div>
      </div>

      <LoadingSpinner v-if="loading" />

      <template v-else>
        <!-- 4 KPI Cards: Real-Time Budget Tracking -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
          <!-- 1. Total Pagu Rencana -->
          <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs relative overflow-hidden">
            <div class="w-10 h-10 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs mb-3">
              <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pagu Rencana</p>
            <h3 class="text-2xl font-black text-slate-900 mt-1 tracking-tight">
              {{ formatRupiah(summary.total_rencana) }}
            </h3>
            <p class="text-[11px] text-slate-500 mt-1">Total alokasi pagu tahun {{ selectedTahun }}</p>
          </div>

          <!-- 2. Realisasi Terserap -->
          <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs relative overflow-hidden">
            <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs mb-3">
              <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Realisasi Anggaran</p>
            <h3 class="text-2xl font-black text-emerald-700 mt-1 tracking-tight">
              {{ formatRupiah(summary.total_realisasi) }}
            </h3>
            <p class="text-[11px] text-emerald-600 font-semibold mt-1">Terserap untuk pembangunan warga</p>
          </div>

          <!-- 3. Sisa Anggaran -->
          <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs relative overflow-hidden">
            <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs mb-3">
              <svg class="w-5 h-5 text-amber-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
            </div>
            <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Sisa Pagu</p>
            <h3 class="text-2xl font-black text-amber-700 mt-1 tracking-tight">
              {{ formatRupiah(summary.total_sisa) }}
            </h3>
            <p class="text-[11px] text-slate-500 mt-1">Termin lanjutan & kegiatan berjalan</p>
          </div>

          <!-- 4. Persentase Realisasi Serapan -->
          <div class="bg-emerald-950 text-white p-6 rounded-3xl shadow-sm relative overflow-hidden flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Serapan Anggaran</span>
                <span class="px-2 py-0.5 rounded-full bg-emerald-800 text-[10px] font-bold text-emerald-200">Real-Time</span>
              </div>
              <p class="text-3xl sm:text-4xl font-black text-white mt-2">
                {{ summary.persentase_total }}%
              </p>
            </div>
            <div class="mt-3">
              <div class="w-full bg-emerald-900 h-2.5 rounded-full overflow-hidden">
                <div 
                  class="bg-amber-400 h-full rounded-full transition-all duration-700" 
                  :style="{ width: Math.min(summary.persentase_total || 0, 100) + '%' }"
                ></div>
              </div>
              <p class="text-[10px] text-emerald-300 mt-1.5 font-medium">
                {{ summary.kegiatan_selesai }} selesai, {{ summary.kegiatan_berjalan }} sedang berjalan
              </p>
            </div>
          </div>
        </div>

        <!-- Breakdown Realisasi per Kategori Program -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
            <div>
              <h2 class="text-lg sm:text-xl font-bold text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                Realisasi Anggaran Menurut Bidang Kegiatan
              </h2>
              <p class="text-xs text-slate-500 mt-0.5">Komparasi nilai pagu rencana terhadap realisasi serapan pada masing-masing bidang.</p>
            </div>
            <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200 self-start sm:self-auto">
              {{ summary.daftar_kategori?.length || 4 }} Bidang Program
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div 
              v-for="b in (summary.breakdown_kategori || [])" 
              :key="b.kategori"
              class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-3"
            >
              <div class="flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-xs sm:text-sm">{{ b.kategori }}</h3>
                <span class="text-xs font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md">
                  {{ b.persentase }}%
                </span>
              </div>

              <!-- Bar Comparison -->
              <div class="space-y-1">
                <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                  <div 
                    class="bg-emerald-600 h-full rounded-full transition-all duration-500" 
                    :style="{ width: Math.min(b.persentase || 0, 100) + '%' }"
                  ></div>
                </div>
                <div class="flex items-center justify-between text-[11px]">
                  <span class="text-slate-500">Rencana: <strong class="text-slate-800">{{ formatRupiah(b.rencana) }}</strong></span>
                  <span class="text-emerald-700">Terserap: <strong>{{ formatRupiah(b.realisasi) }}</strong></span>
                </div>
              </div>

              <div class="flex items-center justify-between text-[10px] text-slate-500 pt-1 border-t border-slate-200/60">
                <span>{{ b.jumlah_kegiatan }} Kegiatan Terlaksana</span>
                <span>Sisa Dana: {{ formatRupiah(b.sisa) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Daftar Kegiatan, Filter, & Penerima Manfaat -->
        <div class="space-y-6">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
              <h2 class="text-xl font-bold text-slate-900">Rincian Program & Penerima Manfaat</h2>
              <p class="text-xs text-slate-500 mt-0.5">Pantau pelaksanaan teknis setiap kegiatan, progres fisik, dan dampak bagi masyarakat.</p>
            </div>

            <!-- Toolbar Filter Kategori & Pencarian -->
            <div class="flex flex-wrap items-center gap-2">
              <div class="flex items-center gap-1 overflow-x-auto pb-1 max-w-full">
                <button 
                  v-for="cat in availableCategories" 
                  :key="cat"
                  @click="selectedCategory = cat"
                  class="px-3 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap"
                  :class="selectedCategory === cat ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                >
                  {{ cat }}
                </button>
              </div>
            </div>
          </div>

          <!-- Cards Grid Kegiatan -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div 
              v-for="item in filteredKegiatan" 
              :key="item.id"
              class="bg-white rounded-3xl p-6 sm:p-7 border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-5"
            >
              <div class="space-y-4">
                <!-- Header Card: Tahun, Kategori, Status -->
                <div class="flex items-start justify-between gap-2">
                  <div class="flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 font-bold text-xs text-slate-700">
                      TA {{ item.tahun }}
                    </span>
                    <span 
                      class="px-2.5 py-1 rounded-lg text-[10px] font-bold"
                      :class="getKategoriBadge(item.kategori)"
                    >
                      {{ item.kategori }}
                    </span>
                  </div>

                  <span 
                    class="px-2.5 py-1 rounded-full text-[10px] font-bold"
                    :class="getStatusBadge(item.status)"
                  >
                    {{ item.status }}
                  </span>
                </div>

                <!-- Judul & Program Induk -->
                <div>
                  <p class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">
                    {{ item.program }}
                  </p>
                  <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug mt-1">
                    {{ item.kegiatan }}
                  </h3>
                  <p v-if="item.lokasi" class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>{{ item.lokasi }}</span>
                  </p>
                </div>

                <!-- Deskripsi Kegiatan -->
                <p class="text-xs text-slate-600 leading-relaxed">
                  {{ item.deskripsi }}
                </p>

                <!-- Box Real-time Tracking Anggaran Kegiatan -->
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2.5">
                  <div class="flex items-center justify-between text-xs">
                    <span class="font-bold text-slate-700">Sumber: {{ item.sumber_dana }}</span>
                    <span class="font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded text-[11px]">
                      {{ item.persentase_realisasi }}% Serapan
                    </span>
                  </div>

                  <div class="w-full bg-slate-200 h-2 rounded-full overflow-hidden">
                    <div 
                      class="bg-emerald-600 h-full rounded-full" 
                      :style="{ width: Math.min(item.persentase_realisasi || 0, 100) + '%' }"
                    ></div>
                  </div>

                  <div class="grid grid-cols-2 gap-2 text-[11px] pt-1 border-t border-slate-200/60">
                    <div>
                      <span class="text-slate-400 block text-[10px]">Pagu Rencana:</span>
                      <strong class="text-slate-800">{{ formatRupiah(item.anggaran_rencana) }}</strong>
                    </div>
                    <div class="text-right">
                      <span class="text-emerald-600 block text-[10px]">Realisasi:</span>
                      <strong class="text-emerald-700">{{ formatRupiah(item.anggaran_realisasi) }}</strong>
                    </div>
                  </div>
                </div>

                <!-- Box Penerima Manfaat & Capaian Fisik -->
                <div class="p-3.5 rounded-2xl bg-emerald-50/40 border border-emerald-100 space-y-2 text-xs">
                  <div class="flex items-start gap-2">
                    <span class="text-emerald-700 font-bold flex-shrink-0">🎯 Sasaran:</span>
                    <span class="text-slate-800 font-medium">{{ item.penerima_manfaat_target || '-' }}</span>
                  </div>
                  <div v-if="item.penerima_manfaat_realisasi" class="flex items-start gap-2">
                    <span class="text-emerald-700 font-bold flex-shrink-0">✓ Capaian:</span>
                    <span class="text-emerald-900 font-semibold">{{ item.penerima_manfaat_realisasi }}</span>
                  </div>
                </div>
              </div>

              <!-- Footer Card: Progres Fisik & PJ -->
              <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                <span class="text-slate-500 text-[11px]">
                  Progres Fisik: <strong class="text-slate-900">{{ item.progres_fisik }}%</strong>
                </span>
                <span v-if="item.penanggung_jawab" class="text-slate-400 text-[10px]">
                  PJ: {{ item.penanggung_jawab }}
                </span>
              </div>
            </div>
          </div>

          <div v-if="!filteredKegiatan.length" class="text-center py-12 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
            <p class="text-base font-bold text-slate-700">Belum ada kegiatan yang cocok dengan filter yang dipilih.</p>
            <p class="text-xs text-slate-400">Silakan pilih kategori "Semua" atau ganti tahun anggaran.</p>
          </div>
        </div>

        <!-- Banner Keterbukaan Informasi & Pengaduan Warga -->
        <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 rounded-3xl p-8 sm:p-10 text-white shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 border border-emerald-800">
          <div class="space-y-2 text-center md:text-left">
            <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider">
              PPID & Partisipasi Masyarakat
            </span>
            <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight">
              Punya Tanggapan atau Pertanyaan Terkait Pembangunan?
            </h3>
            <p class="text-xs sm:text-sm text-emerald-200 max-w-2xl leading-relaxed">
              Masyarakat berhak mengajukan permohonan informasi publik atau menyampaikan aspirasi pembangunan melalui layanan Pejabat Pengelola Informasi dan Dokumentasi (PPID) Kelurahan Kraksaan Wetan.
            </p>
          </div>

          <router-link 
            to="/kontak" 
            class="px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs shadow-md transition whitespace-nowrap self-center md:self-auto"
          >
            Kirim Aspirasi / Pengaduan
          </router-link>
        </div>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const selectedTahun = ref(new Date().getFullYear());
const selectedCategory = ref('Semua');
const kegiatanList = ref([]);
const summary = ref({
  tahun_terpilih: new Date().getFullYear(),
  total_rencana: 0,
  total_realisasi: 0,
  total_sisa: 0,
  persentase_total: 0,
  total_kegiatan: 0,
  kegiatan_selesai: 0,
  kegiatan_berjalan: 0,
  daftar_tahun: [2026, 2025],
  daftar_kategori: [],
  breakdown_kategori: []
});

const availableCategories = computed(() => {
  const cats = ['Semua'];
  if (summary.value.daftar_kategori) {
    summary.value.daftar_kategori.forEach(c => {
      if (!cats.includes(c)) cats.push(c);
    });
  }
  return cats;
});

const filteredKegiatan = computed(() => {
  if (selectedCategory.value === 'Semua') {
    return kegiatanList.value;
  }
  return kegiatanList.value.filter(k => k.kategori === selectedCategory.value);
});

const formatRupiah = (val) => {
  const num = Number(val) || 0;
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(num);
};

const getKategoriBadge = (kategori) => {
  switch (kategori) {
    case 'Infrastruktur & Sarpras':
      return 'bg-blue-100 text-blue-900 border border-blue-200';
    case 'Pemberdayaan Masyarakat':
      return 'bg-amber-100 text-amber-900 border border-amber-200';
    case 'Bantuan Sosial & Kesehatan':
      return 'bg-rose-100 text-rose-900 border border-rose-200';
    case 'Pemerintahan & Pelayanan Digital':
    default:
      return 'bg-emerald-100 text-emerald-900 border border-emerald-200';
  }
};

const getStatusBadge = (status) => {
  switch (status) {
    case 'Selesai':
      return 'bg-emerald-100 text-emerald-800';
    case 'Sedang Berjalan':
      return 'bg-blue-100 text-blue-800';
    case 'Rencana':
      return 'bg-slate-100 text-slate-700';
    case 'Evaluasi':
    default:
      return 'bg-amber-100 text-amber-800';
  }
};

const selectYear = (year) => {
  selectedTahun.value = year;
  loadData();
};

const loadData = async () => {
  loading.value = true;
  try {
    const params = {};
    if (selectedTahun.value !== 'Semua') {
      params.tahun = selectedTahun.value;
    } else {
      params.tahun = 'Semua';
    }

    const res = await KelurahanService.getTransparansi(params);
    summary.value = res?.summary || summary.value;
    kegiatanList.value = res?.kegiatan || [];
  } catch (err) {
    console.error('Gagal mengambil data transparansi publik:', err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadData();
});
</script>
