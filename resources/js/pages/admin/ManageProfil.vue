<template>
  <div class="space-y-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <h2 class="text-xl font-bold text-slate-900">Kelola Profil Kelurahan & Aparatur</h2>
      <p class="text-xs text-slate-500">Perbarui visi, misi, deskripsi wilayah, data pimpinan lurah, serta susunan aparatur kelurahan.</p>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700">&times;</button>
    </div>

    <LoadingSpinner v-if="loading" />
    <template v-else>
      <!-- Form Profil & Visi Misi -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs">
        <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
          Informasi Utama & Visi Misi
        </h3>

        <form @submit.prevent="saveProfil" class="space-y-5 text-xs sm:text-sm">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            <div>
              <label class="block font-bold text-slate-700 mb-1">Nama Kelurahan *</label>
              <input type="text" v-model="profilForm.nama" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Alamat Kantor *</label>
              <input type="text" v-model="profilForm.alamat" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none" />
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Kecamatan</label>
              <input type="text" v-model="profilForm.kecamatan" placeholder="Kraksaan" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Kabupaten / Kota</label>
              <input type="text" v-model="profilForm.kabupaten" placeholder="Kabupaten Probolinggo" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Provinsi</label>
              <input type="text" v-model="profilForm.provinsi" placeholder="Jawa Timur" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Kode Pos</label>
              <input type="text" v-model="profilForm.kode_pos" placeholder="67282" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Telepon Kantor</label>
              <input type="text" v-model="profilForm.telepon" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Email Resmi</label>
              <input type="email" v-model="profilForm.email" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Jam Pelayanan</label>
              <input type="text" v-model="profilForm.jam_kerja" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none" />
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Deskripsi Ringkas Wilayah *</label>
            <RichTextEditor 
              v-model="profilForm.deskripsi" 
              placeholder="Tulis ringkasan profil wilayah kelurahan..." 
              height="180px"
            />
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Sejarah Kelurahan</label>
            <RichTextEditor 
              v-model="profilForm.sejarah" 
              placeholder="Tuliskan riwayat sejarah berdirinya kelurahan..." 
              height="240px"
            />
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Visi Kelurahan *</label>
            <textarea rows="2" v-model="profilForm.visi" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"></textarea>
          </div>

          <!-- Batas Wilayah Administratif -->
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <span>Batas-Batas Wilayah Administratif</span>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Dinamis</span>
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">Tentukan batas geografis kelurahan di empat penjuru mata angin.</p>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Batas Utara</label>
                <input type="text" v-model="profilForm.batas_wilayah.utara" placeholder="Contoh: Desa Kalibuntu & Selat Madura" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">Batas Selatan</label>
                <input type="text" v-model="profilForm.batas_wilayah.selatan" placeholder="Contoh: Desa Sumberlele & Kecamatan Besuk" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">Batas Timur</label>
                <input type="text" v-model="profilForm.batas_wilayah.timur" placeholder="Contoh: Desa Bulu & Desa Rondokuning" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">Batas Barat</label>
                <input type="text" v-model="profilForm.batas_wilayah.barat" placeholder="Contoh: Sungai Kraksaan & Kelurahan Patokan" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-600 outline-none text-xs sm:text-sm" />
              </div>
            </div>
          </div>

          <!-- Potensi Unggulan Kelurahan -->
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div>
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <span>Potensi Unggulan Kelurahan</span>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Dinamis</span>
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">Kelola kartu sorotan potensi sektor unggulan kelurahan di halaman Tentang Kami.</p>
              </div>
              <button 
                type="button" 
                @click="addPotensi" 
                class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs self-start sm:self-auto transition shadow-xs"
              >
                + Tambah Potensi
              </button>
            </div>

            <div class="space-y-3">
              <div 
                v-for="(pot, pIdx) in profilForm.potensi_unggulan" 
                :key="pIdx" 
                class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 relative shadow-xs"
              >
                <div class="flex items-center justify-between gap-2">
                  <span class="text-xs font-bold text-emerald-800">Potensi #{{ pIdx + 1 }}</span>
                  <button 
                    type="button" 
                    @click="removePotensi(pIdx)" 
                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold"
                  >
                    Hapus
                  </button>
                </div>
                <input 
                  type="text" 
                  v-model="pot.judul" 
                  placeholder="Judul Potensi (Contoh: UMKM Kuliner & Niaga)" 
                  class="w-full px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs font-bold" 
                />
                <textarea 
                  rows="2" 
                  v-model="pot.deskripsi" 
                  placeholder="Keterangan singkat potensi ini..." 
                  class="w-full px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Tonggak Linimasa Sejarah (Timeline) -->
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div>
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <span>Tonggak Perkembangan Sejarah (Timeline)</span>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Dinamis</span>
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">Kelola butir-butir linimasa sejarah perkembangan kelurahan di halaman Sejarah.</p>
              </div>
              <button 
                type="button" 
                @click="addTimeline" 
                class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs self-start sm:self-auto transition shadow-xs"
              >
                + Tambah Linimasa
              </button>
            </div>

            <div class="space-y-3">
              <div 
                v-for="(tl, tIdx) in profilForm.sejarah_timeline" 
                :key="tIdx" 
                class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 relative shadow-xs"
              >
                <div class="flex items-center justify-between gap-2">
                  <span class="text-xs font-bold text-emerald-800">Periode #{{ tIdx + 1 }}</span>
                  <button 
                    type="button" 
                    @click="removeTimeline(tIdx)" 
                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold"
                  >
                    Hapus
                  </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  <input 
                    type="text" 
                    v-model="tl.tahun" 
                    placeholder="Era / Tahun (Contoh: Tahun 2010 - Sekarang)" 
                    class="w-full px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs font-bold" 
                  />
                  <input 
                    type="text" 
                    v-model="tl.judul" 
                    placeholder="Sub-judul (Contoh: Ibu Kota Kabupaten)" 
                    class="w-full px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs" 
                  />
                </div>
                <textarea 
                  rows="2" 
                  v-model="tl.deskripsi" 
                  placeholder="Keterangan capaian atau peristiwa bersejarah..." 
                  class="w-full px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Tata Nilai Pelayanan Budaya Kerja -->
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div>
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <span>Tata Nilai Pelayanan (Budaya Kerja ASN)</span>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Dinamis</span>
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">Kelola kartu nilai-nilai pelayanan di halaman Visi & Misi.</p>
              </div>
              <button 
                type="button" 
                @click="addTataNilai" 
                class="px-3 py-1.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs self-start sm:self-auto transition shadow-xs"
              >
                + Tambah Tata Nilai
              </button>
            </div>

            <div class="space-y-3">
              <div 
                v-for="(val, vIdx) in profilForm.tata_nilai" 
                :key="vIdx" 
                class="p-4 bg-white rounded-xl border border-slate-200 space-y-2 relative shadow-xs"
              >
                <div class="flex items-center justify-between gap-2">
                  <span class="text-xs font-bold text-emerald-800">Nilai #{{ vIdx + 1 }}</span>
                  <button 
                    type="button" 
                    @click="removeTataNilai(vIdx)" 
                    class="text-rose-600 hover:text-rose-800 text-xs font-semibold"
                  >
                    Hapus
                  </button>
                </div>
                <input 
                  type="text" 
                  v-model="val.judul" 
                  placeholder="Nama Nilai (Contoh: Berorientasi Pelayanan)" 
                  class="w-full px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs font-bold" 
                />
                <textarea 
                  rows="2" 
                  v-model="val.deskripsi" 
                  placeholder="Penerapan dan makna nilai pelayanan..." 
                  class="w-full px-3 py-1.5 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none text-xs"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Bagian Pimpinan Lurah -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-4 mt-6">
            <h4 class="font-bold text-slate-900 text-sm">Data Kepala Kelurahan (Lurah)</h4>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lurah</label>
                <input type="text" v-model="profilForm.lurah_nama" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">NIP Lurah</label>
                <input type="text" v-model="profilForm.lurah_nip" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">Jabatan Pimpinan</label>
                <input type="text" v-model="profilForm.lurah_jabatan" placeholder="Lurah Kraksaan Wetan" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white" />
              </div>
            </div>

            <!-- Upload Foto Lurah -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Foto Resmi Lurah</label>
              <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Preview Foto Lurah -->
                <div class="w-16 h-20 sm:w-20 sm:h-24 rounded-xl border border-slate-200 bg-white overflow-hidden flex items-center justify-center shrink-0 shadow-xs">
                  <img 
                    v-if="previewLurahFoto || profilForm.lurah_foto" 
                    :src="previewLurahFoto || profilForm.lurah_foto" 
                    alt="Foto Lurah" 
                    class="w-full h-full object-cover object-top"
                  />
                  <span v-else class="text-slate-400 text-xs text-center p-2">Belum ada foto</span>
                </div>

                <!-- Kontrol Upload & URL -->
                <div class="flex-1 w-full space-y-2">
                  <div class="flex flex-col sm:flex-row gap-2">
                    <input 
                      type="text" 
                      v-model="profilForm.lurah_foto" 
                      placeholder="Masukkan URL atau unggah file foto Lurah..." 
                      class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    />
                    <label class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1.5 shadow-xs transition whitespace-nowrap">
                      <span v-if="uploadingLurahFoto">Mengunggah...</span>
                      <span v-else class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Pilih & Unggah Foto
                      </span>
                      <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleLurahFotoUpload" :disabled="uploadingLurahFoto" />
                    </label>
                    <button 
                      v-if="profilForm.lurah_foto" 
                      type="button" 
                      @click="profilForm.lurah_foto = ''" 
                      class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl text-xs transition whitespace-nowrap"
                    >
                      Hapus Foto
                    </button>
                  </div>
                  <p class="text-[11px] text-slate-500">Format: JPG, PNG, atau WebP resmi (Maks. 10MB).</p>
                </div>
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Sambutan Lurah</label>
              <RichTextEditor 
                v-model="profilForm.lurah_sambutan" 
                placeholder="Tuliskan kata sambutan Kepala Kelurahan..." 
                height="200px"
              />
            </div>

          </div>

          <!-- Pengaturan Saluran Pengaduan Warga (SP4N-LAPOR! & Halo SAE) -->
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4 mt-6">
            <div>
              <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                <span>Pengaturan Saluran Pengaduan Warga (SP4N-LAPOR! & Halo SAE)</span>
                <span class="px-2 py-0.5 rounded-full bg-rose-100 text-rose-800 text-[10px] font-bold">Terintegrasi WA</span>
              </h4>
              <p class="text-xs text-slate-500 mt-0.5">Tentukan tautan resmi portal SP4N-LAPOR! dan nomor WhatsApp Halo SAE terintegrasi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Tautan Portal SP4N-LAPOR! *</label>
                <input 
                  type="url" 
                  v-model="profilForm.link_span_lapor" 
                  placeholder="https://www.lapor.go.id/" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs" 
                />
                <p class="text-[10px] text-slate-400 mt-1">Portal pengaduan nasional RI (default: https://www.lapor.go.id/).</p>
              </div>

              <div>
                <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp Halo SAE *</label>
                <input 
                  type="text" 
                  v-model="profilForm.halo_sae_wa" 
                  placeholder="082131001001" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs" 
                />
                <p class="text-[10px] text-slate-400 mt-1">Nomor WhatsApp yang langsung terhubung ke wa.me warga.</p>
              </div>

              <div class="sm:col-span-2">
                <label class="block font-bold text-slate-700 mb-1">Tautan Web Portal Halo SAE (Opsional)</label>
                <input 
                  type="url" 
                  v-model="profilForm.halo_sae_link" 
                  placeholder="https://halosae.probolinggokab.go.id" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-xs" 
                />
              </div>
            </div>
          </div>

          <div class="text-right pt-2">
            <button type="submit" :disabled="saving" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold">
              <span v-if="saving">Menyimpan...</span>
              <span v-else>Simpan Perubahan Profil</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Bagian Aparatur Perangkat Kelurahan -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
            Susunan Perangkat & Aparatur Kelurahan
          </h3>
          <button @click="openPerangkatModal()" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 font-bold text-xs hover:bg-emerald-100">
            + Tambah Aparatur
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">Foto</th>
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Jabatan</th>
                <th class="py-3 px-4">Tugas / Bidang</th>
                <th class="py-3 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
              <tr v-for="p in perangkatList" :key="p.id" class="hover:bg-slate-50">
                <td class="py-2.5 px-4">
                  <div class="w-9 h-11 rounded-lg overflow-hidden border border-slate-200 bg-slate-100 flex items-center justify-center shrink-0">
                    <img v-if="p.foto" :src="p.foto" :alt="p.nama" class="w-full h-full object-cover object-top" />
                    <span v-else class="text-xs font-bold text-slate-400">{{ p.nama.charAt(0) }}</span>
                  </div>
                </td>
                <td class="py-3 px-4 font-bold text-slate-900">{{ p.nama }}</td>
                <td class="py-3 px-4 text-emerald-700 font-semibold">{{ p.jabatan }}</td>
                <td class="py-3 px-4">{{ p.bidang || '-' }}</td>
                <td class="py-3 px-4 text-right whitespace-nowrap">
                  <button @click="openPerangkatModal(p)" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 font-semibold mr-1 cursor-pointer">Edit</button>
                  <button @click="deletePerangkat(p.id)" class="px-2.5 py-1 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold cursor-pointer">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>

    <!-- Modal Aparatur -->
    <div v-if="showPerangkatModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
          <h4 class="text-base font-bold text-slate-900">{{ perangkatEditId ? 'Edit Aparatur' : 'Tambah Aparatur' }}</h4>
          <button 
            type="button" 
            @click="showPerangkatModal = false" 
            class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer"
            title="Tutup"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <form @submit.prevent="savePerangkat" class="space-y-3 text-xs sm:text-sm">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Nama Lengkap *</label>
            <input type="text" v-model="perangkatForm.nama" required class="w-full px-3 py-2 rounded-xl border border-slate-200" />
          </div>
          <div>
            <label class="block font-bold text-slate-700 mb-1">Jabatan *</label>
            <input type="text" v-model="perangkatForm.jabatan" required placeholder="Kasi Pemerintahan / Staf" class="w-full px-3 py-2 rounded-xl border border-slate-200" />
          </div>
          <div>
            <label class="block font-bold text-slate-700 mb-1">Tugas / Bidang</label>
            <input type="text" v-model="perangkatForm.bidang" placeholder="Trantibum & Kependudukan" class="w-full px-3 py-2 rounded-xl border border-slate-200" />
          </div>

          <!-- Foto Aparatur -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Foto Aparatur (Opsional)</label>
            <div class="flex items-center gap-3">
              <div class="w-12 h-14 rounded-xl border border-slate-200 bg-slate-100 overflow-hidden flex items-center justify-center shrink-0">
                <img v-if="previewPerangkatFoto || perangkatForm.foto" :src="previewPerangkatFoto || perangkatForm.foto" alt="Preview" class="w-full h-full object-cover object-top" />
                <span v-else class="text-slate-400 text-xs">Foto</span>
              </div>
              <div class="flex-1 space-y-1.5">
                <div class="flex gap-2">
                  <input 
                    type="text" 
                    v-model="perangkatForm.foto" 
                    placeholder="URL foto atau pilih file..." 
                    class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 text-xs" 
                  />
                  <label class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-xs flex items-center justify-center gap-1 shadow-2xs transition whitespace-nowrap">
                    <span v-if="uploadingPerangkatFoto">...</span>
                    <span v-else>Upload</span>
                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="handlePerangkatFotoUpload" :disabled="uploadingPerangkatFoto" />
                  </label>
                  <button 
                    v-if="perangkatForm.foto" 
                    type="button" 
                    @click="perangkatForm.foto = ''" 
                    class="px-2.5 py-1.5 bg-rose-50 text-rose-700 hover:bg-rose-100 rounded-xl text-xs font-semibold cursor-pointer"
                    title="Hapus Foto"
                  >
                    &times;
                  </button>
                </div>
                <p class="text-[10px] text-slate-400">Format: JPG, PNG, atau WebP (Maks. 10MB)</p>
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-2 pt-3">
            <button type="button" @click="showPerangkatModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-semibold cursor-pointer">Batal</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 text-white font-bold cursor-pointer">Simpan</button>
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
import { AdminService, KelurahanService } from '../../services/api';
import { useToast } from '../../composables/useToast';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const uploadingLogo = ref(false);
const uploadingHero = ref(false);
const uploadingLurahFoto = ref(false);
const uploadingPerangkatFoto = ref(false);
const previewLurahFoto = ref('');
const previewPerangkatFoto = ref('');
const successMsg = ref('');
const misiText = ref('');
const perangkatList = ref([]);
const showPerangkatModal = ref(false);
const perangkatEditId = ref(null);

const isValidImageFile = (file) => {
  if (!file) return false;
  const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif', 'image/jpg'];
  const ext = file.name.split('.').pop()?.toLowerCase();
  const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'];
  return (allowedMimeTypes.includes(file.type) || file.type.startsWith('image/')) && allowedExts.includes(ext);
};

const handleLogoUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    toast.warning('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).', 'Format Tidak Didukung');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.warning('Ukuran file logo terlalu besar! Maksimal 10MB.', 'File Terlalu Besar');
    event.target.value = '';
    return;
  }

  uploadingLogo.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      profilForm.logo = uploadedUrl;
      const msg = 'Logo baru berhasil diunggah! Klik "Simpan Perubahan Profil" untuk menerapkan.';
      successMsg.value = msg;
      toast.success(msg, 'Upload Logo Berhasil');
    }
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.error('Gagal mengunggah logo: ' + errText, 'Upload Gagal');
  } finally {
    uploadingLogo.value = false;
    event.target.value = '';
  }
};

const handleHeroUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    toast.warning('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).', 'Format Tidak Didukung');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.warning('Ukuran file foto hero terlalu besar! Maksimal 10MB.', 'File Terlalu Besar');
    event.target.value = '';
    return;
  }

  uploadingHero.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      profilForm.hero_image = uploadedUrl;
      const msg = 'Foto latar hero banner berhasil diunggah! Klik "Simpan Perubahan Profil" untuk menerapkan.';
      successMsg.value = msg;
      toast.success(msg, 'Upload Hero Berhasil');
    }
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.error('Gagal mengunggah foto hero: ' + errText, 'Upload Gagal');
  } finally {
    uploadingHero.value = false;
    event.target.value = '';
  }
};

const handleLurahFotoUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    toast.warning('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).', 'Format Tidak Didukung');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.warning('Ukuran file foto Lurah terlalu besar! Maksimal 10MB.', 'File Terlalu Besar');
    event.target.value = '';
    return;
  }

  // Pratinjau instan seketika
  const reader = new FileReader();
  reader.onload = (e) => {
    previewLurahFoto.value = e.target.result;
    profilForm.lurah_foto = e.target.result;
  };
  reader.readAsDataURL(file);

  uploadingLurahFoto.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      profilForm.lurah_foto = uploadedUrl;
      const msg = 'Foto Lurah berhasil diunggah! Klik "Simpan Perubahan Profil" untuk menerapkan.';
      successMsg.value = msg;
      toast.success(msg, 'Upload Foto Lurah');
    }
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.warning('Pratinjau foto Lurah siap disimpan: ' + errText, 'Pratinjau Lokal');
  } finally {
    uploadingLurahFoto.value = false;
    event.target.value = '';
  }
};

const handlePerangkatFotoUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    toast.warning('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP).', 'Format Tidak Didukung');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.warning('Ukuran file foto aparatur terlalu besar! Maksimal 10MB.', 'File Terlalu Besar');
    event.target.value = '';
    return;
  }

  // Pratinjau instan seketika
  const reader = new FileReader();
  reader.onload = (e) => {
    previewPerangkatFoto.value = e.target.result;
    perangkatForm.foto = e.target.result;
  };
  reader.readAsDataURL(file);

  uploadingPerangkatFoto.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    const uploadedUrl = res?.data?.url || res?.url;
    if (uploadedUrl) {
      perangkatForm.foto = uploadedUrl;
      toast.success('Foto aparatur berhasil diunggah!');
    }
  } catch (err) {
    const errText = err.response?.data?.message || err.message;
    toast.warning('Pratinjau foto aparatur siap disimpan: ' + errText, 'Pratinjau Lokal');
  } finally {
    uploadingPerangkatFoto.value = false;
    event.target.value = '';
  }
};


const profilForm = reactive({
  nama: '',
  kecamatan: '',
  kabupaten: '',
  provinsi: '',
  kode_pos: '',
  logo: '',
  hero_mode: 'slider',
  hero_image: '',
  alamat: '',
  telepon: '',
  email: '',
  jam_kerja: '',
  deskripsi: '',
  sejarah: '',
  visi: '',
  misi: [],
  batas_wilayah: {
    utara: '',
    selatan: '',
    timur: '',
    barat: ''
  },
  potensi_unggulan: [],
  tata_nilai: [],
  sejarah_timeline: [],
  custom_nav_menus: {
    profil: [],
    pemerintahan: [],
    informasi: []
  },
  lurah_nama: '',
  lurah_nip: '',
  lurah_jabatan: 'Lurah Kraksaan Wetan',
  lurah_sambutan: '',
  lurah_foto: '',
  link_span_lapor: 'https://www.lapor.go.id/',
  halo_sae_wa: '082131001001',
  halo_sae_link: 'https://halosae.probolinggokab.go.id'
});

const perangkatForm = reactive({
  nama: '',
  jabatan: '',
  bidang: '',
  foto: ''
});

const loadData = async () => {
  loading.value = true;
  try {
    const data = await KelurahanService.getProfil();
    profilForm.nama = data.nama || '';
    profilForm.kecamatan = data.kecamatan || '';
    profilForm.kabupaten = data.kabupaten || '';
    profilForm.provinsi = data.provinsi || '';
    profilForm.kode_pos = data.kode_pos || '';
    profilForm.logo = data.logo || '';
    profilForm.hero_mode = data.hero_mode || 'slider';
    profilForm.hero_image = data.hero_image || '';
    profilForm.alamat = data.alamat || '';
    profilForm.telepon = data.telepon || '';
    profilForm.email = data.email || '';
    profilForm.jam_kerja = data.jam_kerja || '';
    profilForm.deskripsi = data.deskripsi || '';
    profilForm.sejarah = data.sejarah || '';
    profilForm.visi = data.visi || '';
    profilForm.batas_wilayah = data.batas_wilayah || {
      utara: 'Desa Kalibuntu & Selat Madura',
      selatan: 'Desa Sumberlele & Kecamatan Besuk',
      timur: 'Desa Bulu & Desa Rondokuning',
      barat: 'Sungai Kraksaan & Kelurahan Patokan'
    };
    profilForm.potensi_unggulan = data.potensi_unggulan ? JSON.parse(JSON.stringify(data.potensi_unggulan)) : [
      { judul: 'UMKM Kuliner & Niaga', deskripsi: 'Pusat jajanan tradisional, olahan hasil laut Kraksaan, dan sentra pedagang pasar lokal.' },
      { judul: 'Kawasan Pemukiman', deskripsi: 'Lingkungan RT/RW tertib dengan semangat gotong royong dan posyandu integrasi aktif.' },
      { judul: 'Pelayanan Digital', deskripsi: 'Pemanfaatan sistem digital kependudukan dan transparansi informasi warga berbasis website.' }
    ];
    profilForm.tata_nilai = data.tata_nilai ? JSON.parse(JSON.stringify(data.tata_nilai)) : [
      { judul: 'Berorientasi Pelayanan', deskripsi: 'Memahami dan memenuhi kebutuhan masyarakat secara ramah, cekatan, dan solutif.' },
      { judul: 'Akuntabel & Transparan', deskripsi: 'Melaksanakan tugas dengan jujur, bertanggung jawab, cermat, disiplin, dan bebas pungli.' },
      { judul: 'Harmonis & Gotong Royong', deskripsi: 'Saling peduli, menghargai keberagaman warga, dan menjaga kerukunan antarkomunitas.' },
      { judul: 'Adaptif & Kolaboratif', deskripsi: 'Terus berinovasi dan memanfaatkan teknologi digital untuk percepatan layanan publik.' }
    ];
    profilForm.sejarah_timeline = data.sejarah_timeline ? JSON.parse(JSON.stringify(data.sejarah_timeline)) : [
      { tahun: 'Era Hindia Belanda & Pra-Kemerdekaan', judul: 'Sentra Niaga Pesisir', deskripsi: 'Berkembang sebagai sentra niaga masyarakat agraris dan pesisir di sekitar stasiun dan jalur pos Daendels.' },
      { tahun: 'Peralihan Menjadi Kelurahan Definitif', judul: 'Penataan Administrasi', deskripsi: 'Status tata kelola pemerintahan bertransformasi menjadi kelurahan dengan penataan administrasi RT/RW modern.' },
      { tahun: 'Tahun 2010 - Sekarang: Ibu Kota Kabupaten', judul: 'Pusat Ibu Kota Baru', deskripsi: 'Pusat pemekaran infrastruktur perkotaan, digitalisasi pelayanan, dan penguatan UMKM warga.' }
    ];
    profilForm.custom_nav_menus = data.custom_nav_menus ? JSON.parse(JSON.stringify(data.custom_nav_menus)) : {
      profil: [],
      pemerintahan: [],
      informasi: []
    };
    if (!profilForm.custom_nav_menus.profil) profilForm.custom_nav_menus.profil = [];
    if (!profilForm.custom_nav_menus.pemerintahan) profilForm.custom_nav_menus.pemerintahan = [];
    if (!profilForm.custom_nav_menus.informasi) profilForm.custom_nav_menus.informasi = [];
    profilForm.link_span_lapor = data.link_span_lapor || 'https://www.lapor.go.id/';
    profilForm.halo_sae_wa = data.halo_sae_wa || '082131001001';
    profilForm.halo_sae_link = data.halo_sae_link || 'https://halosae.probolinggokab.go.id';
    profilForm.lurah_nama = data.lurah?.nama || '';
    profilForm.lurah_nip = data.lurah?.nip || '';
    profilForm.lurah_jabatan = data.lurah?.jabatan || 'Lurah Kraksaan Wetan';
    profilForm.lurah_sambutan = data.lurah?.sambutan || '';
    profilForm.lurah_foto = data.lurah?.foto || '';
    misiText.value = (data.misi || []).join('\n');
    perangkatList.value = data.perangkat || [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};


const addPotensi = () => {
  if (!profilForm.potensi_unggulan) profilForm.potensi_unggulan = [];
  profilForm.potensi_unggulan.push({ judul: '', deskripsi: '' });
};

const removePotensi = (index) => {
  profilForm.potensi_unggulan.splice(index, 1);
};

const addTimeline = () => {
  if (!profilForm.sejarah_timeline) profilForm.sejarah_timeline = [];
  profilForm.sejarah_timeline.push({ tahun: '', judul: '', deskripsi: '' });
};

const removeTimeline = (index) => {
  profilForm.sejarah_timeline.splice(index, 1);
};

const addTataNilai = () => {
  if (!profilForm.tata_nilai) profilForm.tata_nilai = [];
  profilForm.tata_nilai.push({ judul: '', deskripsi: '' });
};

const removeTataNilai = (index) => {
  profilForm.tata_nilai.splice(index, 1);
};

const saveProfil = async () => {
  saving.value = true;
  profilForm.misi = misiText.value.split('\n').map(s => s.trim()).filter(s => s.length > 0);
  try {
    const res = await AdminService.updateProfil(profilForm);
    const msg = res.message || 'Profil kelurahan berhasil diperbarui!';
    successMsg.value = msg;
    toast.success(msg, 'Profil Kelurahan');
    if (res?.data) {
      Object.assign(profilForm, res.data);
    }
    window.dispatchEvent(new CustomEvent('profil-updated', { detail: res.data }));
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Terjadi kesalahan sistem';
    toast.error('Gagal menyimpan profil: ' + errText, 'Gagal Menyimpan');
  } finally {
    saving.value = false;
  }
};

const openPerangkatModal = (item = null) => {
  previewPerangkatFoto.value = '';
  if (item) {
    perangkatEditId.value = item.id;
    perangkatForm.nama = item.nama;
    perangkatForm.jabatan = item.jabatan;
    perangkatForm.bidang = item.bidang || '';
    perangkatForm.foto = item.foto || '';
  } else {
    perangkatEditId.value = null;
    perangkatForm.nama = '';
    perangkatForm.jabatan = '';
    perangkatForm.bidang = '';
    perangkatForm.foto = '';
  }
  showPerangkatModal.value = true;
};

const savePerangkat = async () => {
  try {
    await AdminService.savePerangkat(perangkatForm, perangkatEditId.value);
    showPerangkatModal.value = false;
    const msg = perangkatEditId.value ? 'Data aparatur berhasil diperbarui!' : 'Aparatur baru berhasil ditambahkan!';
    successMsg.value = msg;
    toast.success(msg, 'Aparatur Kelurahan');
    await loadData();
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Gagal menyimpan data';
    toast.error('Gagal menyimpan aparatur: ' + errText, 'Gagal Menyimpan');
  }
};

const deletePerangkat = async (id) => {
  if (!confirm('Hapus aparatur ini?')) return;
  try {
    await AdminService.deletePerangkat(id);
    const msg = 'Aparatur berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'Aparatur Dihapus');
    await loadData();
  } catch (err) {
    const errText = err.response?.data?.message || err.message || 'Gagal menghapus data';
    toast.error('Gagal menghapus aparatur: ' + errText, 'Gagal Menghapus');
  }
};

onMounted(loadData);
</script>
