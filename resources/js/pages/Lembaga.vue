<template>
  <div class="pb-16">
    <!-- Breadcrumb -->
    <Breadcrumb :items="[{ label: 'Pemerintahan', to: '/pemerintahan' }, { label: 'Lembaga Kemasyarakatan' }]" />

    <!-- Hero Header Section -->
    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Mitra Pembangunan & Pemberdayaan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Lembaga Kemasyarakatan Kelurahan (LKK)</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl leading-relaxed">
          Wadah partisipasi aktif masyarakat, gotong royong, dan sinergi kemitraan bersama Pemerintah Kelurahan Kraksaan Wetan dalam perencanaan, pelaksanaan, dan pelestarian pembangunan.
        </p>
      </div>
    </section>

    <!-- Main Content Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-10">
      <LoadingSpinner v-if="loading" />

      <template v-else>
        <!-- Filter Kategori & Info Ringkas -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-5">
          <!-- Kategori Tabs -->
          <div class="flex items-center gap-2 overflow-x-auto pb-1 max-w-full">
            <button 
              v-for="cat in availableCategories" 
              :key="cat"
              @click="selectedCategory = cat"
              class="px-3.5 py-1.5 rounded-full text-xs font-bold transition whitespace-nowrap"
              :class="selectedCategory === cat ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
            >
              {{ cat }}
            </button>
          </div>

          <div class="text-xs text-slate-500 font-semibold self-start sm:self-auto whitespace-nowrap">
            Menampilkan {{ filteredLembaga.length }} Lembaga Aktif
          </div>
        </div>

        <!-- Cards Grid Lembaga Kemasyarakatan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div 
            v-for="item in filteredLembaga" 
            :key="item.id"
            class="bg-white rounded-3xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden"
          >
            <!-- Card Header & Profil -->
            <div class="p-6 sm:p-7 space-y-5">
              <!-- Top Row: Icon Badge & Kategori -->
              <div class="flex items-start justify-between gap-3">
                <div 
                  class="w-13 h-13 rounded-2xl flex items-center justify-center font-black text-sm uppercase shadow-xs flex-shrink-0"
                  :class="getThemeClasses(item.warna_tema).badge"
                >
                  {{ item.singkatan || item.nama.substring(0, 3) }}
                </div>

                <span 
                  class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider"
                  :class="getThemeClasses(item.warna_tema).pill"
                >
                  {{ item.kategori || 'Mitra Kelurahan' }}
                </span>
              </div>

              <!-- Judul Lembaga -->
              <div>
                <h3 class="font-extrabold text-slate-900 text-base sm:text-lg leading-snug">
                  {{ item.nama }}
                </h3>
                <p v-if="item.singkatan && item.nama !== item.singkatan" class="text-xs text-emerald-700 font-semibold mt-0.5">
                  ({{ item.singkatan }})
                </p>
              </div>

              <!-- Deskripsi -->
              <p class="text-xs text-slate-600 leading-relaxed">
                {{ item.deskripsi }}
              </p>

              <!-- Struktur / Pimpinan & Kontak -->
              <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2 text-xs">
                <div v-if="item.ketua" class="flex items-center gap-2 text-slate-700">
                  <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  <span class="truncate"><strong>Ketua:</strong> {{ item.ketua }}</span>
                </div>

                <div v-if="item.jumlah_anggota" class="flex items-center gap-2 text-slate-700">
                  <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                  <span class="truncate"><strong>Anggota/Kader:</strong> {{ item.jumlah_anggota }}</span>
                </div>

                <div v-if="item.alamat" class="flex items-center gap-2 text-slate-600 text-[11px]">
                  <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  <span class="truncate">{{ item.alamat }}</span>
                </div>

                <div v-if="item.kontak" class="flex items-center gap-2 text-slate-600 text-[11px]">
                  <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                  <span class="truncate">{{ item.kontak }}</span>
                </div>
              </div>

              <!-- Program Kerja Unggulan -->
              <div v-if="item.program_kerja && item.program_kerja.length" class="space-y-2 pt-2">
                <p class="text-[11px] font-bold text-slate-900 uppercase tracking-wider flex items-center gap-1.5">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                  Program Kerja Utama:
                </p>
                <ul class="space-y-1.5 text-xs text-slate-600">
                  <li 
                    v-for="(prog, idx) in item.program_kerja" 
                    :key="idx" 
                    class="flex items-start gap-2 leading-relaxed"
                  >
                    <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>{{ prog }}</span>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Bottom Footer Card -->
            <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500 font-medium">
              <span>Status: <strong class="text-emerald-700">Aktif & Berjalan</strong></span>
              <span class="text-slate-400">Kraksaan Wetan</span>
            </div>
          </div>
        </div>

        <div v-if="!filteredLembaga.length" class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8 space-y-3">
          <p class="text-base font-bold text-slate-700">Belum ada data lembaga pada kategori ini.</p>
          <p class="text-xs text-slate-400">Silakan pilih kategori "Semua" untuk melihat seluruh lembaga kemasyarakatan.</p>
        </div>

        <!-- Banner Partisipasi & Gotong Royong -->
        <div class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 rounded-3xl p-8 sm:p-10 text-white shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 border border-emerald-800">
          <div class="space-y-2 text-center md:text-left">
            <span class="px-3 py-1 rounded-full bg-amber-400 text-slate-950 font-bold text-xs uppercase tracking-wider">
              Aspirasi & Partisipasi Warga
            </span>
            <h3 class="text-xl sm:text-2xl font-extrabold tracking-tight">
              Ingin Berkolaborasi atau Mengajukan Aspirasi?
            </h3>
            <p class="text-xs sm:text-sm text-emerald-200 max-w-2xl leading-relaxed">
              Pemerintah Kelurahan Kraksaan Wetan senantiasa membuka ruang keterlibatan warga dan pemuda untuk bergabung dalam program-program kemasyarakatan.
            </p>
          </div>

          <router-link 
            to="/kontak" 
            class="px-6 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs shadow-md transition whitespace-nowrap self-center md:self-auto"
          >
            Hubungi Kantor Kelurahan
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
const lembagaList = ref([]);
const selectedCategory = ref('Semua');

const availableCategories = computed(() => {
  const cats = ['Semua'];
  lembagaList.value.forEach(item => {
    if (item.kategori && !cats.includes(item.kategori)) {
      cats.push(item.kategori);
    }
  });
  return cats;
});

const filteredLembaga = computed(() => {
  if (selectedCategory.value === 'Semua') {
    return lembagaList.value;
  }
  return lembagaList.value.filter(item => item.kategori === selectedCategory.value);
});

const getThemeClasses = (theme) => {
  switch (theme) {
    case 'amber':
      return {
        badge: 'bg-amber-100 text-amber-900 border-2 border-amber-300',
        pill: 'bg-amber-100 text-amber-800'
      };
    case 'rose':
      return {
        badge: 'bg-rose-100 text-rose-900 border-2 border-rose-300',
        pill: 'bg-rose-100 text-rose-800'
      };
    case 'blue':
      return {
        badge: 'bg-blue-100 text-blue-900 border-2 border-blue-300',
        pill: 'bg-blue-100 text-blue-800'
      };
    case 'indigo':
      return {
        badge: 'bg-indigo-100 text-indigo-900 border-2 border-indigo-300',
        pill: 'bg-indigo-100 text-indigo-800'
      };
    case 'purple':
      return {
        badge: 'bg-purple-100 text-purple-900 border-2 border-purple-300',
        pill: 'bg-purple-100 text-purple-800'
      };
    case 'emerald':
    default:
      return {
        badge: 'bg-emerald-100 text-emerald-900 border-2 border-emerald-300',
        pill: 'bg-emerald-100 text-emerald-800'
      };
  }
};

onMounted(async () => {
  try {
    const data = await KelurahanService.getLembaga();
    lembagaList.value = data || [];
  } catch (err) {
    console.error('Gagal memuat data lembaga:', err);
  } finally {
    loading.value = false;
  }
});
</script>
