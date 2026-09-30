<template>
  <div class="space-y-6">
    <!-- Tab Navigation -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap items-center justify-between gap-3">
      <div class="flex flex-wrap items-center gap-2">
        <button
          type="button"
          @click="activeTab = 'layanan'"
          :class="activeTab === 'layanan' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
          class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>Katalog SOP Layanan</span>
          <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'layanan' ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-200 text-slate-700'">{{ layananList.length }}</span>
        </button>

        <button
          type="button"
          @click="activeTab = 'maklumat'"
          :class="activeTab === 'maklumat' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
          class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
          <span>Maklumat Pelayanan</span>
        </button>

        <button
          type="button"
          @click="activeTab = 'skm'"
          :class="activeTab === 'skm' ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
          class="px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          <span>Survei Kepuasan (SKM)</span>
          <span class="px-2 py-0.5 rounded-full text-[10px]" :class="activeTab === 'skm' ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-200 text-slate-700'">{{ skmList.length }}</span>
        </button>
      </div>

      <!-- Quick Action Buttons per Tab -->
      <div v-if="activeTab === 'layanan'">
        <button 
          @click="openModal()" 
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <span>Tambah Layanan</span>
        </button>
      </div>
      <div v-else-if="activeTab === 'skm'">
        <button 
          @click="openSkmModal()" 
          class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition cursor-pointer"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <span>Tambah Periode SKM</span>
        </button>
      </div>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700 font-bold hover:text-emerald-900 cursor-pointer">&times;</button>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: KATALOG SOP LAYANAN                 -->
    <!-- ========================================== -->
    <div v-if="activeTab === 'layanan'" class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div>
          <h2 class="text-xl font-bold text-slate-900">Kelola Layanan Masyarakat</h2>
          <p class="text-xs text-slate-500">Atur jenis permohonan surat, dokumen kependudukan, alur, dan persyaratan berkas.</p>
        </div>
      </div>

      <LoadingSpinner v-if="loading" />
      <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
              <tr>
                <th class="py-3.5 px-4">Nama Layanan</th>
                <th class="py-3.5 px-4">Kategori</th>
                <th class="py-3.5 px-4">Biaya</th>
                <th class="py-3.5 px-4">Waktu</th>
                <th class="py-3.5 px-4 text-center">Status Online</th>
                <th class="py-3.5 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
              <tr v-for="l in layananList" :key="l.id" class="hover:bg-slate-50">
                <td class="py-3 px-4 max-w-sm">
                  <p class="font-bold text-slate-900">{{ l.judul }}</p>
                  <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ l.deskripsi }}</p>
                  <div v-if="l.slug" class="mt-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600 border border-slate-200" title="Slug URL Layanan">
                      slug: {{ l.slug }}
                    </span>
                  </div>
                </td>
                <td class="py-3 px-4">
                  <span class="px-2.5 py-1 rounded-md bg-slate-100 font-semibold text-[11px]">
                    {{ l.kategori }}
                  </span>
                </td>
                <td class="py-3 px-4 text-emerald-700 font-semibold">{{ l.biaya }}</td>
                <td class="py-3 px-4">{{ l.waktu }}</td>
                <td class="py-3 px-4 text-center whitespace-nowrap">
                  <button 
                    @click="toggleAktifLayanan(l)" 
                    class="px-2.5 py-1 rounded-full text-[11px] font-bold transition inline-flex items-center gap-1.5 cursor-pointer"
                    :class="l.aktif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                    :title="l.aktif ? 'Layanan aktif menerima pengajuan online. Klik untuk menonaktifkan.' : 'Layanan dinonaktifkan. Klik untuk mengaktifkan.'"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="l.aktif ? 'bg-emerald-600' : 'bg-slate-400'"></span>
                    <span>{{ l.aktif ? 'Aktif' : 'Nonaktif' }}</span>
                  </button>
                </td>
                <td class="py-3 px-4 text-right whitespace-nowrap">
                  <button 
                    @click="openModal(l)" 
                    class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5 cursor-pointer"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteItem(l.id)" 
                    class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold cursor-pointer"
                  >
                    Hapus
                  </button>
                </td>
              </tr>
              <tr v-if="!layananList.length">
                <td colspan="6" class="py-8 text-center text-slate-400">Belum ada layanan terdaftar.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 2: MAKLUMAT PELAYANAN                 -->
    <!-- ========================================== -->
    <!-- TAB 2: MAKLUMAT PELAYANAN                 -->
    <!-- ========================================== -->
    <div v-else-if="activeTab === 'maklumat'" class="space-y-6">
      <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-xl font-bold text-slate-900">Kelola Maklumat Pelayanan Publik</h2>
            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
              Otomatis Nonaktifkan Versi Lama
            </span>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            Pernyataan standar pelayanan warga. Jika ada data maklumat terbaru yang diterbitkan atau diaktifkan, maka data versi lama akan otomatis dinonaktifkan.
          </p>
        </div>
        <div class="flex items-center gap-3">
          <button 
            type="button"
            @click="createNewMaklumat"
            class="px-4 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5 cursor-pointer"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Buat Maklumat Baru</span>
          </button>
        </div>
      </div>

      <LoadingSpinner v-if="loadingMaklumat" text="Memuat data maklumat..." />
      <div v-else class="space-y-6">
        <!-- Form Input / Edit Maklumat -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-5">
          <!-- Banner Mode Status -->
          <div v-if="isNewMaklumat" class="p-4 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs font-semibold flex items-center justify-between">
            <div class="flex items-center gap-2.5">
              <span class="text-base">✨</span>
              <span><strong>Mode Versi Baru:</strong> Maklumat baru ini akan otomatis menjadi versi aktif yang tayang di website dan seluruh data versi lama akan otomatis dinonaktifkan.</span>
            </div>
            <button type="button" @click="isNewMaklumat = false; loadMaklumat()" class="text-blue-700 hover:text-blue-900 underline text-xs font-bold cursor-pointer">
              Batal
            </button>
          </div>
          <div v-else-if="maklumatForm.id" class="p-3.5 rounded-2xl bg-emerald-50/70 border border-emerald-200 text-emerald-950 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-base">✏️</span>
              <span>Sedang mengedit versi: <strong>{{ maklumatForm.judul }}</strong> ({{ maklumatForm.nomor_sk || 'Tanpa No SK' }}) &bull; Status: <strong :class="maklumatForm.aktif ? 'text-emerald-700' : 'text-slate-500'">{{ maklumatForm.aktif ? 'Aktif (Tayang)' : 'Nonaktif (Arsip)' }}</strong></span>
            </div>
            <button type="button" @click="createNewMaklumat" class="text-emerald-700 hover:text-emerald-900 font-bold text-xs underline cursor-pointer">
              Beralih Buat Baru
            </button>
          </div>

          <form @submit.prevent="saveMaklumat(false)" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1.5">Judul Maklumat *</label>
                <input 
                  type="text" 
                  v-model="maklumatForm.judul" 
                  required 
                  placeholder="Maklumat Pelayanan Kelurahan Kraksaan Wetan"
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm font-medium"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1.5">Nomor SK / Dasar Penetapan</label>
                <input 
                  type="text" 
                  v-model="maklumatForm.nomor_sk" 
                  placeholder="Contoh: SK Lurah Kraksaan Wetan No. 060/12/426.311.01/2026"
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm"
                />
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 text-xs mb-1.5">Motto / Semboyan Pelayanan</label>
              <input 
                type="text" 
                v-model="maklumatForm.motto" 
                placeholder="Contoh: Melayani dengan HATI: Hangat, Akuntabel, Transparan, dan Ikhlas"
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm italic"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 text-xs mb-1.5">Isi Teks Janji & Komitmen Maklumat *</label>
              <textarea 
                rows="5" 
                v-model="maklumatForm.konten" 
                required 
                placeholder="Dengan ini kami seluruh aparatur Kelurahan Kraksaan Wetan menyatakan sanggup menyelenggarakan pelayanan sesuai standar pelayanan yang telah ditetapkan..."
                class="w-full px-3.5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm leading-relaxed"
              ></textarea>
              <p class="text-[11px] text-slate-400 mt-1">Gunakan enter/baris baru untuk memisahkan poin-poin ikrar pelayanan.</p>
            </div>

            <!-- Upload Poster Gambar Maklumat -->
            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                  <label class="block font-bold text-slate-800 text-xs">Poster / Dokumen Visual Maklumat (Gambar)</label>
                  <p class="text-[11px] text-slate-500">Dapat berupa infografis, poster, atau foto piagam maklumat pelayanan berbingkai. Dilengkapi fitur crop & sesuaikan rasio.</p>
                </div>
                <label class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl bg-white border border-slate-300 hover:border-emerald-500 text-slate-700 hover:text-emerald-700 font-bold text-xs cursor-pointer shadow-2xs transition">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  <span>{{ uploadingPoster ? 'Mengunggah...' : (maklumatForm.gambar ? 'Ganti Poster (Crop)' : 'Unggah & Crop Poster') }}</span>
                  <input 
                    type="file" 
                    accept="image/png, image/jpeg, image/jpg, image/webp" 
                    class="hidden" 
                    @change="handleMaklumatImageUpload" 
                    :disabled="uploadingPoster" 
                  />
                </label>
              </div>

              <!-- Preview Image (Unclipped Framed Plakat) -->
              <div v-if="maklumatForm.gambar" class="flex flex-col sm:flex-row items-center sm:items-start gap-4 bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs">
                <div class="relative w-28 sm:w-36 aspect-[3/4] bg-slate-900 rounded-xl overflow-hidden border-2 border-amber-400/50 p-1 shadow-md shrink-0">
                  <div class="w-full h-full border border-amber-300/30 rounded-lg overflow-hidden flex items-center justify-center bg-slate-950">
                    <img 
                      :src="maklumatForm.gambar" 
                      alt="Poster Maklumat" 
                      class="w-full h-full object-contain" 
                    />
                  </div>
                </div>
                <div class="flex-1 space-y-2 text-center sm:text-left min-w-0">
                  <div class="flex items-center gap-2 justify-center sm:justify-start flex-wrap">
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                      <svg class="w-3 h-3 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                      Piagam Terpasang
                    </span>
                    <span class="text-[11px] text-slate-400">Pratinjau Aspek Utuh (Tanpa Terpotong)</span>
                  </div>
                  <p class="text-xs font-mono text-slate-500 truncate max-w-md">{{ maklumatForm.gambar }}</p>
                  <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 pt-1">
                    <a 
                      :href="maklumatForm.gambar" 
                      target="_blank" 
                      class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-bold transition"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                      <span>Lihat Resolusi Penuh</span>
                    </a>
                    <button 
                      type="button" 
                      @click="maklumatForm.gambar = ''" 
                      class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-rose-600 hover:bg-rose-50 text-xs font-bold transition cursor-pointer"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      <span>Hapus Poster</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100">
              <label class="inline-flex items-center gap-2 cursor-pointer">
                <input 
                  type="checkbox" 
                  v-model="maklumatForm.aktif" 
                  class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4"
                />
                <span class="text-xs font-bold text-slate-700">Tetapkan sebagai Maklumat Tayang Utama (Data lama otomatis nonaktif)</span>
              </label>

              <div class="flex items-center gap-2">
                <button
                  v-if="!isNewMaklumat && maklumatForm.id"
                  type="button"
                  @click="saveMaklumat(true)"
                  :disabled="savingMaklumat"
                  class="px-4 py-2.5 rounded-xl bg-blue-700 hover:bg-blue-800 disabled:opacity-50 text-white font-bold text-xs shadow-xs cursor-pointer transition flex items-center gap-1.5"
                >
                  <span>Terbitkan Sebagai Versi Baru</span>
                </button>
                <button 
                  type="submit" 
                  :disabled="savingMaklumat"
                  class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold text-xs shadow-xs cursor-pointer transition flex items-center gap-2"
                >
                  <svg v-if="savingMaklumat" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                  <span>{{ savingMaklumat ? 'Menyimpan...' : (isNewMaklumat || !maklumatForm.id ? 'Terbitkan Maklumat Baru' : 'Simpan Perubahan') }}</span>
                </button>
              </div>
            </div>
          </form>
        </div>

        <!-- Tabel Riwayat & Arsip Maklumat Pelayanan -->
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
          <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
              <h3 class="font-bold text-sm text-slate-900">Riwayat & Arsip Maklumat Pelayanan</h3>
              <p class="text-xs text-slate-500 mt-0.5">Daftar seluruh versi maklumat. Hanya 1 maklumat aktif yang akan tampil ke masyarakat.</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg">
              Total: {{ maklumatHistory.length }} Versi
            </span>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
              <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
                <tr>
                  <th class="py-3 px-4">Judul Maklumat</th>
                  <th class="py-3 px-4">Nomor SK</th>
                  <th class="py-3 px-4">Motto Pelayanan</th>
                  <th class="py-3 px-4 text-center">Status Tayang</th>
                  <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 text-slate-600">
                <tr v-if="maklumatHistory.length === 0">
                  <td colspan="5" class="py-8 text-center text-slate-400">Belum ada riwayat maklumat.</td>
                </tr>
                <tr v-for="item in maklumatHistory" :key="item.id" class="hover:bg-slate-50 transition">
                  <td class="py-3.5 px-4">
                    <div class="flex items-center gap-3">
                      <div class="w-9 h-11 rounded-lg bg-slate-900 border border-amber-400/40 p-0.5 shadow-2xs flex items-center justify-center shrink-0 overflow-hidden">
                        <img 
                          v-if="item.gambar" 
                          :src="item.gambar" 
                          alt="Poster" 
                          class="w-full h-full object-contain"
                        />
                        <span v-else class="text-base">📜</span>
                      </div>
                      <div>
                        <p class="font-bold text-slate-900">{{ item.judul }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5 truncate max-w-xs">{{ item.konten }}</p>
                      </div>
                    </div>
                  </td>
                  <td class="py-3.5 px-4 font-mono text-[11px] text-slate-700">
                    {{ item.nomor_sk || '-' }}
                  </td>
                  <td class="py-3.5 px-4 text-slate-500 italic max-w-xs truncate">
                    {{ item.motto || '-' }}
                  </td>
                  <td class="py-3.5 px-4 text-center">
                    <span 
                      v-if="item.aktif" 
                      class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300"
                    >
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                      Aktif (Tayang)
                    </span>
                    <span 
                      v-else 
                      class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200"
                    >
                      Nonaktif (Arsip)
                    </span>
                  </td>
                  <td class="py-3.5 px-4 text-right">
                    <div class="inline-flex items-center gap-1.5">
                      <button 
                        v-if="!item.aktif"
                        type="button"
                        @click="toggleAktifMaklumat(item)"
                        class="px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-[11px] border border-emerald-200 transition cursor-pointer"
                        title="Jadikan Maklumat Aktif (yang lain otomatis nonaktif)"
                      >
                        Jadikan Aktif
                      </button>
                      <button 
                        type="button"
                        @click="editMaklumatItem(item)"
                        class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 transition cursor-pointer"
                        title="Edit Data"
                      >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                      </button>
                      <button 
                        v-if="maklumatHistory.length > 1"
                        type="button"
                        @click="deleteMaklumatItem(item)"
                        class="p-1.5 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition cursor-pointer"
                        title="Hapus"
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
      </div>
    </div>

    <!-- ========================================== -->
    <!-- TAB 3: SURVEI KEPUASAN MASYARAKAT (SKM)   -->
    <!-- ========================================== -->
    <div v-else-if="activeTab === 'skm'" class="space-y-6">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div>
          <h2 class="text-xl font-bold text-slate-900">Kelola Survei Kepuasan Masyarakat (SKM)</h2>
          <p class="text-xs text-slate-500">
            Publikasikan indeks IKM semesteran/tahunan secara dinamis beserta rincian unsur penilaian dan dokumen laporan.
          </p>
        </div>
      </div>

      <LoadingSpinner v-if="loadingSkm" text="Memuat riwayat SKM..." />
      <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
              <tr>
                <th class="py-3.5 px-4">Periode & Tahun</th>
                <th class="py-3.5 px-4">Skor IKM</th>
                <th class="py-3.5 px-4">Mutu & Predikat</th>
                <th class="py-3.5 px-4">Responden</th>
                <th class="py-3.5 px-4">Unsur Dinamis</th>
                <th class="py-3.5 px-4">Laporan PDF</th>
                <th class="py-3.5 px-4 text-center">Status Tayang</th>
                <th class="py-3.5 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
              <tr v-for="item in skmList" :key="item.id" class="hover:bg-slate-50">
                <td class="py-3 px-4">
                  <p class="font-bold text-slate-900">{{ item.periode }}</p>
                  <p class="text-[11px] text-slate-400">Tahun {{ item.tahun }}</p>
                </td>
                <td class="py-3 px-4">
                  <span class="text-base font-extrabold text-emerald-800">{{ item.skor_ikm }}</span>
                  <span class="text-[11px] text-slate-400"> / {{ item.skala_maksimal }}</span>
                </td>
                <td class="py-3 px-4">
                  <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md font-bold text-[11px] bg-emerald-100 text-emerald-900">
                    <span>Mutu {{ item.mutu_pelayanan }}</span>
                    <span>•</span>
                    <span>{{ item.predikat }}</span>
                  </span>
                </td>
                <td class="py-3 px-4 font-semibold text-slate-700">
                  {{ item.jumlah_responden }} Orang
                </td>
                <td class="py-3 px-4">
                  <span v-if="item.unsur_penilaian && item.unsur_penilaian.length" class="px-2 py-0.5 rounded bg-blue-50 text-blue-700 border border-blue-200 font-semibold text-[10px]">
                    {{ item.unsur_penilaian.length }} Unsur Penilaian
                  </span>
                  <span v-else class="text-slate-400 text-[11px]">-</span>
                </td>
                <td class="py-3 px-4">
                  <a 
                    v-if="item.file_laporan" 
                    :href="item.file_laporan" 
                    target="_blank" 
                    class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 hover:text-rose-900 bg-rose-50 px-2 py-1 rounded-md"
                  >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Unduh PDF</span>
                  </a>
                  <span v-else class="text-slate-400 text-[11px]">Belum ada</span>
                </td>
                <td class="py-3 px-4 text-center whitespace-nowrap">
                  <button 
                    @click="toggleAktifSkm(item)" 
                    class="px-2.5 py-1 rounded-full text-[11px] font-bold transition inline-flex items-center gap-1.5 cursor-pointer"
                    :class="item.aktif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                    :title="item.aktif ? 'SKM aktif ditampilkan publik. Klik untuk sembunyikan.' : 'SKM tersembunyi. Klik untuk tampilkan.'"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="item.aktif ? 'bg-emerald-600' : 'bg-slate-400'"></span>
                    <span>{{ item.aktif ? 'Tayang' : 'Draft' }}</span>
                  </button>
                </td>
                <td class="py-3 px-4 text-right whitespace-nowrap">
                  <button 
                    @click="openSkmModal(item)" 
                    class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5 cursor-pointer"
                  >
                    Edit
                  </button>
                  <button 
                    @click="deleteSkmItem(item.id)" 
                    class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold cursor-pointer"
                  >
                    Hapus
                  </button>
                </td>
              </tr>
              <tr v-if="!skmList.length">
                <td colspan="8" class="py-8 text-center text-slate-400">Belum ada data Survei SKM yang diinput.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL FORM TAMBAH / SUNTING LAYANAN SOP   -->
    <!-- ========================================== -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs"
    >
      <div class="bg-white rounded-2xl sm:rounded-3xl max-w-5xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
        <!-- Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">
              {{ editId ? 'Sunting Layanan Publik' : 'Tambah Layanan Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola standar operasional prosedur (SOP), syarat berkas, dan alur permohonan.</p>
          </div>
          <button 
            type="button" 
            @click="showModal = false" 
            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer"
            title="Tutup"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Form with Scrollable Interior & Sticky Footer -->
        <form @submit.prevent="saveItem" class="flex flex-col flex-1 overflow-hidden">
          <div class="p-5 sm:p-6 overflow-y-auto flex-1 text-xs sm:text-sm">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
              <!-- Left Column: Primary Content (Nama, Deskripsi & Alur) -->
              <div class="lg:col-span-7 space-y-4">
                <div>
                  <label class="block font-bold text-slate-700 mb-1">Nama Layanan *</label>
                  <input 
                    type="text" 
                    v-model="form.judul" 
                    required 
                    placeholder="Contoh: Surat Pengantar SKCK" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-medium"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat *</label>
                  <RichTextEditor 
                    v-model="form.deskripsi" 
                    placeholder="Deskripsi peruntukan layanan..." 
                    height="140px"
                    maxHeight="220px"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1">Alur Prosedur Pengurusan</label>
                  <RichTextEditor 
                    v-model="form.alur" 
                    placeholder="Langkah pengajuan hingga pengambilan dokumen..." 
                    height="160px"
                    maxHeight="240px"
                  />
                </div>
              </div>

              <!-- Right Column: Meta, Waktu, Biaya, Persyaratan -->
              <div class="lg:col-span-5 space-y-3.5 bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80">
                <div>
                  <div class="flex items-center justify-between mb-1">
                    <label class="block font-bold text-slate-700 text-xs">Kategori Layanan *</label>
                    <button 
                      type="button" 
                      @click="isCustomKategori = !isCustomKategori"
                      class="text-[10px] font-bold text-emerald-700 hover:text-emerald-900 cursor-pointer"
                    >
                      {{ isCustomKategori ? '← Master' : '+ Baru' }}
                    </button>
                  </div>
                  <div v-if="!isCustomKategori">
                    <select 
                      v-model="form.kategori" 
                      required 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    >
                      <option v-for="k in kategoriOptions" :key="k.id || k" :value="k.nama || k">
                        {{ k.nama || k }}
                      </option>
                      <option v-if="form.kategori && !kategoriOptions.some(k => (k.nama || k) === form.kategori)" :value="form.kategori">
                        {{ form.kategori }}
                      </option>
                    </select>
                  </div>
                  <div v-else>
                    <input 
                      type="text" 
                      v-model="form.customKategori" 
                      required 
                      placeholder="Kategori baru..." 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    />
                  </div>
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1 text-xs">Ikon Tipe Layanan</label>
                  <select 
                    v-model="form.icon" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                  >
                    <option value="Users">Users (Kependudukan)</option>
                    <option value="Home">Home (Domisili)</option>
                    <option value="Briefcase">Briefcase (Usaha / SKU)</option>
                    <option value="HeartHandshake">HeartHandshake (SKTM/Bansos)</option>
                    <option value="ShieldCheck">ShieldCheck (SKCK)</option>
                    <option value="FileText">FileText (Umum)</option>
                  </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Estimasi Waktu</label>
                    <input 
                      type="text" 
                      v-model="form.waktu" 
                      placeholder="10 - 15 Menit" 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    />
                  </div>

                  <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Biaya / Tarif</label>
                    <input 
                      type="text" 
                      v-model="form.biaya" 
                      placeholder="Gratis (Rp 0)" 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    />
                  </div>
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1 text-xs">Persyaratan Berkas (1 baris per syarat)</label>
                  <textarea 
                    rows="4" 
                    v-model="persyaratanText" 
                    placeholder="Surat pengantar RT dan RW&#10;Fotokopi KTP dan KK&#10;Pas foto 3x4" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs leading-relaxed"
                  ></textarea>
                </div>
              </div>
            </div>
          </div>

          <!-- Sticky Footer Actions -->
          <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
            <span class="text-xs text-slate-400 italic hidden sm:inline">Kolom bertanda * wajib diisi</span>
            <div class="flex items-center gap-2.5 ml-auto">
              <button 
                type="button" 
                @click="showModal = false" 
                class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold text-xs cursor-pointer transition"
              >
                Batal
              </button>
              <button 
                type="submit" 
                :disabled="saving"
                class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold text-xs shadow-xs cursor-pointer transition"
              >
                <span v-if="saving">Menyimpan...</span>
                <span v-else>{{ editId ? 'Simpan Perubahan' : 'Simpan Layanan' }}</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================== -->
    <!-- MODAL FORM TAMBAH / SUNTING SURVEI SKM    -->
    <!-- ========================================== -->
    <div 
      v-if="showSkmModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs"
    >
      <div class="bg-white rounded-2xl sm:rounded-3xl max-w-4xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
        <!-- Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">
              {{ editSkmId ? 'Sunting Data Survei SKM' : 'Tambah Periode Survei SKM Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola indeks IKM, skala nilai, dan unsur penilaian dinamis.</p>
          </div>
          <button 
            type="button" 
            @click="showSkmModal = false" 
            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer"
            title="Tutup"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Form Body -->
        <form @submit.prevent="saveSkmItem" class="flex flex-col flex-1 overflow-hidden">
          <div class="p-5 sm:p-6 overflow-y-auto flex-1 text-xs sm:text-sm space-y-5">
            <!-- Basic Metrics -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1">Tahun *</label>
                <input 
                  type="text" 
                  v-model="skmForm.tahun" 
                  required 
                  placeholder="2026" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1">Periode *</label>
                <input 
                  type="text" 
                  v-model="skmForm.periode" 
                  required 
                  placeholder="Semester I 2026 atau Tahun 2026" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1">Skor IKM *</label>
                <input 
                  type="number" 
                  step="0.01" 
                  v-model.number="skmForm.skor_ikm" 
                  required 
                  placeholder="88.45" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs font-bold text-emerald-800"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1">Skala Maksimal *</label>
                <input 
                  type="number" 
                  step="0.01" 
                  v-model.number="skmForm.skala_maksimal" 
                  required 
                  placeholder="100.00" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                />
              </div>
            </div>

            <!-- Grade, Mutu, & Responden -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1">Mutu Pelayanan *</label>
                <input 
                  type="text" 
                  v-model="skmForm.mutu_pelayanan" 
                  required 
                  placeholder="A / B / C" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1">Predikat Kinerja *</label>
                <input 
                  type="text" 
                  v-model="skmForm.predikat" 
                  required 
                  placeholder="Sangat Baik / Baik" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 text-xs mb-1">Jumlah Responden *</label>
                <input 
                  type="number" 
                  v-model.number="skmForm.jumlah_responden" 
                  required 
                  placeholder="150" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                />
              </div>
            </div>

            <!-- Link Survei Online -->
            <div>
              <label class="block font-bold text-slate-700 text-xs mb-1">Tautan Kuesioner Survei Publik (Opsional)</label>
              <input 
                type="url" 
                v-model="skmForm.link_survei" 
                placeholder="https://forms.gle/... atau tautan survei online lainnya" 
                class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
              />
              <p class="text-[11px] text-slate-400 mt-0.5">Jika diisi, warga dapat langsung mengklik tombol "Isi Kuesioner Survei" di halaman SKM publik.</p>
            </div>

            <!-- DINAMIS: Unsur-unsur Penilaian SKM -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
              <div class="flex items-center justify-between">
                <div>
                  <h4 class="font-bold text-slate-800 text-xs sm:text-sm">Rincian Unsur Penilaian (Dinamis)</h4>
                  <p class="text-[11px] text-slate-500">Sesuaikan indikator penilaian sesuai pedoman tahun bersangkutan (misal 9 unsur PermenPAN-RB).</p>
                </div>
                <button 
                  type="button" 
                  @click="addUnsurRow" 
                  class="px-3 py-1.5 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-bold text-xs transition cursor-pointer flex items-center gap-1.5"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  <span>Tambah Unsur</span>
                </button>
              </div>

              <div v-if="skmForm.unsur_penilaian && skmForm.unsur_penilaian.length" class="space-y-2">
                <div 
                  v-for="(u, idx) in skmForm.unsur_penilaian" 
                  :key="idx" 
                  class="flex items-center gap-2 bg-white p-2.5 rounded-xl border border-slate-200"
                >
                  <span class="w-6 text-center text-xs font-bold text-slate-400">{{ idx + 1 }}.</span>
                  <input 
                    type="text" 
                    v-model="u.nama" 
                    placeholder="Nama Unsur (misal: Persyaratan, Waktu Pelayanan)" 
                    class="flex-1 px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-1 focus:ring-emerald-600 outline-none text-xs" 
                    required 
                  />
                  <input 
                    type="number" 
                    step="0.01" 
                    v-model.number="u.nilai" 
                    placeholder="Skor" 
                    class="w-24 px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-1 focus:ring-emerald-600 outline-none text-xs font-bold text-emerald-800" 
                    required 
                  />
                  <button 
                    type="button" 
                    @click="removeUnsurRow(idx)" 
                    class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg cursor-pointer transition"
                    title="Hapus baris unsur ini"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </div>
              <div v-else class="text-center py-4 text-xs text-slate-400 italic">
                Belum ada rincian unsur. Klik "Tambah Unsur" untuk menambahkan parameter penilaian SKM.
              </div>
            </div>

            <!-- Metodologi -->
            <div>
              <label class="block font-bold text-slate-700 text-xs mb-1">Metodologi & Landasan Penilaian</label>
              <textarea 
                rows="2" 
                v-model="skmForm.metodologi" 
                placeholder="Contoh: Berpedoman pada PermenPAN-RB No. 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat Unit Penyelenggara Pelayanan Publik..." 
                class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
              ></textarea>
            </div>

            <!-- File Laporan PDF SKM -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
              <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <div>
                  <label class="block font-bold text-slate-800 text-xs">Unggah Dokumen Laporan Resmi (PDF)</label>
                  <p class="text-[11px] text-slate-500">Berkas PDF hasil olah data SKM lengkap untuk diunduh oleh publik.</p>
                </div>
                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-slate-300 hover:border-emerald-500 text-slate-700 font-bold text-xs cursor-pointer shadow-2xs transition">
                  <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  <span>{{ uploadingSkmPdf ? 'Mengunggah...' : (skmForm.file_laporan ? 'Ganti PDF' : 'Unggah PDF') }}</span>
                  <input 
                    type="file" 
                    accept="application/pdf" 
                    class="hidden" 
                    @change="handleSkmPdfUpload" 
                    :disabled="uploadingSkmPdf" 
                  />
                </label>
              </div>

              <!-- Preview File -->
              <div v-if="skmForm.file_laporan" class="flex items-center justify-between bg-white p-3 rounded-xl border border-slate-200">
                <div class="flex items-center gap-2">
                  <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                  <span class="text-xs font-semibold text-slate-800 break-all">{{ skmForm.file_laporan }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <a 
                    :href="skmForm.file_laporan" 
                    target="_blank" 
                    class="text-xs font-bold text-emerald-700 hover:text-emerald-900"
                  >
                    Buka File
                  </a>
                  <button 
                    type="button" 
                    @click="skmForm.file_laporan = ''" 
                    class="text-xs font-bold text-rose-600 hover:text-rose-800 cursor-pointer"
                  >
                    Hapus
                  </button>
                </div>
              </div>
            </div>

            <!-- Status Aktif Toggle -->
            <div>
              <label class="inline-flex items-center gap-2 cursor-pointer">
                <input 
                  type="checkbox" 
                  v-model="skmForm.aktif" 
                  class="rounded text-emerald-600 focus:ring-emerald-500 h-4 w-4"
                />
                <span class="text-xs font-bold text-slate-700">Publikasikan SKM ini (Status Aktif)</span>
              </label>
            </div>
          </div>

          <!-- Sticky Footer Actions -->
          <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
            <span class="text-xs text-slate-400 italic hidden sm:inline">Kolom bertanda * wajib diisi</span>
            <div class="flex items-center gap-2.5 ml-auto">
              <button 
                type="button" 
                @click="showSkmModal = false" 
                class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold text-xs cursor-pointer transition"
              >
                Batal
              </button>
              <button 
                type="submit" 
                :disabled="savingSkm"
                class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold text-xs shadow-xs cursor-pointer transition"
              >
                <span v-if="savingSkm">Menyimpan...</span>
                <span v-else>{{ editSkmId ? 'Simpan Perubahan' : 'Simpan Periode SKM' }}</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Image Cropper Modal for Maklumat Poster -->
    <ImageCropperModal
      v-model:show="showCropper"
      :image-file="selectedImageFile"
      :default-aspect-ratio="cropperAspectRatio"
      @cropped="handleCroppedPoster"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import RichTextEditor from '../../components/RichTextEditor.vue';
import ImageCropperModal from '../../components/ImageCropperModal.vue';
import { AdminService } from '../../services/api';
import { useToast } from '../../composables/useToast';

const toast = useToast();
const activeTab = ref('layanan'); // 'layanan' | 'maklumat' | 'skm'
const loading = ref(true);
const saving = ref(false);
const layananList = ref([]);
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');
const persyaratanText = ref('');
const kategoriOptions = ref([]);
const isCustomKategori = ref(false);

// Cropper modal state for maklumat poster
const showCropper = ref(false);
const selectedImageFile = ref(null);
const cropperAspectRatio = ref(3 / 4);

const form = reactive({
  judul: '',
  kategori: 'Kependudukan',
  customKategori: '',
  icon: 'FileText',
  deskripsi: '',
  persyaratan: [],
  alur: '',
  waktu: '10 - 15 Menit',
  biaya: 'Gratis (Rp 0)'
});

/* -----------------------------------------------
 * MAKLUMAT PELAYANAN STATE
 * ----------------------------------------------- */
const loadingMaklumat = ref(false);
const savingMaklumat = ref(false);
const uploadingPoster = ref(false);
const maklumatHistory = ref([]);
const isNewMaklumat = ref(false);
const maklumatForm = reactive({
  id: null,
  judul: 'Maklumat Pelayanan Kelurahan Kraksaan Wetan',
  nomor_sk: '',
  motto: '',
  konten: '',
  gambar: '',
  aktif: true
});

/* -----------------------------------------------
 * SURVEI KEPUASAN MASYARAKAT (SKM) STATE
 * ----------------------------------------------- */
const loadingSkm = ref(false);
const savingSkm = ref(false);
const uploadingSkmPdf = ref(false);
const skmList = ref([]);
const showSkmModal = ref(false);
const editSkmId = ref(null);

const skmForm = reactive({
  tahun: new Date().getFullYear().toString(),
  periode: 'Semester I ' + new Date().getFullYear(),
  skor_ikm: 88.00,
  skala_maksimal: 100.00,
  mutu_pelayanan: 'A',
  predikat: 'Sangat Baik',
  jumlah_responden: 100,
  metodologi: '',
  unsur_penilaian: [],
  link_survei: '',
  file_laporan: '',
  aktif: true,
  urutan: 0
});

/* -----------------------------------------------
 * DATA FETCHING
 * ----------------------------------------------- */
const loadData = async () => {
  loading.value = true;
  try {
    const [data, kats] = await Promise.all([
      AdminService.getLayanan(),
      AdminService.getMasterKategori('layanan')
    ]);
    layananList.value = data || [];
    kategoriOptions.value = kats || [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const loadMaklumat = async () => {
  loadingMaklumat.value = true;
  try {
    const res = await AdminService.getMaklumatAdmin();
    const item = res?.data || res;
    if (item && !isNewMaklumat.value) {
      maklumatForm.id = item.id || null;
      maklumatForm.judul = item.judul || '';
      maklumatForm.nomor_sk = item.nomor_sk || '';
      maklumatForm.motto = item.motto || '';
      maklumatForm.konten = item.konten || '';
      maklumatForm.gambar = item.gambar || '';
      maklumatForm.aktif = !!item.aktif;
    }
    maklumatHistory.value = res?.riwayat || (item ? [item] : []);
  } catch (e) {
    console.error('Gagal mengambil maklumat:', e);
  } finally {
    loadingMaklumat.value = false;
  }
};

const loadSkm = async () => {
  loadingSkm.value = true;
  try {
    const res = await AdminService.getSurveiSkmAdmin();
    skmList.value = res || [];
  } catch (e) {
    console.error('Gagal mengambil SKM:', e);
  } finally {
    loadingSkm.value = false;
  }
};

/* -----------------------------------------------
 * LAYANAN ACTIONS
 * ----------------------------------------------- */
const openModal = (item = null) => {
  isCustomKategori.value = false;
  form.customKategori = '';
  if (item) {
    editId.value = item.id;
    form.judul = item.judul;
    form.kategori = item.kategori;
    form.icon = item.icon || 'FileText';
    form.deskripsi = item.deskripsi;
    form.alur = item.alur || '';
    form.waktu = item.waktu || '10 - 15 Menit';
    form.biaya = item.biaya || 'Gratis (Rp 0)';
    persyaratanText.value = (item.persyaratan || []).join('\n');
  } else {
    editId.value = null;
    form.judul = '';
    form.kategori = (kategoriOptions.value[0]?.nama || kategoriOptions.value[0]) || 'Kependudukan';
    form.icon = 'FileText';
    form.deskripsi = '';
    form.alur = '';
    form.waktu = '10 - 15 Menit';
    form.biaya = 'Gratis (Rp 0)';
    persyaratanText.value = '';
  }
  showModal.value = true;
};

const saveItem = async () => {
  if (saving.value) return;
  saving.value = true;
  form.persyaratan = persyaratanText.value
    .split('\n')
    .map(s => s.trim())
    .filter(s => s.length > 0);

  try {
    const payload = { ...form };
    if (isCustomKategori.value && form.customKategori?.trim()) {
      payload.kategori = form.customKategori.trim();
    }
    const res = await AdminService.saveLayanan(payload, editId.value);
    const msg = res.message || 'Layanan berhasil disimpan!';
    successMsg.value = msg;
    toast.success(msg, editId.value ? 'Layanan Diperbarui' : 'Layanan Ditambahkan');
    showModal.value = false;
    await loadData();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan layanan.';
    toast.error('Gagal menyimpan layanan: ' + errMsg);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Hapus layanan ini dari daftar?')) return;
  try {
    await AdminService.deleteLayanan(id);
    const msg = 'Layanan berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'Layanan Dihapus');
    await loadData();
  } catch (err) {
    toast.error('Gagal menghapus layanan.');
  }
};

const toggleAktifLayanan = async (item) => {
  const newStatus = !item.aktif;
  try {
    await AdminService.toggleAktifLayanan(item.id, newStatus);
    item.aktif = newStatus;
    const msg = `Status aktif layanan "${item.judul}" berhasil diubah menjadi ${newStatus ? 'Aktif' : 'Nonaktif'}.`;
    successMsg.value = msg;
    toast.success(msg, 'Status Layanan Diperbarui');
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal mengubah status aktif layanan.';
    toast.error('Gagal mengubah status aktif layanan: ' + errMsg);
  }
};

/* -----------------------------------------------
 * MAKLUMAT ACTIONS
 * ----------------------------------------------- */
const isValidImageFile = (file) => {
  if (!file) return false;
  const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
  const ext = file.name.split('.').pop()?.toLowerCase();
  const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
  return (allowedMimeTypes.includes(file.type) || file.type.startsWith('image/')) && allowedExts.includes(ext);
};

const handleMaklumatImageUpload = (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    toast.error('Format file poster tidak valid! Harap pilih file gambar (JPG, PNG, WebP).');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran file poster terlalu besar! Maksimal 10MB.');
    event.target.value = '';
    return;
  }

  cropperAspectRatio.value = 3 / 4;

  // Pratinjau instan seketika
  const reader = new FileReader();
  reader.onload = (e) => {
    maklumatForm.gambar = e.target.result;
  };
  reader.readAsDataURL(file);

  selectedImageFile.value = file;
  showCropper.value = true;
  event.target.value = '';
};

const handleCroppedPoster = async (croppedFile) => {
  // Pratinjau instan poster yang telah dipotong
  const reader = new FileReader();
  reader.onload = (e) => {
    maklumatForm.gambar = e.target.result;
  };
  reader.readAsDataURL(croppedFile);

  uploadingPoster.value = true;
  try {
    const res = await AdminService.uploadFile(croppedFile, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      maklumatForm.gambar = uploadedUrl;
      toast.success('Poster maklumat berhasil disesuaikan dan diunggah.');
    }
  } catch (err) {
    toast.warning('Pratinjau poster siap disimpan: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingPoster.value = false;
  }
};

const saveMaklumat = async (asNewVersion = false) => {
  if (savingMaklumat.value) return;
  savingMaklumat.value = true;
  try {
    const payload = {
      ...maklumatForm,
      is_new_version: asNewVersion || isNewMaklumat.value || !maklumatForm.id
    };
    const res = await AdminService.saveMaklumatAdmin(payload);
    const msg = res.message || 'Maklumat Pelayanan berhasil disimpan!';
    successMsg.value = msg;
    toast.success(msg, 'Maklumat Pelayanan');
    isNewMaklumat.value = false;
    await loadMaklumat();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan maklumat.';
    toast.error('Gagal menyimpan: ' + errMsg);
  } finally {
    savingMaklumat.value = false;
  }
};

const createNewMaklumat = () => {
  maklumatForm.id = null;
  maklumatForm.judul = 'Maklumat Pelayanan Kelurahan Kraksaan Wetan';
  maklumatForm.nomor_sk = '';
  maklumatForm.motto = '';
  maklumatForm.konten = '';
  maklumatForm.gambar = '';
  maklumatForm.aktif = true;
  isNewMaklumat.value = true;
};

const editMaklumatItem = (item) => {
  maklumatForm.id = item.id;
  maklumatForm.judul = item.judul || '';
  maklumatForm.nomor_sk = item.nomor_sk || '';
  maklumatForm.motto = item.motto || '';
  maklumatForm.konten = item.konten || '';
  maklumatForm.gambar = item.gambar || '';
  maklumatForm.aktif = !!item.aktif;
  isNewMaklumat.value = false;
  window.scrollTo({ top: 300, behavior: 'smooth' });
};

const toggleAktifMaklumat = async (item) => {
  try {
    const res = await AdminService.toggleAktifMaklumat(item.id);
    const msg = res.message || 'Status maklumat berhasil diubah.';
    successMsg.value = msg;
    toast.success(msg, 'Status Maklumat');
    await loadMaklumat();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal mengubah status maklumat.';
    toast.error(errMsg);
  }
};

const deleteMaklumatItem = async (item) => {
  if (!confirm(`Hapus riwayat maklumat "${item.judul}"?`)) return;
  try {
    await AdminService.deleteMaklumat(item.id);
    const msg = 'Maklumat berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'Maklumat Dihapus');
    await loadMaklumat();
  } catch (err) {
    toast.error('Gagal menghapus maklumat.');
  }
};

/* -----------------------------------------------
 * SURVEI SKM ACTIONS
 * ----------------------------------------------- */
const openSkmModal = (item = null) => {
  if (item) {
    editSkmId.value = item.id;
    skmForm.tahun = item.tahun;
    skmForm.periode = item.periode;
    skmForm.skor_ikm = item.skor_ikm;
    skmForm.skala_maksimal = item.skala_maksimal;
    skmForm.mutu_pelayanan = item.mutu_pelayanan;
    skmForm.predikat = item.predikat;
    skmForm.jumlah_responden = item.jumlah_responden;
    skmForm.metodologi = item.metodologi || '';
    skmForm.link_survei = item.link_survei || '';
    skmForm.file_laporan = item.file_laporan || '';
    skmForm.aktif = !!item.aktif;
    skmForm.urutan = item.urutan || 0;
    skmForm.unsur_penilaian = Array.isArray(item.unsur_penilaian)
      ? JSON.parse(JSON.stringify(item.unsur_penilaian))
      : [];
  } else {
    editSkmId.value = null;
    skmForm.tahun = new Date().getFullYear().toString();
    skmForm.periode = 'Semester I ' + new Date().getFullYear();
    skmForm.skor_ikm = 88.00;
    skmForm.skala_maksimal = 100.00;
    skmForm.mutu_pelayanan = 'A';
    skmForm.predikat = 'Sangat Baik';
    skmForm.jumlah_responden = 100;
    skmForm.metodologi = 'Pedoman PermenPAN-RB No. 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat Unit Penyelenggara Pelayanan Publik.';
    skmForm.link_survei = '';
    skmForm.file_laporan = '';
    skmForm.aktif = true;
    skmForm.urutan = 0;
    skmForm.unsur_penilaian = [
      { nama: '1. Persyaratan Pelayanan', nilai: 88.50 },
      { nama: '2. Prosedur Pelayanan', nilai: 89.00 },
      { nama: '3. Waktu Pelayanan', nilai: 87.50 },
      { nama: '4. Biaya/Tarif (Gratis)', nilai: 95.00 },
      { nama: '5. Produk Spesifikasi Layanan', nilai: 88.00 },
      { nama: '6. Kompetensi Pelaksana', nilai: 87.00 },
      { nama: '7. Perilaku Pelaksana', nilai: 89.50 },
      { nama: '8. Penanganan Pengaduan', nilai: 86.00 },
      { nama: '9. Sarana & Prasarana', nilai: 85.50 }
    ];
  }
  showSkmModal.value = true;
};

const addUnsurRow = () => {
  if (!skmForm.unsur_penilaian) {
    skmForm.unsur_penilaian = [];
  }
  skmForm.unsur_penilaian.push({
    nama: '',
    nilai: 85.00
  });
};

const removeUnsurRow = (index) => {
  skmForm.unsur_penilaian.splice(index, 1);
};

const handleSkmPdfUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  uploadingSkmPdf.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'document');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      skmForm.file_laporan = uploadedUrl;
      toast.success('Laporan PDF SKM berhasil diunggah.');
    }
  } catch (err) {
    toast.error('Gagal mengunggah PDF SKM.');
  } finally {
    uploadingSkmPdf.value = false;
    event.target.value = '';
  }
};

const saveSkmItem = async () => {
  if (savingSkm.value) return;
  savingSkm.value = true;
  try {
    const payload = {
      ...skmForm,
      unsur_penilaian: (skmForm.unsur_penilaian || []).filter(u => u.nama && u.nama.trim())
    };
    const res = await AdminService.saveSurveiSkm(payload, editSkmId.value);
    const msg = res.message || 'Data Survei SKM berhasil disimpan!';
    successMsg.value = msg;
    toast.success(msg, editSkmId.value ? 'SKM Diperbarui' : 'SKM Ditambahkan');
    showSkmModal.value = false;
    await loadSkm();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan data SKM.';
    toast.error('Gagal menyimpan SKM: ' + errMsg);
  } finally {
    savingSkm.value = false;
  }
};

const toggleAktifSkm = async (item) => {
  const newStatus = !item.aktif;
  try {
    await AdminService.toggleAktifSurveiSkm(item.id, newStatus);
    item.aktif = newStatus;
    const msg = `Status tayang SKM ${item.periode} berhasil diubah menjadi ${newStatus ? 'Tayang' : 'Draft'}.`;
    successMsg.value = msg;
    toast.success(msg, 'Status SKM');
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal mengubah status tayang SKM.';
    toast.error('Gagal mengubah status: ' + errMsg);
  }
};

const deleteSkmItem = async (id) => {
  if (!confirm('Hapus riwayat survei SKM ini? Berkas PDF laporan terkait juga akan dibersihkan.')) return;
  try {
    await AdminService.deleteSurveiSkm(id);
    const msg = 'Data Survei SKM berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'SKM Dihapus');
    await loadSkm();
  } catch (err) {
    toast.error('Gagal menghapus SKM.');
  }
};

onMounted(async () => {
  await Promise.all([
    loadData(),
    loadMaklumat(),
    loadSkm()
  ]);
});
</script>
