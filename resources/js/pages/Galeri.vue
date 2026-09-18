<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Galeri Kegiatan' }]" />

    <!-- Hero Banner Header -->
    <HeroPageHeader 
      badge-text="Dokumentasi Visual"
      title="Galeri Kegiatan Kelurahan"
      description="Dokumentasi foto dan video rekam jejak program kerja pemerintah, pelayanan masyarakat, dan kegiatan sosial warga Kraksaan Wetan."
    />

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
import HeroPageHeader from '../components/HeroPageHeader.vue';
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
