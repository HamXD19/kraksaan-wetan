<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Profil', to: '/profil' }, { label: 'Struktur Organisasi' }]" />

    <!-- Hero Banner Header -->
    <HeroPageHeader 
      badge-text="Bagan Tata Kelola"
      title="Struktur Organisasi Kelurahan"
      description="Susunan tata laksana kepengurusan Pemerintah Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo."
      :profil="profil"
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <LoadingSpinner v-if="loading" />
      <div v-else class="space-y-12">
        <!-- Bagan Visual Hierarki Sederhana & Elegan (Tetap Menurun dengan Foto & Jabatan) -->
        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-sm text-center reveal overflow-hidden">
          <div class="max-w-2xl mx-auto mb-10 text-center">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Hierarki Kepemimpinan & Tata Kerja
            </div>
            <h3 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
              Bagan Struktur Organisasi Pemerintah Kelurahan
            </h3>
            <p class="text-xs text-slate-500 mt-1">
              Garis koordinasi dan komando kepemimpinan dalam melayani urusan administrasi dan kemasyarakatan.
            </p>
          </div>

          <!-- Tingkat 1: Kepala Kelurahan (Lurah) -->
          <div class="max-w-md mx-auto mb-2 reveal-scale delay-75">
            <div class="relative bg-gradient-to-br from-[#022c22] via-[#064e3b] to-[#022c22] text-white rounded-3xl p-6 shadow-xl border-2 border-amber-400 overflow-hidden text-center sm:text-left">
              <div class="absolute -right-6 -bottom-6 w-24 h-24 bg-amber-400/10 rounded-full blur-xl pointer-events-none"></div>

              <div class="flex flex-col sm:flex-row items-center gap-5">
                <!-- Foto Lurah -->
                <div class="relative w-24 h-28 sm:w-28 sm:h-32 rounded-2xl overflow-hidden shrink-0 border-2 border-amber-300 shadow-md bg-emerald-950 flex items-center justify-center">
                  <img 
                    v-if="profil.lurah?.foto" 
                    :src="profil.lurah?.foto" 
                    :alt="profil.lurah?.nama" 
                    class="w-full h-full object-cover object-top"
                  />
                  <div v-else class="w-full h-full flex flex-col items-center justify-center text-amber-300 bg-emerald-900">
                    <svg class="w-10 h-10 opacity-70" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span class="text-[9px] font-bold mt-1">LURAH</span>
                  </div>
                  <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-400 text-emerald-950 shadow-2xs">
                    Pimpinan
                  </span>
                </div>

                <!-- Keterangan Lurah -->
                <div class="flex-1 min-w-0 space-y-1.5">
                  <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-amber-400/20 text-amber-300 border border-amber-400/40 text-[10px] font-black uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    {{ profil.lurah?.jabatan || 'Lurah Kraksaan Wetan' }}
                  </div>
                  <h4 class="text-base sm:text-lg font-black text-white leading-tight">
                    {{ profil.lurah?.nama || 'Ahmad Fauzi, S.STP., M.Si.' }}
                  </h4>
                  <p class="text-[11px] font-mono text-emerald-200">
                    NIP. {{ profil.lurah?.nip || '-' }}
                  </p>
                  <p class="text-[11px] text-emerald-200/80 leading-snug pt-1">
                    Pemimpin pelaksanaan urusan pemerintahan umum, ketentraman, dan pembangunan kelurahan.
                  </p>
                </div>
              </div>
            </div>

            <!-- Garis Penghubung Turun ke Sekretariat -->
            <div class="w-0.5 h-10 bg-emerald-600 mx-auto relative">
              <span class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-2.5 h-2.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100"></span>
            </div>
          </div>

          <!-- Tingkat 2: Sekretaris Kelurahan (Dinamis DB) -->
          <div v-if="sekretaris" class="max-w-md mx-auto mb-2 reveal-scale delay-150">
            <div class="bg-white rounded-3xl p-5 shadow-lg border-2 border-emerald-600 text-slate-800 text-center sm:text-left">
              <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Foto Sekretaris -->
                <div class="relative w-20 h-24 sm:w-24 sm:h-28 rounded-2xl overflow-hidden shrink-0 border-2 border-emerald-500 shadow-sm bg-slate-100 flex items-center justify-center">
                  <img 
                    v-if="sekretaris.foto" 
                    :src="sekretaris.foto" 
                    :alt="sekretaris.nama" 
                    class="w-full h-full object-cover object-top"
                  />
                  <div v-else class="w-full h-full flex flex-col items-center justify-center text-emerald-700 bg-emerald-50">
                    <svg class="w-8 h-8 opacity-70" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span class="text-[9px] font-bold mt-1 text-center px-1">SEKRETARIS</span>
                  </div>
                  <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded text-[8px] font-bold bg-emerald-700 text-white">
                    Sekretariat
                  </span>
                </div>

                <!-- Keterangan Sekretaris -->
                <div class="flex-1 min-w-0 space-y-1">
                  <div class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 text-[10px] font-extrabold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    {{ sekretaris.jabatan }}
                  </div>
                  <h4 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">
                    {{ sekretaris.nama }}
                  </h4>
                  <p class="text-xs text-emerald-700 font-semibold">
                    {{ sekretaris.bidang || 'Penyelenggaraan Administrasi & Pelayanan Internal' }}
                  </p>
                  <p class="text-[11px] text-slate-500 leading-snug">
                    Mengoordinasikan perumusan kebijakan teknis, urusan umum, dan pelaporan akuntabilitas.
                  </p>
                </div>
              </div>
            </div>

            <!-- Garis Penghubung Turun ke Seksi-Seksi -->
            <div class="w-0.5 h-10 bg-emerald-600 mx-auto relative">
              <span class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-2.5 h-2.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100"></span>
            </div>
          </div>

          <!-- Tingkat 3: Seksi-Seksi (Kasi) / Perangkat Teknis (Percabangan Menurun) -->
          <div v-if="seksiList.length" class="max-w-5xl mx-auto mb-8 reveal delay-200">
            <!-- Label Tingkat Seksi -->
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-[11px] font-bold uppercase tracking-wider mb-6">
              <span>Seksi Pelaksana Kewilayahan & Pelayanan</span>
            </div>

            <!-- Tree Horizontal Line (Desktop) -->
            <div class="hidden sm:block relative mb-6">
              <div class="h-0.5 bg-emerald-600 mx-auto w-3/4"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              <div 
                v-for="(s, sIdx) in seksiList" 
                :key="s.id || sIdx"
                class="relative bg-white rounded-3xl p-5 border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col items-center text-center group"
              >
                <!-- Top Connector Dot -->
                <div class="hidden sm:block absolute -top-3 left-1/2 -translate-x-1/2 w-2.5 h-2.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100"></div>

                <!-- Foto Kasi -->
                <div class="relative w-20 h-24 sm:w-24 sm:h-28 rounded-2xl overflow-hidden shrink-0 border-2 border-emerald-400 shadow-xs bg-slate-50 flex items-center justify-center mb-3">
                  <img 
                    v-if="s.foto" 
                    :src="s.foto" 
                    :alt="s.nama" 
                    class="w-full h-full object-cover object-top"
                  />
                  <div v-else class="w-full h-full flex flex-col items-center justify-center text-emerald-700 bg-emerald-50">
                    <svg class="w-8 h-8 opacity-60" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    <span class="text-[10px] font-bold mt-1 text-center px-1">KASI</span>
                  </div>
                </div>

                <!-- Badge Jabatan Kasi -->
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200 mb-2 leading-tight">
                  {{ s.jabatan }}
                </span>

                <!-- Nama Lengkap -->
                <h5 class="text-sm font-bold text-slate-900 leading-tight mb-1">
                  {{ s.nama }}
                </h5>

                <!-- Tugas / Bidang -->
                <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                  {{ s.bidang || 'Pelaksana Urusan Teknis Kelurahan' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Tingkat 4: Staf Pelaksana Teknis & Administrasi (Jika Ada) -->
          <div v-if="stafList.length" class="max-w-4xl mx-auto pt-4 reveal delay-300">
            <!-- Garis Penghubung Menurun ke Staf -->
            <div class="w-0.5 h-8 bg-emerald-600 mx-auto relative mb-6">
              <span class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-2.5 h-2.5 rounded-full bg-emerald-600 ring-4 ring-emerald-100"></span>
            </div>

            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-[11px] font-bold uppercase tracking-wider mb-6">
              <span>Staf Pelaksana & Administrasi Teknis</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
              <div 
                v-for="(st, stIdx) in stafList" 
                :key="st.id || stIdx"
                class="bg-slate-50 rounded-2xl p-4 border border-slate-200 shadow-2xs hover:shadow-xs transition flex items-center gap-3.5 text-left"
              >
                <!-- Foto Staf -->
                <div class="relative w-14 h-16 rounded-xl overflow-hidden shrink-0 border border-slate-300 bg-white flex items-center justify-center">
                  <img 
                    v-if="st.foto" 
                    :src="st.foto" 
                    :alt="st.nama" 
                    class="w-full h-full object-cover object-top"
                  />
                  <div v-else class="w-full h-full flex items-center justify-center text-emerald-700 bg-emerald-50 font-bold text-xs">
                    {{ st.nama.charAt(0) }}
                  </div>
                </div>

                <div class="flex-1 min-w-0">
                  <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-white text-emerald-800 border border-slate-200 mb-1">
                    {{ st.jabatan }}
                  </span>
                  <h6 class="text-xs font-bold text-slate-900 truncate">{{ st.nama }}</h6>
                  <p class="text-[11px] text-slate-500 truncate">{{ st.bidang || 'Pelaksana Teknis' }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabel Lengkap Aparatur Kelurahan -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-8 reveal delay-100">
          <h3 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
            Daftar Perangkat & Staf Kelurahan Kraksaan Wetan
          </h3>
          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs sm:text-sm">
              <thead>
                <tr class="bg-slate-100 text-slate-700 font-bold border-b border-slate-200">
                  <th class="py-3 px-4">No</th>
                  <th class="py-3 px-4">Foto</th>
                  <th class="py-3 px-4">Nama Aparatur</th>
                  <th class="py-3 px-4">Jabatan</th>
                  <th class="py-3 px-4">Tugas / Bidang Pokok</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-slate-600">
                <!-- Row Lurah -->
                <tr class="hover:bg-slate-50">
                  <td class="py-3 px-4 font-semibold">1</td>
                  <td class="py-3 px-4">
                    <div class="w-10 h-12 rounded-lg overflow-hidden border border-amber-300 bg-slate-100 shrink-0">
                      <img 
                        v-if="profil.lurah?.foto" 
                        :src="profil.lurah?.foto" 
                        :alt="profil.lurah?.nama" 
                        class="w-full h-full object-cover object-top"
                      />
                      <div v-else class="w-full h-full bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs">
                        L
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ profil.lurah?.nama }}</td>
                  <td class="py-3 px-4 text-emerald-700 font-semibold">{{ profil.lurah?.jabatan || 'Lurah' }}</td>
                  <td class="py-3 px-4">Pemimpin pelaksanaan urusan pemerintahan, ketertiban umum, dan pemberdayaan masyarakat</td>
                </tr>

                <!-- Rows Perangkat -->
                <tr v-for="(p, i) in profil.perangkat" :key="p.id || i" class="hover:bg-slate-50">
                  <td class="py-3 px-4 font-semibold">{{ i + 2 }}</td>
                  <td class="py-3 px-4">
                    <div class="w-10 h-12 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 shrink-0">
                      <img 
                        v-if="p.foto" 
                        :src="p.foto" 
                        :alt="p.nama" 
                        class="w-full h-full object-cover object-top"
                      />
                      <div v-else class="w-full h-full bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-xs">
                        {{ p.nama.charAt(0) }}
                      </div>
                    </div>
                  </td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ p.nama }}</td>
                  <td class="py-3 px-4 text-emerald-700 font-semibold">{{ p.jabatan }}</td>
                  <td class="py-3 px-4">{{ p.bidang || '-' }}</td>
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
import { ref, computed, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import HeroPageHeader from '../components/HeroPageHeader.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const profil = ref({});

const sekretaris = computed(() => {
  if (!profil.value.perangkat || !profil.value.perangkat.length) return null;
  // Cari jabatan mengandung kata 'Sekretaris'
  const found = profil.value.perangkat.find(p => p.jabatan && p.jabatan.toLowerCase().includes('sekretaris'));
  return found || null;
});

const seksiList = computed(() => {
  if (!profil.value.perangkat || !profil.value.perangkat.length) return [];
  const secId = sekretaris.value?.id;
  // Perangkat selain sekretaris yang mengandung kata 'Kasi' atau 'Seksi'
  const kasis = profil.value.perangkat.filter(p => 
    p.id !== secId && p.jabatan && (p.jabatan.toLowerCase().includes('kasi') || p.jabatan.toLowerCase().includes('seksi'))
  );
  if (kasis.length) return kasis;
  // Fallback: perangkat selain sekretaris ambil hingga 3
  return profil.value.perangkat.filter(p => p.id !== secId).slice(0, 3);
});

const stafList = computed(() => {
  if (!profil.value.perangkat || !profil.value.perangkat.length) return [];
  const secId = sekretaris.value?.id;
  const seksiIds = seksiList.value.map(s => s.id);
  // Perangkat selain sekretaris dan selain kasi
  return profil.value.perangkat.filter(p => p.id !== secId && !seksiIds.includes(p.id));
});

onMounted(async () => {
  try {
    profil.value = await KelurahanService.getProfil();
  } finally {
    loading.value = false;
  }
});
</script>
