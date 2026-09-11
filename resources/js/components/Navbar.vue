<template>
  <header class="w-full z-50 transition-all duration-300">
    <!-- Top Bar Info Pemerintah -->
    <div class="bg-emerald-950 text-emerald-100 text-xs py-2 px-4 border-b border-emerald-900/60">
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
        </div>
      </div>
    </div>

    <!-- Main Header Brand & Identity -->
    <div class="bg-white border-b border-slate-200 py-3.5 px-4">
      <div class="max-w-7xl mx-auto flex items-center justify-between">
        <router-link to="/" class="flex items-center gap-3.5 group">
          <!-- Official Probolinggo Crest Badge / Custom Logo -->
          <div class="w-12 h-14 md:w-14 md:h-16 flex-shrink-0 flex items-center justify-center">
            <img 
              :src="profil?.logo || '/images/logo-probolinggo.png'" 
              :alt="profil?.nama || 'Logo Kelurahan Kraksaan Wetan'" 
              class="w-full h-full object-contain drop-shadow-sm transition-transform group-hover:scale-105" 
            />
          </div>
          
          <div>
            <div class="text-[11px] md:text-xs font-semibold uppercase tracking-wider text-emerald-800">
              Pemerintah Kabupaten Probolinggo
            </div>
            <h1 class="text-lg md:text-2xl font-bold tracking-tight text-slate-900 leading-tight">
              Kelurahan Kraksaan Wetan
            </h1>
            <div class="text-xs md:text-sm text-slate-500 font-medium">
              Kecamatan Kraksaan &bull; Pusat Pemerintahan & Pelayanan Warga
            </div>
          </div>
        </router-link>

        <div class="hidden lg:flex items-center gap-3">
          <router-link to="/pelayanan" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold hover:bg-emerald-100 transition shadow-xs">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Panduan Layanan
          </router-link>
          <router-link to="/kontak" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-700 text-white text-xs font-semibold hover:bg-emerald-800 transition shadow-sm hover:shadow">
            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Aspirasi Warga
          </router-link>
        </div>

        <!-- Mobile Menu Toggle Button -->
        <button 
          @click="isMobileMenuOpen = !isMobileMenuOpen"
          class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-emerald-700 hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-emerald-600"
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

    <!-- Sticky Desktop Navigation Bar -->
    <nav 
      class="hidden lg:block bg-emerald-800 text-white shadow-md sticky top-0 z-40 transition-all duration-200 border-t border-emerald-700"
      :class="{ 'shadow-lg bg-emerald-900/95 backdrop-blur-md': isScrolled }"
    >
      <div class="max-w-7xl mx-auto px-4">
        <ul class="flex items-center space-x-1 font-medium text-sm">
          <!-- BERANDA -->
          <li>
            <router-link 
              to="/" 
              class="px-3.5 py-3 inline-block transition-colors border-b-2 hover:bg-emerald-700/60"
              :class="$route.path === '/' ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              BERANDA
            </router-link>
          </li>

          <!-- PROFIL (Dropdown) -->
          <li class="relative group" @mouseenter="activeDropdown = 'profil'" @mouseleave="activeDropdown = null">
            <button 
              class="px-3.5 py-3 inline-flex items-center gap-1.5 transition-colors border-b-2 group-hover:bg-emerald-700/60"
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
                <router-link to="/profil" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Tentang Kelurahan</router-link>
                <router-link to="/profil/sejarah" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Sejarah Kelurahan</router-link>
                <router-link to="/profil/visi-misi" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Visi dan Misi</router-link>
                <router-link to="/profil/struktur-organisasi" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Struktur Organisasi</router-link>
                <router-link to="/pemerintahan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Profil Lurah & Perangkat</router-link>
                <router-link to="/informasi-publik" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Data Wilayah</router-link>
              </div>
            </div>
          </li>

          <!-- PEMERINTAHAN (Dropdown) -->
          <li class="relative group" @mouseenter="activeDropdown = 'pemerintahan'" @mouseleave="activeDropdown = null">
            <button 
              class="px-3.5 py-3 inline-flex items-center gap-1.5 transition-colors border-b-2 group-hover:bg-emerald-700/60"
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
                <router-link to="/pemerintahan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Pemerintahan Kelurahan</router-link>
                <router-link to="/profil/struktur-organisasi" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Struktur Organisasi</router-link>
                <router-link to="/pemerintahan#perangkat" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Perangkat Kelurahan</router-link>
                <router-link to="/pemerintahan#rtrw" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Rukun Tetangga & RW (RT/RW)</router-link>
                <router-link to="/lembaga" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Lembaga Kemasyarakatan (LKK)</router-link>
              </div>
            </div>
          </li>

          <!-- INFORMASI PUBLIK (Dropdown) -->
          <li class="relative group" @mouseenter="activeDropdown = 'informasi'" @mouseleave="activeDropdown = null">
            <button 
              class="px-3.5 py-3 inline-flex items-center gap-1.5 transition-colors border-b-2 group-hover:bg-emerald-700/60"
              :class="$route.path.startsWith('/informasi-publik') || $route.path.startsWith('/transparansi') ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              INFORMASI PUBLIK
              <svg class="w-4 h-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div 
              v-show="activeDropdown === 'informasi'"
              class="absolute left-0 top-full pt-1 w-64 transition-all z-50"
            >
              <div class="bg-white text-slate-800 shadow-2xl rounded-2xl border border-slate-200/80 p-1.5 space-y-0.5">
                <router-link to="/informasi-publik" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Data Kependudukan</router-link>
                <router-link to="/informasi-publik#wilayah" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Data Wilayah & Lingkungan</router-link>
                <router-link to="/informasi-publik#statistik" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Data Statistik Kelurahan</router-link>
                <router-link to="/pelayanan" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Informasi Pelayanan</router-link>
                <router-link to="/transparansi" class="block px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-800 transition-colors">Transparansi & Akuntabilitas</router-link>
              </div>
            </div>
          </li>

          <!-- BERITA -->
          <li>
            <router-link 
              to="/berita" 
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
    class="px-3.5 py-3 inline-flex items-center transition-colors border-b-2"
    :class="$route.path.startsWith('/galeri') 
      ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' 
      : 'border-transparent text-white/95 hover:text-white hover:bg-emerald-700/60'"
  >
    GALERI
  </router-link>
</li>

          <!-- PELAYANAN -->
          <li>
            <router-link 
              to="/pelayanan" 
              class="px-3.5 py-3 inline-block transition-colors border-b-2 hover:bg-emerald-700/60"
              :class="$route.path === '/pelayanan' ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              PELAYANAN
            </router-link>
          </li>

          <!-- KONTAK -->
          <li>
            <router-link 
              to="/kontak" 
              class="px-3.5 py-3 inline-block transition-colors border-b-2 hover:bg-emerald-700/60"
              :class="$route.path === '/kontak' ? 'border-amber-400 text-amber-300 font-semibold bg-emerald-700/50' : 'border-transparent text-white/95 hover:text-white'"
            >
              KONTAK
            </router-link>
          </li>

          <!-- LOGIN / ADMIN MENU -->
          <li class="ml-auto pl-2 py-1.5 flex items-center">
            <router-link 
              :to="isLoggedIn ? '/admin' : '/admin/login'" 
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

    <!-- Mobile Drawer Navigation (Backdrop + Accordion) -->
    <div 
      v-if="isMobileMenuOpen" 
      class="fixed inset-0 z-50 lg:hidden flex"
    >
      <!-- Backdrop -->
      <div 
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" 
        @click="isMobileMenuOpen = false"
      ></div>

      <!-- Drawer Content Panel -->
      <div class="relative ml-auto w-4/5 max-w-sm bg-white h-full shadow-2xl flex flex-col overflow-y-auto">
        <!-- Drawer Header -->
        <div class="p-4 bg-emerald-800 text-white flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
            <span class="font-bold text-sm tracking-wide">MENU KELURAHAN</span>
          </div>
          <button @click="isMobileMenuOpen = false" class="p-1 rounded-md text-emerald-200 hover:text-white hover:bg-emerald-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Drawer Accordion Navigation -->
        <div class="p-4 space-y-1 text-sm font-medium text-slate-700">
          <router-link 
            to="/" 
            @click="isMobileMenuOpen = false"
            class="block px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800 font-semibold"
          >
            Beranda
          </router-link>

          <!-- Accordion: Profil -->
          <div>
            <button 
              @click="toggleMobileSubmenu('profil')" 
              class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800"
            >
              <span>Profil</span>
              <svg :class="{ 'rotate-180': mobileSubmenus.profil }" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div v-show="mobileSubmenus.profil" class="pl-4 pr-2 py-1 space-y-1 bg-slate-50 rounded-lg text-xs">
              <router-link to="/profil" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Tentang Kelurahan</router-link>
              <router-link to="/profil/sejarah" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Sejarah Kelurahan</router-link>
              <router-link to="/profil/visi-misi" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Visi dan Misi</router-link>
              <router-link to="/profil/struktur-organisasi" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Struktur Organisasi</router-link>
            </div>
          </div>

          <!-- Accordion: Pemerintahan -->
          <div>
            <button 
              @click="toggleMobileSubmenu('pemerintahan')" 
              class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800"
            >
              <span>Pemerintahan</span>
              <svg :class="{ 'rotate-180': mobileSubmenus.pemerintahan }" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div v-show="mobileSubmenus.pemerintahan" class="pl-4 pr-2 py-1 space-y-1 bg-slate-50 rounded-lg text-xs">
              <router-link to="/pemerintahan" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Pemerintahan Kelurahan</router-link>
              <router-link to="/pemerintahan#perangkat" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Perangkat Kelurahan</router-link>
              <router-link to="/pemerintahan#rtrw" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">RT / RW</router-link>
              <router-link to="/lembaga" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Lembaga Kemasyarakatan</router-link>
            </div>
          </div>

          <!-- Accordion: Informasi Publik -->
          <div>
            <button 
              @click="toggleMobileSubmenu('informasi')" 
              class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800"
            >
              <span>Informasi Publik</span>
              <svg :class="{ 'rotate-180': mobileSubmenus.informasi }" class="w-4 h-4 transition-transform text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div v-show="mobileSubmenus.informasi" class="pl-4 pr-2 py-1 space-y-1 bg-slate-50 rounded-lg text-xs">
              <router-link to="/informasi-publik" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Data Kependudukan</router-link>
              <router-link to="/informasi-publik#wilayah" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Data Wilayah</router-link>
              <router-link to="/informasi-publik#statistik" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Data Statistik</router-link>
              <router-link to="/transparansi" @click="isMobileMenuOpen = false" class="block py-2 px-2 hover:text-emerald-700">Transparansi & Akuntabilitas</router-link>
            </div>
          </div>

          <router-link 
            to="/berita" 
            @click="isMobileMenuOpen = false"
            class="block px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800"
          >
            Berita & Pengumuman
          </router-link>

          <router-link 
            to="/galeri" 
            @click="isMobileMenuOpen = false"
            class="block px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800"
          >
            Galeri Kegiatan
          </router-link>

          <router-link 
            to="/pelayanan" 
            @click="isMobileMenuOpen = false"
            class="block px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800"
          >
            Pelayanan Masyarakat
          </router-link>

          <router-link 
            to="/kontak" 
            @click="isMobileMenuOpen = false"
            class="block px-3 py-2.5 rounded-lg hover:bg-emerald-50 hover:text-emerald-800"
          >
            Kontak & Pengaduan
          </router-link>

          <!-- Mobile Drawer Login / Admin Item -->
          <div class="pt-2 border-t border-slate-100 mt-2">
            <router-link 
              :to="isLoggedIn ? '/admin' : '/admin/login'" 
              @click="isMobileMenuOpen = false"
              class="flex items-center justify-between px-3 py-2.5 rounded-xl font-bold transition"
              :class="isLoggedIn ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-950 border border-amber-200'"
            >
              <div class="flex items-center gap-2 text-xs">
                <svg class="w-4 h-4" :class="isLoggedIn ? 'text-emerald-600' : 'text-amber-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                <span>{{ isLoggedIn ? 'Panel Admin Kelurahan' : 'Login Administrator' }}</span>
              </div>
              <span 
                class="text-[10px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider" 
                :class="isLoggedIn ? 'bg-emerald-200/80 text-emerald-900' : 'bg-amber-300 text-amber-950'"
              >
                {{ isLoggedIn ? 'Aktif' : 'Masuk' }} &rsaquo;
              </span>
            </router-link>
          </div>
        </div>

        <!-- Drawer Quick Actions -->
        <div class="mt-auto p-4 border-t border-slate-200 bg-slate-50 text-xs text-slate-500">
          <p class="font-semibold text-slate-700 mb-1">Kelurahan Kraksaan Wetan</p>
          <p class="mb-3">Kec. Kraksaan, Kab. Probolinggo</p>
          <a 
            href="tel:0335841234" 
            class="w-full flex items-center justify-center gap-2 py-2 px-4 rounded-lg bg-emerald-700 text-white font-medium hover:bg-emerald-800"
          >
            Hubungi Kantor
          </a>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import { AdminService, KelurahanService } from '../services/api';

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
  galeri: false
});

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
  isScrolled.value = window.scrollY > 80;
};

const handleProfilUpdate = (e) => {
  if (e.detail) {
    profil.value = e.detail;
  } else {
    loadProfil();
  }
};

// Pantau perubahan rute untuk mengecek status auth & profil
watch(() => route.path, () => {
  checkAuth();
  loadProfil();
});

onMounted(() => {
  checkAuth();
  loadProfil();
  window.addEventListener('scroll', handleScroll);
  window.addEventListener('storage', checkAuth);
  window.addEventListener('profil-updated', handleProfilUpdate);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
  window.removeEventListener('storage', checkAuth);
  window.removeEventListener('profil-updated', handleProfilUpdate);
});
</script>
