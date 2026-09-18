<template>
  <div class="pb-16 bg-slate-50/50 min-h-screen">
    <Breadcrumb :items="[
      { label: 'Informasi Publik', to: '/informasi-publik' },
      { label: 'Agenda Kegiatan', to: '/agenda' },
      { label: agenda.judul || 'Detail Agenda' }
    ]" />

    <!-- Hero Header Portal Agenda -->
    <HeroPageHeader 
      badge-text="JADWAL & AGENDA RESMI"
      title="Rincian Agenda Kegiatan"
      description="Informasi lengkap jadwal acara, lokasi pertemuan, susunan agenda, dan petunjuk kehadiran kegiatan Kelurahan Kraksaan Wetan."
      padding-class="py-8 sm:py-10"
    />

    <!-- Main Container Layout 2 Kolom Serupa Berita -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
      <LoadingSpinner v-if="loading" />

      <div v-else-if="agenda.id" class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        <!-- Kolom Kiri (Sidebar): Agenda Lainnya & Kontak (lg:col-span-4) -->
        <aside class="lg:col-span-4 space-y-6 lg:sticky lg:top-24 order-2 lg:order-1 reveal-left delay-75">
          <!-- Widget Agenda Mendatang Lainnya -->
          <div class="bg-white rounded-3xl p-6 border border-slate-200/90 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
              <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-600 animate-pulse"></span>
                <span>Agenda Terdekat</span>
              </h3>
              <router-link to="/agenda" class="text-[11px] text-emerald-700 hover:text-emerald-900 font-bold transition">
                Semua Agenda &rarr;
              </router-link>
            </div>

            <!-- List Agenda Terdekat -->
            <div class="divide-y divide-slate-100 space-y-3">
              <article 
                v-for="item in agendaLainnya" 
                :key="item.id"
                class="pt-3 first:pt-0 group"
              >
                <router-link :to="`/agenda/${item.slug}`" class="flex items-start gap-3">
                  <div class="w-16 h-16 sm:w-18 sm:h-18 rounded-2xl overflow-hidden bg-slate-100 shrink-0 border border-slate-200/70">
                    <img 
                      v-if="item.foto" 
                      :src="item.foto" 
                      :alt="item.judul"
                      class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                      loading="lazy"
                    />
                    <div v-else class="w-full h-full bg-emerald-900/90 flex items-center justify-center text-emerald-200 text-xs font-bold">
                      <svg class="w-6 h-6 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-1.5 text-[10px] text-slate-400 mb-1 font-semibold">
                      <span class="text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">
                        {{ item.kategori || 'Umum' }}
                      </span>
                    </div>
                    <h4 class="text-xs font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-emerald-700 transition">
                      {{ item.judul }}
                    </h4>
                    <p class="text-[11px] text-emerald-700 font-semibold mt-1">
                      {{ item.formatted_jadwal }}
                    </p>
                  </div>
                </router-link>
              </article>

              <div v-if="!agendaLainnya.length" class="text-xs text-slate-400 italic py-2">
                Tidak ada agenda kegiatan lain saat ini.
              </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
              <router-link 
                to="/agenda"
                class="w-full py-2.5 px-4 rounded-xl bg-slate-50 hover:bg-emerald-50 text-slate-700 hover:text-emerald-800 font-bold text-xs transition flex items-center justify-center gap-2 border border-slate-200"
              >
                <span>Lihat Seluruh Kalender Agenda</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </router-link>
            </div>
          </div>

          <!-- Banner Bantuan & Konfirmasi Kehadiran -->
          <div class="bg-gradient-to-br from-emerald-950 to-emerald-900 text-white p-6 rounded-3xl shadow-xs space-y-3">
            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Pertanyaan & Konfirmasi</span>
            <h4 class="text-sm font-bold leading-snug">Perlu Informasi Tambahan Terkait Acara?</h4>
            <p class="text-xs text-emerald-200 leading-relaxed">
              Silakan hubungi staf pelayanan kelurahan untuk informasi teknis atau konfirmasi kehadiran.
            </p>
            <router-link 
              to="/kontak"
              class="inline-block py-2 px-4 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs transition shadow-sm"
            >
              Hubungi Kelurahan
            </router-link>
          </div>
        </aside>

        <!-- Kolom Kanan: Konten Utama Rincian Agenda (lg:col-span-8) -->
        <main class="lg:col-span-8 bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm order-1 lg:order-2 reveal">
          <!-- Header Informasi Agenda -->
          <div class="p-6 sm:p-10 space-y-4">
            <div class="flex flex-wrap items-center gap-2.5">
              <!-- Kategori -->
              <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                {{ agenda.kategori || 'Umum' }}
              </span>

              <!-- Status Realtime -->
              <span 
                v-if="agenda.status_agenda === 'berlangsung'" 
                class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-500 text-white flex items-center gap-1.5 shadow-xs"
              >
                <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
                Sedang Berlangsung
              </span>
              <span 
                v-else-if="agenda.status_agenda === 'akan_datang'" 
                class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-600 text-white flex items-center gap-1.5 shadow-xs"
              >
                <svg class="w-3 h-3 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Akan Datang
              </span>
              <span 
                v-else 
                class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-700 text-slate-200"
              >
                Telah Selesai
              </span>

              <span class="text-xs text-slate-300">&bull;</span>
              <span class="text-xs text-slate-500 font-medium">
                Penyelenggara: <strong class="text-slate-700">{{ agenda.penyelenggara || 'Pemerintah Kelurahan' }}</strong>
              </span>
            </div>

            <!-- Judul Agenda -->
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 tracking-tight leading-tight">
              {{ agenda.judul }}
            </h1>
          </div>

          <!-- Highlight Box Jadwal & Lokasi -->
          <div class="mx-6 sm:mx-10 mb-6 p-5 sm:p-6 rounded-2xl bg-emerald-50/70 border border-emerald-200/80 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Jadwal Pelaksanaan
              </span>
              <p class="text-sm sm:text-base font-extrabold text-slate-900 leading-snug">
                {{ agenda.formatted_jadwal }}
              </p>
            </div>

            <div class="space-y-1.5">
              <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Tempat / Lokasi Acara
              </span>
              <p class="text-sm sm:text-base font-extrabold text-slate-900 leading-snug">
                {{ agenda.lokasi || 'Wilayah Kelurahan Kraksaan Wetan' }}
              </p>
            </div>
          </div>

          <!-- Foto Poster Kegiatan (Jika ada) -->
          <div v-if="agenda.foto" class="w-full max-h-[480px] bg-slate-900 overflow-hidden flex items-center justify-center">
            <img 
              :src="agenda.foto" 
              :alt="agenda.judul"
              class="w-full h-full max-h-[480px] object-contain cursor-zoom-in hover:opacity-95 transition"
              @click="zoomPhoto = agenda.foto"
              title="Klik untuk memperbesar gambar"
            />
          </div>

          <!-- Isi Deskripsi Agenda Lengkap -->
          <div class="p-6 sm:p-10 space-y-6 text-slate-700 text-sm sm:text-base leading-relaxed">
            <div class="space-y-2">
              <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900">
                Deskripsi & Susunan Kegiatan
              </h3>
              <div 
                class="rich-content text-slate-700 text-sm sm:text-base leading-relaxed"
                v-html="agenda.deskripsi"
              ></div>
            </div>

            <!-- Share & Navigasi Bawah -->
            <div class="pt-8 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-4">
              <router-link 
                to="/agenda"
                class="inline-flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-900 transition"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Daftar Agenda
              </router-link>

              <div class="flex items-center gap-2 text-xs">
                <span class="text-slate-500 font-medium">Bagikan:</span>
                <button 
                  type="button"
                  @click="shareAgenda" 
                  class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold transition flex items-center gap-1.5 cursor-pointer"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                  <span>Salin Tautan</span>
                </button>
              </div>
            </div>
          </div>
        </main>
      </div>

      <!-- State Jika Tidak Ditemukan -->
      <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200 p-8 space-y-4 max-w-xl mx-auto">
        <div class="w-16 h-16 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Agenda Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 leading-relaxed">
          Agenda kegiatan yang Anda tuju mungkin sudah berakhir, dipindahkan, atau telah dinonaktifkan oleh administrator.
        </p>
        <router-link 
          to="/agenda" 
          class="inline-block px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition"
        >
          Kembali ke Semua Agenda
        </router-link>
      </div>
    </div>

    <!-- Modal Zoom Foto -->
    <div 
      v-if="zoomPhoto" 
      @click="zoomPhoto = null" 
      class="fixed inset-0 z-60 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-sm cursor-zoom-out"
    >
      <img :src="zoomPhoto" alt="Pratinjau Foto" class="max-w-full max-h-[92vh] rounded-2xl shadow-2xl object-contain" />
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Breadcrumb from '../components/Breadcrumb.vue';
import HeroPageHeader from '../components/HeroPageHeader.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';
import { useToast } from '../composables/useToast';

const toast = useToast();
const route = useRoute();

const loading = ref(true);
const agenda = ref({});
const agendaLainnya = ref([]);
const zoomPhoto = ref(null);

const loadAgendaDetail = async (slug) => {
  loading.value = true;
  try {
    const [detailData, allAgendaRes] = await Promise.all([
      KelurahanService.getAgendaBySlug(slug),
      KelurahanService.getAgenda()
    ]);

    agenda.value = detailData || {};
    if (agenda.value?.judul) {
      document.title = `${agenda.value.judul} - Agenda Kelurahan Kraksaan Wetan`;
    }

    const others = (allAgendaRes?.data || []).filter(item => item.slug !== slug);
    agendaLainnya.value = others.slice(0, 5);
  } catch (err) {
    console.error('Gagal memuat detail agenda:', err);
    agenda.value = {};
  } finally {
    loading.value = false;
  }
};

watch(() => route.params.slug, async (newSlug) => {
  if (newSlug) {
    await loadAgendaDetail(newSlug);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }
});

onMounted(() => {
  const slug = route.params.slug;
  if (slug) {
    loadAgendaDetail(slug);
  }
});

const shareAgenda = () => {
  if (navigator.clipboard) {
    navigator.clipboard.writeText(window.location.href);
    toast.success('Tautan agenda kegiatan berhasil disalin ke clipboard!', 'Tautan Disalin');
  }
};
</script>

<style scoped>
.rich-content :deep(p) {
  margin-bottom: 0.75rem;
}
.rich-content :deep(ul) {
  list-style-type: disc;
  padding-left: 1.25rem;
  margin-bottom: 0.75rem;
}
.rich-content :deep(ol) {
  list-style-type: decimal;
  padding-left: 1.25rem;
  margin-bottom: 0.75rem;
}
.rich-content :deep(h1),
.rich-content :deep(h2),
.rich-content :deep(h3),
.rich-content :deep(h4) {
  font-weight: 700;
  color: #0f172a;
  margin-top: 1.25rem;
  margin-bottom: 0.5rem;
}
</style>
