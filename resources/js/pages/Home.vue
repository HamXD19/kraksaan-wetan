<template>
  <div class="space-y-16 sm:space-y-24 pb-16">
    <!-- 1. Hero Section -->
    <HeroSection :profil="profil" />

    <!-- 2. Quick Service Section -->
    <section id="layanan-masyarakat" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">
      <div class="text-center max-w-2xl mx-auto mb-12">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-3">
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
          Layanan Cepat Warga
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
          Pelayanan Masyarakat Terpadu
        </h2>
        <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
          Kemudahan pengurusan dokumen administrasi dan perizinan kependudukan warga Kelurahan Kraksaan Wetan secara transparan, cepat, dan tanpa biaya.
        </p>
      </div>

      <LoadingSpinner v-if="loading.layanan" text="Memuat daftar layanan..." />
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <ServiceCard 
          v-for="item in quickServices" 
          :key="item.id" 
          :layanan="item" 
        />
      </div>

      <div class="text-center mt-8">
        <router-link 
          to="/pelayanan" 
          class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-900 transition"
        >
          <span>Lihat Seluruh 6+ Panduan Layanan & Persyaratan</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </router-link>
      </div>
    </section>

    <!-- 3. Profil Singkat Kelurahan -->
    <section id="profil-kelurahan" class="bg-gradient-to-b from-white to-slate-100 py-16 border-y border-slate-200 scroll-mt-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
          <!-- Gambar & Sambutan Lurah -->
          <div class="lg:col-span-5 relative">
            <div class="relative mx-auto max-w-sm rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-slate-200">
              <img 
                :src="profil.lurah?.foto || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80'" 
                :alt="profil.lurah?.nama"
                class="w-full h-96 object-cover object-top"
              />
              <div class="absolute bottom-0 inset-x-0 p-5 bg-gradient-to-t from-emerald-950 via-emerald-900/90 to-transparent text-white">
                <p class="text-xs font-semibold text-amber-400 uppercase tracking-wider">{{ profil.lurah?.jabatan || 'Lurah Kraksaan Wetan' }}</p>
                <h4 class="text-base font-bold">{{ profil.lurah?.nama }}</h4>
                <p class="text-[11px] text-emerald-200">NIP. {{ profil.lurah?.nip }}</p>
              </div>
            </div>
          </div>

          <!-- Teks Deskripsi, Visi, Misi Ringkas -->
          <div class="lg:col-span-7 space-y-6">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Profil Kelurahan
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-snug">
              Mengenal Lebih Dekat <br class="hidden sm:inline">
              <span class="text-emerald-700">Kelurahan Kraksaan Wetan</span>
            </h2>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              {{ profil.deskripsi }}
            </p>

            <!-- Visi Highlight -->
            <div class="p-5 rounded-2xl bg-white border-l-4 border-amber-500 shadow-xs">
              <h4 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">Visi Kelurahan:</h4>
              <p class="text-xs sm:text-sm font-semibold text-slate-800 italic">
                "{{ profil.visi }}"
              </p>
            </div>

            <!-- Misi 2 Poin Awal -->
            <div class="space-y-2 text-xs sm:text-sm text-slate-700">
              <p class="font-bold text-slate-800">Fokus Program Utama:</p>
              <ul class="space-y-1.5 pl-4 list-disc marker:text-emerald-600">
                <li v-for="(m, idx) in (profil.misi || []).slice(0, 3)" :key="idx">
                  {{ m }}
                </li>
              </ul>
            </div>

            <div class="pt-2">
              <router-link 
                to="/profil" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs sm:text-sm font-bold shadow-md transition transform hover:-translate-y-0.5"
              >
                <span>Selengkapnya Tentang Kami</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. Statistik Kelurahan (Animated Counter) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
          Data Statistik
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
          Statistik Wilayah & Kependudukan
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Data agregat kependudukan terkini Kelurahan Kraksaan Wetan, Kab. Probolinggo
        </p>
      </div>

      <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        <StatisticCard 
          label="Jumlah Penduduk" 
          :value="statistik.penduduk || 6842" 
          unit="Jiwa" 
          subtext="L: 3.390 | P: 3.452"
        />
        <StatisticCard 
          label="Kepala Keluarga" 
          :value="statistik.kk || 2185" 
          unit="KK" 
          subtext="Tersebar di 7 RW"
        />
        <StatisticCard 
          label="Rukun Warga (RW)" 
          :value="statistik.rw || 7" 
          unit="RW" 
          subtext="Lingkungan Wilayah"
        />
        <StatisticCard 
          label="Rukun Tetangga (RT)" 
          :value="statistik.rt || 28" 
          unit="RT" 
          subtext="Pelayanan Lingkungan"
        />
        <StatisticCard 
          label="Luas Wilayah" 
          :value="statistik.luas_wilayah || '1.84'" 
          unit="km²" 
          subtext="Kepadatan 3.718/km²"
          class="col-span-2 sm:col-span-1"
        />
      </div>
    </section>

    <!-- 5. Berita Terbaru -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            Warta Kraksaan
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Berita & Kabar Terkini
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Informasi kegiatan pemerintahan, pemberdayaan masyarakat, dan agenda kelurahan.
          </p>
        </div>

        <router-link 
          to="/berita" 
          class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-900 transition self-start sm:self-auto"
        >
          <span>Lihat Semua Berita</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </router-link>
      </div>

      <LoadingSpinner v-if="loading.berita" text="Memuat berita terbaru..." />
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <NewsCard 
          v-for="b in beritaList.slice(0, 3)" 
          :key="b.id" 
          :berita="b" 
        />
      </div>
    </section>

    <!-- 6. Pengumuman Resmi Kelurahan -->
    <section class="bg-amber-50/60 py-16 border-y border-amber-200/70">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
          <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-200/80 text-amber-900 text-xs font-bold uppercase tracking-wider mb-2">
              <span class="w-2 h-2 rounded-full bg-amber-600"></span>
              Pemberitahuan
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Pengumuman & Himbauan Resmi
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
              Agenda penting, jadwal pelayanan keliling, dan edaran kedinasan warga.
            </p>
          </div>
          <router-link 
            to="/berita?tab=pengumuman" 
            class="text-xs sm:text-sm font-bold text-amber-800 hover:text-amber-950 transition"
          >
            Arsip Pengumuman &rarr;
          </router-link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <AnnouncementCard 
            v-for="item in pengumumanList.slice(0, 3)" 
            :key="item.id" 
            :pengumuman="item" 
          />
        </div>
      </div>
    </section>

    <!-- 7. Galeri Dokumentasi Kegiatan -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10">
        <div>
          <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            Dokumentasi
          </div>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Galeri Kegiatan & Potensi Wilayah
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Potret dinamika kebersamaan warga dan giat pembangunan di Kraksaan Wetan.
          </p>
        </div>

        <router-link 
          to="/galeri" 
          class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-900 transition self-start sm:self-auto"
        >
          <span>Buka Galeri Lengkap</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </router-link>
      </div>

      <LoadingSpinner v-if="loading.galeri" text="Memuat foto kegiatan..." />
      <GalleryGrid v-else :items="galeriList.slice(0, 6)" :show-filter="false" />
    </section>

    <!-- 8. Lokasi Kelurahan Kraksaan Wetan -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
          Peta & Navigasi
        </div>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
          Lokasi Kantor Kelurahan
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Kunjungi kantor kami untuk layanan langsung dan konsultasi tatap muka.
        </p>
      </div>

      <LocationMap />
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import HeroSection from '../components/HeroSection.vue';
import ServiceCard from '../components/ServiceCard.vue';
import StatisticCard from '../components/StatisticCard.vue';
import NewsCard from '../components/NewsCard.vue';
import AnnouncementCard from '../components/AnnouncementCard.vue';
import GalleryGrid from '../components/GalleryGrid.vue';
import LocationMap from '../components/LocationMap.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = reactive({
  layanan: true,
  profil: true,
  statistik: true,
  berita: true,
  galeri: true
});

const profil = ref({});
const statistik = ref({});
const quickServices = ref([]);
const beritaList = ref([]);
const pengumumanList = ref([]);
const galeriList = ref([]);

onMounted(async () => {
  try {
    const [p, s, l, b, pg, g] = await Promise.all([
      KelurahanService.getProfil(),
      KelurahanService.getStatistik(),
      KelurahanService.getLayanan(),
      KelurahanService.getBerita(),
      KelurahanService.getPengumuman(),
      KelurahanService.getGaleri()
    ]);

    profil.value = p;
    statistik.value = s;
    quickServices.value = l.slice(0, 4);
    beritaList.value = b;
    pengumumanList.value = pg;
    galeriList.value = g;
  } catch (err) {
    console.error('Gagal mengambil data beranda:', err);
  } finally {
    loading.layanan = false;
    loading.profil = false;
    loading.statistik = false;
    loading.berita = false;
    loading.galeri = false;
  }
});
</script>
