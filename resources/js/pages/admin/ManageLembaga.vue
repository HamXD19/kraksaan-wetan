<template>
  <div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-[11px] font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
          Mitra Pembangunan Wilayah
        </div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Lembaga Kemasyarakatan (LKK)</h2>
        <p class="text-xs text-slate-500 mt-0.5">Atur daftar organisasi kemasyarakatan mitra kelurahan, profil pimpinan, kontak, dan program kerja unggulan.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition self-start sm:self-auto"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        <span>Tambah Lembaga Baru</span>
      </button>
    </div>

    <!-- Alert Sukses / Info -->
    <div v-if="successMsg" class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between shadow-xs">
      <div class="flex items-center gap-2">
        <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ successMsg }}</span>
      </div>
      <button @click="successMsg = ''" class="text-emerald-700 hover:text-emerald-900 font-bold">&times;</button>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <p class="text-[11px] font-semibold text-slate-400">Total Lembaga</p>
        <p class="text-2xl font-black text-slate-900 mt-1">{{ lembagaList.length }}</p>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <p class="text-[11px] font-semibold text-slate-400">Lembaga Aktif</p>
        <p class="text-2xl font-black text-emerald-700 mt-1">{{ activeLembagaCount }}</p>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <p class="text-[11px] font-semibold text-slate-400">Nonaktif / Arsip</p>
        <p class="text-2xl font-black text-slate-400 mt-1">{{ lembagaList.length - activeLembagaCount }}</p>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <p class="text-[11px] font-semibold text-slate-400">Tampil di Publik</p>
        <p class="text-2xl font-black text-amber-600 mt-1">Live</p>
      </div>
    </div>

    <!-- Konten / Loading -->
    <LoadingSpinner v-if="loading" />

    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <!-- Toolbar Filter & Pencarian -->
      <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 bg-slate-50/50">
        <div class="relative w-full sm:w-72">
          <input 
            type="text" 
            v-model="searchQuery" 
            placeholder="Cari nama lembaga atau singkatan..." 
            class="w-full pl-9 pr-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        <div class="text-xs text-slate-500 font-medium">
          Menampilkan {{ filteredLembaga.length }} dari {{ lembagaList.length }} data lembaga
        </div>
      </div>

      <!-- Tabel Lembaga -->
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
            <tr>
              <th class="py-3.5 px-4">Lembaga / Inisial</th>
              <th class="py-3.5 px-4">Kategori & Pimpinan</th>
              <th class="py-3.5 px-4">Program Kerja</th>
              <th class="py-3.5 px-4 text-center">Urutan</th>
              <th class="py-3.5 px-4 text-center">Status Publik</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-for="item in filteredLembaga" :key="item.id" class="hover:bg-slate-50/80 transition">
              <!-- Singkatan & Nama -->
              <td class="py-3 px-4 max-w-xs sm:max-w-sm">
                <div class="flex items-start gap-3">
                  <div 
                    class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs uppercase"
                    :class="getThemeClasses(item.warna_tema).badge"
                  >
                    {{ item.singkatan || item.nama.substring(0, 3) }}
                  </div>
                  <div>
                    <p class="font-bold text-slate-900 text-xs sm:text-sm leading-snug">{{ item.nama }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5 line-clamp-1">{{ item.deskripsi }}</p>
                  </div>
                </div>
              </td>

              <!-- Kategori & Pimpinan -->
              <td class="py-3 px-4 whitespace-nowrap">
                <span 
                  class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider mb-1"
                  :class="getThemeClasses(item.warna_tema).pill"
                >
                  {{ item.kategori || 'Umum' }}
                </span>
                <p class="font-semibold text-slate-800 text-[11px]">Ketua: {{ item.ketua || '-' }}</p>
                <p class="text-[10px] text-slate-400">{{ item.kontak || item.alamat || '-' }}</p>
              </td>

              <!-- Program Kerja & Anggota -->
              <td class="py-3 px-4">
                <div class="flex flex-col gap-0.5">
                  <span class="font-semibold text-slate-700 text-[11px]">
                    {{ (item.program_kerja && item.program_kerja.length) ? item.program_kerja.length + ' Program Unggulan' : 'Belum diisi' }}
                  </span>
                  <span class="text-[10px] text-slate-400">{{ item.jumlah_anggota || 'Kader/Pengurus aktif' }}</span>
                </div>
              </td>

              <!-- Urutan -->
              <td class="py-3 px-4 text-center font-mono font-bold text-slate-700">
                {{ item.urutan ?? 0 }}
              </td>

              <!-- Status Toggle -->
              <td class="py-3 px-4 text-center whitespace-nowrap">
                <button 
                  @click="toggleAktif(item)" 
                  class="px-2.5 py-1 rounded-full text-[10px] font-bold transition inline-flex items-center gap-1.5 cursor-pointer shadow-xs"
                  :class="item.aktif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                  :title="item.aktif ? 'Lembaga ditampilkan di website publik. Klik untuk menyembunyikan.' : 'Lembaga disembunyikan. Klik untuk menampilkan.'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="item.aktif ? 'bg-emerald-600' : 'bg-slate-400'"></span>
                  <span>{{ item.aktif ? 'Aktif' : 'Nonaktif' }}</span>
                </button>
              </td>

              <!-- Aksi -->
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <button 
                  @click="openModal(item)" 
                  class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5 transition"
                >
                  Edit
                </button>
                <button 
                  @click="deleteItem(item)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold transition"
                >
                  Hapus
                </button>
              </td>
            </tr>

            <tr v-if="!filteredLembaga.length">
              <td colspan="6" class="py-10 text-center text-slate-400 text-xs">
                Tidak ada data lembaga yang cocok dengan pencarian.
              </td>
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
      <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-8 shadow-2xl border border-slate-200 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
          <div>
            <h3 class="text-lg font-bold text-slate-900">
              {{ editId ? 'Edit Data Lembaga Kemasyarakatan' : 'Tambah Lembaga Kemasyarakatan' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola informasi organisasi mitra, struktur pimpinan, dan program kerjanya.</p>
          </div>
          <button @click="showModal = false" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Nama Lembaga -->
            <div class="sm:col-span-2">
              <label class="block font-bold text-slate-700 mb-1">Nama Lembaga *</label>
              <input 
                type="text" 
                v-model="form.nama" 
                required 
                placeholder="Contoh: Lembaga Pemberdayaan Masyarakat Kelurahan" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <!-- Singkatan -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Singkatan / Inisial *</label>
              <input 
                type="text" 
                v-model="form.singkatan" 
                required 
                placeholder="LPMK, PKK, dll." 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none uppercase font-bold"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <!-- Kategori -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Kategori Lembaga *</label>
              <select 
                v-model="form.kategori" 
                required 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option value="" disabled>-- Pilih Kategori Lembaga --</option>
                <option v-for="k in kategoriOptions" :key="k.id" :value="k.nama">
                  {{ k.nama }}
                </option>
                <option v-if="form.kategori && !kategoriOptions.some(k => k.nama === form.kategori)" :value="form.kategori">
                  {{ form.kategori }}
                </option>
              </select>
            </div>

            <!-- Pilihan Warna Aksen -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Warna Aksen Kartu</label>
              <select 
                v-model="form.warna_tema" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option value="emerald">Emerald (Hijau Kelurahan)</option>
                <option value="amber">Amber (Kuning Keemasan)</option>
                <option value="rose">Rose (Merah Muda PKK)</option>
                <option value="blue">Blue (Biru Karang Taruna)</option>
                <option value="indigo">Indigo (Nila Medis/Kesehatan)</option>
                <option value="purple">Purple (Ungu Edukatif)</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <!-- Ketua -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Nama Ketua / Koordinator</label>
              <input 
                type="text" 
                v-model="form.ketua" 
                placeholder="Nama lengkap pimpinan lembaga" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <!-- Kontak -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Kontak / No. Telepon / Email</label>
              <input 
                type="text" 
                v-model="form.kontak" 
                placeholder="No HP / WA / Email sekretariat" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <!-- Alamat Sekretariat -->
            <div class="sm:col-span-2">
              <label class="block font-bold text-slate-700 mb-1">Alamat Sekretariat</label>
              <input 
                type="text" 
                v-model="form.alamat" 
                placeholder="Gedung / Ruangan / Balai Kelurahan" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <!-- Jumlah Anggota -->
            <div>
              <label class="block font-bold text-slate-700 mb-1">Jumlah Anggota / Kader</label>
              <input 
                type="text" 
                v-model="form.jumlah_anggota" 
                placeholder="Contoh: 30 Kader Aktif" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <!-- Deskripsi Lembaga -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Deskripsi Tugas & Peran Lembaga *</label>
            <textarea 
              v-model="form.deskripsi" 
              required 
              rows="3" 
              placeholder="Jelaskan peran lembaga dalam membantu pemerintahan kelurahan dan masyarakat..." 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none resize-none leading-relaxed"
            ></textarea>
          </div>

          <!-- Program Kerja Dinamis -->
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
            <div class="flex items-center justify-between">
              <div>
                <label class="block font-bold text-slate-800 text-xs">Butir Program Kerja & Agenda Pokok</label>
                <p class="text-[11px] text-slate-500">Daftar kegiatan unggulan atau program strategis lembaga ini.</p>
              </div>
              <button 
                type="button" 
                @click="addProgramKerja" 
                class="px-2.5 py-1 rounded-lg bg-emerald-100 hover:bg-emerald-200 text-emerald-800 text-xs font-bold transition flex items-center gap-1"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Butir
              </button>
            </div>

            <div class="space-y-2">
              <div 
                v-for="(prog, idx) in form.program_kerja" 
                :key="idx" 
                class="flex items-center gap-2"
              >
                <span class="w-5 text-center text-slate-400 font-mono text-xs font-bold">{{ idx + 1 }}.</span>
                <input 
                  type="text" 
                  v-model="form.program_kerja[idx]" 
                  placeholder="Contoh: Penyelenggaraan posyandu balita dan senam lansia rutin bulanan" 
                  class="flex-1 px-3 py-1.5 rounded-xl border border-slate-200 bg-white text-xs focus:ring-2 focus:ring-emerald-600 outline-none"
                />
                <button 
                  type="button" 
                  @click="removeProgramKerja(idx)" 
                  class="p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50"
                  title="Hapus butir ini"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>

              <p v-if="!form.program_kerja.length" class="text-xs text-slate-400 italic text-center py-2">
                Belum ada butir program kerja. Klik "Tambah Butir" untuk menambahkan.
              </p>
            </div>
          </div>

          <!-- Urutan & Aktif -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1 items-center">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Nomor Urut Tampilan</label>
              <input 
                type="number" 
                v-model.number="form.urutan" 
                min="0" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono"
              />
            </div>

            <div class="flex items-center gap-2 sm:pt-6">
              <input 
                type="checkbox" 
                id="check_aktif" 
                v-model="form.aktif" 
                class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
              />
              <label for="check_aktif" class="font-bold text-slate-700 cursor-pointer select-none">
                Publikasikan ke Website (Status Aktif)
              </label>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition"
            >
              Batal
            </button>
            <button 
              type="submit" 
              :disabled="saving" 
              class="px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold transition shadow-sm flex items-center gap-1.5"
            >
              <span v-if="saving">Menyimpan...</span>
              <span v-else>{{ editId ? 'Simpan Perubahan' : 'Tambahkan Lembaga' }}</span>
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
const successMsg = ref('');
const lembagaList = ref([]);
const kategoriOptions = ref([]);
const searchQuery = ref('');
const showModal = ref(false);
const editId = ref(null);

const form = reactive({
  nama: '',
  singkatan: '',
  kategori: '',
  warna_tema: 'emerald',
  ketua: '',
  kontak: '',
  alamat: '',
  jumlah_anggota: '',
  deskripsi: '',
  program_kerja: [],
  urutan: 0,
  aktif: true
});

const activeLembagaCount = computed(() => {
  return lembagaList.value.filter(l => l.aktif).length;
});

const filteredLembaga = computed(() => {
  if (!searchQuery.value) return lembagaList.value;
  const q = searchQuery.value.toLowerCase();
  return lembagaList.value.filter(l => 
    (l.nama && l.nama.toLowerCase().includes(q)) ||
    (l.singkatan && l.singkatan.toLowerCase().includes(q)) ||
    (l.kategori && l.kategori.toLowerCase().includes(q)) ||
    (l.ketua && l.ketua.toLowerCase().includes(q))
  );
});

const getThemeClasses = (theme) => {
  switch (theme) {
    case 'amber':
      return {
        badge: 'bg-amber-100 text-amber-900 border border-amber-300/80',
        pill: 'bg-amber-100 text-amber-800'
      };
    case 'rose':
      return {
        badge: 'bg-rose-100 text-rose-900 border border-rose-300/80',
        pill: 'bg-rose-100 text-rose-800'
      };
    case 'blue':
      return {
        badge: 'bg-blue-100 text-blue-900 border border-blue-300/80',
        pill: 'bg-blue-100 text-blue-800'
      };
    case 'indigo':
      return {
        badge: 'bg-indigo-100 text-indigo-900 border border-indigo-300/80',
        pill: 'bg-indigo-100 text-indigo-800'
      };
    case 'purple':
      return {
        badge: 'bg-purple-100 text-purple-900 border border-purple-300/80',
        pill: 'bg-purple-100 text-purple-800'
      };
    case 'emerald':
    default:
      return {
        badge: 'bg-emerald-100 text-emerald-900 border border-emerald-300/80',
        pill: 'bg-emerald-100 text-emerald-800'
      };
  }
};

const addProgramKerja = () => {
  form.program_kerja.push('');
};

const removeProgramKerja = (index) => {
  form.program_kerja.splice(index, 1);
};

const loadData = async () => {
  loading.value = true;
  try {
    const [data, kats] = await Promise.all([
      AdminService.getLembaga(),
      AdminService.getMasterKategori('lembaga')
    ]);
    lembagaList.value = data || [];
    kategoriOptions.value = kats || [];
  } catch (err) {
    console.error('Gagal mengambil data lembaga:', err);
  } finally {
    loading.value = false;
  }
};

const openModal = (item = null) => {
  if (item) {
    editId.value = item.id;
    form.nama = item.nama;
    form.singkatan = item.singkatan || '';
    form.kategori = item.kategori || '';
    form.warna_tema = item.warna_tema || 'emerald';
    form.ketua = item.ketua || '';
    form.kontak = item.kontak || '';
    form.alamat = item.alamat || '';
    form.jumlah_anggota = item.jumlah_anggota || '';
    form.deskripsi = item.deskripsi || '';
    form.program_kerja = Array.isArray(item.program_kerja) ? [...item.program_kerja] : [];
    form.urutan = item.urutan ?? 0;
    form.aktif = !!item.aktif;
  } else {
    editId.value = null;
    form.nama = '';
    form.singkatan = '';
    form.kategori = kategoriOptions.value.length > 0 ? kategoriOptions.value[0].nama : '';
    form.warna_tema = 'emerald';
    form.ketua = '';
    form.kontak = '';
    form.alamat = '';
    form.jumlah_anggota = '';
    form.deskripsi = '';
    form.program_kerja = [''];
    form.urutan = lembagaList.value.length + 1;
    form.aktif = true;
  }
  showModal.value = true;
};

const saveItem = async () => {
  saving.value = true;
  try {
    // Filter empty program kerja items
    const cleanProgramKerja = (form.program_kerja || []).map(p => p.trim()).filter(Boolean);
    const payload = {
      nama: form.nama,
      singkatan: form.singkatan,
      kategori: form.kategori,
      warna_tema: form.warna_tema,
      ketua: form.ketua,
      kontak: form.kontak,
      alamat: form.alamat,
      jumlah_anggota: form.jumlah_anggota,
      deskripsi: form.deskripsi,
      program_kerja: cleanProgramKerja,
      urutan: form.urutan,
      aktif: form.aktif
    };

    const res = await AdminService.saveLembaga(payload, editId.value);
    successMsg.value = res?.message || 'Data lembaga kemasyarakatan berhasil disimpan!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan data lembaga: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const toggleAktif = async (item) => {
  const newState = !item.aktif;
  try {
    await AdminService.toggleAktifLembaga(item.id, newState);
    item.aktif = newState;
    successMsg.value = `Status ${item.nama} berhasil diubah menjadi ${newState ? 'Aktif' : 'Nonaktif'}.`;
  } catch (err) {
    alert('Gagal mengubah status lembaga: ' + (err.response?.data?.message || err.message));
  }
};

const deleteItem = async (item) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus "${item.nama}"? Data yang dihapus tidak dapat dikembalikan.`)) {
    return;
  }
  try {
    await AdminService.deleteLembaga(item.id);
    successMsg.value = `Lembaga "${item.nama}" berhasil dihapus.`;
    await loadData();
  } catch (err) {
    alert('Gagal menghapus data lembaga: ' + (err.response?.data?.message || err.message));
  }
};

onMounted(() => {
  loadData();
});
</script>
