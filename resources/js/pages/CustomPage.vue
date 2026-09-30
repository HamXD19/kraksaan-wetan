<template>
  <div class="pb-16 bg-slate-50/50 min-h-screen">
    <!-- Breadcrumb -->
    <Breadcrumb :items="[
      { label: parentMenuLabel, to: parentMenuRoute },
      { label: halaman.judul || 'Halaman Informasi' }
    ]" />

    <!-- Hero Header Halaman -->
    <HeroPageHeader 
      :badge-text="categoryBadgeText"
      :title="halaman.judul || 'Memuat Halaman...'"
      :description="halaman.ringkasan || 'Halaman informasi resmi Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo.'"
      padding-class="py-8 sm:py-12"
    />

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
      <LoadingSpinner v-if="loading" />

      <!-- State: Error / Not Found -->
      <div v-else-if="error || !halaman.id" class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs max-w-xl mx-auto space-y-4">
        <div class="w-16 h-16 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto text-2xl font-bold">
          !
        </div>
        <h2 class="text-xl font-bold text-slate-900">Halaman Tidak Ditemukan</h2>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
          Mohon maaf, halaman yang Anda tuju belum dipublikasikan atau tautan yang dimasukkan tidak tepat.
        </p>
        <div class="pt-2">
          <router-link to="/" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Kembali ke Beranda
          </router-link>
        </div>
      </div>

      <!-- State: Loaded Content -->
      <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Main Article (lg:col-span-8) -->
        <main class="lg:col-span-8 bg-white rounded-3xl p-6 sm:p-8 md:p-10 border border-slate-200/90 shadow-xs space-y-6">
          <!-- Meta Header -->
          <div class="space-y-3 pb-6 border-b border-slate-100">
            <div class="flex flex-wrap items-center gap-2 text-xs">
              <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 font-bold uppercase tracking-wider text-[11px]">
                {{ categoryBadgeText }}
              </span>
              <span class="text-slate-400">&bull;</span>
              <span class="text-slate-500 flex items-center gap-1 font-medium">
                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Diperbarui {{ formatDate(halaman.updated_at || halaman.created_at) }}
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 leading-tight">
              {{ halaman.judul }}
            </h1>

            <!-- Ringkasan jika ada -->
            <p v-if="halaman.ringkasan" class="text-sm sm:text-base text-slate-600 font-medium leading-relaxed italic bg-slate-50 p-4 rounded-2xl border-l-4 border-emerald-600">
              "{{ halaman.ringkasan }}"
            </p>
          </div>

          <!-- Featured Image / Gambar Utama -->
          <div v-if="halaman.gambar" class="rounded-2xl overflow-hidden border border-slate-200/80 shadow-xs bg-slate-100">
            <img 
              :src="halaman.gambar" 
              :alt="halaman.judul"
              class="w-full max-h-[460px] object-cover"
              loading="lazy"
            />
          </div>

          <!-- Rich Content / Paragraf -->
          <div 
            class="article-content prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4"
            v-html="formattedKonten"
          ></div>

          <!-- Bagian Aksi / Berbagi -->
          <div class="pt-6 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
            <div class="text-xs text-slate-400">
              Informasi Publik Kelurahan Kraksaan Wetan
            </div>
            <div class="flex items-center gap-2">
              <button 
                type="button" 
                @click="salinTautan" 
                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold transition"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                {{ tersalin ? 'Tautan Tersalin!' : 'Salin Tautan' }}
              </button>
            </div>
          </div>
        </main>

        <!-- Sidebar (lg:col-span-4) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24">
          <!-- Widget Menu Terkait -->
          <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs space-y-4">
            <h3 class="text-sm font-bold text-slate-900 pb-3 border-b border-slate-100 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Navigasi Terkait
            </h3>
            <div class="space-y-1.5 text-xs">
              <router-link to="/profil" class="flex items-center justify-between p-2.5 rounded-xl text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 font-semibold transition">
                <span>Profil Kelurahan</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
              <router-link to="/pemerintahan" class="flex items-center justify-between p-2.5 rounded-xl text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 font-semibold transition">
                <span>Struktur Pemerintahan</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
              <router-link to="/pelayanan" class="flex items-center justify-between p-2.5 rounded-xl text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 font-semibold transition">
                <span>Panduan Pelayanan Warga</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
              <router-link to="/agenda" class="flex items-center justify-between p-2.5 rounded-xl text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 font-semibold transition">
                <span>Agenda Kegiatan Kelurahan</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
              <router-link to="/kontak" class="flex items-center justify-between p-2.5 rounded-xl text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 font-semibold transition">
                <span>Kontak & Pengaduan</span>
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
            </div>
          </div>

          <!-- Bantuan & Kontak Kantor -->
          <div class="bg-gradient-to-br from-emerald-950 to-emerald-900 text-white p-6 rounded-3xl shadow-xs space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Pusat Layanan Warga</span>
            <h4 class="text-sm font-bold leading-snug">Ada Pertanyaan Seputar Informasi Ini?</h4>
            <p class="text-xs text-emerald-200 leading-relaxed">
              Hubungi staf kelurahan atau sampaikan aspirasi dan pengaduan Anda secara online melalui portal resmi.
            </p>
            <router-link 
              to="/kontak"
              class="inline-block py-2 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition shadow-sm"
            >
              Hubungi Kelurahan
            </router-link>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Breadcrumb from '../components/Breadcrumb.vue';
import HeroPageHeader from '../components/HeroPageHeader.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const route = useRoute();
const loading = ref(true);
const error = ref(null);
const halaman = ref({});
const tersalin = ref(false);

const categoryBadgeText = computed(() => {
  const cat = halaman.value.kategori;
  if (cat === 'profil') return 'PROFIL KELURAHAN';
  if (cat === 'pemerintahan') return 'PEMERINTAHAN';
  if (cat === 'informasi') return 'INFORMASI PUBLIK';
  return 'INFORMASI KELURAHAN';
});

const parentMenuLabel = computed(() => {
  const cat = halaman.value.kategori;
  if (cat === 'profil') return 'Profil';
  if (cat === 'pemerintahan') return 'Pemerintahan';
  if (cat === 'informasi') return 'Informasi Publik';
  return 'Informasi';
});

const parentMenuRoute = computed(() => {
  const cat = halaman.value.kategori;
  if (cat === 'profil') return '/profil';
  if (cat === 'pemerintahan') return '/pemerintahan';
  if (cat === 'informasi') return '/informasi-publik';
  return '/';
});

const formattedKonten = computed(() => {
  if (!halaman.value.konten) return '<p class="italic text-slate-400">Konten informasi belum diisi.</p>';
  return halaman.value.konten;
});

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  try {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    }).format(d);
  } catch (e) {
    return dateStr;
  }
};

const salinTautan = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href);
    tersalin.value = true;
    setTimeout(() => {
      tersalin.value = false;
    }, 2500);
  } catch (err) {
    // fallback
  }
};

const loadData = async () => {
  const slug = route.params.slug;
  if (!slug) return;
  loading.value = true;
  error.value = null;

  try {
    const res = await KelurahanService.getHalamanBySlug(slug);
    halaman.value = res || {};
    if (halaman.value.judul) {
      document.title = `${halaman.value.judul} - Kelurahan Kraksaan Wetan`;
    }
  } catch (err) {
    console.error('Gagal memuat halaman kustom:', err);
    error.value = err.message || 'Gagal memuat halaman.';
  } finally {
    loading.value = false;
  }
};

watch(() => route.params.slug, () => {
  loadData();
});

onMounted(() => {
  loadData();
});
</script>

<style scoped>
.article-content :deep(p) {
  margin-bottom: 1rem;
}
.article-content :deep(h1),
.article-content :deep(h2),
.article-content :deep(h3),
.article-content :deep(h4) {
  font-weight: 700;
  color: #0f172a;
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
}
.article-content :deep(ul),
.article-content :deep(ol) {
  margin-left: 1.5rem;
  margin-bottom: 1rem;
}
.article-content :deep(ul) {
  list-style-type: disc;
}
.article-content :deep(ol) {
  list-style-type: decimal;
}
.article-content :deep(img) {
  border-radius: 1rem;
  margin: 1rem 0;
  max-width: 100%;
  height: auto;
}
</style>
