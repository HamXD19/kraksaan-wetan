<template>
  <div class="space-y-8">
    <!-- Header Welcome -->
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2 mb-2">
          <span class="px-3 py-1 rounded-full bg-emerald-800 text-amber-300 text-xs font-bold uppercase tracking-wider inline-block">
            Selamat Datang
          </span>
          <span 
            v-if="authUser?.role_label" 
            class="px-2.5 py-0.5 rounded-full text-xs font-bold"
            :class="getRoleHeaderBadge(authUser.role)"
          >
            {{ authUser.role_label }}
          </span>
        </div>
        <h2 class="text-2xl sm:text-3xl font-bold">Ringkasan Sistem Kelurahan</h2>
        <p class="text-xs sm:text-sm text-emerald-200 mt-1">
          Kelola konten portal website resmi Kelurahan Kraksaan Wetan secara real-time dan terintegrasi dengan database MySQL.
        </p>
      </div>

      <!-- Quick Action Buttons based on Role -->
      <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
        <router-link 
          v-if="isSuperAdmin()"
          to="/admin/staff" 
          class="px-4 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shadow-md transition flex items-center gap-1.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          Kelola Staf
        </router-link>

        <router-link 
          v-if="canAccess('staff_konten')"
          to="/admin/berita" 
          class="px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold shadow-md transition flex items-center gap-1.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          Tulis Berita Baru
        </router-link>

        <router-link 
          v-if="canAccess('staff_pelayanan') && !canAccess('staff_konten')"
          to="/admin/layanan" 
          class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold shadow-md transition flex items-center gap-1.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          Kelola Layanan
        </router-link>

        <router-link 
          v-if="canAccess('staff_administrasi') && !isSuperAdmin()"
          to="/admin/lembaga" 
          class="px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold shadow-md transition flex items-center gap-1.5"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          Kelola Lembaga
        </router-link>
      </div>
    </div>

    <LoadingSpinner v-if="loading" />
    <template v-else>
      <!-- Stat Metric Cards Filtered by Role -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Card 1: Total Staf (Super Admin) -->
        <div v-if="isSuperAdmin()" class="bg-white p-5 rounded-2xl border border-purple-200 bg-purple-50/20 shadow-xs flex items-center justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-purple-700">Akun Staf</p>
            <p class="text-3xl font-extrabold text-purple-900 font-mono mt-1">{{ dashboard.counts?.staff || 0 }}</p>
            <router-link to="/admin/staff" class="text-[11px] font-semibold text-purple-700 hover:underline mt-1 block">Kelola Akun Staf &rarr;</router-link>
          </div>
          <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          </div>
        </div>

        <!-- Card: Berita (Konten & Super Admin) -->
        <div v-if="canAccess('staff_konten')" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Berita</p>
            <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1">{{ dashboard.counts?.berita || 0 }}</p>
            <router-link to="/admin/berita" class="text-[11px] font-semibold text-emerald-700 hover:underline mt-1 block">Kelola Berita &rarr;</router-link>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
          </div>
        </div>

        <!-- Card: Pengumuman (Konten & Super Admin) -->
        <div v-if="canAccess('staff_konten')" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Pengumuman</p>
            <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1">{{ dashboard.counts?.pengumuman || 0 }}</p>
            <router-link to="/admin/pengumuman" class="text-[11px] font-semibold text-emerald-700 hover:underline mt-1 block">Kelola Pengumuman &rarr;</router-link>
          </div>
          <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
          </div>
        </div>

        <!-- Card: Galeri (Konten & Super Admin) -->
        <div v-if="canAccess('staff_konten')" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Galeri Foto</p>
            <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1">{{ dashboard.counts?.galeri || 0 }}</p>
            <router-link to="/admin/galeri" class="text-[11px] font-semibold text-emerald-700 hover:underline mt-1 block">Kelola Galeri &rarr;</router-link>
          </div>
          <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          </div>
        </div>

        <!-- Card: Layanan Warga (Pelayanan & Super Admin) -->
        <div v-if="canAccess('staff_pelayanan')" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Layanan Warga</p>
            <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1">{{ dashboard.counts?.layanan || 0 }}</p>
            <router-link to="/admin/layanan" class="text-[11px] font-semibold text-emerald-700 hover:underline mt-1 block">Kelola Layanan &rarr;</router-link>
          </div>
          <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
        </div>

        <!-- Card: Lembaga Kemasyarakatan (Administrasi & Super Admin) -->
        <div v-if="canAccess('staff_administrasi')" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Lembaga (LKK)</p>
            <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1">{{ dashboard.counts?.lembaga || 0 }}</p>
            <router-link to="/admin/lembaga" class="text-[11px] font-semibold text-emerald-700 hover:underline mt-1 block">Kelola Lembaga &rarr;</router-link>
          </div>
          <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          </div>
        </div>
      </div>

      <!-- Recent Berita Table (Konten & Humas & Super Admin) -->
      <div v-if="canAccess('staff_konten') || isSuperAdmin()" class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
          <div>
            <h3 class="font-bold text-slate-900 text-base">Berita & Publikasi Terbaru</h3>
            <p class="text-xs text-slate-500">Daftar publikasi berita kelurahan terkini</p>
          </div>
          <router-link to="/admin/berita" class="text-xs font-bold text-emerald-700 hover:underline">
            Kelola Semua Berita &rarr;
          </router-link>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200">
              <tr>
                <th class="py-3 px-4">Judul Berita</th>
                <th class="py-3 px-4">Kategori</th>
                <th class="py-3 px-4">Tanggal</th>
                <th class="py-3 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600">
              <tr v-for="b in dashboard.recent_berita" :key="b.id" class="hover:bg-slate-50">
                <td class="py-3 px-4 font-bold text-slate-900">{{ b.judul }}</td>
                <td class="py-3 px-4">
                  <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-800 font-medium text-[11px]">
                    {{ b.kategori }}
                  </span>
                </td>
                <td class="py-3 px-4 text-slate-400">{{ b.tanggal }}</td>
                <td class="py-3 px-4 text-right">
                  <router-link to="/admin/berita" class="text-emerald-700 hover:underline font-semibold">
                    Edit
                  </router-link>
                </td>
              </tr>
              <tr v-if="!dashboard.recent_berita?.length">
                <td colspan="4" class="py-8 text-center text-slate-400">Belum ada berita terbit.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import LoadingSpinner from '../../components/LoadingSpinner.vue';
import { AdminService } from '../../services/api';

const loading = ref(true);
const dashboard = ref({});
const authUser = ref(null);

const isSuperAdmin = () => {
  return authUser.value?.role === 'super_admin';
};

const canAccess = (roles) => {
  if (!authUser.value) return false;
  if (authUser.value.role === 'super_admin') return true;
  if (Array.isArray(roles)) {
    return roles.includes(authUser.value.role);
  }
  return authUser.value.role === roles;
};

const getRoleHeaderBadge = (role) => {
  switch (role) {
    case 'super_admin':
      return 'bg-purple-800 text-purple-200 border border-purple-600/50';
    case 'staff_konten':
      return 'bg-emerald-800 text-emerald-200 border border-emerald-600/50';
    case 'staff_pelayanan':
      return 'bg-blue-800 text-blue-200 border border-blue-600/50';
    case 'staff_administrasi':
      return 'bg-amber-800 text-amber-200 border border-amber-600/50';
    default:
      return 'bg-slate-800 text-slate-200';
  }
};

onMounted(async () => {
  authUser.value = AdminService.getAuthUser();
  try {
    dashboard.value = await AdminService.getDashboard();
  } catch (err) {
    console.error('Gagal mengambil data dashboard:', err);
  } finally {
    loading.value = false;
  }
});

function formatDateTime(val) {
  if (!val) return '-';
  return val.replace('T', ' ').substring(0, 16);
}
</script>
