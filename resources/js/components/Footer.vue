<template>
  <footer class="bg-emerald-950 text-emerald-100 border-t-4 border-amber-500 mt-auto relative overflow-hidden">
    <!-- Subtle Government Grid Watermark Background -->
    <div class="absolute inset-0 opacity-5 pointer-events-none bg-[radial-gradient(#34d399_1px,transparent_1px)] [background-size:16px_16px]"></div>

    <!-- Main Footer Container -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-10">
        
        <!-- Kolom 1: Identitas Resmi Kelurahan (lg:col-span-4) -->
        <div class="lg:col-span-4 space-y-4 reveal">
          <div class="flex items-center gap-3.5">
            <!-- Emblem / Logo Kelurahan / Pemkab -->
            <div class="w-12 h-14 shrink-0 flex items-center justify-center">
              <img 
                :src="profil?.logo || '/images/logo-probolinggo.png'" 
                :alt="profil?.nama || 'Logo Kelurahan Kraksaan Wetan'" 
                class="w-full h-full object-contain drop-shadow-sm" 
              />
            </div>
            <div>
              <span class="text-[11px] font-semibold text-emerald-300 uppercase tracking-wider block">Pemerintah Kab. Probolinggo</span>
              <h3 class="text-white font-black text-lg tracking-tight leading-tight">Kelurahan Kraksaan Wetan</h3>
              <p class="text-xs text-emerald-400 font-medium">Kecamatan Kraksaan &bull; Jawa Timur</p>
            </div>
          </div>

          <p class="text-xs text-emerald-200/90 leading-relaxed font-normal">
            Pusat administrasi pemerintahan dan pelayanan publik terpadu masyarakat Kelurahan Kraksaan Wetan. Mengutamakan integritas, transparansi pembangunan, dan kemudahan layanan administrasi kependudukan bebas pungutan liar (Rp 0).
          </p>

          <!-- Administrative ID Badges -->
          <div class="flex flex-wrap gap-2 pt-1">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-900/90 border border-emerald-700/60 text-emerald-200 text-xs font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
              Kode Pos: 67282
            </span>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-900/90 border border-emerald-700/60 text-emerald-200 text-xs font-semibold">
              <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
              Kode Wilayah: 35.13.14.1001
            </span>
          </div>
        </div>

        <!-- Kolom 2: Layanan & Informasi Publik (lg:col-span-3) -->
        <div class="lg:col-span-3 space-y-3 reveal delay-100">
          <h4 class="text-white font-bold text-sm tracking-wide flex items-center gap-2 border-b border-emerald-800 pb-2">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            Layanan & Transparansi
          </h4>
          <ul class="space-y-2 text-xs text-emerald-200/90">
            <li>
              <router-link to="/pelayanan" class="hover:text-amber-300 transition-colors flex items-center gap-2">
                <span class="text-emerald-500 font-bold">&rsaquo;</span> SOP & Persyaratan Pelayanan
              </router-link>
            </li>
            <li>
              <router-link to="/transparansi" class="hover:text-amber-300 transition-colors flex items-center gap-2">
                <span class="text-emerald-500 font-bold">&rsaquo;</span> Transparansi Anggaran (APBD/Kelurahan)
              </router-link>
            </li>
            <li>
              <router-link to="/informasi-publik" class="hover:text-amber-300 transition-colors flex items-center gap-2">
                <span class="text-emerald-500 font-bold">&rsaquo;</span> Data Kependudukan & Lingkungan RW
              </router-link>
            </li>
            <li>
              <router-link to="/pemerintahan" class="hover:text-amber-300 transition-colors flex items-center gap-2">
                <span class="text-emerald-500 font-bold">&rsaquo;</span> Susunan Aparatur & Profil Lurah
              </router-link>
            </li>
            <li>
              <router-link to="/lembaga" class="hover:text-amber-300 transition-colors flex items-center gap-2">
                <span class="text-emerald-500 font-bold">&rsaquo;</span> Lembaga Kemasyarakatan (LKK)
              </router-link>
            </li>
            <li>
              <router-link to="/berita" class="hover:text-amber-300 transition-colors flex items-center gap-2">
                <span class="text-emerald-500 font-bold">&rsaquo;</span> Berita & Pengumuman Kedinasan
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Kolom 3: Jam Pelayanan & Integritas (lg:col-span-2) -->
        <div class="lg:col-span-2 space-y-3 reveal delay-150">
          <h4 class="text-white font-bold text-sm tracking-wide flex items-center gap-2 border-b border-emerald-800 pb-2">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            Jam Pelayanan Kantor
          </h4>
          <div class="bg-emerald-900/60 rounded-2xl p-3 border border-emerald-800/80 text-xs space-y-2">
            <div>
              <p class="text-emerald-300 text-[11px] font-semibold">Senin — Kamis</p>
              <p class="text-white font-bold text-xs mt-0.5">08.00 — 15.30 WIB</p>
            </div>
            <div class="pt-1.5 border-t border-emerald-800/80">
              <p class="text-emerald-300 text-[11px] font-semibold">Jumat</p>
              <p class="text-white font-bold text-xs mt-0.5">08.00 — 14.30 WIB</p>
            </div>
            <div class="pt-1.5 border-t border-emerald-800/80 text-[10px] text-emerald-300/80">
              <p>Sabtu, Minggu & Hari Libur:</p>
              <p class="text-amber-300 font-semibold">Tutup (Pelayanan Online Aktif)</p>
            </div>
          </div>
          <div class="p-2.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-[11px] text-amber-200 flex items-center gap-2">
            <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            <span class="font-bold">Pelayanan Ramah & Rp 0 (Bebas Pungli)</span>
          </div>
        </div>

        <!-- Kolom 4: Kontak & Pengaduan Resmi (lg:col-span-3) -->
        <div class="lg:col-span-3 space-y-3 reveal delay-200">
          <h4 class="text-white font-bold text-sm tracking-wide flex items-center gap-2 border-b border-emerald-800 pb-2">
            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
            Kontak & Pengaduan
          </h4>
          <div class="space-y-2.5 text-xs text-emerald-200">
            <div class="flex items-start gap-2.5">
              <svg class="w-4 h-4 text-amber-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span>{{ profil?.alamat || 'Jl. Dr. Soetomo No. 12, Kraksaan Wetan, Kec. Kraksaan, Kab. Probolinggo 67282' }}</span>
            </div>

            <div class="flex items-center gap-2.5">
              <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
              <span>{{ profil?.telepon || '(0335) 841234' }}</span>
            </div>

            <div class="flex items-center gap-2.5">
              <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <span class="truncate">{{ profil?.email || 'kelurahan.kraksaanwetan@probolinggokab.go.id' }}</span>
            </div>
          </div>

          <!-- Official Government Service Portals -->
          <div class="pt-2 space-y-1.5 text-xs">
            <a 
              :href="profil?.link_span_lapor || 'https://www.lapor.go.id/'" 
              target="_blank" 
              rel="noopener"
              class="flex items-center justify-between p-2 rounded-xl bg-rose-950/80 border border-rose-800/80 text-rose-200 hover:bg-rose-900/90 transition"
            >
              <span class="font-bold text-[11px]">Portal SP4N-LAPOR! RI</span>
              <span class="text-[10px] text-rose-300">&nearr;</span>
            </a>

            <a 
              :href="profil?.halo_sae_wa ? 'https://wa.me/' + profil.halo_sae_wa.replace(/\D/g, '') : 'https://wa.me/6282131001001'" 
              target="_blank" 
              rel="noopener"
              class="flex items-center justify-between p-2 rounded-xl bg-emerald-900/80 border border-emerald-700/80 text-emerald-200 hover:bg-emerald-800/90 transition"
            >
              <span class="font-bold text-[11px]">WhatsApp Halo SAE (Respon Cepat)</span>
              <span class="text-[10px] text-emerald-300">&nearr;</span>
            </a>
          </div>
        </div>

      </div>
    </div>

    <!-- Bottom Bar Copyright & Administrative Access -->
    <div class="bg-black/30 border-t border-emerald-900 py-4 px-4 sm:px-6 lg:px-8 text-xs text-emerald-300/80">
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
        <div>
          <p class="font-semibold text-white">
            &copy; 2026 Pemerintah Kelurahan Kraksaan Wetan.
          </p>
          <p class="text-[11px] text-emerald-400/80">
            Hak Cipta Dilindungi Undang-Undang &bull; Bagian dari Pemerintah Kabupaten Probolinggo, Jawa Timur
          </p>
        </div>

        <div class="flex items-center gap-4 text-xs font-semibold">
          <a href="https://probolinggokab.go.id" target="_blank" rel="noopener" class="text-emerald-300 hover:text-amber-300 transition">
            Portal Pemkab Probolinggo
          </a>
          <span>&bull;</span>
          <router-link to="/kontak" class="text-emerald-300 hover:text-amber-300 transition">
            Kontak Darurat
          </router-link>
          <span>&bull;</span>
          <router-link to="/admin/login" class="text-amber-400 hover:text-amber-300 transition">
            Panel Administrator
          </router-link>
        </div>
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
