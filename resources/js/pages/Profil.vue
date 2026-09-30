<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Profil Kelurahan' }]" />

    <!-- Page Header Banner -->
    <HeroPageHeader 
      badge-text="Profil & Identitas"
      :title="`Tentang ${profil.nama || 'Kelurahan Kraksaan Wetan'}`"
      :description="`Informasi umum, letak geografis, karakteristik wilayah, dan potensi ${profil.nama || 'Kelurahan Kraksaan Wetan'}.`"
      :profil="profil"
    />

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
              Sebagai bagian integral dari kawasan perkotaan {{ profil.kecamatan || 'Kraksaan' }} yang ditetapkan sebagai Ibu Kota {{ profil.kabupaten || 'Kabupaten Probolinggo' }} berdasarkan PP No. 2 Tahun 2010, {{ profil.nama || 'Kelurahan Kraksaan Wetan' }} memegang peranan krusial sebagai simpul perekonomian rakyat, perumahan padat tertata, serta pusat layanan pemerintahan tingkat dasar.
            </p>
          </div>

          <!-- Ringkasan Data Wilayah & Kependudukan (Dinamis DB) -->
          <div class="bg-gradient-to-br from-emerald-50/70 to-slate-50 p-5 sm:p-6 rounded-2xl border border-emerald-100 reveal delay-100">
            <h3 class="font-bold text-slate-900 text-sm mb-4 uppercase tracking-wider flex items-center gap-2">
              <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
              Indikator Geografis & Demografi
            </h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
              <div class="p-3 bg-white rounded-xl border border-slate-200/80 shadow-xs">
                <span class="text-[10px] text-slate-400 font-semibold uppercase block">Luas Wilayah</span>
                <span class="text-base font-extrabold text-emerald-900">{{ statistik.luas_wilayah || '1.84' }} <span class="text-xs font-normal">km²</span></span>
              </div>
              <div class="p-3 bg-white rounded-xl border border-slate-200/80 shadow-xs">
                <span class="text-[10px] text-slate-400 font-semibold uppercase block">Kepadatan</span>
                <span class="text-base font-extrabold text-emerald-900">{{ statistik.kepadatan || '3.718/km²' }}</span>
              </div>
              <div class="p-3 bg-white rounded-xl border border-slate-200/80 shadow-xs">
                <span class="text-[10px] text-slate-400 font-semibold uppercase block">Jumlah RW / RT</span>
                <span class="text-base font-extrabold text-emerald-900">{{ statistik.rw || 7 }} RW <span class="text-xs font-normal">/ {{ statistik.rt || 22 }} RT</span></span>
              </div>
              <div class="p-3 bg-white rounded-xl border border-slate-200/80 shadow-xs">
                <span class="text-[10px] text-slate-400 font-semibold uppercase block">Total Penduduk</span>
                <span class="text-base font-extrabold text-emerald-900">{{ Number(statistik.penduduk || 6842).toLocaleString('id-ID') }} <span class="text-xs font-normal">Jiwa</span></span>
              </div>
            </div>
          </div>

          <!-- Batas Wilayah (Dinamis DB) -->
          <div v-if="profil.batas_wilayah" class="bg-slate-50 p-5 rounded-xl border border-slate-200 reveal delay-150">
            <h3 class="font-bold text-slate-800 text-sm mb-3 uppercase tracking-wider">Batas-Batas Wilayah Administratif</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-600">
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Utara</span>
                {{ profil.batas_wilayah.utara || 'Desa Kalibuntu & Selat Madura' }}
              </div>
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Selatan</span>
                {{ profil.batas_wilayah.selatan || 'Desa Sumberlele & Kecamatan Besuk' }}
              </div>
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Timur</span>
                {{ profil.batas_wilayah.timur || 'Desa Bulu & Desa Rondokuning' }}
              </div>
              <div class="p-3 bg-white rounded-lg border border-slate-200/80">
                <span class="font-bold text-emerald-800 block mb-0.5">Barat</span>
                {{ profil.batas_wilayah.barat || 'Sungai Kraksaan & Kelurahan Patokan' }}
              </div>
            </div>
          </div>

          <!-- Potensi Unggulan (Dinamis DB) -->
          <div v-if="profil.potensi_unggulan && profil.potensi_unggulan.length" class="reveal delay-200">
            <h2 class="text-xl font-bold text-slate-900 mb-3 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
              Potensi Unggulan Kelurahan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
              <div 
                v-for="(pot, pIdx) in profil.potensi_unggulan" 
                :key="pIdx"
                class="p-4 rounded-xl border border-emerald-100 bg-emerald-50/50"
              >
                <h4 class="font-bold text-emerald-900 mb-1 text-sm">{{ pot.judul }}</h4>
                <p class="text-slate-600">{{ pot.deskripsi }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar Info -->
        <div class="lg:col-span-4 space-y-6 reveal-right delay-100">
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

          <!-- Informasi Kontak & Kantor (Dinamis DB) -->
          <div class="bg-emerald-950 text-white p-6 rounded-2xl shadow-sm space-y-4">
            <div>
              <h4 class="font-bold text-amber-400 text-xs uppercase tracking-wider mb-2">Kantor Kelurahan</h4>
              <p class="text-xs text-emerald-100 leading-relaxed">
                {{ profil.alamat }}
              </p>
            </div>

            <div class="space-y-2 pt-2 border-t border-emerald-800/80 text-xs">
              <div v-if="profil.jam_kerja" class="flex items-start gap-2 text-emerald-200">
                <svg class="w-4 h-4 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ profil.jam_kerja }}</span>
              </div>
              <div v-if="profil.telepon" class="flex items-center gap-2 text-emerald-200">
                <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>{{ profil.telepon }}</span>
              </div>
              <div v-if="profil.email" class="flex items-center gap-2 text-emerald-200 break-all">
                <svg class="w-4 h-4 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>{{ profil.email }}</span>
              </div>
            </div>

            <router-link 
              to="/kontak"
              class="block text-center py-2 px-4 rounded-lg bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition shadow-sm"
            >
              Lihat Denah & Kontak Lengkap
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
import HeroPageHeader from '../components/HeroPageHeader.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const profil = ref({});
const statistik = ref({});

onMounted(async () => {
  try {
    const [profilRes, statRes] = await Promise.all([
      KelurahanService.getProfil(),
      KelurahanService.getStatistik().catch(() => ({}))
    ]);
    profil.value = profilRes;
    statistik.value = statRes;
  } finally {
    loading.value = false;
  }
});
</script>
