<template>
  <div class="pb-16 bg-slate-50/50 min-h-screen">
    <Breadcrumb :items="[{ label: 'Galeri Kegiatan', to: '/galeri' }, { label: galeri.judul || 'Detail Dokumentasi' }]" />

    <!-- Hero Header Portal Dokumentasi -->
    <section class="relative bg-emerald-950 text-white overflow-hidden py-8 sm:py-10 border-b border-emerald-800">
      <!-- Background subtle overlay -->
      <div class="absolute inset-0 z-0 opacity-20">
        <img 
          src="/images/hero-bromo-vector.jpg" 
          alt="Gunung Bromo - Kelurahan Kraksaan Wetan" 
          class="w-full h-full object-cover object-[center_35%]"
        />
      </div>
      <div class="absolute inset-0 z-1 bg-gradient-to-r from-emerald-950 via-emerald-950/90 to-transparent"></div>

      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/90 border border-emerald-500/40 text-emerald-200 text-xs font-semibold mb-2 backdrop-blur-xs">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          <span>DOKUMENTASI VISUAL RESMI</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Portal Galeri & Publikasi Visual</h2>
        <p class="text-xs sm:text-sm text-emerald-200 mt-1 max-w-2xl leading-relaxed">
          Rekam jejak visual program kerja pemerintah, pelayanan masyarakat, dan kegiatan sosial warga Kelurahan Kraksaan Wetan.
        </p>
      </div>
    </section>

    <!-- Main Content Layout (Mirip Halaman Berita) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
      <LoadingSpinner v-if="loading" />

      <div v-else-if="galeri.id" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Kolom Kiri (Sidebar): Foto Terkini Lainnya (lg:col-span-4) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24 order-2 lg:order-1">
          <!-- Widget Foto Dokumentasi Lainnya -->
          <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span>Foto Lainnya</span>
              </h3>
              <router-link to="/galeri" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold transition">
                Indeks Galeri &rarr;
              </router-link>
            </div>

            <!-- List Foto Terkait Lainnya -->
            <div class="divide-y divide-slate-100 space-y-3">
              <article 
                v-for="item in galeriLainnya" 
                :key="item.id"
                class="pt-3 first:pt-0 group"
              >
                <router-link :to="`/galeri/${item.id}`" class="flex items-start gap-3 transition-transform duration-200 group-hover:translate-x-1">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200/70 relative">
                    <img 
                      :src="item.gambar" 
                      :alt="item.judul"
                      class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                      loading="lazy"
                    />
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 text-[10px] text-slate-400 mb-1 font-semibold">
                      <span class="text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">
                        {{ item.kategori }}
                      </span>
                      <span>&bull;</span>
                      <span>{{ item.tanggal }}</span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-emerald-700 transition-colors">
                      {{ item.judul }}
                    </h4>
                  </div>
                </router-link>
              </article>

              <div v-if="!galeriLainnya.length" class="text-xs text-slate-400 italic py-2">
                Belum ada foto dokumentasi lainnya.
              </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
              <router-link 
                to="/galeri"
                class="w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 font-bold text-xs transition flex items-center justify-center gap-2 border border-slate-200"
              >
                <span>Lihat Semua Galeri Foto</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </router-link>
            </div>
          </div>

          <!-- Banner Informasi & Layanan -->
          <div class="bg-gradient-to-br from-emerald-950 to-emerald-900 text-white p-6 rounded-3xl shadow-xs space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Arsip Informasi Publik</span>
            <h4 class="text-sm font-bold leading-snug">Ingin Mengetahui Agenda & Berita Terbaru?</h4>
            <p class="text-xs text-emerald-200 leading-relaxed">
              Baca liputan resmi program kegiatan dan edaran kedinasan warga Kraksaan Wetan.
            </p>
            <router-link 
              to="/berita"
              class="inline-block py-2 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition shadow-sm"
            >
              Kunjungi Warta Berita &rarr;
            </router-link>
          </div>
        </aside>

        <!-- Kolom Kanan: Konten Foto Utama (lg:col-span-8) -->
        <main class="lg:col-span-8 bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm order-1 lg:order-2">
          <!-- Header Informasi Foto -->
          <div class="p-6 sm:p-10 space-y-4">
            <div class="flex flex-wrap items-center gap-2.5">
              <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                {{ galeri.kategori }}
              </span>
              <span class="text-xs text-slate-300">&bull;</span>
              <span class="text-xs text-slate-500 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ galeri.tanggal }}
              </span>
              <span class="text-xs text-slate-300">&bull;</span>
              <span class="text-xs text-slate-500 font-medium">
                Dokumentasi: <strong class="text-slate-700">Pemerintah Kelurahan Kraksaan Wetan</strong>
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
              {{ galeri.judul }}
            </h1>
          </div>

          <!-- Tampilan Foto Utama (Featured Photo) -->
          <div class="w-full bg-slate-950 relative group">
            <div class="max-h-[550px] w-full flex items-center justify-center overflow-hidden bg-slate-950">
              <img 
                :src="galeri.gambar" 
                :alt="galeri.judul"
                class="w-full max-h-[550px] object-contain object-center transition-transform duration-500 group-hover:scale-[1.02]"
              />
            </div>
            <!-- Caption Bar di Bawah Foto -->
            <div class="bg-slate-900/90 text-slate-300 text-xs px-6 py-3 flex items-center justify-between border-t border-slate-800">
              <span class="flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Foto Kegiatan Resmi Kelurahan Kraksaan Wetan</span>
              </span>
              <a 
                :href="galeri.gambar" 
                target="_blank" 
                rel="noopener"
                class="text-emerald-400 hover:text-emerald-300 font-semibold flex items-center gap-1 transition text-[11px]"
              >
                <span>Buka Resolusi Penuh</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            </div>
          </div>

          <!-- Uraian & Keterangan Foto (Seperti Halaman Berita) -->
          <div class="p-6 sm:p-10 space-y-6 text-slate-700 text-sm sm:text-base leading-relaxed">
            <!-- Highlight / Lead Callout -->
            <div class="border-l-4 border-emerald-600 pl-4 bg-emerald-50/50 p-4 rounded-r-2xl">
              <p class="font-semibold text-slate-900 text-sm sm:text-base">
                {{ galeri.judul }}
              </p>
              <p class="text-xs text-slate-500 mt-1">
                Kategori: <strong class="text-emerald-800">{{ galeri.kategori }}</strong> &bull; Tanggal Pelaksanaan: <strong class="text-slate-700">{{ galeri.tanggal }}</strong>
              </p>
            </div>

            <!-- Paragraf Keterangan Lengkap -->
            <div class="space-y-4 text-slate-700 leading-relaxed text-sm sm:text-base">
              <p v-if="galeri.deskripsi" class="whitespace-pre-line">
                {{ galeri.deskripsi }}
              </p>
              <p v-else class="text-slate-400 italic text-sm">
                Dokumentasi visual arsip resmi kegiatan dan pelayanan Pemerintah Kelurahan Kraksaan Wetan.
              </p>
            </div>

            <!-- Share & Tombol Kembali -->
            <div class="pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
              <router-link 
                to="/galeri"
                class="inline-flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Semua Galeri
              </router-link>

              <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-500 font-medium">Bagikan:</span>
                <button 
                  @click="sharePhoto" 
                  class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 active:scale-95 text-emerald-800 font-bold transition-all duration-200 flex items-center gap-1.5 cursor-pointer shadow-xs"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                  Salin Tautan
                </button>
              </div>
            </div>
          </div>

          <!-- Bagian "Setelah Dilihat Ada Beberapa Foto Untuk Dilihat Selengkapnya" -->
          <div class="bg-slate-50 p-6 sm:p-10 border-t border-slate-200">
            <div class="flex items-center justify-between mb-6">
              <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">DOKUMENTASI LAINNYA</span>
                <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-0.5">Lihat Foto Selengkapnya</h3>
              </div>
              <router-link 
                to="/galeri" 
                class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 transition"
              >
                <span>Lihat Semua</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
            </div>

            <!-- Grid Foto Terkait Selengkapnya -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
              <div 
                v-for="item in galeriLainnya.slice(0, 3)" 
                :key="item.id"
                class="bg-white rounded-2xl overflow-hidden border border-slate-200/90 shadow-xs hover:shadow-2xl hover:border-emerald-400/50 transition-all duration-300 transform hover:-translate-y-1.5 group flex flex-col justify-between"
              >
                <div class="relative h-44 overflow-hidden bg-slate-100">
                  <img 
                    :src="item.gambar" 
                    :alt="item.judul"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out"
                    loading="lazy"
                  />
                  <!-- Light Sweep Reflection Effect on Hover -->
                  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 ease-in-out pointer-events-none z-10"></div>

                  <div class="absolute top-2.5 left-2.5 z-20">
                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-emerald-800/90 text-white backdrop-blur-xs">
                      {{ item.kategori }}
                    </span>
                  </div>
                </div>

                <div class="p-4 flex flex-col flex-1 justify-between">
                  <div>
                    <span class="text-[10px] text-slate-400 font-medium">{{ item.tanggal }}</span>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-900 line-clamp-2 mt-1 leading-snug group-hover:text-emerald-700 transition-colors">
                      {{ item.judul }}
                    </h4>
                    <p class="text-[11px] text-slate-500 line-clamp-2 mt-1">
                      {{ item.deskripsi }}
                    </p>
                  </div>

                  <div class="pt-3 mt-3 border-t border-slate-100">
                    <router-link 
                      :to="`/galeri/${item.id}`"
                      class="text-xs font-bold text-emerald-700 hover:text-emerald-900 active:scale-95 inline-flex items-center gap-1 transition-all duration-200"
                    >
                      <span>Lihat Selengkapnya</span>
                      <svg class="w-3.5 h-3.5 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </router-link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
      </div>

      <!-- State Jika Foto Tidak Ditemukan -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8">
        <h2 class="text-xl font-bold text-slate-800 mb-2">Foto Dokumentasi Tidak Ditemukan</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">Foto kegiatan yang Anda cari mungkin telah dipindahkan atau dihapus.</p>
        <router-link to="/galeri" class="px-5 py-2.5 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition">
          Kembali ke Galeri Kegiatan
        </router-link>
      </div>
    </div>

    <!-- Toast Notification with Slide-Up Transition -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="transform translate-y-6 opacity-0 scale-95"
      enter-to-class="transform translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="transform translate-y-0 opacity-100 scale-100"
      leave-to-class="transform translate-y-6 opacity-0 scale-95"
    >
      <div 
        v-if="showToast" 
        class="fixed bottom-8 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-slate-900 text-white shadow-2xl border border-emerald-500/60 backdrop-blur-md"
      >
        <span class="w-7 h-7 rounded-full bg-emerald-600 flex items-center justify-center text-white shrink-0 shadow-xs">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </span>
        <span class="text-xs sm:text-sm font-semibold tracking-wide">Tautan foto berhasil disalin ke clipboard!</span>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const route = useRoute();
const loading = ref(true);
const galeri = ref({});
const galeriLainnya = ref([]);
const showToast = ref(false);

const loadData = async (id) => {
  loading.value = true;
  try {
    const [photoData, allPhotos] = await Promise.all([
      KelurahanService.getGaleriById(id),
      KelurahanService.getGaleri()
    ]);

    galeri.value = photoData || {};
    if (galeri.value?.judul) {
      document.title = `${galeri.value.judul} - Galeri Kelurahan Kraksaan Wetan`;
    }

    // Filter foto lainnya (kecualikan foto yang sedang dilihat)
    galeriLainnya.value = (allPhotos || [])
      .filter(item => String(item.id) !== String(id));
  } catch (err) {
    console.error('Gagal memuat galeri:', err);
  } finally {
    loading.value = false;
  }
};

watch(() => route.params.id, async (newId) => {
  if (newId) {
    await loadData(newId);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
});

onMounted(() => {
  const id = route.params.id;
  if (id) {
    loadData(id);
  }
});

const sharePhoto = async () => {
  if (navigator.clipboard) {
    try {
      await navigator.clipboard.writeText(window.location.href);
      showToast.value = true;
      setTimeout(() => {
        showToast.value = false;
      }, 3000);
    } catch {
      // Fallback
    }
  }
};
</script>
