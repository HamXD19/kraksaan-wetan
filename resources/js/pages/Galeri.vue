<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Galeri Kegiatan' }]" />

    <section class="bg-emerald-900 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-300">Dokumentasi Visual</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Galeri Kegiatan Kelurahan</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl">
          Dokumentasi foto dan rekam jejak program kerja pemerintah, pelayanan masyarakat, dan kegiatan sosial warga Kraksaan Wetan.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10">
      <LoadingSpinner v-if="loading" />
      <div v-else>
        <GalleryGrid :items="galeriList" :show-filter="true" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import GalleryGrid from '../components/GalleryGrid.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const galeriList = ref([]);

onMounted(async () => {
  try {
    galeriList.value = await KelurahanService.getGaleri();
  } finally {
    loading.value = false;
  }
});
</script>
