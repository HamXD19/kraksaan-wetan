<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Pengumuman Kedinasan</h2>
        <p class="text-xs text-slate-500">Publikasikan himbauan, edaran, banner sosialisasi, dan agenda resmi kepada masyarakat.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs shadow-sm transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Pengumuman
      </button>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700">&times;</button>
    </div>

    <LoadingSpinner v-if="loading" />
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
            <tr>
              <th class="py-3.5 px-4">Prioritas</th>
              <th class="py-3.5 px-4">Kategori</th>
              <th class="py-3.5 px-4">Judul & Media</th>
              <th class="py-3.5 px-4">Tanggal</th>
              <th class="py-3.5 px-4">Penyelenggara</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-for="p in pengumumanList" :key="p.id" class="hover:bg-slate-50">
              <td class="py-3 px-4">
                <span 
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase"
                  :class="p.prioritas === 'Penting' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800'"
                >
                  {{ p.prioritas }}
                </span>
              </td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-semibold text-[11px]">
                  {{ p.kategori || 'Umum' }}
                </span>
              </td>
              <td class="py-3 px-4 max-w-sm">
                <div class="flex items-start gap-3">
                  <img 
                    v-if="p.thumbnail || p.banner" 
                    :src="p.thumbnail || p.banner" 
                    alt="Thumbnail" 
                    class="w-12 h-12 rounded-xl object-cover border border-slate-200 shrink-0 bg-slate-100"
                  />
                  <div>
                    <p class="font-bold text-slate-900 leading-snug">{{ p.judul }}</p>
                    <p class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ p.isi }}</p>
                    <div class="flex items-center gap-2 mt-1">
                      <span v-if="p.banner" class="text-[9px] bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded font-bold">Ada Banner</span>
                      <span v-if="p.thumbnail" class="text-[9px] bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded font-bold">Ada Thumbnail</span>
                      <a 
                        v-if="p.file" 
                        :href="p.file_url || ('/api/pengumuman/' + p.id + '/unduh')" 
                        target="_blank" 
                        class="text-[9px] bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/80 px-2 py-0.5 rounded-md font-bold inline-flex items-center gap-1 transition shadow-2xs" 
                        title="Unduh Berkas PDF"
                      >
                        <span>Lampiran PDF</span>
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                      </a>
                    </div>
                  </div>
                </div>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">{{ p.tanggal }}</td>
              <td class="py-3 px-4">{{ p.penyelenggara || '-' }}</td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <button 
                  @click="openModal(p)" 
                  class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5"
                >
                  Edit
                </button>
                <button 
                  @click="deleteItem(p.id)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold"
                >
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="!pengumumanList.length">
              <td colspan="6" class="py-8 text-center text-slate-400">Belum ada pengumuman tersimpan.</td>
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
              {{ editId ? 'Sunting Pengumuman' : 'Tambah Pengumuman Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola judul, prioritas, uraian isi pengumuman, dan berkas lampiran resmi.</p>
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
              <!-- Left Column: Primary Content (Judul, Isi Pengumuman, & Lampiran PDF) -->
              <div class="lg:col-span-7 space-y-4">
                <div>
                  <label class="block font-bold text-slate-700 mb-1">Judul Pengumuman *</label>
                  <input 
                    type="text" 
                    v-model="form.judul" 
                    required 
                    placeholder="Contoh: Sosialisasi Penataan Lingkungan Bersih..." 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-medium text-xs sm:text-sm"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1">Uraian Isi Pengumuman *</label>
                  <RichTextEditor 
                    v-model="form.isi"
                    placeholder="Rincian informasi pengumuman..."
                    height="200px"
                    maxHeight="320px"
                  />
                </div>

                <!-- Dokumen Lampiran PDF -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                  <div class="flex items-center justify-between">
                    <label class="block font-bold text-slate-900 text-xs">File Lampiran PDF (Opsional)</label>
                    <span class="text-[10px] text-slate-400">PDF resmi kedinasan</span>
                  </div>
                  <div class="flex gap-2">
                    <input 
                      type="text" 
                      v-model="form.file" 
                      placeholder="URL atau unggah dokumen..." 
                      class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs outline-none"
                    />
                    <label class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl text-xs cursor-pointer whitespace-nowrap flex items-center gap-1 shadow-xs">
                      <span>{{ uploadingFile ? '...' : 'Unggah PDF' }}</span>
                      <input type="file" accept="application/pdf" class="hidden" @change="handlePdfUpload" :disabled="uploadingFile" />
                    </label>
                    <button v-if="form.file" type="button" @click="form.file = ''" class="px-2.5 py-1.5 bg-rose-50 text-rose-700 rounded-xl text-xs font-semibold">Hapus</button>
                  </div>
                  <div v-if="form.file" class="flex items-center justify-between p-2 rounded-xl bg-emerald-50 border border-emerald-200/80 text-[11px] text-emerald-900">
                    <span class="truncate font-semibold flex items-center gap-1.5">
                      <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                      <span class="truncate">{{ form.file.split('/').pop() }}</span>
                    </span>
                    <a 
                      :href="form.file.startsWith('http') ? form.file : ('/storage/uploads/' + form.file)" 
                      target="_blank" 
                      class="font-bold underline text-emerald-700 hover:text-emerald-900 shrink-0 text-[11px]"
                    >
                      Lihat File &rarr;
                    </a>
                  </div>
                </div>
              </div>

              <!-- Right Column: Meta & Media Pengumuman -->
              <div class="lg:col-span-5 space-y-3.5 bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80">
                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Prioritas *</label>
                    <select 
                      v-model="form.prioritas" 
                      required 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs font-semibold"
                    >
                      <option value="Penting">Penting</option>
                      <option value="Himbauan">Himbauan</option>
                      <option value="Pemberitahuan">Pemberitahuan</option>
                    </select>
                  </div>

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
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1 text-xs">Penyelenggara / Seksi</label>
                  <input 
                    type="text" 
                    v-model="form.penyelenggara" 
                    placeholder="Kelurahan Kraksaan Wetan" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                  />
                </div>

                <!-- Banner Media (Lanskap 16:9) -->
                <div class="space-y-1.5 pt-1">
                  <div class="flex items-center justify-between">
                    <label class="block font-bold text-slate-700 text-xs">Banner / Gambar Utama (16:9)</label>
                    <span class="text-[10px] text-slate-400">Tampil lebar di atas kartu</span>
                  </div>
                  <div class="flex items-center gap-2.5">
                    <div class="w-16 h-11 rounded-lg border border-slate-200 bg-white overflow-hidden shrink-0 flex items-center justify-center shadow-2xs">
                      <img v-if="form.banner" :src="form.banner" alt="Banner" class="w-full h-full object-cover" />
                      <span v-else class="text-[9px] text-slate-400">Kosong</span>
                    </div>
                    <div class="flex-1 flex gap-1.5">
                      <input 
                        type="text" 
                        v-model="form.banner" 
                        placeholder="URL atau pilih file untuk crop..." 
                        class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs outline-none"
                      />
                      <label class="px-2.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg text-xs cursor-pointer whitespace-nowrap flex items-center gap-1 shadow-xs transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ uploadingBanner ? 'Memproses...' : 'Pilih & Crop' }}</span>
                        <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="onSelectImage($event, 'banner')" :disabled="uploadingBanner" />
                      </label>
                      <button 
                        v-if="form.banner" 
                        type="button" 
                        @click="form.banner = ''" 
                        class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold cursor-pointer transition"
                        title="Hapus Banner"
                      >
                        Hapus
                      </button>
                    </div>
                  </div>
                </div>

                <!-- Thumbnail Media (Kotak 1:1) -->
                <div class="space-y-1.5 pt-1">
                  <div class="flex items-center justify-between">
                    <label class="block font-bold text-slate-700 text-xs">Thumbnail Pengumuman (1:1)</label>
                    <span class="text-[10px] text-slate-400">Tampil di samping judul</span>
                  </div>
                  <div class="flex items-center gap-2.5">
                    <div class="w-11 h-11 rounded-lg border border-slate-200 bg-white overflow-hidden shrink-0 flex items-center justify-center shadow-2xs">
                      <img v-if="form.thumbnail" :src="form.thumbnail" alt="Thumbnail" class="w-full h-full object-cover" />
                      <span v-else class="text-[9px] text-slate-400">Kosong</span>
                    </div>
                    <div class="flex-1 flex gap-1.5">
                      <input 
                        type="text" 
                        v-model="form.thumbnail" 
                        placeholder="URL atau pilih file untuk crop..." 
                        class="flex-1 px-2.5 py-1.5 rounded-lg border border-slate-200 bg-white text-xs outline-none"
                      />
                      <label class="px-2.5 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-lg text-xs cursor-pointer whitespace-nowrap flex items-center gap-1 shadow-xs transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <span>{{ uploadingThumbnail ? 'Memproses...' : 'Pilih & Crop' }}</span>
                        <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp" class="hidden" @change="onSelectImage($event, 'thumbnail')" :disabled="uploadingThumbnail" />
                      </label>
                      <button 
                        v-if="form.thumbnail" 
                        type="button" 
                        @click="form.thumbnail = ''" 
                        class="px-2 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-700 rounded-lg text-xs font-semibold cursor-pointer transition"
                        title="Hapus Thumbnail"
                      >
                        Hapus
                      </button>
                    </div>
                  </div>
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
                class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 disabled:opacity-50 text-slate-950 font-bold text-xs shadow-xs cursor-pointer transition"
              >
                <span v-if="saving">Menyimpan...</span>
                <span v-else>{{ editId ? 'Simpan Perubahan' : 'Terbitkan Pengumuman' }}</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Image Cropper Modal for Banner & Thumbnail -->
    <ImageCropperModal
      v-model:show="showCropper"
      :image-file="selectedImageFile"
      :default-aspect-ratio="cropperAspectRatio"
      @cropped="handleCroppedImage"
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
const loading = ref(true);
const saving = ref(false);
const uploadingBanner = ref(false);
const uploadingThumbnail = ref(false);
const uploadingFile = ref(false);
const pengumumanList = ref([]);
const kategoriOptions = ref([]);
const isCustomKategori = ref(false);
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');

// Crop Modal State
const showCropper = ref(false);
const selectedImageFile = ref(null);
const cropTarget = ref('banner'); // 'banner' | 'thumbnail'
const cropperAspectRatio = ref(16 / 9);

const form = reactive({
  judul: '',
  prioritas: 'Penting',
  kategori: 'Kedinasan',
  customKategori: '',
  penyelenggara: 'Kelurahan Kraksaan Wetan',
  banner: '',
  thumbnail: '',
  file: '',
  isi: ''
});

const loadData = async () => {
  loading.value = true;
  try {
    const [data, kats] = await Promise.all([
      AdminService.getPengumuman(),
      AdminService.getMasterKategori('pengumuman')
    ]);
    pengumumanList.value = data || [];
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
    form.prioritas = item.prioritas;
    form.kategori = item.kategori || (kategoriOptions.value[0]?.nama || 'Kedinasan');
    form.penyelenggara = item.penyelenggara;
    form.banner = item.banner || '';
    form.thumbnail = item.thumbnail || '';
    form.file = item.file || '';
    form.isi = item.isi;
  } else {
    editId.value = null;
    form.judul = '';
    form.prioritas = 'Penting';
    form.kategori = kategoriOptions.value[0]?.nama || 'Kedinasan';
    form.penyelenggara = 'Kelurahan Kraksaan Wetan';
    form.banner = '';
    form.thumbnail = '';
    form.file = '';
    form.isi = '';
  }
  showModal.value = true;
};

const isValidImageFile = (file) => {
  if (!file) return false;
  const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif', 'image/jpg'];
  const ext = file.name.split('.').pop()?.toLowerCase();
  const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'];
  return (allowedMimeTypes.includes(file.type) || file.type.startsWith('image/')) && allowedExts.includes(ext);
};

const onSelectImage = (e, target = 'banner') => {
  const file = e.target.files?.[0];
  if (!file) return;

  const targetLabel = target === 'banner' ? 'banner' : 'thumbnail';

  if (!isValidImageFile(file)) {
    toast.error(`Format file ${targetLabel} tidak valid! Harap pilih file gambar (JPG, PNG, WebP).`);
    e.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.error(`Ukuran file ${targetLabel} terlalu besar! Maksimal 10MB.`);
    e.target.value = '';
    return;
  }

  cropTarget.value = target;
  cropperAspectRatio.value = target === 'banner' ? (16 / 9) : (1 / 1);
  selectedImageFile.value = file;
  showCropper.value = true;
  e.target.value = '';
};

const handleCroppedImage = async (croppedFile) => {
  const isBanner = cropTarget.value === 'banner';
  if (isBanner) {
    uploadingBanner.value = true;
  } else {
    uploadingThumbnail.value = true;
  }

  try {
    const res = await AdminService.uploadFile(croppedFile, 'image');
    const url = res.data?.url || res.url;
    if (url) {
      if (isBanner) {
        form.banner = url;
        toast.success('Banner pengumuman berhasil disesuaikan dan diunggah!');
      } else {
        form.thumbnail = url;
        toast.success('Thumbnail pengumuman berhasil disesuaikan dan diunggah!');
      }
    }
  } catch (err) {
    toast.error('Gagal mengunggah foto: ' + (err.response?.data?.message || err.message));
  } finally {
    if (isBanner) {
      uploadingBanner.value = false;
    } else {
      uploadingThumbnail.value = false;
    }
  }
};

const handlePdfUpload = async (e) => {
  const file = e.target.files?.[0];
  if (!file) return;

  const ext = file.name.split('.').pop()?.toLowerCase();
  const isValidPdf = file.type === 'application/pdf' || ext === 'pdf';

  if (!isValidPdf) {
    toast.error('Format file dokumen tidak valid! Harap pilih file dokumen PDF (.pdf).');
    e.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran file dokumen terlalu besar! Maksimal 10MB.');
    e.target.value = '';
    return;
  }

  uploadingFile.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'document');
    if (res.data?.url) {
      form.file = res.data.url;
      toast.success('Lampiran PDF berhasil diunggah!');
    }
  } catch (err) {
    toast.error('Gagal mengunggah dokumen PDF: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingFile.value = false;
    e.target.value = '';
  }
};

const saveItem = async () => {
  if (saving.value) return;
  saving.value = true;
  try {
    const payload = { ...form };
    if (isCustomKategori.value && form.customKategori?.trim()) {
      payload.kategori = form.customKategori.trim();
    }
    const res = await AdminService.savePengumuman(payload, editId.value);
    const msg = res.message || 'Pengumuman berhasil disimpan ke database!';
    successMsg.value = msg;
    toast.success(msg, editId.value ? 'Pengumuman Diperbarui' : 'Pengumuman Diterbitkan');
    showModal.value = false;
    await loadData();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan pengumuman.';
    toast.error('Gagal menyimpan pengumuman: ' + errMsg);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) return;
  try {
    await AdminService.deletePengumuman(id);
    const msg = 'Pengumuman berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'Pengumuman Dihapus');
    await loadData();
  } catch (err) {
    toast.error('Gagal menghapus pengumuman.');
  }
};

onMounted(loadData);
</script>
