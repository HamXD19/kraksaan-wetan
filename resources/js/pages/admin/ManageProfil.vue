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
          <!-- Logo Daerah / Kelurahan -->
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
              <div>
                <label class="block font-bold text-slate-900 text-sm">Logo Daerah / Kelurahan</label>
                <p class="text-[11px] text-slate-500">Logo ini ditampilkan di seluruh portal (Navbar utama, Footer, dan Panel Admin).</p>
              </div>
              <span class="text-[10px] px-2.5 py-1 rounded-md bg-slate-200/70 font-semibold text-slate-700 self-start sm:self-auto">
                {{ profilForm.logo ? 'Kustom Terpasang' : 'Logo Bawaan Aktif' }}
              </span>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4">
              <!-- Logo Preview Box -->
              <div class="w-20 h-24 sm:w-24 sm:h-28 rounded-xl border border-slate-200 bg-white p-2 flex items-center justify-center flex-shrink-0 shadow-xs">
                <img v-if="profilForm.logo" :src="profilForm.logo" alt="Logo Kelurahan" class="w-full h-full object-contain" />
                <div v-else class="w-full h-full flex flex-col items-center justify-center text-center">
                  <svg viewBox="0 0 80 96" class="w-12 h-14" fill="none">
                    <path d="M40 2L76 18V50C76 72 40 94 40 94C40 94 4 72 4 50V18L40 2Z" fill="#047857" stroke="#f59e0b" stroke-width="3"/>
                    <path d="M22 56L40 32L58 56H22Z" fill="#f8fafc"/>
                    <circle cx="40" cy="26" r="5" fill="#f59e0b"/>
                  </svg>
                  <span class="text-[9px] font-semibold text-slate-400 mt-1">Default</span>
                </div>
              </div>

              <!-- Upload Controls -->
              <div class="flex-1 w-full space-y-2">
                <div class="flex flex-col sm:flex-row gap-2">
                  <input 
                    type="text" 
                    v-model="profilForm.logo" 
                    placeholder="Masukkan URL Logo atau pilih Unggah File..." 
                    class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                  />
                  <label class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1.5 shadow-xs transition whitespace-nowrap">
                    <span v-if="uploadingLogo">Mengunggah...</span>
                    <span v-else class="flex items-center gap-1">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                      Pilih & Unggah Logo
                    </span>
                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleLogoUpload" :disabled="uploadingLogo" />
                  </label>
                  <button 
                    v-if="profilForm.logo" 
                    type="button" 
                    @click="profilForm.logo = ''" 
                    class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl text-xs transition whitespace-nowrap"
                  >
                    Reset Bawaan
                  </button>
                </div>
                <p class="text-[11px] text-slate-500">Format yang didukung: PNG transparan, SVG, atau JPG/WebP (Maks. 10MB).</p>
              </div>
            </div>
          </div>

          <!-- Pengaturan Foto Latar Hero Banner Beranda -->
          <div class="p-4 sm:p-5 rounded-2xl bg-slate-50 border border-slate-200 space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div>
                <h4 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                  <span>Foto Latar Hero Banner Beranda</span>
                  <span class="px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">Dinamis</span>
                </h4>
                <p class="text-xs text-slate-500 mt-0.5">Unggah foto latar khusus atau masukkan URL gambar untuk tampilan hero banner di halaman utama website.</p>
              </div>
              <span class="text-[10px] px-2.5 py-1 rounded-md bg-slate-200/70 font-semibold text-slate-700 self-start sm:self-auto">
                {{ profilForm.hero_image ? 'Foto Kustom Terpasang' : 'Foto Bawaan (Gunung Bromo)' }}
              </span>
            </div>

            <!-- Banner Preview Box -->
            <div class="relative w-full h-36 sm:h-48 rounded-2xl overflow-hidden border border-slate-200 bg-emerald-950 shadow-inner group">
              <img 
                :src="profilForm.hero_image || '/images/hero-bromo-vector.jpg'" 
                alt="Pratinjau Hero Banner" 
                class="w-full h-full object-cover object-[center_35%] transition-transform duration-300 group-hover:scale-105"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-emerald-950/80 via-transparent to-black/20"></div>
              <div class="absolute bottom-3 left-4 right-4 flex items-center justify-between text-white text-xs">
                <span class="font-bold drop-shadow-sm flex items-center gap-1.5">
                  <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                  Pratinjau Tampilan Hero Banner
                </span>
                <span class="text-[10px] text-emerald-200 bg-emerald-900/80 px-2 py-0.5 rounded-md backdrop-blur-xs">
                  {{ profilForm.hero_image ? 'Kustom' : 'Default' }}
                </span>
              </div>
            </div>

            <!-- Input Controls & Upload -->
            <div class="space-y-2">
              <div class="flex flex-col sm:flex-row gap-2">
                <input 
                  type="text" 
                  v-model="profilForm.hero_image" 
                  placeholder="Masukkan URL Foto Banner atau pilih Unggah Foto..." 
                  class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                />
                <label class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1.5 shadow-xs transition whitespace-nowrap">
                  <span v-if="uploadingHero">Mengunggah...</span>
                  <span v-else class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Pilih & Unggah Foto Hero
                  </span>
                  <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleHeroUpload" :disabled="uploadingHero" />
                </label>
                <button 
                  v-if="profilForm.hero_image" 
                  type="button" 
                  @click="profilForm.hero_image = ''" 
                  class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-xl text-xs transition whitespace-nowrap"
                >
                  Reset Bawaan
                </button>
              </div>
              <p class="text-[11px] text-slate-500">Format yang didukung: JPG, PNG, WebP, SVG (Disarankan rasio lanskap 16:9 resolusi tinggi, Maks. 10MB).</p>
            </div>
          </div>

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
            <textarea rows="3" v-model="profilForm.deskripsi" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Sejarah Kelurahan</label>
            <textarea rows="4" v-model="profilForm.sejarah" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Visi Kelurahan *</label>
            <textarea rows="2" v-model="profilForm.visi" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Misi Pembangunan (1 baris per poin misi)</label>
            <textarea rows="4" v-model="misiText" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"></textarea>
          </div>

          <!-- Bagian Pimpinan Lurah -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-4 mt-6">
            <h4 class="font-bold text-slate-900 text-sm">Data Kepala Kelurahan (Lurah)</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block font-bold text-slate-700 mb-1">Nama Lurah</label>
                <input type="text" v-model="profilForm.lurah_nama" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 mb-1">NIP Lurah</label>
                <input type="text" v-model="profilForm.lurah_nip" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white" />
              </div>
            </div>

            <!-- Upload Foto Lurah -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Foto Resmi Lurah</label>
              <div class="flex flex-col sm:flex-row items-center gap-4">
                <!-- Preview Foto Lurah -->
                <div class="w-16 h-20 sm:w-20 sm:h-24 rounded-xl border border-slate-200 bg-white overflow-hidden flex items-center justify-center shrink-0 shadow-xs">
                  <img 
                    v-if="profilForm.lurah_foto" 
                    :src="profilForm.lurah_foto" 
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
              <textarea rows="3" v-model="profilForm.lurah_sambutan" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white"></textarea>
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
                <th class="py-3 px-4">Nama</th>
                <th class="py-3 px-4">Jabatan</th>
                <th class="py-3 px-4">Tugas / Bidang</th>
                <th class="py-3 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
              <tr v-for="p in perangkatList" :key="p.id" class="hover:bg-slate-50">
                <td class="py-3 px-4 font-bold text-slate-900">{{ p.nama }}</td>
                <td class="py-3 px-4 text-emerald-700 font-semibold">{{ p.jabatan }}</td>
                <td class="py-3 px-4">{{ p.bidang || '-' }}</td>
                <td class="py-3 px-4 text-right whitespace-nowrap">
                  <button @click="openPerangkatModal(p)" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 font-semibold mr-1">Edit</button>
                  <button @click="deletePerangkat(p.id)" class="px-2.5 py-1 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>

    <!-- Modal Aparatur -->
    <div v-if="showPerangkatModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs" @click.self="showPerangkatModal = false">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <h4 class="text-base font-bold text-slate-900 mb-4">{{ perangkatEditId ? 'Edit Aparatur' : 'Tambah Aparatur' }}</h4>
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
          <div class="flex justify-end gap-2 pt-3">
            <button type="button" @click="showPerangkatModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-semibold">Batal</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 text-white font-bold">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import { AdminService, KelurahanService } from '../../services/api';

const loading = ref(true);
const saving = ref(false);
const uploadingLogo = ref(false);
const uploadingHero = ref(false);
const uploadingLurahFoto = ref(false);
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
    alert('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran file logo terlalu besar! Maksimal 10MB.');
    event.target.value = '';
    return;
  }

  uploadingLogo.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    if (res.data?.url) {
      profilForm.logo = res.data.url;
      successMsg.value = 'Logo baru berhasil diunggah! Jangan lupa klik tombol "Simpan Perubahan Profil" di bawah.';
    }
  } catch (err) {
    alert('Gagal mengunggah logo: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingLogo.value = false;
    event.target.value = '';
  }
};

const handleHeroUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    alert('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran file foto hero terlalu besar! Maksimal 10MB.');
    event.target.value = '';
    return;
  }

  uploadingHero.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    if (res.data?.url) {
      profilForm.hero_image = res.data.url;
      successMsg.value = 'Foto latar hero banner berhasil diunggah! Jangan lupa klik tombol "Simpan Perubahan Profil" di bawah.';
    }
  } catch (err) {
    alert('Gagal mengunggah foto hero: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingHero.value = false;
    event.target.value = '';
  }
};

const handleLurahFotoUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    alert('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran file foto Lurah terlalu besar! Maksimal 10MB.');
    event.target.value = '';
    return;
  }

  uploadingLurahFoto.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    if (res.data?.url) {
      profilForm.lurah_foto = res.data.url;
      successMsg.value = 'Foto Lurah berhasil diunggah! Jangan lupa klik tombol "Simpan Perubahan Profil" di bawah.';
    }
  } catch (err) {
    alert('Gagal mengunggah foto Lurah: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingLurahFoto.value = false;
    event.target.value = '';
  }
};


const profilForm = reactive({
  nama: '',
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
  bidang: ''
});

const loadData = async () => {
  loading.value = true;
  try {
    const data = await KelurahanService.getProfil();
    profilForm.nama = data.nama || '';
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
    profilForm.link_span_lapor = data.link_span_lapor || 'https://www.lapor.go.id/';
    profilForm.halo_sae_wa = data.halo_sae_wa || '082131001001';
    profilForm.halo_sae_link = data.halo_sae_link || 'https://halosae.probolinggokab.go.id';
    profilForm.lurah_nama = data.lurah?.nama || '';
    profilForm.lurah_nip = data.lurah?.nip || '';
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


const saveProfil = async () => {
  saving.value = true;
  profilForm.misi = misiText.value.split('\n').map(s => s.trim()).filter(s => s.length > 0);
  try {
    const res = await AdminService.updateProfil(profilForm);
    successMsg.value = res.message || 'Profil kelurahan berhasil diperbarui!';
    window.dispatchEvent(new CustomEvent('profil-updated', { detail: res.data }));
  } catch (err) {
    alert('Gagal menyimpan profil: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const openPerangkatModal = (item = null) => {
  if (item) {
    perangkatEditId.value = item.id;
    perangkatForm.nama = item.nama;
    perangkatForm.jabatan = item.jabatan;
    perangkatForm.bidang = item.bidang || '';
  } else {
    perangkatEditId.value = null;
    perangkatForm.nama = '';
    perangkatForm.jabatan = '';
    perangkatForm.bidang = '';
  }
  showPerangkatModal.value = true;
};

const savePerangkat = async () => {
  try {
    await AdminService.savePerangkat(perangkatForm, perangkatEditId.value);
    showPerangkatModal.value = false;
    successMsg.value = 'Data aparatur berhasil disimpan!';
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan aparatur.');
  }
};

const deletePerangkat = async (id) => {
  if (!confirm('Hapus aparatur ini?')) return;
  try {
    await AdminService.deletePerangkat(id);
    successMsg.value = 'Aparatur berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus aparatur.');
  }
};

onMounted(loadData);
</script>
