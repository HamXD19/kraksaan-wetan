<template>
  <div class="min-h-screen bg-slate-50/50 pb-20">
    <!-- Breadcrumb Nav -->
    <Breadcrumb :items="[{ label: 'Beranda', to: '/' }, { label: 'Dokumen' }]" />

    <!-- Hero Header -->
    <section class="relative bg-emerald-950 text-white overflow-hidden py-10 sm:py-14 border-b border-emerald-800">
      <!-- Background Hero Image -->
      <div class="absolute inset-0 z-0 overflow-hidden">
        <img 
          :src="heroImage" 
          alt="Latar Hero Kelurahan Kraksaan Wetan" 
          class="w-full h-full object-cover object-[center_35%] pointer-events-none opacity-25"
          fetchpriority="high"
          loading="eager"
        />
        <div class="absolute inset-0 z-1 bg-gradient-to-r from-emerald-950 via-emerald-950/90 to-emerald-900/80"></div>
        <div class="absolute inset-0 z-1 bg-gradient-to-t from-emerald-950 via-transparent to-emerald-950/40"></div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-800/80 border border-emerald-700/80 text-amber-300 text-xs font-bold uppercase tracking-wider mb-3 shadow-xs">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          Pusat Unduhan & Dokumen Resmi
        </div>
        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white leading-tight">
          Daftar Dokumen Publik
        </h1>
        <p class="text-xs sm:text-sm md:text-base text-emerald-200/90 mt-2.5 max-w-3xl leading-relaxed">
          Akses dan unduh berkas dokumen kedinasan, perencanaan, laporan pertanggungjawaban, produk hukum, dan regulasi Kelurahan Kraksaan Wetan secara transparan dan mudah.
        </p>

        <!-- Quick Summary Counters -->
        <div class="flex flex-wrap items-center gap-3 sm:gap-6 mt-6 text-xs sm:text-sm text-emerald-200/90">
          <div class="flex items-center gap-2 bg-emerald-900/60 border border-emerald-700/60 px-3.5 py-1.5 rounded-xl backdrop-blur-xs">
            <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Total: <strong class="text-white font-bold">{{ documents.length }}</strong> Dokumen</span>
          </div>
          <div class="flex items-center gap-2 bg-emerald-900/60 border border-emerald-700/60 px-3.5 py-1.5 rounded-xl backdrop-blur-xs">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>Total Diunduh: <strong class="text-white font-bold">{{ totalDownloads }}</strong> Kali</span>
          </div>
          <div v-if="selectedKategori !== 'Semua Kategori'" class="flex items-center gap-2 bg-amber-400/20 border border-amber-400/40 text-amber-200 px-3 py-1 rounded-xl">
            <span>Filter Kategori: <strong class="text-amber-300 font-bold">{{ selectedKategori }}</strong></span>
            <button @click="selectedKategori = 'Semua Kategori'; handleFilterChange()" class="hover:text-white cursor-pointer ml-1 font-bold">&times;</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 sm:mt-8">
      <div class="bg-white rounded-2xl border border-slate-200/90 shadow-sm p-4 sm:p-6 space-y-6">
        
        <!-- Controls Bar (Filter Tahun, Kategori, Show entries, Search) -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 pb-2 border-b border-slate-100">
          <!-- Left Controls (Dropdowns) -->
          <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-xs sm:text-sm font-medium text-slate-700">
            <!-- Filter Tahun -->
            <div class="flex items-center gap-2">
              <label for="filter-tahun" class="font-semibold text-slate-700 shrink-0">Tahun:</label>
              <div class="relative">
                <select 
                  id="filter-tahun"
                  v-model="selectedTahun"
                  @change="handleFilterChange"
                  class="appearance-none bg-slate-50 border border-emerald-300/80 hover:border-emerald-500 rounded-xl px-3.5 py-2 pr-8 text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition cursor-pointer"
                >
                  <option v-for="t in allTahunOptions" :key="t" :value="t">{{ t }}</option>
                </select>
                <svg class="w-4 h-4 text-slate-500 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

            <!-- Filter Kategori -->
            <div class="flex items-center gap-2">
              <label for="filter-kategori" class="font-semibold text-slate-700 shrink-0">Kategori:</label>
              <div class="relative">
                <select 
                  id="filter-kategori"
                  v-model="selectedKategori"
                  @change="handleFilterChange"
                  class="appearance-none bg-slate-50 border border-emerald-400 hover:border-emerald-600 rounded-xl px-3.5 py-2 pr-8 text-xs sm:text-sm font-semibold text-emerald-950 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition cursor-pointer max-w-[210px] sm:max-w-xs truncate"
                >
                  <option v-for="cat in allKategoriOptions" :key="cat" :value="cat">{{ cat }}</option>
                </select>
                <svg class="w-4 h-4 text-emerald-700 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

            <!-- Show Entries -->
            <div class="flex items-center gap-2">
              <label for="filter-show" class="font-semibold text-slate-700 shrink-0">Show:</label>
              <div class="relative">
                <select 
                  id="filter-show"
                  v-model.number="perPage"
                  @change="currentPage = 1"
                  class="appearance-none bg-slate-50 border border-emerald-300/80 hover:border-emerald-500 rounded-xl px-3.5 py-2 pr-8 text-xs sm:text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white transition cursor-pointer"
                >
                  <option :value="10">10 entries</option>
                  <option :value="25">25 entries</option>
                  <option :value="50">50 entries</option>
                  <option :value="100">100 entries</option>
                </select>
                <svg class="w-4 h-4 text-slate-500 absolute right-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
              </div>
            </div>

            <!-- Reset Filter Button -->
            <button 
              v-if="hasActiveFilters"
              @click="resetFilters"
              type="button"
              class="text-xs text-rose-600 hover:text-rose-800 font-semibold underline cursor-pointer ml-1"
            >
              Reset Filter
            </button>
          </div>

          <!-- Right Controls (Search Input + Search Button) -->
          <div class="flex items-center w-full lg:w-auto">
            <div class="relative flex items-stretch w-full sm:w-72">
              <input 
                v-model="searchQuery"
                @input="currentPage = 1"
                @keyup.enter="currentPage = 1"
                type="text" 
                placeholder="Search Here..." 
                class="w-full pl-3.5 pr-8 py-2 rounded-l-xl bg-slate-50 border border-slate-300 text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:bg-white focus:border-emerald-600 transition"
              />
              <button 
                v-if="searchQuery" 
                @click="searchQuery = ''; currentPage = 1" 
                class="absolute right-12 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer p-1"
                title="Hapus pencarian"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
              <button 
                type="button"
                @click="currentPage = 1"
                class="inline-flex items-center justify-center px-4 py-2 bg-emerald-700 hover:bg-emerald-800 active:bg-emerald-900 text-white rounded-r-xl font-medium transition cursor-pointer shadow-xs"
                title="Cari"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="py-16">
          <LoadingSpinner />
        </div>

        <!-- Table View Section -->
        <template v-else>
          <div class="overflow-x-auto rounded-xl border border-slate-200 shadow-xs">
            <table class="w-full text-left border-collapse">
              <!-- Table Header: Solid Emerald Green -->
              <thead class="bg-emerald-800 text-white">
                <tr>
                  <!-- NO -->
                  <th 
                    @click="handleSort('id')" 
                    scope="col"
                    class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-center w-14 cursor-pointer hover:bg-emerald-900 transition select-none"
                    title="Urutkan berdasarkan Nomor"
                  >
                    <div class="inline-flex items-center gap-1">
                      <span>NO</span>
                      <span class="text-[10px] opacity-80" :class="sortField === 'id' ? 'opacity-100 font-extrabold text-amber-300' : ''">
                        {{ sortField === 'id' ? (sortOrder === 'asc' ? '▲' : '▼') : '▲▼' }}
                      </span>
                    </div>
                  </th>

                  <!-- JUDUL DOKUMEN -->
                  <th 
                    @click="handleSort('judul')" 
                    scope="col"
                    class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider min-w-[280px] cursor-pointer hover:bg-emerald-900 transition select-none"
                    title="Urutkan berdasarkan Judul Dokumen"
                  >
                    <div class="inline-flex items-center gap-1.5">
                      <span>JUDUL DOKUMEN</span>
                      <span class="text-[10px] opacity-80" :class="sortField === 'judul' ? 'opacity-100 font-extrabold text-amber-300' : ''">
                        {{ sortField === 'judul' ? (sortOrder === 'asc' ? '▲' : '▼') : '▲▼' }}
                      </span>
                    </div>
                  </th>

                  <!-- PDF -->
                  <th scope="col" class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-center w-24">
                    PDF
                  </th>

                  <!-- ZIP -->
                  <th scope="col" class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-center w-20">
                    ZIP
                  </th>

                  <!-- KATEGORI -->
                  <th 
                    @click="handleSort('kategori')" 
                    scope="col"
                    class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-center min-w-[150px] cursor-pointer hover:bg-emerald-900 transition select-none"
                    title="Urutkan berdasarkan Kategori"
                  >
                    <div class="inline-flex items-center gap-1.5 justify-center">
                      <span>KATEGORI</span>
                      <span class="text-[10px] opacity-80" :class="sortField === 'kategori' ? 'opacity-100 font-extrabold text-amber-300' : ''">
                        {{ sortField === 'kategori' ? (sortOrder === 'asc' ? '▲' : '▼') : '▲▼' }}
                      </span>
                    </div>
                  </th>

                  <!-- TANGGAL -->
                  <th 
                    @click="handleSort('tanggal')" 
                    scope="col"
                    class="py-3.5 px-4 text-xs font-bold uppercase tracking-wider text-center w-32 cursor-pointer hover:bg-emerald-900 transition select-none"
                    title="Urutkan berdasarkan Tanggal"
                  >
                    <div class="inline-flex items-center gap-1.5 justify-center">
                      <span>TANGGAL</span>
                      <span class="text-[10px] opacity-80" :class="sortField === 'tanggal' ? 'opacity-100 font-extrabold text-amber-300' : ''">
                        {{ sortField === 'tanggal' ? (sortOrder === 'asc' ? '▲' : '▼') : '▲▼' }}
                      </span>
                    </div>
                  </th>
                </tr>
              </thead>

              <!-- Table Body -->
              <tbody class="divide-y divide-slate-200/80 text-xs sm:text-sm bg-white">
                <tr 
                  v-for="(doc, idx) in paginatedDocuments" 
                  :key="doc.id"
                  class="hover:bg-emerald-50/40 transition-colors group"
                >
                  <!-- NO -->
                  <td class="py-4 px-4 text-center font-semibold text-slate-600 align-middle">
                    {{ (currentPage - 1) * perPage + idx + 1 }}
                  </td>

                  <!-- JUDUL DOKUMEN -->
                  <td class="py-4 px-4 align-middle">
                    <div class="flex flex-col">
                      <button 
                        @click="openPreviewModal(doc)" 
                        class="text-left font-bold text-slate-900 hover:text-emerald-700 transition cursor-pointer text-xs sm:text-sm leading-snug"
                      >
                        {{ doc.judul }}
                      </button>
                      
                      <div class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                        <span v-if="doc.nomor_dokumen" class="font-medium text-slate-600 mr-1.5 font-mono">
                          [{{ doc.nomor_dokumen }}]
                        </span>
                        <span>{{ doc.deskripsi || 'Dokumen publikasi resmi kedinasan Kelurahan Kraksaan Wetan.' }}</span>
                      </div>

                      <div class="flex items-center gap-2 mt-1.5 text-[10px] text-slate-400">
                        <span v-if="doc.ukuran_file">&bull; {{ doc.ukuran_file }}</span>
                        <span>&bull; Diunduh {{ doc.diunduh || 0 }} kali</span>
                        <span v-if="doc.tahun">&bull; TA {{ doc.tahun }}</span>
                      </div>
                    </div>
                  </td>

                  <!-- PDF Column (Preview Pop-up & Quick Download) -->
                  <td class="py-4 px-4 text-center align-middle">
                    <div class="inline-flex items-center justify-center gap-1.5">
                      <button 
                        @click="openPreviewModal(doc)"
                        type="button"
                        class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition active:scale-95 cursor-pointer"
                        :title="'Lihat Pratinjau Pop-up PDF: ' + doc.judul"
                      >
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>PDF</span>
                      </button>

                      <button 
                        @click.stop="handleDownload(doc)"
                        type="button"
                        class="p-1 rounded-full text-slate-400 hover:text-emerald-700 hover:bg-emerald-50 transition cursor-pointer"
                        :title="'Unduh Berkas Langsung: ' + doc.judul"
                      >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                      </button>
                    </div>
                  </td>

                  <!-- ZIP Column -->
                  <td class="py-4 px-4 text-center align-middle">
                    <button 
                      v-if="isZipFile(doc)"
                      @click="handleDownload(doc)"
                      type="button"
                      class="inline-flex items-center justify-center gap-1 px-3 py-1.5 rounded-full bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-xs transition active:scale-95 cursor-pointer"
                      :title="'Unduh Arsip ZIP: ' + doc.judul"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                      </svg>
                      <span>ZIP</span>
                    </button>
                    <span v-else class="text-slate-400 font-bold select-none text-base">-</span>
                  </td>

                  <!-- KATEGORI Column -->
                  <td class="py-4 px-4 text-center align-middle">
                    <button 
                      @click="selectedKategori = doc.kategori; handleFilterChange()"
                      class="inline-block px-3 py-1 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-semibold transition cursor-pointer text-center max-w-[160px] truncate"
                      :title="'Filter dokumen dengan kategori: ' + doc.kategori"
                    >
                      {{ doc.kategori || 'Umum' }}
                    </button>
                  </td>

                  <!-- TANGGAL Column -->
                  <td class="py-4 px-4 text-center align-middle font-medium text-slate-700 whitespace-nowrap text-xs sm:text-sm">
                    {{ formatDisplayDate(doc) }}
                  </td>
                </tr>

                <!-- Empty State Row -->
                <tr v-if="filteredDocuments.length === 0">
                  <td colspan="6" class="py-12 px-4 text-center">
                    <div class="flex flex-col items-center justify-center max-w-sm mx-auto space-y-3">
                      <div class="w-14 h-14 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                      </div>
                      <div class="text-sm font-bold text-slate-700">Tidak ada dokumen yang sesuai</div>
                      <p class="text-xs text-slate-500">
                        Tidak ditemukan berkas dokumen dengan filter atau kata kunci yang Anda masukkan. Silakan ubah filter atau reset.
                      </p>
                      <button 
                        @click="resetFilters" 
                        class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold transition cursor-pointer shadow-xs"
                      >
                        Reset Semua Filter
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Table Footer: Pagination & Info -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2 text-xs sm:text-sm text-slate-600">
            <!-- Entry counter text -->
            <div>
              <span v-if="filteredDocuments.length > 0">
                Showing <strong>{{ showingFrom }}</strong> to <strong>{{ showingTo }}</strong> of <strong>{{ filteredDocuments.length }}</strong> entries
              </span>
              <span v-else>
                Showing 0 entries
              </span>
            </div>

            <!-- Pagination Buttons -->
            <div v-if="totalPages > 1" class="flex items-center gap-1">
              <!-- Previous Button -->
              <button 
                @click="goToPage(currentPage - 1)" 
                :disabled="currentPage === 1"
                class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer"
              >
                Previous
              </button>

              <!-- Page Numbers -->
              <template v-for="page in visiblePages" :key="page">
                <span v-if="page === '...'" class="px-2 py-1 text-slate-400">...</span>
                <button 
                  v-else
                  @click="goToPage(page)"
                  class="px-3 py-1.5 rounded-lg border text-xs font-bold transition cursor-pointer"
                  :class="currentPage === page ? 'bg-emerald-700 text-white border-emerald-700 shadow-xs' : 'border-slate-200 text-slate-700 hover:bg-slate-100'"
                >
                  {{ page }}
                </button>
              </template>

              <!-- Next Button -->
              <button 
                @click="goToPage(currentPage + 1)" 
                :disabled="currentPage === totalPages"
                class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-semibold text-slate-700 hover:bg-slate-100 disabled:opacity-40 disabled:cursor-not-allowed transition cursor-pointer"
              >
                Next
              </button>
            </div>
          </div>
        </template>
      </div>
    </main>

    <!-- Modal Pratinjau Dokumen PDF Pop-up -->
    <Teleport to="body">
      <div 
        v-if="selectedPreviewDoc" 
        class="fixed inset-0 z-50 flex items-center justify-center p-2 sm:p-4 md:p-6 bg-slate-950/80 backdrop-blur-xs animate-fade-in"
        @click.self="closePreviewModal"
      >
        <div class="bg-white rounded-2xl sm:rounded-3xl max-w-5xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[96vh] overflow-hidden">
          <!-- Modal Top Bar / Header -->
          <div class="flex items-center justify-between px-4 sm:px-6 py-3.5 border-b border-slate-200 bg-slate-50/90 shrink-0">
            <div class="flex items-center gap-3 min-w-0 pr-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <div class="min-w-0">
                <div class="flex items-center gap-2 mb-0.5">
                  <span class="inline-block px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-800 font-bold text-[10px] uppercase">
                    {{ selectedPreviewDoc.kategori || 'Dokumen Publik' }}
                  </span>
                  <span v-if="selectedPreviewDoc.nomor_dokumen" class="text-xs text-slate-500 font-mono hidden sm:inline truncate">
                    No: {{ selectedPreviewDoc.nomor_dokumen }}
                  </span>
                </div>
                <h3 class="text-xs sm:text-sm md:text-base font-bold text-slate-900 truncate" :title="selectedPreviewDoc.judul">
                  {{ selectedPreviewDoc.judul }}
                </h3>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2 shrink-0">
              <!-- Buka Tab Baru -->
              <a 
                :href="`/api/dokumen/${selectedPreviewDoc.id}/pratinjau`" 
                target="_blank" 
                rel="noopener noreferrer"
                class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-100 text-xs font-semibold transition"
                title="Buka dokumen di tab jendela baru"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Tab Baru</span>
              </a>

              <!-- Unduh PDF Button -->
              <button 
                @click="handleDownload(selectedPreviewDoc)" 
                type="button" 
                class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-bold transition shadow-xs cursor-pointer"
                title="Unduh berkas PDF ini"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span class="hidden sm:inline">Unduh PDF</span>
              </button>

              <!-- Close Button -->
              <button 
                @click="closePreviewModal" 
                class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                aria-label="Tutup pratinjau"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
          </div>

          <!-- Modal Body: Embedded PDF Viewer -->
          <div class="relative flex-1 bg-slate-900 w-full min-h-[55vh] max-h-[72vh] flex flex-col">
            <iframe 
              :src="`/api/dokumen/${selectedPreviewDoc.id}/pratinjau#toolbar=1&navpanes=0&view=FitH`"
              class="w-full flex-1 border-0 bg-slate-900"
              title="Pratinjau Dokumen PDF"
            >
              <div class="flex flex-col items-center justify-center h-full p-8 text-center text-white space-y-3">
                <p class="text-sm text-slate-300">Pratinjau PDF tidak dapat ditampilkan langsung di peramban ini.</p>
                <a 
                  :href="`/api/dokumen/${selectedPreviewDoc.id}/pratinjau`" 
                  target="_blank" 
                  class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs"
                >
                  Buka Dokumen PDF di Tab Baru
                </a>
              </div>
            </iframe>
          </div>

          <!-- Modal Bottom Bar / Footer -->
          <div class="flex flex-col sm:flex-row items-center justify-between gap-3 px-4 sm:px-6 py-3 border-t border-slate-200 bg-slate-50/90 text-xs text-slate-600 shrink-0">
            <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-[11px] sm:text-xs text-slate-500">
              <span v-if="selectedPreviewDoc.tahun">Tahun: <strong class="text-slate-700">{{ selectedPreviewDoc.tahun }}</strong></span>
              <span v-if="selectedPreviewDoc.ukuran_file">&bull; Ukuran: <strong class="text-slate-700">{{ selectedPreviewDoc.ukuran_file }}</strong></span>
              <span>&bull; Diunduh: <strong class="text-slate-700">{{ selectedPreviewDoc.diunduh || 0 }}x</strong></span>
              <span>&bull; Tanggal: <strong class="text-slate-700">{{ formatDisplayDate(selectedPreviewDoc) }}</strong></span>
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto justify-end">
              <button 
                @click="closePreviewModal" 
                type="button" 
                class="px-4 py-1.5 rounded-xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold transition cursor-pointer"
              >
                Tutup
              </button>
              <button 
                @click="handleDownload(selectedPreviewDoc)" 
                type="button" 
                class="px-4 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold transition shadow-xs cursor-pointer inline-flex items-center gap-1.5"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Unduh PDF</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Breadcrumb from '../components/Breadcrumb.vue';
import LoadingSpinner from '../components/LoadingSpinner.vue';
import { KelurahanService } from '../services/api';
import { useToast } from '../composables/useToast';

const route = useRoute();
const router = useRouter();
const toast = useToast();

const loading = ref(true);
const documents = ref([]);
const meta = ref({ total: 0, kategori_list: [], tahun_list: [] });
const profil = ref({});

// Filters
const selectedTahun = ref('Semua Tahun');
const selectedKategori = ref('Semua Kategori');
const searchQuery = ref('');
const perPage = ref(10);
const currentPage = ref(1);

// Sorting
const sortField = ref('id'); // 'id' | 'judul' | 'kategori' | 'tanggal'
const sortOrder = ref('asc'); // 'asc' | 'desc'

// Modal Preview
const selectedPreviewDoc = ref(null);

const heroImage = computed(() => {
  return profil.value?.hero_image || '/images/hero-bromo-vector.jpg';
});

const totalDownloads = computed(() => {
  return documents.value.reduce((sum, d) => sum + (parseInt(d.diunduh, 10) || 0), 0);
});

// Category list based on screenshot + database
const baseCategories = [
  'Semua Kategori',
  'Musrenbang',
  'UMKM',
  'Renstra & Renja',
  'SK Kelembagaan',
  'Regulasi & Kebijakan',
];

const allKategoriOptions = computed(() => {
  const metaCats = meta.value.kategori_list || [];
  const docCats = documents.value.map(d => d.kategori).filter(Boolean);
  const otherCats = Array.from(new Set([...metaCats, ...docCats]))
    .filter(c => !baseCategories.includes(c));
  
  return [...baseCategories, ...otherCats];
});

const allTahunOptions = computed(() => {
  const metaTahun = meta.value.tahun_list || [];
  const docTahun = documents.value.map(d => d.tahun).filter(Boolean);
  const combined = Array.from(new Set([...metaTahun, ...docTahun])).sort().reverse();
  if (combined.length === 0) {
    return ['Semua Tahun', '2026', '2025'];
  }
  return ['Semua Tahun', ...combined];
});

const hasActiveFilters = computed(() => {
  return selectedTahun.value !== 'Semua Tahun' || 
    selectedKategori.value !== 'Semua Kategori' || 
    searchQuery.value.trim() !== '';
});

// Filtered Documents
const filteredDocuments = computed(() => {
  let list = [...documents.value];

  // Filter Tahun
  if (selectedTahun.value !== 'Semua Tahun') {
    list = list.filter(d => String(d.tahun) === String(selectedTahun.value));
  }

  // Filter Kategori
  if (selectedKategori.value !== 'Semua Kategori') {
    list = list.filter(d => (d.kategori || '').trim().toLowerCase() === selectedKategori.value.trim().toLowerCase());
  }

  // Search Query
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.trim().toLowerCase();
    list = list.filter(d => {
      const matchJudul = (d.judul || '').toLowerCase().includes(q);
      const matchNomor = (d.nomor_dokumen || '').toLowerCase().includes(q);
      const matchKategori = (d.kategori || '').toLowerCase().includes(q);
      const matchDeskripsi = (d.deskripsi || '').toLowerCase().includes(q);
      return matchJudul || matchNomor || matchKategori || matchDeskripsi;
    });
  }

  // Sorting
  list.sort((a, b) => {
    let aVal, bVal;
    if (sortField.value === 'id') {
      aVal = a.id || 0;
      bVal = b.id || 0;
    } else if (sortField.value === 'judul') {
      aVal = (a.judul || '').toLowerCase();
      bVal = (b.judul || '').toLowerCase();
    } else if (sortField.value === 'kategori') {
      aVal = (a.kategori || '').toLowerCase();
      bVal = (b.kategori || '').toLowerCase();
    } else if (sortField.value === 'tanggal') {
      aVal = a.tanggal_publikasi || a.created_at || '';
      bVal = b.tanggal_publikasi || b.created_at || '';
    }

    if (aVal < bVal) return sortOrder.value === 'asc' ? -1 : 1;
    if (aVal > bVal) return sortOrder.value === 'asc' ? 1 : -1;
    return 0;
  });

  return list;
});

// Pagination
const totalPages = computed(() => {
  return Math.ceil(filteredDocuments.value.length / perPage.value) || 1;
});

const paginatedDocuments = computed(() => {
  const start = (currentPage.value - 1) * perPage.value;
  return filteredDocuments.value.slice(start, start + perPage.value);
});

const showingFrom = computed(() => {
  if (filteredDocuments.value.length === 0) return 0;
  return (currentPage.value - 1) * perPage.value + 1;
});

const showingTo = computed(() => {
  return Math.min(currentPage.value * perPage.value, filteredDocuments.value.length);
});

const visiblePages = computed(() => {
  const total = totalPages.value;
  const current = currentPage.value;
  if (total <= 7) {
    return Array.from({ length: total }, (_, i) => i + 1);
  }

  const pages = [];
  pages.push(1);
  if (current > 3) {
    pages.push('...');
  }
  const start = Math.max(2, current - 1);
  const end = Math.min(total - 1, current + 1);
  for (let i = start; i <= end; i++) {
    pages.push(i);
  }
  if (current < total - 2) {
    pages.push('...');
  }
  pages.push(total);
  return pages;
});

const goToPage = (page) => {
  if (page >= 1 && page <= totalPages.value) {
    currentPage.value = page;
    window.scrollTo({ top: 300, behavior: 'smooth' });
  }
};

const handleSort = (field) => {
  if (sortField.value === field) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortOrder.value = 'asc';
  }
  currentPage.value = 1;
};

const handleFilterChange = () => {
  currentPage.value = 1;
  const query = { ...route.query };

  if (selectedKategori.value && selectedKategori.value !== 'Semua Kategori') {
    query.kategori = selectedKategori.value;
  } else {
    delete query.kategori;
  }

  if (selectedTahun.value && selectedTahun.value !== 'Semua Tahun') {
    query.tahun = selectedTahun.value;
  } else {
    delete query.tahun;
  }

  router.replace({ query }).catch(() => {});
};

const resetFilters = () => {
  selectedTahun.value = 'Semua Tahun';
  selectedKategori.value = 'Semua Kategori';
  searchQuery.value = '';
  currentPage.value = 1;
  router.replace({ query: {} }).catch(() => {});
};

const isZipFile = (doc) => {
  const filename = (doc.file || doc.nama_file_asli || '').toLowerCase();
  return filename.endsWith('.zip') || filename.endsWith('.rar') || filename.endsWith('.7z');
};

const formatDisplayDate = (doc) => {
  if (doc.tanggal_publikasi) {
    const parts = doc.tanggal_publikasi.split('-');
    if (parts.length === 3) {
      return `${parts[2]}-${parts[1]}-${parts[0]}`;
    }
  }
  if (doc.created_at) {
    const d = new Date(doc.created_at);
    const day = String(d.getDate()).padStart(2, '0');
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const year = d.getFullYear();
    return `${day}-${month}-${year}`;
  }
  return '-';
};

const openPreviewModal = (doc) => {
  selectedPreviewDoc.value = doc;
};

const closePreviewModal = () => {
  selectedPreviewDoc.value = null;
};

const handleDownload = (doc) => {
  if (!doc || !doc.id) return;
  const downloadUrl = KelurahanService.getDokumenUnduhUrl(doc.id);
  
  toast.info(`Mengunduh berkas "${doc.judul}"...`, 'Memulai Unduhan');

  const link = document.createElement('a');
  link.href = downloadUrl;
  link.setAttribute('download', `${doc.judul || 'dokumen'}.pdf`);
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  doc.diunduh = (doc.diunduh || 0) + 1;
};

const fetchDocuments = async () => {
  loading.value = true;
  try {
    const res = await KelurahanService.getDokumen();
    documents.value = res.data || [];
    meta.value = res.meta || { total: documents.value.length, kategori_list: [], tahun_list: [] };
  } catch (err) {
    console.error('Failed to load documents:', err);
    documents.value = [];
  } finally {
    loading.value = false;
  }
};

const applyQueryFromRoute = () => {
  if (route.query.kategori) {
    const matched = allKategoriOptions.value.find(
      c => c.toLowerCase() === route.query.kategori.toLowerCase()
    );
    selectedKategori.value = matched || route.query.kategori;
  } else {
    selectedKategori.value = 'Semua Kategori';
  }

  if (route.query.tahun) {
    selectedTahun.value = route.query.tahun;
  }
};

watch(() => route.query, () => {
  applyQueryFromRoute();
  currentPage.value = 1;
});

onMounted(async () => {
  try {
    const p = await KelurahanService.getProfil();
    if (p) profil.value = p;
  } catch (err) {
    console.error('Failed to load profil:', err);
  }

  await fetchDocuments();
  applyQueryFromRoute();
});
</script>
