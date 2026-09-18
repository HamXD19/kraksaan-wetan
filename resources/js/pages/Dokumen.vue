<template>
  <div class="pb-20">
    <!-- Breadcrumb Nav -->
    <Breadcrumb :items="[{ label: 'Beranda', to: '/' }, { label: 'Unduh Dokumen Publik' }]" />

    <!-- Hero Header -->
    <section class="relative bg-emerald-950 text-white overflow-hidden py-12 sm:py-16 border-b border-emerald-800 reveal-fade">
      <!-- Background: Mengambil Foto Latar Hero Beranda -->
      <div class="absolute inset-0 z-0 overflow-hidden">
        <img 
          :src="heroImage" 
          alt="Latar Hero Kelurahan Kraksaan Wetan" 
          class="w-full h-full object-cover object-[center_35%] pointer-events-none"
          fetchpriority="high"
          loading="eager"
        />

        <!-- Government Gradient Masks -->
        <div class="absolute inset-0 z-1 bg-gradient-to-r from-emerald-950/95 via-emerald-950/90 to-emerald-950/70"></div>
        <div class="absolute inset-0 z-1 bg-gradient-to-t from-emerald-950 via-transparent to-emerald-950/40"></div>
        <div class="absolute inset-0 z-1 bg-radial at-top opacity-20 mix-blend-overlay"></div>
      </div>

      <!-- Permanent Decorative Landscape Silhouette Contour at Bottom -->
      <div class="absolute bottom-0 inset-x-0 z-2 pointer-events-none opacity-40">
        <svg class="w-full h-10 sm:h-12 text-emerald-950 fill-current preserve-3d" viewBox="0 0 1440 120" preserveAspectRatio="none">
          <path d="M0,32L60,42.7C120,53,240,75,360,69.3C480,64,600,32,720,32C840,32,960,64,1080,69.3C1200,75,1320,53,1380,42.7L1440,32L1440,120L1380,120C1320,120,1200,120,1080,120C960,120,840,120,720,120C600,120,480,120,360,120C240,120,120,120,60,120L0,120Z"></path>
        </svg>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 border border-emerald-700/80 text-amber-300 text-xs font-bold uppercase tracking-wider mb-3 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          Pusat Unduhan & Dokumen Resmi
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
          Unduh Dokumen Publik Kelurahan
        </h1>
        <p class="text-xs sm:text-sm md:text-base text-emerald-200/90 mt-3 max-w-3xl leading-relaxed">
          Layanan pengunduhan berkas dokumen resmi kedinasan, formulir permohonan surat kependudukan, produk hukum, surat keputusan lurah, laporan kinerja, dan pedoman pelayanan warga Kelurahan Kraksaan Wetan secara transparan, mudah, dan bebas biaya (Rp 0).
        </p>

        <!-- Quick Metrics -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mt-8 max-w-2xl">
          <div class="bg-emerald-900/60 border border-emerald-700/50 backdrop-blur-xs p-3.5 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-amber-400/20 text-amber-300 flex items-center justify-center font-bold text-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
              <div class="text-lg sm:text-xl font-extrabold text-white">{{ meta.total || documents.length }}</div>
              <div class="text-[11px] text-emerald-300/80 font-medium">Dokumen Tersedia</div>
            </div>
          </div>

          <div class="bg-emerald-900/60 border border-emerald-700/50 backdrop-blur-xs p-3.5 rounded-2xl flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-400/20 text-emerald-300 flex items-center justify-center font-bold text-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            </div>
            <div>
              <div class="text-lg sm:text-xl font-extrabold text-white">{{ totalDownloads }}</div>
              <div class="text-[11px] text-emerald-300/80 font-medium">Kali Diunduh</div>
            </div>
          </div>

          <div class="hidden sm:flex bg-emerald-900/60 border border-emerald-700/50 backdrop-blur-xs p-3.5 rounded-2xl items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-sky-400/20 text-sky-300 flex items-center justify-center font-bold text-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
              <div class="text-lg sm:text-xl font-extrabold text-white">Format PDF</div>
              <div class="text-[11px] text-emerald-300/80 font-medium">Resmi & Terverifikasi</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-8">
      <!-- Search & Mode Switcher Bar -->
      <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search Input -->
        <div class="relative w-full md:w-96">
          <input 
            v-model="searchQuery" 
            @input="handleSearch"
            type="text" 
            placeholder="Cari berkas dokumen, nomor SK, kata kunci..." 
            class="w-full pl-10 pr-10 py-2.5 rounded-2xl bg-slate-50 border border-slate-200 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3.5 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <button 
            v-if="searchQuery" 
            @click="clearSearch"
            class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 p-0.5 rounded-full cursor-pointer"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- View Switcher & Quick Reset -->
        <div class="flex items-center gap-2 w-full md:w-auto justify-between md:justify-end">
          <div class="inline-flex p-1 rounded-2xl bg-slate-100 border border-slate-200/80 text-xs font-bold">
            <button 
              type="button"
              @click="switchViewMode('folder')"
              class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl transition cursor-pointer"
              :class="viewMode === 'folder' ? 'bg-white text-emerald-800 shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900'"
            >
              <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
              <span>Mode Folder</span>
            </button>
            <button 
              type="button"
              @click="switchViewMode('all')"
              class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl transition cursor-pointer"
              :class="viewMode === 'all' ? 'bg-white text-emerald-800 shadow-xs font-extrabold' : 'text-slate-600 hover:text-slate-900'"
            >
              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
              <span>Semua Dokumen</span>
            </button>
          </div>

          <button 
            v-if="hasActiveFilter" 
            @click="resetFilters" 
            class="text-xs text-emerald-700 hover:text-emerald-900 font-bold hover:underline cursor-pointer ml-2"
          >
            Reset Filter
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <LoadingSpinner v-if="loading" />

      <!-- Main Content Area -->
      <template v-else>
        <!-- ========================================================================= -->
        <!-- STATE A: SEARCH ACTIVE (Shows Search Results with Folder badges)          -->
        <!-- ========================================================================= -->
        <div v-if="searchQuery.trim()" class="space-y-6">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-2xl bg-amber-50/80 border border-amber-200">
            <div class="flex items-center gap-2 text-xs sm:text-sm text-amber-900 font-semibold">
              <svg class="w-5 h-5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
              <span>Hasil pencarian untuk <strong>"{{ searchQuery }}"</strong> &bull; Ditemukan <strong>{{ searchResults.length }}</strong> berkas</span>
            </div>
            <button 
              @click="clearSearch" 
              class="text-xs font-bold text-amber-800 hover:text-amber-950 underline cursor-pointer self-start sm:self-auto"
            >
              &times; Bersihkan Pencarian
            </button>
          </div>

          <!-- Empty search -->
          <div v-if="searchResults.length === 0" class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center mx-auto">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-900">Tidak ada dokumen yang sesuai</h3>
              <p class="text-xs text-slate-500 mt-1">Coba gunakan kata kunci lain seperti "Renstra", "DPA", "Surat", atau "2026".</p>
            </div>
            <button 
              @click="clearSearch"
              class="px-4 py-2 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition cursor-pointer"
            >
              Kembali ke Semua Berkas
            </button>
          </div>

          <!-- Search Cards Grid -->
          <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div 
              v-for="doc in searchResults" 
              :key="doc.id"
              class="bg-white rounded-3xl border border-slate-200/90 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all p-5 sm:p-6 flex flex-col justify-between group relative overflow-hidden"
            >
              <!-- Card Content -->
              <div>
                <div class="flex flex-wrap items-center gap-1.5 mb-3">
                  <!-- Folder indicator badge -->
                  <button 
                    @click="openFolder(doc.periode)" 
                    class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200 hover:bg-amber-100 transition cursor-pointer"
                    title="Buka folder ini"
                  >
                    <svg class="w-3.5 h-3.5 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                    <span>Folder: {{ doc.periode || 'Umum' }}</span>
                  </button>

                  <!-- Kategori Badge -->
                  <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                    {{ doc.kategori || 'Dokumen' }}
                  </span>

                  <!-- Year / Period Time Detail -->
                  <span v-if="doc.label_periode_lengkap || doc.tahun" class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-700">
                    {{ doc.label_periode_lengkap || doc.tahun }}
                  </span>
                </div>

                <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                  {{ doc.judul }}
                </h3>

                <div v-if="doc.nomor_dokumen" class="text-xs font-mono text-emerald-800 font-semibold mt-1 flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                  <span>No: {{ doc.nomor_dokumen }}</span>
                </div>

                <p v-if="doc.deskripsi" class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-2">
                  {{ (doc.deskripsi || '').replace(/<[^>]*>?/gm, '') }}
                </p>
              </div>

              <!-- Card Action -->
              <div class="pt-4 mt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3 text-xs text-slate-500">
                  <span v-if="doc.ukuran_file">{{ doc.ukuran_file }}</span>
                  <span class="font-medium text-emerald-700">{{ doc.diunduh || 0 }} unduhan</span>
                </div>
                <div class="flex items-center gap-2">
                  <button 
                    type="button"
                    @click="openPreviewModal(doc)"
                    class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-xs font-semibold cursor-pointer"
                  >
                    Detail
                  </button>
                  <button 
                    type="button"
                    @click="handleDownload(doc)"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    <span>Unduh</span>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- STATE B: MODE FOLDER - ROOT DIRECTORY (Showing the 6 Folder Cards)        -->
        <!-- ========================================================================= -->
        <div v-else-if="viewMode === 'folder' && !activeFolder" class="space-y-6">
          <!-- Folder Directory Header -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-3 border-b border-slate-200">
            <div>
              <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <svg class="w-6 h-6 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                <span>Direktori Folder Dokumen Kelurahan</span>
              </h2>
              <p class="text-xs text-slate-500 mt-0.5">
                Pilih folder periode di bawah untuk membuka dan melihat daftar berkas dokumen yang tersimpan:
              </p>
            </div>
            <div class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 text-slate-600 self-start sm:self-auto">
              Total {{ documents.length }} Berkas Tersedia
            </div>
          </div>

          <!-- Folder Cards Grid (2 cols on md, 3 cols on xl) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <div 
              v-for="folder in folderList" 
              :key="folder.id"
              @click="openFolder(folder.id)"
              class="group relative bg-white rounded-3xl border border-slate-200 p-6 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-200 flex flex-col justify-between cursor-pointer overflow-hidden"
              :class="folder.accentBorder"
            >
              <!-- Subtle gradient glow backdrop -->
              <div 
                class="absolute -top-12 -right-12 w-32 h-32 rounded-full opacity-30 blur-2xl pointer-events-none group-hover:scale-150 transition-transform duration-500"
                :style="{ backgroundColor: folder.folderColor }"
              ></div>

              <!-- Top Content -->
              <div class="relative z-10 space-y-4">
                <div class="flex items-center justify-between">
                  <!-- Big Folder Tab Icon -->
                  <div 
                    class="w-14 h-14 rounded-2xl flex items-center justify-center border transition-transform duration-200 group-hover:scale-110 shadow-xs"
                    :class="folder.iconBg"
                  >
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                    </svg>
                  </div>

                  <!-- Count Badge -->
                  <span 
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold border shadow-2xs"
                    :class="folder.badgeClass"
                  >
                    {{ folder.count }} Berkas
                  </span>
                </div>

                <div>
                  <h3 class="text-base sm:text-lg font-extrabold text-slate-900 group-hover:text-emerald-700 transition-colors flex items-center justify-between">
                    <span>{{ folder.name }}</span>
                  </h3>
                  <p class="text-xs text-slate-500 mt-1.5 leading-relaxed line-clamp-2">
                    {{ folder.subtitle }}
                  </p>
                </div>

                <!-- Available Year Pills in Folder -->
                <div v-if="folder.years.length > 0" class="flex items-center gap-1.5 flex-wrap pt-1">
                  <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tahun:</span>
                  <span 
                    v-for="y in folder.years.slice(0, 3)" 
                    :key="y"
                    class="text-[10px] font-semibold px-2 py-0.5 rounded-md bg-slate-100 text-slate-600"
                  >
                    {{ y }}
                  </span>
                  <span v-if="folder.years.length > 3" class="text-[10px] text-slate-400 font-bold">
                    +{{ folder.years.length - 3 }} lainnya
                  </span>
                </div>
              </div>

              <!-- Bottom Footer: Click Prompt -->
              <div class="relative z-10 pt-4 mt-5 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-700 group-hover:text-emerald-700 transition-colors">
                <span class="flex items-center gap-1.5">
                  <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                  <span>Buka Folder</span>
                </span>
                <span class="inline-flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                  <span>Lihat Berkas</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- STATE C: MODE FOLDER - DRILLDOWN VIEW (Inside a Specific Folder)          -->
        <!-- ========================================================================= -->
        <div v-else-if="viewMode === 'folder' && activeFolder" class="space-y-6">
          <!-- Folder Navigation Breadcrumb Bar -->
          <div class="bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/90 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs sm:text-sm">
              <button 
                type="button" 
                @click="openFolder(null)"
                class="font-bold text-slate-500 hover:text-emerald-700 flex items-center gap-1.5 transition cursor-pointer"
              >
                <svg class="w-4 h-4 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                <span>Direktori Folder</span>
              </button>
              <span class="text-slate-300 font-bold">/</span>
              <span class="font-extrabold text-slate-900 flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-xl text-amber-900 border border-amber-200/80">
                <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                <span>{{ currentFolder?.name || activeFolder }}</span>
              </span>
            </div>

            <!-- Back to Folders Button -->
            <button 
              type="button" 
              @click="openFolder(null)"
              class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer self-start sm:self-auto"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
              <span>Kembali ke Semua Folder</span>
            </button>
          </div>

          <!-- Active Folder Context Header Banner -->
          <div class="bg-gradient-to-r from-emerald-900 via-emerald-950 to-[#0f2922] text-white p-6 sm:p-8 rounded-3xl border border-emerald-800 shadow-md relative overflow-hidden flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="relative z-10 space-y-2 max-w-2xl">
              <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/90 text-amber-300 text-xs font-bold uppercase tracking-wider border border-emerald-700/60">
                <span>📁 Isi Folder: {{ currentFolder?.shortName || activeFolder }}</span>
              </div>
              <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                {{ currentFolder?.name || activeFolder }}
              </h1>
              <p class="text-xs sm:text-sm text-emerald-200/90 leading-relaxed">
                {{ currentFolder?.subtitle }}
              </p>
            </div>

            <div class="relative z-10 flex flex-col items-start md:items-end gap-2 shrink-0">
              <div class="px-4 py-2 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white">
                <span class="text-xs text-emerald-200 block">Berkas di Folder Ini:</span>
                <span class="text-xl sm:text-2xl font-black text-amber-300">{{ folderDocuments.length }} Dokumen</span>
              </div>
            </div>
          </div>

          <!-- Sub-filters inside Active Folder (Year Filter Pills) -->
          <div v-if="folderAvailableYears.length > 1" class="bg-white p-4 rounded-2xl border border-slate-200 flex items-center gap-2 overflow-x-auto scrollbar-none">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap pr-2">Filter Tahun:</span>
            <button 
              @click="selectedFolderTahun = 'Semua'"
              class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer"
              :class="selectedFolderTahun === 'Semua' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
            >
              Semua Tahun ({{ folderDocumentsCountAll }})
            </button>
            <button 
              v-for="y in folderAvailableYears" 
              :key="y"
              @click="selectedFolderTahun = y"
              class="px-3.5 py-1.5 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer"
              :class="selectedFolderTahun === y ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
            >
              Tahun {{ y }}
            </button>
          </div>

          <!-- Empty in Folder -->
          <div v-if="folderDocuments.length === 0" class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto space-y-4">
            <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center mx-auto">
              <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-900">Belum ada dokumen di folder ini</h3>
              <p class="text-xs text-slate-500 mt-1">Silakan kembali ke direktori utama untuk memilih folder lainnya.</p>
            </div>
            <button 
              @click="openFolder(null)"
              class="px-4 py-2 rounded-xl bg-slate-800 text-white text-xs font-bold hover:bg-slate-900 transition cursor-pointer"
            >
              Kembali ke Direktori Folder
            </button>
          </div>

          <!-- Documents List in Active Folder (Grouped Cleanly) -->
          <div v-else class="space-y-8">
            <div v-for="group in folderGroupedDocuments" :key="group.title" class="space-y-4">
              <!-- Group Header -->
              <div class="flex items-center gap-3 pb-2 border-b border-slate-200">
                <div class="w-2.5 h-6 bg-emerald-600 rounded-full"></div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800">
                  {{ group.title }}
                </h2>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                  {{ group.items.length }} berkas
                </span>
              </div>

              <!-- Cards Grid -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div 
                  v-for="doc in group.items" 
                  :key="doc.id"
                  class="bg-white rounded-3xl border border-slate-200/90 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all p-5 sm:p-6 flex flex-col justify-between group relative overflow-hidden"
                >
                  <!-- Card Header: Badges & Info -->
                  <div>
                    <div class="flex flex-wrap items-center gap-1.5 mb-3">
                      <!-- Kategori Badge -->
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ doc.kategori || 'Dokumen Resmi' }}
                      </span>

                      <!-- Subkategori Badge -->
                      <span v-if="doc.subkategori" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                        {{ doc.subkategori }}
                      </span>

                      <!-- Periode Badge -->
                      <span v-if="doc.periode_ke" class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-teal-100 text-teal-800">
                        {{ doc.periode_ke }}
                      </span>

                      <!-- Year / Period Time Detail -->
                      <span v-if="doc.label_periode_lengkap || doc.tahun" class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-md bg-amber-50 text-amber-900 border border-amber-200/60">
                        {{ doc.label_periode_lengkap || doc.tahun }}
                      </span>
                    </div>

                    <!-- Title & Number -->
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                      {{ doc.judul }}
                    </h3>

                    <div v-if="doc.nomor_dokumen" class="text-xs font-mono text-emerald-800 font-semibold mt-1 flex items-center gap-1.5">
                      <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                      <span>No: {{ doc.nomor_dokumen }}</span>
                    </div>

                    <!-- Description snippet -->
                    <p v-if="doc.deskripsi" class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-2">
                      {{ (doc.deskripsi || '').replace(/<[^>]*>?/gm, '') }}
                    </p>
                  </div>

                  <!-- Card Footer Action Buttons -->
                  <div class="pt-4 mt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 text-xs text-slate-500">
                      <span v-if="doc.ukuran_file" class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7M4 7c0-2 1.5-3 3.5-3h9c2 0 3.5 1 3.5 3M4 7h16"/></svg>
                        {{ doc.ukuran_file }}
                      </span>
                      <span class="flex items-center gap-1 font-medium text-emerald-700">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ doc.diunduh || 0 }} unduhan
                      </span>
                    </div>

                    <div class="flex items-center gap-2">
                      <button 
                        type="button"
                        @click="openPreviewModal(doc)"
                        class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-xs font-semibold cursor-pointer inline-flex items-center gap-1.5"
                        title="Lihat Detail & Pratinjau Dokumen"
                      >
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Detail</span>
                      </button>

                      <button 
                        type="button"
                        @click="handleDownload(doc)"
                        class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer group-hover:scale-[1.02] active:scale-[0.98]"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ========================================================================= -->
        <!-- STATE D: MODE ALL DOCUMENTS (Flat List with Multi-Filter Toolbar)         -->
        <!-- ========================================================================= -->
        <div v-else class="space-y-6">
          <!-- Comprehensive Multi-Filter Bar -->
          <div class="bg-white p-5 rounded-3xl border border-slate-200/90 shadow-xs space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
              <!-- Periode Filter -->
              <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Periode</label>
                <select 
                  v-model="selectedPeriode" 
                  @change="fetchDocuments"
                  class="w-full py-2.5 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
                >
                  <option value="Semua">Semua Periode</option>
                  <option value="5 Tahunan">5 Tahunan</option>
                  <option value="Tahunan">Tahunan</option>
                  <option value="Semesteran">Semesteran</option>
                  <option value="Triwulanan">Triwulanan</option>
                  <option value="Bulanan">Bulanan</option>
                  <option value="Sewaktu-waktu">Sewaktu-waktu</option>
                </select>
              </div>

              <!-- Tahun Filter -->
              <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Tahun</label>
                <select 
                  v-model="selectedTahun" 
                  @change="fetchDocuments"
                  class="w-full py-2.5 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
                >
                  <option value="Semua">Semua Tahun</option>
                  <option v-for="y in (meta.tahun_list || [2026, 2025, 2024])" :key="y" :value="y">Tahun {{ y }}</option>
                </select>
              </div>

              <!-- Kategori Filter -->
              <div>
                <label class="block text-[10px] font-bold uppercase text-slate-400 mb-1">Kategori</label>
                <select 
                  v-model="selectedKategori" 
                  @change="selectCategory(selectedKategori)"
                  class="w-full py-2.5 px-3 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
                >
                  <option value="Semua">Semua Kategori</option>
                  <option v-for="c in availableCategories" :key="c" :value="c">{{ c }}</option>
                </select>
              </div>
            </div>

            <!-- Subkategori if any -->
            <div v-if="availableSubcategories.length > 0" class="flex items-center gap-1.5 overflow-x-auto pt-2 border-t border-slate-100">
              <span class="text-xs font-bold text-slate-400 whitespace-nowrap">Sub:</span>
              <button 
                @click="selectSubcategory('Semua')"
                class="px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer"
                :class="selectedSubkategori === 'Semua' ? 'bg-emerald-100 text-emerald-800 font-bold' : 'bg-slate-100 text-slate-600'"
              >
                Semua Subkategori
              </button>
              <button 
                v-for="s in availableSubcategories" 
                :key="s"
                @click="selectSubcategory(s)"
                class="px-2.5 py-1 rounded-lg text-xs font-semibold whitespace-nowrap transition cursor-pointer"
                :class="selectedSubkategori === s ? 'bg-emerald-100 text-emerald-800 font-bold' : 'bg-slate-100 text-slate-600'"
              >
                {{ s }}
              </button>
            </div>
          </div>

          <!-- Empty State -->
          <div 
            v-if="documents.length === 0" 
            class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-lg mx-auto space-y-4"
          >
            <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-900">Tidak ada dokumen ditemukan</h3>
              <p class="text-xs text-slate-500 mt-1">Belum ada dokumen publik yang terdaftar pada kriteria filter ini.</p>
            </div>
            <button 
              @click="resetFilters"
              class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition cursor-pointer"
            >
              Reset Semua Filter
            </button>
          </div>

          <!-- Grouped Document View by Year / Period -->
          <div v-else class="space-y-8">
            <div v-for="group in groupedDocuments" :key="group.title" class="space-y-4">
              <!-- Group Section Header -->
              <div class="flex items-center gap-3 pb-2 border-b border-slate-200">
                <div class="w-2.5 h-6 bg-emerald-600 rounded-full"></div>
                <h2 class="text-base sm:text-lg font-bold text-slate-800">
                  {{ group.title }}
                </h2>
                <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                  {{ group.items.length }} berkas
                </span>
              </div>

              <!-- Cards Grid -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div 
                  v-for="doc in group.items" 
                  :key="doc.id"
                  class="bg-white rounded-3xl border border-slate-200/90 shadow-xs hover:shadow-md hover:border-emerald-300 transition-all p-5 sm:p-6 flex flex-col justify-between group relative overflow-hidden"
                >
                  <!-- Top Card Header: Badges -->
                  <div>
                    <div class="flex flex-wrap items-center gap-1.5 mb-3">
                      <!-- Kategori Badge -->
                      <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        {{ doc.kategori || 'Dokumen Resmi' }}
                      </span>

                      <!-- Subkategori Badge -->
                      <span v-if="doc.subkategori" class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-medium bg-slate-100 text-slate-700 border border-slate-200">
                        {{ doc.subkategori }}
                      </span>

                      <!-- Periode Badge -->
                      <span v-if="doc.periode" class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider" :class="getPeriodeBadgeClass(doc.periode)">
                        {{ doc.periode }}
                      </span>

                      <!-- Year / Period Time Detail -->
                      <span v-if="doc.label_periode_lengkap || doc.tahun" class="ml-auto text-xs font-semibold px-2 py-0.5 rounded-md bg-amber-50 text-amber-900 border border-amber-200/60">
                        {{ doc.label_periode_lengkap || doc.tahun }}
                      </span>
                    </div>

                    <!-- Title & Number -->
                    <h3 class="text-base sm:text-lg font-bold text-slate-900 group-hover:text-emerald-700 transition-colors leading-snug">
                      {{ doc.judul }}
                    </h3>

                    <div v-if="doc.nomor_dokumen" class="text-xs font-mono text-emerald-800 font-semibold mt-1 flex items-center gap-1.5">
                      <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                      <span>No: {{ doc.nomor_dokumen }}</span>
                    </div>

                    <!-- Description snippet -->
                    <p v-if="doc.deskripsi" class="text-xs text-slate-600 mt-2.5 leading-relaxed line-clamp-2">
                      {{ (doc.deskripsi || '').replace(/<[^>]*>?/gm, '') }}
                    </p>
                  </div>

                  <!-- Bottom Card Meta & Action Buttons -->
                  <div class="pt-4 mt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="flex items-center gap-3 text-xs text-slate-500">
                      <span v-if="doc.ukuran_file" class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1.5 3 3.5 3h9c2 0 3.5-1 3.5-3V7M4 7c0-2 1.5-3 3.5-3h9c2 0 3.5 1 3.5 3M4 7h16"/></svg>
                        {{ doc.ukuran_file }}
                      </span>
                      <span class="flex items-center gap-1 font-medium text-emerald-700">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        {{ doc.diunduh || 0 }} unduhan
                      </span>
                    </div>

                    <div class="flex items-center gap-2">
                      <button 
                        type="button"
                        @click="openPreviewModal(doc)"
                        class="px-3 py-2 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 transition text-xs font-semibold cursor-pointer inline-flex items-center gap-1.5"
                      >
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <span>Detail</span>
                      </button>

                      <button 
                        type="button"
                        @click="handleDownload(doc)"
                        class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer group-hover:scale-[1.02] active:scale-[0.98]"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Unduh</span>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>

      <!-- Bottom Information Card: Bantuan & Kontak -->
      <div class="bg-gradient-to-br from-emerald-50 to-emerald-100/60 p-6 sm:p-8 rounded-3xl border border-emerald-200/80 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-1 max-w-2xl">
          <div class="flex items-center gap-2 text-emerald-900 font-bold text-sm">
            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Membutuhkan Dokumen atau Formulir Khusus Lainnya?</span>
          </div>
          <p class="text-xs text-emerald-800 leading-relaxed">
            Jika dokumen atau blangko surat permohonan yang Anda butuhkan belum tersedia dalam daftar unduhan, silakan hubungi petugas pelayanan di Balai Kelurahan Kraksaan Wetan atau melalui layanan WhatsApp Halo SAE.
          </p>
        </div>

        <div class="flex items-center gap-3 w-full md:w-auto">
          <router-link 
            to="/kontak" 
            class="w-full md:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition shadow-sm"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>Hubungi Layanan Warga</span>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Modal Pratinjau & Detail Dokumen -->
    <div 
      v-if="selectedPreviewDoc" 
      class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/75 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4"
    >
      <div 
        class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl overflow-hidden border border-slate-200 flex flex-col max-h-[92vh] animate-in fade-in zoom-in-95 duration-200"
      >
        <!-- Modal Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80 shrink-0">
          <div class="flex items-center gap-2">
            <span class="p-2 rounded-xl bg-emerald-100 text-emerald-800">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </span>
            <div>
              <h3 class="text-sm font-bold text-slate-800 line-clamp-1">Pratinjau & Detail Berkas</h3>
              <p class="text-[11px] text-slate-500">Kelurahan Kraksaan Wetan</p>
            </div>
          </div>
          <!-- Close button ONLY -->
          <button 
            @click="closePreviewModal" 
            class="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition cursor-pointer"
            title="Tutup Jendela"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Modal Body (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-6">
          <!-- Metadata Badges Grid -->
          <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/80 space-y-3">
            <h4 class="text-base font-bold text-slate-900">{{ selectedPreviewDoc.judul }}</h4>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
              <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Kategori</span>
                <span class="font-semibold text-slate-800">{{ selectedPreviewDoc.kategori || '-' }}</span>
              </div>
              <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Subkategori</span>
                <span class="font-semibold text-slate-800">{{ selectedPreviewDoc.subkategori || '-' }}</span>
              </div>
              <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Periode / Waktu</span>
                <span class="font-semibold text-slate-800">{{ selectedPreviewDoc.label_periode_lengkap || selectedPreviewDoc.tahun || '-' }}</span>
              </div>
              <div>
                <span class="text-slate-400 block text-[10px] uppercase font-bold">Nomor Dokumen</span>
                <span class="font-mono text-emerald-800 font-semibold">{{ selectedPreviewDoc.nomor_dokumen || '-' }}</span>
              </div>
            </div>
          </div>

          <!-- Description if available -->
          <div v-if="selectedPreviewDoc.deskripsi" class="space-y-1">
            <h5 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Keterangan / Ringkasan Dokumen:</h5>
            <div 
              class="text-xs text-slate-600 leading-relaxed bg-white p-3 rounded-xl border border-slate-100 prose prose-sm max-w-none"
              v-html="selectedPreviewDoc.deskripsi"
            ></div>
          </div>

          <!-- PDF Embed / Viewer Frame -->
          <div class="border border-slate-200 rounded-2xl overflow-hidden bg-slate-100">
            <div class="bg-slate-200/80 px-4 py-2 flex items-center justify-between text-xs text-slate-600">
              <span class="font-semibold">Pratinjau Dokumen (PDF)</span>
              <a 
                :href="selectedPreviewDoc.file_url" 
                target="_blank" 
                class="text-emerald-700 hover:underline font-bold flex items-center gap-1"
              >
                <span>Buka di Tab Baru</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              </a>
            </div>
            <div class="h-96 w-full bg-slate-50 flex items-center justify-center">
              <iframe 
                :src="selectedPreviewDoc.file_url" 
                class="w-full h-full border-none"
                title="Pratinjau Dokumen PDF"
              >
                <div class="p-6 text-center text-xs text-slate-500">
                  Peramban Anda tidak mendukung pratinjau PDF langsung. Silakan klik tombol "Buka di Tab Baru" atau "Unduh Dokumen".
                </div>
              </iframe>
            </div>
          </div>
        </div>

        <!-- Modal Footer Sticky -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
          <div class="text-xs text-slate-500">
            Ukuran: <strong class="text-slate-700">{{ selectedPreviewDoc.ukuran_file || '-' }}</strong> &bull;
            Diunduh: <strong class="text-emerald-700">{{ selectedPreviewDoc.diunduh || 0 }} kali</strong>
          </div>
          <div class="flex items-center gap-2">
            <button 
              type="button" 
              @click="closePreviewModal" 
              class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
            >
              Tutup
            </button>
            <button 
              type="button" 
              @click="handleDownload(selectedPreviewDoc)" 
              class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 text-white text-xs font-bold hover:bg-emerald-800 transition cursor-pointer shadow-xs"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              <span>Unduh Dokumen</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';

const loading = ref(true);
const documents = ref([]);
const meta = ref({ total: 0, kategori_list: [], tahun_list: [], master_kategori_tree: [] });
const profil = ref({});

// Folder State
const viewMode = ref('folder'); // 'folder' | 'all'
const activeFolder = ref(null);
const selectedFolderTahun = ref('Semua');

// Filters State
const searchQuery = ref('');
const selectedKategori = ref('Semua');
const selectedSubkategori = ref('Semua');
const selectedPeriode = ref('Semua');
const selectedTahun = ref('Semua');
const selectedPreviewDoc = ref(null);

const heroImage = computed(() => {
  return profil.value?.hero_image || '/images/hero-bromo-vector.jpg';
});

// Master Folder Definitions
const folderDefinitions = [
  {
    id: 'Tahunan',
    name: 'Dokumen Tahunan',
    shortName: 'Tahunan',
    subtitle: 'Rencana Kerja (RKP), DPA, LKjIP, Buku Profil, dan dokumen laporan kerja tahunan',
    iconBg: 'bg-amber-500/10 text-amber-600 border-amber-200 group-hover:bg-amber-500/20',
    folderColor: '#f59e0b',
    badgeClass: 'bg-amber-100 text-amber-900 border-amber-200/80',
    accentBorder: 'hover:border-amber-400 group-hover:shadow-amber-500/10',
  },
  {
    id: 'Triwulanan',
    name: 'Dokumen Triwulanan',
    shortName: 'Triwulanan',
    subtitle: 'Laporan penyerapan anggaran per triwulan, survei berkala kepuasan masyarakat (IKM / SKM)',
    iconBg: 'bg-emerald-500/10 text-emerald-600 border-emerald-200 group-hover:bg-emerald-500/20',
    folderColor: '#10b981',
    badgeClass: 'bg-emerald-100 text-emerald-900 border-emerald-200/80',
    accentBorder: 'hover:border-emerald-400 group-hover:shadow-emerald-500/10',
  },
  {
    id: 'Semesteran',
    name: 'Dokumen Semesteran',
    shortName: 'Semesteran',
    subtitle: 'Laporan realisasi anggaran Semester I & II, laporan evaluasi capaian kinerja tengah tahun',
    iconBg: 'bg-teal-500/10 text-teal-600 border-teal-200 group-hover:bg-teal-500/20',
    folderColor: '#14b8a6',
    badgeClass: 'bg-teal-100 text-teal-900 border-teal-200/80',
    accentBorder: 'hover:border-teal-400 group-hover:shadow-teal-500/10',
  },
  {
    id: '5 Tahunan',
    name: 'Dokumen 5 Tahunan',
    shortName: '5 Tahunan',
    subtitle: 'Rencana Strategis (Renstra) dan RPJM Kelurahan untuk arah pembangunan lima tahunan',
    iconBg: 'bg-purple-500/10 text-purple-600 border-purple-200 group-hover:bg-purple-500/20',
    folderColor: '#9333ea',
    badgeClass: 'bg-purple-100 text-purple-900 border-purple-200/80',
    accentBorder: 'hover:border-purple-400 group-hover:shadow-purple-500/10',
  },
  {
    id: 'Bulanan',
    name: 'Dokumen Bulanan',
    shortName: 'Bulanan',
    subtitle: 'Rekapitulasi mutasi kependudukan bulanan dan laporan berkala rutin kelurahan',
    iconBg: 'bg-blue-500/10 text-blue-600 border-blue-200 group-hover:bg-blue-500/20',
    folderColor: '#2563eb',
    badgeClass: 'bg-blue-100 text-blue-900 border-blue-200/80',
    accentBorder: 'hover:border-blue-400 group-hover:shadow-blue-500/10',
  },
  {
    id: 'Sewaktu-waktu',
    name: 'Dokumen Sewaktu-waktu / Layanan',
    shortName: 'Sewaktu-waktu',
    subtitle: 'Blangko formulir permohonan surat warga, standar operasional (SOP), dan berkas insidental',
    iconBg: 'bg-rose-500/10 text-rose-600 border-rose-200 group-hover:bg-rose-500/20',
    folderColor: '#e11d48',
    badgeClass: 'bg-rose-100 text-rose-900 border-rose-200/80',
    accentBorder: 'hover:border-rose-400 group-hover:shadow-rose-500/10',
  },
];

// Computed Folders List
const folderList = computed(() => {
  const allDocs = documents.value || [];
  return folderDefinitions.map(f => {
    const items = allDocs.filter(d => (d.periode || '').trim().toLowerCase() === f.id.toLowerCase());
    const years = Array.from(new Set(items.map(d => d.tahun).filter(Boolean))).sort().reverse();
    return {
      ...f,
      count: items.length,
      years,
      items,
    };
  });
});

// Currently Opened Folder
const currentFolder = computed(() => {
  if (!activeFolder.value) return null;
  return folderList.value.find(f => f.id.toLowerCase() === activeFolder.value.toLowerCase()) || {
    id: activeFolder.value,
    name: `Dokumen ${activeFolder.value}`,
    shortName: activeFolder.value,
    subtitle: `Kumpulan berkas dokumen periode ${activeFolder.value}`,
    count: folderDocuments.value.length,
    years: [],
    badgeClass: 'bg-slate-100 text-slate-800',
    iconBg: 'bg-slate-100 text-slate-700',
  };
});

// Available years in active folder
const folderAvailableYears = computed(() => {
  if (!activeFolder.value) return [];
  const items = documents.value.filter(d => (d.periode || '').trim().toLowerCase() === activeFolder.value.toLowerCase());
  return Array.from(new Set(items.map(d => d.tahun).filter(Boolean))).sort().reverse();
});

const folderDocumentsCountAll = computed(() => {
  if (!activeFolder.value) return 0;
  return documents.value.filter(d => (d.periode || '').trim().toLowerCase() === activeFolder.value.toLowerCase()).length;
});

// Documents in Active Folder (Filtered by selectedFolderTahun)
const folderDocuments = computed(() => {
  if (!activeFolder.value) return [];
  let list = documents.value.filter(d => (d.periode || '').trim().toLowerCase() === activeFolder.value.toLowerCase());
  if (selectedFolderTahun.value !== 'Semua') {
    list = list.filter(d => d.tahun == selectedFolderTahun.value);
  }
  return list;
});

// Grouped documents inside active folder (by Year / Period)
const folderGroupedDocuments = computed(() => {
  if (folderDocuments.value.length === 0) return [];
  const groups = {};
  for (const doc of folderDocuments.value) {
    let groupKey = 'Lainnya';
    if (doc.periode === '5 Tahunan' && doc.tahun && doc.tahun_selesai) {
      groupKey = `Periode ${doc.tahun}–${doc.tahun_selesai}`;
    } else if (doc.tahun) {
      groupKey = `Tahun ${doc.tahun}`;
    }

    if (!groups[groupKey]) {
      groups[groupKey] = [];
    }
    groups[groupKey].push(doc);
  }

  return Object.keys(groups).sort((a, b) => {
    if (a.includes('Periode') && !b.includes('Periode')) return -1;
    if (!a.includes('Periode') && b.includes('Periode')) return 1;
    return b.localeCompare(a);
  }).map(key => ({
    title: key,
    items: groups[key]
  }));
});

// Search Results Across All Documents
const searchResults = computed(() => {
  const q = searchQuery.value.trim().toLowerCase();
  if (!q) return [];
  return documents.value.filter(d => {
    const matchJudul = (d.judul || '').toLowerCase().includes(q);
    const matchNomor = (d.nomor_dokumen || '').toLowerCase().includes(q);
    const matchKategori = (d.kategori || '').toLowerCase().includes(q);
    const matchSub = (d.subkategori || '').toLowerCase().includes(q);
    const matchPeriode = (d.periode || '').toLowerCase().includes(q);
    const matchDeskripsi = (d.deskripsi || '').toLowerCase().includes(q);
    return matchJudul || matchNomor || matchKategori || matchSub || matchPeriode || matchDeskripsi;
  });
});

const defaultCategories = [
  'Formulir Layanan',
  'Perencanaan & Kinerja',
  'Transparansi & Keuangan',
  'Produk Hukum & SK',
  'Laporan Kependudukan',
  'Pedoman & Surat Edaran'
];

const availableCategories = computed(() => {
  const backendCategories = meta.value.kategori_list || [];
  const merged = Array.from(new Set([...defaultCategories, ...backendCategories]));
  return merged;
});

const availableSubcategories = computed(() => {
  if (selectedKategori.value === 'Semua') return [];
  const tree = meta.value.master_kategori_tree || [];
  const parent = tree.find(k => k.nama.toLowerCase() === selectedKategori.value.toLowerCase());
  if (!parent || !parent.subkategoris) return [];
  return parent.subkategoris.map(s => s.nama);
});

const hasActiveFilter = computed(() => {
  return searchQuery.value.trim() !== '' || 
    selectedKategori.value !== 'Semua' || 
    selectedSubkategori.value !== 'Semua' || 
    selectedPeriode.value !== 'Semua' || 
    selectedTahun.value !== 'Semua' ||
    activeFolder.value !== null;
});

const totalDownloads = computed(() => {
  return documents.value.reduce((sum, d) => sum + (parseInt(d.diunduh, 10) || 0), 0);
});

const groupedDocuments = computed(() => {
  if (documents.value.length === 0) return [];

  const groups = {};
  for (const doc of documents.value) {
    let groupKey = 'Lainnya';
    if (doc.periode === '5 Tahunan' && doc.tahun && doc.tahun_selesai) {
      groupKey = `Periode ${doc.tahun}–${doc.tahun_selesai}`;
    } else if (doc.tahun) {
      groupKey = `Tahun ${doc.tahun}`;
    }

    if (!groups[groupKey]) {
      groups[groupKey] = [];
    }
    groups[groupKey].push(doc);
  }

  return Object.keys(groups).sort((a, b) => {
    if (a.includes('Periode') && !b.includes('Periode')) return -1;
    if (!a.includes('Periode') && b.includes('Periode')) return 1;
    return b.localeCompare(a);
  }).map(key => ({
    title: key,
    items: groups[key]
  }));
});

function getPeriodeBadgeClass(periode) {
  switch (periode) {
    case '5 Tahunan': return 'bg-purple-100 text-purple-800';
    case 'Tahunan': return 'bg-blue-100 text-blue-800';
    case 'Semesteran': return 'bg-teal-100 text-teal-800';
    case 'Triwulanan': return 'bg-amber-100 text-amber-800';
    case 'Bulanan': return 'bg-indigo-100 text-indigo-800';
    case 'Sewaktu-waktu': return 'bg-rose-100 text-rose-800';
    default: return 'bg-slate-100 text-slate-800';
  }
}

const openFolder = (folderId) => {
  activeFolder.value = folderId;
  selectedFolderTahun.value = 'Semua';
  viewMode.value = 'folder';
};

const switchViewMode = (mode) => {
  viewMode.value = mode;
  if (mode === 'all') {
    activeFolder.value = null;
  }
};

let searchTimeout = null;
const handleSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    if (viewMode.value === 'all') {
      fetchDocuments();
    }
  }, 300);
};

const clearSearch = () => {
  searchQuery.value = '';
  if (viewMode.value === 'all') {
    fetchDocuments();
  }
};

const selectCategory = (category) => {
  selectedKategori.value = category;
  selectedSubkategori.value = 'Semua';
  fetchDocuments();
};

const selectSubcategory = (sub) => {
  selectedSubkategori.value = sub;
  fetchDocuments();
};

const resetFilters = () => {
  searchQuery.value = '';
  selectedKategori.value = 'Semua';
  selectedSubkategori.value = 'Semua';
  selectedPeriode.value = 'Semua';
  selectedTahun.value = 'Semua';
  activeFolder.value = null;
  selectedFolderTahun.value = 'Semua';
  fetchDocuments();
};

const fetchDocuments = async () => {
  loading.value = true;
  try {
    const params = {};
    if (viewMode.value === 'all') {
      if (searchQuery.value.trim()) {
        params.q = searchQuery.value.trim();
      }
      if (selectedKategori.value !== 'Semua') {
        params.kategori = selectedKategori.value;
      }
      if (selectedSubkategori.value !== 'Semua') {
        params.subkategori = selectedSubkategori.value;
      }
      if (selectedPeriode.value !== 'Semua') {
        params.periode = selectedPeriode.value;
      }
      if (selectedTahun.value !== 'Semua') {
        params.tahun = selectedTahun.value;
      }
    }

    const res = await KelurahanService.getDokumen(params);
    documents.value = res.data || [];
    meta.value = res.meta || { total: documents.value.length, kategori_list: [], tahun_list: [], master_kategori_tree: [] };
  } catch (err) {
    console.error('Failed to load documents:', err);
    documents.value = [];
  } finally {
    loading.value = false;
  }
};

const openPreviewModal = (doc) => {
  selectedPreviewDoc.value = doc;
};

const closePreviewModal = () => {
  selectedPreviewDoc.value = null;
};

const handleDownload = (doc) => {
  const downloadUrl = KelurahanService.getDokumenUnduhUrl(doc.id);
  
  const link = document.createElement('a');
  link.href = downloadUrl;
  link.setAttribute('download', `${doc.judul}.pdf`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  doc.diunduh = (doc.diunduh || 0) + 1;
};

onMounted(async () => {
  try {
    const p = await KelurahanService.getProfil();
    if (p) {
      profil.value = p;
    }
  } catch (err) {
    console.error('Failed to load profil for hero image:', err);
  }
  fetchDocuments();
});
</script>
