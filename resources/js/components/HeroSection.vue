<template>
  <section class="relative bg-emerald-950 text-white overflow-hidden py-10 sm:py-12 lg:py-16 min-h-[380px] lg:min-h-[440px] flex items-center">
    <!-- Background: Pemandangan Gunung Bromo Vektor Pilihan User -->
    <div class="absolute inset-0 z-0">
      <img 
        :src="heroImage" 
        alt="Gunung Bromo - Kabupaten Probolinggo" 
        class="w-full h-full object-cover object-[center_35%]"
        fetchpriority="high"
        loading="eager"
      />

      <!-- Government Gradient Masks: Left Dark For Text Legibility, Right Open For Landscape -->
      <div class="absolute inset-0 z-2 bg-gradient-to-r from-emerald-950/95 via-emerald-950/85 md:via-emerald-950/70 to-emerald-950/30"></div>
      <div class="absolute inset-0 z-2 bg-gradient-to-t from-emerald-950 via-transparent to-emerald-950/40"></div>
      <div class="absolute inset-0 z-2 bg-radial at-top opacity-20 mix-blend-overlay"></div>
    </div>

    <!-- Permanent Decorative Landscape Silhouette Contour at Bottom -->
    <div class="absolute bottom-0 inset-x-0 z-3 pointer-events-none opacity-40">
      <svg class="w-full h-12 sm:h-16 text-emerald-950 fill-current preserve-3d" viewBox="0 0 1440 120" preserveAspectRatio="none">
        <path d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,32C840,32,960,64,1080,69.3C1200,75,1320,53,1380,42.7L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
      </svg>
    </div>

    <!-- Main Content Container: Clean, Grand, Proportional & Unobstructed -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
      <div class="max-w-3xl space-y-5">
        <!-- Official Government Badge -->
        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-800/90 border border-emerald-500/40 text-emerald-200 text-xs font-semibold shadow-inner backdrop-blur-md">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          <span>WEBSITE RESMI PEMERINTAH KELURAHAN</span>
        </div>

        <!-- Headline & Subheadline -->
        <div class="space-y-3">
          <p class="text-xs sm:text-sm md:text-base font-bold text-emerald-300 tracking-wide flex items-center gap-2">
            <span>Selamat Datang di Portal Resmi</span>
          </p>
          <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[1.14] drop-shadow-md">
            Kelurahan <br class="hidden sm:inline" />
            <span class="text-amber-400">Kraksaan Wetan</span>
          </h1>

          <p class="text-sm sm:text-base md:text-lg text-emerald-100/95 leading-relaxed font-normal max-w-2xl drop-shadow-xs">
            Media informasi keterbukaan publik, pelayanan administrasi kependudukan cepat, dan panduan kemasyarakatan Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo.
          </p>
        </div>

        <!-- Action Buttons (CTA) -->
        <div class="flex flex-wrap items-center gap-3 pt-1">
          <a 
            href="#layanan-masyarakat" 
            class="inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs sm:text-sm shadow-lg hover:shadow-amber-500/30 transition-all transform hover:-translate-y-0.5 cursor-pointer"
          >
            <svg class="w-4 h-4 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <span>Layanan Masyarakat</span>
          </a>

          <a 
            href="#profil-kelurahan" 
            class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-emerald-900/80 hover:bg-emerald-800/90 text-white font-bold text-xs sm:text-sm border border-emerald-500/40 backdrop-blur-md transition-all transform hover:-translate-y-0.5 cursor-pointer"
          >
            <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Jelajahi Informasi</span>
          </a>
        </div>

        <!-- Feature Highlights Bar -->
        <div class="pt-4 border-t border-emerald-800/60 grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs text-emerald-200/90">
          <div class="flex items-center gap-2">
            <span class="p-1 rounded-md bg-emerald-800/80 text-amber-400 shrink-0">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </span>
            <span>Webiste Resmi</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="p-1 rounded-md bg-emerald-800/80 text-amber-400 shrink-0">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </span>
            <span>Pelayanan Cepat & Akurat</span>
          </div>
          <div class="flex items-center gap-2 col-span-2 sm:col-span-1">
            <span class="p-1 rounded-md bg-emerald-800/80 text-amber-400 shrink-0">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </span>
            <span>Bebas Pungli (Rp 0)</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  profil: {
    type: Object,
    default: () => ({})
  }
});

const heroImage = computed(() => {
  return props.profil?.hero_image || '/images/hero-bromo-vector.jpg';
});
</script>
