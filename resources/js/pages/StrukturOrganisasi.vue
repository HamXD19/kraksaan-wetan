<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Profil', to: '/profil' }, { label: 'Struktur Organisasi' }]" />

    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Bagan Tata Kelola</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Struktur Organisasi Kelurahan</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl">
          Susunan tata laksana kepengurusan Pemerintah Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <LoadingSpinner v-if="loading" />
      <div v-else class="space-y-12">
        <!-- Bagan Visual Hierarki Sederhana & Elegan -->
        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-sm text-center">
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-8">
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            Hierarki Kepemimpinan
          </div>

          <!-- Tingkat 1: Lurah -->
          <div class="max-w-xs mx-auto mb-8">
            <div class="p-5 rounded-2xl bg-gradient-to-b from-emerald-800 to-emerald-900 text-white shadow-lg border-2 border-amber-400">
              <span class="text-[10px] uppercase font-bold text-amber-300 tracking-wider">Pemimpin Kelurahan</span>
              <h3 class="text-base font-bold mt-1">{{ profil.lurah?.nama }}</h3>
              <p class="text-xs text-emerald-200">{{ profil.lurah?.jabatan }}</p>
              <p class="text-[10px] text-emerald-300 mt-1">NIP. {{ profil.lurah?.nip }}</p>
            </div>
            <div class="w-0.5 h-8 bg-emerald-700 mx-auto"></div>
          </div>

          <!-- Tingkat 2: Sekretaris Kelurahan -->
          <div class="max-w-xs mx-auto mb-8">
            <div class="p-4 rounded-xl bg-white border-2 border-emerald-600 text-slate-800 shadow-md">
              <span class="text-[10px] uppercase font-bold text-emerald-700 tracking-wider">Sekretariat</span>
              <h4 class="text-sm font-bold text-slate-900 mt-0.5">Bambang Supriyanto, S.AP.</h4>
              <p class="text-xs text-slate-500">Sekretaris Kelurahan</p>
            </div>
            <div class="w-0.5 h-8 bg-emerald-700 mx-auto"></div>
          </div>

          <!-- Tingkat 3: Seksi-Seksi (Kasi) -->
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-4xl mx-auto">
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 shadow-xs">
              <span class="text-[10px] uppercase font-bold text-emerald-800 tracking-wider">Seksi Pemerintahan</span>
              <h5 class="text-xs font-bold text-slate-900 mt-1">Siti Aminah, S.Sos.</h5>
              <p class="text-[11px] text-slate-500">Kasi Pemerintahan, Trantibum</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 shadow-xs">
              <span class="text-[10px] uppercase font-bold text-emerald-800 tracking-wider">Seksi Ekbang</span>
              <h5 class="text-xs font-bold text-slate-900 mt-1">H. Mulyadi, S.E.</h5>
              <p class="text-[11px] text-slate-500">Kasi Perekonomian & Pembangunan</p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 shadow-xs">
              <span class="text-[10px] uppercase font-bold text-emerald-800 tracking-wider">Seksi Kesra</span>
              <h5 class="text-xs font-bold text-slate-900 mt-1">Dewi Lestari, S.Pd.</h5>
              <p class="text-[11px] text-slate-500">Kasi Kesejahteraan Rakyat</p>
            </div>
          </div>
        </div>

        <!-- Tabel Lengkap Aparatur Kelurahan -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8">
          <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
            Daftar Perangkat & Staf Kelurahan Kraksaan Wetan
          </h3>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                  <th class="py-3 px-4">No</th>
                  <th class="py-3 px-4">Nama Aparatur</th>
                  <th class="py-3 px-4">Jabatan</th>
                  <th class="py-3 px-4">Tugas / Bidang Pokok</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-slate-600">
                <tr class="hover:bg-slate-50">
                  <td class="py-3 px-4 font-semibold">1</td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ profil.lurah?.nama }}</td>
                  <td class="py-3 px-4 text-emerald-700 font-semibold">Lurah</td>
                  <td class="py-3 px-4">Pemimpin pelaksanaan urusan pemerintahan, ketertiban umum, dan pemberdayaan masyarakat</td>
                </tr>
                <tr v-for="(p, i) in profil.perangkat" :key="i" class="hover:bg-slate-50">
                  <td class="py-3 px-4 font-semibold">{{ i + 2 }}</td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ p.nama }}</td>
                  <td class="py-3 px-4 text-emerald-700 font-semibold">{{ p.jabatan }}</td>
                  <td class="py-3 px-4">{{ p.bidang }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const profil = ref({});

onMounted(async () => {
  try {
    profil.value = await KelurahanService.getProfil();
  } finally {
    loading.value = false;
  }
});
</script>
