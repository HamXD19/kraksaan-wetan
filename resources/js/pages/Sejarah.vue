<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Profil', to: '/profil' }, { label: 'Sejarah Kelurahan' }]" />

    <!-- Hero Banner Header -->
    <HeroPageHeader 
      badge-text="Asal Usul & Rekam Jejak"
      title="Sejarah Kelurahan Kraksaan Wetan"
      description="Napak tilas perkembangan Kraksaan Wetan dari kawasan bersejarah hingga menjadi pusat kota Probolinggo."
      :profil="profil"
    />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <LoadingSpinner v-if="loading" />
      <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs space-y-6 reveal">
          <div class="prose prose-emerald max-w-none text-slate-700 text-sm leading-relaxed space-y-4">
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 pb-2 border-b border-slate-100 flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
              Akar Sejarah & Asal Usul Nama Kraksaan
            </h2>

            <!-- Narasi Sejarah Resmi (Dinamis DB) -->
            <div v-if="profil.sejarah" class="p-5 rounded-2xl bg-gradient-to-r from-emerald-50 via-emerald-50/50 to-white border-l-4 border-emerald-600 shadow-xs mb-6">
              <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider block mb-1">Catatan Historis Resmi</span>
              <div class="text-slate-800 leading-relaxed text-sm rich-content" v-html="profil.sejarah"></div>
            </div>

            <p>
              Kota Kraksaan memiliki sejarah panjang sejak masa kolonial Hindia Belanda dan era kerajaan Majapahit. Wilayah Kraksaan yang dialiri oleh Sungai Kraksaan (Kali Rondokuning) menjadi kawasan strategis penghubung antara pesisir utara Selat Madura dan jalur transportasi darat Pantura Jawa Timur.
            </p>

            <p>
              Kata <strong>"Kraksaan"</strong> konon berkaitan dengan kondisi alam kawasan yang subur dan ramai aktivitas perdagangan rakyat tempo dulu. Sementara predikat <strong>"Wetan"</strong> (dalam bahasa Jawa berarti Timur) merujuk pada letak geografis wilayah ini yang berada di sisi sebelah timur aliran Kali Kraksaan, membedakannya dengan kawasan barat (Kelurahan Patokan / Kraksaan Kulon).
            </p>

            <div class="my-6 p-5 bg-emerald-50 rounded-xl border border-emerald-200 reveal delay-100">
              <h4 class="font-bold text-emerald-900 text-sm mb-1">Momentum PP No. 2 Tahun 2010</h4>
              <p class="text-xs text-emerald-800">
                Pemerintah Republik Indonesia secara resmi menetapkan Kecamatan Kraksaan sebagai Ibu Kota Kabupaten Probolinggo menggantikan Kota Probolinggo. Ketetapan ini mengukuhkan posisi Kelurahan Kraksaan Wetan sebagai simpul perkotaan, sentra pelayanan publik, dan pemukiman dinamis.
              </p>
            </div>

            <h3 class="text-lg font-bold text-slate-900 pt-4">Tonggak Perkembangan Waktu</h3>
            <div class="space-y-4 pl-4 border-l-2 border-emerald-600 reveal delay-150">
              <div class="relative pl-4">
                <span class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-emerald-600 border-2 border-white"></span>
                <span class="text-xs font-bold text-emerald-700">Era Hindia Belanda & Pra-Kemerdekaan</span>
                <p class="text-xs text-slate-600 mt-1">Berkembang sebagai sentra niaga masyarakat agraris dan pesisir di sekitar stasiun dan jalur pos Daendels.</p>
              </div>
              <div class="relative pl-4">
                <span class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-emerald-600 border-2 border-white"></span>
                <span class="text-xs font-bold text-emerald-700">Peralihan Menjadi Kelurahan Definitif</span>
                <p class="text-xs text-slate-600 mt-1">Status tata kelola pemerintahan bertransformasi menjadi kelurahan dengan penataan administrasi RT/RW modern.</p>
              </div>
              <div class="relative pl-4">
                <span class="absolute -left-[21px] top-1 w-3 h-3 rounded-full bg-amber-500 border-2 border-white"></span>
                <span class="text-xs font-bold text-amber-700">Tahun 2010 - Sekarang: Ibu Kota Kabupaten</span>
                <p class="text-xs text-slate-600 mt-1">Pusat pemekaran infrastruktur perkotaan, digitalisasi pelayanan, dan penguatan UMKM warga.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-6 reveal-right delay-100">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <h3 class="font-bold text-slate-900 text-sm mb-4 pb-2 border-b border-slate-100">Menu Profil</h3>
            <ul class="space-y-2 text-xs">
              <li>
                <router-link to="/profil" class="block p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 font-medium">
                  &bull; Tentang Kelurahan
                </router-link>
              </li>
              <li>
                <router-link to="/profil/sejarah" class="block p-2.5 rounded-lg bg-emerald-50 text-emerald-800 font-bold">
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
            </ul>
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

onMounted(async () => {
  try {
    profil.value = await KelurahanService.getProfil();
  } finally {
    loading.value = false;
  }
});
</script>

<style scoped>
.rich-content :deep(p) {
  margin-bottom: 0.5rem;
}
.rich-content :deep(p:last-child) {
  margin-bottom: 0;
}
.rich-content :deep(ul) {
  list-style-type: disc;
  padding-left: 1.25rem;
  margin: 0.5rem 0;
}
.rich-content :deep(ol) {
  list-style-type: decimal;
  padding-left: 1.25rem;
  margin: 0.5rem 0;
}
.rich-content :deep(li) {
  margin-bottom: 0.2rem;
}
.rich-content :deep(a) {
  color: #047857;
  text-decoration: underline;
}
</style>
