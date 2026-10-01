<template>
  <header 
    class="sticky top-0 z-50 w-full transition-[box-shadow] duration-300"
    :class="{ 'shadow-lg': isScrolled }"
  >
    <!-- Top Bar Info Pemerintah -->
    <div 
      class="bg-emerald-950 text-emerald-100 text-xs px-4 border-emerald-900/60 transition-all duration-300 overflow-hidden"
      :class="isScrolled ? 'max-h-0 py-0 opacity-0 border-b-0 pointer-events-none' : 'max-h-16 py-2 opacity-100 border-b'"
    >
      <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2">
        <div class="flex items-center space-x-4">
          <span class="flex items-center gap-1.5 font-medium">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            Portal Resmi Pemerintah Kelurahan Kraksaan Wetan
          </span>
          <span class="hidden md:inline text-emerald-500">|</span>
          <span class="hidden md:inline text-emerald-200/80">Kecamatan Kraksaan, Kabupaten Probolinggo</span>
        </div>
        <div class="flex items-center gap-3 sm:gap-4 text-emerald-200/90 text-xs">
          <span class="hidden sm:inline">Senin - Jumat: 08.00 - 15.30 WIB</span>
          <a href="tel:0335841234" class="hover:text-amber-300 transition-colors flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            (0335) 841234
          </a>

          <!-- Sound Feedback Toggle -->
          <button 
            type="button"
            data-sound-toggle="true"
            @click="toggleSound"
            :title="isSoundEnabled ? 'Suara Navigasi: Aktif (Klik untuk matikan)' : 'Suara Navigasi: Nonaktif (Klik untuk bunyikan)'"
            class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold transition-all cursor-pointer border"
            :class="isSoundEnabled 
              ? 'bg-emerald-900 text-amber-300 border-emerald-700 hover:bg-emerald-800' 
              : 'bg-emerald-950 text-emerald-400/60 border-emerald-900 hover:text-emerald-300'"
          >
            <svg v-if="isSoundEnabled" class="w-3 h-3 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77zm-2.5-2.23l-5.5 5h-4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1h4l5.5 5c.66.6 1.5.15 1.5-.75v-18.5c0-.9-.84-1.35-1.5-.75zm9 11c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
            <svg v-else class="w-3 h-3 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51c.66-1.24 1.03-2.65 1.03-4.15 0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zm-15.5-8.5l1.27-1.27 18 18-1.27 1.27-5.18-5.18c-.58.43-1.23.77-1.93.98v-2.06c.39-.14.75-.34 1.07-.6l-4.46-4.46v5.82c0 .9-.84 1.35-1.5.75l-5.5-5h-4c-.55 0-1-.45-1-1v-8c0-.55.45-1 1-1h4l2.18-1.98-4.68-4.68zm8.5 2.7l-3.32 3.02 3.32 3.32v-6.34z"/></svg>
            <span>{{ isSoundEnabled ? 'Suara: On' : 'Suara: Off' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Main Header Brand & Identity -->
    <div 
      class="bg-white/95 backdrop-blur-md border-b border-slate-200 px-4 transition-all duration-300"
      :class="isScrolled ? 'py-2 shadow-xs' : 'py-3.5'"
    >
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <router-link to="/" data-speech="Kelurahan Kraksaan Wetan" class="flex items-center gap-3.5 group">
          <!-- Official Probolinggo Crest Badge / Custom Logo -->
          <div 
            class="flex-shrink-0 flex items-center justify-center transition-all duration-300"
            :class="isScrolled ? 'w-10 h-12 md:w-11 md:h-13' : 'w-12 h-14 md:w-14 md:h-16'"
          >
            <img 
              :src="profil?.logo || '/images/logo-probolinggo.png'" 
              @error="$event.target.src = '/images/logo-probolinggo.png'"
              :alt="profil?.nama || 'Logo Kelurahan Kraksaan Wetan'" 
              class="w-full h-full object-contain drop-shadow-sm transition-transform group-hover:scale-105" 
            />
          </div>
          
          <div>
            <div 
              class="text-[11px] md:text-xs font-semibold uppercase tracking-wider text-emerald-800 transition-all duration-300"
              :class="isScrolled ? 'hidden sm:block' : 'block'"
            >
              Pemerintah Kabupaten Probolinggo
            </div>
            <h1 
              class="font-bold tracking-tight text-slate-900 leading-tight transition-all duration-300"
              :class="isScrolled ? 'text-base md:text-xl' : 'text-lg md:text-2xl'"
            >
              Kelurahan Kraksaan Wetan
            </h1>
            <div 
              class="text-xs md:text-sm text-slate-500 font-medium transition-all duration-300"
              :class="isScrolled ? 'hidden sm:block text-[11px] md:text-xs' : 'text-xs md:text-sm'"
            >
              Kecamatan Kraksaan &bull; Pusat Pemerintahan & Pelayanan Warga
            </div>
          </div>
        </router-link>

        <div class="hidden lg:flex items-center gap-3">
          <router-link to="/pelayanan" data-speech="Panduan Layanan" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold hover:bg-emerald-100 transition shadow-xs">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Panduan Layanan
          </router-link>
          <router-link to="/kontak" data-speech="Aspirasi Warga" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-700 text-white text-xs font-semibold hover:bg-emerald-800 transition shadow-sm hover:shadow">
            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Aspirasi Warga
          </router-link>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <button 
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          data-speech="Buka Menu"
          class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-600 cursor-pointer"
          aria-label="Buka menu navigasi"
        >
          <svg v-if="!isMobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
          <svg v-else class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    </div>

    <!-- Desktop Navigation Bar -->
    <nav 
      class="hidden lg:block bg-emerald-800 text-white transition-all duration-300 border-t border-emerald-700"
      :class="isScrolled ? 'bg-emerald-900/95 backdrop-blur-md shadow-md' : 'bg-emerald-800 shadow-xs'"
    >
      <div class="max-w-7xl mx-auto px-4">
        <ul class="flex items-center space-x-1 font-medium text-sm">
          <!-- BERANDA -->
          <li>
            <router-link 
              to="/" 
              data-speech="Beranda"
              class="px-3.5 py-3 inline-block transition-colors border-b-2 hover:bg-emerald-700/60"
              :class="$route.path === '/' ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              BERANDA
            </router-link>
          </li>

          <!-- PROFIL (Dropdown) -->
          <li class="relative group" @mouseenter="activeDropdown = 'profil'" @mouseleave="activeDropdown = null">
            <button 
              data-speech="Profil"
              class="px-3.5 py-3 inline-flex items-center gap-1.5 transition-colors border-b-2 group-hover:bg-emerald-700/60 cursor-pointer"
              :class="$route.path.startsWith('/profil') ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              PROFIL
              <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div 
              v-show="activeDropdown === 'profil'"
              class="absolute left-0 top-full pt-1 w-64 transition-all z-50"
            >
              <div class="bg-white text-slate-800 shadow-2xl rounded-2xl border border-slate-200/80 p-1.5 space-y-0.5">
                <router-link to="/profil" data-speech="Tentang Kelurahan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Tentang Kelurahan</router-link>
                <router-link to="/profil/sejarah" data-speech="Sejarah Kelurahan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Sejarah Kelurahan</router-link>
                <router-link to="/profil/visi-misi" data-speech="Visi dan Misi" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Visi dan Misi</router-link>
                <router-link to="/profil/struktur-organisasi" data-speech="Struktur Organisasi" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Struktur Organisasi</router-link>
                <router-link to="/pemerintahan" data-speech="Profil Lurah dan Perangkat" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Profil Lurah & Perangkat</router-link>
                <router-link to="/informasi-publik" data-speech="Data Wilayah" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Data Wilayah</router-link>

                <!-- Sub-menu Tambahan dari Admin (Profil) -->
                <template v-if="customMenus.profil && customMenus.profil.length">
                  <template v-for="(sub, sIdx) in customMenus.profil" :key="'prof-sub-' + sIdx">
                    <a 
                      v-if="isExternalUrl(sub.url)" 
                      :href="sub.url" 
                      :target="sub.target || '_self'" 
                      :rel="sub.target === '_blank' ? 'noopener noreferrer' : undefined"
                      :data-speech="sub.label"
                      class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      <span>{{ sub.label }}</span>
                      <svg v-if="sub.target === '_blank'" class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    <router-link 
                      v-else 
                      :to="sub.url" 
                      :data-speech="sub.label" 
                      class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      {{ sub.label }}
                    </router-link>
                  </template>
                </template>
              </div>
            </div>
          </li>

          <!-- PEMERINTAHAN (Dropdown) -->
          <li class="relative group" @mouseenter="activeDropdown = 'pemerintahan'" @mouseleave="activeDropdown = null">
            <button 
              data-speech="Pemerintahan"
              class="px-3.5 py-3 inline-flex items-center gap-1.5 transition-colors border-b-2 group-hover:bg-emerald-700/60 cursor-pointer"
              :class="$route.path.startsWith('/pemerintahan') ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              PEMERINTAHAN
              <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div 
              v-show="activeDropdown === 'pemerintahan'"
              class="absolute left-0 top-full pt-1 w-64 transition-all z-50"
            >
              <div class="bg-white text-slate-800 shadow-2xl rounded-2xl border border-slate-200/80 p-1.5 space-y-0.5">
                <router-link to="/pemerintahan" data-speech="Pemerintahan Kelurahan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Pemerintahan Kelurahan</router-link>
                <router-link to="/profil/struktur-organisasi" data-speech="Struktur Organisasi" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Struktur Organisasi</router-link>
                <router-link to="/pemerintahan#perangkat" data-speech="Perangkat Kelurahan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Perangkat Kelurahan</router-link>
                <router-link to="/pemerintahan#rtrw" data-speech="Rukun Tetangga dan Rukun Warga" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Rukun Tetangga & RW (RT/RW)</router-link>
                <router-link to="/lembaga" data-speech="Lembaga Kemasyarakatan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Lembaga Kemasyarakatan (LKK)</router-link>

                <!-- Sub-menu Tambahan dari Admin (Pemerintahan) -->
                <template v-if="customMenus.pemerintahan && customMenus.pemerintahan.length">
                  <template v-for="(sub, sIdx) in customMenus.pemerintahan" :key="'pem-sub-' + sIdx">
                    <a 
                      v-if="isExternalUrl(sub.url)" 
                      :href="sub.url" 
                      :target="sub.target || '_self'" 
                      :rel="sub.target === '_blank' ? 'noopener noreferrer' : undefined"
                      :data-speech="sub.label"
                      class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      <span>{{ sub.label }}</span>
                      <svg v-if="sub.target === '_blank'" class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    <router-link 
                      v-else 
                      :to="sub.url" 
                      :data-speech="sub.label" 
                      class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      {{ sub.label }}
                    </router-link>
                  </template>
                </template>
              </div>
            </div>
          </li>

          <!-- INFORMASI PUBLIK (Dropdown) -->
          <li class="relative group" @mouseenter="activeDropdown = 'informasi'" @mouseleave="activeDropdown = null">
            <button 
              data-speech="Informasi Publik"
              class="px-3.5 py-3 inline-flex items-center gap-1.5 transition-colors border-b-2 group-hover:bg-emerald-700/60 cursor-pointer"
              :class="$route.path.startsWith('/informasi-publik') || $route.path.startsWith('/transparansi') || $route.path.startsWith('/pelayanan') || $route.path.startsWith('/survei-skm') || $route.path.startsWith('/agenda') ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              INFORMASI PUBLIK
              <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div 
              v-show="activeDropdown === 'informasi'"
              class="absolute left-0 top-full pt-1 w-64 transition-all z-50"
            >
              <div class="bg-white text-slate-800 shadow-2xl rounded-2xl border border-slate-200/80 p-1.5 space-y-0.5">
                <router-link to="/informasi-publik" data-speech="Data dan Statistik Kelurahan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Data & Statistik Kelurahan</router-link>
                <router-link to="/agenda" data-speech="Agenda Kegiatan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Agenda Kegiatan</router-link>
                <router-link to="/pelayanan" data-speech="Informasi Pelayanan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Informasi Pelayanan</router-link>
                <router-link to="/survei-skm" data-speech="Survei Kepuasan Masyarakat" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Survei Kepuasan Masyarakat (SKM)</router-link>
                <router-link to="/dokumen" data-speech="Dokumen" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Dokumen</router-link>
                <router-link to="/transparansi" data-speech="Transparansi dan Akuntabilitas" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Transparansi & Akuntabilitas</router-link>

                <!-- Sub-menu Tambahan dari Admin (Informasi Publik) -->
                <template v-if="customMenus.informasi && customMenus.informasi.length">
                  <template v-for="(sub, sIdx) in customMenus.informasi" :key="'info-sub-' + sIdx">
                    <a 
                      v-if="isExternalUrl(sub.url)" 
                      :href="sub.url" 
                      :target="sub.target || '_self'" 
                      :rel="sub.target === '_blank' ? 'noopener noreferrer' : undefined"
                      :data-speech="sub.label"
                      class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      <span>{{ sub.label }}</span>
                      <svg v-if="sub.target === '_blank'" class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    <router-link 
                      v-else 
                      :to="sub.url" 
                      :data-speech="sub.label" 
                      class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      {{ sub.label }}
                    </router-link>
                  </template>
                </template>
              </div>
            </div>
          </li>

          <!-- BERITA -->
          <li>
            <router-link 
              to="/berita" 
              data-speech="Berita dan Pengumuman"
              class="px-3.5 py-3 inline-block transition-colors border-b-2 hover:bg-emerald-700/60"
              :class="$route.path.startsWith('/berita') ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              BERITA & PENGUMUMAN
            </router-link>
          </li>

          <!-- GALERI -->
          <li>
            <router-link 
              to="/galeri" 
              data-speech="Galeri Dokumentasi"
              class="px-3.5 py-3 inline-flex items-center transition-colors border-b-2"
              :class="$route.path.startsWith('/galeri') 
                ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' 
                : 'border-transparent text-white/95 hover:text-white hover:bg-emerald-700/60'"
            >
              GALERI
            </router-link>
          </li>

          <!-- DOKUMEN (Dropdown) -->
          <li class="relative group" @mouseenter="activeDropdown = 'dokumen'" @mouseleave="activeDropdown = null">
            <button 
              data-speech="Dokumen"
              class="px-3.5 py-3 inline-flex items-center gap-1.5 transition-colors border-b-2 group-hover:bg-emerald-700/60 cursor-pointer"
              :class="$route.path.startsWith('/dokumen') ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              DOKUMEN
              <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div 
              v-show="activeDropdown === 'dokumen'"
              class="absolute left-0 top-full pt-1 w-64 transition-all z-50"
            >
              <div class="bg-white text-slate-800 shadow-2xl rounded-2xl border border-slate-200/80 p-1.5 space-y-0.5">
                <router-link to="/dokumen" data-speech="Semua Dokumen" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Semua Dokumen</router-link>
                <router-link to="/dokumen?kategori=Musrenbang" data-speech="Musrenbang" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Musrenbang</router-link>
                <router-link to="/dokumen?kategori=UMKM" data-speech="UMKM" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">UMKM</router-link>
                <router-link :to="{ path: '/dokumen', query: { kategori: 'Renstra & Renja' } }" data-speech="Renstra dan Renja" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Renstra & Renja</router-link>
                <router-link to="/dokumen?kategori=SK+Kelembagaan" data-speech="SK Kelembagaan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">SK Kelembagaan</router-link>
                <router-link :to="{ path: '/dokumen', query: { kategori: 'Regulasi & Kebijakan' } }" data-speech="Regulasi dan Kebijakan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Regulasi & Kebijakan</router-link>

                <!-- Sub-menu Tambahan dari Admin (Dokumen) jika ada -->
                <template v-if="customMenus.dokumen && customMenus.dokumen.length">
                  <template v-for="(sub, sIdx) in customMenus.dokumen" :key="'dok-sub-' + sIdx">
                    <a 
                      v-if="isExternalUrl(sub.url)" 
                      :href="sub.url" 
                      :target="sub.target || '_self'" 
                      :rel="sub.target === '_blank' ? 'noopener noreferrer' : undefined"
                      :data-speech="sub.label"
                      class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      <span>{{ sub.label }}</span>
                      <svg v-if="sub.target === '_blank'" class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                    <router-link 
                      v-else 
                      :to="sub.url" 
                      :data-speech="sub.label" 
                      class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors"
                    >
                      {{ sub.label }}
                    </router-link>
                  </template>
                </template>
              </div>
            </div>
          </li>

          <!-- KONTAK -->
          <li>
            <router-link 
              to="/kontak" 
              data-speech="Kontak dan Pengaduan"
              class="px-3.5 py-3 inline-block transition-colors border-b-2 hover:bg-emerald-700/60"
              :class="$route.path === '/kontak' ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              KONTAK
            </router-link>
          </li>

          <!-- SOUND TOGGLE & LOGIN -->
          <li class="ml-auto pl-2 py-1.5 flex items-center gap-2">
            <button
              type="button"
              data-sound-toggle="true"
              @click="toggleSound"
              :title="isSoundEnabled ? 'Suara Navigasi: Aktif (Klik untuk matikan)' : 'Suara Navigasi: Nonaktif (Klik untuk bunyikan)'"
              class="p-2 rounded-lg text-emerald-200 hover:text-white hover:bg-emerald-700/60 transition-all cursor-pointer flex items-center justify-center"
              aria-label="Pengaturan Efek Suara"
            >
              <svg v-if="isSoundEnabled" class="w-4 h-4 text-amber-300" fill="currentColor" viewBox="0 0 24 24"><path d="M14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77zm-2.5-2.23l-5.5 5h-4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1h4l5.5 5c.66.6 1.5.15 1.5-.75v-18.5c0-.9-.84-1.35-1.5-.75zm9 11c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
              <svg v-else class="w-4 h-4 text-white/50" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51c.66-1.24 1.03-2.65 1.03-4.15 0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zm-15.5-8.5l1.27-1.27 18 18-1.27 1.27-5.18-5.18c-.58.43-1.23.77-1.93.98v-2.06c.39-.14.75-.34 1.07-.6l-4.46-4.46v5.82c0 .9-.84 1.35-1.5.75l-5.5-5h-4c-.55 0-1-.45-1-1v-8c0-.55.45-1 1-1h4l2.18-1.98-4.68-4.68zm8.5 2.7l-3.32 3.02 3.32 3.32v-6.34z"/></svg>
            </button>

            <!-- LOGIN / ADMIN MENU -->
            <router-link 
              :to="isLoggedIn ? '/admin' : '/admin/login'" 
              data-no-sound="true"
              class="px-3.5 py-1.5 rounded-lg font-bold text-xs inline-flex items-center gap-1.5 transition shadow-sm hover:shadow"
              :class="isLoggedIn ? 'bg-emerald-700 hover:bg-emerald-600 text-amber-300 border border-emerald-600' : 'bg-amber-400 hover:bg-amber-300 text-slate-950 font-bold'"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
              </svg>
              <span>{{ isLoggedIn ? 'LOGIN' : 'LOGIN' }}</span>
            </router-link>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Mobile Floating Card Dropdown Menu (AMIK Taruna Style) -->
    <Transition
      enter-active-class="transition duration-200 ease-out"
      enter-from-class="transform -translate-y-3 opacity-0 scale-[0.98]"
      enter-to-class="transform translate-y-0 opacity-100 scale-100"
      leave-active-class="transition duration-150 ease-in"
      leave-from-class="transform translate-y-0 opacity-100 scale-100"
      leave-to-class="transform -translate-y-3 opacity-0 scale-[0.98]"
    >
      <div 
        v-if="isMobileMenuOpen"
        class="lg:hidden absolute top-full inset-x-0 px-3.5 py-2 max-h-[calc(100vh-75px)] overflow-y-auto z-50 pointer-events-auto"
      >
        <!-- Floating Card Container -->
        <div class="max-w-lg mx-auto bg-white/98 backdrop-blur-xl rounded-[22px] shadow-2xl border border-slate-200/90 p-4 sm:p-5 space-y-3">
          <!-- Mobile Menu Header / Status Badge -->
          <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span class="font-bold text-xs tracking-wider text-slate-800 uppercase">Navigasi Kelurahan</span>
            </div>
            
            <!-- Sound Toggle Button in Menu -->
            <button 
              type="button"
              data-sound-toggle="true"
              @click="toggleSound" 
              :title="isSoundEnabled ? 'Suara Navigasi: Aktif' : 'Suara Navigasi: Nonaktif'"
              class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold transition border cursor-pointer"
              :class="isSoundEnabled ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-slate-50 text-slate-500 border-slate-200'"
            >
              <svg v-if="isSoundEnabled" class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77zm-2.5-2.23l-5.5 5h-4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1h4l5.5 5c.66.6 1.5.15 1.5-.75v-18.5c0-.9-.84-1.35-1.5-.75zm9 11c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
              <svg v-else class="w-3.5 h-3.5 text-slate-400" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51c.66-1.24 1.03-2.65 1.03-4.15 0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zm-15.5-8.5l1.27-1.27 18 18-1.27 1.27-5.18-5.18c-.58.43-1.23.77-1.93.98v-2.06c.39-.14.75-.34 1.07-.6l-4.46-4.46v5.82c0 .9-.84 1.35-1.5.75l-5.5-5h-4c-.55 0-1-.45-1-1v-8c0-.55.45-1 1-1h4l2.18-1.98-4.68-4.68zm8.5 2.7l-3.32 3.02 3.32 3.32v-6.34z"/></svg>
              <span>{{ isSoundEnabled ? 'Suara On' : 'Suara Off' }}</span>
            </button>
          </div>

          <!-- Main Navigation Links List -->
          <div class="space-y-1 text-sm font-semibold text-slate-700">
            <!-- BERANDA -->
            <router-link 
              to="/" 
              data-speech="Beranda"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition"
              :class="$route.path === '/' ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
            >
              <span>Beranda</span>
              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </router-link>

            <!-- PROFIL (Accordion) -->
            <div>
              <button 
                @click="toggleMobileSubmenu('profil')" 
                data-speech="Menu Profil"
                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition cursor-pointer"
                :class="$route.path.startsWith('/profil') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
              >
                <span>Profil Kelurahan</span>
                <svg :class="{ 'rotate-180': mobileSubmenus.profil }" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div v-show="mobileSubmenus.profil" class="pl-3 pr-2 py-1.5 space-y-1 bg-slate-50/80 rounded-xl text-xs mt-1 border border-slate-100">
                <router-link to="/profil" data-speech="Tentang Kelurahan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Tentang Kelurahan</router-link>
                <router-link to="/profil/sejarah" data-speech="Sejarah Kelurahan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Sejarah Kelurahan</router-link>
                <router-link to="/profil/visi-misi" data-speech="Visi dan Misi" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Visi dan Misi</router-link>
                <router-link to="/profil/struktur-organisasi" data-speech="Struktur Organisasi" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Struktur Organisasi</router-link>
                <!-- Custom Submenus Profil -->
                <template v-for="(sub, sIdx) in (customMenus.profil || [])" :key="'mob-prof-' + sIdx">
                  <a 
                    v-if="isExternalUrl(sub.url)" 
                    :href="sub.url" 
                    :target="sub.target || '_self'" 
                    @click="isMobileMenuOpen = false" 
                    class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium"
                  >
                    <span>{{ sub.label }}</span>
                    <svg v-if="sub.target === '_blank'" class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                  <router-link 
                    v-else 
                    :to="sub.url" 
                    @click="isMobileMenuOpen = false" 
                    class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium"
                  >
                    {{ sub.label }}
                  </router-link>
                </template>
              </div>
            </div>

            <!-- PEMERINTAHAN (Accordion) -->
            <div>
              <button 
                @click="toggleMobileSubmenu('pemerintahan')" 
                data-speech="Menu Pemerintahan"
                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition cursor-pointer"
                :class="$route.path.startsWith('/pemerintahan') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
              >
                <span>Pemerintahan</span>
                <svg :class="{ 'rotate-180': mobileSubmenus.pemerintahan }" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div v-show="mobileSubmenus.pemerintahan" class="pl-3 pr-2 py-1.5 space-y-1 bg-slate-50/80 rounded-xl text-xs mt-1 border border-slate-100">
                <router-link to="/pemerintahan" data-speech="Pemerintahan Kelurahan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Pemerintahan Kelurahan</router-link>
                <router-link to="/pemerintahan#perangkat" data-speech="Perangkat Kelurahan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Perangkat Kelurahan</router-link>
                <router-link to="/pemerintahan#rtrw" data-speech="Rukun Tetangga dan Rukun Warga" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">RT / RW</router-link>
                <router-link to="/lembaga" data-speech="Lembaga Kemasyarakatan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Lembaga Kemasyarakatan (LKK)</router-link>
                <!-- Custom Submenus Pemerintahan -->
                <template v-for="(sub, sIdx) in (customMenus.pemerintahan || [])" :key="'mob-pem-' + sIdx">
                  <a 
                    v-if="isExternalUrl(sub.url)" 
                    :href="sub.url" 
                    :target="sub.target || '_self'" 
                    @click="isMobileMenuOpen = false" 
                    class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium"
                  >
                    <span>{{ sub.label }}</span>
                    <svg v-if="sub.target === '_blank'" class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                  <router-link 
                    v-else 
                    :to="sub.url" 
                    @click="isMobileMenuOpen = false" 
                    class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium"
                  >
                    {{ sub.label }}
                  </router-link>
                </template>
              </div>
            </div>

            <!-- INFORMASI PUBLIK (Accordion) -->
            <div>
              <button 
                @click="toggleMobileSubmenu('informasi')" 
                data-speech="Menu Informasi Publik"
                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition cursor-pointer"
                :class="$route.path.startsWith('/informasi-publik') || $route.path.startsWith('/transparansi') || $route.path.startsWith('/pelayanan') || $route.path.startsWith('/survei-skm') || $route.path.startsWith('/agenda') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
              >
                <span>Informasi Publik</span>
                <svg :class="{ 'rotate-180': mobileSubmenus.informasi }" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div v-show="mobileSubmenus.informasi" class="pl-3 pr-2 py-1.5 space-y-1 bg-slate-50/80 rounded-xl text-xs mt-1 border border-slate-100">
                <router-link to="/informasi-publik" data-speech="Data dan Statistik Kelurahan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Data & Statistik Kelurahan</router-link>
                <router-link to="/agenda" data-speech="Agenda Kegiatan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Agenda Kegiatan</router-link>
                <router-link to="/pelayanan" data-speech="Informasi Pelayanan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Informasi Pelayanan</router-link>
                <router-link to="/survei-skm" data-speech="Survei Kepuasan Masyarakat" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Survei Kepuasan Masyarakat (SKM)</router-link>
                <router-link to="/dokumen" data-speech="Dokumen" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Dokumen Publik</router-link>
                <router-link to="/transparansi" data-speech="Transparansi dan Akuntabilitas" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Transparansi & Akuntabilitas</router-link>
                <!-- Custom Submenus Informasi Publik -->
                <template v-for="(sub, sIdx) in (customMenus.informasi || [])" :key="'mob-info-' + sIdx">
                  <a 
                    v-if="isExternalUrl(sub.url)" 
                    :href="sub.url" 
                    :target="sub.target || '_self'" 
                    @click="isMobileMenuOpen = false" 
                    class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium"
                  >
                    <span>{{ sub.label }}</span>
                    <svg v-if="sub.target === '_blank'" class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                  <router-link 
                    v-else 
                    :to="sub.url" 
                    @click="isMobileMenuOpen = false" 
                    class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium"
                  >
                    {{ sub.label }}
                  </router-link>
                </template>
              </div>
            </div>

            <!-- BERITA & PENGUMUMAN -->
            <router-link 
              to="/berita" 
              data-speech="Berita dan Pengumuman"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition"
              :class="$route.path.startsWith('/berita') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
            >
              <span>Berita & Pengumuman</span>
              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </router-link>

            <!-- GALERI KEGIATAN -->
            <router-link 
              to="/galeri" 
              data-speech="Galeri Dokumentasi"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition"
              :class="$route.path.startsWith('/galeri') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
            >
              <span>Galeri Kegiatan</span>
              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </router-link>

            <!-- DOKUMEN (Accordion) -->
            <div>
              <button 
                @click="toggleMobileSubmenu('dokumen')" 
                data-speech="Menu Dokumen"
                class="w-full flex items-center justify-between px-3.5 py-2.5 rounded-xl transition cursor-pointer"
                :class="$route.path.startsWith('/dokumen') ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
              >
                <span>Dokumen Publik</span>
                <svg :class="{ 'rotate-180': mobileSubmenus.dokumen }" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
              </button>
              <div v-show="mobileSubmenus.dokumen" class="pl-3 pr-2 py-1.5 space-y-1 bg-slate-50/80 rounded-xl text-xs mt-1 border border-slate-100">
                <router-link to="/dokumen" data-speech="Semua Dokumen" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Semua Dokumen</router-link>
                <router-link to="/dokumen?kategori=Musrenbang" data-speech="Musrenbang" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Musrenbang</router-link>
                <router-link to="/dokumen?kategori=UMKM" data-speech="UMKM" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">UMKM</router-link>
                <router-link :to="{ path: '/dokumen', query: { kategori: 'Renstra & Renja' } }" data-speech="Renstra dan Renja" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Renstra & Renja</router-link>
                <router-link to="/dokumen?kategori=SK+Kelembagaan" data-speech="SK Kelembagaan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">SK Kelembagaan</router-link>
                <router-link :to="{ path: '/dokumen', query: { kategori: 'Regulasi & Kebijakan' } }" data-speech="Regulasi dan Kebijakan" @click="isMobileMenuOpen = false" class="block py-2 px-3 rounded-lg hover:bg-white hover:text-emerald-700 font-medium">Regulasi & Kebijakan</router-link>
              </div>
            </div>

            <!-- KONTAK & PENGADUAN -->
            <router-link 
              to="/kontak" 
              data-speech="Kontak dan Pengaduan"
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition"
              :class="$route.path === '/kontak' ? 'bg-emerald-50 text-emerald-800 font-bold' : 'hover:bg-slate-50 hover:text-emerald-700'"
            >
              <span>Kontak & Pengaduan</span>
              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </router-link>
          </div>

          <!-- Quick Action Buttons on Mobile Menu (AMIK Taruna Pill Style) -->
          <div class="pt-3 border-t border-slate-100 space-y-2">
            <div class="grid grid-cols-2 gap-2">
              <router-link 
                to="/pelayanan" 
                @click="isMobileMenuOpen = false"
                class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-bold transition shadow-2xs"
              >
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>Layanan</span>
              </router-link>
              <router-link 
                to="/kontak" 
                @click="isMobileMenuOpen = false"
                class="flex items-center justify-center gap-1.5 py-2.5 px-3 rounded-full bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition shadow-xs"
              >
                <svg class="w-3.5 h-3.5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Aspirasi</span>
              </router-link>
            </div>

            <!-- Login Admin Pill Button -->
            <router-link 
              :to="isLoggedIn ? '/admin' : '/admin/login'" 
              data-no-sound="true"
              @click="isMobileMenuOpen = false"
              class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-full font-bold text-xs transition shadow-xs"
              :class="isLoggedIn ? 'bg-emerald-800 text-amber-300 hover:bg-emerald-900 border border-emerald-700' : 'bg-amber-400 hover:bg-amber-300 text-slate-950'"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
              <span>{{ isLoggedIn ? 'Buka Panel Admin' : 'Login Administrator' }}</span>
            </router-link>
          </div>
        </div>

        <!-- Backdrop overlay -->
        <div 
          class="fixed inset-0 top-0 bg-slate-900/50 backdrop-blur-xs -z-10" 
          @click="isMobileMenuOpen = false"
        ></div>
      </div>
    </Transition>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { AdminService, KelurahanService } from '../services/api';
import { isSoundEnabled, toggleSound } from '../utils/sound';

const route = useRoute();
const isScrolled = ref(false);
const isMobileMenuOpen = ref(false);
const activeDropdown = ref(null);
const isLoggedIn = ref(false);
const profil = ref(null);
const mobileSubmenus = ref({
  profil: false,
  pemerintahan: false,
  informasi: false,
  dokumen: false,
  galeri: false
});

const customMenus = computed(() => {
  return profil.value?.custom_nav_menus || {
    profil: [],
    pemerintahan: [],
    informasi: [],
    dokumen: []
  };
});

const isExternalUrl = (url) => {
  if (!url) return false;
  return url.startsWith('http://') || url.startsWith('https://') || url.startsWith('//');
};

const checkAuth = () => {
  isLoggedIn.value = AdminService.isAuthenticated();
};

const loadProfil = async () => {
  try {
    profil.value = await KelurahanService.getProfil();
  } catch (e) {
    // fallback to default
  }
};

const toggleMobileSubmenu = (menu) => {
  mobileSubmenus.value[menu] = !mobileSubmenus.value[menu];
};

const handleScroll = () => {
  isScrolled.value = window.scrollY > 30;
};

const handleProfilUpdate = (e) => {
  if (e.detail) {
    profil.value = e.detail;
  } else {
    loadProfil();
  }
};

// Pantau perubahan rute untuk mengecek status auth, profil & tutup mobile menu
watch(() => route.path, () => {
  checkAuth();
  loadProfil();
  isMobileMenuOpen.value = false;
});

onMounted(() => {
  checkAuth();
  loadProfil();
  handleScroll();
  window.addEventListener('scroll', handleScroll, { passive: true });
  window.addEventListener('storage', checkAuth);
  window.addEventListener('profil-updated', handleProfilUpdate);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  window.removeEventListener('storage', checkAuth);
  window.removeEventListener('profil-updated', handleProfilUpdate);
});
</script>
