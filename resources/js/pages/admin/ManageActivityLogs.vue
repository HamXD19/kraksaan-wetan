<template>
  <div class="space-y-6">
    <!-- Header Page -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-purple-100 text-purple-800 text-[11px] font-bold uppercase tracking-wider mb-2">
          <span class="w-2 h-2 rounded-full bg-purple-600"></span>
          Super Admin Audit Trail
        </div>
        <h2 class="text-xl font-bold text-slate-900">Pemantauan Log Aktifitas Akun</h2>
        <p class="text-xs text-slate-500 mt-0.5">Pantau seluruh rekam jejak aktifitas, riwayat login, serta modifikasi data oleh setiap akun staf kelurahan.</p>
      </div>

      <div class="flex items-center gap-2 self-start sm:self-auto">
        <button 
          @click="loadLogs" 
          :disabled="loading"
          class="inline-flex items-center justify-center gap-2 px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition disabled:opacity-50"
          title="Segarkan Data Log"
        >
          <svg class="w-4 h-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          <span>Refresh</span>
        </button>
      </div>
    </div>

    <!-- 4 Metric Cards: Statistics Summary -->
    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3">
      <!-- Total Log -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total Riwayat Log</p>
        <p class="text-2xl font-black text-slate-900 mt-1">{{ summary.total_logs || 0 }}</p>
        <p class="text-[10px] text-slate-500 mt-0.5">Tercatat di sistem</p>
      </div>

      <!-- Aktifitas Hari Ini -->
      <div class="bg-white p-4 rounded-2xl border border-emerald-200 bg-emerald-50/30 shadow-xs">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-700">Aktifitas Hari Ini</p>
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        </div>
        <p class="text-2xl font-black text-emerald-900 mt-1">{{ summary.today_logs || 0 }}</p>
        <p class="text-[10px] text-emerald-600 mt-0.5">Aksi dilakukan hari ini</p>
      </div>

      <!-- Akun Aktif Hari Ini -->
      <div class="bg-white p-4 rounded-2xl border border-purple-200 bg-purple-50/30 shadow-xs">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-bold uppercase tracking-wider text-purple-700">Akun Aktif Hari Ini</p>
          <span class="w-2 h-2 rounded-full bg-purple-600"></span>
        </div>
        <p class="text-2xl font-black text-purple-900 mt-1">{{ summary.active_users_today || 0 }}</p>
        <p class="text-[10px] text-purple-600 mt-0.5">Pengguna beraktifitas</p>
      </div>

      <!-- Modul Teraktif -->
      <div class="bg-white p-4 rounded-2xl border border-blue-200 bg-blue-50/30 shadow-xs">
        <div class="flex items-center justify-between">
          <p class="text-[10px] font-bold uppercase tracking-wider text-blue-700">Modul Paling Aktif</p>
          <span class="w-2 h-2 rounded-full bg-blue-600"></span>
        </div>
        <p class="text-xl font-black text-blue-900 mt-1 capitalize truncate">{{ formatModuleName(summary.most_active_module) }}</p>
        <p class="text-[10px] text-blue-600 mt-0.5">Frekuensi aksi tertinggi</p>
      </div>
    </div>

    <!-- Filter & Search Toolbar -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <!-- Filter Akun / User -->
        <div>
          <label class="block text-[11px] font-bold text-slate-600 mb-1">Filter Akun Pengguna</label>
          <select 
            v-model="filters.user_id" 
            @change="applyFilter"
            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-hidden bg-slate-50/50"
          >
            <option value="semua">Semua Akun Staf</option>
            <option v-for="u in usersList" :key="u.id" :value="u.id">
              {{ u.name }} ({{ u.role_label || u.role }})
            </option>
          </select>
        </div>

        <!-- Filter Modul -->
        <div>
          <label class="block text-[11px] font-bold text-slate-600 mb-1">Filter Modul Sistem</label>
          <select 
            v-model="filters.module" 
            @change="applyFilter"
            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-hidden bg-slate-50/50"
          >
            <option value="semua">Semua Modul</option>
            <option value="auth">Autentikasi & Sesi</option>
            <option value="staff">Manajemen Akun Staf</option>
            <option value="berita">Berita Kelurahan</option>
            <option value="pengumuman">Pengumuman</option>
            <option value="galeri">Galeri Foto & Video</option>
            <option value="layanan">Pelayanan Warga</option>
            <option value="lembaga">Lembaga Kemasyarakatan</option>
            <option value="statistik">Statistik Kependudukan</option>
            <option value="transparansi">Transparansi Anggaran</option>
            <option value="profil">Profil & Aparatur</option>
            <option value="kategori">Master Kategori</option>
            <option value="media">Media & Dokumen</option>
          </select>
        </div>

        <!-- Filter Aksi -->
        <div>
          <label class="block text-[11px] font-bold text-slate-600 mb-1">Jenis Aksi</label>
          <select 
            v-model="filters.action" 
            @change="applyFilter"
            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-hidden bg-slate-50/50"
          >
            <option value="semua">Semua Jenis Aksi</option>
            <option value="login">Login (Masuk)</option>
            <option value="logout">Logout (Keluar)</option>
            <option value="create">Tambah Data (Create)</option>
            <option value="update">Ubah Data (Update)</option>
            <option value="delete">Hapus Data (Delete)</option>
            <option value="reset_password">Reset Kata Sandi</option>
            <option value="upload">Unggah File (Upload)</option>
          </select>
        </div>

        <!-- Search Box -->
        <div>
          <label class="block text-[11px] font-bold text-slate-600 mb-1">Cari Keterangan / IP</label>
          <div class="relative">
            <input 
              v-model="filters.search" 
              @input="debounceSearch"
              type="text" 
              placeholder="Cari kata kunci, nama, IP..." 
              class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-purple-600 focus:outline-hidden bg-slate-50/50"
            />
            <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </div>
        </div>
      </div>

      <!-- Active Filters Pill & Reset -->
      <div v-if="hasActiveFilters" class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-100 text-xs">
        <div class="flex flex-wrap items-center gap-1.5">
          <span class="text-[11px] text-slate-500 font-medium">Filter aktif:</span>
          <span v-if="filters.user_id !== 'semua'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-purple-100 text-purple-800 text-[10px] font-bold">
            Akun: {{ getSelectedUserName() }}
          </span>
          <span v-if="filters.module !== 'semua'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 text-[10px] font-bold">
            Modul: {{ formatModuleName(filters.module) }}
          </span>
          <span v-if="filters.action !== 'semua'" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800 text-[10px] font-bold">
            Aksi: {{ formatActionName(filters.action) }}
          </span>
          <span v-if="filters.search" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-800 text-[10px] font-bold">
            "{{ filters.search }}"
          </span>
        </div>
        <button 
          @click="resetFilters" 
          class="text-xs text-rose-600 hover:text-rose-800 font-bold hover:underline"
        >
          Reset Semua Filter
        </button>
      </div>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="py-16 flex flex-col items-center justify-center space-y-3 bg-white rounded-3xl border border-slate-200">
      <div class="animate-spin rounded-full h-9 w-9 border-b-2 border-purple-700"></div>
      <p class="text-xs font-semibold text-slate-500">Memuat data rekam jejak aktifitas...</p>
    </div>

    <!-- Empty State -->
    <div v-else-if="logs.length === 0" class="bg-white p-12 rounded-3xl border border-slate-200 text-center space-y-3">
      <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 mx-auto flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>
      <h3 class="font-bold text-slate-900 text-sm">Tidak ada catatan log aktifitas</h3>
      <p class="text-xs text-slate-500">Belum ada riwayat aktifitas yang cocok dengan filter yang Anda tentukan.</p>
      <button 
        v-if="hasActiveFilters" 
        @click="resetFilters" 
        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-purple-100 hover:bg-purple-200 text-purple-800 font-bold text-xs transition"
      >
        Reset Filter
      </button>
    </div>

    <!-- Data Table -->
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs">
          <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200">
            <tr>
              <th class="px-5 py-3.5">Akun Pengguna</th>
              <th class="px-5 py-3.5">Aksi & Modul</th>
              <th class="px-5 py-3.5">Deskripsi Aktifitas</th>
              <th class="px-5 py-3.5">Alamat IP & Perangkat</th>
              <th class="px-5 py-3.5">Waktu Kejadian</th>
              <th class="px-5 py-3.5 text-right">Detail</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
            <tr v-for="log in logs" :key="log.id" class="hover:bg-slate-50/80 transition">
              <!-- Akun Pengguna -->
              <td class="px-5 py-3.5 whitespace-nowrap">
                <div class="flex items-center gap-2.5">
                  <div 
                    class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-[11px] text-white uppercase shadow-xs flex-shrink-0"
                    :class="getAvatarColor(log.user_role)"
                  >
                    {{ (log.user_name || 'U').charAt(0) }}
                  </div>
                  <div class="min-w-0 max-w-[160px]">
                    <p class="font-bold text-slate-900 truncate" :title="log.user_name">{{ log.user_name || 'Sistem' }}</p>
                    <div class="flex items-center gap-1">
                      <span 
                        class="px-1.5 py-0.2 rounded text-[9px] font-bold leading-tight"
                        :class="getRoleBadgeClass(log.user_role)"
                      >
                        {{ formatRoleName(log.user_role) }}
                      </span>
                    </div>
                  </div>
                </div>
              </td>

              <!-- Aksi & Modul -->
              <td class="px-5 py-3.5 whitespace-nowrap">
                <div class="flex flex-col gap-1 items-start">
                  <span 
                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold"
                    :class="getActionBadgeClass(log.action)"
                  >
                    <span class="w-1.5 h-1.5 rounded-full" :class="getActionDotClass(log.action)"></span>
                    {{ formatActionName(log.action) }}
                  </span>
                  <span class="inline-flex items-center gap-1 px-2 py-0.2 rounded-md bg-slate-100 text-slate-600 text-[10px] font-medium">
                    {{ formatModuleName(log.module) }}
                  </span>
                </div>
              </td>

              <!-- Deskripsi Aktifitas -->
              <td class="px-5 py-3.5 max-w-xs sm:max-w-md">
                <p class="text-slate-800 font-medium leading-relaxed break-words text-xs">
                  {{ log.description }}
                </p>
                <p v-if="log.user_email" class="text-[10px] text-slate-400 font-mono mt-0.5">
                  {{ log.user_email }}
                </p>
              </td>

              <!-- IP Address & User Agent -->
              <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">
                <div class="space-y-0.5">
                  <p class="font-mono text-[11px] text-slate-700 flex items-center gap-1">
                    <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    {{ log.ip_address || '127.0.0.1' }}
                  </p>
                  <p class="text-[10px] text-slate-400 truncate max-w-[140px]" :title="log.user_agent">
                    {{ parseUserAgent(log.user_agent) }}
                  </p>
                </div>
              </td>

              <!-- Waktu Kejadian -->
              <td class="px-5 py-3.5 whitespace-nowrap text-slate-500">
                <p class="font-bold text-slate-800 text-[11px]">
                  {{ formatRelativeTime(log.created_at) }}
                </p>
                <p class="text-[10px] text-slate-400">
                  {{ formatDateTime(log.created_at) }}
                </p>
              </td>

              <!-- Tombol Detail -->
              <td class="px-5 py-3.5 text-right whitespace-nowrap">
                <button 
                  @click="openModalDetail(log)"
                  class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-purple-100 text-slate-600 hover:text-purple-800 font-bold text-[11px] transition"
                  title="Lihat Detail Teknis"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <span>Detail</span>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination Footer -->
      <div v-if="pagination.total > pagination.per_page" class="p-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
        <p class="text-slate-500">
          Menampilkan baris <span class="font-bold text-slate-800">{{ pagination.from }}</span> - <span class="font-bold text-slate-800">{{ pagination.to }}</span> dari total <span class="font-bold text-slate-800">{{ pagination.total }}</span> aktifitas
        </p>

        <div class="flex items-center gap-1.5">
          <button 
            @click="changePage(pagination.current_page - 1)" 
            :disabled="pagination.current_page <= 1"
            class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
          >
            &laquo; Sebelumnya
          </button>
          
          <span class="px-3 py-1.5 rounded-xl bg-purple-50 text-purple-800 font-bold border border-purple-200">
            Hal. {{ pagination.current_page }} dari {{ pagination.last_page }}
          </span>

          <button 
            @click="changePage(pagination.current_page + 1)" 
            :disabled="pagination.current_page >= pagination.last_page"
            class="px-3 py-1.5 rounded-xl border border-slate-200 text-slate-700 font-bold hover:bg-slate-50 disabled:opacity-40 disabled:cursor-not-allowed transition"
          >
            Berikutnya &raquo;
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL DETAIL AUDIT LOG -->
    <div v-if="showModal && activeLog" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 flex items-center justify-center p-4">
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 space-y-4 animate-in fade-in zoom-in duration-150">
        <!-- Modal Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
          <div class="flex items-center gap-2.5">
            <div 
              class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs text-white uppercase"
              :class="getAvatarColor(activeLog.user_role)"
            >
              {{ (activeLog.user_name || 'U').charAt(0) }}
            </div>
            <div>
              <h3 class="font-bold text-slate-900 text-sm">Detail Rekam Jejak Aktifitas #{{ activeLog.id }}</h3>
              <p class="text-[11px] text-slate-500">{{ formatDateTime(activeLog.created_at) }}</p>
            </div>
          </div>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 font-bold text-xl">&times;</button>
        </div>

        <!-- Detail Information Grid -->
        <div class="space-y-3 text-xs">
          <!-- Pengguna & Peran -->
          <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100 grid grid-cols-2 gap-2">
            <div>
              <p class="text-[10px] text-slate-400 font-bold uppercase">Nama Pengguna</p>
              <p class="font-bold text-slate-800 mt-0.5">{{ activeLog.user_name || 'Sistem' }}</p>
            </div>
            <div>
              <p class="text-[10px] text-slate-400 font-bold uppercase">Peran (Role)</p>
              <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold mt-0.5" :class="getRoleBadgeClass(activeLog.user_role)">
                {{ formatRoleName(activeLog.user_role) }}
              </span>
            </div>
            <div class="col-span-2 pt-1 border-t border-slate-200/60">
              <p class="text-[10px] text-slate-400 font-bold uppercase">Alamat Email</p>
              <p class="font-mono text-slate-700 text-[11px] mt-0.5">{{ activeLog.user_email || '-' }}</p>
            </div>
          </div>

          <!-- Modul & Deskripsi -->
          <div class="space-y-1">
            <p class="text-[10px] text-slate-400 font-bold uppercase">Keterangan Aksi</p>
            <div class="p-3 bg-purple-50/40 border border-purple-100 rounded-2xl text-slate-800 font-medium">
              <div class="flex items-center gap-2 mb-1.5">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" :class="getActionBadgeClass(activeLog.action)">
                  {{ formatActionName(activeLog.action) }}
                </span>
                <span class="text-[11px] font-bold text-purple-900">
                  Modul: {{ formatModuleName(activeLog.module) }}
                </span>
              </div>
              <p class="text-xs leading-relaxed text-slate-800">{{ activeLog.description }}</p>
            </div>
          </div>

          <!-- Data Teknis (IP & User Agent) -->
          <div class="space-y-1">
            <p class="text-[10px] text-slate-400 font-bold uppercase">Informasi Jaringan & Perangkat</p>
            <div class="bg-slate-50 p-3 rounded-2xl border border-slate-100 space-y-1.5 font-mono text-[11px]">
              <div class="flex items-center justify-between">
                <span class="text-slate-500">IP Address:</span>
                <span class="font-bold text-slate-800">{{ activeLog.ip_address || '127.0.0.1' }}</span>
              </div>
              <div>
                <span class="text-slate-500 block text-[10px]">User-Agent:</span>
                <p class="text-slate-700 text-[10px] break-all mt-0.5 bg-white p-2 rounded-lg border border-slate-200">
                  {{ activeLog.user_agent || 'Tidak diketahui' }}
                </p>
              </div>
            </div>
          </div>

          <!-- Payload Data / Perubahan (JSON) -->
          <div v-if="activeLog.properties && Object.keys(activeLog.properties).length > 0" class="space-y-1">
            <p class="text-[10px] text-slate-400 font-bold uppercase">Parameter Data Terkait (Payload)</p>
            <pre class="bg-slate-900 text-emerald-300 p-3 rounded-2xl text-[10px] font-mono overflow-x-auto max-h-40">{{ JSON.stringify(activeLog.properties, null, 2) }}</pre>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="pt-3 border-t border-slate-100 flex justify-end">
          <button 
            @click="showModal = false" 
            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { AdminService } from '../../services/api';

const route = useRoute();
const router = useRouter();

const loading = ref(false);
const logs = ref([]);
const usersList = ref([]);
const showModal = ref(false);
const activeLog = ref(null);

const summary = reactive({
  total_logs: 0,
  today_logs: 0,
  active_users_today: 0,
  most_active_module: '-'
});

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 20,
  total: 0,
  from: 0,
  to: 0
});

const filters = reactive({
  user_id: 'semua',
  module: 'semua',
  action: 'semua',
  search: ''
});

let searchTimeout = null;

const hasActiveFilters = computed(() => {
  return filters.user_id !== 'semua' || 
         filters.module !== 'semua' || 
         filters.action !== 'semua' || 
         filters.search.trim() !== '';
});

const debounceSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    pagination.current_page = 1;
    loadLogs();
  }, 400);
};

const applyFilter = () => {
  pagination.current_page = 1;
  loadLogs();
};

const resetFilters = () => {
  filters.user_id = 'semua';
  filters.module = 'semua';
  filters.action = 'semua';
  filters.search = '';
  pagination.current_page = 1;
  loadLogs();
};

const changePage = (page) => {
  if (page < 1 || page > pagination.last_page) return;
  pagination.current_page = page;
  loadLogs();
};

const openModalDetail = (log) => {
  activeLog.value = log;
  showModal.value = true;
};

const loadUsers = async () => {
  try {
    const list = await AdminService.getActivityLogUsers();
    usersList.value = list;
  } catch (e) {
    console.error('Failed to load activity log users', e);
  }
};

const loadLogs = async () => {
  loading.value = true;
  try {
    const params = {
      page: pagination.current_page,
      per_page: pagination.per_page,
      user_id: filters.user_id,
      module: filters.module,
      action: filters.action,
      search: filters.search
    };

    const res = await AdminService.getActivityLogs(params);
    if (res && res.data) {
      logs.value = res.data.data || [];
      pagination.current_page = res.data.current_page || 1;
      pagination.last_page = res.data.last_page || 1;
      pagination.total = res.data.total || 0;
      pagination.from = res.data.from || 0;
      pagination.to = res.data.to || 0;
    }

    if (res && res.summary) {
      summary.total_logs = res.summary.total_logs || 0;
      summary.today_logs = res.summary.today_logs || 0;
      summary.active_users_today = res.summary.active_users_today || 0;
      summary.most_active_module = res.summary.most_active_module || '-';
    }
  } catch (e) {
    console.error('Failed to load activity logs', e);
  } finally {
    loading.value = false;
  }
};

const getSelectedUserName = () => {
  const found = usersList.value.find(u => String(u.id) === String(filters.user_id));
  return found ? found.name : `User #${filters.user_id}`;
};

const formatModuleName = (mod) => {
  const map = {
    auth: 'Autentikasi & Sesi',
    staff: 'Kelola Staf',
    berita: 'Berita',
    pengumuman: 'Pengumuman',
    galeri: 'Galeri Kegiatan',
    layanan: 'Layanan SOP',
    lembaga: 'Lembaga (LKK)',
    statistik: 'Statistik Wilayah',
    transparansi: 'Transparansi Anggaran',
    profil: 'Profil & Aparatur',
    kategori: 'Master Kategori',
    media: 'Media & File'
  };
  return map[mod] || mod || '-';
};

const formatActionName = (act) => {
  const map = {
    login: 'Login',
    logout: 'Logout',
    create: 'Tambah Data',
    update: 'Ubah Data',
    delete: 'Hapus Data',
    reset_password: 'Reset Sandi',
    upload: 'Upload File'
  };
  return map[act] || act || '-';
};

const formatRoleName = (role) => {
  const map = {
    super_admin: 'Super Admin',
    staff_konten: 'Staff Konten & Humas',
    staff_pelayanan: 'Staff Pelayanan',
    staff_administrasi: 'Staff Administrasi'
  };
  return map[role] || role || 'Pengguna';
};

const getAvatarColor = (role) => {
  switch (role) {
    case 'super_admin': return 'bg-purple-600';
    case 'staff_konten': return 'bg-emerald-600';
    case 'staff_pelayanan': return 'bg-blue-600';
    case 'staff_administrasi': return 'bg-amber-600';
    default: return 'bg-slate-600';
  }
};

const getRoleBadgeClass = (role) => {
  switch (role) {
    case 'super_admin': return 'bg-purple-100 text-purple-800 border border-purple-200';
    case 'staff_konten': return 'bg-emerald-100 text-emerald-800 border border-emerald-200';
    case 'staff_pelayanan': return 'bg-blue-100 text-blue-800 border border-blue-200';
    case 'staff_administrasi': return 'bg-amber-100 text-amber-800 border border-amber-200';
    default: return 'bg-slate-100 text-slate-700';
  }
};

const getActionBadgeClass = (action) => {
  switch (action) {
    case 'login': return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
    case 'logout': return 'bg-slate-100 text-slate-600 border border-slate-200';
    case 'create': return 'bg-teal-50 text-teal-700 border border-teal-200';
    case 'update': return 'bg-blue-50 text-blue-700 border border-blue-200';
    case 'delete': return 'bg-rose-50 text-rose-700 border border-rose-200';
    case 'reset_password': return 'bg-amber-50 text-amber-700 border border-amber-200';
    case 'upload': return 'bg-purple-50 text-purple-700 border border-purple-200';
    default: return 'bg-slate-100 text-slate-700';
  }
};

const getActionDotClass = (action) => {
  switch (action) {
    case 'login': return 'bg-emerald-500';
    case 'logout': return 'bg-slate-400';
    case 'create': return 'bg-teal-500';
    case 'update': return 'bg-blue-500';
    case 'delete': return 'bg-rose-500';
    case 'reset_password': return 'bg-amber-500';
    case 'upload': return 'bg-purple-500';
    default: return 'bg-slate-500';
  }
};

const parseUserAgent = (ua) => {
  if (!ua) return 'Perangkat Web';
  if (ua.includes('Chrome')) return 'Google Chrome';
  if (ua.includes('Firefox')) return 'Mozilla Firefox';
  if (ua.includes('Safari')) return 'Apple Safari';
  if (ua.includes('Edge')) return 'Microsoft Edge';
  return 'Web Browser';
};

const formatRelativeTime = (timestamp) => {
  if (!timestamp) return '-';
  const diff = (new Date() - new Date(timestamp)) / 1000;
  if (diff < 60) return 'Baru saja';
  if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
  if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
  if (diff < 604800) return `${Math.floor(diff / 86400)} hari lalu`;
  return formatDateTime(timestamp);
};

const formatDateTime = (timestamp) => {
  if (!timestamp) return '-';
  try {
    const d = new Date(timestamp);
    return new Intl.DateTimeFormat('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    }).format(d);
  } catch (e) {
    return timestamp;
  }
};

onMounted(async () => {
  await loadUsers();
  if (route.query.user_id) {
    filters.user_id = route.query.user_id;
  }
  loadLogs();
});
</script>
