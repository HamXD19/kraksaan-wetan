<template>
  <div class="space-y-6 pb-12">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 flex items-center gap-2.5">
          <span class="p-2 rounded-xl bg-purple-100 text-purple-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </span>
          <span>Setting System</span>
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Pusat konfigurasi identitas visual (Logo & Hero Banner) dan pembuat sub-menu dropdown beserta halamannya.
        </p>
      </div>

      <!-- Tab Navigasi Pengaturan -->
      <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl self-start sm:self-auto">
        <button 
          type="button" 
          @click="activeTab = 'visual'" 
          class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5"
          :class="activeTab === 'visual' ? 'bg-white text-emerald-800 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Logo & Hero Banner
        </button>
        <button 
          type="button" 
          @click="activeTab = 'menu'" 
          class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5"
          :class="activeTab === 'menu' ? 'bg-white text-emerald-800 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
          Sub-Menu & Halaman
        </button>
        <button 
          type="button" 
          @click="switchToStorageTab" 
          class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition flex items-center gap-1.5"
          :class="activeTab === 'storage' ? 'bg-white text-emerald-800 shadow-xs' : 'text-slate-600 hover:text-slate-900'"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/></svg>
          Penyimpanan & Berkas
        </button>
      </div>
    </div>

    <!-- Alert Notifikasi -->
    <div v-if="alert.show" class="p-4 rounded-2xl text-xs font-semibold flex items-center justify-between transition-all" :class="alert.type === 'success' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200'">
      <span>{{ alert.message }}</span>
      <button @click="alert.show = false" class="text-slate-400 hover:text-slate-600 font-bold ml-4">&times;</button>
    </div>

    <!-- TAB 1: IDENTITAS VISUAL (LOGO & HERO BANNER) -->
    <div v-show="activeTab === 'visual'" class="space-y-6">
      <form @submit.prevent="saveVisualSettings" class="space-y-6">
        <!-- 1. PENGATURAN LOGO KELURAHAN -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-5">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
            <div>
              <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                <span>Logo Kelurahan / Pemkab</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Navbar & Footer</span>
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">Logo ini muncul pada bagian atas (navbar utama), logo cetak dokumen, dan footer website.</p>
            </div>
            <span class="text-[10px] px-2.5 py-1 rounded-md bg-slate-100 font-semibold text-slate-700 self-start sm:self-auto">
              {{ formVisual.logo ? 'Logo Kustom Terpasang' : 'Logo Bawaan Aktif' }}
            </span>
          </div>

          <div class="flex flex-col sm:flex-row items-center gap-6">
            <!-- Logo Preview Box -->
            <div class="w-24 h-28 sm:w-28 sm:h-32 rounded-2xl border-2 border-dashed border-slate-200 bg-slate-50 p-2.5 flex items-center justify-center flex-shrink-0 shadow-xs">
              <img v-if="previewLogo || formVisual.logo" :src="previewLogo || formVisual.logo" alt="Logo Kelurahan" class="w-full h-full object-contain drop-shadow-xs" />
              <div v-else class="w-full h-full flex flex-col items-center justify-center text-center">
                <svg viewBox="0 0 80 96" class="w-12 h-14" fill="none">
                  <path d="M40 2L76 18V50C76 72 40 94 40 94C40 94 4 72 4 50V18L40 2Z" fill="#047857" stroke="#f59e0b" stroke-width="3"/>
                  <path d="M22 56L40 32L58 56H22Z" fill="#f8fafc"/>
                  <circle cx="40" cy="26" r="5" fill="#f59e0b"/>
                </svg>
                <span class="text-[9px] font-bold text-slate-400 mt-1">Default</span>
              </div>
            </div>

            <!-- Upload Controls -->
            <div class="flex-1 w-full space-y-3">
              <div class="flex flex-col sm:flex-row gap-2">
                <input 
                  type="text" 
                  v-model="formVisual.logo" 
                  placeholder="Masukkan URL Logo atau pilih Unggah File..." 
                  class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                />
                <label class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1.5 shadow-xs transition whitespace-nowrap">
                  <span v-if="uploadingLogo">Mengunggah...</span>
                  <span v-else class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Pilih & Unggah Logo
                  </span>
                  <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleLogoUpload" :disabled="uploadingLogo" />
                </label>
                <button 
                  v-if="formVisual.logo" 
                  type="button" 
                  @click="formVisual.logo = ''" 
                  class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl text-xs transition whitespace-nowrap"
                >
                  Reset Bawaan
                </button>
              </div>
              <p class="text-[11px] text-slate-500">Format yang didukung: PNG transparan, SVG, atau JPG/WebP (Maks. 10MB). Disarankan format PNG dengan rasio vertikal/persegi seimbang.</p>
            </div>
          </div>
        </div>

        <!-- 2. PENGATURAN FOTO LATAR HERO BANNER BERANDA -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-5">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-100">
            <div>
              <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                <span>Foto Latar Hero Banner Beranda & Header Halaman</span>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Latar Utama</span>
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">Foto lanskap beresolusi tinggi sebagai latar belakang visual hero di beranda dan halaman informasi.</p>
            </div>
            <span class="text-[10px] px-2.5 py-1 rounded-md bg-slate-100 font-semibold text-slate-700 self-start sm:self-auto">
              {{ formVisual.hero_image ? 'Foto Kustom Terpasang' : 'Foto Bawaan (Gunung Bromo)' }}
            </span>
          </div>

          <!-- Banner Preview Box -->
          <div class="relative w-full h-40 sm:h-56 rounded-2xl overflow-hidden border border-slate-200 bg-emerald-950 shadow-inner group">
            <img 
              :src="previewHero || formVisual.hero_image || '/images/hero-bromo-vector.jpg'" 
              alt="Pratinjau Hero Banner" 
              class="w-full h-full object-cover object-[center_35%] transition-transform duration-300 group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/90 via-emerald-950/40 to-black/20"></div>
            <div class="absolute bottom-4 left-5 right-5 flex items-center justify-between text-white text-xs">
              <span class="font-bold drop-shadow-sm flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Pratinjau Tampilan Hero Banner Beranda
              </span>
              <span class="text-[10px] text-emerald-200 bg-emerald-900/80 px-2.5 py-1 rounded-lg backdrop-blur-xs font-bold">
                {{ previewHero || formVisual.hero_image ? 'Kustom' : 'Default' }}
              </span>
            </div>
          </div>

          <!-- Input Controls & Upload -->
          <div class="space-y-2">
            <div class="flex flex-col sm:flex-row gap-2">
              <input 
                type="text" 
                v-model="formVisual.hero_image" 
                placeholder="Masukkan URL Foto Banner atau pilih Unggah Foto..." 
                class="flex-1 px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
              />
              <label class="px-4 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1.5 shadow-xs transition whitespace-nowrap">
                <span v-if="uploadingHero">Mengunggah...</span>
                <span v-else class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  Pilih & Unggah Foto Hero
                </span>
                <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleHeroUpload" :disabled="uploadingHero" />
              </label>
              <button 
                v-if="formVisual.hero_image" 
                type="button" 
                @click="formVisual.hero_image = ''" 
                class="px-3.5 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl text-xs transition whitespace-nowrap"
              >
                Reset Bawaan
              </button>
            </div>
            <p class="text-[11px] text-slate-500">Format yang didukung: JPG, PNG, WebP, SVG (Disarankan rasio lanskap 16:9 resolusi tinggi, Maks. 10MB).</p>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2">
          <span v-if="uploadingLogo || uploadingHero" class="text-xs text-amber-700 font-semibold animate-pulse flex items-center gap-1.5">
            <svg class="w-4 h-4 animate-spin text-amber-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            Sedang mengunggah berkas gambar ke server...
          </span>
          <button 
            type="submit" 
            :disabled="savingVisual || uploadingLogo || uploadingHero" 
            class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold text-xs shadow-xs transition flex items-center gap-2 cursor-pointer"
          >
            <svg v-if="savingVisual" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ savingVisual ? 'Menyimpan Pengaturan...' : 'Simpan Logo & Hero Banner' }}</span>
          </button>
        </div>
      </form>
    </div>

    <!-- TAB 2: KELOLA SUB-MENU DROPDOWN & PEMBUAT HALAMAN -->
    <div v-show="activeTab === 'menu'" class="space-y-6">
      <!-- Tombol Tambah Halaman / Sub-Menu Baru -->
      <div class="bg-gradient-to-r from-emerald-900 to-emerald-950 p-6 rounded-3xl text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
        <div class="space-y-1">
          <h3 class="text-base font-bold flex items-center gap-2">
            <span>Pembuat Sub-Menu & Halaman Web Dinamis</span>
            <span class="px-2 py-0.5 rounded-full bg-amber-400 text-slate-950 text-[10px] font-black">Fitur Baru</span>
          </h3>
          <p class="text-xs text-emerald-200">
            Setiap sub-menu yang dibuat akan otomatis memiliki halaman detail lengkap dengan judul, foto cover, dan teks paragraf/rich content.
          </p>
        </div>
        <button 
          type="button" 
          @click="openCreateModal" 
          class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-xs transition flex items-center gap-1.5 self-start sm:self-auto"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <span>Buat Sub-Menu & Halaman Baru</span>
        </button>
      </div>

      <!-- Filter Kategori & Pencarian -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 shadow-xs">
        <div class="flex items-center gap-2 w-full sm:w-auto">
          <span class="text-xs font-bold text-slate-600 whitespace-nowrap">Kategori Dropdown:</span>
          <select v-model="filterKategori" class="px-3 py-1.5 rounded-xl border border-slate-200 text-xs bg-slate-50 outline-none focus:ring-2 focus:ring-emerald-600">
            <option value="Semua">Semua Dropdown</option>
            <option value="profil">Dropdown Profil</option>
            <option value="pemerintahan">Dropdown Pemerintahan</option>
            <option value="informasi">Dropdown Informasi Publik</option>
          </select>
        </div>

        <div class="relative w-full sm:w-64">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Cari judul halaman..." 
            class="w-full pl-9 pr-3.5 py-1.5 rounded-xl border border-slate-200 text-xs bg-slate-50 outline-none focus:ring-2 focus:ring-emerald-600"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="loadingHalaman" class="p-8 text-center bg-white rounded-3xl border border-slate-200">
        <p class="text-xs text-slate-500 font-semibold animate-pulse">Memuat daftar halaman dan sub-menu...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredHalamanList.length === 0" class="p-12 text-center bg-white rounded-3xl border border-slate-200 space-y-3">
        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto text-xl font-bold">
          &equiv;
        </div>
        <h4 class="text-sm font-bold text-slate-800">Belum Ada Halaman Sub-Menu Kustom</h4>
        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
          Tambahkan sub-menu baru untuk dropdown navbar beserta halaman penjelasannya dengan mengklik tombol di bawah.
        </p>
        <button 
          type="button" 
          @click="openCreateModal" 
          class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs transition inline-flex items-center gap-1.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Tambah Sub-Menu Sekarang
        </button>
      </div>

      <!-- Table / Card List of Pages -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div 
          v-for="item in filteredHalamanList" 
          :key="item.id" 
          class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between group"
        >
          <div>
            <!-- Cover Image Box -->
            <div class="relative h-36 bg-slate-100 overflow-hidden border-b border-slate-100">
              <img 
                v-if="item.gambar" 
                :src="item.gambar" 
                :alt="item.judul"
                class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
              />
              <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400 bg-slate-50">
                <svg class="w-8 h-8 text-slate-300 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span class="text-[10px] font-semibold">Tanpa Gambar Cover</span>
              </div>

              <!-- Badge Kategori Dropdown -->
              <div class="absolute top-2.5 left-2.5">
                <span 
                  class="px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider backdrop-blur-md shadow-xs"
                  :class="getCategoryBadgeClass(item.kategori)"
                >
                  Dropdown: {{ item.kategori }}
                </span>
              </div>

              <!-- Badge Status Aktif -->
              <div class="absolute top-2.5 right-2.5">
                <span 
                  class="px-2 py-0.5 rounded-md text-[10px] font-bold shadow-xs"
                  :class="item.aktif ? 'bg-emerald-800/90 text-emerald-200' : 'bg-slate-700/90 text-slate-300'"
                >
                  {{ item.aktif ? 'Aktif' : 'Draft / Nonaktif' }}
                </span>
              </div>
            </div>

            <!-- Page Title & Excerpt -->
            <div class="p-4 space-y-2">
              <h4 class="font-bold text-slate-900 text-sm line-clamp-2 leading-snug group-hover:text-emerald-700 transition">
                {{ item.judul }}
              </h4>
              <p class="text-[11px] text-slate-500 line-clamp-2">
                {{ item.ringkasan || 'Belum ada ringkasan deskripsi singkat.' }}
              </p>
              <div class="pt-1 flex items-center gap-2 text-[11px] font-mono text-emerald-800">
                <span class="px-2 py-0.5 rounded bg-emerald-50 border border-emerald-100 truncate">
                  /halaman/{{ item.slug }}
                </span>
              </div>
            </div>
          </div>

          <!-- Bottom Action Buttons -->
          <div class="p-4 pt-0 border-t border-slate-50 flex items-center justify-between gap-2 mt-2">
            <a 
              :href="`/halaman/${item.slug}`" 
              target="_blank" 
              class="px-2.5 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 text-[11px] font-bold transition flex items-center gap-1"
              title="Buka Halaman Publik di Tab Baru"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              <span>Lihat Halaman</span>
            </a>

            <div class="flex items-center gap-1.5">
              <button 
                type="button" 
                @click="openEditModal(item)" 
                class="px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-[11px] font-bold transition flex items-center gap-1"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit</span>
              </button>
              <button 
                type="button" 
                @click="confirmDeleteHalaman(item)" 
                class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 text-[11px] font-bold transition"
                title="Hapus Halaman & Sub-Menu Ini"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL FORM INPUT/EDIT SUB-MENU & HALAMAN -->
    <div v-if="modal.show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs overflow-y-auto">
      <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] flex flex-col shadow-2xl border border-slate-200 overflow-hidden my-auto">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
          <div>
            <h3 class="font-bold text-slate-900 text-sm sm:text-base flex items-center gap-2">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
              <span>{{ modal.isEdit ? 'Edit Sub-Menu & Halaman' : 'Tambah Sub-Menu & Konfigurasi Halaman' }}</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">Tentukan label menu, letak dropdown, dan isi konten halaman.</p>
          </div>
          <button @click="closeModal" class="p-2 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Modal Body Form (Scrollable) -->
        <form @submit.prevent="submitHalamanForm" class="flex-1 overflow-y-auto p-6 space-y-5 text-xs">
          <!-- Pilihan Kategori Dropdown Navbar -->
          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Letak Menu Dropdown Navbar *</label>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
              <label 
                class="p-3 rounded-xl border cursor-pointer flex items-center gap-2.5 transition"
                :class="pageForm.kategori === 'profil' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-900 font-bold shadow-xs' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
              >
                <input type="radio" v-model="pageForm.kategori" value="profil" class="text-emerald-600 focus:ring-emerald-500" />
                <span>Dropdown Profil</span>
              </label>

              <label 
                class="p-3 rounded-xl border cursor-pointer flex items-center gap-2.5 transition"
                :class="pageForm.kategori === 'pemerintahan' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-900 font-bold shadow-xs' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
              >
                <input type="radio" v-model="pageForm.kategori" value="pemerintahan" class="text-emerald-600 focus:ring-emerald-500" />
                <span>Dropdown Pemerintahan</span>
              </label>

              <label 
                class="p-3 rounded-xl border cursor-pointer flex items-center gap-2.5 transition"
                :class="pageForm.kategori === 'informasi' ? 'border-emerald-600 bg-emerald-50/70 text-emerald-900 font-bold shadow-xs' : 'border-slate-200 hover:bg-slate-50 text-slate-700'"
              >
                <input type="radio" v-model="pageForm.kategori" value="informasi" class="text-emerald-600 focus:ring-emerald-500" />
                <span>Dropdown Informasi</span>
              </label>
            </div>
            <p class="text-[11px] text-slate-400 mt-1">Sub-menu akan otomatis muncul di dropdown navigasi navbar yang dipilih.</p>
          </div>

          <!-- Judul Sub-Menu & Judul Halaman -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Nama Sub-Menu / Judul Halaman *</label>
              <input 
                type="text" 
                v-model="pageForm.judul" 
                required 
                placeholder="Contoh: Prestasi & Penghargaan Kelurahan" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                @input="generateSlug"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Slug URL (/halaman/:slug) *</label>
              <input 
                type="text" 
                v-model="pageForm.slug" 
                required 
                placeholder="prestasi-kelurahan" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs font-mono text-emerald-800"
              />
            </div>
          </div>

          <!-- Ringkasan Singkat -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Ringkasan Singkat (Opsional)</label>
            <input 
              type="text" 
              v-model="pageForm.ringkasan" 
              placeholder="Ringkasan 1-2 kalimat pengantar untuk pembaca..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
            />
          </div>

          <!-- Upload Gambar / Foto Cover Halaman -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
            <label class="block font-bold text-slate-700">Foto Cover / Gambar Utama Halaman</label>
            
            <div class="flex flex-col sm:flex-row items-center gap-4">
              <!-- Pratinjau Foto -->
              <div class="w-28 h-20 rounded-xl border border-slate-200 bg-white overflow-hidden flex items-center justify-center shrink-0 shadow-xs">
                <img v-if="pageForm.gambar" :src="pageForm.gambar" alt="Cover" class="w-full h-full object-cover" />
                <span v-else class="text-[10px] text-slate-400 text-center px-1">Tanpa Foto</span>
              </div>

              <!-- Input Kontrol & Tombol Upload -->
              <div class="flex-1 w-full space-y-2">
                <div class="flex flex-col sm:flex-row gap-2">
                  <input 
                    type="text" 
                    v-model="pageForm.gambar" 
                    placeholder="URL gambar atau unggah berkas foto..." 
                    class="flex-1 px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs outline-none focus:ring-1 focus:ring-emerald-600" 
                  />
                  <label class="px-3.5 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1.5 transition shadow-xs whitespace-nowrap">
                    <span v-if="uploadingPageImg">Mengunggah...</span>
                    <span v-else class="flex items-center gap-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                      Pilih Foto
                    </span>
                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="handlePageImgUpload" :disabled="uploadingPageImg" />
                  </label>
                  <button 
                    v-if="pageForm.gambar" 
                    type="button" 
                    @click="pageForm.gambar = ''" 
                    class="px-2.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl text-xs transition"
                  >
                    Hapus
                  </button>
                </div>
                <p class="text-[10px] text-slate-400">Format foto: JPG, PNG, WebP (Maks. 10MB). Akan ditampilkan di atas artikel halaman.</p>
              </div>
            </div>
          </div>

          <!-- Input Konten Paragraf (RichTextEditor) -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block font-bold text-slate-700">Konten Paragraf & Isi Halaman *</label>
              <span class="text-[11px] text-emerald-700 font-medium">Mendukung format heading, list, gambar, dan tebal/miring</span>
            </div>
            <RichTextEditor 
              v-model="pageForm.konten" 
              placeholder="Tuliskan isi informasi lengkap untuk halaman ini (paragraf, poin penting, tabel atau deskripsi)..." 
            />
          </div>

          <!-- Opsi Tambahan: Status Aktif & Urutan -->
          <div class="flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-slate-100">
            <label class="flex items-center gap-2 cursor-pointer">
              <input type="checkbox" v-model="pageForm.aktif" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4" />
              <span class="font-bold text-slate-700">Publikasikan & Tampilkan di Navbar</span>
            </label>

            <div class="flex items-center gap-2">
              <span class="font-bold text-slate-600">Urutan Tampil:</span>
              <input 
                type="number" 
                v-model.number="pageForm.urutan" 
                class="w-20 px-2.5 py-1.5 rounded-lg border border-slate-200 text-xs text-center" 
                min="0"
              />
            </div>
          </div>

          <!-- Modal Footer Actions -->
          <div class="pt-4 flex items-center justify-end gap-2.5 border-t border-slate-100">
            <button 
              type="button" 
              @click="closeModal" 
              class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-bold transition"
            >
              Batal
            </button>
            <button 
              type="submit" 
              :disabled="savingPage" 
              class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold transition flex items-center gap-1.5 shadow-xs"
            >
              <span v-if="savingPage">Menyimpan...</span>
              <span v-else>{{ modal.isEdit ? 'Simpan Perubahan' : 'Terbitkan Halaman' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- TAB 3: PENYIMPANAN & MANAJEMEN BERKAS (ORPHANED FILES) -->
    <div v-show="activeTab === 'storage'" class="space-y-6">
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-5 border-b border-slate-100">
          <div>
            <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2.5">
              <span>Manajemen Penyimpanan & Berkas Terhapus</span>
              <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Disk Public</span>
            </h3>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl">
              Setiap kali Anda menghapus berita, galeri, pengumuman, aparatur, atau dokumen, sistem otomatis menghapus berkas fisik aslinya dari folder penyimpanan. Halaman ini juga mendeteksi dan membersihkan jika terdapat berkas sisa/orphaned yang tidak lagi digunakan di database.
            </p>
          </div>
          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="loadStorageStats"
              :disabled="loadingStorage"
              class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 font-bold text-xs transition flex items-center gap-1.5 shadow-xs disabled:opacity-50"
            >
              <svg class="w-4 h-4" :class="{ 'animate-spin': loadingStorage }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
              <span>{{ loadingStorage ? 'Memindai...' : 'Pindai Ulang' }}</span>
            </button>
            <button
              v-if="storageStats.orphaned_files_count > 0"
              type="button"
              @click="handleCleanStorage"
              :disabled="cleaningStorage"
              class="px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs transition flex items-center gap-1.5 shadow-xs disabled:opacity-50"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              <span>{{ cleaningStorage ? 'Membersihkan...' : 'Bersihkan Berkas Sampah' }}</span>
            </button>
          </div>
        </div>

        <!-- Metric Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
          <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4.5">
            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Berkas di Disk</div>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-black text-slate-900">{{ storageStats.total_disk_files }}</span>
              <span class="text-xs font-semibold text-slate-500">berkas</span>
            </div>
            <div class="text-[11px] text-slate-500 mt-1">Ukuran fisik: <span class="font-bold text-slate-700">{{ storageStats.total_disk_size_formatted || '0 B' }}</span></div>
          </div>

          <div class="bg-emerald-50/60 border border-emerald-200/80 rounded-2xl p-4.5">
            <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider">Berkas Aktif Terhubung DB</div>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-black text-emerald-950">{{ storageStats.active_db_files_count }}</span>
              <span class="text-xs font-semibold text-emerald-700">berkas terdaftar</span>
            </div>
            <div class="text-[11px] text-emerald-700 mt-1">Berita, galeri, pengumuman, dokumen & profil</div>
          </div>

          <div :class="storageStats.orphaned_files_count > 0 ? 'bg-amber-50/70 border-amber-200' : 'bg-slate-50 border-slate-200/80'" class="border rounded-2xl p-4.5">
            <div class="text-[11px] font-bold uppercase tracking-wider" :class="storageStats.orphaned_files_count > 0 ? 'text-amber-800' : 'text-slate-500'">Berkas Sampah / Yatim</div>
            <div class="mt-2 flex items-baseline gap-2">
              <span class="text-2xl font-black" :class="storageStats.orphaned_files_count > 0 ? 'text-amber-950' : 'text-slate-900'">{{ storageStats.orphaned_files_count }}</span>
              <span class="text-xs font-semibold" :class="storageStats.orphaned_files_count > 0 ? 'text-amber-700' : 'text-slate-500'">berkas tidak terpakai</span>
            </div>
            <div class="text-[11px] mt-1" :class="storageStats.orphaned_files_count > 0 ? 'text-amber-700 font-bold' : 'text-slate-500'">
              {{ storageStats.orphaned_files_count > 0 ? `Dapat dibebaskan: ${storageStats.orphaned_size_formatted}` : 'Tidak ada berkas tertinggal' }}
            </div>
          </div>
        </div>

        <!-- Status Banner -->
        <div v-if="storageStats.orphaned_files_count === 0 && !loadingStorage" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
          <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          </div>
          <div>
            <h4 class="text-xs font-bold text-emerald-900">Folder Penyimpanan Bersih & Sinkron</h4>
            <p class="text-[11px] text-emerald-700 mt-0.5">Semua berkas pada folder storage kelurahan memiliki keterikatan langsung dengan data di database. Tidak ada sisa berkas sampah terabaikan.</p>
          </div>
        </div>

        <!-- Orphaned Files Table if any -->
        <div v-if="storageStats.orphaned_files && storageStats.orphaned_files.length > 0" class="space-y-3">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-slate-900 flex items-center gap-2">
              <span>Daftar Berkas Sampah Yang Ditemukan</span>
              <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold">{{ storageStats.orphaned_files.length }}</span>
            </h4>
            <span class="text-[11px] text-slate-500">Total ukuran: <strong class="text-slate-700">{{ storageStats.orphaned_size_formatted }}</strong></span>
          </div>

          <div class="border border-slate-200 rounded-2xl overflow-hidden shadow-2xs">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4">Nama / Path Berkas</th>
                  <th class="py-3 px-4 w-32">Ukuran</th>
                  <th class="py-3 px-4 w-44">Terakhir Diubah</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="file in storageStats.orphaned_files" :key="file.path" class="hover:bg-slate-50/80 transition">
                  <td class="py-2.5 px-4 font-mono text-[11px] text-slate-800 break-all">{{ file.path }}</td>
                  <td class="py-2.5 px-4 text-slate-600 font-medium whitespace-nowrap">{{ file.size_formatted }}</td>
                  <td class="py-2.5 px-4 text-slate-500 whitespace-nowrap">{{ file.last_modified }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { KelurahanService, AdminService } from '../../services/api';
import RichTextEditor from '../../components/RichTextEditor.vue';
import { useToast } from '../../composables/useToast';

const toast = useToast();
const activeTab = ref('visual');
const uploadingLogo = ref(false);
const uploadingHero = ref(false);
const savingVisual = ref(false);
const previewLogo = ref('');
const previewHero = ref('');

const alert = ref({
  show: false,
  type: 'success',
  message: ''
});

const formVisual = ref({
  logo: '',
  hero_image: ''
});

// State Tab Sub-menu & Halaman
const halamanList = ref([]);
const loadingHalaman = ref(false);
const filterKategori = ref('Semua');
const searchQuery = ref('');

const modal = ref({
  show: false,
  isEdit: false,
  targetId: null
});

const pageForm = ref({
  judul: '',
  slug: '',
  kategori: 'profil',
  ringkasan: '',
  gambar: '',
  konten: '',
  aktif: true,
  urutan: 0
});

const uploadingPageImg = ref(false);
const savingPage = ref(false);

const showAlert = (message, type = 'success') => {
  alert.value = { show: true, type, message };
  setTimeout(() => {
    alert.value.show = false;
  }, 4000);
};

const getCategoryBadgeClass = (kategori) => {
  if (kategori === 'profil') return 'bg-emerald-800 text-emerald-200';
  if (kategori === 'pemerintahan') return 'bg-blue-800 text-blue-200';
  if (kategori === 'informasi') return 'bg-purple-800 text-purple-200';
  return 'bg-slate-800 text-slate-200';
};

const filteredHalamanList = computed(() => {
  return halamanList.value.filter(item => {
    const matchCat = filterKategori.value === 'Semua' || item.kategori === filterKategori.value;
    const s = searchQuery.value.toLowerCase().trim();
    const matchSearch = !s || item.judul.toLowerCase().includes(s) || (item.ringkasan && item.ringkasan.toLowerCase().includes(s));
    return matchCat && matchSearch;
  });
});

const generateSlug = () => {
  if (!modal.value.isEdit || !pageForm.value.slug) {
    pageForm.value.slug = pageForm.value.judul
      .toLowerCase()
      .trim()
      .replace(/[^\w\s-]/g, '')
      .replace(/[\s_-]+/g, '-')
      .replace(/^-+|-+$/g, '');
  }
};

// 1. Visual Settings Functions
const loadProfilVisual = async () => {
  try {
    const data = await KelurahanService.getProfil();
    if (data) {
      formVisual.value.logo = data.logo || '';
      formVisual.value.hero_image = data.hero_image || '';
    }
  } catch (err) {
    console.error('Gagal mengambil data visual profil:', err);
  }
};

const handleLogoUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran berkas logo terlalu besar (maksimal 10MB)', 'File Terlalu Besar');
    showAlert('Ukuran berkas logo terlalu besar (maksimal 10MB)', 'error');
    event.target.value = '';
    return;
  }

  // Tampilkan pratinjau instan seketika di peramban menggunakan FileReader
  const reader = new FileReader();
  reader.onload = (e) => {
    const dataUrl = e.target.result;
    previewLogo.value = dataUrl;
    formVisual.value.logo = dataUrl;
  };
  reader.readAsDataURL(file);

  uploadingLogo.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      formVisual.value.logo = uploadedUrl;
      const msg = 'Logo baru berhasil diunggah! Klik tombol "Simpan Logo & Hero Banner" di bawah untuk menyimpan perubahan.';
      toast.success(msg, 'Logo Diunggah');
      showAlert(msg);
    }
  } catch (err) {
    // JANGAN hapus previewLogo agar pratinjau tetap tampak di mata pengguna
    const errText = err.response?.data?.message || err.message || 'Gagal mengunggah berkas';
    toast.warning('Pratinjau logo lokal siap disimpan: ' + errText, 'Pratinjau Lokal');
  } finally {
    uploadingLogo.value = false;
    event.target.value = '';
  }
};

const handleHeroUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran berkas hero banner terlalu besar (maksimal 10MB)', 'File Terlalu Besar');
    showAlert('Ukuran berkas hero banner terlalu besar (maksimal 10MB)', 'error');
    event.target.value = '';
    return;
  }

  // Tampilkan pratinjau instan seketika di peramban menggunakan FileReader
  const reader = new FileReader();
  reader.onload = (e) => {
    const dataUrl = e.target.result;
    previewHero.value = dataUrl;
    formVisual.value.hero_image = dataUrl;
  };
  reader.readAsDataURL(file);

  uploadingHero.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      formVisual.value.hero_image = uploadedUrl;
      const msg = 'Foto latar hero banner berhasil diunggah! Klik tombol "Simpan Logo & Hero Banner" di bawah untuk menyimpan perubahan.';
      toast.success(msg, 'Hero Banner Diunggah');
      showAlert(msg);
    }
  } catch (err) {
    // JANGAN hapus previewHero agar pratinjau tetap tampak di mata pengguna
    const errText = err.response?.data?.message || err.message || 'Gagal mengunggah berkas';
    toast.warning('Pratinjau hero lokal siap disimpan: ' + errText, 'Pratinjau Lokal');
  } finally {
    uploadingHero.value = false;
    event.target.value = '';
  }
};

const saveVisualSettings = async () => {
  if (savingVisual.value) return;
  if (uploadingLogo.value || uploadingHero.value) {
    toast.warning('Sedang mengunggah berkas gambar, mohon tunggu sebentar...', 'Mohon Tunggu');
    return;
  }
  savingVisual.value = true;
  try {
    const res = await AdminService.updateProfil({
      logo: formVisual.value.logo,
      hero_image: formVisual.value.hero_image
    });
    const msg = res?.message || 'Pengaturan Logo dan Hero Banner berhasil disimpan!';
    toast.success(msg, 'Pengaturan Disimpan');
    showAlert(msg, 'success');
    if (res?.data?.logo) formVisual.value.logo = res.data.logo;
    if (res?.data?.hero_image) formVisual.value.hero_image = res.data.hero_image;
    previewLogo.value = '';
    previewHero.value = '';
    // Kirim custom event agar navbar dan komponen lain langsung terupdate secara reaktif
    window.dispatchEvent(new CustomEvent('profil-updated', { detail: res?.data }));
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Terjadi kesalahan sistem';
    toast.error('Gagal menyimpan pengaturan visual: ' + errText, 'Simpan Gagal');
    showAlert('Gagal menyimpan pengaturan visual: ' + errText, 'error');
  } finally {
    savingVisual.value = false;
  }
};

// 2. Custom Pages / Sub-menus CRUD
const loadHalamanList = async () => {
  loadingHalaman.value = true;
  try {
    const res = await AdminService.getHalamanKustom();
    const fetched = res?.data || [];
    if (Array.isArray(fetched)) {
      const existingNew = halamanList.value.filter(item => !fetched.some(h => h.id === item.id));
      halamanList.value = [...existingNew, ...fetched];
    } else {
      halamanList.value = fetched;
    }
  } catch (err) {
    console.error('Gagal mengambil daftar halaman kustom:', err);
  } finally {
    loadingHalaman.value = false;
  }
};

const openCreateModal = () => {
  modal.value = {
    show: true,
    isEdit: false,
    targetId: null
  };
  pageForm.value = {
    judul: '',
    slug: '',
    kategori: 'profil',
    ringkasan: '',
    gambar: '',
    konten: '',
    aktif: true,
    urutan: halamanList.value.length
  };
};

const openEditModal = (item) => {
  modal.value = {
    show: true,
    isEdit: true,
    targetId: item.id
  };
  pageForm.value = {
    judul: item.judul || '',
    slug: item.slug || '',
    kategori: item.kategori || 'profil',
    ringkasan: item.ringkasan || '',
    gambar: item.gambar || '',
    konten: item.konten || '',
    aktif: item.aktif !== undefined ? Boolean(item.aktif) : true,
    urutan: item.urutan || 0
  };
};

const closeModal = () => {
  modal.value.show = false;
};

const handlePageImgUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran berkas cover terlalu besar (maksimal 10MB)', 'File Terlalu Besar');
    showAlert('Ukuran berkas cover terlalu besar (maksimal 10MB)', 'error');
    event.target.value = '';
    return;
  }

  // Pratinjau instan seketika di peramban
  const reader = new FileReader();
  reader.onload = (e) => {
    pageForm.value.gambar = e.target.result;
  };
  reader.readAsDataURL(file);

  uploadingPageImg.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      pageForm.value.gambar = uploadedUrl;
      toast.success('Foto cover halaman berhasil diunggah.');
      showAlert('Foto cover halaman berhasil diunggah.');
    }
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Gagal mengunggah gambar';
    toast.warning('Pratinjau cover siap disimpan: ' + errText, 'Pratinjau Lokal');
  } finally {
    uploadingPageImg.value = false;
    event.target.value = '';
  }
};

// Sinkronisasi custom_nav_menus di profil_kelurahans agar navbar selalu update otomatis
const syncCustomNavMenus = async (pages) => {
  try {
    const navMenus = {
      profil: [],
      pemerintahan: [],
      informasi: []
    };

    pages.filter(p => p.aktif).forEach(p => {
      const cat = p.kategori || 'profil';
      if (!navMenus[cat]) navMenus[cat] = [];
      navMenus[cat].push({
        label: p.judul,
        url: `/halaman/${p.slug}`,
        target: '_self'
      });
    });

    const res = await AdminService.updateProfil({
      custom_nav_menus: navMenus
    });
    window.dispatchEvent(new CustomEvent('profil-updated', { detail: res?.data }));
  } catch (e) {
    console.warn('Sync custom nav menus warning:', e);
  }
};

const submitHalamanForm = async () => {
  if (savingPage.value) return;
  if (!pageForm.value.judul) {
    toast.warning('Judul halaman wajib diisi.', 'Form Belum Lengkap');
    showAlert('Judul halaman wajib diisi.', 'error');
    return;
  }

  savingPage.value = true;
  try {
    if (modal.value.isEdit) {
      const res = await AdminService.saveHalamanKustom(pageForm.value, modal.value.targetId);
      toast.success('Halaman kustom berhasil diperbarui!');
      showAlert('Halaman kustom berhasil diperbarui!');
      if (res?.data) {
        const idx = halamanList.value.findIndex(h => h.id === modal.value.targetId);
        if (idx !== -1) halamanList.value[idx] = res.data;
      }
    } else {
      const res = await AdminService.saveHalamanKustom(pageForm.value);
      toast.success('Halaman kustom baru dan sub-menu berhasil dibuat!');
      showAlert('Halaman kustom baru dan sub-menu berhasil dibuat!');
      if (res?.data) {
        halamanList.value.unshift(res.data);
      }
    }

    closeModal();
    await loadHalamanList();
    await syncCustomNavMenus(halamanList.value);
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Gagal menyimpan halaman';
    toast.error('Gagal menyimpan halaman: ' + errText, 'Gagal Menyimpan');
    showAlert('Gagal menyimpan halaman: ' + errText, 'error');
  } finally {
    savingPage.value = false;
  }
};

const confirmDeleteHalaman = async (item) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus sub-menu & halaman "${item.judul}"?`)) {
    return;
  }

  try {
    await AdminService.deleteHalamanKustom(item.id);
    toast.success(`Halaman "${item.judul}" berhasil dihapus.`);
    showAlert(`Halaman "${item.judul}" berhasil dihapus.`);
    await loadHalamanList();
    await syncCustomNavMenus(halamanList.value);
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Gagal menghapus halaman';
    toast.error('Gagal menghapus halaman: ' + errText, 'Gagal Menghapus');
    showAlert('Gagal menghapus halaman: ' + errText, 'error');
  }
};

// 3. Storage & Orphaned Files Management State & Methods
const loadingStorage = ref(false);
const cleaningStorage = ref(false);
const storageStats = ref({
  total_disk_files: 0,
  total_disk_size: 0,
  total_disk_size_formatted: '0 B',
  active_db_files_count: 0,
  orphaned_files_count: 0,
  orphaned_size: 0,
  orphaned_size_formatted: '0 B',
  orphaned_files: []
});

const switchToStorageTab = async () => {
  activeTab.value = 'storage';
  await loadStorageStats();
};

const loadStorageStats = async () => {
  loadingStorage.value = true;
  try {
    const data = await AdminService.getStorageStats();
    if (data) {
      storageStats.value = data;
    }
  } catch (err) {
    showAlert('Gagal memindai status penyimpanan: ' + (err.response?.data?.message || err.message), 'error');
  } finally {
    loadingStorage.value = false;
  }
};

const handleCleanStorage = async () => {
  if (!confirm(`Apakah Anda yakin ingin menghapus ${storageStats.value.orphaned_files_count} berkas sampah (${storageStats.value.orphaned_size_formatted}) secara permanen dari server?`)) {
    return;
  }

  cleaningStorage.value = true;
  try {
    const res = await AdminService.cleanOrphanedStorage();
    showAlert(res.message || 'Penyimpanan berhasil dibersihkan.');
    await loadStorageStats();
  } catch (err) {
    showAlert('Gagal membersihkan berkas: ' + (err.response?.data?.message || err.message), 'error');
  } finally {
    cleaningStorage.value = false;
  }
};

onMounted(() => {
  loadProfilVisual();
  loadHalamanList();
});
</script>
