<template>
  <div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Master Kategori Terpadu</h2>
        <p class="text-xs text-slate-500">Kelola master kategori data untuk modul Berita, Pengumuman, Layanan, Galeri, Lembaga, dan Transparansi Anggaran.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition cursor-pointer"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Kategori
      </button>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700 font-bold">&times;</button>
    </div>

    <!-- Modul Filter Tabs -->
    <div class="bg-white p-2 rounded-2xl border border-slate-200 shadow-xs flex flex-wrap gap-1.5">
      <button 
        v-for="tab in moduleTabs" 
        :key="tab.id"
        @click="activeModuleTab = tab.id"
        class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2"
        :class="activeModuleTab === tab.id ? 'bg-emerald-700 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'"
      >
        <span>{{ tab.label }}</span>
        <span 
          class="px-2 py-0.5 rounded-full text-[10px]"
          :class="activeModuleTab === tab.id ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-200 text-slate-700'"
        >
          {{ getModuleCount(tab.id) }}
        </span>
      </button>
    </div>

    <!-- Data Table -->
    <LoadingSpinner v-if="loading" />
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
            <tr>
              <th class="py-3.5 px-4">Urutan</th>
              <th class="py-3.5 px-4">Modul</th>
              <th class="py-3.5 px-4">Nama Kategori</th>
              <th class="py-3.5 px-4">Slug / Identifikasi</th>
              <th class="py-3.5 px-4">Keterangan</th>
              <th class="py-3.5 px-4 text-center">Status</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-for="kat in filteredKategoris" :key="kat.id" class="hover:bg-slate-50">
              <td class="py-3 px-4 font-mono font-semibold text-slate-400">#{{ kat.urutan || 0 }}</td>
              <td class="py-3 px-4">
                <span 
                  class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider"
                  :class="getModulBadgeClass(kat.modul)"
                >
                  {{ kat.modul }}
                </span>
              </td>
              <td class="py-3 px-4">
                <div class="flex items-center gap-2">
                  <span class="w-3 h-3 rounded-full shrink-0" :class="getColorCircleClass(kat.warna)"></span>
                  <span class="font-bold text-slate-900 text-xs sm:text-sm">{{ kat.nama }}</span>
                </div>
              </td>
              <td class="py-3 px-4 font-mono text-[11px] text-slate-500">{{ kat.slug }}</td>
              <td class="py-3 px-4 max-w-xs text-slate-500 line-clamp-1 mt-3">{{ kat.keterangan || '-' }}</td>
              <td class="py-3 px-4 text-center whitespace-nowrap">
                <span 
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold"
                  :class="kat.is_aktif ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500'"
                >
                  {{ kat.is_aktif ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <button 
                  @click="openModal(kat)" 
                  class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5 cursor-pointer"
                >
                  Edit
                </button>
                <button 
                  @click="deleteItem(kat.id)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold cursor-pointer"
                >
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="!filteredKategoris.length">
              <td colspan="7" class="py-8 text-center text-slate-400">Belum ada kategori terdaftar pada modul ini.</td>
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
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200">
        <h3 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
          {{ editId ? 'Sunting Master Kategori' : 'Tambah Master Kategori Baru' }}
        </h3>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Modul Induk *</label>
            <select 
              v-model="form.modul" 
              required 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white font-semibold"
            >
              <option value="berita">Berita Kelurahan</option>
              <option value="pengumuman">Pengumuman Kedinasan</option>
              <option value="layanan">Layanan Masyarakat (SOP)</option>
              <option value="galeri">Galeri Foto & Dokumentasi</option>
              <option value="lembaga">Lembaga Kemasyarakatan (LKK)</option>
              <option value="transparansi">Transparansi Anggaran</option>
            </select>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Nama Kategori *</label>
            <input 
              type="text" 
              v-model="form.nama" 
              required 
              placeholder="Contoh: Kependudukan, Infrastruktur, dll." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            />
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Warna Label</label>
              <select 
                v-model="form.warna" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white"
              >
                <option value="emerald">Emerald (Hijau Kelurahan)</option>
                <option value="blue">Blue (Biru Informasi)</option>
                <option value="amber">Amber (Kuning Keemasan)</option>
                <option value="rose">Rose (Merah Muda)</option>
                <option value="purple">Purple (Ungu Lembaga)</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Urutan Prioritas</label>
              <input 
                type="number" 
                v-model="form.urutan" 
                min="0"
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Deskripsi / Keterangan (Opsional)</label>
            <textarea 
              rows="3" 
              v-model="form.keterangan" 
              placeholder="Penjelasan ringkas peruntukan kategori..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            ></textarea>
          </div>

          <div class="flex items-center gap-2 pt-2">
            <input 
              type="checkbox" 
              id="is_aktif" 
              v-model="form.is_aktif" 
              class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500"
            />
            <label for="is_aktif" class="font-bold text-slate-700 cursor-pointer">Status Kategori Aktif</label>
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
              <span v-else>Simpan Kategori</span>
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
const kategoris = ref([]);
const activeModuleTab = ref('semua');
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');

const moduleTabs = [
  { id: 'semua', label: 'Semua Kategori' },
  { id: 'berita', label: 'Berita' },
  { id: 'pengumuman', label: 'Pengumuman' },
  { id: 'layanan', label: 'Layanan (SOP)' },
  { id: 'galeri', label: 'Galeri Foto' },
  { id: 'lembaga', label: 'Lembaga' },
  { id: 'transparansi', label: 'Transparansi' },
];

const form = reactive({
  modul: 'berita',
  nama: '',
  keterangan: '',
  warna: 'emerald',
  urutan: 1,
  is_aktif: true,
});

const loadData = async () => {
  loading.value = true;
  try {
    kategoris.value = await AdminService.getMasterKategori();
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const filteredKategoris = computed(() => {
  if (activeModuleTab.value === 'semua') {
    return kategoris.value;
  }
  return kategoris.value.filter(k => k.modul === activeModuleTab.value);
});

function getModuleCount(modulId) {
  if (modulId === 'semua') return kategoris.value.length;
  return kategoris.value.filter(k => k.modul === modulId).length;
}

function getModulBadgeClass(modul) {
  switch (modul) {
    case 'berita': return 'bg-blue-100 text-blue-800';
    case 'pengumuman': return 'bg-amber-100 text-amber-800';
    case 'layanan': return 'bg-emerald-100 text-emerald-800';
    case 'galeri': return 'bg-purple-100 text-purple-800';
    case 'lembaga': return 'bg-indigo-100 text-indigo-800';
    case 'transparansi': return 'bg-rose-100 text-rose-800';
    default: return 'bg-slate-100 text-slate-800';
  }
}

function getColorCircleClass(warna) {
  switch (warna) {
    case 'emerald': return 'bg-emerald-500';
    case 'blue': return 'bg-blue-500';
    case 'amber': return 'bg-amber-500';
    case 'rose': return 'bg-rose-500';
    case 'purple': return 'bg-purple-500';
    default: return 'bg-emerald-500';
  }
}

function openModal(item = null) {
  if (item) {
    editId.value = item.id;
    form.modul = item.modul;
    form.nama = item.nama;
    form.keterangan = item.keterangan || '';
    form.warna = item.warna || 'emerald';
    form.urutan = item.urutan || 1;
    form.is_aktif = item.is_aktif !== undefined ? Boolean(item.is_aktif) : true;
  } else {
    editId.value = null;
    form.modul = activeModuleTab.value !== 'semua' ? activeModuleTab.value : 'berita';
    form.nama = '';
    form.keterangan = '';
    form.warna = 'emerald';
    form.urutan = (kategoris.value.filter(k => k.modul === form.modul).length + 1);
    form.is_aktif = true;
  }
  showModal.value = true;
}

async function saveItem() {
  saving.value = true;
  try {
    let res;
    if (editId.value) {
      res = await AdminService.updateMasterKategori(editId.value, form);
    } else {
      res = await AdminService.storeMasterKategori(form);
    }
    successMsg.value = res.message || 'Master kategori berhasil disimpan!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan master kategori: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
}

async function deleteItem(id) {
  if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) return;
  try {
    await AdminService.deleteMasterKategori(id);
    successMsg.value = 'Kategori berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus kategori: ' + (err.response?.data?.message || err.message));
  }
}

onMounted(loadData);
</script>
