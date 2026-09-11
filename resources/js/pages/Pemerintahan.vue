<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Pemerintahan' }]" />

    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Penyelenggaraan Pemerintahan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Pemerintahan Kelurahan Kraksaan Wetan</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl">
          Sinergi kepemimpinan kelurahan, aparatur sipil, pimpinan RT/RW, dan lembaga kemasyarakatan.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-16">
      <LoadingSpinner v-if="loading" />
      <template v-else>
        <!-- Section 1: Profil Lurah (#lurah) -->
        <section id="lurah" class="scroll-mt-24 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
          <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
            <div class="md:col-span-4 text-center">
              <div class="w-48 h-56 mx-auto rounded-2xl overflow-hidden shadow-lg border-4 border-emerald-100 bg-slate-100">
                <img 
                  :src="profil.lurah?.foto || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80'" 
                  :alt="profil.lurah?.nama" 
                  class="w-full h-full object-cover"
                />
              </div>
              <h3 class="font-bold text-slate-900 mt-4 text-base">{{ profil.lurah?.nama }}</h3>
              <p class="text-xs text-emerald-700 font-semibold">{{ profil.lurah?.jabatan }}</p>
              <p class="text-[11px] text-slate-500">NIP. {{ profil.lurah?.nip }}</p>
            </div>

            <div class="md:col-span-8 space-y-4">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                Sambutan Kepala Kelurahan
              </div>
              <h2 class="text-2xl font-bold text-slate-900 tracking-tight">
                Mewujudkan Kraksaan Wetan yang Transparan dan Melayani Sepenuh Hati
              </h2>
              <blockquote class="text-xs sm:text-sm text-slate-700 leading-relaxed italic border-l-4 border-amber-400 pl-4 bg-slate-50 p-4 rounded-r-xl">
                "{{ profil.lurah?.sambutan }}"
              </blockquote>
              <p class="text-xs text-slate-600 leading-relaxed">
                Pemerintah Kelurahan Kraksaan Wetan terus berkomitmen meningkatkan sarana digitalisasi pelayanan, menjembatani aspirasi masyarakat ke tingkat kecamatan dan kabupaten, serta mengawal pembangunan berbasis gotong royong.
              </p>
            </div>
          </div>
        </section>

        <!-- Section 2: Perangkat Kelurahan (#perangkat) -->
        <section id="perangkat" class="scroll-mt-24">
          <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Aparatur Pelayanan
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Perangkat & Pelaksana Teknis
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
              Jajaran aparatur yang siap melayani kebutuhan administratif dan urusan masyarakat sehari-hari.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div 
              v-for="(p, i) in profil.perangkat" 
              :key="i"
              class="bg-white rounded-2xl p-6 border border-slate-200 shadow-xs hover:shadow-md transition"
            >
              <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 font-bold flex items-center justify-center text-sm mb-4">
                {{ p.nama.charAt(0) }}
              </div>
              <h4 class="font-bold text-slate-900 text-sm mb-1">{{ p.nama }}</h4>
              <p class="text-xs font-semibold text-emerald-700 mb-2">{{ p.jabatan }}</p>
              <p class="text-xs text-slate-500 border-t border-slate-100 pt-2">{{ p.bidang }}</p>
            </div>
          </div>
        </section>

        <!-- Section 3: Rukun Tetangga & RW (#rtrw) -->
        <section id="rtrw" class="scroll-mt-24 bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                Garis Depan Lingkungan
              </div>
              <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Rukun Warga (RW) & Rukun Tetangga (RT)</h2>
              <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Kelurahan Kraksaan Wetan terbagi atas 7 RW dan 28 RT yang tersebar di wilayah strategis.
              </p>
            </div>
            <div class="text-xs font-semibold px-4 py-2 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 self-start sm:self-auto">
              Total 7 RW &bull; 28 RT
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div 
              v-for="lingk in statistik.lingkungan" 
              :key="lingk.nama"
              class="p-4 rounded-xl bg-slate-50 border border-slate-200"
            >
              <div class="flex items-center justify-between mb-1">
                <h4 class="font-bold text-slate-900 text-xs sm:text-sm">{{ lingk.nama }}</h4>
                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">{{ lingk.rt }} RT</span>
              </div>
              <p class="text-xs text-slate-500">Estimasi Penduduk: ~{{ lingk.penduduk }} Jiwa</p>
            </div>
          </div>
        </section>

        <!-- Section 4: Lembaga Kemasyarakatan (#lembaga) -->
        <section id="lembaga" class="scroll-mt-24">
          <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Mitra Pembangunan
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Lembaga Kemasyarakatan Kelurahan (LKK)
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
              Wadah partisipasi masyarakat dalam perencanaan, pelaksanaan, dan pelestarian hasil pembangunan.
            </p>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div 
              v-for="l in lembagaList" 
              :key="l.id"
              class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs hover:shadow-md transition flex flex-col justify-between"
            >
              <div>
                <div class="flex items-center justify-between gap-2 mb-4">
                  <div 
                    class="w-12 h-12 rounded-xl flex items-center justify-center font-bold text-xs shadow-xs uppercase"
                    :class="getThemeBadgeClass(l.warna_tema)"
                  >
                    {{ l.singkatan || l.nama.substring(0, 3) }}
                  </div>
                  <span 
                    v-if="l.kategori" 
                    class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                    :class="getThemePillClass(l.warna_tema)"
                  >
                    {{ l.kategori }}
                  </span>
                </div>
                <h4 class="font-bold text-slate-900 text-base mb-1">{{ l.nama }}</h4>
                <p v-if="l.singkatan && l.nama !== l.singkatan" class="text-xs text-emerald-700 font-semibold mb-2">
                  ({{ l.singkatan }})
                </p>
                <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ l.deskripsi }}</p>
              </div>

              <div class="pt-3 border-t border-slate-100 text-[11px] text-slate-500 space-y-1">
                <p v-if="l.ketua"><strong class="text-slate-700">Ketua:</strong> {{ l.ketua }}</p>
                <p v-if="l.jumlah_anggota"><strong class="text-slate-700">Kader/Anggota:</strong> {{ l.jumlah_anggota }}</p>
              </div>
            </div>
          </div>

          <!-- Link Navigasi ke Halaman Penuh Lembaga -->
          <div class="mt-8 text-center">
            <router-link 
              to="/lembaga" 
              class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-800 hover:bg-emerald-900 text-amber-300 font-bold text-xs shadow-xs transition"
            >
              <span>Buka Halaman Detail Lembaga & Program Kerja</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </router-link>
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
const profil = ref({});
const statistik = ref({});
const lembagaList = ref([]);

const getThemeBadgeClass = (theme) => {
  switch (theme) {
    case 'amber': return 'bg-amber-100 text-amber-900 border border-amber-300/80';
    case 'rose': return 'bg-rose-100 text-rose-900 border border-rose-300/80';
    case 'blue': return 'bg-blue-100 text-blue-900 border border-blue-300/80';
    case 'indigo': return 'bg-indigo-100 text-indigo-900 border border-indigo-300/80';
    case 'purple': return 'bg-purple-100 text-purple-900 border border-purple-300/80';
    case 'emerald':
    default: return 'bg-emerald-100 text-emerald-900 border border-emerald-300/80';
  }
};

const getThemePillClass = (theme) => {
  switch (theme) {
    case 'amber': return 'bg-amber-100 text-amber-800';
    case 'rose': return 'bg-rose-100 text-rose-800';
    case 'blue': return 'bg-blue-100 text-blue-800';
    case 'indigo': return 'bg-indigo-100 text-indigo-800';
    case 'purple': return 'bg-purple-100 text-purple-800';
    case 'emerald':
    default: return 'bg-emerald-100 text-emerald-800';
  }
};

onMounted(async () => {
  try {
    const [p, s, l] = await Promise.all([
      KelurahanService.getProfil(),
      KelurahanService.getStatistik(),
      KelurahanService.getLembaga()
    ]);
    profil.value = p;
    statistik.value = s;
    lembagaList.value = l || [];
  } finally {
    loading.value = false;
  }
});
</script>
