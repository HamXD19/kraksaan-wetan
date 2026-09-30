<template>
  <div class="space-y-8 pb-16">
    <!-- Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 text-[#2073B7] border border-blue-200 text-[11px] font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-[#2073B7]"></span>
          Good Governance & Akuntabilitas
        </div>
        <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">
          Manajemen Anggaran & APBD Kelurahan
        </h2>
        <p class="text-xs text-slate-500 mt-0.5">
          Formulir komprehensif satu pintu untuk mengelola data APBD tahunan beserta seluruh rincian pos Pendapatan, Belanja, dan Pembiayaan.
        </p>
      </div>

      <button 
        v-if="!showForm"
        @click="openCreateForm" 
        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#2073B7] hover:bg-blue-800 text-white font-bold text-xs shadow-md transition self-start sm:self-auto cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah APBD Tahun Baru</span>
      </button>
      <button 
        v-else
        @click="closeForm" 
        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Kembali ke Daftar APBD</span>
      </button>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ successMsg }}</span>
      </div>
      <button @click="successMsg = ''" class="text-emerald-700 hover:text-emerald-900 font-bold cursor-pointer">&times;</button>
    </div>

    <!-- ============================================================== -->
    <!-- TAMPILAN 1: DAFTAR DOKUMEN APBD TAHUNAN                        -->
    <!-- ============================================================== -->
    <div v-if="!showForm" class="space-y-6">
      <!-- KPI Highlight Cards Ringkasan APBD -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
          <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Pagu Belanja</p>
          <p class="text-xl sm:text-2xl font-black text-slate-900 mt-1.5">{{ formatRupiah(summary.total_rencana) }}</p>
          <p class="text-[10px] text-slate-500 mt-1">Alokasi seluruh program tahunan</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
          <p class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Realisasi Serapan</p>
          <p class="text-xl sm:text-2xl font-black text-emerald-700 mt-1.5">{{ formatRupiah(summary.total_realisasi) }}</p>
          <p class="text-[10px] text-emerald-600 font-semibold mt-1">{{ summary.persentase_total }}% dari pagu rencana</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
          <p class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Sisa Pagu</p>
          <p class="text-xl sm:text-2xl font-black text-amber-700 mt-1.5">{{ formatRupiah(summary.total_sisa) }}</p>
          <p class="text-[10px] text-slate-500 mt-1">Sisa anggaran / efisiensi</p>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs">
          <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Arsip APBD</p>
          <p class="text-xl sm:text-2xl font-black text-slate-900 mt-1.5">{{ budgets.length }} Tahun</p>
          <p class="text-[10px] text-slate-500 mt-1">{{ summary.total_kegiatan }} total pos rincian terdata</p>
        </div>
      </div>

      <LoadingSpinner v-if="loading" />

      <!-- Tabel Daftar APBD -->
      <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <!-- Toolbar Filter & Search -->
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
          <div class="flex items-center gap-2 w-full sm:w-auto">
            <select 
              v-model="filterStatus" 
              @change="loadData"
              class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 outline-none cursor-pointer"
            >
              <option value="Semua">Semua Status</option>
              <option value="published">Publikasi (Live)</option>
              <option value="draft">Draft (Sembunyi)</option>
            </select>
          </div>

          <div class="relative w-full sm:w-72">
            <input 
              type="text" 
              v-model="searchQuery" 
              @input="filterDebounce"
              placeholder="Cari dokumen APBD..." 
              class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-[#2073B7] outline-none bg-white"
            />
            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
              <tr>
                <th class="py-3.5 px-4">Tahun & Dokumen APBD</th>
                <th class="py-3.5 px-4">Tanggal Rilis</th>
                <th class="py-3.5 px-4">Total Pendapatan</th>
                <th class="py-3.5 px-4">Total Belanja</th>
                <th class="py-3.5 px-4">Surplus / (Defisit)</th>
                <th class="py-3.5 px-4 text-center">Status</th>
                <th class="py-3.5 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
              <tr v-for="b in budgets" :key="b.id" class="hover:bg-slate-50/70 transition">
                <td class="py-3.5 px-4 max-w-sm">
                  <div class="flex items-start gap-2.5">
                    <span class="px-2 py-0.5 rounded bg-blue-100 font-black text-blue-900 text-[10px] shrink-0">
                      {{ b.tahun }}
                    </span>
                    <div>
                      <p class="font-bold text-slate-900 text-xs leading-snug">{{ b.judul }}</p>
                      <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ b.items_count || 0 }} pos rincian anggaran</p>
                    </div>
                  </div>
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap text-slate-500 font-medium">
                  {{ formatTanggal(b.tanggal_publikasi) }}
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap">
                  <span class="font-bold text-blue-900">{{ formatRupiah(b.total_pendapatan_realisasi || b.total_pendapatan_rencana) }}</span>
                  <span class="block text-[10px] text-slate-400">Rencana: {{ formatRupiah(b.total_pendapatan_rencana) }}</span>
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap">
                  <span class="font-bold text-rose-900">{{ formatRupiah(b.total_belanja_realisasi || b.total_belanja_rencana) }}</span>
                  <span class="block text-[10px] text-slate-400">Rencana: {{ formatRupiah(b.total_belanja_rencana) }}</span>
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap font-bold" :class="(b.surplus_defisit_realisasi ?? 0) >= 0 ? 'text-emerald-700' : 'text-rose-700'">
                  {{ formatRupiah(b.surplus_defisit_realisasi ?? (b.total_pendapatan_realisasi - b.total_belanja_realisasi)) }}
                </td>
                <td class="py-3.5 px-4 text-center whitespace-nowrap">
                  <button 
                    @click="toggleStatus(b)"
                    class="px-2.5 py-1 rounded-full text-[10px] font-bold transition inline-flex items-center gap-1 shadow-xs cursor-pointer"
                    :class="b.status === 'published' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-amber-100 text-amber-800 hover:bg-amber-200'"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="b.status === 'published' ? 'bg-emerald-600' : 'bg-amber-500'"></span>
                    <span>{{ b.status === 'published' ? 'Published' : 'Draft' }}</span>
                  </button>
                </td>
                <td class="py-3.5 px-4 text-right whitespace-nowrap">
                  <div class="inline-flex items-center gap-1.5">
                    <router-link 
                      :to="`/transparansi/${b.slug || b.id}`"
                      target="_blank"
                      class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition"
                      title="Lihat Pratinjau Publik"
                    >
                      Lihat
                    </router-link>
                    <button 
                      @click="openEditForm(b)"
                      class="px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[#2073B7] font-semibold transition cursor-pointer"
                    >
                      Sunting
                    </button>
                    <button 
                      @click="deleteBudget(b)"
                      class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold transition cursor-pointer"
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="!budgets.length">
                <td colspan="7" class="py-12 text-center text-slate-400 text-xs">
                  Belum ada dokumen APBD yang dibuat. Klik tombol <strong>"Tambah APBD Tahun Baru"</strong> untuk menginput anggaran komprehensif.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ============================================================== -->
    <!-- TAMPILAN 2: SINGLE FORM BULK INPUT (CREATE & EDIT)             -->
    <!-- ============================================================== -->
    <div v-else class="space-y-8">
      <form @submit.prevent="saveForm" class="space-y-8">
        <!-- 1. INFORMASI UMUM & BERKAS -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs space-y-6">
          <div class="border-b border-slate-100 pb-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
              <h3 class="text-base sm:text-lg font-black text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#2073B7]"></span>
                Informasi Umum & Berkas Lampiran
              </h3>
              <p class="text-xs text-slate-500 mt-0.5">Identitas utama dokumen publikasi APBD kelurahan.</p>
            </div>

            <!-- Tombol Muat Template Default -->
            <button 
              type="button"
              @click="populateDefaultAccounts"
              class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-blue-50 text-[#2073B7] hover:bg-blue-100 font-bold text-xs transition cursor-pointer self-start sm:self-auto"
              title="Mengisi otomatis baris rekening baku APBD agar tidak perlu mengetik dari awal"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
              <span>Muat Pos Standar APBD</span>
            </button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Judul Dokumen (2 cols) -->
            <div class="sm:col-span-2 space-y-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Judul Dokumen Publikasi <span class="text-rose-500">*</span>
              </label>
              <input 
                type="text" 
                v-model="form.judul" 
                required
                placeholder="Contoh: Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan Tahun Anggaran 2026"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-[#2073B7] outline-none"
              />
            </div>

            <!-- Tahun Anggaran -->
            <div class="space-y-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Tahun Anggaran <span class="text-rose-500">*</span>
              </label>
              <input 
                type="number" 
                v-model.number="form.tahun" 
                required
                min="2000"
                max="2100"
                @change="updateDefaultJudul"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#2073B7] outline-none"
              />
            </div>

            <!-- Tanggal Publikasi -->
            <div class="space-y-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Tanggal Publikasi <span class="text-rose-500">*</span>
              </label>
              <input 
                type="date" 
                v-model="form.tanggal_publikasi" 
                required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-[#2073B7] outline-none"
              />
            </div>

            <!-- Status Publikasi -->
            <div class="space-y-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Status Publikasi <span class="text-rose-500">*</span>
              </label>
              <select 
                v-model="form.status" 
                required
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-800 focus:ring-2 focus:ring-[#2073B7] outline-none cursor-pointer"
              >
                <option value="published">Publikasi (Tayang ke Publik)</option>
                <option value="draft">Draft (Hanya Panel Admin)</option>
              </select>
            </div>

            <!-- Upload Thumbnail / Gambar Sampul -->
            <div class="space-y-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Gambar Sampul / Thumbnail (Opsional)
              </label>
              <div class="flex items-center gap-2">
                <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold cursor-pointer transition">
                  <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <span>Pilih & Crop Foto</span>
                  <input type="file" accept="image/*" class="hidden" @change="onSelectImage" />
                </label>
                <button 
                  v-if="form.gambar" 
                  type="button" 
                  @click="form.gambar = ''" 
                  class="p-2 text-rose-500 hover:text-rose-700 font-bold text-xs cursor-pointer" 
                  title="Hapus gambar"
                >
                  &times; Hapus
                </button>
              </div>
              <p v-if="form.gambar" class="text-[10px] text-emerald-600 font-medium truncate">
                Foto siap: {{ form.gambar }}
              </p>
            </div>

            <!-- Upload File Lampiran PDF Dokumen Publik -->
            <div class="sm:col-span-2 space-y-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Lampiran Dokumen Resmi (PDF)
              </label>
              <div class="flex items-center gap-2">
                <label class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold cursor-pointer transition">
                  <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                  <span>{{ uploadingPdf ? 'Mengunggah...' : 'Upload Berkas PDF' }}</span>
                  <input type="file" accept="application/pdf" class="hidden" @change="onSelectPdf" :disabled="uploadingPdf" />
                </label>
                <span v-if="form.file_lampiran" class="text-xs text-slate-700 font-medium truncate max-w-xs">
                  {{ form.file_lampiran }}
                </span>
                <button 
                  v-if="form.file_lampiran" 
                  type="button" 
                  @click="form.file_lampiran = ''" 
                  class="text-rose-500 hover:text-rose-700 font-bold text-xs cursor-pointer"
                >
                  &times;
                </button>
              </div>
              <p class="text-[10px] text-slate-400">Berkas format PDF (maks. 10MB) yang dapat diunduh oleh pengunjung portal.</p>
            </div>

            <!-- Narasi / Deskripsi Pengantar (Full width) -->
            <div class="sm:col-span-2 lg:col-span-4 space-y-1.5">
              <label class="block text-xs font-bold text-slate-700">
                Narasi / Ringkasan Pengantar APBD
              </label>
              <textarea 
                v-model="form.deskripsi" 
                rows="3"
                placeholder="Tuliskan gambaran umum arah kebijakan anggaran, fokus program pembangunan, dan catatan strategis tahun berjalan..."
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-medium focus:ring-2 focus:ring-[#2073B7] outline-none"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- 2. SECTION 1: PENDAPATAN (REPEATER TABLE) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
          <div class="px-6 py-4 bg-[#2073B7] text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center font-bold text-xs">1</span>
              <h3 class="text-sm sm:text-base font-bold tracking-tight">SECTION 1: PENDAPATAN KELURAHAN</h3>
            </div>
            <button 
              type="button" 
              @click="addRow('pendapatan')" 
              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white text-[#2073B7] font-bold text-xs shadow-xs hover:bg-blue-50 transition cursor-pointer"
            >
              + Tambah Baris Pendapatan
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                <tr>
                  <th class="py-3 px-3 w-48">Sub-Kategori</th>
                  <th class="py-3 px-3">Uraian / Akun Rekening</th>
                  <th class="py-3 px-3 w-40 text-right">Rencana (Rp)</th>
                  <th class="py-3 px-3 w-40 text-right">Realisasi (Rp)</th>
                  <th class="py-3 px-3 w-36 text-right">Selisih (Rp)</th>
                  <th class="py-3 px-2 w-12 text-center">Hapus</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(row, idx) in pendapatanRows" :key="row.uid" class="hover:bg-slate-50/70 transition">
                  <td class="p-2">
                    <select v-model="row.kategori" class="w-full p-2 text-xs rounded-lg border border-slate-200 bg-white font-medium">
                      <option value="Pendapatan Asli">Pendapatan Asli</option>
                      <option value="Pendapatan Transfer">Pendapatan Transfer</option>
                      <option value="Pendapatan Lain-lain">Pendapatan Lain-lain</option>
                    </select>
                  </td>
                  <td class="p-2">
                    <input 
                      type="text" 
                      v-model="row.uraian" 
                      required 
                      placeholder="Uraian pendapatan..." 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 font-medium"
                    />
                  </td>
                  <td class="p-2 text-right">
                    <input 
                      type="number" 
                      v-model.number="row.anggaran" 
                      min="0" 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 text-right font-medium"
                    />
                  </td>
                  <td class="p-2 text-right">
                    <input 
                      type="number" 
                      v-model.number="row.realisasi" 
                      min="0" 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 text-right font-medium"
                    />
                  </td>
                  <td class="p-2 text-right font-bold" :class="(row.realisasi - row.anggaran) >= 0 ? 'text-emerald-700' : 'text-rose-700'">
                    {{ formatRupiah(row.realisasi - row.anggaran) }}
                  </td>
                  <td class="p-2 text-center">
                    <button type="button" @click="removeRow('pendapatan', idx)" class="text-rose-500 hover:text-rose-700 font-bold p-1 cursor-pointer">
                      ✕
                    </button>
                  </td>
                </tr>

                <tr v-if="!pendapatanRows.length">
                  <td colspan="6" class="p-6 text-center text-slate-400 italic">
                    Belum ada baris pendapatan. Klik tombol "+ Tambah Baris Pendapatan" di atas.
                  </td>
                </tr>

                <!-- Sub-total Pendapatan Footer -->
                <tr class="bg-blue-50/80 font-black text-slate-900 border-t-2 border-blue-200">
                  <td colspan="2" class="py-3 px-4 uppercase text-blue-950">SUBTOTAL PENDAPATAN</td>
                  <td class="py-3 px-3 text-right">{{ formatRupiah(calcTotalPendapatanRencana) }}</td>
                  <td class="py-3 px-3 text-right text-blue-950">{{ formatRupiah(calcTotalPendapatanRealisasi) }}</td>
                  <td class="py-3 px-3 text-right" :class="(calcTotalPendapatanRealisasi - calcTotalPendapatanRencana) >= 0 ? 'text-emerald-700' : 'text-rose-700'">
                    {{ formatRupiah(calcTotalPendapatanRealisasi - calcTotalPendapatanRencana) }}
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 3. SECTION 2: BELANJA (REPEATER TABLE) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
          <div class="px-6 py-4 bg-[#2073B7] text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center font-bold text-xs">2</span>
              <h3 class="text-sm sm:text-base font-bold tracking-tight">SECTION 2: BELANJA KELURAHAN</h3>
            </div>
            <button 
              type="button" 
              @click="addRow('belanja')" 
              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white text-[#2073B7] font-bold text-xs shadow-xs hover:bg-blue-50 transition cursor-pointer"
            >
              + Tambah Baris Belanja
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                <tr>
                  <th class="py-3 px-3 w-56">Bidang Belanja</th>
                  <th class="py-3 px-3">Uraian / Kegiatan Program</th>
                  <th class="py-3 px-3 w-40 text-right">Rencana (Rp)</th>
                  <th class="py-3 px-3 w-40 text-right">Realisasi (Rp)</th>
                  <th class="py-3 px-3 w-36 text-right">Selisih (Rp)</th>
                  <th class="py-3 px-2 w-12 text-center">Hapus</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(row, idx) in belanjaRows" :key="row.uid" class="hover:bg-slate-50/70 transition">
                  <td class="p-2">
                    <select v-model="row.kategori" class="w-full p-2 text-xs rounded-lg border border-slate-200 bg-white font-medium">
                      <option value="Penyelenggaraan Pemerintahan">Penyelenggaraan Pemerintahan</option>
                      <option value="Pelaksanaan Pembangunan">Pelaksanaan Pembangunan</option>
                      <option value="Pembinaan Kemasyarakatan">Pembinaan Kemasyarakatan</option>
                      <option value="Pemberdayaan Masyarakat">Pemberdayaan Masyarakat</option>
                      <option value="Belanja Tak Terduga">Belanja Tak Terduga</option>
                    </select>
                  </td>
                  <td class="p-2">
                    <input 
                      type="text" 
                      v-model="row.uraian" 
                      required 
                      placeholder="Uraian kegiatan belanja..." 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 font-medium"
                    />
                  </td>
                  <td class="p-2 text-right">
                    <input 
                      type="number" 
                      v-model.number="row.anggaran" 
                      min="0" 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 text-right font-medium"
                    />
                  </td>
                  <td class="p-2 text-right">
                    <input 
                      type="number" 
                      v-model.number="row.realisasi" 
                      min="0" 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 text-right font-medium"
                    />
                  </td>
                  <td class="p-2 text-right font-bold" :class="(row.realisasi - row.anggaran) >= 0 ? 'text-rose-700' : 'text-emerald-700'">
                    {{ formatRupiah(row.realisasi - row.anggaran) }}
                  </td>
                  <td class="p-2 text-center">
                    <button type="button" @click="removeRow('belanja', idx)" class="text-rose-500 hover:text-rose-700 font-bold p-1 cursor-pointer">
                      ✕
                    </button>
                  </td>
                </tr>

                <tr v-if="!belanjaRows.length">
                  <td colspan="6" class="p-6 text-center text-slate-400 italic">
                    Belum ada baris belanja. Klik tombol "+ Tambah Baris Belanja" di atas.
                  </td>
                </tr>

                <!-- Sub-total Belanja Footer -->
                <tr class="bg-rose-50/80 font-black text-slate-900 border-t-2 border-rose-200">
                  <td colspan="2" class="py-3 px-4 uppercase text-rose-950">SUBTOTAL BELANJA</td>
                  <td class="py-3 px-3 text-right">{{ formatRupiah(calcTotalBelanjaRencana) }}</td>
                  <td class="py-3 px-3 text-right text-rose-950">{{ formatRupiah(calcTotalBelanjaRealisasi) }}</td>
                  <td class="py-3 px-3 text-right" :class="(calcTotalBelanjaRealisasi - calcTotalBelanjaRencana) >= 0 ? 'text-rose-700' : 'text-emerald-700'">
                    {{ formatRupiah(calcTotalBelanjaRealisasi - calcTotalBelanjaRencana) }}
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 4. SECTION 3: PEMBIAYAAN (REPEATER TABLE) -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
          <div class="px-6 py-4 bg-[#2073B7] text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="w-6 h-6 rounded-lg bg-white/20 flex items-center justify-center font-bold text-xs">3</span>
              <h3 class="text-sm sm:text-base font-bold tracking-tight">SECTION 3: PEMBIAYAAN KELURAHAN</h3>
            </div>
            <button 
              type="button" 
              @click="addRow('pembiayaan')" 
              class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white text-[#2073B7] font-bold text-xs shadow-xs hover:bg-blue-50 transition cursor-pointer"
            >
              + Tambah Baris Pembiayaan
            </button>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                <tr>
                  <th class="py-3 px-3 w-56">Jenis Pembiayaan</th>
                  <th class="py-3 px-3">Uraian / Pos Pembiayaan</th>
                  <th class="py-3 px-3 w-40 text-right">Rencana (Rp)</th>
                  <th class="py-3 px-3 w-40 text-right">Realisasi (Rp)</th>
                  <th class="py-3 px-3 w-36 text-right">Selisih (Rp)</th>
                  <th class="py-3 px-2 w-12 text-center">Hapus</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="(row, idx) in pembiayaanRows" :key="row.uid" class="hover:bg-slate-50/70 transition">
                  <td class="p-2">
                    <select v-model="row.kategori" class="w-full p-2 text-xs rounded-lg border border-slate-200 bg-white font-medium">
                      <option value="Penerimaan Pembiayaan">Penerimaan Pembiayaan</option>
                      <option value="Pengeluaran Pembiayaan">Pengeluaran Pembiayaan</option>
                    </select>
                  </td>
                  <td class="p-2">
                    <input 
                      type="text" 
                      v-model="row.uraian" 
                      required 
                      placeholder="Contoh: SiLPA tahun sebelumnya / Penyertaan Modal..." 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 font-medium"
                    />
                  </td>
                  <td class="p-2 text-right">
                    <input 
                      type="number" 
                      v-model.number="row.anggaran" 
                      min="0" 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 text-right font-medium"
                    />
                  </td>
                  <td class="p-2 text-right">
                    <input 
                      type="number" 
                      v-model.number="row.realisasi" 
                      min="0" 
                      class="w-full p-2 text-xs rounded-lg border border-slate-200 text-right font-medium"
                    />
                  </td>
                  <td class="p-2 text-right font-bold text-slate-800">
                    {{ formatRupiah(row.realisasi - row.anggaran) }}
                  </td>
                  <td class="p-2 text-center">
                    <button type="button" @click="removeRow('pembiayaan', idx)" class="text-rose-500 hover:text-rose-700 font-bold p-1 cursor-pointer">
                      ✕
                    </button>
                  </td>
                </tr>

                <tr v-if="!pembiayaanRows.length">
                  <td colspan="6" class="p-6 text-center text-slate-400 italic">
                    Belum ada baris pembiayaan. Klik tombol "+ Tambah Baris Pembiayaan" di atas jika ada SILPA atau dana cadangan.
                  </td>
                </tr>

                <!-- Netto Pembiayaan Footer -->
                <tr class="bg-emerald-50/80 font-black text-slate-900 border-t-2 border-emerald-200">
                  <td colspan="2" class="py-3 px-4 uppercase text-emerald-950">PEMBIAYAAN NETTO (Penerimaan - Pengeluaran)</td>
                  <td class="py-3 px-3 text-right">{{ formatRupiah(calcPembiayaanNettoRencana) }}</td>
                  <td class="py-3 px-3 text-right text-emerald-950">{{ formatRupiah(calcPembiayaanNettoRealisasi) }}</td>
                  <td class="py-3 px-3 text-right text-emerald-800">
                    {{ formatRupiah(calcPembiayaanNettoRealisasi - calcPembiayaanNettoRencana) }}
                  </td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- 5. LIVE SUMMARY BOX & SUBMIT BUTTON (STICKY BAR) -->
        <div class="bg-gradient-to-r from-slate-900 via-slate-800 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl border border-slate-700 space-y-6">
          <div class="border-b border-slate-700/80 pb-4 flex items-center justify-between">
            <div>
              <span class="text-xs font-bold text-amber-400 uppercase tracking-wider">Live Calculation Summary</span>
              <h4 class="text-base sm:text-lg font-black text-white">Ringkasan Otomatis APBD TA {{ form.tahun }}</h4>
            </div>
            <span class="text-xs text-slate-400 hidden sm:inline-block">Dihitung otomatis sebelum disimpan</span>
          </div>

          <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 text-xs">
            <!-- Total Pendapatan -->
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 space-y-1">
              <span class="text-slate-400 block text-[11px]">Total Pendapatan</span>
              <p class="text-base sm:text-lg font-black text-blue-300">{{ formatRupiah(calcTotalPendapatanRealisasi) }}</p>
              <p class="text-[10px] text-slate-400">Rencana: {{ formatRupiah(calcTotalPendapatanRencana) }}</p>
            </div>

            <!-- Total Belanja -->
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 space-y-1">
              <span class="text-slate-400 block text-[11px]">Total Belanja</span>
              <p class="text-base sm:text-lg font-black text-rose-300">{{ formatRupiah(calcTotalBelanjaRealisasi) }}</p>
              <p class="text-[10px] text-slate-400">Rencana: {{ formatRupiah(calcTotalBelanjaRencana) }}</p>
            </div>

            <!-- Surplus / Defisit -->
            <div class="p-3.5 rounded-2xl bg-white/5 border border-white/10 space-y-1">
              <span class="text-slate-400 block text-[11px]">Surplus / (Defisit)</span>
              <p 
                class="text-base sm:text-lg font-black"
                :class="calcSurplusDefisitRealisasi >= 0 ? 'text-emerald-300' : 'text-amber-300'"
              >
                {{ formatRupiah(calcSurplusDefisitRealisasi) }}
              </p>
              <p class="text-[10px] text-slate-400">Rencana: {{ formatRupiah(calcSurplusDefisitRencana) }}</p>
            </div>

            <!-- SILPA Tahun Berkenaan -->
            <div class="p-3.5 rounded-2xl bg-white/10 border border-amber-400/30 space-y-1">
              <span class="text-amber-300 block text-[11px] font-bold">SILPA Tahun Berkenaan</span>
              <p class="text-base sm:text-lg font-black text-amber-300">{{ formatRupiah(calcSilpaRealisasi) }}</p>
              <p class="text-[10px] text-slate-300">Netto: {{ formatRupiah(calcPembiayaanNettoRealisasi) }}</p>
            </div>
          </div>

          <!-- Submit Buttons -->
          <div class="pt-4 border-t border-slate-700/80 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-400">
              Menyimpan {{ pendapatanRows.length + belanjaRows.length + pembiayaanRows.length }} baris rincian pos anggaran secara atomik dalam satu transaksi database.
            </p>

            <div class="flex items-center gap-3 w-full sm:w-auto">
              <button 
                type="button" 
                @click="closeForm"
                class="w-1/2 sm:w-auto px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs transition cursor-pointer"
              >
                Batal
              </button>
              <button 
                type="submit" 
                :disabled="saving"
                class="w-1/2 sm:w-auto px-8 py-3.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-slate-950 font-black text-xs shadow-lg transition flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50"
              >
                <svg v-if="saving" class="w-4 h-4 animate-spin text-slate-950" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span>{{ saving ? 'Menyimpan Anggaran...' : 'Simpan Seluruh Anggaran APBD' }}</span>
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>

    <!-- Image Cropper Modal for Thumbnail -->
    <ImageCropperModal
      v-model:show="showCropper"
      :image-file="selectedImageFile"
      :default-aspect-ratio="16 / 9"
      @cropped="handleCroppedImage"
    />
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import ImageCropperModal from '../../components/ImageCropperModal.vue';
import { AdminService } from '../../services/api';
import { useToast } from '../../composables/useToast';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const uploadingPdf = ref(false);
const successMsg = ref('');

const budgets = ref([]);
const summary = ref({
  total_rencana: 0,
  total_realisasi: 0,
  total_sisa: 0,
  persentase_total: 0,
  total_kegiatan: 0,
  total_tahun: 0,
});

const filterStatus = ref('Semua');
const searchQuery = ref('');
const showForm = ref(false);
const editId = ref(null);

// Cropper State
const showCropper = ref(false);
const selectedImageFile = ref(null);

// Main Comprehensive Form
const form = reactive({
  judul: '',
  tahun: new Date().getFullYear(),
  tanggal_publikasi: new Date().toISOString().split('T')[0],
  status: 'published',
  gambar: '',
  file_lampiran: '',
  deskripsi: '',
});

const pendapatanRows = ref([]);
const belanjaRows = ref([]);
const pembiayaanRows = ref([]);

let uidCounter = 1;
const genUid = () => 'uid_' + uidCounter++;

// --- Live Calculations ---
const calcTotalPendapatanRencana = computed(() => {
  return pendapatanRows.value.reduce((acc, r) => acc + (Number(r.anggaran) || 0), 0);
});
const calcTotalPendapatanRealisasi = computed(() => {
  return pendapatanRows.value.reduce((acc, r) => acc + (Number(r.realisasi) || 0), 0);
});

const calcTotalBelanjaRencana = computed(() => {
  return belanjaRows.value.reduce((acc, r) => acc + (Number(r.anggaran) || 0), 0);
});
const calcTotalBelanjaRealisasi = computed(() => {
  return belanjaRows.value.reduce((acc, r) => acc + (Number(r.realisasi) || 0), 0);
});

const calcSurplusDefisitRencana = computed(() => {
  return calcTotalPendapatanRencana.value - calcTotalBelanjaRencana.value;
});
const calcSurplusDefisitRealisasi = computed(() => {
  return calcTotalPendapatanRealisasi.value - calcTotalBelanjaRealisasi.value;
});

const calcPenerimaanPembiayaanRencana = computed(() => {
  return pembiayaanRows.value
    .filter(r => (r.kategori || '').toLowerCase().includes('penerimaan'))
    .reduce((acc, r) => acc + (Number(r.anggaran) || 0), 0);
});
const calcPenerimaanPembiayaanRealisasi = computed(() => {
  return pembiayaanRows.value
    .filter(r => (r.kategori || '').toLowerCase().includes('penerimaan'))
    .reduce((acc, r) => acc + (Number(r.realisasi) || 0), 0);
});

const calcPengeluaranPembiayaanRencana = computed(() => {
  return pembiayaanRows.value
    .filter(r => (r.kategori || '').toLowerCase().includes('pengeluaran'))
    .reduce((acc, r) => acc + (Number(r.anggaran) || 0), 0);
});
const calcPengeluaranPembiayaanRealisasi = computed(() => {
  return pembiayaanRows.value
    .filter(r => (r.kategori || '').toLowerCase().includes('pengeluaran'))
    .reduce((acc, r) => acc + (Number(r.realisasi) || 0), 0);
});

const calcPembiayaanNettoRencana = computed(() => {
  return calcPenerimaanPembiayaanRencana.value - calcPengeluaranPembiayaanRencana.value;
});
const calcPembiayaanNettoRealisasi = computed(() => {
  return calcPenerimaanPembiayaanRealisasi.value - calcPengeluaranPembiayaanRealisasi.value;
});

const calcSilpaRencana = computed(() => {
  return calcSurplusDefisitRencana.value + calcPembiayaanNettoRencana.value;
});
const calcSilpaRealisasi = computed(() => {
  return calcSurplusDefisitRealisasi.value + calcPembiayaanNettoRealisasi.value;
});

// Formatters
const formatRupiah = (val) => {
  const num = Number(val) || 0;
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(num);
};

const formatTanggal = (dateStr) => {
  if (!dateStr) return '-';
  try {
    const d = new Date(dateStr);
    return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).format(d);
  } catch {
    return dateStr;
  }
};

let debounceTimer = null;
const filterDebounce = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    loadData();
  }, 350);
};

// --- Form Operations ---
const updateDefaultJudul = () => {
  if (!editId.value) {
    form.judul = `Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan Tahun Anggaran ${form.tahun}`;
  }
};

const openCreateForm = () => {
  editId.value = null;
  form.tahun = new Date().getFullYear();
  form.judul = `Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan Tahun Anggaran ${form.tahun}`;
  form.tanggal_publikasi = new Date().toISOString().split('T')[0];
  form.status = 'published';
  form.gambar = '';
  form.file_lampiran = '';
  form.deskripsi = '';

  pendapatanRows.value = [];
  belanjaRows.value = [];
  pembiayaanRows.value = [];

  showForm.value = true;
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

const populateDefaultAccounts = () => {
  if (pendapatanRows.value.length === 0) {
    pendapatanRows.value = [
      { uid: genUid(), tipe: 'pendapatan', kategori: 'Pendapatan Asli', uraian: 'Hasil Usaha & Pengelolaan Aset Kelurahan', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'pendapatan', kategori: 'Pendapatan Asli', uraian: 'Swadaya, Partisipasi, dan Gotong Royong Warga', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'pendapatan', kategori: 'Pendapatan Transfer', uraian: 'Alokasi Dana Kelurahan (ADK)', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'pendapatan', kategori: 'Pendapatan Transfer', uraian: 'Bagi Hasil Pajak & Retribusi Daerah', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'pendapatan', kategori: 'Pendapatan Lain-lain', uraian: 'Penerimaan Hibah & Sumbangan', anggaran: 0, realisasi: 0 },
    ];
  }

  if (belanjaRows.value.length === 0) {
    belanjaRows.value = [
      { uid: genUid(), tipe: 'belanja', kategori: 'Penyelenggaraan Pemerintahan', uraian: 'Operasional Perkantoran & Pelayanan Publik', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'belanja', kategori: 'Pelaksanaan Pembangunan', uraian: 'Pembangunan Saluran Drainase & Pemukiman', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'belanja', kategori: 'Pelaksanaan Pembangunan', uraian: 'Penerangan Jalan Lingkungan & Pavingisasi', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'belanja', kategori: 'Pembinaan Kemasyarakatan', uraian: 'Pembinaan Kelembagaan RT/RW & Pemuda', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'belanja', kategori: 'Pemberdayaan Masyarakat', uraian: 'Pelatihan Ekonomi Kreatif & Bantuan UMKM', anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'belanja', kategori: 'Belanja Tak Terduga', uraian: 'Keadaan Mendesak & Penanggulangan Bencana', anggaran: 0, realisasi: 0 },
    ];
  }

  if (pembiayaanRows.value.length === 0) {
    pembiayaanRows.value = [
      { uid: genUid(), tipe: 'pembiayaan', kategori: 'Penerimaan Pembiayaan', uraian: `Sisa Lebih Perhitungan Anggaran (SiLPA) Tahun ${form.tahun - 1}`, anggaran: 0, realisasi: 0 },
      { uid: genUid(), tipe: 'pembiayaan', kategori: 'Pengeluaran Pembiayaan', uraian: 'Pembentukan Dana Cadangan', anggaran: 0, realisasi: 0 },
    ];
  }

  toast.info('Pos rekening standar APBD berhasil dimuat ke dalam formulir.');
};

const openEditForm = async (item) => {
  editId.value = item.id;
  loading.value = true;
  try {
    const res = await AdminService.getTransparansiDetail(item.id);
    const data = res || item;

    form.judul = data.judul || '';
    form.tahun = Number(data.tahun) || new Date().getFullYear();
    form.tanggal_publikasi = data.tanggal_publikasi ? data.tanggal_publikasi.split('T')[0] : new Date().toISOString().split('T')[0];
    form.status = data.status || 'published';
    form.gambar = data.gambar || '';
    form.file_lampiran = data.file_lampiran || '';
    form.deskripsi = data.deskripsi || '';

    const items = data.items || [];
    pendapatanRows.value = items.filter(i => i.tipe === 'pendapatan').map(i => ({ ...i, uid: genUid() }));
    belanjaRows.value = items.filter(i => i.tipe === 'belanja').map(i => ({ ...i, uid: genUid() }));
    pembiayaanRows.value = items.filter(i => i.tipe === 'pembiayaan').map(i => ({ ...i, uid: genUid() }));

    showForm.value = true;
    window.scrollTo({ top: 0, behavior: 'smooth' });
  } catch (err) {
    toast.error('Gagal mengambil rincian data APBD: ' + (err.message || err));
  } finally {
    loading.value = false;
  }
};

const closeForm = () => {
  showForm.value = false;
  editId.value = null;
};

const addRow = (section) => {
  if (section === 'pendapatan') {
    pendapatanRows.value.push({
      uid: genUid(),
      tipe: 'pendapatan',
      kategori: 'Pendapatan Asli',
      uraian: '',
      anggaran: 0,
      realisasi: 0,
    });
  } else if (section === 'belanja') {
    belanjaRows.value.push({
      uid: genUid(),
      tipe: 'belanja',
      kategori: 'Pelaksanaan Pembangunan',
      uraian: '',
      anggaran: 0,
      realisasi: 0,
    });
  } else if (section === 'pembiayaan') {
    pembiayaanRows.value.push({
      uid: genUid(),
      tipe: 'pembiayaan',
      kategori: 'Penerimaan Pembiayaan',
      uraian: '',
      anggaran: 0,
      realisasi: 0,
    });
  }
};

const removeRow = (section, idx) => {
  if (section === 'pendapatan') pendapatanRows.value.splice(idx, 1);
  if (section === 'belanja') belanjaRows.value.splice(idx, 1);
  if (section === 'pembiayaan') pembiayaanRows.value.splice(idx, 1);
};

// --- Media Upload Handlers ---
const onSelectImage = (e) => {
  const file = e.target.files?.[0];
  if (file) {
    selectedImageFile.value = file;
    showCropper.value = true;
  }
};

const handleCroppedImage = async (blob) => {
  try {
    const file = new File([blob], `apbd_${form.tahun}_${Date.now()}.jpg`, { type: 'image/jpeg' });
    const res = await AdminService.uploadFile(file, 'image');
    if (res?.data?.url || res?.url || res?.path) {
      form.gambar = res.data?.url || res.url || res.path;
      toast.success('Gambar sampul APBD berhasil diunggah.');
    }
  } catch (err) {
    toast.error('Gagal mengunggah foto sampul: ' + (err.message || err));
  }
};

const onSelectPdf = async (e) => {
  const file = e.target.files?.[0];
  if (!file) return;

  if (file.type !== 'application/pdf' && !file.name.toLowerCase().endsWith('.pdf')) {
    toast.error('Berkas harus berformat PDF (.pdf)!');
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran berkas melebihi batas 10MB!');
    return;
  }

  uploadingPdf.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'document');
    if (res?.data?.path || res?.path || res?.url) {
      form.file_lampiran = res.data?.path || res.path || res.url;
      toast.success('Berkas PDF resmi APBD berhasil diunggah.');
    }
  } catch (err) {
    toast.error('Gagal mengunggah berkas PDF: ' + (err.message || err));
  } finally {
    uploadingPdf.value = false;
  }
};

// --- Submit Bulk Save ---
const saveForm = async () => {
  const allItems = [
    ...pendapatanRows.value.map((r, i) => ({ ...r, urutan: i + 1 })),
    ...belanjaRows.value.map((r, i) => ({ ...r, urutan: pendapatanRows.value.length + i + 1 })),
    ...pembiayaanRows.value.map((r, i) => ({ ...r, urutan: pendapatanRows.value.length + belanjaRows.value.length + i + 1 })),
  ];

  if (allItems.length === 0) {
    toast.error('Harap masukkan setidaknya satu pos anggaran sebelum menyimpan!');
    return;
  }

  saving.value = true;
  try {
    const payload = {
      judul: form.judul,
      tahun: Number(form.tahun),
      tanggal_publikasi: form.tanggal_publikasi,
      status: form.status,
      deskripsi: form.deskripsi || null,
      gambar: form.gambar || null,
      file_lampiran: form.file_lampiran || null,
      items: allItems.map(item => ({
        tipe: item.tipe,
        kategori: item.kategori,
        uraian: item.uraian,
        anggaran: Number(item.anggaran) || 0,
        realisasi: Number(item.realisasi) || 0,
        keterangan: item.keterangan || null,
        urutan: item.urutan || 0,
      })),
    };

    const res = await AdminService.saveTransparansi(payload, editId.value);
    const msg = res?.message || 'Data APBD tahunan berhasil disimpan!';
    successMsg.value = msg;
    toast.success(msg, editId.value ? 'APBD Diperbarui' : 'APBD Tersimpan');

    showForm.value = false;
    editId.value = null;
    await loadData();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan data APBD.';
    toast.error(errMsg);
  } finally {
    saving.value = false;
  }
};

const toggleStatus = async (item) => {
  const newStatus = item.status === 'published' ? 'draft' : 'published';
  try {
    await AdminService.toggleStatusTransparansi(item.id, newStatus);
    item.status = newStatus;
    toast.success(`Status APBD ${item.tahun} diubah menjadi ${newStatus}.`);
  } catch (err) {
    toast.error('Gagal mengubah status: ' + (err.message || err));
  }
};

const deleteBudget = async (item) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus APBD Tahun ${item.tahun} ("${item.judul}")? Seluruh rincian pos anggaran dan berkas fisik lampiran akan dihapus.`)) {
    return;
  }

  try {
    await AdminService.deleteTransparansi(item.id);
    toast.success(`APBD Tahun ${item.tahun} berhasil dihapus.`);
    await loadData();
  } catch (err) {
    toast.error('Gagal menghapus APBD: ' + (err.message || err));
  }
};

const loadData = async () => {
  loading.value = true;
  try {
    const params = {};
    if (filterStatus.value !== 'Semua') {
      params.status = filterStatus.value;
    }
    if (searchQuery.value?.trim()) {
      params.search = searchQuery.value.trim();
    }

    const res = await AdminService.getTransparansi(params);
    summary.value = res?.summary || summary.value;
    budgets.value = res?.budgets || [];
  } catch (err) {
    console.error('Gagal memuat daftar APBD admin:', err);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  loadData();
});
</script>
