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
                <div v-if="l.slug" class="mt-1">
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-mono bg-slate-100 text-slate-600 border border-slate-200" title="Slug URL Layanan">
                    slug: {{ l.slug }}
                  </span>
                </div>
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
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/60 backdrop-blur-xs"
    >
      <div class="bg-white rounded-2xl sm:rounded-3xl max-w-5xl w-full shadow-2xl border border-slate-200 flex flex-col max-h-[92vh] overflow-hidden">
        <!-- Sticky Header -->
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0 bg-white">
          <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900">
              {{ editId ? 'Sunting Layanan Publik' : 'Tambah Layanan Baru' }}
            </h3>
            <p class="text-xs text-slate-500">Kelola standar operasional prosedur (SOP), syarat berkas, dan alur permohonan.</p>
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
              <!-- Left Column: Primary Content (Nama, Deskripsi & Alur) -->
              <div class="lg:col-span-7 space-y-4">
                <div>
                  <label class="block font-bold text-slate-700 mb-1">Nama Layanan *</label>
                  <input 
                    type="text" 
                    v-model="form.judul" 
                    required 
                    placeholder="Contoh: Surat Pengantar SKCK" 
                    class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-medium"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1">Deskripsi Singkat *</label>
                  <RichTextEditor 
                    v-model="form.deskripsi" 
                    placeholder="Deskripsi peruntukan layanan..." 
                    height="140px"
                    maxHeight="220px"
                  />
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1">Alur Prosedur Pengurusan</label>
                  <RichTextEditor 
                    v-model="form.alur" 
                    placeholder="Langkah pengajuan hingga pengambilan dokumen..." 
                    height="160px"
                    maxHeight="240px"
                  />
                </div>
              </div>

              <!-- Right Column: Meta, Waktu, Biaya, Persyaratan -->
              <div class="lg:col-span-5 space-y-3.5 bg-slate-50/70 p-4 rounded-2xl border border-slate-200/80">
                <div>
                  <div class="flex items-center justify-between mb-1">
                    <label class="block font-bold text-slate-700 text-xs">Kategori Layanan *</label>
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
                  <label class="block font-bold text-slate-700 mb-1 text-xs">Ikon Tipe Layanan</label>
                  <select 
                    v-model="form.icon" 
                    class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                  >
                    <option value="Users">Users (Kependudukan)</option>
                    <option value="Home">Home (Domisili)</option>
                    <option value="Briefcase">Briefcase (Usaha / SKU)</option>
                    <option value="HeartHandshake">HeartHandshake (SKTM/Bansos)</option>
                    <option value="ShieldCheck">ShieldCheck (SKCK)</option>
                    <option value="FileText">FileText (Umum)</option>
                  </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                  <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Estimasi Waktu</label>
                    <input 
                      type="text" 
                      v-model="form.waktu" 
                      placeholder="10 - 15 Menit" 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    />
                  </div>

                  <div>
                    <label class="block font-bold text-slate-700 mb-1 text-xs">Biaya / Tarif</label>
                    <input 
                      type="text" 
                      v-model="form.biaya" 
                      placeholder="Gratis (Rp 0)" 
                      class="w-full px-3 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none bg-white text-xs"
                    />
                  </div>
                </div>

                <div>
                  <label class="block font-bold text-slate-700 mb-1 text-xs">Persyaratan Berkas (1 baris per syarat)</label>
                  <textarea 
                    rows="4" 
                    v-model="persyaratanText" 
                    placeholder="Surat pengantar RT dan RW&#10;Fotokopi KTP dan KK&#10;Pas foto 3x4" 
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
                <span v-else>{{ editId ? 'Simpan Perubahan' : 'Simpan Layanan' }}</span>
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
const layananList = ref([]);
const showModal = ref(false);
const editId = ref(null);
const successMsg = ref('');
const persyaratanText = ref('');
const kategoriOptions = ref([]);
const isCustomKategori = ref(false);

const form = reactive({
  judul: '',
  kategori: 'Kependudukan',
  customKategori: '',
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
  isCustomKategori.value = false;
  form.customKategori = '';
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
    form.kategori = (kategoriOptions.value[0]?.nama || kategoriOptions.value[0]) || 'Kependudukan';
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
    const payload = { ...form };
    if (isCustomKategori.value && form.customKategori?.trim()) {
      payload.kategori = form.customKategori.trim();
    }
    const res = await AdminService.saveLayanan(payload, editId.value);
    const msg = res.message || 'Layanan berhasil disimpan!';
    successMsg.value = msg;
    toast.success(msg, editId.value ? 'Layanan Diperbarui' : 'Layanan Ditambahkan');
    showModal.value = false;
    await loadData();
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal menyimpan layanan.';
    toast.error('Gagal menyimpan layanan: ' + errMsg);
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (!confirm('Hapus layanan ini dari daftar?')) return;
  try {
    await AdminService.deleteLayanan(id);
    const msg = 'Layanan berhasil dihapus.';
    successMsg.value = msg;
    toast.success(msg, 'Layanan Dihapus');
    await loadData();
  } catch (err) {
    toast.error('Gagal menghapus layanan.');
  }
};

const toggleAktifLayanan = async (item) => {
  const newStatus = !item.aktif;
  try {
    await AdminService.toggleAktifLayanan(item.id, newStatus);
    item.aktif = newStatus;
    const msg = `Status aktif layanan "${item.judul}" berhasil diubah menjadi ${newStatus ? 'Aktif' : 'Nonaktif'}.`;
    successMsg.value = msg;
    toast.success(msg, 'Status Layanan Diperbarui');
  } catch (err) {
    const errMsg = err.response?.data?.message || err.message || 'Gagal mengubah status aktif layanan.';
    toast.error('Gagal mengubah status aktif layanan: ' + errMsg);
  }
};

onMounted(loadData);
</script>
