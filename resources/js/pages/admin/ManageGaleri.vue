<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Galeri Foto</h2>
        <p class="text-xs text-slate-500">Unggah dan kelola arsip visual giat pemerintahan dan masyarakat Kraksaan Wetan.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Foto Galeri
      </button>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700">&times;</button>
    </div>

    <LoadingSpinner v-if="loading" />
    <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="g in galeriList" 
        :key="g.id"
        class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs flex flex-col justify-between"
      >
        <div>
          <div class="h-44 w-full bg-slate-100 overflow-hidden relative">
            <img :src="g.gambar" :alt="g.judul" class="w-full h-full object-cover" />
            <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded bg-emerald-800/90 text-white text-[10px] font-bold uppercase">
              {{ g.kategori }}
            </span>
          </div>
          <div class="p-4">
            <p class="text-[11px] text-slate-400 mb-1">{{ g.tanggal }}</p>
            <h4 class="font-bold text-slate-900 text-sm mb-1">{{ g.judul }}</h4>
            <p class="text-xs text-slate-500 line-clamp-2">{{ g.deskripsi }}</p>
          </div>
        </div>

        <div class="p-4 pt-0 border-t border-slate-100 flex items-center justify-end gap-2 mt-auto">
          <button 
            @click="openModal(g)" 
            class="px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs"
          >
            Edit
          </button>
          <button 
            @click="deleteItem(g.id)" 
            class="px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold text-xs"
          >
            Hapus
          </button>
        </div>
      </div>
    </div>

    <!-- Modal Form Tambah / Edit -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
      @click.self="showModal = false"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
          {{ editId ? 'Sunting Foto Galeri' : 'Tambah Foto Baru' }}
        </h3>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Judul Foto / Kegiatan *</label>
            <input 
              type="text" 
              v-model="form.judul" 
              required 
              placeholder="Contoh: Kerja Bakti RW 03 Kauman" 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Kategori *</label>
              <select 
                v-model="form.kategori" 
                required 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option v-for="k in kategoriOptions" :key="k.id" :value="k.nama">
                  {{ k.nama }}
                </option>
                <option v-if="form.kategori && !kategoriOptions.some(k => k.nama === form.kategori)" :value="form.kategori">
                  {{ form.kategori }}
                </option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Tanggal</label>
              <input 
                type="text" 
                v-model="form.tanggal" 
                placeholder="04 Maret 2026" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Foto Kegiatan *</label>
            <div class="flex flex-col sm:flex-row gap-2">
              <input 
                type="text" 
                v-model="form.gambar" 
                required 
                placeholder="https://... atau pilih upload file" 
                class="flex-1 px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
              <label class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold rounded-xl cursor-pointer text-center text-xs flex items-center justify-center gap-1.5 border border-emerald-200">
                <span v-if="uploading">Mengunggah...</span>
                <span v-else class="flex items-center gap-1">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  Unggah Foto
                </span>
                <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleFileUpload" :disabled="uploading" />
              </label>
            </div>
            <p v-if="form.gambar" class="text-[11px] text-emerald-600 mt-1 flex items-center gap-1">
              ✓ URL Foto terpasang
            </p>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Keterangan / Deskripsi</label>
            <textarea 
              rows="3" 
              v-model="form.deskripsi" 
              placeholder="Keterangan singkat momen foto..." 
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
              class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold"
            >
              <span v-if="saving">Menyimpan...</span>
              <span v-else>Simpan Foto</span>
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
const uploading = ref(false);
const galeriList = ref([]);
const kategoriOptions = ref([]);
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');

const handleFileUpload = async (event) => {
  const file = event.target.files?.[0];
  if (!file) return;

  const allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/gif', 'image/jpg'];
  const ext = file.name.split('.').pop()?.toLowerCase();
  const allowedExts = ['jpg', 'jpeg', 'png', 'webp', 'svg', 'gif'];
  const isValidImage = (allowedMimeTypes.includes(file.type) || file.type.startsWith('image/')) && allowedExts.includes(ext);

  if (!isValidImage) {
    alert('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran file foto terlalu besar! Maksimal 10MB.');
    event.target.value = '';
    return;
  }

  uploading.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    if (res.data?.url) {
      form.gambar = res.data.url;
      successMsg.value = 'Foto berhasil diunggah!';
    }
  } catch (err) {
    alert('Gagal mengunggah foto: ' + (err.response?.data?.message || err.message));
  } finally {
    uploading.value = false;
    event.target.value = '';
  }
};

const form = reactive({
  judul: '',
  kategori: 'Pemerintahan',
  tanggal: '',
  gambar: '',
  deskripsi: ''
});

const loadData = async () => {
  loading.value = true;
  try {
    const [galeris, kats] = await Promise.all([
      AdminService.getGaleri(),
      AdminService.getMasterKategori('galeri')
    ]);
    galeriList.value = galeris || [];
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
    form.kategori = item.kategori;
    form.tanggal = item.tanggal;
    form.gambar = item.gambar;
    form.deskripsi = item.deskripsi || '';
  } else {
    editId.value = null;
    form.judul = '';
    form.kategori = kategoriOptions.value.length > 0 ? kategoriOptions.value[0].nama : 'Pemerintahan';
    form.tanggal = '';
    form.gambar = '';
    form.deskripsi = '';
  }
  showModal.value = true;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const res = await AdminService.saveGaleri(form, editId.value);
    successMsg.value = res.message || 'Foto galeri berhasil disimpan!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan galeri: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Hapus foto ini dari galeri?')) return;
  try {
    await AdminService.deleteGaleri(id);
    successMsg.value = 'Foto berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus foto.');
  }
};

onMounted(loadData);
</script>
