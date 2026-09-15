<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Standar Operasional Prosedur (SOP) Pelayanan' }]" />

    <!-- Header Section -->
    <section class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
              Standar Pelayanan Publik Kelurahan
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Katalog SOP Pelayanan Masyarakat</h1>
            <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl leading-relaxed">
              Panduan resmi Standar Operasional Prosedur (SOP), daftar persyaratan berkas, alur tahapan permohonan, estimasi waktu penyelesaian, dan kepastian tarif layanan di Kelurahan Kraksaan Wetan.
            </p>
          </div>

          <div class="flex items-center gap-2">
            <a 
              href="#panduan-umum"
              class="px-5 py-3 rounded-xl bg-emerald-800/90 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm transition flex items-center gap-2 border border-emerald-600/50"
            >
              <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Alur Pengurusan
            </a>
            <router-link 
              to="/kontak"
              class="px-5 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs sm:text-sm transition flex items-center gap-2 shadow-md"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              Hubungi Petugas
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-10">
      <!-- Highlight Banner: Bebas Biaya -->
      <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white p-5 sm:p-6 rounded-3xl shadow-sm border border-emerald-800 flex flex-col md:flex-row items-center justify-between gap-5 reveal">
        <div class="flex items-center gap-4 text-center md:text-left">
          <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-0.5 rounded-full bg-amber-500 text-slate-950 text-[11px] font-bold uppercase tracking-wider">
                100% Gratis & Bebas Pungli
              </span>
            </div>
            <h3 class="text-base sm:text-lg font-bold mt-1 text-white">Seluruh Pelayanan di Kelurahan Kraksaan Wetan Tidak Dipungut Biaya</h3>
            <p class="text-xs text-emerald-200 mt-0.5">Warga dipersilakan melapor melalui portal resmi SP4N-LAPOR! atau Halo SAE bila menemukan indikasi pungutan liar.</p>
          </div>
        </div>
        <router-link 
          to="/kontak" 
          class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs sm:text-sm shadow-md transition flex-shrink-0"
        >
          Saluran Pengaduan
        </router-link>
      </div>

      <!-- Search & Filter Bar -->
      <div class="bg-white p-4 sm:p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4 reveal delay-100">
        <div class="flex flex-col sm:flex-row gap-3">
          <!-- Search Input -->
          <div class="relative flex-1">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Cari SOP atau nama permohonan layanan (misal: KTP, KK, Keterangan Usaha)..." 
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 focus:bg-white text-xs sm:text-sm"
            />
          </div>

          <!-- Total Counter -->
          <div class="flex items-center px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold self-start sm:self-auto">
            <span>{{ filteredLayanan.length }} Layanan Tersedia</span>
          </div>
        </div>

        <!-- Filter Categories -->
        <div class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-100">
          <button 
            @click="selectedKategori = 'Semua'"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
            :class="selectedKategori === 'Semua' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            Semua Kategori
          </button>
          <button 
            v-for="kat in kategoriList" 
            :key="kat"
            @click="selectedKategori = kat"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
            :class="selectedKategori === kat ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            {{ kat }}
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <LoadingSpinner v-if="loading" text="Memuat katalog layanan..." />

      <!-- Grid Layanan Cards -->
      <div v-else-if="filteredLayanan.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="(item, idx) in filteredLayanan" 
          :key="item.id"
          class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between reveal"
          :class="`delay-${((idx % 6) + 1) * 75}`"
        >
          <div class="space-y-4">
            <!-- Header: Category & SLA -->
            <div class="flex items-center justify-between gap-2">
              <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 tracking-wider">
                {{ item.kategori }}
              </span>
              <span class="text-[11px] text-emerald-700 font-bold flex items-center gap-1 bg-emerald-50 px-2.5 py-0.5 rounded-md">
                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ item.waktu || '10 - 15 Menit' }}
              </span>
            </div>

            <!-- Title & Description -->
            <div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                {{ item.judul }}
              </h3>
              <p class="text-xs text-slate-500 mt-1.5 leading-relaxed line-clamp-2">
                {{ item.deskripsi }}
              </p>
            </div>

            <!-- Requirements Preview -->
            <div v-if="item.persyaratan && item.persyaratan.length > 0" class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
              <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block">
                Persyaratan Berkas:
              </span>
              <ul class="space-y-1 text-xs text-slate-600">
                <li 
                  v-for="(req, rIdx) in item.persyaratan.slice(0, 3)" 
                  :key="rIdx"
                  class="flex items-start gap-1.5"
                >
                  <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  <span class="line-clamp-1">{{ req }}</span>
                </li>
                <li v-if="item.persyaratan.length > 3" class="text-[11px] text-emerald-700 font-semibold pl-5">
                  + {{ item.persyaratan.length - 3 }} berkas pendukung lainnya
                </li>
              </ul>
            </div>
          </div>

          <!-- Card Footer: Cost & SOP Action Button -->
          <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <div>
              <span class="text-[10px] text-slate-400 block font-semibold uppercase">Biaya Resmi</span>
              <span class="text-xs font-bold text-emerald-700">{{ item.biaya || 'Gratis (Rp 0)' }}</span>
            </div>

            <button 
              @click="openDetailSOP(item)"
              class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs hover:shadow-md transition flex items-center gap-1.5 cursor-pointer"
            >
              <span>Lihat SOP</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <h3 class="text-base font-bold text-slate-800 mb-1">Layanan Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 mb-4">Tidak ada layanan yang sesuai dengan kata kunci atau filter yang Anda pilih.</p>
        <button 
          @click="resetFilter"
          class="px-4 py-2 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition"
        >
          Reset Pencarian
        </button>
      </div>

      <!-- General SOP Procedure Guide -->
      <div id="panduan-umum" class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs space-y-6">
        <div>
          <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase mb-2">
            Standar Pelayanan Prima
          </span>
          <h2 class="text-2xl font-bold text-slate-900">Alur Umum Pengurusan Pelayanan di Kantor Kelurahan</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Masyarakat dapat mengikuti 4 langkah mudah berikut saat mengurus dokumen administrasi di kantor kelurahan:
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-2">
          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center mb-3">1</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Siapkan Berkas</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Pastikan berkas asli & fotokopi KTP, Kartu Keluarga, dan surat pengantar RT/RW (bila disyaratkan) telah lengkap.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center mb-3">2</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Ambil Nomor Antrean</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Kunjungi loket pelayanan terpadu Kelurahan Kraksaan Wetan pada hari dan jam kerja yang berlaku.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center mb-3">3</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Verifikasi & Pemrosesan</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Petugas memverifikasi kelengkapan berkas dan memproses dokumen permohonan surat secara cepat dan teliti.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center mb-3">4</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Penerbitan Dokumen</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Penandatanganan oleh Kepala Kelurahan/pejabat berwenang dan penyerahan dokumen resmi kepada pemohon (Rp 0).
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail SOP Lengkap -->
    <div 
      v-if="activeSop" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs animate-fade-in"
      @click.self="activeSop = null"
    >
      <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 flex flex-col">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-start justify-between gap-4 sticky top-0 bg-white z-10 rounded-t-3xl">
          <div>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
              {{ activeSop.kategori }}
            </span>
            <h2 class="text-xl font-bold text-slate-900 mt-1.5 leading-snug">
              {{ activeSop.judul }}
            </h2>
          </div>
          <button 
            @click="activeSop = null" 
            class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"
          >
            &times;
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 sm:p-8 space-y-6 text-xs sm:text-sm">
          <!-- Deskripsi Layanan -->
          <div>
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Deskripsi Layanan</h4>
            <p class="text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">
              {{ activeSop.deskripsi }}
            </p>
          </div>

          <!-- Parameter Waktu & Biaya -->
          <div class="grid grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100">
              <span class="text-[10px] uppercase font-bold text-emerald-700 tracking-wider">Estimasi Waktu</span>
              <p class="text-sm font-bold text-slate-900 mt-0.5">{{ activeSop.waktu || '10 - 15 Menit' }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-100">
              <span class="text-[10px] uppercase font-bold text-amber-800 tracking-wider">Tarif / Biaya</span>
              <p class="text-sm font-bold text-slate-900 mt-0.5">{{ activeSop.biaya || 'Gratis (Rp 0)' }}</p>
            </div>
          </div>

          <!-- Persyaratan Berkas -->
          <div>
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Persyaratan Dokumen yang Wajib Disiapkan
            </h4>
            <div v-if="activeSop.persyaratan && activeSop.persyaratan.length" class="space-y-2">
              <div 
                v-for="(p, idx) in activeSop.persyaratan" 
                :key="idx" 
                class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-3"
              >
                <div class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-slate-700 font-medium">{{ p }}</span>
              </div>
            </div>
            <p v-else class="text-slate-400 italic">Tidak ada persyaratan khusus.</p>
          </div>

          <!-- Alur / Prosedur SOP -->
          <div>
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Alur Standar Operasional Prosedur (SOP)
            </h4>
            <div v-if="activeSop.alur" class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 leading-relaxed whitespace-pre-line">
              {{ activeSop.alur }}
            </div>
            <div v-else class="space-y-2">
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">1</span>
                <span>Pemohon datang ke loket pelayanan dengan membawa seluruh berkas persyaratan.</span>
              </div>
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">2</span>
                <span>Petugas pelayanan memeriksa kelengkapan administrasi dan keabsahan berkas pemohon.</span>
              </div>
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">3</span>
                <span>Petugas memproses draf surat/dokumen dan diajukan ke Kepala Kelurahan untuk penandatanganan resmi.</span>
              </div>
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-amber-500 text-slate-950 font-bold text-xs flex items-center justify-center flex-shrink-0">4</span>
                <span>Dokumen yang telah ditandatangani dan distempel diserahkan kepada pemohon tanpa dipungut biaya.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-5 border-t border-slate-100 bg-slate-50 rounded-b-3xl flex justify-end">
          <button 
            @click="activeSop = null" 
            class="px-5 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const layananList = ref([]);
const kategoriList = ref([]);
const selectedKategori = ref('Semua');
const searchQuery = ref('');
const activeSop = ref(null);

onMounted(async () => {
  try {
    const [layananData, katData] = await Promise.all([
      KelurahanService.getLayanan(),
      KelurahanService.getKategori('layanan')
    ]);

    layananList.value = layananData || [];
    
    if (katData && katData.length > 0) {
      kategoriList.value = katData.map(k => k.nama);
    } else {
      const distinct = [...new Set((layananData || []).map(l => l.kategori).filter(Boolean))];
      kategoriList.value = distinct;
    }
  } catch (err) {
    console.error('Gagal memuat layanan:', err);
  } finally {
    loading.value = false;
  }
});

const filteredLayanan = computed(() => {
  return layananList.value.filter(item => {
    const matchCategory = selectedKategori.value === 'Semua' || item.kategori === selectedKategori.value;
    const matchQuery = !searchQuery.value || 
      item.judul.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (item.deskripsi && item.deskripsi.toLowerCase().includes(searchQuery.value.toLowerCase()));
    return matchCategory && matchQuery;
  });
});

function openDetailSOP(item) {
  activeSop.value = item;
}

function resetFilter() {
  selectedKategori.value = 'Semua';
  searchQuery.value = '';
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.98);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}
</style>
