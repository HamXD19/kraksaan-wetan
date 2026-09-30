<template>
  <div class="pb-16">
    <Breadcrumb :items="[
      { label: 'Pelayanan Publik', to: '/pelayanan' },
      { label: 'Survei Kepuasan Masyarakat (SKM)' }
    ]" />

    <!-- Header Section -->
    <HeroPageHeader 
      badge-text="Indeks Kepuasan Masyarakat (IKM)"
      title="Survei Kepuasan Masyarakat (SKM)"
      description="Pengukuran tingkat kepuasan warga terhadap kualitas dan akuntabilitas penyelenggaraan pelayanan publik di Kelurahan Kraksaan Wetan secara transparan, objektif, dan berkelanjutan."
    >
      <div class="flex flex-wrap items-center gap-2.5 mt-4">
        <a 
          v-if="selectedSkm?.link_survei"
          :href="selectedSkm.link_survei" 
          target="_blank" 
          rel="noopener noreferrer"
          class="px-5 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm transition flex items-center gap-2 shadow-md cursor-pointer"
        >
          <svg class="w-4 h-4 text-emerald-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          <span>Isi Survei Kepuasan Online</span>
        </a>

        <button 
          v-if="selectedSkm"
          type="button"
          @click="downloadReport(selectedSkm)"
          class="px-5 py-3 rounded-xl bg-emerald-800/90 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm transition flex items-center gap-2 border border-emerald-600/50 shadow-xs cursor-pointer"
        >
          <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>Unduh Laporan Resmi (PDF)</span>
        </button>

        <router-link
          to="/pelayanan"
          class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm transition flex items-center gap-1.5"
        >
          <span>Standar SOP Pelayanan</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </router-link>
      </div>
    </HeroPageHeader>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-10">
      <!-- Loading State -->
      <LoadingSpinner v-if="loading" text="Memuat data Survei Kepuasan Masyarakat..." />

      <template v-else-if="skmList.length > 0">
        <!-- 1. Filter / Selector Periode Tahun -->
        <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div class="flex items-center gap-2">
            <span class="p-2 rounded-xl bg-emerald-100 text-emerald-800 flex-shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </span>
            <div>
              <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Pilih Periode Laporan SKM</h3>
              <p class="text-xs text-slate-500">Pilih tahun/periode untuk melihat nilai rincian unsur pelayanan terkait.</p>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2">
            <button
              v-for="item in skmList"
              :key="item.id"
              @click="selectedSkm = item"
              class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer"
              :class="selectedSkm?.id === item.id 
                ? 'bg-emerald-700 text-white shadow-xs ring-2 ring-emerald-600/30' 
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
            >
              <span>{{ item.tahun }} ({{ item.periode }})</span>
              <span 
                class="px-1.5 py-0.5 rounded text-[10px] font-bold"
                :class="selectedSkm?.id === item.id ? 'bg-emerald-900 text-amber-300' : 'bg-slate-200 text-slate-700'"
              >
                {{ item.skor_ikm }}
              </span>
            </button>
          </div>
        </div>

        <!-- 2. Utama: Hero Card Skor Dinamis -->
        <div v-if="selectedSkm" class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-slate-950 text-white rounded-3xl p-6 sm:p-10 shadow-lg border border-emerald-800 relative overflow-hidden">
          <div class="absolute -right-16 -top-16 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

          <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
            <!-- Nilai IKM & Mutu -->
            <div class="lg:col-span-5 text-center lg:text-left space-y-4">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 border border-emerald-600/50 text-emerald-200 text-xs font-bold">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                <span>Periode {{ selectedSkm.periode }} {{ selectedSkm.tahun }}</span>
              </div>

              <div>
                <p class="text-xs uppercase tracking-wider text-emerald-300/90 font-bold">Nilai Indeks Kepuasan Masyarakat (IKM)</p>
                <div class="mt-2 flex items-baseline justify-center lg:justify-start gap-2">
                  <span class="text-5xl sm:text-6xl font-black text-amber-400 tracking-tight">{{ selectedSkm.skor_ikm }}</span>
                  <span class="text-base text-emerald-200/80 font-bold">/ {{ selectedSkm.skala_maksimal || '100' }}</span>
                </div>
              </div>

              <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 pt-1">
                <span class="px-3.5 py-1.5 rounded-xl bg-amber-400 text-slate-950 font-black text-xs uppercase tracking-wider shadow-sm">
                  Mutu: {{ selectedSkm.mutu_pelayanan }}
                </span>
                <span class="px-3.5 py-1.5 rounded-xl bg-emerald-800 text-white font-bold text-xs border border-emerald-600">
                  Kinerja: {{ selectedSkm.predikat }}
                </span>
              </div>

              <div class="pt-2 flex items-center justify-center lg:justify-start gap-2 text-xs text-emerald-200/80">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span>Berdasarkan survei langsung terhadap <strong>{{ selectedSkm.jumlah_responden }} responden warga</strong></span>
              </div>
            </div>

            <!-- Ringkasan Cepat Unsur Terbaik -->
            <div class="lg:col-span-7 bg-white/5 backdrop-blur-md rounded-2xl p-5 sm:p-6 border border-white/10 space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-white/10">
                <h4 class="text-xs font-bold text-white uppercase tracking-wider flex items-center gap-2">
                  <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <span>Transparansi Penilaian Mutu Layanan</span>
                </h4>
                <span class="text-[11px] text-emerald-300 font-semibold">Tahun {{ selectedSkm.tahun }}</span>
              </div>

              <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
                {{ selectedSkm.metodologi || 'Penilaian SKM dilaksanakan menggunakan instrumen kuesioner terstandar mencakup seluruh unsur pelayanan dasar publik di Kelurahan Kraksaan Wetan.' }}
              </p>

              <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-2">
                <div class="bg-black/20 rounded-xl p-3 border border-white/5">
                  <div class="text-[10px] text-emerald-300 uppercase font-semibold">Skala Penilaian</div>
                  <div class="text-base font-bold text-white mt-0.5">0 - {{ selectedSkm.skala_maksimal || '100' }}</div>
                </div>
                <div class="bg-black/20 rounded-xl p-3 border border-white/5">
                  <div class="text-[10px] text-emerald-300 uppercase font-semibold">Total Responden</div>
                  <div class="text-base font-bold text-amber-400 mt-0.5">{{ selectedSkm.jumlah_responden }} Orang</div>
                </div>
                <div class="bg-black/20 rounded-xl p-3 border border-white/5 col-span-2 sm:col-span-1">
                  <div class="text-[10px] text-emerald-300 uppercase font-semibold">Status Kinerja</div>
                  <div class="text-base font-bold text-emerald-400 mt-0.5">{{ selectedSkm.predikat }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- 3. Rincian Skor Dinamis Per Unsur Pelayanan -->
        <div v-if="selectedSkm?.unsur_penilaian && selectedSkm.unsur_penilaian.length > 0" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900 flex items-center gap-2">
                <span>Rincian Nilai Indeks Per Unsur Pelayanan</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
                  {{ selectedSkm.unsur_penilaian.length }} Unsur
                </span>
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">
                Nilai capaian kepuasan masyarakat berdasarkan indikator penilaian yang berlaku pada periode {{ selectedSkm.tahun }}.
              </p>
            </div>

            <div class="text-xs font-semibold text-slate-500">
              Skor Tertinggi: <span class="font-bold text-emerald-700">{{ maxElementScore }}</span>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div 
              v-for="(unsur, uIdx) in selectedSkm.unsur_penilaian" 
              :key="uIdx"
              class="p-4 rounded-2xl border border-slate-100 bg-slate-50/70 hover:bg-white hover:border-emerald-200 hover:shadow-xs transition space-y-2.5"
            >
              <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-slate-800 flex items-center gap-2">
                  <span class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-800 flex items-center justify-center text-[10px] font-black">
                    {{ uIdx + 1 }}
                  </span>
                  <span>{{ unsur.nama }}</span>
                </span>
                <span class="font-black text-emerald-800 text-sm">
                  {{ unsur.nilai }}
                </span>
              </div>

              <!-- Progress Bar -->
              <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                <div 
                  class="h-full bg-gradient-to-r from-emerald-600 to-amber-500 rounded-full transition-all duration-700 ease-out"
                  :style="{ width: calculateProgressPercentage(unsur.nilai, selectedSkm.skala_maksimal) + '%' }"
                ></div>
              </div>

              <div class="flex items-center justify-between text-[11px] text-slate-500 pt-0.5">
                <span>Konversi Persentase</span>
                <span class="font-semibold text-slate-700">{{ calculateProgressPercentage(unsur.nilai, selectedSkm.skala_maksimal) }}%</span>
              </div>
            </div>
          </div>
        </div>

        <!-- 4. Penjelasan Standar Skala & Metodologi Penilaian -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
          <div class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-4">
            <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
              <span class="p-2 rounded-xl bg-blue-100 text-blue-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </span>
              <span>Tata Cara & Pedoman Penilaian SKM</span>
            </h3>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              Survei Kepuasan Masyarakat (SKM) dilaksanakan secara periodik untuk mengetahui kelemahan maupun kelebihan dari unit pelayanan publik kami. Hasil survei ini dijadikan dasar utama dalam perbaikan kualitas pelayanan, perbaikan SOP, serta pembinaan disiplin petugas pelayanan.
            </p>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 text-xs space-y-2">
              <div class="font-bold text-slate-800">Tabel Interval Mutu Pelayanan (PermenPAN-RB No. 14/2017):</div>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-1 text-center">
                <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                  <div class="font-bold text-emerald-800">88.31 – 100.00</div>
                  <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Mutu A (Sangat Baik)</div>
                </div>
                <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                  <div class="font-bold text-blue-800">76.61 – 88.30</div>
                  <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Mutu B (Baik)</div>
                </div>
                <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                  <div class="font-bold text-amber-800">65.00 – 76.60</div>
                  <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Mutu C (Kurang Baik)</div>
                </div>
                <div class="p-2.5 rounded-xl bg-white border border-slate-200 shadow-2xs">
                  <div class="font-bold text-rose-800">25.00 – 64.99</div>
                  <div class="text-[10px] text-slate-500 font-semibold mt-0.5">Mutu D (Tidak Baik)</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Call To Action Partisipasi Warga -->
          <div class="lg:col-span-4 bg-gradient-to-br from-emerald-800 to-emerald-950 text-white rounded-3xl p-6 sm:p-8 shadow-sm border border-emerald-700 flex flex-col justify-between space-y-6">
            <div class="space-y-3">
              <span class="px-2.5 py-1 rounded-full bg-amber-400 text-slate-950 text-[10px] font-black uppercase tracking-wider inline-block">
                Suara Anda Berarti
              </span>
              <h4 class="text-lg font-bold">Bantu Kami Meningkatkan Kualitas Pelayanan</h4>
              <p class="text-xs text-emerald-200 leading-relaxed">
                Apakah Anda baru saja menyelesaikan urusan administrasi di kantor Kelurahan Kraksaan Wetan? Berikan penilaian objektif Anda untuk pelayanan kami.
              </p>
            </div>

            <div class="space-y-3">
              <a 
                v-if="selectedSkm?.link_survei"
                :href="selectedSkm.link_survei" 
                target="_blank" 
                rel="noopener noreferrer"
                class="w-full py-3 px-4 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs text-center transition flex items-center justify-center gap-2 shadow-md cursor-pointer"
              >
                <span>Buka Formulir Kuesioner</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>

              <router-link
                to="/kontak"
                class="w-full py-2.5 px-4 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs text-center transition flex items-center justify-center gap-1.5"
              >
                <span>Sampaikan Saran / Kritik</span>
              </router-link>
            </div>
          </div>
        </div>

        <!-- 5. Riwayat Histori Penilaian SKM Lainnya -->
        <div v-if="skmList.length > 1" class="space-y-4">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span>Riwayat Laporan Survei SKM Sebelumnya</span>
            <span class="px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-700 text-xs font-bold">{{ skmList.length }} Laporan</span>
          </h3>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="item in skmList"
              :key="'hist-' + item.id"
              class="bg-white rounded-2xl p-5 border border-slate-200 hover:border-emerald-300 hover:shadow-xs transition space-y-3"
            >
              <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase">{{ item.periode }}</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Tahun {{ item.tahun }}</span>
              </div>

              <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900">{{ item.skor_ikm }}</span>
                <span class="text-xs text-slate-500 font-medium">/ {{ item.skala_maksimal || '100' }}</span>
              </div>

              <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-100 text-slate-600">
                <span>{{ item.jumlah_responden }} Responden</span>
                <span class="font-bold text-emerald-700">{{ item.predikat }}</span>
              </div>

              <div class="pt-2 flex items-center gap-2">
                <button
                  type="button"
                  @click="selectedSkm = item"
                  class="flex-1 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs text-center transition cursor-pointer"
                >
                  Lihat Rincian
                </button>
                <button
                  type="button"
                  @click="downloadReport(item)"
                  class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-100 text-slate-600 transition cursor-pointer"
                  title="Unduh Laporan PDF"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
        <svg class="w-12 h-12 text-slate-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
        <h3 class="text-base font-bold text-slate-800">Data Survei Belum Dipublikasikan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">Laporan Survei Kepuasan Masyarakat (SKM) sedang dalam proses pengolahan dan verifikasi data oleh tim pelayanan kelurahan.</p>
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
const skmList = ref([]);
const selectedSkm = ref(null);

const maxElementScore = computed(() => {
  if (!selectedSkm.value?.unsur_penilaian?.length) return 0;
  return Math.max(...selectedSkm.value.unsur_penilaian.map(u => Number(unsurNilai(u.nilai))));
});

const unsurNilai = (val) => {
  return typeof val === 'number' ? val : parseFloat(val) || 0;
};

const calculateProgressPercentage = (score, maxScale = 100) => {
  const numScore = unsurNilai(score);
  const numMax = Number(maxScale) || 100;
  const pct = (numScore / numMax) * 100;
  return Math.min(Math.max(roundNumber(pct, 1), 0), 100);
};

const roundNumber = (num, decimals = 1) => {
  const p = Math.pow(10, decimals);
  return Math.round(num * p) / p;
};

const loadSkmData = async () => {
  loading.value = true;
  try {
    const res = await KelurahanService.getSurveiSkm();
    if (res?.list) {
      skmList.value = res.list;
      selectedSkm.value = res.latest || res.list[0] || null;
    }
  } catch (err) {
    console.error('Gagal memuat data Survei SKM:', err);
  } finally {
    loading.value = false;
  }
};

const downloadReport = (skm) => {
  if (!skm?.id) return;
  window.open(`/api/survei-skm/${skm.id}/unduh`, '_blank');
};

onMounted(() => {
  loadSkmData();
});
</script>
