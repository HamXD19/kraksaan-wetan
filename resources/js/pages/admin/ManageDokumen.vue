<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Dokumen Publik (PDF)</h2>
        <p class="text-xs text-slate-500 mt-1">Unggah dan atur pengelompokan dokumen berdasarkan kategori, subkategori, periode pelaporan (5 tahunan, tahunan, semesteran, triwulanan, bulanan, sewaktu-waktu), dan tahun.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Dokumen PDF
      </button>
    </div>

    <!-- Alert Notifications -->
    <div v-if="successMsg" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ successMsg }}</span>
      </div>
      <button @click="successMsg = ''" class="text-emerald-700 font-bold hover:text-emerald-900 cursor-pointer">&times;</button>
    </div>

    <div v-if="errorMsg" class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-xs font-semibold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span>{{ errorMsg }}</span>
      </div>
      <button @click="errorMsg = ''" class="text-red-700 font-bold hover:text-red-900 cursor-pointer">&times;</button>
    </div>

    <!-- KPI Summary Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-sm">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div>
          <div class="text-2xl font-extrabold text-slate-900">{{ summary.total || 0 }}</div>
          <div class="text-xs text-slate-500 font-medium">Total Dokumen Publik</div>
        </div>
      </div>

      <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-bold text-sm">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div>
          <div class="text-2xl font-extrabold text-slate-900">{{ summary.aktif || 0 }}</div>
          <div class="text-xs text-slate-500 font-medium">Dokumen Aktif & Tampil</div>
        </div>
      </div>

      <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-sm">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        </div>
        <div>
          <div class="text-2xl font-extrabold text-slate-900">{{ summary.total_unduhan || 0 }}</div>
          <div class="text-xs text-slate-500 font-medium">Total Diunduh Warga</div>
        </div>
      </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs space-y-3">
      <div class="flex flex-col lg:flex-row gap-3 items-stretch lg:items-center justify-between">
        <!-- Search Input -->
        <div class="relative flex-1">
          <input 
            v-model="searchQuery" 
            @input="handleSearchInput"
            type="text" 
            placeholder="Cari judul, nomor surat, subkategori, kata kunci..." 
            class="w-full pl-9 pr-8 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-600"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <button 
            v-if="searchQuery" 
            @click="searchQuery = ''; fetchDokumen()" 
            class="absolute right-2.5 top-2.5 text-slate-400 hover:text-slate-600 text-xs font-bold"
          >
            &times;
          </button>
        </div>

        <!-- Quick Folder Period Filter Bar -->
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none border-b border-slate-100">
          <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 whitespace-nowrap pr-1 flex items-center gap-1">
            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
            <span>Folder:</span>
          </span>
          <button 
            type="button"
            @click="filterPeriode = 'Semua'; fetchDokumen()"
            class="px-2.5 py-1 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer"
            :class="filterPeriode === 'Semua' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            Semua Folder
          </button>
          <button 
            v-for="p in ['Tahunan', 'Triwulanan', 'Semesteran', '5 Tahunan', 'Bulanan', 'Sewaktu-waktu']" 
            :key="p"
            type="button"
            @click="filterPeriode = p; fetchDokumen()"
            class="px-2.5 py-1 rounded-xl text-xs font-bold whitespace-nowrap transition cursor-pointer"
            :class="filterPeriode === p ? 'bg-emerald-700 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
          >
            📁 {{ p }}
          </button>
        </div>

        <!-- Filter Selects Grid -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
          <!-- Kategori -->
          <select 
            v-model="filterKategori" 
            @change="onFilterKategoriChange"
            class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
          >
            <option value="Semua">Semua Kategori</option>
            <option v-for="k in availableFilterKategoris" :key="k" :value="k">{{ k }}</option>
          </select>

          <!-- Subkategori -->
          <select 
            v-model="filterSubkategori" 
            @change="fetchDokumen"
            class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
          >
            <option value="Semua">Semua Subkategori</option>
            <option v-for="s in availableFilterSubkategoris" :key="s" :value="s">{{ s }}</option>
          </select>

          <!-- Periode -->
          <select 
            v-model="filterPeriode" 
            @change="fetchDokumen"
            class="px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
          >
            <option value="Semua">Semua Periode</option>
            <option value="5 Tahunan">5 Tahunan</option>
            <option value="Tahunan">Tahunan</option>
            <option value="Semesteran">Semesteran</option>
            <option value="Triwulanan">Triwulanan</option>
            <option value="Bulanan">Bulanan</option>
            <option value="Sewaktu-waktu">Sewaktu-waktu</option>
          </select>

          <!-- Tahun / Status -->
          <div class="flex items-center gap-1.5">
            <select 
              v-model="filterTahun" 
              @change="fetchDokumen"
              class="w-1/2 px-2.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
            >
              <option value="Semua">Tahun</option>
              <option v-for="y in availableFilterTahuns" :key="y" :value="y">{{ y }}</option>
            </select>

            <select 
              v-model="filterStatus" 
              @change="fetchDokumen"
              class="w-1/2 px-2.5 py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-semibold text-slate-700"
            >
              <option value="semua">Status</option>
              <option value="aktif">Aktif</option>
              <option value="nonaktif">Nonaktif</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Active Filters Tag Bar -->
      <div v-if="hasActiveFilter" class="flex items-center gap-2 pt-2 border-t border-slate-100 text-xs flex-wrap">
        <span class="text-slate-400 font-medium">Filter aktif:</span>
        <span v-if="filterKategori !== 'Semua'" class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-semibold flex items-center gap-1">
          Kategori: {{ filterKategori }}
          <button @click="filterKategori = 'Semua'; onFilterKategoriChange()" class="hover:text-emerald-900">&times;</button>
        </span>
        <span v-if="filterSubkategori !== 'Semua'" class="px-2 py-0.5 rounded-md bg-blue-50 text-blue-700 font-semibold flex items-center gap-1">
          Subkategori: {{ filterSubkategori }}
          <button @click="filterSubkategori = 'Semua'; fetchDokumen()" class="hover:text-blue-900">&times;</button>
        </span>
        <span v-if="filterPeriode !== 'Semua'" class="px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-semibold flex items-center gap-1">
          Periode: {{ filterPeriode }}
          <button @click="filterPeriode = 'Semua'; fetchDokumen()" class="hover:text-purple-900">&times;</button>
        </span>
        <span v-if="filterTahun !== 'Semua'" class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 font-semibold flex items-center gap-1">
          Tahun: {{ filterTahun }}
          <button @click="filterTahun = 'Semua'; fetchDokumen()" class="hover:text-slate-900">&times;</button>
        </span>
        <span v-if="filterStatus !== 'semua'" class="px-2 py-0.5 rounded-md bg-amber-50 text-amber-700 font-semibold flex items-center gap-1">
          Status: {{ filterStatus }}
          <button @click="filterStatus = 'semua'; fetchDokumen()" class="hover:text-amber-900">&times;</button>
        </span>
        <button 
          @click="resetFilters" 
          class="text-red-600 hover:text-red-800 font-bold ml-auto cursor-pointer"
        >
          Reset Semua
        </button>
      </div>
    </div>

    <!-- Data Table -->
    <LoadingSpinner v-if="loading" />
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
            <tr>
              <th class="py-3.5 px-4 min-w-[220px]">Dokumen & Judul</th>
              <th class="py-3.5 px-3">Kategori</th>
              <th class="py-3.5 px-3">Subkategori</th>
              <th class="py-3.5 px-3">Periode</th>
              <th class="py-3.5 px-3">Waktu / Pelaporan</th>
              <th class="py-3.5 px-3 text-center">Diunduh</th>
              <th class="py-3.5 px-3 text-center">Status</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-if="dokumenList.length === 0">
              <td colspan="8" class="py-12 text-center text-slate-400">
                <div class="max-w-sm mx-auto space-y-2">
                  <p class="font-bold text-slate-600">Tidak ada dokumen yang sesuai</p>
                  <p class="text-xs text-slate-400">Silakan ubah kata kunci pencarian atau sesuaikan filter di atas.</p>
                </div>
              </td>
            </tr>
            <tr v-for="d in dokumenList" :key="d.id" class="hover:bg-slate-50/80 transition">
              <!-- Judul & Info -->
              <td class="py-3 px-4">
                <div class="flex items-start gap-3">
                  <div class="w-9 h-9 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-200/60 font-black text-[11px]">
                    PDF
                  </div>
                  <div>
                    <p class="font-bold text-slate-900 leading-snug">{{ d.judul }}</p>
                    <div class="flex items-center gap-2 mt-0.5 text-[11px]">
                      <span v-if="d.nomor_dokumen" class="font-mono text-emerald-700 font-semibold">
                        No: {{ d.nomor_dokumen }}
                      </span>
                      <span v-if="d.ukuran_file" class="text-slate-400">
                        • {{ d.ukuran_file }}
                      </span>
                      <span v-if="d.tanggal_format || d.tanggal" class="text-slate-400 hidden sm:inline">
                        • {{ d.tanggal_format || d.tanggal }}
                      </span>
                    </div>
                  </div>
                </div>
              </td>

              <!-- Kategori -->
              <td class="py-3 px-3">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold text-[11px] whitespace-nowrap">
                  {{ d.kategori || 'Umum' }}
                </span>
              </td>

              <!-- Subkategori -->
              <td class="py-3 px-3">
                <span v-if="d.subkategori" class="inline-flex items-center px-2 py-0.5 rounded-md bg-blue-50 text-blue-800 font-medium text-[11px] whitespace-nowrap">
                  {{ d.subkategori }}
                </span>
                <span v-else class="text-slate-400 italic text-[11px]">-</span>
              </td>

              <!-- Periode -->
              <td class="py-3 px-3">
                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
                  :class="{
                    'bg-purple-100 text-purple-800': d.periode === '5 Tahunan',
                    'bg-indigo-100 text-indigo-800': d.periode === 'Tahunan',
                    'bg-amber-100 text-amber-800': d.periode === 'Semesteran',
                    'bg-cyan-100 text-cyan-800': d.periode === 'Triwulanan',
                    'bg-teal-100 text-teal-800': d.periode === 'Bulanan',
                    'bg-slate-100 text-slate-700': d.periode === 'Sewaktu-waktu' || !d.periode,
                  }"
                >
                  {{ d.periode || 'Tahunan' }}
                </span>
              </td>

              <!-- Waktu / Pelaporan -->
              <td class="py-3 px-3 font-semibold text-slate-800 whitespace-nowrap">
                {{ d.label_periode_lengkap || formatWaktuDisplay(d) }}
              </td>

              <!-- Diunduh -->
              <td class="py-3 px-3 text-center">
                <span class="inline-flex items-center gap-1 font-bold text-xs text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                  <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                  {{ d.diunduh || 0 }}
                </span>
              </td>

              <!-- Status Toggle -->
              <td class="py-3 px-3 text-center">
                <button 
                  @click="toggleStatus(d)"
                  class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition cursor-pointer"
                  :class="d.aktif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                >
                  {{ d.aktif ? 'Aktif' : 'Nonaktif' }}
                </button>
              </td>

              <!-- Aksi -->
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1.5">
                  <!-- Download Real Link -->
                  <a 
                    :href="d.file_url" 
                    target="_blank" 
                    class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition shadow-2xs"
                    title="Unduh / Buka Dokumen"
                  >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                  </a>

                  <!-- Edit -->
                  <button 
                    @click="openModal(d)" 
                    class="p-1.5 rounded-lg bg-amber-50 text-amber-800 hover:bg-amber-100 transition shadow-2xs cursor-pointer"
                    title="Edit Dokumen"
                  >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                  </button>

                  <!-- Delete -->
                  <button 
                    @click="confirmDelete(d)" 
                    class="p-1.5 rounded-lg bg-rose-50 text-rose-700 hover:bg-rose-100 transition shadow-2xs cursor-pointer"
                    title="Hapus Dokumen"
                  >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form (Tambah / Edit) Widescreen Ergonomis Bebas Zoom Out -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs">
      <div class="bg-white rounded-2xl sm:rounded-3xl max-w-5xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
        <!-- Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">
              {{ isEditing ? 'Edit Dokumen Publik (PDF)' : 'Tambah Dokumen Publik (PDF)' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola dokumen lengkap dengan kategori, subkategori, dan periode pelaporannya.</p>
          </div>
          <button @click="closeModal" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 cursor-pointer" title="Tutup">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="saveDokumen" class="flex flex-col flex-1 overflow-hidden">
          <!-- Inner Scrollable Two-Column Content -->
          <div class="p-5 sm:p-6 overflow-y-auto flex-1 text-xs sm:text-sm space-y-4">
            <!-- Error Inside Modal -->
            <div v-if="modalError" class="p-3.5 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs flex items-center justify-between shadow-2xs">
              <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <span class="font-medium">{{ modalError }}</span>
              </div>
              <button type="button" @click="modalError = ''" class="text-red-500 hover:text-red-700 font-bold ml-2 cursor-pointer">&times;</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
              <!-- Left Column: Identitas & Klasifikasi Dokumen (cols-7) -->
              <div class="lg:col-span-7 space-y-4">
                <!-- Judul Dokumen -->
                <div>
                  <label class="block font-bold text-slate-700 mb-1">Judul Dokumen <span class="text-red-500">*</span></label>
                  <input 
                    v-model="form.judul" 
                    type="text" 
                    required 
                    placeholder="Contoh: Laporan Realisasi Anggaran Pendapatan dan Belanja" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-xs sm:text-sm"
                  />
                </div>

                <!-- Kategori & Subkategori (Grid 2 Kolom) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                  <!-- Kategori -->
                  <div>
                    <div class="flex items-center justify-between mb-1">
                      <label class="block font-bold text-slate-700">Kategori Dokumen <span class="text-red-500">*</span></label>
                      <button 
                        type="button" 
                        @click="isCustomKategori = !isCustomKategori"
                        class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 cursor-pointer"
                      >
                        {{ isCustomKategori ? '← Dari Master' : '+ Tulis Baru' }}
                      </button>
                    </div>

                    <div v-if="!isCustomKategori">
                      <select 
                        v-model="form.kategori" 
                        @change="onFormKategoriChange"
                        required 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white text-xs sm:text-sm"
                      >
                        <option value="" disabled>-- Pilih Kategori --</option>
                        <option v-for="k in kategoriOptions" :key="k" :value="k">
                          {{ k }}
                        </option>
                      </select>
                    </div>
                    <div v-else>
                      <input 
                        v-model="form.customKategori" 
                        type="text" 
                        required 
                        placeholder="Ketik kategori baru..." 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-xs sm:text-sm"
                      />
                    </div>
                  </div>

                  <!-- Subkategori -->
                  <div>
                    <div class="flex items-center justify-between mb-1">
                      <label class="block font-bold text-slate-700">Subkategori (Opsional)</label>
                      <button 
                        type="button" 
                        @click="isCustomSubkategori = !isCustomSubkategori"
                        class="text-[11px] font-bold text-emerald-700 hover:text-emerald-900 cursor-pointer"
                      >
                        {{ isCustomSubkategori ? '← Dari Master' : '+ Tulis Baru' }}
                      </button>
                    </div>

                    <div v-if="!isCustomSubkategori">
                      <select 
                        v-model="form.subkategori" 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white text-xs sm:text-sm"
                      >
                        <option value="">-- Tanpa Subkategori --</option>
                        <option v-for="sub in formSubkategoriOptions" :key="sub" :value="sub">
                          {{ sub }}
                        </option>
                      </select>
                    </div>
                    <div v-else>
                      <input 
                        v-model="form.customSubkategori" 
                        type="text" 
                        placeholder="Ketik subkategori baru..." 
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-xs sm:text-sm"
                      />
                    </div>
                  </div>
                </div>

                <!-- Deskripsi / Catatan Dokumen -->
                <div>
                  <label class="block font-bold text-slate-700 mb-1">Deskripsi / Catatan Dokumen</label>
                  <RichTextEditor 
                    v-model="form.deskripsi" 
                    placeholder="Uraian isi dokumen, dasar hukum, atau petunjuk penggunaan berkas..." 
                    height="140px"
                    maxHeight="200px"
                  />
                </div>
              </div>

              <!-- Right Column: Media, Periode Dinamis, Meta (cols-5) -->
              <div class="lg:col-span-5 space-y-4">
                <!-- Upload Dokumen PDF Dropzone -->
                <div class="space-y-1.5">
                  <label class="block font-bold text-slate-700">Berkas Dokumen PDF <span class="text-red-500">*</span></label>
                  
                  <div 
                    class="p-4 rounded-2xl border-2 border-dashed transition text-center space-y-2"
                    :class="isDragging ? 'border-emerald-500 bg-emerald-100/60' : 'border-emerald-300 bg-emerald-50/40'"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="onDropFile"
                  >
                    <input 
                      type="file" 
                      ref="fileInputRef" 
                      accept="application/pdf,.pdf" 
                      @change="handleFileUpload" 
                      class="hidden" 
                    />

                    <div v-if="uploading" class="py-3 text-emerald-800 font-semibold flex items-center justify-center gap-2">
                      <svg class="animate-spin w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                      <span>Mengunggah berkas PDF...</span>
                    </div>

                    <div v-else>
                      <div v-if="form.file" class="bg-white p-2.5 rounded-xl border border-emerald-200 flex items-center justify-between gap-2 text-left">
                        <div class="flex items-center gap-2 min-w-0">
                          <div class="w-8 h-8 rounded-lg bg-red-100 text-red-700 font-bold text-[10px] flex items-center justify-center shrink-0">
                            PDF
                          </div>
                          <div class="min-w-0">
                            <div class="font-bold text-slate-800 truncate text-xs">
                              {{ form.nama_file_asli || form.file }}
                            </div>
                            <div class="text-[10px] text-slate-400">
                              {{ form.ukuran_file || 'Ukuran siap unduh' }}
                            </div>
                          </div>
                        </div>

                        <button 
                          type="button" 
                          @click="$refs.fileInputRef.click()"
                          class="px-2.5 py-1.5 rounded-lg bg-slate-100 text-slate-700 hover:bg-slate-200 text-[11px] font-bold cursor-pointer shrink-0"
                        >
                          Ganti
                        </button>
                      </div>

                      <div v-else class="py-2.5">
                        <svg class="w-7 h-7 text-emerald-600 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <p class="font-bold text-slate-700 text-xs">Pilih atau Seret Berkas PDF</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">Format .pdf kedinasan (Maks. 10MB)</p>
                        <button 
                          type="button" 
                          @click="$refs.fileInputRef.click()"
                          class="mt-2 px-3.5 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs cursor-pointer"
                        >
                          Pilih Berkas PDF
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Bagian Periode & Waktu Pelaporan Dinamis -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
                  <!-- Pilihan Frekuensi / Periode -->
                  <div>
                    <label class="block font-bold text-slate-700 mb-1">Periode Dokumen <span class="text-red-500">*</span></label>
                    <select 
                      v-model="form.periode" 
                      required 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 bg-white font-semibold text-xs"
                    >
                      <option value="5 Tahunan">5 Tahunan (Contoh: Rencana Strategis 2025–2029)</option>
                      <option value="Tahunan">Tahunan (Contoh: Laporan Tahunan 2026)</option>
                      <option value="Semesteran">Semesteran (Contoh: Semester I atau Semester II)</option>
                      <option value="Triwulanan">Triwulanan (Contoh: Triwulan I, II, III, IV)</option>
                      <option value="Bulanan">Bulanan (Contoh: Laporan Bulanan Januari–Desember)</option>
                      <option value="Sewaktu-waktu">Sewaktu-waktu / Insidental (Contoh: SK, Edaran, SOP)</option>
                    </select>
                  </div>

                  <!-- Logika Form Dinamis Berdasarkan Periode -->

                  <!-- KASUS 1: 5 Tahunan (Tahun Mulai & Tahun Selesai) -->
                  <div v-if="form.periode === '5 Tahunan'" class="grid grid-cols-2 gap-2.5 pt-1">
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Tahun Mulai <span class="text-red-500">*</span></label>
                      <input 
                        v-model="form.tahun" 
                        type="text" 
                        required 
                        placeholder="Contoh: 2025" 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-mono"
                      />
                    </div>
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Tahun Selesai <span class="text-red-500">*</span></label>
                      <input 
                        v-model="form.tahun_selesai" 
                        type="text" 
                        required 
                        placeholder="Contoh: 2029" 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-mono"
                      />
                    </div>
                  </div>

                  <!-- KASUS 2: Triwulanan (Tahun & Triwulan Ke) -->
                  <div v-else-if="form.periode === 'Triwulanan'" class="grid grid-cols-2 gap-2.5 pt-1">
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Tahun <span class="text-red-500">*</span></label>
                      <input 
                        v-model="form.tahun" 
                        type="text" 
                        required 
                        placeholder="2026" 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-mono"
                      />
                    </div>
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Periode Ke <span class="text-red-500">*</span></label>
                      <select 
                        v-model="form.periode_ke" 
                        required 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold"
                      >
                        <option value="" disabled>-- Pilih Triwulan --</option>
                        <option value="Triwulan I">Triwulan I (Jan - Mar)</option>
                        <option value="Triwulan II">Triwulan II (Apr - Jun)</option>
                        <option value="Triwulan III">Triwulan III (Jul - Sep)</option>
                        <option value="Triwulan IV">Triwulan IV (Okt - Des)</option>
                      </select>
                    </div>
                  </div>

                  <!-- KASUS 3: Semesteran (Tahun & Semester Ke) -->
                  <div v-else-if="form.periode === 'Semesteran'" class="grid grid-cols-2 gap-2.5 pt-1">
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Tahun <span class="text-red-500">*</span></label>
                      <input 
                        v-model="form.tahun" 
                        type="text" 
                        required 
                        placeholder="2026" 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-mono"
                      />
                    </div>
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Periode Ke <span class="text-red-500">*</span></label>
                      <select 
                        v-model="form.periode_ke" 
                        required 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold"
                      >
                        <option value="" disabled>-- Pilih Semester --</option>
                        <option value="Semester I">Semester I (Jan - Jun)</option>
                        <option value="Semester II">Semester II (Jul - Des)</option>
                      </select>
                    </div>
                  </div>

                  <!-- KASUS 4: Bulanan (Tahun & Bulan) -->
                  <div v-else-if="form.periode === 'Bulanan'" class="grid grid-cols-2 gap-2.5 pt-1">
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Tahun <span class="text-red-500">*</span></label>
                      <input 
                        v-model="form.tahun" 
                        type="text" 
                        required 
                        placeholder="2026" 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-mono"
                      />
                    </div>
                    <div>
                      <label class="block font-bold text-slate-700 mb-1 text-[11px]">Bulan Pelaporan <span class="text-red-500">*</span></label>
                      <select 
                        v-model="form.periode_ke" 
                        required 
                        class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-semibold"
                      >
                        <option value="" disabled>-- Pilih Bulan --</option>
                        <option value="Januari">Januari</option>
                        <option value="Februari">Februari</option>
                        <option value="Maret">Maret</option>
                        <option value="April">April</option>
                        <option value="Mei">Mei</option>
                        <option value="Juni">Juni</option>
                        <option value="Juli">Juli</option>
                        <option value="Agustus">Agustus</option>
                        <option value="September">September</option>
                        <option value="Oktober">Oktober</option>
                        <option value="November">November</option>
                        <option value="Desember">Desember</option>
                      </select>
                    </div>
                  </div>

                  <!-- KASUS 5: Tahunan (Hanya Tahun) -->
                  <div v-else-if="form.periode === 'Tahunan'" class="pt-1">
                    <label class="block font-bold text-slate-700 mb-1 text-[11px]">Tahun Dokumen <span class="text-red-500">*</span></label>
                    <input 
                      v-model="form.tahun" 
                      type="text" 
                      required 
                      placeholder="2026" 
                      class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-mono"
                    />
                  </div>

                  <!-- KASUS 6: Sewaktu-waktu / Insidental -->
                  <div v-else class="pt-1 space-y-1">
                    <label class="block font-bold text-slate-700 mb-1 text-[11px]">Tahun Rilis (Opsional)</label>
                    <input 
                      v-model="form.tahun" 
                      type="text" 
                      placeholder="Contoh: 2026" 
                      class="w-full px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs font-mono"
                    />
                    <p class="text-[10px] text-slate-400">Dokumen insidental tidak mewajibkan periode berkala khusus.</p>
                  </div>
                </div>

                <!-- Nomor Surat & Tanggal Publikasi -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div>
                    <label class="block font-bold text-slate-700 mb-1">Nomor Dokumen / Surat</label>
                    <input 
                      v-model="form.nomor_dokumen" 
                      type="text" 
                      placeholder="Contoh: SK/14/KW/2026" 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 font-mono text-xs"
                    />
                  </div>

                  <div>
                    <label class="block font-bold text-slate-700 mb-1">Tanggal Publikasi</label>
                    <input 
                      v-model="form.tanggal_publikasi" 
                      type="date" 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-600 text-xs"
                    />
                  </div>
                </div>

                <!-- Status Aktif Checkbox Card -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
                  <div class="flex items-center gap-2">
                    <input 
                      id="aktifCheckbox" 
                      v-model="form.aktif" 
                      type="checkbox" 
                      class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 cursor-pointer"
                    />
                    <label for="aktifCheckbox" class="text-xs font-semibold text-slate-700 select-none cursor-pointer">
                      Publikasikan dokumen ini pada menu unduhan portal publik
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Sticky Footer Action Bar -->
          <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
            <div class="text-xs text-slate-400">
              <span v-if="isEditing" class="text-emerald-700 font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Mode Edit Dokumen Publik
              </span>
              <span v-else class="text-slate-500">
                Menambahkan dokumen publik baru
              </span>
            </div>
            <div class="flex items-center gap-2.5">
              <button 
                type="button" 
                @click="closeModal" 
                class="px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold transition text-xs sm:text-sm cursor-pointer"
              >
                Batal
              </button>
              <button 
                type="submit" 
                :disabled="saving || uploading"
                class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold transition shadow-xs disabled:opacity-50 cursor-pointer text-xs sm:text-sm flex items-center gap-1.5"
              >
                {{ saving ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Terbitkan Dokumen') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Modal Delete Confirmation -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl space-y-4 text-center relative">
        <button 
          type="button" 
          @click="showDeleteModal = false" 
          class="absolute top-4 right-4 p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer"
          title="Tutup"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div>
          <h3 class="text-base font-bold text-slate-900">Hapus Dokumen Publik?</h3>
          <p class="text-xs text-slate-500 mt-1">
            Apakah Anda yakin ingin menghapus berkas dokumen "<strong>{{ selectedDoc?.judul }}</strong>"? Dokumen yang dihapus tidak dapat diunduh kembali oleh masyarakat.
          </p>
        </div>
        <div class="flex items-center justify-center gap-2 pt-2">
          <button 
            @click="showDeleteModal = false" 
            class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer"
          >
            Batal
          </button>
          <button 
            @click="doDelete" 
            :disabled="deleting"
            class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition shadow-xs disabled:opacity-50 cursor-pointer"
          >
            {{ deleting ? 'Menghapus...' : 'Ya, Hapus Dokumen' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import RichTextEditor from '../../components/RichTextEditor.vue';
import { AdminService } from '../../services/api';
import { useToast } from '../../composables/useToast';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const deleting = ref(false);
const uploading = ref(false);
const isDragging = ref(false);

const dokumenList = ref([]);
const summary = ref({ total: 0, aktif: 0, total_unduhan: 0 });
const meta = ref({ kategori_list: [], subkategori_list: [], periode_list: [], tahun_list: [], master_kategori_tree: [] });
const successMsg = ref('');
const errorMsg = ref('');
const modalError = ref('');

// Filter States
const searchQuery = ref('');
const filterKategori = ref('Semua');
const filterSubkategori = ref('Semua');
const filterPeriode = ref('Semua');
const filterTahun = ref('Semua');
const filterStatus = ref('semua');

// Modal States
const showModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const selectedDoc = ref(null);
const fileInputRef = ref(null);

// Category & Subcategory Taxonomy
const isCustomKategori = ref(false);
const isCustomSubkategori = ref(false);
const masterKategoriTree = ref([]);

const defaultKategoriList = [
  'Perencanaan & Pembangunan',
  'Keuangan & Anggaran',
  'Pemerintahan & Administrasi',
  'Pelayanan Publik',
  'Kegiatan & Kemasyarakatan',
  'Transparansi & Akuntabilitas'
];

// Computed Categories for Form
const kategoriOptions = computed(() => {
  const treeNames = masterKategoriTree.value.map(k => k.nama);
  const combined = [...defaultKategoriList];
  for (const name of treeNames) {
    if (!combined.includes(name)) combined.push(name);
  }
  if (meta.value.kategori_list) {
    for (const name of meta.value.kategori_list) {
      if (!combined.includes(name)) combined.push(name);
    }
  }
  return combined;
});

// Computed Subcategories based on currently selected form.kategori
const formSubkategoriOptions = computed(() => {
  const currentCat = isCustomKategori.value ? form.value.customKategori : form.value.kategori;
  if (!currentCat) return [];

  const matchedCategory = masterKategoriTree.value.find(k => k.nama.toLowerCase() === currentCat.toLowerCase());
  if (matchedCategory && matchedCategory.subkategoris) {
    return matchedCategory.subkategoris.map(s => s.nama);
  }
  return [];
});

// Computed Filters for Table
const availableFilterKategoris = computed(() => {
  const set = new Set();
  masterKategoriTree.value.forEach(k => set.add(k.nama));
  (meta.value.kategori_list || []).forEach(k => set.add(k));
  defaultKategoriList.forEach(k => set.add(k));
  return Array.from(set);
});

const availableFilterSubkategoris = computed(() => {
  if (filterKategori.value !== 'Semua') {
    const matchedCategory = masterKategoriTree.value.find(k => k.nama.toLowerCase() === filterKategori.value.toLowerCase());
    if (matchedCategory && matchedCategory.subkategoris) {
      return matchedCategory.subkategoris.map(s => s.nama);
    }
  }
  return meta.value.subkategori_list || [];
});

const availableFilterTahuns = computed(() => {
  const set = new Set(meta.value.tahun_list || []);
  const currentYear = new Date().getFullYear();
  set.add(currentYear.toString());
  set.add((currentYear - 1).toString());
  return Array.from(set).sort((a, b) => b - a);
});

const hasActiveFilter = computed(() => {
  return filterKategori.value !== 'Semua' ||
    filterSubkategori.value !== 'Semua' ||
    filterPeriode.value !== 'Semua' ||
    filterTahun.value !== 'Semua' ||
    filterStatus.value !== 'semua' ||
    searchQuery.value.trim() !== '';
});

const onFilterKategoriChange = () => {
  filterSubkategori.value = 'Semua';
  fetchDokumen();
};

const resetFilters = () => {
  searchQuery.value = '';
  filterKategori.value = 'Semua';
  filterSubkategori.value = 'Semua';
  filterPeriode.value = 'Semua';
  filterTahun.value = 'Semua';
  filterStatus.value = 'semua';
  fetchDokumen();
};

let searchTimeout = null;
const handleSearchInput = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchDokumen();
  }, 300);
};

const form = ref({
  id: null,
  judul: '',
  nomor_dokumen: '',
  kategori: 'Perencanaan & Pembangunan',
  customKategori: '',
  subkategori: '',
  customSubkategori: '',
  periode: 'Tahunan',
  tahun: new Date().getFullYear().toString(),
  tahun_selesai: '',
  periode_ke: '',
  tanggal_publikasi: new Date().toISOString().split('T')[0],
  deskripsi: '',
  file: '',
  nama_file_asli: '',
  ukuran_file: '',
  aktif: true
});

const onFormKategoriChange = () => {
  form.value.subkategori = '';
  form.value.customSubkategori = '';
  isCustomSubkategori.value = false;
};

const formatWaktuDisplay = (doc) => {
  if (doc.periode === '5 Tahunan') {
    return doc.tahun && doc.tahun_selesai ? `${doc.tahun}–${doc.tahun_selesai}` : (doc.tahun || '-');
  }
  if (doc.periode === 'Triwulanan' || doc.periode === 'Semesteran' || doc.periode === 'Bulanan') {
    return [doc.tahun, doc.periode_ke].filter(Boolean).join(' — ') || '-';
  }
  return doc.tahun || '-';
};

const fetchMasterTaxonomy = async () => {
  try {
    const kats = await AdminService.getMasterKategori('dokumen');
    if (kats) {
      // Filter top-level
      masterKategoriTree.value = kats.filter(k => !k.parent_id);
    }
  } catch (err) {
    console.warn('Gagal memuat master kategori dokumen:', err);
  }
};

const fetchDokumen = async () => {
  loading.value = true;
  try {
    const params = {};
    if (searchQuery.value.trim()) params.q = searchQuery.value.trim();
    if (filterKategori.value !== 'Semua') params.kategori = filterKategori.value;
    if (filterSubkategori.value !== 'Semua') params.subkategori = filterSubkategori.value;
    if (filterPeriode.value !== 'Semua') params.periode = filterPeriode.value;
    if (filterTahun.value !== 'Semua') params.tahun = filterTahun.value;
    if (filterStatus.value !== 'semua') params.status = filterStatus.value;

    const res = await AdminService.getDokumen(params);
    dokumenList.value = res.data || [];
    summary.value = res.summary || { total: dokumenList.value.length, aktif: 0, total_unduhan: 0 };
    meta.value = res.meta || { kategori_list: [], subkategori_list: [], periode_list: [], tahun_list: [], master_kategori_tree: [] };

    if (res.meta?.master_kategori_tree && res.meta.master_kategori_tree.length) {
      masterKategoriTree.value = res.meta.master_kategori_tree;
    }
  } catch (err) {
    errorMsg.value = 'Gagal memuat daftar dokumen publik.';
  } finally {
    loading.value = false;
  }
};

const openModal = (doc = null) => {
  modalError.value = '';
  isCustomKategori.value = false;
  isCustomSubkategori.value = false;

  if (doc) {
    isEditing.value = true;
    const knownKategori = kategoriOptions.value.includes(doc.kategori);

    form.value = {
      id: doc.id,
      judul: doc.judul || '',
      nomor_dokumen: doc.nomor_dokumen || '',
      kategori: knownKategori ? doc.kategori : (doc.kategori || 'Perencanaan & Pembangunan'),
      customKategori: knownKategori ? '' : (doc.kategori || ''),
      subkategori: doc.subkategori || '',
      customSubkategori: '',
      periode: doc.periode || 'Tahunan',
      tahun: doc.tahun || new Date().getFullYear().toString(),
      tahun_selesai: doc.tahun_selesai || '',
      periode_ke: doc.periode_ke || '',
      tanggal_publikasi: doc.tanggal_publikasi || new Date().toISOString().split('T')[0],
      deskripsi: doc.deskripsi || '',
      file: doc.file || '',
      nama_file_asli: doc.nama_file_asli || '',
      ukuran_file: doc.ukuran_file || '',
      aktif: doc.aktif !== false
    };

    if (!knownKategori && doc.kategori) {
      isCustomKategori.value = true;
    }
  } else {
    isEditing.value = false;
    form.value = {
      id: null,
      judul: '',
      nomor_dokumen: '',
      kategori: 'Perencanaan & Pembangunan',
      customKategori: '',
      subkategori: '',
      customSubkategori: '',
      periode: 'Tahunan',
      tahun: new Date().getFullYear().toString(),
      tahun_selesai: '',
      periode_ke: '',
      tanggal_publikasi: new Date().toISOString().split('T')[0],
      deskripsi: '',
      file: '',
      nama_file_asli: '',
      ukuran_file: '',
      aktif: true
    };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  modalError.value = '';
  isDragging.value = false;
  if (fileInputRef.value) fileInputRef.value.value = '';
};

const formatBytes = (bytes, decimals = 1) => {
  if (!+bytes) return '0 Bytes';
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ['Bytes', 'KB', 'MB', 'GB'];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
};

const onDropFile = (e) => {
  isDragging.value = false;
  const files = e.dataTransfer?.files;
  if (files && files.length > 0) {
    processFileUpload(files[0]);
  }
};

const handleFileUpload = (e) => {
  const file = e.target?.files?.[0];
  if (file) {
    processFileUpload(file);
  }
};

const processFileUpload = async (file) => {
  if (!file) return;
  modalError.value = '';

  const isPdf = (file.type && file.type === 'application/pdf') || file.name.toLowerCase().endsWith('.pdf');
  if (!isPdf) {
    modalError.value = 'Format file tidak didukung. Harap pilih berkas PDF (.pdf)!';
    if (fileInputRef.value) fileInputRef.value.value = '';
    return;
  }

  if (file.size > 10 * 1024 * 1024) {
    modalError.value = 'Ukuran file dokumen PDF melebihi batas maksimal 10MB!';
    if (fileInputRef.value) fileInputRef.value.value = '';
    return;
  }

  uploading.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'document');

    const uploadedPath = res?.data?.path || res?.data?.url;
    if (uploadedPath) {
      form.value.file = uploadedPath;
      form.value.nama_file_asli = file.name;
      form.value.ukuran_file = formatBytes(file.size);
      
      // Auto-populate title if empty
      if (!form.value.judul) {
        form.value.judul = file.name.replace(/\.[^/.]+$/, '').replace(/[-_]/g, ' ');
      }
    } else {
      throw new Error(res?.message || 'Gagal memperoleh tautan berkas setelah diunggah.');
    }
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal mengunggah file PDF.';
    modalError.value = 'Gagal mengunggah file PDF: ' + errMsg;
  } finally {
    uploading.value = false;
    if (fileInputRef.value) fileInputRef.value.value = '';
  }
};

const saveDokumen = async () => {
  modalError.value = '';
  if (!form.value.file) {
    modalError.value = 'Harap pilih dan unggah berkas dokumen PDF terlebih dahulu!';
    return;
  }

  // Validasi dinamis sesuai periode
  if (form.value.periode === '5 Tahunan') {
    if (!form.value.tahun || !form.value.tahun_selesai) {
      modalError.value = 'Untuk periode 5 Tahunan, Tahun Mulai dan Tahun Selesai wajib diisi!';
      return;
    }
  } else if (['Triwulanan', 'Semesteran', 'Bulanan'].includes(form.value.periode)) {
    if (!form.value.tahun || !form.value.periode_ke) {
      modalError.value = `Untuk periode ${form.value.periode}, Tahun dan Periode Ke wajib dipilih!`;
      return;
    }
  } else if (form.value.periode === 'Tahunan') {
    if (!form.value.tahun) {
      modalError.value = 'Tahun dokumen wajib diisi!';
      return;
    }
  }

  saving.value = true;
  try {
    const finalKategori = isCustomKategori.value 
      ? (form.value.customKategori.trim() || 'Perencanaan & Pembangunan') 
      : (form.value.kategori || 'Perencanaan & Pembangunan');

    const finalSubkategori = isCustomSubkategori.value
      ? (form.value.customSubkategori.trim() || null)
      : (form.value.subkategori || null);

    const payload = {
      judul: form.value.judul,
      nomor_dokumen: form.value.nomor_dokumen || null,
      kategori: finalKategori,
      subkategori: finalSubkategori,
      periode: form.value.periode || 'Tahunan',
      tahun: form.value.tahun || null,
      tahun_selesai: form.value.periode === '5 Tahunan' ? form.value.tahun_selesai : null,
      periode_ke: ['Triwulanan', 'Semesteran', 'Bulanan'].includes(form.value.periode) ? form.value.periode_ke : null,
      tanggal_publikasi: form.value.tanggal_publikasi || null,
      deskripsi: form.value.deskripsi || null,
      file: form.value.file,
      nama_file_asli: form.value.nama_file_asli,
      ukuran_file: form.value.ukuran_file,
      aktif: form.value.aktif
    };

    if (isEditing.value && form.value.id) {
      await AdminService.saveDokumen(payload, form.value.id);
      const msg = `Dokumen "${payload.judul}" berhasil diperbarui.`;
      successMsg.value = msg;
      toast.success(msg, 'Dokumen Diperbarui');
    } else {
      await AdminService.saveDokumen(payload);
      const msg = `Dokumen "${payload.judul}" berhasil diterbitkan.`;
      successMsg.value = msg;
      toast.success(msg, 'Dokumen Diterbitkan');
    }

    closeModal();
    fetchDokumen();
    fetchMasterTaxonomy();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan dokumen.';
    modalError.value = 'Gagal menyimpan dokumen: ' + errMsg;
    toast.error(errMsg, 'Gagal Menyimpan Dokumen');
  } finally {
    saving.value = false;
  }
};

const toggleStatus = async (doc) => {
  try {
    await AdminService.toggleDokumenStatus(doc.id);
    doc.aktif = !doc.aktif;
    const msg = `Status dokumen "${doc.judul}" berhasil diubah menjadi ${doc.aktif ? 'Aktif' : 'Nonaktif'}.`;
    successMsg.value = msg;
    toast.success(msg, 'Status Diperbarui');
  } catch (err) {
    const errMsg = 'Gagal mengubah status dokumen.';
    errorMsg.value = errMsg;
    toast.error(errMsg);
  }
};

const confirmDelete = (doc) => {
  selectedDoc.value = doc;
  showDeleteModal.value = true;
};

const doDelete = async () => {
  if (!selectedDoc.value) return;
  deleting.value = true;
  try {
    await AdminService.deleteDokumen(selectedDoc.value.id);
    const msg = `Dokumen "${selectedDoc.value.judul}" berhasil dihapus.`;
    successMsg.value = msg;
    toast.success(msg, 'Dokumen Dihapus');
    showDeleteModal.value = false;
    fetchDokumen();
  } catch (err) {
    const errMsg = 'Gagal menghapus dokumen.';
    errorMsg.value = errMsg;
    toast.error(errMsg);
  } finally {
    deleting.value = false;
  }
};

onMounted(() => {
  fetchDokumen();
  fetchMasterTaxonomy();
});
</script>
