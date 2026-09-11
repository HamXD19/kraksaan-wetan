<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Layanan Masyarakat</h2>
        <p class="text-xs text-slate-500">Atur jenis permohonan surat, dokumen kependudukan, alur, dan persyaratan berkas.</p>
      </div>

      <button 
        @click="openModal()" 
        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-sm transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Layanan
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
              <th class="py-3.5 px-4">Nama Layanan</th>
              <th class="py-3.5 px-4">Kategori</th>
              <th class="py-3.5 px-4">Biaya</th>
              <th class="py-3.5 px-4">Waktu</th>
              <th class="py-3.5 px-4 text-center">Status Online</th>
              <th class="py-3.5 px-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-slate-600">
            <tr v-for="l in layananList" :key="l.id" class="hover:bg-slate-50">
              <td class="py-3 px-4 max-w-sm">
                <p class="font-bold text-slate-900">{{ l.judul }}</p>
                <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5">{{ l.deskripsi }}</p>
              </td>
              <td class="py-3 px-4">
                <span class="px-2.5 py-1 rounded-md bg-slate-100 font-semibold text-[11px]">
                  {{ l.kategori }}
                </span>
              </td>
              <td class="py-3 px-4 text-emerald-700 font-semibold">{{ l.biaya }}</td>
              <td class="py-3 px-4">{{ l.waktu }}</td>
              <td class="py-3 px-4 text-center whitespace-nowrap">
                <button 
                  @click="toggleAktifLayanan(l)" 
                  class="px-2.5 py-1 rounded-full text-[11px] font-bold transition inline-flex items-center gap-1.5 cursor-pointer"
                  :class="l.aktif ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200'"
                  :title="l.aktif ? 'Layanan aktif menerima pengajuan online. Klik untuk menonaktifkan.' : 'Layanan dinonaktifkan. Klik untuk mengaktifkan.'"
                >
                  <span class="w-1.5 h-1.5 rounded-full" :class="l.aktif ? 'bg-emerald-600' : 'bg-slate-400'"></span>
                  <span>{{ l.aktif ? 'Aktif' : 'Nonaktif' }}</span>
                </button>
              </td>
              <td class="py-3 px-4 text-right whitespace-nowrap">
                <button 
                  @click="openModal(l)" 
                  class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold mr-1.5"
                >
                  Edit
                </button>
                <button 
                  @click="deleteItem(l.id)" 
                  class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold"
                >
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="!layananList.length">
              <td colspan="6" class="py-8 text-center text-slate-400">Belum ada layanan terdaftar.</td>
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
          {{ editId ? 'Sunting Layanan' : 'Tambah Layanan Baru' }}
        </h3>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs sm:text-sm">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Nama Layanan *</label>
              <input 
                type="text" 
                v-model="form.judul" 
                required 
                placeholder="Contoh: Surat Pengantar SKCK" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Kategori Layanan *</label>
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
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Ikon Tipe</label>
              <select 
                v-model="form.icon" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              >
                <option value="Users">Users (Kependudukan)</option>
                <option value="Home">Home (Domisili)</option>
                <option value="Briefcase">Briefcase (Usaha / SKU)</option>
                <option value="HeartHandshake">HeartHandshake (SKTM/Bansos)</option>
                <option value="ShieldCheck">ShieldCheck (SKCK)</option>
                <option value="FileText">FileText (Umum)</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Estimasi Waktu</label>
              <input 
                type="text" 
                v-model="form.waktu" 
                placeholder="10 - 15 Menit" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Biaya Layanan</label>
              <input 
                type="text" 
                v-model="form.biaya" 
                placeholder="Gratis (Rp 0)" 
                class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat *</label>
            <textarea 
              rows="2" 
              v-model="form.deskripsi" 
              required 
              placeholder="Deskripsi peruntukan layanan..." 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            ></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Persyaratan Berkas (1 baris per syarat)</label>
            <textarea 
              rows="4" 
              v-model="persyaratanText" 
              placeholder="Surat pengantar RT dan RW&#10;Fotokopi KTP dan KK&#10;Pas foto 3x4" 
              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none"
            ></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Alur Prosedur Pengurusan</label>
            <textarea 
              rows="2" 
              v-model="form.alur" 
              placeholder="Langkah pengajuan hingga pengambilan dokumen..." 
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
              <span v-else>Simpan Layanan</span>
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
const layananList = ref([]);
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');
const persyaratanText = ref('');
const kategoriOptions = ref([]);

const form = reactive({
  judul: '',
  kategori: 'Kependudukan',
  icon: 'FileText',
  deskripsi: '',
  persyaratan: [],
  alur: '',
  waktu: '10 - 15 Menit',
  biaya: 'Gratis (Rp 0)'
});

const loadData = async () => {
  loading.value = true;
  try {
    const [data, kats] = await Promise.all([
      AdminService.getLayanan(),
      AdminService.getMasterKategori('layanan')
    ]);
    layananList.value = data || [];
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
    form.icon = item.icon || 'FileText';
    form.deskripsi = item.deskripsi;
    form.alur = item.alur || '';
    form.waktu = item.waktu || '10 - 15 Menit';
    form.biaya = item.biaya || 'Gratis (Rp 0)';
    persyaratanText.value = (item.persyaratan || []).join('\n');
  } else {
    editId.value = null;
    form.judul = '';
    form.kategori = 'Kependudukan';
    form.icon = 'FileText';
    form.deskripsi = '';
    form.alur = '';
    form.waktu = '10 - 15 Menit';
    form.biaya = 'Gratis (Rp 0)';
    persyaratanText.value = '';
  }
  showModal.value = true;
};

const saveItem = async () => {
  saving.value = true;
  form.persyaratan = persyaratanText.value
    .split('\n')
    .map(s => s.trim())
    .filter(s => s.length > 0);

  try {
    const res = await AdminService.saveLayanan(form, editId.value);
    successMsg.value = res.message || 'Layanan berhasil disimpan!';
    showModal.value = false;
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan layanan: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Hapus layanan ini dari daftar?')) return;
  try {
    await AdminService.deleteLayanan(id);
    successMsg.value = 'Layanan berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus layanan.');
  }
};

const toggleAktifLayanan = async (item) => {
  const newStatus = !item.aktif;
  try {
    await AdminService.toggleAktifLayanan(item.id, newStatus);
    item.aktif = newStatus;
    successMsg.value = `Status aktif layanan "${item.judul}" berhasil diubah menjadi ${newStatus ? 'Aktif' : 'Nonaktif'}.`;
    setTimeout(() => {
      successMsg.value = '';
    }, 3000);
  } catch (err) {
    alert('Gagal mengubah status aktif layanan: ' + (err.response?.data?.message || err.message));
  }
};

onMounted(loadData);
</script>
