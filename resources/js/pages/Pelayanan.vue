<template>
  <div class="pb-16">
    <Breadcrumb :items="[{ label: 'Standar Operasional Prosedur (SOP) Pelayanan' }]" />

    <!-- Header Section -->
    <HeroPageHeader 
      badge-text="Standar Pelayanan Publik Kelurahan"
      title="Katalog SOP Pelayanan Masyarakat"
      description="Panduan resmi Standar Operasional Prosedur (SOP), daftar persyaratan berkas, alur tahapan permohonan, estimasi waktu penyelesaian, dan kepastian tarif layanan di Kelurahan Kraksaan Wetan."
    >
      <div class="flex flex-wrap items-center gap-2.5 mt-4">
        <a 
          href="#maklumat-pelayanan"
          class="px-5 py-3 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm transition flex items-center gap-2 shadow-md cursor-pointer"
        >
          <svg class="w-4 h-4 text-emerald-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>Maklumat Pelayanan</span>
        </a>
        <router-link 
          to="/survei-skm"
          class="px-5 py-3 rounded-xl bg-emerald-800/90 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm transition flex items-center gap-2 border border-emerald-600/50 shadow-xs cursor-pointer"
        >
          <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
          <span>Survei SKM Warga</span>
        </router-link>
        <a 
          href="#panduan-umum"
          class="px-4 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs sm:text-sm transition flex items-center gap-1.5"
        >
          <span>Alur SOP</span>
        </a>
      </div>
    </HeroPageHeader>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-10">
      <!-- Highlight Banner: Bebas Biaya & Skor SKM -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 reveal">
        <!-- Banner 1: Bebas Biaya -->
        <div class="lg:col-span-7 bg-gradient-to-r from-emerald-950 via-emerald-900 to-emerald-950 text-white p-5 sm:p-6 rounded-3xl shadow-sm border border-emerald-800 flex flex-col sm:flex-row items-center justify-between gap-5">
          <div class="flex items-center gap-4 text-center sm:text-left">
            <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center flex-shrink-0">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <span class="px-2.5 py-0.5 rounded-full bg-amber-500 text-slate-950 text-[11px] font-bold uppercase tracking-wider">
                100% Gratis & Bebas Pungli
              </span>
              <h3 class="text-base sm:text-lg font-bold mt-1 text-white">Seluruh Pelayanan di Kelurahan Kraksaan Wetan Gratis</h3>
              <p class="text-xs text-emerald-200 mt-0.5">Warga dipersilakan melapor melalui SP4N-LAPOR! bila menemukan indikasi pungutan liar.</p>
            </div>
          </div>
          <router-link 
            to="/kontak" 
            class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs sm:text-sm shadow-md transition flex-shrink-0 whitespace-nowrap"
          >
            Pengaduan
          </router-link>
        </div>

        <!-- Banner 2: Indeks Kepuasan Masyarakat (SKM) Ringkas -->
        <div class="lg:col-span-5 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="flex items-center gap-3.5">
            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 flex items-center justify-center flex-shrink-0 font-black text-sm">
              IKM
            </div>
            <div>
              <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Kepuasan Masyarakat</div>
              <div class="flex items-baseline gap-1.5 mt-0.5">
                <span class="text-2xl font-black text-emerald-800">{{ skmLatest?.skor_ikm || '89.42' }}</span>
                <span class="text-xs text-slate-500 font-semibold">/ {{ skmLatest?.skala_maksimal || '100' }}</span>
                <span class="ml-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">
                  {{ skmLatest?.mutu_pelayanan || 'Mutu A' }}
                </span>
              </div>
            </div>
          </div>
          <router-link 
            to="/survei-skm" 
            class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition flex-shrink-0 whitespace-nowrap shadow-xs"
          >
            Hasil SKM &rarr;
          </router-link>
        </div>
      </div>

      <!-- Maklumat Pelayanan Section -->
      <section id="maklumat-pelayanan" class="bg-gradient-to-br from-white via-emerald-50/20 to-slate-50 rounded-3xl p-6 sm:p-10 border-2 border-emerald-600/30 shadow-lg scroll-mt-24 space-y-6 relative overflow-hidden">
        <!-- Background Subtle Watermark -->
        <div class="absolute -right-16 -bottom-16 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-5 border-b border-emerald-100 relative z-10">
          <div class="space-y-2">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
              <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
              Komitmen Mutu & Janji Pelayanan Publik
            </div>
            <h3 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight leading-tight">
              {{ maklumat?.judul || 'Maklumat Pelayanan Kelurahan Kraksaan Wetan' }}
            </h3>
            <div v-if="maklumat?.nomor_sk" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-xs font-semibold text-emerald-800">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span>SK Penetapan: {{ maklumat.nomor_sk }}</span>
            </div>
          </div>

          <div v-if="maklumat?.motto" class="max-w-md p-4 rounded-2xl bg-amber-50/90 border border-amber-200/80 text-xs text-amber-950 shadow-xs">
            <span class="font-bold flex items-center gap-1.5 text-[10px] uppercase tracking-wider text-amber-700 mb-1">
              <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
              Motto Pelayanan:
            </span>
            <span class="italic font-semibold text-amber-900 leading-relaxed block">"{{ maklumat.motto }}"</span>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
          <!-- Kolom Teks Paragraf Komitmen -->
          <div class="lg:col-span-7 space-y-4">
            <!-- Box Piagam Janji -->
            <div class="relative bg-white border border-emerald-200/80 rounded-2xl p-5 sm:p-6 shadow-xs space-y-4">
              <div class="flex items-start gap-3">
                <span class="text-3xl sm:text-4xl text-amber-500/60 font-serif leading-none select-none">“</span>
                <p class="text-xs sm:text-sm text-slate-700 leading-relaxed font-normal whitespace-pre-line break-words flex-1">
                  {{ maklumat?.konten || 'Dengan ini kami menyatakan sanggup menyelenggarakan pelayanan publik sesuai standar yang telah ditetapkan.' }}
                </p>
              </div>

              <!-- 4 Standar Pelayanan Pills -->
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-4 border-t border-slate-100 text-[11px]">
                <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                  <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Bebas Biaya (Rp 0)</span>
                </div>
                <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                  <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Transparan & SOP</span>
                </div>
                <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                  <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Tepat & Pasti</span>
                </div>
                <div class="flex items-center gap-1.5 text-slate-700 font-medium">
                  <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Ramah & Akuntabel</span>
                </div>
              </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="pt-2 flex flex-wrap items-center gap-3">
              <router-link
                to="/survei-skm"
                class="px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer active:scale-95"
              >
                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Beri Penilaian di Survei Kepuasan</span>
              </router-link>

              <button
                v-if="maklumat?.gambar"
                type="button"
                @click="showMaklumatModal = true"
                class="px-4 py-2.5 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-800 font-bold text-xs transition flex items-center gap-2 shadow-xs cursor-pointer active:scale-95"
              >
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Lihat Poster Piagam HD</span>
              </button>
            </div>
          </div>

          <!-- Kolom Gambar Poster Maklumat Berbingkai -->
          <div class="lg:col-span-5 flex flex-col items-center justify-center">
            <div 
              v-if="maklumat?.gambar"
              @click="showMaklumatModal = true"
              class="group relative w-full max-w-sm bg-slate-950 rounded-2xl p-2.5 border-2 border-amber-400/50 shadow-xl hover:border-amber-400 transition-all duration-300 cursor-pointer transform hover:-translate-y-1.5"
              title="Klik untuk memperbesar poster maklumat"
            >
              <div class="w-full aspect-[3/4] sm:aspect-[4/5] bg-black/40 rounded-xl overflow-hidden flex items-center justify-center relative">
                <img 
                  :src="maklumat.gambar" 
                  :alt="maklumat.judul" 
                  class="w-full h-full object-contain rounded-lg group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
                <div class="absolute inset-0 bg-slate-950/65 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 text-white text-xs font-bold p-3 text-center backdrop-blur-2xs">
                  <div class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                  </div>
                  <span>Perbesar Piagam HD</span>
                </div>
              </div>
              <p class="text-[10px] text-center text-slate-400 mt-2 font-medium flex items-center justify-center gap-1">
                <svg class="w-3 h-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Klik untuk melihat piagam penuh</span>
              </p>
            </div>

            <!-- Fallback Jika Belum Ada Gambar: Plakat Resmi Kelurahan -->
            <div v-else class="w-full max-w-sm p-6 rounded-2xl border-2 border-emerald-300/80 bg-gradient-to-b from-emerald-50 to-white text-center space-y-3.5 shadow-sm">
              <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 text-emerald-700 border-2 border-emerald-300 flex items-center justify-center shadow-xs">
                <svg viewBox="0 0 80 96" class="w-8 h-10" fill="none">
                  <path d="M40 2L76 18V50C76 72 40 94 40 94C40 94 4 72 4 50V18L40 2Z" fill="#047857" stroke="#f59e0b" stroke-width="3"/>
                  <path d="M22 56L40 32L58 56H22Z" fill="#f8fafc"/>
                  <circle cx="40" cy="26" r="5" fill="#f59e0b"/>
                </svg>
              </div>
              <div>
                <h5 class="text-sm font-bold text-emerald-950 uppercase tracking-wider">Piagam Maklumat Pelayanan</h5>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Komitmen resmi aparatur Kelurahan Kraksaan Wetan memberikan pelayanan prima, transparan, dan tanpa diskriminasi.</p>
              </div>
              <div class="pt-2 border-t border-emerald-100 text-[11px] font-semibold text-emerald-800">
                Pemerintah Kelurahan Kraksaan Wetan
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Search & Filter Bar -->
      <div class="bg-white p-4 sm:p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4 reveal delay-100">
        <div class="flex flex-col sm:flex-row gap-3">
          <!-- Search Input -->
          <div class="relative flex-1">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input 
              type="text" 
              v-model="searchQuery" 
              placeholder="Cari SOP atau nama permohonan layanan (misal: KTP, KK, Keterangan Usaha)..." 
              class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-slate-50 focus:bg-white text-xs sm:text-sm"
            />
          </div>

          <!-- Total Counter -->
          <div class="flex items-center px-4 py-2 rounded-xl bg-slate-100 text-slate-600 text-xs font-semibold self-start sm:self-auto">
            <span>{{ filteredLayanan.length }} Layanan Tersedia</span>
          </div>
        </div>

        <!-- Filter Categories -->
        <div class="flex flex-wrap gap-1.5 pt-2 border-t border-slate-100">
          <button 
            @click="selectedKategori = 'Semua'"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
            :class="selectedKategori === 'Semua' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            Semua Kategori
          </button>
          <button 
            v-for="kat in kategoriList" 
            :key="kat"
            @click="selectedKategori = kat"
            class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition"
            :class="selectedKategori === kat ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            {{ kat }}
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <LoadingSpinner v-if="loading" text="Memuat katalog layanan..." />

      <!-- Grid Layanan Cards -->
      <div v-else-if="filteredLayanan.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div 
          v-for="(item, idx) in filteredLayanan" 
          :key="item.id"
          class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col justify-between reveal"
          :class="`delay-${((idx % 6) + 1) * 75}`"
        >
          <div class="space-y-4">
            <!-- Header: Category & SLA -->
            <div class="flex items-center justify-between gap-2">
              <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 tracking-wider">
                {{ item.kategori }}
              </span>
              <span class="text-[11px] text-emerald-700 font-bold flex items-center gap-1 bg-emerald-50 px-2.5 py-0.5 rounded-md">
                <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ item.waktu || '10 - 15 Menit' }}
              </span>
            </div>

            <!-- Title & Description -->
            <div>
              <h3 class="text-base sm:text-lg font-bold text-slate-900 leading-snug group-hover:text-emerald-700 transition">
                {{ item.judul }}
              </h3>
              <p class="text-xs text-slate-500 mt-1.5 leading-relaxed line-clamp-2">
                {{ item.deskripsi }}
              </p>
            </div>

            <!-- Requirements Preview -->
            <div v-if="item.persyaratan && item.persyaratan.length > 0" class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
              <span class="text-[11px] font-bold text-slate-700 uppercase tracking-wider block">
                Persyaratan Berkas:
              </span>
              <ul class="space-y-1 text-xs text-slate-600">
                <li 
                  v-for="(req, rIdx) in item.persyaratan.slice(0, 3)" 
                  :key="rIdx"
                  class="flex items-start gap-1.5"
                >
                  <svg class="w-3.5 h-3.5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                  <span class="line-clamp-1">{{ req }}</span>
                </li>
                <li v-if="item.persyaratan.length > 3" class="text-[11px] text-emerald-700 font-semibold pl-5">
                  + {{ item.persyaratan.length - 3 }} berkas pendukung lainnya
                </li>
              </ul>
            </div>
          </div>

          <!-- Card Footer: Cost & SOP Action Button -->
          <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
            <div>
              <span class="text-[10px] text-slate-400 block font-semibold uppercase">Biaya Resmi</span>
              <span class="text-xs font-bold text-emerald-700">{{ item.biaya || 'Gratis (Rp 0)' }}</span>
            </div>

            <button 
              @click="openDetailSOP(item)"
              class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs hover:shadow-md transition flex items-center gap-1.5 cursor-pointer"
            >
              <span>Lihat SOP</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
        <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <h3 class="text-base font-bold text-slate-800 mb-1">Layanan Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 mb-4">Tidak ada layanan yang sesuai dengan kata kunci atau filter yang Anda pilih.</p>
        <button 
          @click="resetFilter"
          class="px-4 py-2 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition"
        >
          Reset Pencarian
        </button>
      </div>

      <!-- General SOP Procedure Guide -->
      <div id="panduan-umum" class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs space-y-6">
        <div>
          <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase mb-2">
            Standar Pelayanan Prima
          </span>
          <h2 class="text-2xl font-bold text-slate-900">Alur Umum Pengurusan Pelayanan di Kantor Kelurahan</h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Masyarakat dapat mengikuti 4 langkah mudah berikut saat mengurus dokumen administrasi di kantor kelurahan:
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-2">
          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center mb-3">1</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Siapkan Berkas</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Pastikan berkas asli & fotokopi KTP, Kartu Keluarga, dan surat pengantar RT/RW (bila disyaratkan) telah lengkap.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center mb-3">2</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Ambil Nomor Antrean</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Kunjungi loket pelayanan terpadu Kelurahan Kraksaan Wetan pada hari dan jam kerja yang berlaku.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center mb-3">3</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Verifikasi & Pemrosesan</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Petugas memverifikasi kelengkapan berkas dan memproses dokumen permohonan surat secara cepat dan teliti.
            </p>
          </div>

          <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 relative">
            <div class="w-8 h-8 rounded-xl bg-amber-500 text-slate-950 font-black text-sm flex items-center justify-center mb-3">4</div>
            <h4 class="font-bold text-slate-900 text-sm mb-1.5">Penerbitan Dokumen</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
              Penandatanganan oleh Kepala Kelurahan/pejabat berwenang dan penyerahan dokumen resmi kepada pemohon (Rp 0).
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail SOP Lengkap -->
    <div 
      v-if="activeSop" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs animate-fade-in"
    >
      <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 flex flex-col">
        <!-- Modal Header -->
        <div class="p-6 border-b border-slate-100 flex items-start justify-between gap-4 sticky top-0 bg-white z-10 rounded-t-3xl">
          <div>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800">
              {{ activeSop.kategori }}
            </span>
            <h2 class="text-xl font-bold text-slate-900 mt-1.5 leading-snug">
              {{ activeSop.judul }}
            </h2>
          </div>
          <button 
            @click="activeSop = null" 
            class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center transition"
          >
            &times;
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 sm:p-8 space-y-6 text-xs sm:text-sm">
          <!-- Deskripsi Layanan -->
          <div>
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Deskripsi Layanan</h4>
            <div 
              class="text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100 rich-content"
              v-html="activeSop.deskripsi"
            ></div>
          </div>

          <!-- Parameter Waktu & Biaya -->
          <div class="grid grid-cols-2 gap-4">
            <div class="p-4 rounded-2xl bg-emerald-50/70 border border-emerald-100">
              <span class="text-[10px] uppercase font-bold text-emerald-700 tracking-wider">Estimasi Waktu</span>
              <p class="text-sm font-bold text-slate-900 mt-0.5">{{ activeSop.waktu || '10 - 15 Menit' }}</p>
            </div>
            <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-100">
              <span class="text-[10px] uppercase font-bold text-amber-800 tracking-wider">Tarif / Biaya</span>
              <p class="text-sm font-bold text-slate-900 mt-0.5">{{ activeSop.biaya || 'Gratis (Rp 0)' }}</p>
            </div>
          </div>

          <!-- Persyaratan Berkas -->
          <div>
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Persyaratan Dokumen yang Wajib Disiapkan
            </h4>
            <div v-if="activeSop.persyaratan && activeSop.persyaratan.length" class="space-y-2">
              <div 
                v-for="(p, idx) in activeSop.persyaratan" 
                :key="idx" 
                class="p-3 rounded-xl bg-slate-50 border border-slate-200/70 flex items-start gap-3"
              >
                <div class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0 mt-0.5">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-slate-700 font-medium">{{ p }}</span>
              </div>
            </div>
            <p v-else class="text-slate-400 italic">Tidak ada persyaratan khusus.</p>
          </div>

          <!-- Alur / Prosedur SOP -->
          <div>
            <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2 flex items-center gap-2">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Alur Standar Operasional Prosedur (SOP)
            </h4>
            <div 
              v-if="activeSop.alur" 
              class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-slate-700 leading-relaxed rich-content"
              v-html="activeSop.alur"
            ></div>
            <div v-else class="space-y-2">
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">1</span>
                <span>Pemohon datang ke loket pelayanan dengan membawa seluruh berkas persyaratan.</span>
              </div>
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">2</span>
                <span>Petugas pelayanan memeriksa kelengkapan administrasi dan keabsahan berkas pemohon.</span>
              </div>
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0">3</span>
                <span>Petugas memproses draf surat/dokumen dan diajukan ke Kepala Kelurahan untuk penandatanganan resmi.</span>
              </div>
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 flex items-start gap-3">
                <span class="w-5 h-5 rounded-full bg-amber-500 text-slate-950 font-bold text-xs flex items-center justify-center flex-shrink-0">4</span>
                <span>Dokumen yang telah ditandatangani dan distempel diserahkan kepada pemohon tanpa dipungut biaya.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-5 border-t border-slate-100 bg-slate-50 rounded-b-3xl flex justify-end">
          <button 
            @click="activeSop = null" 
            class="px-5 py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Preview Gambar Poster Maklumat HD -->
    <div 
      v-if="showMaklumatModal && maklumat?.gambar" 
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/85 backdrop-blur-sm animate-fade-in"
      @click.self="showMaklumatModal = false"
    >
      <div class="bg-white rounded-3xl max-w-4xl w-full max-h-[92vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col">
        <div class="p-4 px-6 border-b border-slate-100 flex items-center justify-between bg-white z-10">
          <div>
            <h3 class="font-bold text-sm sm:text-base text-slate-900">{{ maklumat.judul }}</h3>
            <p v-if="maklumat.nomor_sk" class="text-xs text-emerald-700 font-semibold">{{ maklumat.nomor_sk }}</p>
          </div>
          <div class="flex items-center gap-2">
            <a 
              :href="maklumat.gambar" 
              target="_blank" 
              class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-1"
              title="Buka Gambar Asli"
            >
              <span>Ukuran Penuh</span>
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            <button 
              @click="showMaklumatModal = false" 
              class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition cursor-pointer font-bold"
              title="Tutup"
            >
              &times;
            </button>
          </div>
        </div>
        <div class="p-4 sm:p-6 overflow-y-auto flex items-center justify-center bg-slate-900/90 min-h-[50vh]">
          <img :src="maklumat.gambar" :alt="maklumat.judul" class="max-w-full max-h-[78vh] object-contain rounded-xl shadow-2xl border border-white/10" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import HeroPageHeader from '../components/HeroPageHeader.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const layananList = ref([]);
const kategoriList = ref([]);
const selectedKategori = ref('Semua');
const searchQuery = ref('');
const activeSop = ref(null);
const maklumat = ref(null);
const skmLatest = ref(null);
const showMaklumatModal = ref(false);

onMounted(async () => {
  try {
    const [layananData, katData, maklumatData, skmData] = await Promise.all([
      KelurahanService.getLayanan(),
      KelurahanService.getKategori('layanan'),
      KelurahanService.getMaklumatPelayanan(),
      KelurahanService.getSurveiSkm()
    ]);

    layananList.value = layananData || [];
    maklumat.value = maklumatData || null;
    skmLatest.value = skmData?.latest || null;
    
    if (katData && katData.length > 0) {
      kategoriList.value = katData.map(k => k.nama);
    } else {
      const distinct = [...new Set((layananData || []).map(l => l.kategori).filter(Boolean))];
      kategoriList.value = distinct;
    }
  } catch (err) {
    console.error('Gagal memuat layanan:', err);
  } finally {
    loading.value = false;
  }
});

const filteredLayanan = computed(() => {
  return layananList.value.filter(item => {
    const matchCategory = selectedKategori.value === 'Semua' || item.kategori === selectedKategori.value;
    const matchQuery = !searchQuery.value || 
      item.judul.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      (item.deskripsi && item.deskripsi.toLowerCase().includes(searchQuery.value.toLowerCase()));
    return matchCategory && matchQuery;
  });
});

function openDetailSOP(item) {
  activeSop.value = item;
}

function resetFilter() {
  selectedKategori.value = 'Semua';
  searchQuery.value = '';
}
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.98);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.rich-content :deep(p) {
  margin-bottom: 0.5rem;
}
.rich-content :deep(p:last-child) {
  margin-bottom: 0;
}
.rich-content :deep(ul) {
  list-style-type: disc;
  padding-left: 1.25rem;
  margin: 0.5rem 0;
}
.rich-content :deep(ol) {
  list-style-type: decimal;
  padding-left: 1.25rem;
  margin: 0.5rem 0;
}
.rich-content :deep(li) {
  margin-bottom: 0.25rem;
}
.rich-content :deep(a) {
  color: #047857;
  text-decoration: underline;
}
</style>
