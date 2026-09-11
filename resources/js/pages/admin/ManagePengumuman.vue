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
                      <span v-if="p.file" class="text-[9px] bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded font-bold">Lampiran PDF</span>
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
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
      @click.self="showModal = false"
    >
      <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
          {{ editId ? 'Sunting Pengumuman' : 'Tambah Pengumuman Baru' }}
        </h3>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Judul Pengumuman *</label>
            <input 
              type="text" 
              v-model="form.judul" 
              required 
              placeholder="Contoh: Sosialisasi Penataan Lingkungan Bersih..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Prioritas *</label>
              <select 
                v-model="form.prioritas" 
                required 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option value="Penting">Penting</option>
                <option value="Himbauan">Himbauan</option>
                <option value="Pemberitahuan">Pemberitahuan</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Kategori Pengumuman *</label>
              <select 
                v-model="form.kategori" 
                required 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option v-for="k in kategoriOptions" :key="k.id" :value="k.nama">
                  {{ k.nama }}
                </option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Penyelenggara / Seksi</label>
              <input 
                type="text" 
                v-model="form.penyelenggara" 
                placeholder="Kelurahan / Kasi Trantib" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <!-- Banner Pengumuman -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
            <label class="block font-bold text-slate-900 text-xs">Banner Pengumuman (Spanduk Lanskap / Rasio 16:9)</label>
            <div class="flex flex-col sm:flex-row items-center gap-3">
              <div class="w-24 h-14 rounded-xl border border-slate-200 bg-white overflow-hidden shrink-0 flex items-center justify-center">
                <img v-if="form.banner" :src="form.banner" alt="Banner" class="w-full h-full object-cover" />
                <span v-else class="text-[10px] text-slate-400">Belum ada</span>
              </div>
              <div class="flex-1 w-full space-y-1.5">
                <div class="flex gap-2">
                  <input 
                    type="text" 
                    v-model="form.banner" 
                    placeholder="URL gambar banner atau unggah foto..." 
                    class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs outline-none"
                  />
                  <label class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl text-xs cursor-pointer whitespace-nowrap">
                    <span>{{ uploadingBanner ? 'Mengunggah...' : 'Unggah Foto' }}</span>
                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleBannerUpload" :disabled="uploadingBanner" />
                  </label>
                  <button v-if="form.banner" type="button" @click="form.banner = ''" class="px-2 py-1 bg-rose-50 text-rose-700 rounded-lg text-xs font-semibold">Hapus</button>
                </div>
                <p class="text-[10px] text-slate-500">Format gambar: JPG, PNG, WebP, SVG (Maks. 10MB).</p>
              </div>
            </div>
          </div>

          <!-- Thumbnail Pengumuman -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
            <label class="block font-bold text-slate-900 text-xs">Thumbnail Pengumuman (Gambar Mini Kotak / 1:1)</label>
            <div class="flex flex-col sm:flex-row items-center gap-3">
              <div class="w-14 h-14 rounded-xl border border-slate-200 bg-white overflow-hidden shrink-0 flex items-center justify-center">
                <img v-if="form.thumbnail" :src="form.thumbnail" alt="Thumbnail" class="w-full h-full object-cover" />
                <span v-else class="text-[10px] text-slate-400">Belum ada</span>
              </div>
              <div class="flex-1 w-full space-y-1.5">
                <div class="flex gap-2">
                  <input 
                    type="text" 
                    v-model="form.thumbnail" 
                    placeholder="URL gambar thumbnail atau unggah foto..." 
                    class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs outline-none"
                  />
                  <label class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl text-xs cursor-pointer whitespace-nowrap">
                    <span>{{ uploadingThumbnail ? 'Mengunggah...' : 'Unggah Foto' }}</span>
                    <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleThumbnailUpload" :disabled="uploadingThumbnail" />
                  </label>
                  <button v-if="form.thumbnail" type="button" @click="form.thumbnail = ''" class="px-2 py-1 bg-rose-50 text-rose-700 rounded-lg text-xs font-semibold">Hapus</button>
                </div>
                <p class="text-[10px] text-slate-500">Format gambar: JPG, PNG, WebP, SVG (Maks. 10MB).</p>
              </div>
            </div>
          </div>

          <!-- Dokumen Lampiran PDF -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
            <label class="block font-bold text-slate-900 text-xs">File Dokumen Lampiran PDF (Opsional)</label>
            <div class="flex gap-2">
              <input 
                type="text" 
                v-model="form.file" 
                placeholder="URL atau nama file dokumen PDF lampiran..." 
                class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 bg-white text-xs outline-none"
              />
              <label class="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold rounded-xl text-xs cursor-pointer whitespace-nowrap flex items-center gap-1">
                <span>{{ uploadingFile ? 'Mengunggah...' : 'Unggah PDF' }}</span>
                <input type="file" accept="application/pdf" class="hidden" @change="handlePdfUpload" :disabled="uploadingFile" />
              </label>
              <button v-if="form.file" type="button" @click="form.file = ''" class="px-2.5 py-2 bg-rose-50 text-rose-700 rounded-xl text-xs font-semibold">Hapus</button>
            </div>
            <p class="text-[10px] text-slate-500">Format dokumen: Khusus PDF resmi kedinasan (Maks. 10MB).</p>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Uraian Isi Pengumuman *</label>
            <textarea 
              rows="4" 
              v-model="form.isi" 
              required 
              placeholder="Rincian informasi pengumuman..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold"
            >
              Batal
            </button>
            <button 
              type="submit" 
              :disabled="saving"
              class="px-5 py-2 rounded-xl bg-amber-500 hover:bg-amber-400 disabled:opacity-50 text-slate-950 font-bold"
            >
              <span v-if="saving">Menyimpan...</span>
              <span v-else>Simpan Pengumuman</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import { AdminService } from '../../services/api';

const loading = ref(true);
const saving = ref(false);
const uploadingBanner = ref(false);
const uploadingThumbnail = ref(false);
const uploadingFile = ref(false);
const pengumumanList = ref([]);
const kategoriOptions = ref([]);
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');

const form = reactive({
  judul: '',
  prioritas: 'Penting',
  kategori: 'Kedinasan',
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

const handleBannerUpload = async (e) => {
  const file = e.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    alert('Format file banner tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).');
    e.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran file banner terlalu besar! Maksimal 10MB.');
    e.target.value = '';
    return;
  }

  uploadingBanner.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    if (res.data?.url) {
      form.banner = res.data.url;
    }
  } catch (err) {
    alert('Gagal mengunggah banner: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingBanner.value = false;
    e.target.value = '';
  }
};

const handleThumbnailUpload = async (e) => {
  const file = e.target.files?.[0];
  if (!file) return;

  if (!isValidImageFile(file)) {
    alert('Format file thumbnail tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).');
    e.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran file thumbnail terlalu besar! Maksimal 10MB.');
    e.target.value = '';
    return;
  }

  uploadingThumbnail.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    if (res.data?.url) {
      form.thumbnail = res.data.url;
    }
  } catch (err) {
    alert('Gagal mengunggah thumbnail: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingThumbnail.value = false;
    e.target.value = '';
  }
};

const handlePdfUpload = async (e) => {
  const file = e.target.files?.[0];
  if (!file) return;

  const ext = file.name.split('.').pop()?.toLowerCase();
  const isValidPdf = file.type === 'application/pdf' || ext === 'pdf';

  if (!isValidPdf) {
    alert('Format file dokumen tidak valid! Harap pilih file dokumen PDF (.pdf).');
    e.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran file dokumen terlalu besar! Maksimal 10MB.');
    e.target.value = '';
    return;
  }

  uploadingFile.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'document');
    if (res.data?.url) {
      form.file = res.data.url;
    }
  } catch (err) {
    alert('Gagal mengunggah dokumen PDF: ' + (err.response?.data?.message || err.message));
  } finally {
    uploadingFile.value = false;
    e.target.value = '';
  }
};

const saveItem = async () => {
  saving.value = true;
  try {
    const res = await AdminService.savePengumuman(form, editId.value);
    successMsg.value = res.message || 'Pengumuman berhasil disimpan ke database!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan pengumuman: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) return;
  try {
    await AdminService.deletePengumuman(id);
    successMsg.value = 'Pengumuman berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus pengumuman.');
  }
};

onMounted(loadData);
</script>
