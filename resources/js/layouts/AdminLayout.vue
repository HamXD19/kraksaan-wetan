<template>
  <div class="min-h-screen bg-slate-100 flex flex-col md:flex-row text-slate-800 font-sans">
    <!-- Mobile Sidebar Backdrop -->
    <div 
      v-if="sidebarOpen" 
      @click="sidebarOpen = false" 
      class="fixed inset-0 z-40 bg-slate-950/60 md:hidden"
    ></div>

    <!-- Sidebar Navigation -->
    <aside 
      class="fixed md:sticky top-0 inset-y-0 left-0 z-50 w-64 bg-emerald-950 text-white flex flex-col transition-transform duration-300 transform md:translate-x-0"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <!-- Sidebar Brand Header -->
      <div class="p-5 border-b border-emerald-900 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="w-9 h-11 flex-shrink-0 flex items-center justify-center">
            <img 
              v-if="profil?.logo" 
              :src="profil.logo" 
              alt="Logo Kelurahan" 
              class="w-full h-full object-contain" 
            />
            <svg v-else viewBox="0 0 80 96" class="w-full h-full" fill="none">
              <path d="M40 2L76 18V50C76 72 40 94 40 94C40 94 4 72 4 50V18L40 2Z" fill="#047857" stroke="#f59e0b" stroke-width="3"/>
              <path d="M22 56L40 32L58 56H22Z" fill="#f8fafc"/>
              <circle cx="40" cy="26" r="5" fill="#f59e0b"/>
            </svg>
          </div>
          <div>
            <h2 class="font-bold text-sm leading-tight text-white">Panel Admin</h2>
            <p class="text-[11px] text-amber-400 font-semibold">Kraksaan Wetan</p>
          </div>
        </div>
        <button @click="sidebarOpen = false" class="md:hidden text-emerald-300 hover:text-white">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- Nav Links -->
      <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto text-xs font-semibold">
        <!-- Dashboard: Semua Staf -->
        <router-link 
          to="/admin" 
          exact-active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
          class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
        >
          <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          <span>Dashboard</span>
        </router-link>

        <!-- Kelola Akun Staf: Super Admin Only -->
        <router-link 
          v-if="isSuperAdmin()"
          to="/admin/staff" 
          active-class="bg-purple-900 text-purple-200 font-bold shadow-xs border border-purple-700/50"
          class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-purple-200 transition"
        >
          <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
          <span>Kelola Akun Staf</span>
        </router-link>

        <!-- Modul Konten & Humas -->
        <template v-if="canAccess('staff_konten')">
          <div class="pt-2 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-emerald-400/70">
            Konten & Informasi
          </div>

          <router-link 
            to="/admin/berita" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            <span>Kelola Berita</span>
          </router-link>

          <router-link 
            to="/admin/pengumuman" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            <span>Kelola Pengumuman</span>
          </router-link>

          <router-link 
            to="/admin/galeri" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            <span>Kelola Galeri Foto</span>
          </router-link>
        </template>

        <!-- Modul Pelayanan Masyarakat -->
        <template v-if="canAccess('staff_pelayanan')">
          <div class="pt-2 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-emerald-400/70">
            Pelayanan Warga
          </div>

          <router-link 
            to="/admin/layanan" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Kelola Layanan</span>
          </router-link>
        </template>

        <!-- Modul Administrasi Wilayah & LKK -->
        <template v-if="canAccess('staff_administrasi')">
          <div class="pt-2 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-emerald-400/70">
            Administrasi Wilayah
          </div>

          <router-link 
            to="/admin/lembaga" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            <span>Lembaga Kemasyarakatan</span>
          </router-link>

          <router-link 
            to="/admin/statistik" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            <span>Statistik Kependudukan</span>
          </router-link>

          <router-link 
            to="/admin/transparansi" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span>Transparansi Anggaran</span>
          </router-link>
        </template>

        <!-- Master Kategori (Semua Divisi) -->
        <div class="pt-2 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-emerald-400/70">
          Master Data
        </div>

        <router-link 
          to="/admin/kategori" 
          active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
          class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
        >
          <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
          <span>Master Kategori</span>
        </router-link>

        <!-- Modul Kebijakan & Profil (Super Admin Only) -->
        <template v-if="isSuperAdmin()">
          <div class="pt-2 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-purple-300/70">
            Pemerintahan & Profil
          </div>

          <router-link 
            to="/admin/profil" 
            active-class="bg-emerald-800 text-amber-300 font-bold shadow-xs"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl hover:bg-emerald-900 text-emerald-100 transition"
          >
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            <span>Profil & Aparatur</span>
          </router-link>
        </template>
      </nav>

      <!-- Bottom User & Logout -->
      <div class="p-4 border-t border-emerald-900 text-xs space-y-3">
        <div class="flex items-center gap-2 text-emerald-200">
          <div 
            class="w-8 h-8 rounded-full font-bold flex items-center justify-center text-xs text-white uppercase shadow-xs flex-shrink-0"
            :class="getRoleColor(user?.role)"
          >
            {{ (user?.name || 'A').charAt(0) }}
          </div>
          <div class="truncate min-w-0">
            <p class="font-bold text-white truncate">{{ user?.name || 'Administrator' }}</p>
            <div class="flex items-center gap-1.5 mt-0.5">
              <span 
                class="px-1.5 py-0.2 rounded text-[10px] font-bold"
                :class="getRoleBadgeSmall(user?.role)"
              >
                {{ user?.role_label || 'Admin' }}
              </span>
            </div>
          </div>
        </div>

        <div class="pt-2 flex flex-col gap-2">
          <router-link to="/" target="_blank" class="flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-emerald-900 hover:bg-emerald-800 text-emerald-200 font-medium transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Buka Website Publik
          </router-link>
          <button @click="handleLogout" class="flex items-center justify-center gap-1.5 py-1.5 px-3 rounded-lg bg-rose-900/60 hover:bg-rose-800 text-rose-200 font-medium transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar (Logout)
          </button>
        </div>
      </div>
    </aside>

    <!-- Main Admin Workspace -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Topbar Header -->
      <header class="bg-white border-b border-slate-200 py-3.5 px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-3">
          <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-lg text-slate-600 hover:bg-slate-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <div>
            <h1 class="text-base font-bold text-slate-900">Sistem Manajemen Konten (CMS)</h1>
            <p class="text-[11px] text-slate-500">Kelurahan Kraksaan Wetan &bull; Terhubung Database MySQL</p>
          </div>
        </div>

        <div class="flex items-center gap-3">
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            Database Aktif
          </span>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-4 sm:p-6 lg:p-8">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { AdminService, KelurahanService } from '../services/api';

const router = useRouter();
const route = useRoute();
const sidebarOpen = ref(false);
const user = ref(null);
const profil = ref(null);

const isSuperAdmin = () => {
  return user.value?.role === 'super_admin';
};

const canAccess = (roles) => {
  if (!user.value) return false;
  if (user.value.role === 'super_admin') return true;
  if (Array.isArray(roles)) {
    return roles.includes(user.value.role);
  }
  return user.value.role === roles;
};

const getRoleColor = (role) => {
  switch (role) {
    case 'super_admin':
      return 'bg-purple-600 text-white';
    case 'staff_konten':
      return 'bg-emerald-600 text-white';
    case 'staff_pelayanan':
      return 'bg-blue-600 text-white';
    case 'staff_administrasi':
      return 'bg-amber-500 text-white';
    default:
      return 'bg-slate-600 text-white';
  }
};

const getRoleBadgeSmall = (role) => {
  switch (role) {
    case 'super_admin':
      return 'bg-purple-900/80 text-purple-200 border border-purple-700/50';
    case 'staff_konten':
      return 'bg-emerald-900/80 text-emerald-200 border border-emerald-700/50';
    case 'staff_pelayanan':
      return 'bg-blue-900/80 text-blue-200 border border-blue-700/50';
    case 'staff_administrasi':
      return 'bg-amber-900/80 text-amber-200 border border-amber-700/50';
    default:
      return 'bg-slate-800 text-slate-300';
  }
};

const loadProfil = async () => {
  try {
    profil.value = await KelurahanService.getProfil();
  } catch (e) {
    // fallback
  }
};

const handleProfilUpdate = (e) => {
  if (e.detail) {
    profil.value = e.detail;
  } else {
    loadProfil();
  }
};

onMounted(async () => {
  loadProfil();
  window.addEventListener('profil-updated', handleProfilUpdate);
  user.value = AdminService.getAuthUser();
  try {
    const me = await AdminService.getMe();
    if (me) user.value = me;
  } catch (e) {
    // handled by interceptor if 401
  }
});

onUnmounted(() => {
  window.removeEventListener('profil-updated', handleProfilUpdate);
});

const handleLogout = async () => {
  await AdminService.logout();
  router.push('/admin/login');
};
</script>
