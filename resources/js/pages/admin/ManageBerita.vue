<template>
  <div class="space-y-6">
    <!-- Header Action -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Berita Kelurahan</h2>
        <p class="text-xs text-slate-500">Tambah, sunting, dan publikasikan warta kegiatan ke database.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tulis Artikel Baru
      </button>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700">&times;</button>
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
              <th class="py-3.5 px-4">Tanggal</th>
              <th class="py-3.5 px-4 text-center">Dilihat</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-for="b in beritaList" :key="b.id" class="hover:bg-slate-50">
              <td class="py-3 px-4">
                <img :src="b.gambar" :alt="b.judul" class="w-12 h-10 object-cover rounded-lg bg-slate-100" />
              </td>
              <td class="py-3 px-4 max-w-sm">
                <p class="font-bold text-slate-900 line-clamp-1">{{ b.judul }}</p>
                <p class="text-[11px] text-slate-400 line-clamp-1">{{ b.ringkasan }}</p>
              </td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-800 font-semibold text-[11px]">
                  {{ b.kategori }}
                </span>
              </td>
              <td class="py-3 px-4 whitespace-nowrap">{{ b.tanggal }}</td>
              <td class="py-3 px-4 text-center font-mono font-semibold">{{ b.dilihat }}</td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <button 
                  @click="openModal(b)" 
                  class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5"
                >
                  Edit
                </button>
                <button 
                  @click="deleteItem(b.id)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold"
                >
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="!beritaList.length">
              <td colspan="6" class="py-8 text-center text-slate-400">Belum ada artikel berita di database.</td>
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
          {{ editId ? 'Sunting Berita' : 'Tulis Berita Baru' }}
        </h3>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Judul Artikel *</label>
            <input 
              type="text" 
              v-model="form.judul" 
              required 
              placeholder="Contoh: Musrenbangkel Kraksaan Wetan..." 
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
              <label class="block font-bold text-slate-700 mb-1">Penulis</label>
              <input 
                type="text" 
                v-model="form.penulis" 
                placeholder="Tim Humas Kelurahan" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Gambar / Foto Artikel</label>
            <div class="flex flex-col sm:flex-row gap-2">
              <input 
                type="text" 
                v-model="form.gambar" 
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
            <label class="block font-bold text-slate-700 mb-1">Ringkasan (Excerpt) *</label>
            <textarea 
              rows="2" 
              v-model="form.ringkasan" 
              required 
              placeholder="Ringkasan 1-2 kalimat..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            ></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Isi Konten Lengkap *</label>
            <textarea 
              rows="6" 
              v-model="form.konten" 
              required 
              placeholder="Paragraf artikel lengkap..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-sans"
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
              <span v-else>Simpan Artikel</span>
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
const beritaList = ref([]);
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
    alert('Ukuran file terlalu besar! Maksimal 10MB.');
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
  penulis: 'Tim Humas Kelurahan',
  gambar: '',
  ringkasan: '',
  konten: ''
});

const loadData = async () => {
  loading.value = true;
  try {
    const [beritas, kats] = await Promise.all([
      AdminService.getBerita(),
      AdminService.getMasterKategori('berita')
    ]);
    beritaList.value = beritas || [];
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
    form.penulis = item.penulis;
    form.gambar = item.gambar;
    form.ringkasan = item.ringkasan;
    form.konten = item.konten;
  } else {
    editId.value = null;
    form.judul = '';
    form.kategori = kategoriOptions.value.length > 0 ? kategoriOptions.value[0].nama : 'Pemerintahan';
    form.penulis = 'Tim Humas Kelurahan';
    form.gambar = 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80';
    form.ringkasan = '';
    form.konten = '';
  }
  showModal.value = true;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const res = await AdminService.saveBerita(form, editId.value);
    successMsg.value = res.message || 'Berita berhasil disimpan ke database!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan berita: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus artikel berita ini dari database?')) return;
  try {
    await AdminService.deleteBerita(id);
    successMsg.value = 'Berita berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus berita.');
  }
};

onMounted(loadData);
</script>
