<template>
  <div class="space-y-6">
    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs">
      <div>
        <div class="flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
          <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Kelola Agenda Kegiatan</h1>
        </div>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
          Publikasikan jadwal kegiatan, rapat musyawarah, dan agenda kemasyarakatan. Agenda akan <strong>otomatis disembunyikan dari publik saat tanggal selesai terlewati</strong>.
        </p>
      </div>

      <button 
        type="button" 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs sm:text-sm transition shadow-sm hover:shadow cursor-pointer self-start sm:self-auto"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Agenda Baru</span>
      </button>
    </div>

    <!-- Ringkasan Statistik Status Agenda -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
      <div 
        @click="filterStatus = 'semua'; loadData()"
        class="bg-white p-4 rounded-2xl border transition cursor-pointer flex flex-col justify-between"
        :class="filterStatus === 'semua' ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/20' : 'border-slate-200/80 hover:border-slate-300'"
      >
        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Agenda</span>
        <div class="mt-2 flex items-baseline justify-between">
          <span class="text-2xl font-black text-slate-900">{{ counts.total || 0 }}</span>
          <span class="text-xs text-slate-500 font-medium">Semua Entri</span>
        </div>
      </div>

      <div 
        @click="filterStatus = 'berlangsung'; loadData()"
        class="bg-white p-4 rounded-2xl border transition cursor-pointer flex flex-col justify-between"
        :class="filterStatus === 'berlangsung' ? 'border-emerald-600 ring-2 ring-emerald-500/20 bg-emerald-50/20' : 'border-slate-200/80 hover:border-slate-300'"
      >
        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-700 flex items-center gap-1.5">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
          Berlangsung
        </span>
        <div class="mt-2 flex items-baseline justify-between">
          <span class="text-2xl font-black text-emerald-800">{{ counts.berlangsung || 0 }}</span>
          <span class="text-xs text-emerald-700 font-medium">Aktif Saat Ini</span>
        </div>
      </div>

      <div 
        @click="filterStatus = 'akan_datang'; loadData()"
        class="bg-white p-4 rounded-2xl border transition cursor-pointer flex flex-col justify-between"
        :class="filterStatus === 'akan_datang' ? 'border-blue-600 ring-2 ring-blue-500/20 bg-blue-50/20' : 'border-slate-200/80 hover:border-slate-300'"
      >
        <span class="text-[11px] font-bold uppercase tracking-wider text-blue-700">Akan Datang</span>
        <div class="mt-2 flex items-baseline justify-between">
          <span class="text-2xl font-black text-blue-800">{{ counts.akan_datang || 0 }}</span>
          <span class="text-xs text-blue-600 font-medium">Jadwal Nanti</span>
        </div>
      </div>

      <div 
        @click="filterStatus = 'selesai'; loadData()"
        class="bg-white p-4 rounded-2xl border transition cursor-pointer flex flex-col justify-between"
        :class="filterStatus === 'selesai' ? 'border-amber-600 ring-2 ring-amber-500/20 bg-amber-50/20' : 'border-slate-200/80 hover:border-slate-300'"
      >
        <span class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Selesai / Kedaluwarsa</span>
        <div class="mt-2 flex items-baseline justify-between">
          <span class="text-2xl font-black text-slate-700">{{ counts.selesai || 0 }}</span>
          <span class="text-[11px] text-amber-700 font-medium">Otomatis Hilang</span>
        </div>
      </div>

      <div 
        @click="filterStatus = 'nonaktif'; loadData()"
        class="bg-white p-4 rounded-2xl border transition cursor-pointer flex flex-col justify-between col-span-2 lg:col-span-1"
        :class="filterStatus === 'nonaktif' ? 'border-rose-600 ring-2 ring-rose-500/20 bg-rose-50/20' : 'border-slate-200/80 hover:border-slate-300'"
      >
        <span class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Draft / Nonaktif</span>
        <div class="mt-2 flex items-baseline justify-between">
          <span class="text-2xl font-black text-rose-800">{{ counts.nonaktif || 0 }}</span>
          <span class="text-xs text-rose-600 font-medium">Disembunyikan</span>
        </div>
      </div>
    </div>

    <!-- Filter Bar & Pencarian -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-center gap-3">
      <div class="relative flex-1 w-full">
        <input 
          type="text" 
          v-model="searchQuery" 
          @input="handleSearch"
          placeholder="Cari judul agenda, lokasi, atau penyelenggara..." 
          class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm"
        />
        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
      </div>

      <select 
        v-model="selectedKategori" 
        @change="loadData"
        class="px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm bg-white font-medium cursor-pointer w-full sm:w-auto"
      >
        <option value="Semua">Semua Kategori</option>
        <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
      </select>

      <select 
        v-model="filterStatus" 
        @change="loadData"
        class="px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm bg-white font-medium cursor-pointer w-full sm:w-auto"
      >
        <option value="semua">Semua Status</option>
        <option value="berlangsung">Sedang Berlangsung</option>
        <option value="akan_datang">Akan Datang</option>
        <option value="selesai">Selesai / Kedaluwarsa</option>
        <option value="nonaktif">Nonaktif</option>
      </select>
    </div>

    <!-- Loading State -->
    <LoadingSpinner v-if="loading" />

    <!-- Tabel Data Agenda -->
    <div v-else class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-xs sm:text-sm">
          <thead>
            <tr class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200 text-xs uppercase tracking-wider">
              <th class="py-3.5 px-4">Agenda & Foto</th>
              <th class="py-3.5 px-4">Jadwal Acara</th>
              <th class="py-3.5 px-4">Lokasi & Penyelenggara</th>
              <th class="py-3.5 px-4 text-center">Status Publikasi</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-if="agendaList.length === 0">
              <td colspan="5" class="py-12 text-center text-slate-400">
                Belum ada data agenda kegiatan yang sesuai filter.
              </td>
            </tr>
            <tr 
              v-for="item in agendaList" 
              :key="item.id" 
              class="hover:bg-slate-50/70 transition"
              :class="item.is_expired ? 'bg-slate-50/40' : ''"
            >
              <!-- Kolom Agenda & Foto -->
              <td class="py-4 px-4 max-w-xs sm:max-w-sm">
                <div class="flex items-start gap-3">
                  <div class="w-14 h-14 rounded-xl bg-slate-100 overflow-hidden shrink-0 border border-slate-200 shadow-xs">
                    <img 
                      v-if="item.foto" 
                      :src="item.foto" 
                      :alt="item.judul"
                      class="w-full h-full object-cover" 
                    />
                    <div v-else class="w-full h-full flex items-center justify-center text-slate-400 bg-slate-100 text-[10px] text-center font-bold p-1">
                      No Foto
                    </div>
                  </div>
                  <div class="space-y-1 min-w-0">
                    <span class="inline-block px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800">
                      {{ item.kategori || 'Umum' }}
                    </span>
                    <p class="font-bold text-slate-900 line-clamp-2 leading-snug">{{ item.judul }}</p>
                  </div>
                </div>
              </td>

              <!-- Kolom Jadwal -->
              <td class="py-4 px-4 whitespace-nowrap">
                <div class="space-y-1">
                  <p class="font-bold text-emerald-800 text-xs">{{ item.formatted_jadwal }}</p>
                  <p class="text-[11px] text-slate-400">
                    Mulai: {{ formatDateRaw(item.tanggal_mulai) }}
                  </p>
                </div>
              </td>

              <!-- Kolom Lokasi & Penyelenggara -->
              <td class="py-4 px-4">
                <div class="space-y-1">
                  <p class="font-semibold text-slate-800 line-clamp-1">{{ item.lokasi || 'Wilayah Kelurahan' }}</p>
                  <p class="text-xs text-slate-500">{{ item.penyelenggara || '-' }}</p>
                </div>
              </td>

              <!-- Kolom Status Publikasi -->
              <td class="py-4 px-4 text-center whitespace-nowrap">
                <div class="inline-flex flex-col items-center gap-1">
                  <span 
                    v-if="!item.is_aktif" 
                    class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200"
                  >
                    Nonaktif
                  </span>
                  <span 
                    v-else-if="item.status_agenda === 'berlangsung'" 
                    class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center gap-1.5"
                  >
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    Sedang Berlangsung
                  </span>
                  <span 
                    v-else-if="item.status_agenda === 'akan_datang'" 
                    class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-100 text-blue-800 border border-blue-200"
                  >
                    Akan Datang
                  </span>
                  <span 
                    v-else 
                    class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-900 border border-amber-200 flex items-center gap-1"
                    title="Telah melewati tanggal selesai sehingga otomatis disembunyikan dari halaman publik aktif"
                  >
                    <svg class="w-3 h-3 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Selesai (Otomatis Hilang)
                  </span>
                </div>
              </td>

              <!-- Kolom Aksi -->
              <td class="py-4 px-4 text-right whitespace-nowrap">
                <div class="inline-flex items-center gap-1.5">
                  <button 
                    type="button" 
                    @click="toggleStatus(item)" 
                    :title="item.is_aktif ? 'Nonaktifkan dari website' : 'Aktifkan kembali'"
                    class="p-1.5 rounded-lg border transition cursor-pointer"
                    :class="item.is_aktif ? 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' : 'border-slate-200 text-slate-400 hover:bg-slate-100'"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  </button>

                  <button 
                    type="button" 
                    @click="openModal(item)" 
                    title="Sunting Agenda"
                    class="p-1.5 rounded-lg border border-slate-200 text-blue-700 hover:bg-blue-50 transition cursor-pointer"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>

                  <button 
                    type="button" 
                    @click="deleteItem(item)" 
                    title="Hapus Agenda"
                    class="p-1.5 rounded-lg border border-slate-200 text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- MODAL FORM TAMBAH / EDIT AGENDA -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/70 backdrop-blur-xs animate-in fade-in duration-200"
    >
      <div class="bg-white rounded-3xl max-w-4xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
        <!-- Sticky Header Modal -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">
              {{ editId ? 'Sunting Agenda Kegiatan' : 'Tambah Agenda Kegiatan Baru' }}
            </h3>
            <p class="text-xs text-slate-500">
              Pastikan tanggal mulai dan tanggal selesai kegiatan diisi dengan akurat.
            </p>
          </div>
          <button 
            type="button" 
            @click="showModal = false" 
            class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer"
            title="Tutup Formulir"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Form Scrollable Body -->
        <form @submit.prevent="saveAgenda" class="flex flex-col flex-1 overflow-hidden">
          <div class="p-5 sm:p-6 overflow-y-auto flex-1 space-y-4 text-xs sm:text-sm">
            <!-- Baris 1: Judul Kegiatan -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Nama / Judul Agenda Kegiatan *</label>
              <input 
                type="text" 
                v-model="form.judul" 
                required 
                placeholder="Contoh: Rapat Musyawarah Rencana Kerja Pembangunan Kelurahan (Musrenbangkel)" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <!-- Baris 2: Kategori & Penyelenggara -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Kategori Kegiatan *</label>
                <select 
                  v-model="form.kategori" 
                  required 
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white font-medium cursor-pointer"
                >
                  <option v-for="kat in kategoriOptions" :key="kat" :value="kat">{{ kat }}</option>
                </select>
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">Penyelenggara / Penanggung Jawab</label>
                <input 
                  type="text" 
                  v-model="form.penyelenggara" 
                  placeholder="Contoh: Tim Penggerak PKK, Karang Taruna, dsb." 
                  class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
                />
              </div>
            </div>

            <!-- Baris 3: Tanggal Mulai & Tanggal Selesai (Fitur Otomatis Hilang) -->
            <div class="p-4 rounded-2xl bg-emerald-50/60 border border-emerald-200/80 space-y-3">
              <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                <span class="text-xs font-bold text-emerald-900 uppercase tracking-wider">Jadwal Pelaksanaan & Batas Waktu</span>
              </div>
              <p class="text-[11px] text-emerald-800 leading-relaxed">
                Fitur otomatis: Begitu waktu pada <strong>Tanggal & Waktu Selesai</strong> terlewati, agenda ini akan <strong>secara otomatis disembunyikan dari halaman publik aktif</strong> dan diarsipkan ke riwayat.
              </p>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div>
                  <label class="block font-bold text-slate-700 mb-1">Tanggal & Waktu Mulai *</label>
                  <input 
                    type="datetime-local" 
                    v-model="form.tanggal_mulai" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white font-medium"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1">Tanggal & Waktu Selesai *</label>
                  <input 
                    type="datetime-local" 
                    v-model="form.tanggal_selesai" 
                    required 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white font-medium"
                  />
                </div>
              </div>
            </div>

            <!-- Baris 4: Lokasi Tempat Acara -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Lokasi Tempat Acara</label>
              <input 
                type="text" 
                v-model="form.lokasi" 
                placeholder="Contoh: Balai Pertemuan Kelurahan Kraksaan Wetan, Posyandu Melati RW 02, dsb." 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <!-- Baris 5: Upload Foto / Poster Kegiatan -->
            <div class="space-y-2">
              <label class="block font-bold text-slate-700">Foto / Poster Kegiatan (Opsional)</label>
              <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <!-- Preview Gambar -->
                <div class="w-20 h-20 rounded-xl bg-white border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center shadow-xs">
                  <img v-if="form.foto" :src="form.foto" alt="Preview Foto" class="w-full h-full object-cover" />
                  <span v-else class="text-[10px] text-slate-400 text-center p-1">Belum Ada</span>
                </div>

                <!-- Kontrol Unggah -->
                <div class="flex-1 space-y-2 w-full">
                  <div class="flex flex-wrap items-center gap-2">
                    <label class="px-3.5 py-2 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs cursor-pointer shadow-xs transition flex items-center gap-1.5">
                      <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                      <span>{{ uploadingPhoto ? 'Mengunggah...' : 'Pilih Berkas Gambar' }}</span>
                      <input 
                        type="file" 
                        accept="image/*" 
                        @change="handlePhotoUpload" 
                        :disabled="uploadingPhoto" 
                        class="hidden" 
                      />
                    </label>

                    <button 
                      v-if="form.foto" 
                      type="button" 
                      @click="form.foto = ''" 
                      class="px-3 py-2 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                    >
                      Hapus Foto
                    </button>
                  </div>
                  <p class="text-[11px] text-slate-400">Format: JPG, PNG, WebP (Maksimal 10MB).</p>
                </div>
              </div>
            </div>

            <!-- Baris 6: Deskripsi Kegiatan (RichTextEditor) -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Rincian & Deskripsi Kegiatan *</label>
              <RichTextEditor 
                v-model="form.deskripsi" 
                placeholder="Tuliskan susunan agenda, petunjuk kehadiran, agenda rapat, atau berkas yang perlu dibawa warga..." 
                height="180px"
                maxHeight="300px"
              />
            </div>

            <!-- Baris 7: Status Publikasi -->
            <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center gap-2.5">
              <input 
                type="checkbox" 
                id="is_aktif" 
                v-model="form.is_aktif" 
                class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500 cursor-pointer"
              />
              <label for="is_aktif" class="font-bold text-slate-700 cursor-pointer select-none text-xs">
                Publikasikan ke Website (Status Aktif)
              </label>
            </div>
          </div>

          <!-- Sticky Footer Action Bar -->
          <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-100 flex items-center justify-between shrink-0">
            <div class="text-xs text-slate-400">
              <span v-if="editId" class="text-emerald-700 font-semibold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                Mode Sunting Agenda Kegiatan
              </span>
              <span v-else class="text-slate-500">
                Menambahkan jadwal agenda baru
              </span>
            </div>
            <div class="flex items-center gap-2.5">
              <button 
                type="button" 
                @click="showModal = false" 
                class="px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 font-bold transition text-xs sm:text-sm cursor-pointer"
              >
                Batal
              </button>
              <button 
                type="submit" 
                :disabled="saving || uploadingPhoto" 
                class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold transition shadow-xs text-xs sm:text-sm cursor-pointer flex items-center gap-1.5"
              >
                <span v-if="saving">Menyimpan...</span>
                <span v-else>{{ editId ? 'Simpan Perubahan' : 'Tambahkan Agenda' }}</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import RichTextEditor from '../../components/RichTextEditor.vue';
import { AdminService } from '../../services/api';
import { useToast } from '../../composables/useToast';

const toast = useToast();

const loading = ref(true);
const saving = ref(false);
const uploadingPhoto = ref(false);
const agendaList = ref([]);
const counts = ref({ total: 0, berlangsung: 0, akan_datang: 0, selesai: 0, nonaktif: 0 });
const searchQuery = ref('');
const filterStatus = ref('semua');
const selectedKategori = ref('Semua');
const kategoriOptions = ref(['Pemerintahan', 'Pelayanan Warga', 'Sosial & Kemasyarakatan', 'Pembangunan', 'Kesehatan & Posyandu', 'Keagamaan', 'Pemuda & Olahraga']);

const showModal = ref(false);
const editId = ref(null);

const form = reactive({
  judul: '',
  kategori: 'Pemerintahan',
  penyelenggara: '',
  lokasi: '',
  tanggal_mulai: '',
  tanggal_selesai: '',
  foto: '',
  deskripsi: '',
  is_aktif: true,
});

let searchDebounce = null;

const loadData = async () => {
  loading.value = true;
  try {
    const params = {
      status: filterStatus.value
    };
    if (searchQuery.value.trim()) {
      params.search = searchQuery.value.trim();
    }
    if (selectedKategori.value !== 'Semua') {
      params.kategori = selectedKategori.value;
    }

    const res = await AdminService.getAgenda(params);
    if (res && res.data) {
      if (Array.isArray(res.data)) {
        const existingNew = agendaList.value.filter(item => !res.data.some(a => a.id === item.id));
        agendaList.value = [...existingNew, ...res.data];
      } else {
        agendaList.value = res.data;
      }
      counts.value = res.counts || { total: 0, berlangsung: 0, akan_datang: 0, selesai: 0, nonaktif: 0 };
    }
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.error('Gagal memuat daftar agenda: ' + errText, 'Gagal Memuat');
  } finally {
    loading.value = false;
  }
};

const handleSearch = () => {
  clearTimeout(searchDebounce);
  searchDebounce = setTimeout(() => {
    loadData();
  }, 350);
};

const formatDateForInput = (dateStr) => {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return '';
  // Format YYYY-MM-DDTHH:mm
  const pad = (n) => String(n).padStart(2, '0');
  return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
};

const formatDateRaw = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }) + ' WIB';
};

const openModal = (item = null) => {
  if (item) {
    editId.value = item.id;
    form.judul = item.judul;
    form.kategori = item.kategori || 'Pemerintahan';
    form.penyelenggara = item.penyelenggara || '';
    form.lokasi = item.lokasi || '';
    form.tanggal_mulai = formatDateForInput(item.tanggal_mulai);
    form.tanggal_selesai = formatDateForInput(item.tanggal_selesai);
    form.foto = item.foto || '';
    form.deskripsi = item.deskripsi || '';
    form.is_aktif = item.is_aktif !== undefined ? Boolean(item.is_aktif) : true;
  } else {
    editId.value = null;
    form.judul = '';
    form.kategori = 'Pemerintahan';
    form.penyelenggara = 'Pemerintah Kelurahan Kraksaan Wetan';
    form.lokasi = 'Balai Kelurahan Kraksaan Wetan';
    
    // Default: besok jam 08:00 sampai 12:00
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    tomorrow.setHours(8, 0, 0, 0);
    const tomorrowEnd = new Date(tomorrow);
    tomorrowEnd.setHours(12, 0, 0, 0);
    
    form.tanggal_mulai = formatDateForInput(tomorrow);
    form.tanggal_selesai = formatDateForInput(tomorrowEnd);
    form.foto = '';
    form.deskripsi = '';
    form.is_aktif = true;
  }
  showModal.value = true;
};

const handlePhotoUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!file.type.startsWith('image/')) {
    toast.warning('Format berkas tidak valid! Harap unggah berkas gambar (JPG, PNG, WebP).', 'Format Tidak Sesuai');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.warning('Ukuran gambar terlalu besar! Maksimal 10MB.', 'Ukuran Terlalu Besar');
    event.target.value = '';
    return;
  }

  // Pratinjau instan seketika
  const reader = new FileReader();
  reader.onload = (e) => {
    form.foto = e.target.result;
  };
  reader.readAsDataURL(file);

  uploadingPhoto.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      form.foto = uploadedUrl;
      toast.success('Foto poster kegiatan berhasil diunggah.', 'Upload Berhasil');
    }
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.warning('Pratinjau foto siap disimpan: ' + errText, 'Pratinjau Lokal');
  } finally {
    uploadingPhoto.value = false;
    event.target.value = '';
  }
};

const saveAgenda = async () => {
  if (!form.judul.trim()) {
    toast.warning('Judul agenda kegiatan wajib diisi.', 'Validasi Formulir');
    return;
  }
  if (!form.tanggal_mulai || !form.tanggal_selesai) {
    toast.warning('Tanggal mulai dan selesai kegiatan wajib diisi.', 'Validasi Formulir');
    return;
  }
  if (new Date(form.tanggal_selesai) < new Date(form.tanggal_mulai)) {
    toast.warning('Tanggal selesai tidak boleh lebih awal dari tanggal mulai kegiatan.', 'Validasi Tanggal');
    return;
  }

  saving.value = true;
  try {
    const res = await AdminService.saveAgenda(form, editId.value);
    const msg = res.message || (editId.value ? 'Agenda kegiatan berhasil diperbarui!' : 'Agenda kegiatan baru berhasil ditambahkan!');
    toast.success(msg, 'Agenda Kegiatan');
    showModal.value = false;
    if (res?.data) {
      if (editId.value) {
        const idx = agendaList.value.findIndex(a => a.id === editId.value);
        if (idx !== -1) agendaList.value[idx] = res.data;
      } else {
        agendaList.value.unshift(res.data);
      }
    }
    await loadData();
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Terjadi kesalahan sistem';
    toast.error('Gagal menyimpan agenda: ' + errText, 'Gagal Menyimpan');
  } finally {
    saving.value = false;
  }
};

const toggleStatus = async (item) => {
  try {
    const res = await AdminService.toggleAktifAgenda(item.id);
    const msg = res.message || 'Status publikasi agenda berhasil diubah.';
    toast.success(msg, 'Status Publikasi');
    await loadData();
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.error('Gagal mengubah status agenda: ' + errText, 'Gagal Mengubah');
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus agenda kegiatan "${item.judul}"? Tindakan ini tidak dapat dibatalkan.`)) {
    return;
  }

  try {
    const res = await AdminService.deleteAgenda(item.id);
    toast.success(res.message || 'Agenda kegiatan berhasil dihapus.', 'Agenda Dihapus');
    await loadData();
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.error('Gagal menghapus agenda kegiatan: ' + errText, 'Gagal Menghapus');
  }
};

onMounted(() => {
  loadData();
});
</script>
