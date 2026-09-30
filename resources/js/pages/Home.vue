<template>
  <div class="space-y-16 sm:space-y-24 pb-16">
    <!-- 1. Hero Section -->
    <HeroSection :profil="profil" class="reveal-fade" />

    <!-- 2. Quick Service Section -->
    <section id="layanan-masyarakat" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 scroll-mt-24">
      <div class="text-center max-w-2xl mx-auto mb-12 reveal">
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
          v-for="(item, idx) in quickServices" 
          :key="item.id" 
          :layanan="item" 
          class="reveal"
          :class="`delay-${(idx + 1) * 100}`"
        />
      </div>

      <div class="text-center mt-8 reveal delay-300">
        <router-link 
          to="/pelayanan" 
          class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-900 group active:scale-95 transition-all duration-200"
        >
          <span>Lihat Seluruh 6+ Panduan Layanan & Persyaratan</span>
          <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </router-link>
      </div>
    </section>

    <!-- 2.5 Maklumat Pelayanan & Indeks Kepuasan Masyarakat (IKM) -->
    <!-- 2.5 Maklumat Pelayanan & Indeks Kepuasan Masyarakat (IKM) -->
    <section v-if="maklumat" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-[#022c22] via-[#064e3b] to-[#022c22] text-white p-6 sm:p-10 lg:p-12 shadow-2xl border-2 border-emerald-600/40 ring-1 ring-amber-400/20 reveal">
        <!-- Ambient Glow & Watermark -->
        <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-20 -top-20 w-80 h-80 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        
        <!-- Subtle Seal Watermark Background -->
        <div class="absolute right-6 top-6 opacity-5 pointer-events-none hidden md:block">
          <svg class="w-64 h-64 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 items-center relative z-10">
          <!-- Text Content -->
          <div class="lg:col-span-8 space-y-5">
            <div class="flex flex-wrap items-center gap-2.5">
              <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-900/90 border border-amber-400/50 text-amber-300 text-xs font-bold uppercase tracking-wider shadow-sm">
                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
                Maklumat Pelayanan Publik
              </span>
              <span v-if="maklumat.nomor_sk" class="px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-xs text-emerald-200 text-[11px] sm:text-xs font-semibold border border-white/10 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>SK: {{ maklumat.nomor_sk }}</span>
              </span>
            </div>

            <div>
              <h3 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-sm">
                {{ maklumat.judul || 'Maklumat Pelayanan Kelurahan Kraksaan Wetan' }}
              </h3>
            </div>

            <!-- Motto Pelayanan -->
            <div v-if="maklumat.motto" class="inline-flex items-center gap-2.5 px-4 py-2 rounded-2xl bg-amber-400/15 border border-amber-400/30 text-amber-200 text-xs sm:text-sm font-semibold shadow-inner">
              <svg class="w-4 h-4 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
              <span>Motto: "{{ maklumat.motto }}"</span>
            </div>

            <!-- Pernyataan Ikrar Komitmen Pelayanan -->
            <div class="relative bg-slate-950/40 border border-emerald-600/40 rounded-2xl p-5 sm:p-6 backdrop-blur-xs shadow-inner space-y-3">
              <div class="flex items-start gap-3">
                <span class="text-3xl sm:text-4xl text-amber-400/50 font-serif leading-none select-none">“</span>
                <p class="text-xs sm:text-sm text-emerald-50 leading-relaxed font-normal whitespace-pre-line break-words flex-1">
                  {{ maklumat.konten }}
                </p>
              </div>

              <!-- 4 Pilar Nilai Pelayanan -->
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 pt-4 border-t border-emerald-800/60 text-[11px]">
                <div class="flex items-center gap-1.5 text-emerald-200 font-medium">
                  <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Bebas Pungli (Gratis)</span>
                </div>
                <div class="flex items-center gap-1.5 text-emerald-200 font-medium">
                  <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Transparan & Jelas</span>
                </div>
                <div class="flex items-center gap-1.5 text-emerald-200 font-medium">
                  <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Tepat & Pasti</span>
                </div>
                <div class="flex items-center gap-1.5 text-emerald-200 font-medium">
                  <svg class="w-3.5 h-3.5 text-amber-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                  <span>Sopan & Ramah</span>
                </div>
              </div>
            </div>

            <!-- Action buttons & SKM badge -->
            <div class="pt-2 flex flex-wrap items-center gap-3">
              <router-link
                to="/pelayanan"
                class="px-5 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold text-xs sm:text-sm transition flex items-center gap-2 shadow-lg hover:shadow-amber-400/20 active:scale-95"
              >
                <span>Lihat Standar SOP Pelayanan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </router-link>

              <router-link
                to="/survei-skm"
                class="px-5 py-2.5 rounded-xl bg-emerald-800/90 hover:bg-emerald-700 text-white font-bold text-xs sm:text-sm transition flex items-center gap-2 border border-emerald-500/60 shadow-xs active:scale-95"
              >
                <span v-if="skmLatest">IKM {{ skmLatest.skor_ikm }} ({{ skmLatest.predikat }})</span>
                <span v-else>Survei Kepuasan (SKM)</span>
                <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>

              <button
                v-if="maklumat.gambar"
                type="button"
                @click="showMaklumatModal = true"
                class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition flex items-center gap-2 border border-white/15 cursor-pointer shadow-xs active:scale-95"
              >
                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Buka Piagam HD</span>
              </button>
            </div>
          </div>

          <!-- Framed Certificate / Poster Column -->
          <div class="lg:col-span-4 flex flex-col items-center justify-center">
            <!-- Framed Poster with Full Unclipped Presentation -->
            <div 
              v-if="maklumat.gambar"
              @click="showMaklumatModal = true"
              class="group relative w-full max-w-[280px] sm:max-w-xs bg-slate-950/80 rounded-2xl p-2.5 border-2 border-amber-400/50 shadow-2xl hover:border-amber-300 transition-all duration-300 cursor-pointer transform hover:-translate-y-1.5"
              title="Klik untuk memperbesar piagam maklumat"
            >
              <div class="w-full aspect-[3/4] sm:aspect-[4/5] bg-black/40 rounded-xl overflow-hidden flex items-center justify-center relative">
                <img 
                  :src="maklumat.gambar" 
                  :alt="maklumat.judul" 
                  class="w-full h-full object-contain rounded-lg group-hover:scale-105 transition-transform duration-500"
                  loading="lazy"
                />
                <!-- Hover Overlay -->
                <div class="absolute inset-0 bg-slate-950/65 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center gap-2 text-white text-xs font-bold p-3 text-center backdrop-blur-2xs">
                  <div class="w-10 h-10 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                  </div>
                  <span>Perbesar Piagam HD</span>
                </div>
              </div>
              <p class="text-[10px] text-center text-amber-200/80 mt-2 font-medium flex items-center justify-center gap-1">
                <svg class="w-3 h-3 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <span>Klik untuk melihat piagam penuh</span>
              </p>
            </div>

            <!-- Fallback Plakat Lambang jika belum unggah poster gambar -->
            <div v-else class="w-full max-w-[280px] p-6 rounded-2xl border-2 border-amber-400/40 bg-gradient-to-b from-emerald-900/60 to-slate-950/80 text-center space-y-3.5 shadow-xl">
              <div class="w-16 h-16 mx-auto rounded-full bg-amber-400/10 border-2 border-amber-400/60 flex items-center justify-center shadow-inner">
                <svg class="w-8 h-8 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              </div>
              <div>
                <p class="text-xs font-bold text-amber-300 uppercase tracking-widest">Piagam Integritas</p>
                <p class="text-[11px] text-emerald-200/90 mt-1 leading-relaxed">Komitmen resmi aparatur Kelurahan Kraksaan Wetan memberikan pelayanan prima, transparan, dan tanpa diskriminasi.</p>
              </div>
              <div class="pt-2 border-t border-white/10 text-[10px] text-amber-200/80">
                Pemerintah Kelurahan Kraksaan Wetan
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Modal Preview Poster Maklumat HD -->
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

    <!-- 3. Profil Singkat Kelurahan -->
    <section id="profil-kelurahan" class="bg-gradient-to-b from-white to-slate-100 py-16 border-y border-slate-200 scroll-mt-24">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
          <!-- Gambar & Sambutan Lurah -->
          <div class="lg:col-span-5 relative group reveal-left">
            <div class="relative mx-auto max-w-sm rounded-3xl overflow-hidden shadow-2xl border-4 border-white bg-slate-200">
              <img 
                :src="profil.lurah?.foto || 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=600&q=80'" 
                :alt="profil.lurah?.nama"
                class="w-full h-96 object-cover object-top group-hover:scale-105 transition-transform duration-700 ease-out"
              />
              <div class="absolute bottom-0 inset-x-0 p-5 bg-gradient-to-t from-emerald-950 via-emerald-900/90 to-transparent text-white">
                <p class="text-xs font-semibold text-amber-400 uppercase tracking-wider">{{ profil.lurah?.jabatan || 'Lurah Kraksaan Wetan' }}</p>
                <h4 class="text-base font-bold">{{ profil.lurah?.nama }}</h4>
                <p class="text-[11px] text-emerald-200">NIP. {{ profil.lurah?.nip }}</p>
              </div>
            </div>
          </div>

          <!-- Teks Sambutan -->
          <div class="lg:col-span-7 space-y-6 reveal-right">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider">
              <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
              Pemerintahan Kelurahan
            </div>
            
            <div class="space-y-2">
              <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Membangun Bersama Warga, Melayani dengan Sepenuh Hati
              </h2>
              <p class="text-sm font-semibold text-emerald-700">
                Sambutan {{ profil.lurah?.jabatan || 'Lurah Kraksaan Wetan' }}
              </p>
            </div>

            <p v-if="profil.deskripsi" class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              {{ profil.deskripsi }}
            </p>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic border-l-4 border-emerald-600 pl-4 py-1 bg-emerald-50/50 rounded-r-xl">
              "{{ profil.sambutan_lurah || profil.lurah?.sambutan || 'Selamat datang di website resmi Kelurahan Kraksaan Wetan. Media ini kami dedikasikan sebagai wujud transparansi, keterbukaan informasi publik, dan percepatan pelayanan administrasi bagi seluruh warga masyarakat tercinta.' }}"
            </p>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-2">
              <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 transform hover:-translate-y-0.5 reveal delay-100">
                <p class="text-[11px] text-slate-400 font-semibold uppercase">Kecamatan</p>
                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ profil.kecamatan || 'Kraksaan' }}</p>
              </div>
              <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 transform hover:-translate-y-0.5 reveal delay-200">
                <p class="text-[11px] text-slate-400 font-semibold uppercase">Kabupaten</p>
                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ profil.kabupaten || 'Probolinggo' }}</p>
              </div>
              <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all duration-300 transform hover:-translate-y-0.5 col-span-2 sm:col-span-1 reveal delay-300">
                <p class="text-[11px] text-slate-400 font-semibold uppercase">Provinsi</p>
                <p class="text-sm font-bold text-slate-800 mt-0.5">{{ profil.provinsi || 'Jawa Timur' }}</p>
              </div>
            </div>

            <div class="pt-2">
              <router-link 
                to="/profil" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white text-xs sm:text-sm font-bold shadow-md transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer group"
              >
                <span>Selengkapnya Tentang Kami</span>
                <svg class="w-4 h-4 transform group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- 4. Statistik Kelurahan (Animated Counter) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-10 reveal">
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
          :subtext="statistik.laki_laki && statistik.perempuan ? `L: ${Number(statistik.laki_laki).toLocaleString('id-ID')} | P: ${Number(statistik.perempuan).toLocaleString('id-ID')}` : 'L: 3.390 | P: 3.452'"
          class="reveal delay-75"
        />
        <StatisticCard 
          label="Kepala Keluarga" 
          :value="statistik.kk || 2185" 
          unit="KK" 
          :subtext="statistik.rw ? `Tersebar di ${statistik.rw} RW` : 'Tersebar di 7 RW'"
          class="reveal delay-150"
        />
        <StatisticCard 
          label="Rukun Warga (RW)" 
          :value="statistik.rw || 7" 
          unit="RW" 
          subtext="Lingkungan Wilayah"
          class="reveal delay-200"
        />
        <StatisticCard 
          label="Rukun Tetangga (RT)" 
          :value="statistik.rt || 22" 
          unit="RT" 
          subtext="Pelayanan Lingkungan"
          class="reveal delay-250"
        />
        <StatisticCard 
          label="Luas Wilayah" 
          :value="statistik.luas_wilayah || '1.84'" 
          unit="km²" 
          :subtext="statistik.kepadatan ? `Kepadatan: ${statistik.kepadatan}` : 'Kepadatan 3.718/km²'"
          class="col-span-2 sm:col-span-1 reveal delay-300"
        />
      </div>
    </section>

    <!-- 5. Berita Terbaru -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 reveal">
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
          class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-900 group active:scale-95 transition-all duration-200 self-start sm:self-auto"
        >
          <span>Lihat Semua Berita</span>
          <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </router-link>
      </div>

      <LoadingSpinner v-if="loading.berita" text="Memuat berita terbaru..." />
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <NewsCard 
          v-for="(b, idx) in beritaList.slice(0, 3)" 
          :key="b.id" 
          :berita="b" 
          class="reveal"
          :class="`delay-${(idx + 1) * 100}`"
        />
      </div>
    </section>

    <!-- 6. Pengumuman Resmi Kelurahan -->
    <section class="bg-amber-50/60 py-16 border-y border-amber-200/70">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8 reveal">
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
            v-for="(item, idx) in pengumumanList.slice(0, 3)" 
            :key="item.id" 
            :pengumuman="item" 
            class="reveal"
            :class="`delay-${(idx + 1) * 100}`"
          />
        </div>
      </div>
    </section>

    <!-- 7. Galeri Dokumentasi Kegiatan -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-10 reveal">
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
          class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-900 group active:scale-95 transition-all duration-200 self-start sm:self-auto"
        >
          <span>Buka Galeri Lengkap</span>
          <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </router-link>
      </div>

      <LoadingSpinner v-if="loading.galeri" text="Memuat foto kegiatan..." />
      <GalleryGrid v-else :items="galeriList.slice(0, 6)" :show-filter="false" class="reveal delay-100" />
    </section>

    <!-- 8. Lokasi Kelurahan Kraksaan Wetan -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-10 reveal">
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

      <LocationMap class="reveal-scale delay-100" />
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
const maklumat = ref(null);
const skmLatest = ref(null);
const showMaklumatModal = ref(false);

onMounted(async () => {
  try {
    const [p, s, l, b, pg, g, mData, sData] = await Promise.all([
      KelurahanService.getProfil(),
      KelurahanService.getStatistik(),
      KelurahanService.getLayanan(),
      KelurahanService.getBerita(),
      KelurahanService.getPengumuman(),
      KelurahanService.getGaleri(),
      KelurahanService.getMaklumatPelayanan().catch(() => null),
      KelurahanService.getSurveiSkm().catch(() => null)
    ]);

    profil.value = p;
    statistik.value = s;
    quickServices.value = l.slice(0, 4);
    beritaList.value = b;
    pengumumanList.value = pg;
    galeriList.value = g;
    maklumat.value = mData;
    if (sData) {
      skmLatest.value = sData.skm_terbaru || (sData.arsip && sData.arsip[0]) || null;
    }
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
