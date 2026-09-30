<template>
  <div class="space-y-6">
    <!-- Header Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Berita Kelurahan</h2>
        <p class="text-xs text-slate-500">Tambah, sunting, atur draf/terbit, dan tayangkan warta pada Running Text.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tulis Artikel Baru</span>
      </button>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700 cursor-pointer">&times;</button>
    </div>

    <!-- Table List -->
    <LoadingSpinner v-if="loading" />
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
            <tr>
              <th class="py-3.5 px-4 w-16">Foto</th>
              <th class="py-3.5 px-4">Judul Artikel</th>
              <th class="py-3.5 px-4">Kategori</th>
              <th class="py-3.5 px-4">Status</th>
              <th class="py-3.5 px-4 text-center">Running Text</th>
              <th class="py-3.5 px-4">Tanggal</th>
              <th class="py-3.5 px-4 text-center">Dilihat</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-for="b in beritaList" :key="b.id" class="hover:bg-slate-50">
              <td class="py-3 px-4">
                <img :src="b.gambar" :alt="b.judul" class="w-12 h-10 object-cover rounded-lg bg-slate-100 border border-slate-200" />
              </td>
              <td class="py-3 px-4 max-w-sm">
                <p class="font-bold text-slate-900 line-clamp-1">{{ b.judul }}</p>
                <p class="text-[11px] text-slate-400 line-clamp-1">{{ b.ringkasan }}</p>
                <div v-if="b.slug" class="mt-1 flex items-center gap-1.5">
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600 border border-slate-200">
                    /berita/{{ b.slug }}
                  </span>
                  <a 
                    v-if="b.status === 'published'"
                    :href="`/berita/${b.slug}`" 
                    target="_blank" 
                    class="text-[10px] font-bold text-emerald-700 hover:text-emerald-900 inline-flex items-center gap-0.5" 
                    title="Buka halaman publik"
                  >
                    <span>Lihat</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                  </a>
                </div>
              </td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-1 rounded-md bg-slate-100 font-semibold text-[11px]">
                  {{ b.kategori }}
                </span>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">
                <button
                  type="button"
                  @click="toggleStatus(b)"
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold transition cursor-pointer"
                  :class="b.status === 'published' ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-amber-100 text-amber-800 hover:bg-amber-200'"
                  :title="b.status === 'published' ? 'Klik untuk jadikan Draft' : 'Klik untuk Terbitkan (Upload)'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="b.status === 'published' ? 'bg-emerald-600' : 'bg-amber-600'"></span>
                  <span>{{ b.status === 'published' ? 'Terbit (Upload)' : 'Draft' }}</span>
                </button>
              </td>
              <td class="py-3 px-4 text-center whitespace-nowrap">
                <button
                  type="button"
                  @click="toggleRunningText(b)"
                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold transition cursor-pointer"
                  :class="b.tampil_running_text ? 'bg-blue-50 text-blue-700 border border-blue-200 hover:bg-blue-100' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'"
                  :title="b.tampil_running_text ? 'Tampil di Teks Berjalan. Klik untuk nonaktifkan.' : 'Tidak tampil di Teks Berjalan. Klik untuk aktifkan.'"
                >
                  <span v-if="b.tampil_running_text">&check; Aktif</span>
                  <span v-else>&times; Mati</span>
                </button>
              </td>
              <td class="py-3 px-4 whitespace-nowrap text-slate-500">{{ b.tanggal }}</td>
              <td class="py-3 px-4 text-center font-mono font-semibold">{{ b.dilihat }}</td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <button 
                  @click="openModal(b)" 
                  class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5 cursor-pointer"
                >
                  Edit
                </button>
                <button 
                  @click="deleteItem(b.id)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold cursor-pointer"
                >
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="!beritaList.length">
              <td colspan="8" class="py-8 text-center text-slate-400">Belum ada artikel berita di database.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal Form Tambah / Edit -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs"
    >
      <div class="bg-white rounded-2xl sm:rounded-3xl max-w-5xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
        <!-- Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">
              {{ editId ? 'Sunting Berita' : 'Tulis Berita Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola judul, kategori, ringkasan, isi artikel, dan status publikasi warta.</p>
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
              <!-- Left Column: Primary Content (Judul & Konten) -->
              <div class="lg:col-span-7 space-y-4">
                <div>
                  <label class="block font-bold text-slate-700 mb-1">Judul Artikel *</label>
                  <input 
                    type="text" 
                    v-model="form.judul" 
                    required 
                    placeholder="Contoh: Musrenbangkel Kraksaan Wetan Tahun 2026..." 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-medium"
                  />
                </div>

                <div>
                  <div class="flex items-center justify-between mb-1">
                    <label class="block font-bold text-slate-700">Isi Konten Lengkap *</label>
                    <span class="text-[11px] text-emerald-700 font-medium">Mendukung format heading, list, gambar, dan tebal/miring</span>
                  </div>
                  <RichTextEditor 
                    v-model="form.konten"
                    placeholder="Tulis paragraf artikel lengkap warta kegiatan kelurahan..."
                    height="240px"
                    maxHeight="340px"
                  />
                </div>
              </div>

              <!-- Right Column: Meta, Foto, Ringkasan, & Status -->
              <div class="lg:col-span-5 space-y-4 bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80">
                <!-- Status Publikasi & Running Text -->
                <div class="p-3.5 rounded-xl bg-white border border-slate-200 space-y-3 shadow-2xs">
                  <div>
                    <label class="block font-bold text-slate-700 text-xs mb-1.5">Status Publikasi *</label>
                    <div class="grid grid-cols-2 gap-2">
                      <label 
                        class="flex items-center gap-2 p-2 rounded-xl border cursor-pointer transition text-xs font-bold"
                        :class="form.status === 'published' ? 'border-emerald-600 bg-emerald-50 text-emerald-900 shadow-2xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                      >
                        <input type="radio" value="published" v-model="form.status" class="text-emerald-600 focus:ring-emerald-500" />
                        <span>Upload / Terbit</span>
                      </label>
                      <label 
                        class="flex items-center gap-2 p-2 rounded-xl border cursor-pointer transition text-xs font-bold"
                        :class="form.status === 'draft' ? 'border-amber-500 bg-amber-50 text-amber-900 shadow-2xs' : 'border-slate-200 text-slate-600 hover:bg-slate-50'"
                      >
                        <input type="radio" value="draft" v-model="form.status" class="text-amber-600 focus:ring-amber-500" />
                        <span>Simpan Draft</span>
                      </label>
                    </div>
                  </div>

                  <label class="flex items-center gap-2 cursor-pointer pt-2 border-t border-slate-100">
                    <input type="checkbox" v-model="form.tampil_running_text" class="rounded text-emerald-600 focus:ring-emerald-500 w-4 h-4" />
                    <span class="text-xs font-bold text-slate-700">Tampilkan di Running Text Warta</span>
                  </label>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div>
                    <div class="flex items-center justify-between mb-1">
                      <label class="block font-bold text-slate-700 text-xs">Kategori *</label>
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
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Penulis</label>
                    <input 
                      type="text" 
                      v-model="form.penulis" 
                      placeholder="Tim Humas Kelurahan" 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    />
                  </div>
                </div>

                <!-- Foto Artikel dengan Crop Fitur -->
                <div>
                  <div class="flex items-center justify-between mb-1">
                    <label class="block font-bold text-slate-700 text-xs">Foto Utama Artikel</label>
                    <span class="text-[10px] text-emerald-700 font-semibold">Tersedia Fitur Crop</span>
                  </div>
                  <div class="space-y-2">
                    <div class="flex gap-2">
                      <input 
                        type="text" 
                        v-model="form.gambar" 
                        placeholder="URL foto artikel atau pilih upload..." 
                        class="flex-1 px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                      />
                      <label class="px-3 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1 shrink-0 shadow-xs transition">
                        <span v-if="uploading">...</span>
                        <span v-else class="flex items-center gap-1">
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                          Pilih & Crop
                        </span>
                        <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="handleFileSelect" :disabled="uploading" />
                      </label>
                    </div>
                    <div v-if="form.gambar" class="w-full h-28 rounded-xl border border-slate-200 overflow-hidden bg-white shadow-2xs relative group">
                      <img :src="form.gambar" alt="Preview Foto" class="w-full h-full object-cover" />
                      <button
                        type="button"
                        @click="form.gambar = ''"
                        class="absolute top-2 right-2 p-1 rounded-lg bg-rose-600 text-white opacity-0 group-hover:opacity-100 transition shadow-xs cursor-pointer text-xs"
                        title="Hapus foto"
                      >
                        Hapus
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Input Ringkasan (Auto dari Konten) -->
                <div>
                  <div class="flex items-center justify-between mb-1">
                    <label class="block font-bold text-slate-700 text-xs">Ringkasan (Excerpt) *</label>
                    <button
                      type="button"
                      @click="autoFillRingkasan"
                      class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-900 bg-emerald-100/70 hover:bg-emerald-200 px-2 py-0.5 rounded-lg transition cursor-pointer"
                      title="Isi ringkasan otomatis dari paragraf isi konten lengkap"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                      <span>Auto dari Konten</span>
                    </button>
                  </div>
                  <textarea 
                    rows="3" 
                    v-model="form.ringkasan" 
                    required 
                    placeholder="Ringkasan 1-2 kalimat untuk pratinjau kartu berita..." 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs leading-relaxed"
                  ></textarea>
                  <p class="text-[10px] text-slate-400 mt-1">Otomatis terisi dari kalimat pertama artikel atau klik tombol "Auto dari Konten".</p>
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
                class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold text-xs shadow-xs cursor-pointer transition flex items-center gap-2"
              >
                <svg v-if="saving" class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                <span>{{ saving ? 'Menyimpan...' : (editId ? 'Simpan Perubahan' : (form.status === 'published' ? 'Terbitkan Artikel (Upload)' : 'Simpan Draft')) }}</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Image Cropper Modal -->
    <ImageCropperModal
      v-model:show="showCropper"
      :image-file="selectedImageFile"
      :default-aspect-ratio="4 / 3"
      @cropped="handleCroppedImage"
    />
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import RichTextEditor from '../../components/RichTextEditor.vue';
import ImageCropperModal from '../../components/ImageCropperModal.vue';
import { AdminService } from '../../services/api';
import { useToast } from '../../composables/useToast';

const toast = useToast();
const loading = ref(true);
const saving = ref(false);
const uploading = ref(false);
const beritaList = ref([]);
const kategoriOptions = ref([]);
const isCustomKategori = ref(false);
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');

// Crop Modal State
const showCropper = ref(false);
const selectedImageFile = ref(null);

const form = reactive({
  judul: '',
  kategori: 'Pemerintahan',
  customKategori: '',
  penulis: 'Tim Humas Kelurahan',
  gambar: '',
  ringkasan: '',
  konten: '',
  status: 'published',
  tampil_running_text: true
});

// Helper Ekstraksi Ringkasan Otomatis dari Konten HTML
const extractRingkasan = (html) => {
  if (!html) return '';
  const tmp = document.createElement('div');
  tmp.innerHTML = html;
  const text = (tmp.textContent || tmp.innerText || '').replace(/\s+/g, ' ').trim();
  if (!text) return '';
  if (text.length <= 160) return text;
  const sub = text.substring(0, 160);
  const lastSpace = sub.lastIndexOf(' ');
  return (lastSpace > 110 ? sub.substring(0, lastSpace) : sub).trim() + '...';
};

const autoFillRingkasan = () => {
  const generated = extractRingkasan(form.konten);
  if (generated) {
    form.ringkasan = generated;
    toast.info('Ringkasan berhasil diekstrak otomatis dari isi konten.', 'Auto Ringkasan');
  } else {
    toast.warning('Konten artikel masih kosong. Tulis isi konten terlebih dahulu.');
  }
};

// Reaktif auto-fill ringkasan saat mengetik konten jika ringkasan masih kosong
watch(() => form.konten, (newKonten) => {
  if (!form.ringkasan || form.ringkasan.trim() === '') {
    const auto = extractRingkasan(newKonten);
    if (auto) {
      form.ringkasan = auto;
    }
  }
});

// Image Upload with Pre-Crop
const handleFileSelect = (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
  if (!allowedTypes.includes(file.type) && !file.type.startsWith('image/')) {
    toast.error('Format gambar tidak didukung! Pilih berkas JPG, PNG, atau WebP.');
    event.target.value = '';
    return;
  }

  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran file maksimal 10MB.');
    event.target.value = '';
    return;
  }

  // Set preview awal langsung di form
  const reader = new FileReader();
  reader.onload = (e) => {
    form.gambar = e.target.result;
  };
  reader.readAsDataURL(file);

  selectedImageFile.value = file;
  showCropper.value = true;
  event.target.value = '';
};

const handleCroppedImage = async (croppedFile) => {
  // Set pratinjau instan foto yang telah dipotong
  const reader = new FileReader();
  reader.onload = (e) => {
    form.gambar = e.target.result;
  };
  reader.readAsDataURL(croppedFile);

  uploading.value = true;
  try {
    const res = await AdminService.uploadFile(croppedFile, 'image');
    const url = res.data?.url || res.url;
    if (url) {
      form.gambar = url;
      successMsg.value = 'Foto berhasil dipotong dan diunggah!';
      toast.success('Foto artikel berhasil disesuaikan dan diunggah.');
    }
  } catch (err) {
    toast.warning('Pratinjau foto siap disimpan: ' + (err.response?.data?.message || err.message));
  } finally {
    uploading.value = false;
  }
};

const loadData = async () => {
  loading.value = true;
  try {
    const [beritas, kats] = await Promise.all([
      AdminService.getBerita(),
      AdminService.getMasterKategori('berita')
    ]);
    if (beritas && Array.isArray(beritas)) {
      const existingNew = beritaList.value.filter(item => !beritas.some(b => b.id === item.id));
      beritaList.value = [...existingNew, ...beritas];
    } else if (beritas) {
      beritaList.value = beritas;
    }
    kategoriOptions.value = kats || [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const openModal = (item = null) => {
  isCustomKategori.value = false;
  form.customKategori = '';
  if (item) {
    editId.value = item.id;
    form.judul = item.judul;
    form.kategori = item.kategori;
    form.penulis = item.penulis || 'Tim Humas Kelurahan';
    form.gambar = item.gambar || '';
    form.ringkasan = item.ringkasan || '';
    form.konten = item.konten || '';
    form.status = item.status || 'published';
    form.tampil_running_text = item.tampil_running_text !== undefined ? Boolean(item.tampil_running_text) : true;
  } else {
    editId.value = null;
    form.judul = '';
    form.kategori = kategoriOptions.value.length > 0 ? (kategoriOptions.value[0]?.nama || kategoriOptions.value[0]) : 'Pemerintahan';
    form.penulis = 'Tim Humas Kelurahan';
    form.gambar = 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80';
    form.ringkasan = '';
    form.konten = '';
    form.status = 'published';
    form.tampil_running_text = true;
  }
  showModal.value = true;
};

const saveItem = async () => {
  // Single-Submit Guard
  if (saving.value) return;
  saving.value = true;

  try {
    const payload = { ...form };
    if (isCustomKategori.value && form.customKategori?.trim()) {
      payload.kategori = form.customKategori.trim();
    }
    const res = await AdminService.saveBerita(payload, editId.value);
    const msg = res.message || 'Berita berhasil disimpan ke database!';
    successMsg.value = msg;
    toast.success(msg, editId.value ? 'Berita Diperbarui' : 'Berita Diterbitkan');
    showModal.value = false;
    if (res?.data) {
      if (editId.value) {
        const idx = beritaList.value.findIndex(b => b.id === editId.value);
        if (idx !== -1) beritaList.value[idx] = res.data;
      } else {
        beritaList.value.unshift(res.data);
      }
    }
    await loadData();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan berita.';
    toast.error('Gagal menyimpan berita: ' + errMsg);
  } finally {
    saving.value = false;
  }
};

const toggleStatus = async (b) => {
  try {
    const res = await AdminService.toggleStatusBerita(b.id);
    const newStatus = b.status === 'published' ? 'draft' : 'published';
    b.status = newStatus;
    const msg = res.message || `Status berita diubah menjadi ${newStatus === 'published' ? 'Terbit (Upload)' : 'Draft'}.`;
    successMsg.value = msg;
    toast.success(msg, 'Status Berita');
  } catch (err) {
    toast.error('Gagal mengubah status berita.');
  }
};

const toggleRunningText = async (b) => {
  try {
    const res = await AdminService.toggleRunningTextBerita(b.id);
    b.tampil_running_text = !b.tampil_running_text;
    const msg = res.message || 'Pengaturan Running Text diperbarui.';
    successMsg.value = msg;
    toast.success(msg, 'Running Text');
  } catch (err) {
    toast.error('Gagal mengubah pengaturan Running Text.');
  }
};

const deleteItem = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus artikel berita ini dari database? Foto terkait juga akan dibersihkan.')) return;
  try {
    await AdminService.deleteBerita(id);
    const msg = 'Berita berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'Berita Dihapus');
    await loadData();
  } catch (err) {
    toast.error('Gagal menghapus berita.');
  }
};

onMounted(loadData);
</script>
