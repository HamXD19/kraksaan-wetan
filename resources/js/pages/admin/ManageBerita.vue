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
                <div v-if="b.slug" class="mt-1 flex items-center gap-1.5">
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600 border border-slate-200">
                    /berita/{{ b.slug }}
                  </span>
                  <a 
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
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs"
    >
      <div class="bg-white rounded-2xl sm:rounded-3xl max-w-5xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
        <!-- Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">
              {{ editId ? 'Sunting Berita' : 'Tulis Berita Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola judul, kategori, ringkasan, dan isi warta kegiatan kelurahan.</p>
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
                    placeholder="Contoh: Musrenbangkel Kraksaan Wetan..." 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-medium"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1">Isi Konten Lengkap *</label>
                  <RichTextEditor 
                    v-model="form.konten"
                    placeholder="Tulis paragraf artikel lengkap layaknya Microsoft Word..."
                    height="240px"
                    maxHeight="340px"
                  />
                </div>
              </div>

              <!-- Right Column: Meta, Foto & Ringkasan -->
              <div class="lg:col-span-5 space-y-4 bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80">
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

                <div>
                  <label class="block font-bold text-slate-700 mb-1 text-xs">Gambar / Foto Artikel</label>
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
                          Unggah
                        </span>
                        <input type="file" accept="image/png, image/jpeg, image/jpg, image/webp, image/svg+xml" class="hidden" @change="handleFileUpload" :disabled="uploading" />
                      </label>
                    </div>
                    <div v-if="form.gambar" class="w-full h-24 rounded-xl border border-slate-200 overflow-hidden bg-white shadow-2xs">
                      <img :src="form.gambar" alt="Preview Foto" class="w-full h-full object-cover" />
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1 text-xs">Ringkasan (Excerpt) *</label>
                  <textarea 
                    rows="3" 
                    v-model="form.ringkasan" 
                    required 
                    placeholder="Ringkasan 1-2 kalimat untuk pratinjau kartu berita..." 
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
                <span v-else>{{ editId ? 'Simpan Perubahan' : 'Terbitkan Artikel' }}</span>
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
const uploading = ref(false);
const beritaList = ref([]);
const kategoriOptions = ref([]);
const isCustomKategori = ref(false);
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
    toast.error('Format file tidak valid! Harap pilih file gambar (JPG, PNG, WebP, SVG).');
    event.target.value = '';
    return;
  }
  if (file.size > 10 * 1024 * 1024) {
    toast.error('Ukuran file terlalu besar! Maksimal 10MB.');
    event.target.value = '';
    return;
  }

  uploading.value = true;
  try {
    const res = await AdminService.uploadFile(file, 'image');
    if (res.data?.url) {
      form.gambar = res.data.url;
      successMsg.value = 'Foto berhasil diunggah!';
      toast.success('Foto berhasil diunggah!');
    }
  } catch (err) {
    toast.error('Gagal mengunggah foto: ' + (err.response?.data?.message || err.message));
  } finally {
    uploading.value = false;
    event.target.value = '';
  }
};

const form = reactive({
  judul: '',
  kategori: 'Pemerintahan',
  customKategori: '',
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
  isCustomKategori.value = false;
  form.customKategori = '';
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
    form.kategori = kategoriOptions.value.length > 0 ? (kategoriOptions.value[0]?.nama || kategoriOptions.value[0]) : 'Pemerintahan';
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
    const payload = { ...form };
    if (isCustomKategori.value && form.customKategori?.trim()) {
      payload.kategori = form.customKategori.trim();
    }
    const res = await AdminService.saveBerita(payload, editId.value);
    const msg = res.message || 'Berita berhasil disimpan ke database!';
    successMsg.value = msg;
    toast.success(msg, editId.value ? 'Berita Diperbarui' : 'Berita Diterbitkan');
    showModal.value = false;
    await loadData();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan berita.';
    toast.error('Gagal menyimpan berita: ' + errMsg);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus artikel berita ini dari database?')) return;
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
