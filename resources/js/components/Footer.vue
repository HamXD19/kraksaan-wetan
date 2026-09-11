<template>
  <footer class="bg-slate-900 text-slate-300 border-t-4 border-emerald-600 mt-auto">
    <!-- Main Footer Links & Info -->
    <div class="max-w-7xl mx-auto px-4 py-12 lg:py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">
        <!-- Kolom 1: Tentang Kelurahan -->
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <!-- Emblem / Seal -->
            <div class="w-10 h-12 flex-shrink-0 flex items-center justify-center">
              <img 
                v-if="profil?.logo" 
                :src="profil.logo" 
                :alt="profil?.nama || 'Logo Kelurahan'" 
                class="w-full h-full object-contain" 
              />
              <svg v-else viewBox="0 0 80 96" class="w-full h-full" fill="none">
                <path d="M40 2L76 18V50C76 72 40 94 40 94C40 94 4 72 4 50V18L40 2Z" fill="#047857" stroke="#f59e0b" stroke-width="3"/>
                <path d="M22 56L40 32L58 56H22Z" fill="#f8fafc"/>
                <circle cx="40" cy="26" r="5" fill="#f59e0b"/>
                <text x="40" y="80" text-anchor="middle" fill="#fef08a" font-size="8" font-weight="bold">PROBOLINGGO</text>
              </svg>
            </div>
            <div>
              <h3 class="text-white font-bold text-base leading-tight">Kelurahan Kraksaan Wetan</h3>
              <p class="text-xs text-emerald-400 font-medium">Kabupaten Probolinggo</p>
            </div>
          </div>
          <p class="text-xs text-slate-400 leading-relaxed">
            Portal resmi pelayanan informasi publik, transparansi pembangunan, dan administrasi masyarakat Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo, Provinsi Jawa Timur.
          </p>
          <div class="pt-2">
            <span class="inline-block px-3 py-1 rounded bg-emerald-950/80 border border-emerald-800 text-emerald-300 text-[11px] font-semibold">
              Kode Pos: 67282 &bull; Kode Wilayah: 35.13.14.1001
            </span>
          </div>
        </div>

        <!-- Kolom 2: Navigasi Cepat -->
        <div>
          <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4 border-l-2 border-amber-400 pl-2.5">
            Navigasi Utama
          </h4>
          <ul class="space-y-2 text-xs">
            <li>
              <router-link to="/" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span class="text-emerald-500">&rsaquo;</span> Beranda
              </router-link>
            </li>
            <li>
              <router-link to="/profil" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span class="text-emerald-500">&rsaquo;</span> Profil Kelurahan & Visi Misi
              </router-link>
            </li>
            <li>
              <router-link to="/pemerintahan" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span class="text-emerald-500">&rsaquo;</span> Struktur Pemerintahan & RT/RW
              </router-link>
            </li>
            <li>
              <router-link to="/pelayanan" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span class="text-emerald-500">&rsaquo;</span> Layanan Administrasi Kependudukan
              </router-link>
            </li>
            <li>
              <router-link to="/berita" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span class="text-emerald-500">&rsaquo;</span> Berita & Agenda Kelurahan
              </router-link>
            </li>
            <li>
              <router-link to="/galeri" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span class="text-emerald-500">&rsaquo;</span> Galeri Dokumentasi Kegiatan
              </router-link>
            </li>
            <li>
              <router-link to="/kontak" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span class="text-emerald-500">&rsaquo;</span> Hubungi Kami & Pengaduan
              </router-link>
            </li>
            <li>
              <router-link to="/admin/login" class="hover:text-amber-300 transition-colors flex items-center gap-1.5 text-emerald-300/80">
                <span class="text-amber-400">&rsaquo;</span> Login Administrator (CMS)
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Kolom 3: Layanan & Jam Operasional -->
        <div>
          <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4 border-l-2 border-amber-400 pl-2.5">
            Jam Layanan Kantor
          </h4>
          <div class="bg-slate-800/80 p-3.5 rounded-lg border border-slate-700/60 text-xs space-y-2 mb-4">
            <div class="flex justify-between items-center text-slate-300">
              <span class="font-medium">Senin - Kamis</span>
              <span class="text-emerald-400 font-mono font-semibold">08:00 - 15:30</span>
            </div>
            <div class="flex justify-between items-center text-slate-300">
              <span class="font-medium">Jumat</span>
              <span class="text-emerald-400 font-mono font-semibold">08:00 - 14:30</span>
            </div>
            <div class="flex justify-between items-center text-slate-400 pt-1 border-t border-slate-700">
              <span>Sabtu - Minggu & Hari Libur</span>
              <span class="text-rose-400 font-medium">Tutup</span>
            </div>
          </div>
          <div class="text-xs text-slate-400 space-y-1">
            <p class="font-medium text-slate-300">Tautan Pengaduan & Layanan:</p>
            <p><a href="https://probolinggokab.go.id" target="_blank" rel="noopener" class="text-emerald-400 hover:underline">Portal Resmi Pemkab Probolinggo &nearr;</a></p>
            <p><a href="https://www.lapor.go.id" target="_blank" rel="noopener" class="text-rose-400 hover:underline">SP4N-LAPOR! Nasional &nearr;</a></p>
            <p><a href="https://wa.me/6282131001001" target="_blank" rel="noopener" class="text-emerald-400 hover:underline">WhatsApp Halo SAE (0821-3100-1001) &nearr;</a></p>
          </div>
        </div>

        <!-- Kolom 4: Alamat & Kontak -->
        <div class="space-y-3">
          <h4 class="text-white font-semibold text-sm uppercase tracking-wider mb-4 border-l-2 border-amber-400 pl-2.5">
            Kontak & Lokasi
          </h4>
          <div class="text-xs space-y-2.5 text-slate-300">
            <div class="flex items-start gap-2.5">
              <svg class="w-4 h-4 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span>Jl. Dr. Soetomo No. 12, Kraksaan Wetan, Kec. Kraksaan, Kab. Probolinggo, Jawa Timur 67282</span>
            </div>
            <div class="flex items-center gap-2.5">
              <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <span>(0335) 841234</span>
            </div>
            <div class="flex items-center gap-2.5">
              <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <span class="truncate">kelurahan.kraksaanwetan@probolinggokab.go.id</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Bar Copyright -->
    <div class="bg-slate-950 border-t border-slate-800/80 py-4 px-4 text-xs text-slate-400 text-center">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>&copy; 2026 <strong>Kelurahan Kraksaan Wetan</strong>. Seluruh hak cipta dilindungi.</p>
        <p class="text-emerald-400/90 font-medium">Bagian dari Pemerintah Kabupaten Probolinggo, Jawa Timur</p>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { KelurahanService } from '../services/api';

const profil = ref(null);

const loadProfil = async () => {
  try {
    profil.value = await KelurahanService.getProfil();
  } catch (e) {
    // fallback
  }
};

const handleProfilUpdate = (e) => {
  if (e.detail) {
    profil.value = e.detail;
  } else {
    loadProfil();
  }
};

onMounted(() => {
  loadProfil();
  window.addEventListener('profil-updated', handleProfilUpdate);
});

onUnmounted(() => {
  window.removeEventListener('profil-updated', handleProfilUpdate);
});
</script>
