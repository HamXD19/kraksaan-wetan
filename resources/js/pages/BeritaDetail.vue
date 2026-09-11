<template>
  <div class="pb-16 bg-slate-50/50 min-h-screen">
    <Breadcrumb :items="[{ label: 'Berita', to: '/berita' }, { label: berita.judul || 'Detail Berita' }]" />

    <!-- Hero Header Portal Berita Tetap Muncul -->
    <section class="relative bg-emerald-950 text-white overflow-hidden py-8 sm:py-10 border-b border-emerald-800">
      <!-- Subtle Scenic Background Vector -->
      <div class="absolute inset-0 z-0 opacity-20">
        <img 
          src="/images/hero-bromo-vector.jpg" 
          alt="Gunung Bromo - Kelurahan Kraksaan Wetan" 
          class="w-full h-full object-cover object-[center_35%]"
        />
      </div>

      <!-- Gradient Overlay -->
      <div class="absolute inset-0 z-1 bg-gradient-to-r from-emerald-950 via-emerald-950/90 to-transparent"></div>

      <!-- Hero Header Content -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/90 border border-emerald-500/40 text-emerald-200 text-xs font-semibold mb-2 backdrop-blur-xs">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          <span>KABAR & INFORMASI RESMI</span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">Portal Berita & Publikasi Kelurahan</h2>
        <p class="text-xs sm:text-sm text-emerald-200 mt-1 max-w-2xl leading-relaxed">
          Liputan resmi kegiatan kemasyarakatan, program pembangunan wilayah, dan transparansi pelayanan publik Kelurahan Kraksaan Wetan.
        </p>
      </div>
    </section>

    <!-- Main Container Layout 2 Kolom Profesional -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
      <LoadingSpinner v-if="loading" />

      <div v-else-if="berita.id" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Kolom Kiri (Sidebar): Berita Terkini (lg:col-span-4) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24 order-2 lg:order-1">
          <!-- Widget Berita Terkini -->
          <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-600 animate-pulse"></span>
                <span>Berita Terkini</span>
              </h3>
              <router-link to="/berita" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold transition">
                Indeks &rarr;
              </router-link>
            </div>

            <!-- List Artikel Terkini -->
            <div class="divide-y divide-slate-100 space-y-3">
              <article 
                v-for="item in beritaTerkini" 
                :key="item.id"
                class="pt-3 first:pt-0 group"
              >
                <router-link :to="`/berita/${item.slug}`" class="flex items-start gap-3">
                  <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200/70">
                    <img 
                      :src="item.gambar" 
                      :alt="item.judul"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
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
                    <h4 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-emerald-700 transition">
                      {{ item.judul }}
                    </h4>
                  </div>
                </router-link>
              </article>

              <div v-if="!beritaTerkini.length" class="text-xs text-slate-400 italic py-2">
                Belum ada artikel berita terkini lainnya.
              </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
              <router-link 
                to="/berita"
                class="w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 font-bold text-xs transition flex items-center justify-center gap-2 border border-slate-200"
              >
                <span>Jelajahi Semua Arsip Berita</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </router-link>
            </div>
          </div>

          <!-- Banner Layanan Pengaduan -->
          <div class="bg-gradient-to-br from-emerald-950 to-emerald-900 text-white p-6 rounded-3xl shadow-xs space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Saluran Cepat Tanggap</span>
            <h4 class="text-sm font-bold leading-snug">Punya Laporan atau Keluhan Infrastruktur?</h4>
            <p class="text-xs text-emerald-200 leading-relaxed">
              Hubungi langsung WhatsApp resmi Halo SAE atau laporkan melalui SP4N-LAPOR!.
            </p>
            <router-link 
              to="/kontak"
              class="inline-block py-2 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition shadow-sm"
            >
              Buka Layanan Pengaduan
            </router-link>
          </div>
        </aside>

        <!-- Kolom Kanan: Konten Artikel Utama (lg:col-span-8) -->
        <main class="lg:col-span-8 bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm order-1 lg:order-2">
          <!-- Header Informasi Artikel -->
          <div class="p-6 sm:p-10 space-y-4">
            <div class="flex flex-wrap items-center gap-2.5">
              <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                {{ berita.kategori }}
              </span>
              <span class="text-xs text-slate-300">&bull;</span>
              <span class="text-xs text-slate-500 flex items-center gap-1 font-medium">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ berita.tanggal }}
              </span>
              <span class="text-xs text-slate-300">&bull;</span>
              <span class="text-xs text-slate-500 font-medium">
                Oleh: <strong class="text-slate-700">{{ berita.penulis || 'Humas Kelurahan' }}</strong>
              </span>
              <span v-if="berita.dilihat !== undefined" class="text-xs text-slate-400 font-medium flex items-center gap-1 ml-auto">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                {{ berita.dilihat }} kali dibaca
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
              {{ berita.judul }}
            </h1>
          </div>

          <!-- Foto Berita Utama (Featured Image) -->
          <div class="w-full h-72 sm:h-96 md:h-[420px] bg-slate-100 overflow-hidden">
            <img 
              :src="berita.gambar" 
              :alt="berita.judul"
              class="w-full h-full object-cover object-center"
            />
          </div>

          <!-- Isi Tubuh Berita -->
          <div class="p-6 sm:p-10 space-y-6 text-slate-700 text-sm sm:text-base leading-relaxed">
            <!-- Ringkasan / Lead Paragraph -->
            <p class="font-medium text-slate-800 italic border-l-4 border-emerald-600 pl-4 bg-emerald-50/50 p-4 rounded-r-2xl">
              {{ berita.ringkasan }}
            </p>

            <!-- Teks Berita Lengkap -->
            <div class="space-y-4 whitespace-pre-line text-slate-700 leading-relaxed">
              {{ berita.konten }}
            </div>

            <!-- Share & Navigasi Bawah -->
            <div class="pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
              <router-link 
                to="/berita"
                class="inline-flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Indeks Berita
              </router-link>

              <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-500 font-medium">Bagikan:</span>
                <button 
                  @click="shareArticle" 
                  class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold transition flex items-center gap-1.5"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                  Salin Tautan
                </button>
              </div>
            </div>
          </div>
        </main>
      </div>

      <!-- State Jika Berita Tidak Ditemukan -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8">
        <h2 class="text-xl font-bold text-slate-800 mb-2">Berita Tidak Ditemukan</h2>
        <p class="text-xs sm:text-sm text-slate-500 mb-6">Artikel yang Anda cari mungkin telah dipindahkan atau dihapus.</p>
        <router-link to="/berita" class="px-5 py-2.5 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition">
          Kembali ke Daftar Berita
        </router-link>
      </div>
    </div>
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
const berita = ref({});
const beritaTerkini = ref([]);

const loadArticle = async (slug) => {
  loading.value = true;
  try {
    const [articleData, allArticles] = await Promise.all([
      KelurahanService.getBeritaBySlug(slug),
      KelurahanService.getBerita()
    ]);

    berita.value = articleData || {};
    if (berita.value?.judul) {
      document.title = `${berita.value.judul} - Kelurahan Kraksaan Wetan`;
    }

    // Filter Berita Terkini (ambil hingga 5 artikel terbaru selain artikel yang sedang dibaca)
    beritaTerkini.value = (allArticles || [])
      .filter(item => item.slug !== slug)
      .slice(0, 5);
  } catch (err) {
    console.error('Gagal memuat berita:', err);
  } finally {
    loading.value = false;
  }
};

watch(() => route.params.slug, async (newSlug) => {
  if (newSlug) {
    await loadArticle(newSlug);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
});

onMounted(() => {
  const slug = route.params.slug;
  if (slug) {
    loadArticle(slug);
  }
});

const shareArticle = () => {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(window.location.href);
    alert('Tautan berita berhasil disalin ke clipboard!');
  }
};
</script>
