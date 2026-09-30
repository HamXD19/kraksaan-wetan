<template>
  <section class="relative bg-emerald-950 text-white overflow-hidden border-b border-emerald-800 reveal-fade" :class="paddingClass">
    <!-- Dynamic Hero Background Image (Takes custom hero_image input with fallback to Bromo) -->
    <div class="absolute inset-0 z-0 overflow-hidden">
      <img 
        :src="heroImageUrl" 
        @error="$event.target.src = '/images/hero-bromo-vector.jpg'"
        alt="Latar Hero Kelurahan Kraksaan Wetan" 
        class="w-full h-full object-cover object-[center_35%] pointer-events-none"
        fetchpriority="high"
        loading="eager"
      />

      <!-- Government Gradient Masks for Clear Text Readability -->
      <div class="absolute inset-0 z-1 bg-gradient-to-r from-emerald-950/95 via-emerald-950/90 to-emerald-950/70"></div>
      <div class="absolute inset-0 z-1 bg-gradient-to-t from-emerald-950 via-transparent to-emerald-950/40"></div>
      <div class="absolute inset-0 z-1 bg-radial at-top opacity-20 mix-blend-overlay"></div>
    </div>

    <!-- Permanent Decorative Landscape Silhouette Contour at Bottom -->
    <div class="absolute bottom-0 inset-x-0 z-2 pointer-events-none opacity-40">
      <svg class="w-full h-10 sm:h-12 text-emerald-950 fill-current preserve-3d" viewBox="0 0 1440 120" preserveAspectRatio="none">
        <path d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,32C840,32,960,64,1080,69.3C1200,75,1320,53,1380,42.7L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
      </svg>
    </div>

    <!-- Hero Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
      <slot name="badge">
        <div v-if="badgeText" class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 border border-emerald-700/80 text-amber-300 text-xs font-bold uppercase tracking-wider mb-2 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          {{ badgeText }}
        </div>
      </slot>

      <slot name="title">
        <h1 v-if="title" class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight text-white leading-tight">
          {{ title }}
        </h1>
      </slot>

      <slot name="description">
        <p v-if="description" class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-3xl leading-relaxed">
          {{ description }}
        </p>
      </slot>

      <!-- Default Slot for Extra Actions, Metrics, or Buttons -->
      <slot />
    </div>
  </section>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { KelurahanService } from '../services/api';

const props = defineProps({
  badgeText: {
    type: String,
    default: ''
  },
  title: {
    type: String,
    default: ''
  },
  description: {
    type: String,
    default: ''
  },
  profil: {
    type: Object,
    default: null
  },
  paddingClass: {
    type: String,
    default: 'py-12 sm:py-16'
  }
});

const localProfil = ref({});

onMounted(async () => {
  if (!props.profil || !props.profil.hero_image) {
    try {
      localProfil.value = await KelurahanService.getProfil();
    } catch (e) {
      // fallback
    }
  }
});

const heroImageUrl = computed(() => {
  return props.profil?.hero_image || localProfil.value?.hero_image || '/images/hero-bromo-vector.jpg';
});
</script>
