<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Informasi Publik' }]" />

    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Keterbukaan Informasi & Data</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Informasi Publik & Data Wilayah</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl">
          Akses data agregat kependudukan, statistik wilayah, laporan transparansi, dan informasi publik Kelurahan Kraksaan Wetan.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-16">
      <LoadingSpinner v-if="loading" />
      <template v-else>
        <!-- Data Kependudukan & Statistik Ringkasan -->
        <section id="statistik" class="scroll-mt-24 space-y-8">
          <div class="text-center max-w-2xl mx-auto reveal">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Statistik Kependudukan
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Data Demografi Penduduk
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
              Data agregat semester terkini berdasarkan registrasi administrasi kependudukan.
            </p>
          </div>

          <!-- Cards Statistik -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs text-center reveal delay-75">
              <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Total Penduduk</p>
              <p class="text-3xl font-black text-emerald-950">{{ (statistik.penduduk || 6842).toLocaleString('id-ID') }}</p>
              <p class="text-[11px] text-slate-500 mt-1">Jiwa Terdaftar</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs text-center reveal delay-150">
              <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Laki-Laki</p>
              <p class="text-3xl font-black text-emerald-700">{{ (statistik.laki_laki || 3390).toLocaleString('id-ID') }}</p>
              <p class="text-[11px] text-slate-500 mt-1">49.5% dari Total</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs text-center reveal delay-200">
              <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Perempuan</p>
              <p class="text-3xl font-black text-emerald-700">{{ (statistik.perempuan || 3452).toLocaleString('id-ID') }}</p>
              <p class="text-[11px] text-slate-500 mt-1">50.5% dari Total</p>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs text-center reveal delay-250">
              <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Kepala Keluarga</p>
              <p class="text-3xl font-black text-amber-600">{{ (statistik.kk || 2185).toLocaleString('id-ID') }}</p>
              <p class="text-[11px] text-slate-500 mt-1">Kartu Keluarga (KK)</p>
            </div>
          </div>
        </section>

        <!-- Data Sebaran RW & Wilayah (#wilayah) -->
        <section id="wilayah" class="scroll-mt-24 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm reveal delay-100">
          <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
            Tabel Sebaran Penduduk per-Rukun Warga (RW)
          </h3>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                  <th class="py-3 px-4">Nama Lingkungan / RW</th>
                  <th class="py-3 px-4 text-center">Jumlah RT</th>
                  <th class="py-3 px-4 text-center">Estimasi Penduduk</th>
                  <th class="py-3 px-4">Keterangan Wilayah</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-slate-600">
                <tr v-for="l in statistik.lingkungan" :key="l.nama" class="hover:bg-slate-50">
                  <td class="py-3 px-4 font-bold text-slate-900">{{ l.nama }}</td>
                  <td class="py-3 px-4 text-center font-semibold text-emerald-700">{{ l.rt }} RT</td>
                  <td class="py-3 px-4 text-center font-bold text-slate-800">{{ l.penduduk }} Jiwa</td>
                  <td class="py-3 px-4 text-slate-500">Kawasan pemukiman & sentra aktivitas masyarakat</td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Transparansi & Akuntabilitas (#transparansi) -->
        <section id="transparansi" class="scroll-mt-24 space-y-6 reveal">
          <div class="text-center max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Transparansi Publik
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Keterbukaan Anggaran & Program Kegiatan
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
              Sebagai wujud komitmen good governance dan transparansi pemerintahan kelurahan.
            </p>
          </div>

          <div v-if="transparansiSummary" class="bg-gradient-to-br from-emerald-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
            <div class="relative z-10 space-y-6">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-emerald-800">
                <div>
                  <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Live Budget Tracking</span>
                  <h3 class="text-xl sm:text-2xl font-black mt-1 tracking-tight">Realisasi Anggaran & Kegiatan Kelurahan</h3>
                  <p class="text-xs text-emerald-200 mt-1">Transparansi alokasi dana dan capaian fisik pembangunan wilayah secara berkala.</p>
                </div>
                <router-link 
                  to="/transparansi" 
                  class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs shadow-md transition self-start sm:self-auto"
                >
                  <span>Lihat Seluruh Rincian Kegiatan</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </router-link>
              </div>

              <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-emerald-800/50 p-4 rounded-2xl border border-emerald-700/50">
                  <p class="text-[11px] font-bold uppercase text-emerald-300 tracking-wider">Pagu Rencana</p>
                  <p class="text-base sm:text-xl font-black mt-1">{{ formatRupiah(transparansiSummary.total_rencana) }}</p>
                </div>
                <div class="bg-emerald-800/50 p-4 rounded-2xl border border-emerald-700/50">
                  <p class="text-[11px] font-bold uppercase text-emerald-300 tracking-wider">Realisasi</p>
                  <p class="text-base sm:text-xl font-black mt-1 text-emerald-200">{{ formatRupiah(transparansiSummary.total_realisasi) }}</p>
                </div>
                <div class="bg-emerald-800/50 p-4 rounded-2xl border border-emerald-700/50">
                  <p class="text-[11px] font-bold uppercase text-amber-300 tracking-wider">Sisa Pagu</p>
                  <p class="text-base sm:text-xl font-black mt-1 text-amber-300">{{ formatRupiah(transparansiSummary.total_sisa) }}</p>
                </div>
                <div class="bg-emerald-800/50 p-4 rounded-2xl border border-emerald-700/50">
                  <p class="text-[11px] font-bold uppercase text-emerald-300 tracking-wider">Serapan Anggaran</p>
                  <p class="text-base sm:text-xl font-black mt-1 text-amber-300">{{ transparansiSummary.persentase_total }}%</p>
                  <div class="w-full bg-emerald-950/80 h-1.5 rounded-full mt-2 overflow-hidden">
                    <div class="bg-amber-400 h-full rounded-full" :style="{ width: Math.min(transparansiSummary.persentase_total || 0, 100) + '%' }"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
              <span class="text-xs font-bold uppercase text-emerald-700 block mb-2">Anggaran Kelurahan</span>
              <h4 class="font-bold text-slate-900 text-base mb-2">Alokasi Dana Kelurahan (ADK)</h4>
              <p class="text-xs text-slate-600 leading-relaxed mb-4">
                Transparansi pemanfaatan pos dana sarana prasarana fisik lingkungan dan pemberdayaan masyarakat se-Kelurahan Kraksaan Wetan.
              </p>
              <router-link to="/transparansi" class="text-xs font-bold text-emerald-700 hover:underline inline-flex items-center gap-1">
                <span>Rincian Pos Dana &rarr;</span>
              </router-link>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
              <span class="text-xs font-bold uppercase text-emerald-700 block mb-2">Program Bantuan Sosial</span>
              <h4 class="font-bold text-slate-900 text-base mb-2">Verifikasi DTKS & P3KE</h4>
              <p class="text-xs text-slate-600 leading-relaxed mb-4">
                Penyaluran bantuan beras CBP, PKH, BPNT, dan BLT diverifikasi berkala melalui musyawarah kelurahan agar tepat sasaran bagi keluarga prasejahtera.
              </p>
              <span class="inline-block px-3 py-1 rounded-lg bg-emerald-50 text-emerald-800 text-xs font-medium">Pengawasan Tiga Pilar</span>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
              <span class="text-xs font-bold uppercase text-emerald-700 block mb-2">Perencanaan Pembangunan</span>
              <h4 class="font-bold text-slate-900 text-base mb-2">Hasil Musrenbangkel</h4>
              <p class="text-xs text-slate-600 leading-relaxed mb-4">
                Dokumen berita acara prioritas usulan pembangunan warga dari rembuk RT/RW yang diteruskan ke Musrenbang Kecamatan Kraksaan.
              </p>
              <router-link to="/berita" class="text-xs font-bold text-emerald-700 hover:underline">
                Baca Liputan Musrenbangkel &rarr;
              </router-link>
            </div>
          </div>
        </section>
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const statistik = ref({});
const transparansiSummary = ref(null);

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val || 0);
};

onMounted(async () => {
  try {
    const [statRes, transpRes] = await Promise.allSettled([
      KelurahanService.getStatistik(),
      KelurahanService.getTransparansi()
    ]);
    if (statRes.status === 'fulfilled') {
      statistik.value = statRes.value || {};
    }
    if (transpRes.status === 'fulfilled' && transpRes.value?.summary) {
      transparansiSummary.value = transpRes.value.summary;
    }
  } finally {
    loading.value = false;
  }
});
</script>
