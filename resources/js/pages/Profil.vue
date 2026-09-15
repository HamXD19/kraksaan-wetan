<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Profil Kelurahan' }]" />

    <!-- Page Header Banner -->
    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Profil & Identitas</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Tentang Kelurahan Kraksaan Wetan</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl">
          Informasi umum, letak geografis, karakteristik wilayah, dan potensi Kelurahan Kraksaan Wetan.
        </p>
      </div>
    </section>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <LoadingSpinner v-if="loading" />
      <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main Article -->
        <div class="lg:col-span-8 space-y-8 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs reveal">
          <div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
              Gambaran Umum Wilayah
            </h2>
            <p class="text-sm text-slate-700 leading-relaxed mb-4">
              {{ profil.deskripsi }}
            </p>
            <p class="text-sm text-slate-700 leading-relaxed">
              Sebagai bagian integral dari kawasan perkotaan Kraksaan yang ditetapkan sebagai Ibu Kota Kabupaten Probolinggo berdasarkan PP No. 2 Tahun 2010, Kelurahan Kraksaan Wetan memegang peranan krusial sebagai simpul perekonomian rakyat, perumahan padat tertata, serta pusat layanan pemerintahan tingkat dasar.
            </p>
          </div>

          <!-- Batas Wilayah -->
          <div class="bg-slate-50 p-5 rounded-xl border border-slate-200 reveal delay-100">
            <h3 class="font-bold text-slate-800 text-sm mb-3 uppercase tracking-wider">Batas-Batas Wilayah Administratif</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Utara</span>
                Desa Kalibuntu & Selat Madura
              </div>
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Selatan</span>
                Desa Sumberlele & Kecamatan Besuk
              </div>
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Timur</span>
                Desa Bulu & Desa Rondokuning
              </div>
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Barat</span>
                Sungai Kraksaan & Kelurahan Patokan
              </div>
            </div>
          </div>

          <!-- Potensi Unggulan -->
          <div class="reveal delay-150">
            <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
              Potensi Kelurahan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
              <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50">
                <h4 class="font-bold text-emerald-900 mb-1 text-sm">UMKM Kuliner & Niaga</h4>
                <p class="text-slate-600">Pusat jajanan tradisional, olahan hasil laut Kraksaan, dan pedagang pasar tradisional.</p>
              </div>
              <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50">
                <h4 class="font-bold text-emerald-900 mb-1 text-sm">Kawasan Pemukiman</h4>
                <p class="text-slate-600">Lingkungan RT/RW tertib dengan semangat gotong royong dan posyandu integrasi aktif.</p>
              </div>
              <div class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50">
                <h4 class="font-bold text-emerald-900 mb-1 text-sm">Pelayanan Digital</h4>
                <p class="text-slate-600">Pemanfaatan sistem digital kependudukan dan transparansi informasi warga berbasis website.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-4 space-y-6 reveal delay-100">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <h3 class="font-bold text-slate-900 text-sm mb-4 pb-2 border-b border-slate-100">Navigasi Profil</h3>
            <ul class="space-y-2 text-xs">
              <li>
                <router-link to="/profil" class="block p-2.5 rounded-lg bg-emerald-50 text-emerald-800 font-bold">
                  &bull; Tentang Kelurahan
                </router-link>
              </li>
              <li>
                <router-link to="/profil/sejarah" class="block p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 font-medium">
                  &bull; Sejarah Kelurahan
                </router-link>
              </li>
              <li>
                <router-link to="/profil/visi-misi" class="block p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 font-medium">
                  &bull; Visi & Misi
                </router-link>
              </li>
              <li>
                <router-link to="/profil/struktur-organisasi" class="block p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 font-medium">
                  &bull; Struktur Organisasi
                </router-link>
              </li>
              <li>
                <router-link to="/pemerintahan" class="block p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 font-medium">
                  &bull; Profil Lurah & Perangkat
                </router-link>
              </li>
            </ul>
          </div>

          <div class="bg-emerald-950 text-white p-6 rounded-2xl shadow-sm">
            <h4 class="font-bold text-amber-400 text-xs uppercase tracking-wider mb-2">Kantor Kelurahan</h4>
            <p class="text-xs text-emerald-100 leading-relaxed mb-4">
              {{ profil.alamat }}
            </p>
            <router-link 
              to="/kontak"
              class="block text-center py-2 px-4 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs"
            >
              Lihat Denah & Kontak
            </router-link>
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
