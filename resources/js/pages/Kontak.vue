<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Kontak & Pengaduan' }]" />

    <section class="bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white py-12 px-4 border-b border-emerald-800">
      <div class="max-w-7xl mx-auto">
        <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Pusat Layanan & Saluran Aspirasi</span>
        <h1 class="text-3xl sm:text-4xl font-extrabold mt-1 tracking-tight">Hubungi Kami & Saluran Pengaduan Resmi</h1>
        <p class="text-xs sm:text-sm text-emerald-200 mt-2 max-w-2xl leading-relaxed">
          Sampaikan kritik, saran, pengaduan infrastruktur, kedaruratan, atau permohonan informasi publik melalui kanal pengaduan resmi terpadu Pemerintah Kelurahan Kraksaan Wetan.
        </p>
      </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-10 space-y-12">
      <!-- Portal Pengaduan Resmi: SP4N LAPOR! & WhatsApp Halo SAE Probolinggo -->
      <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-800 flex items-center gap-1.5">
              <span class="w-2 h-2 rounded-full bg-amber-500"></span>
              Saluran Pengaduan Terintegrasi
            </span>
            <h2 class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">Portal Pengaduan Resmi & Cepat Tanggap</h2>
          </div>
          <span class="text-xs text-slate-500 font-medium">
            Layanan Nasional & Pemerintah Kabupaten Probolinggo
          </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Portal 1: Website SP4N-LAPOR! -->
          <div class="bg-gradient-to-br from-white via-rose-50/30 to-white p-6 sm:p-8 rounded-3xl border border-rose-200/80 shadow-xs hover:shadow-md hover:border-rose-400 transition-all flex flex-col justify-between">
            <div>
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3.5">
                  <div class="w-14 h-14 rounded-2xl bg-rose-600 text-white flex items-center justify-center font-black text-sm shadow-md flex-shrink-0 tracking-wider">
                    LAPOR!
                  </div>
                  <div>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 text-rose-800 uppercase tracking-wide">
                      Portal Nasional RI
                    </span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-0.5">Website SP4N-LAPOR!</h3>
                  </div>
                </div>
              </div>

              <p class="text-xs sm:text-sm text-slate-600 mt-4 leading-relaxed">
                Layanan Aspirasi dan Pengaduan Online Rakyat nasional yang diawasi langsung oleh KemenPAN-RB, Kantor Staf Presiden, dan Ombudsman RI untuk memantau standar pelayanan publik di seluruh Indonesia.
              </p>

              <ul class="mt-5 space-y-2 text-xs text-slate-600">
                <li class="flex items-center gap-2.5">
                  <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <span>Fitur pelaporan <strong>Anonim & Rahasia</strong></span>
                </li>
                <li class="flex items-center gap-2.5">
                  <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <span>Terkoneksi langsung ke Kementerian & Pemda</span>
                </li>
                <li class="flex items-center gap-2.5">
                  <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <span>Pemantauan status tindak lanjut secara transparan</span>
                </li>
              </ul>
            </div>

            <div class="mt-8 pt-5 border-t border-rose-100">
              <a 
                :href="spanLaporLink" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="w-full py-3.5 px-4 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-rose-600/30 transition flex items-center justify-center gap-2"
              >
                <span>Buka Website SP4N-LAPOR!</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            </div>
          </div>

          <!-- Portal 2: WhatsApp Halo SAE (Pemkab Probolinggo) -->
          <div class="bg-gradient-to-br from-white via-emerald-50/40 to-white p-6 sm:p-8 rounded-3xl border border-emerald-200/80 shadow-xs hover:shadow-md hover:border-emerald-400 transition-all flex flex-col justify-between">
            <div>
              <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3.5">
                  <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md flex-shrink-0">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.664-.699c.969.585 1.777.948 2.796.948 3.182 0 5.768-2.587 5.769-5.766.001-3.182-2.585-5.734-5.769-5.734zm0 10.518c-.896 0-1.67-.282-2.34-.766l-.167-.12-1.577.414.421-1.539-.13-.207c-.528-.842-.807-1.523-.807-2.534 0-2.614 2.127-4.741 4.741-4.741 2.613 0 4.74 2.127 4.74 4.741 0 2.613-2.127 4.752-4.74 4.752zm3.175-3.568c-.174-.087-1.03-.509-1.189-.567-.159-.058-.275-.087-.391.087-.116.174-.449.567-.551.683-.101.116-.203.13-.377.043-.174-.087-.735-.271-1.401-.864-.518-.462-.869-1.033-.971-1.207-.101-.174-.011-.268.076-.354.078-.078.174-.203.261-.304.087-.102.116-.174.174-.29.058-.116.029-.217-.015-.304-.043-.087-.391-.942-.536-1.29-.141-.339-.285-.293-.391-.298-.101-.005-.217-.006-.333-.006-.116 0-.304.043-.464.217-.159.174-.608.594-.608 1.45s.623 1.682.71 1.798c.087.116 1.226 1.872 2.97 2.625.415.179.739.286.992.367.417.133.796.114 1.096.069.335-.05 1.03-.421 1.175-.828.145-.406.145-.755.101-.828-.043-.072-.159-.116-.333-.203z"/></svg>
                  </div>
                  <div>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 uppercase tracking-wide">
                      Hotline AI Pemkab Probolinggo
                    </span>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 mt-0.5">WhatsApp Halo SAE</h3>
                  </div>
                </div>
              </div>

              <p class="text-xs sm:text-sm text-slate-600 mt-4 leading-relaxed">
                Layanan pengaduan masyarakat terintegrasi berbasis AI Pemerintah Kabupaten Probolinggo. Sampaikan keluhan infrastruktur, kedaruratan, atau pelayanan publik langsung via chat WhatsApp.
              </p>

              <ul class="mt-5 space-y-2 text-xs text-slate-600">
                <li class="flex items-center gap-2.5">
                  <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <span>Nomor WhatsApp: <strong>{{ profil.halo_sae_wa || '0821-3100-1001' }}</strong></span>
                </li>
                <li class="flex items-center gap-2.5">
                  <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <span>Target respon cepat darurat <strong>1 – 3 Jam</strong></span>
                </li>
                <li class="flex items-center gap-2.5">
                  <span class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  </span>
                  <span>Mendukung lampiran foto, video, dan koordinat GPS</span>
                </li>
              </ul>
            </div>

            <div class="mt-8 pt-5 border-t border-emerald-100 flex flex-col sm:flex-row gap-3">
              <a 
                :href="waLink" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="flex-1 py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm shadow-md hover:shadow-emerald-600/30 transition flex items-center justify-center gap-2"
              >
                <span>Chat WhatsApp Halo SAE</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
              <a 
                v-if="profil.halo_sae_link"
                :href="profil.halo_sae_link" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="py-3.5 px-4 rounded-xl border border-emerald-300 hover:bg-emerald-50 text-emerald-800 font-bold text-xs sm:text-sm transition flex items-center justify-center gap-1.5"
                title="Buka Website Halo SAE"
              >
                <span>Portal Web</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Info Kontak & Jam Layanan -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-gradient-to-br from-emerald-950 to-emerald-900 text-white p-6 sm:p-8 rounded-3xl shadow-sm space-y-4">
          <h3 class="text-xl font-bold text-amber-300">Informasi Kantor Kelurahan</h3>
          <div class="space-y-4 text-xs sm:text-sm">
            <div>
              <p class="text-emerald-300 font-semibold mb-1">Alamat Resmi:</p>
              <p class="text-emerald-100">{{ profil.alamat || 'Jl. Dr. Soetomo No. 12, Kraksaan Wetan, Kec. Kraksaan, Kab. Probolinggo, Jawa Timur 67282' }}</p>
            </div>
            <div>
              <p class="text-emerald-300 font-semibold mb-1">Kontak Telepon Kantor:</p>
              <p class="text-emerald-100">{{ profil.telepon || '(0335) 841234' }}</p>
            </div>
            <div>
              <p class="text-emerald-300 font-semibold mb-1">Surat Elektronik (Email Resmi):</p>
              <p class="text-emerald-100">{{ profil.email || 'kelurahan.kraksaanwetan@probolinggokab.go.id' }}</p>
            </div>
            <div>
              <p class="text-emerald-300 font-semibold mb-1">Jam Operasional Pelayanan:</p>
              <p class="text-emerald-100">{{ profil.jam_kerja || 'Senin - Kamis: 08.00 - 15.30 WIB | Jumat: 08.00 - 14.30 WIB' }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between">
          <div>
            <h4 class="font-bold text-slate-900 text-lg mb-2">Kanal Informasi & Media Sosial Resmi</h4>
            <p class="text-xs sm:text-sm text-slate-500 mb-6 leading-relaxed">
              Ikuti publikasi pengumuman, program pembangunan, liputan kegiatan masyarakat, dan transparansi anggaran melalui kanal informasi resmi kami:
            </p>
            <div class="space-y-3 text-xs sm:text-sm font-semibold text-slate-700">
              <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">f</span>
                <span>Facebook: @kelurahankraksaanwetan</span>
              </div>
              <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-pink-600 text-white flex items-center justify-center font-bold text-xs">ig</span>
                <span>Instagram: @kraksaanwetan_official</span>
              </div>
              <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="w-8 h-8 rounded-lg bg-red-600 text-white flex items-center justify-center font-bold text-xs">yt</span>
                <span>YouTube: Kelurahan Kraksaan Wetan</span>
              </div>
            </div>
          </div>

          <div class="mt-6 pt-4 border-t border-slate-100 text-xs text-slate-400">
            Pemerintah Kelurahan Kraksaan Wetan berkomitmen melayani warga dengan transparan, ramah, dan bebas pungli.
          </div>
        </div>
      </div>

      <!-- Peta Google Maps Section -->
      <div>
        <h3 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
          Peta Lokasi Kantor Kelurahan
        </h3>
        <LocationMap />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import LocationMap from '../components/LocationMap.vue';
import { KelurahanService } from '../services/api';

const profil = ref({});

onMounted(async () => {
  try {
    const data = await KelurahanService.getProfil();
    if (data) {
      profil.value = data;
    }
  } catch (err) {
    console.error('Gagal memuat profil kontak:', err);
  }
});

const spanLaporLink = computed(() => {
  return profil.value?.link_span_lapor || 'https://www.lapor.go.id/';
});

const cleanWaNumber = computed(() => {
  let num = (profil.value?.halo_sae_wa || '082131001001').replace(/\D/g, '');
  if (num.startsWith('0')) {
    num = '62' + num.slice(1);
  }
  return num;
});

const waLink = computed(() => {
  return `https://wa.me/${cleanWaNumber.value}?text=Halo%20Admin%20Halo%20SAE,%20saya%20warga%20Kelurahan%20Kraksaan%20Wetan%20ingin%20menyampaikan%20laporan`;
});
</script>
