<template>
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Kelola Galeri & Dokumentasi</h1>
        <p class="text-xs text-slate-500">Unggah dokumentasi foto dan sematkan video dokumentasi kegiatan pemerintahan serta masyarakat Kraksaan Wetan.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:scale-95 text-white font-bold text-xs shadow-sm transition-all duration-200 cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Media Galeri
      </button>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700 font-bold">&times;</button>
    </div>

    <!-- Filter Tab: Semua, Foto, Video -->
    <div class="flex flex-wrap items-center gap-2">
      <button 
        @click="adminFilter = 'semua'"
        class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer"
        :class="adminFilter === 'semua' 
          ? 'bg-emerald-700 text-white shadow-sm' 
          : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
      >
        Semua ({{ galeriList.length }})
      </button>
      <button 
        @click="adminFilter = 'foto'"
        class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center gap-1.5"
        :class="adminFilter === 'foto' 
          ? 'bg-emerald-700 text-white shadow-sm' 
          : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
      >
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <span>Foto Kegiatan ({{ galeriList.filter(g => g.tipe !== 'video').length }})</span>
      </button>
      <button 
        @click="adminFilter = 'video'"
        class="px-4 py-2 rounded-xl text-xs font-bold transition-all duration-200 cursor-pointer flex items-center gap-1.5"
        :class="adminFilter === 'video' 
          ? 'bg-rose-600 text-white shadow-sm' 
          : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
      >
        <svg class="w-3.5 h-3.5" :class="adminFilter === 'video' ? 'text-white' : 'text-rose-500'" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
        <span>Video Dokumentasi ({{ galeriList.filter(g => g.tipe === 'video').length }})</span>
      </button>
    </div>

    <LoadingSpinner v-if="loading" />
    <div v-else-if="filteredGaleriList.length" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="g in filteredGaleriList" 
        :key="g.id"
        class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition-all duration-300 flex flex-col justify-between group"
      >
        <div>
          <div class="h-44 w-full bg-slate-100 overflow-hidden relative">
            <img :src="g.gambar" :alt="g.judul" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" />
            
            <span class="absolute top-2 left-2 px-2.5 py-0.5 rounded bg-emerald-800/90 text-white text-[10px] font-bold uppercase backdrop-blur-xs">
              {{ g.kategori }}
            </span>

            <!-- Video Dokumentasi Badge -->
            <span 
              v-if="g.tipe === 'video'" 
              class="absolute top-2 right-2 px-2 py-0.5 rounded bg-rose-600 text-white text-[10px] font-bold uppercase flex items-center gap-1 shadow-xs"
            >
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
              <span>Video</span>
            </span>

            <!-- Center Play Icon Overlay -->
            <div 
              v-if="g.tipe === 'video'" 
              class="absolute inset-0 flex items-center justify-center bg-black/25 group-hover:bg-black/40 transition-colors"
            >
              <div class="w-11 h-11 rounded-full bg-rose-600/90 text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
              </div>
            </div>
          </div>
          <div class="p-4">
            <div class="flex items-center gap-2 text-[11px] text-slate-400 mb-1">
              <span>{{ g.tanggal }}</span>
              <span>&bull;</span>
              <span class="font-semibold" :class="g.tipe === 'video' ? 'text-rose-600' : 'text-emerald-700'">
                {{ g.tipe === 'video' ? 'Video Dokumentasi' : 'Foto Kegiatan' }}
              </span>
            </div>
            <h4 class="font-bold text-slate-900 text-sm mb-1 group-hover:text-emerald-700 transition-colors">{{ g.judul }}</h4>
            <p class="text-xs text-slate-500 line-clamp-2">{{ g.deskripsi || 'Tidak ada keterangan tambahan.' }}</p>
          </div>
        </div>

        <div class="p-4 pt-0 border-t border-slate-100 flex items-center justify-between gap-2 mt-auto">
          <a 
            v-if="g.tipe === 'video' && g.video_url" 
            :href="g.video_url" 
            target="_blank" 
            rel="noopener"
            class="text-[11px] text-rose-600 hover:text-rose-800 font-bold flex items-center gap-1 transition"
          >
            <span>Buka Video</span>
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </a>
          <span v-else class="text-[11px] text-slate-400">ID: #{{ g.id }}</span>

          <div class="flex items-center gap-2">
            <button 
              @click="openModal(g)" 
              class="px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 active:scale-95 text-slate-700 font-semibold text-xs transition cursor-pointer"
            >
              Edit
            </button>
            <button 
              @click="deleteItem(g.id)" 
              class="px-3 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 active:scale-95 text-rose-700 font-semibold text-xs transition cursor-pointer"
            >
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200">
      <p class="text-slate-500 text-xs">Belum ada konten galeri pada kategori ini.</p>
    </div>

    <!-- Modal Form Tambah / Edit -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs"
      @click.self="showModal = false"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 my-8">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
          <h3 class="text-lg font-bold text-slate-900">
            {{ editId ? 'Sunting Item Galeri' : 'Tambah Media Galeri Baru' }}
          </h3>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 text-lg cursor-pointer">&times;</button>
        </div>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <!-- Type Selector: Foto vs Video Dokumentasi -->
          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Tipe Dokumentasi *</label>
            <div class="grid grid-cols-2 gap-3">
              <button 
                type="button" 
                @click="form.tipe = 'foto'"
                class="py-2.5 px-3 rounded-xl border font-bold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer"
                :class="form.tipe === 'foto' 
                  ? 'bg-emerald-50 border-emerald-500 text-emerald-800 ring-2 ring-emerald-500/20 shadow-xs' 
                  : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Foto Kegiatan</span>
              </button>

              <button 
                type="button" 
                @click="form.tipe = 'video'"
                class="py-2.5 px-3 rounded-xl border font-bold text-xs flex items-center justify-center gap-2 transition-all cursor-pointer"
                :class="form.tipe === 'video' 
                  ? 'bg-rose-50 border-rose-500 text-rose-800 ring-2 ring-rose-500/20 shadow-xs' 
                  : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'"
              >
                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                <span>Video Dokumentasi</span>
              </button>
            </div>
          </div>

          <!-- Judul Input -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">
              {{ form.tipe === 'video' ? 'Judul Video Dokumentasi *' : 'Judul Foto Kegiatan *' }}
            </label>
            <input 
              type="text" 
              v-model="form.judul" 
              required 
              :placeholder="form.tipe === 'video' ? 'Contoh: Liputan Penyaluran Bantuan Pangan Kraksaan Wetan' : 'Contoh: Kerja Bakti RW 03 Kauman'" 
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
              <label class="block font-bold text-slate-700 mb-1">Tanggal Kegiatan</label>
              <input 
                type="text" 
                v-model="form.tanggal" 
                placeholder="14 September 2026" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <!-- Section Khusus Video Dokumentasi -->
          <div v-if="form.tipe === 'video'" class="p-4 rounded-2xl bg-rose-50/70 border border-rose-200/80 space-y-3">
            <div>
              <label class="block font-bold text-rose-950 mb-1 flex items-center gap-1.5">
                <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                <span>Tautan / URL Video Dokumentasi *</span>
              </label>
              <input 
                type="url" 
                v-model="form.video_url" 
                required 
                placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..." 
                class="w-full px-3.5 py-2 rounded-xl border border-rose-200 bg-white focus:ring-2 focus:ring-rose-500 outline-none text-xs"
              />
              <p class="text-[11px] text-rose-700/90 mt-1">
                Salin tautan video dari YouTube untuk ditampilkan sebagai video dokumentasi.
              </p>
            </div>

            <!-- Live Video Preview -->
            <div v-if="youtubeId" class="space-y-2 pt-1">
              <div class="flex items-center justify-between text-[11px]">
                <span class="font-bold text-emerald-700 flex items-center gap-1">
                  ✓ Video Terdeteksi: <code class="bg-white px-1.5 py-0.5 rounded border border-emerald-300 font-mono">{{ youtubeId }}</code>
                </span>
                <span class="text-slate-500">Pratinjau Video:</span>
              </div>
              <div class="aspect-video w-full rounded-xl overflow-hidden bg-black shadow-inner">
                <iframe 
                  :src="`https://www.youtube-nocookie.com/embed/${youtubeId}`" 
                  class="w-full h-full"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                ></iframe>
              </div>
            </div>

            <!-- Opsional Custom Thumbnail for Video -->
            <div class="pt-2 border-t border-rose-200/60">
              <label class="block font-semibold text-slate-700 mb-1 text-xs">
                Foto Sampul / Thumbnail Kustom (Opsional)
              </label>
              <div class="flex gap-2">
                <input 
                  type="text" 
                  v-model="form.gambar" 
                  placeholder="Kosongkan untuk memakai thumbnail otomatis" 
                  class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs outline-none focus:ring-2 focus:ring-emerald-600"
                />
                <label class="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-xl cursor-pointer text-xs flex items-center gap-1 border border-slate-200">
                  <span>Pilih File</span>
                  <input type="file" accept="image/*" class="hidden" @change="handleFileUpload" :disabled="uploading" />
                </label>
              </div>
              <p class="text-[10px] text-slate-500 mt-1">
                * Jika dikosongkan, sistem secara otomatis memasang thumbnail resmi dari video.
              </p>
            </div>
          </div>

          <!-- Section Khusus Foto Kegiatan -->
          <div v-else class="space-y-2">
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
            <!-- Preview Foto -->
            <div v-if="form.gambar" class="mt-2 relative rounded-xl overflow-hidden h-32 bg-slate-100 border border-slate-200">
              <img :src="form.gambar" alt="Pratinjau" class="w-full h-full object-cover" />
              <button 
                type="button" 
                @click="form.gambar = ''" 
                class="absolute top-2 right-2 px-2 py-0.5 rounded bg-black/70 text-white text-[10px] hover:bg-black cursor-pointer"
              >
                Hapus
              </button>
            </div>
          </div>

          <!-- Keterangan / Deskripsi -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Keterangan / Deskripsi Kegiatan</label>
            <textarea 
              rows="3" 
              v-model="form.deskripsi" 
              placeholder="Keterangan singkat rekam jejak kegiatan atau isi konten video..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            ></textarea>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 font-semibold cursor-pointer"
            >
              Batal
            </button>
            <button 
              type="submit" 
              :disabled="saving"
              class="px-5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 active:scale-95 disabled:opacity-50 text-white font-bold transition-all duration-200 cursor-pointer shadow-sm"
            >
              <span v-if="saving">Menyimpan...</span>
              <span v-else>{{ editId ? 'Perbarui Data' : 'Simpan ke Galeri' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
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
const adminFilter = ref('semua');

const form = reactive({
  judul: '',
  kategori: 'Pemerintahan',
  tipe: 'foto',
  tanggal: '',
  gambar: '',
  video_url: '',
  deskripsi: ''
});

// Helper untuk mengekstrak YouTube Video ID
const youtubeId = computed(() => {
  if (!form.video_url) return null;
  const match = form.video_url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/i);
  return match ? match[1] : null;
});

const autoThumbnailUrl = computed(() => {
  return youtubeId.value ? `https://img.youtube.com/vi/${youtubeId.value}/hqdefault.jpg` : null;
});

const filteredGaleriList = computed(() => {
  if (adminFilter.value === 'foto') {
    return galeriList.value.filter(g => g.tipe !== 'video');
  }
  if (adminFilter.value === 'video') {
    return galeriList.value.filter(g => g.tipe === 'video');
  }
  return galeriList.value;
});

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
    form.tipe = item.tipe || 'foto';
    form.tanggal = item.tanggal;
    form.gambar = item.gambar || '';
    form.video_url = item.video_url || '';
    form.deskripsi = item.deskripsi || '';
  } else {
    editId.value = null;
    form.judul = '';
    form.kategori = kategoriOptions.value.length > 0 ? kategoriOptions.value[0].nama : 'Pemerintahan';
    form.tipe = 'foto';
    form.tanggal = '';
    form.gambar = '';
    form.video_url = '';
    form.deskripsi = '';
  }
  showModal.value = true;
};

const saveItem = async () => {
  if (form.tipe === 'video' && !form.video_url) {
    alert('Harap masukkan tautan video dokumentasi!');
    return;
  }
  if (form.tipe === 'foto' && !form.gambar) {
    alert('Harap masukkan atau unggah foto dokumentasi!');
    return;
  }

  saving.value = true;
  try {
    const payload = { ...form };
    // Jika video dan thumbnail dikosongkan, pasang auto thumbnail YouTube
    if (payload.tipe === 'video' && !payload.gambar && autoThumbnailUrl.value) {
      payload.gambar = autoThumbnailUrl.value;
    }

    const res = await AdminService.saveGaleri(payload, editId.value);
    successMsg.value = res.message || 'Data galeri berhasil disimpan!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan galeri: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Hapus item ini dari galeri?')) return;
  try {
    await AdminService.deleteGaleri(id);
    successMsg.value = 'Item galeri berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus item galeri.');
  }
};

onMounted(loadData);
</script>
