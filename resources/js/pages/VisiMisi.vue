<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Profil', to: '/profil' }, { label: 'Visi dan Misi' }]" />

    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Arah & Komitmen Kebijakan</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Visi & Misi Kelurahan Kraksaan Wetan</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl">
          Pedoman dan komitmen strategis dalam mewujudkan pelayanan prima dan masyarakat yang berdaya saing.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <LoadingSpinner v-if="loading" />
      <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 space-y-8">
          <!-- Card Visi -->
          <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative overflow-hidden reveal">
            <div class="absolute top-0 right-0 w-32 h-32 bg-amber-100 rounded-bl-full opacity-50"></div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider mb-4">
              <span class="w-2 h-2 rounded-full bg-amber-500"></span>
              Visi Resmi
            </div>
            <blockquote class="text-xl sm:text-2xl font-extrabold text-emerald-950 leading-relaxed italic">
              "{{ profil.visi }}"
            </blockquote>
          </div>

          <!-- Card Misi -->
          <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm reveal delay-100">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-6">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Misi Pembangunan
            </div>
            <div class="space-y-4">
              <div 
                v-for="(m, idx) in profil.misi" 
                :key="idx"
                class="flex items-start gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-emerald-200 transition reveal"
                :class="`delay-${(idx + 1) * 75}`"
              >
                <span class="w-8 h-8 rounded-lg bg-emerald-700 text-white font-bold flex items-center justify-center flex-shrink-0 text-sm">
                  0{{ idx + 1 }}
                </span>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed pt-1">
                  {{ m }}
                </p>
              </div>
            </div>
          </div>

          <!-- Nilai-Nilai Budaya Kerja -->
          <div class="bg-gradient-to-r from-emerald-950 to-emerald-900 text-white p-8 rounded-3xl shadow-sm reveal delay-150">
            <h3 class="text-lg font-bold text-amber-400 mb-4">Tata Nilai Pelayanan "BERAKHLAK & GUYUB"</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
              <div class="p-3.5 rounded-xl bg-emerald-900/60 border border-emerald-700/60">
                <strong class="text-emerald-200 block mb-1">Berorientasi Pelayanan</strong>
                Memahami dan memenuhi kebutuhan masyarakat secara ramah, cekatan, dan solutif.
              </div>
              <div class="p-3.5 rounded-xl bg-emerald-900/60 border border-emerald-700/60">
                <strong class="text-emerald-200 block mb-1">Akuntabel & Transparan</strong>
                Melaksanakan tugas dengan jujur, bertanggung jawab, cermat, disiplin, dan bebas pungli.
              </div>
              <div class="p-3.5 rounded-xl bg-emerald-900/60 border border-emerald-700/60">
                <strong class="text-emerald-200 block mb-1">Harmonis & Gotong Royong</strong>
                Saling peduli, menghargai keberagaman warga, dan menjaga kerukunan antarkomunitas.
              </div>
              <div class="p-3.5 rounded-xl bg-emerald-900/60 border border-emerald-700/60">
                <strong class="text-emerald-200 block mb-1">Adaptif & Kolaboratif</strong>
                Terus berinovasi dan memanfaatkan teknologi digital untuk percepatan layanan publik.
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-4 space-y-6 reveal delay-100">
          <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
            <h3 class="font-bold text-slate-900 text-sm mb-4 pb-2 border-b border-slate-100">Menu Profil</h3>
            <ul class="space-y-2 text-xs">
              <li>
                <router-link to="/profil" class="block p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 font-medium">
                  &bull; Tentang Kelurahan
                </router-link>
              </li>
              <li>
                <router-link to="/profil/sejarah" class="block p-2.5 rounded-lg hover:bg-slate-50 text-slate-600 font-medium">
                  &bull; Sejarah Kelurahan
                </router-link>
              </li>
              <li>
                <router-link to="/profil/visi-misi" class="block p-2.5 rounded-lg bg-emerald-50 text-emerald-800 font-bold">
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
