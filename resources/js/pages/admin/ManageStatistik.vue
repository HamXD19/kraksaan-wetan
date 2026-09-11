<template>
  <div class="space-y-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <h2 class="text-xl font-bold text-slate-900">Kelola Statistik Kependudukan & Wilayah</h2>
      <p class="text-xs text-slate-500">Perbarui agregat data demografi warga dan sebaran penduduk per Rukun Warga (RW).</p>
    </div>

    <!-- Alert Sukses -->
    <div v-if="successMsg" class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs font-semibold flex items-center justify-between">
      <span>{{ successMsg }}</span>
      <button @click="successMsg = ''" class="text-emerald-700">&times;</button>
    </div>

    <LoadingSpinner v-if="loading" />
    <template v-else>
      <!-- Form Angka Agregat Statistik -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs">
        <h3 class="text-base font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
          Angka Demografi Terkini
        </h3>

        <form @submit.prevent="saveStatistik" class="space-y-4 text-xs sm:text-sm">
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Total Penduduk (Jiwa) *</label>
              <input type="number" v-model.number="statForm.penduduk" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Total KK *</label>
              <input type="number" v-model.number="statForm.kk" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Laki-Laki</label>
              <input type="number" v-model.number="statForm.laki_laki" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Perempuan</label>
              <input type="number" v-model.number="statForm.perempuan" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono" />
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Jumlah RW</label>
              <input type="number" v-model.number="statForm.rw" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Jumlah RT</label>
              <input type="number" v-model.number="statForm.rt" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Luas Wilayah (km²)</label>
              <input type="text" v-model="statForm.luas_wilayah" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none font-mono" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Kepadatan Penduduk</label>
              <input type="text" v-model="statForm.kepadatan" placeholder="3,718 jiwa/km²" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-600 outline-none" />
            </div>
          </div>

          <div class="text-right pt-2">
            <button type="submit" :disabled="saving" class="px-6 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 disabled:opacity-50 text-white font-bold">
              <span v-if="saving">Menyimpan...</span>
              <span v-else>Simpan Perubahan Statistik</span>
            </button>
          </div>
        </form>
      </div>

      <!-- Tabel Sebaran RW Lingkungan -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xs">
        <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
          <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
            Sebaran Lingkungan Rukun Warga (RW)
          </h3>
          <button @click="openLingkunganModal()" class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-800 font-bold text-xs hover:bg-emerald-100">
            + Tambah RW
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-700 font-bold border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">Nama Lingkungan / RW</th>
                <th class="py-3 px-4 text-center">Jumlah RT</th>
                <th class="py-3 px-4 text-center">Estimasi Penduduk</th>
                <th class="py-3 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
              <tr v-for="l in lingkunganList" :key="l.id" class="hover:bg-slate-50">
                <td class="py-3 px-4 font-bold text-slate-900">{{ l.nama }}</td>
                <td class="py-3 px-4 text-center font-semibold text-emerald-700">{{ l.rt }} RT</td>
                <td class="py-3 px-4 text-center font-mono font-semibold">{{ (l.penduduk || 0).toLocaleString('id-ID') }} Jiwa</td>
                <td class="py-3 px-4 text-right whitespace-nowrap">
                  <button @click="openLingkunganModal(l)" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 font-semibold mr-1">Edit</button>
                  <button @click="deleteLingkungan(l.id)" class="px-2.5 py-1 rounded bg-rose-50 text-rose-700 hover:bg-rose-100 font-semibold">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>

    <!-- Modal Lingkungan -->
    <div v-if="showLingkunganModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-xs" @click.self="showLingkunganModal = false">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200">
        <h4 class="text-base font-bold text-slate-900 mb-4">{{ lingkunganEditId ? 'Edit RW' : 'Tambah RW' }}</h4>
        <form @submit.prevent="saveLingkungan" class="space-y-3 text-xs sm:text-sm">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Nama Lingkungan / RW *</label>
            <input type="text" v-model="lingkunganForm.nama" required placeholder="Contoh: RW 08 Kauman Baru" class="w-full px-3 py-2 rounded-xl border border-slate-200" />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Jumlah RT *</label>
              <input type="number" v-model.number="lingkunganForm.rt" required class="w-full px-3 py-2 rounded-xl border border-slate-200" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Estimasi Penduduk *</label>
              <input type="number" v-model.number="lingkunganForm.penduduk" required class="w-full px-3 py-2 rounded-xl border border-slate-200" />
            </div>
          </div>
          <div class="flex justify-end gap-2 pt-3">
            <button type="button" @click="showLingkunganModal = false" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-600 font-semibold">Batal</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-700 text-white font-bold">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import { AdminService, KelurahanService } from '../../services/api';

const loading = ref(true);
const saving = ref(false);
const successMsg = ref('');
const lingkunganList = ref([]);
const showLingkunganModal = ref(false);
const lingkunganEditId = ref(null);

const statForm = reactive({
  penduduk: 6842,
  kk: 2185,
  laki_laki: 3390,
  perempuan: 3452,
  rt: 28,
  rw: 7,
  luas_wilayah: '1.84',
  kepadatan: '3,718 jiwa/km²'
});

const lingkunganForm = reactive({
  nama: '',
  rt: 4,
  penduduk: 950
});

const loadData = async () => {
  loading.value = true;
  try {
    const data = await KelurahanService.getStatistik();
    statForm.penduduk = data.penduduk || 0;
    statForm.kk = data.kk || 0;
    statForm.laki_laki = data.laki_laki || 0;
    statForm.perempuan = data.perempuan || 0;
    statForm.rt = data.rt || 0;
    statForm.rw = data.rw || 0;
    statForm.luas_wilayah = data.luas_wilayah || '1.84';
    statForm.kepadatan = data.kepadatan || '';
    lingkunganList.value = data.lingkungan || [];
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

const saveStatistik = async () => {
  saving.value = true;
  try {
    const res = await AdminService.updateStatistik(statForm);
    successMsg.value = res.message || 'Statistik berhasil diperbarui!';
  } catch (err) {
    alert('Gagal menyimpan statistik: ' + (err.response?.data?.message || err.message));
  } finally {
    saving.value = false;
  }
};

const openLingkunganModal = (item = null) => {
  if (item) {
    lingkunganEditId.value = item.id;
    lingkunganForm.nama = item.nama;
    lingkunganForm.rt = item.rt;
    lingkunganForm.penduduk = item.penduduk;
  } else {
    lingkunganEditId.value = null;
    lingkunganForm.nama = '';
    lingkunganForm.rt = 4;
    lingkunganForm.penduduk = 800;
  }
  showLingkunganModal.value = true;
};

const saveLingkungan = async () => {
  try {
    await AdminService.saveLingkungan(lingkunganForm, lingkunganEditId.value);
    showLingkunganModal.value = false;
    successMsg.value = 'Data lingkungan RW berhasil disimpan!';
    await loadData();
  } catch (err) {
    alert('Gagal menyimpan RW.');
  }
};

const deleteLingkungan = async (id) => {
  if (!confirm('Hapus lingkungan RW ini?')) return;
  try {
    await AdminService.deleteLingkungan(id);
    successMsg.value = 'Lingkungan RW berhasil dihapus.';
    await loadData();
  } catch (err) {
    alert('Gagal menghapus RW.');
  }
};

onMounted(loadData);
</script>
