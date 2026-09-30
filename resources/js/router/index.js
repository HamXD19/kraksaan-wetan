import { createRouter, createWebHistory } from 'vue-router';
import MainLayout from '../layouts/MainLayout.vue';
import { AdminService } from '../services/api';
import { stopAllSound } from '../utils/sound';

const routes = [
  // Portal Publik Warga
  {
    path: '/',
    component: MainLayout,
    children: [
      {
        path: '',
        name: 'home',
        component: () => import('../pages/Home.vue'),
        meta: { title: 'Beranda - Website Resmi Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'profil',
        name: 'profil',
        component: () => import('../pages/Profil.vue'),
        meta: { title: 'Profil Kelurahan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'profil/sejarah',
        name: 'sejarah',
        component: () => import('../pages/Sejarah.vue'),
        meta: { title: 'Sejarah Kelurahan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'profil/visi-misi',
        name: 'visi-misi',
        component: () => import('../pages/VisiMisi.vue'),
        meta: { title: 'Visi dan Misi - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'profil/struktur-organisasi',
        name: 'struktur-organisasi',
        component: () => import('../pages/StrukturOrganisasi.vue'),
        meta: { title: 'Struktur Organisasi - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'pemerintahan',
        name: 'pemerintahan',
        component: () => import('../pages/Pemerintahan.vue'),
        meta: { title: 'Pemerintahan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'lembaga',
        name: 'lembaga',
        component: () => import('../pages/Lembaga.vue'),
        meta: { title: 'Lembaga Kemasyarakatan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'pemerintahan/lembaga',
        redirect: '/lembaga'
      },
      {
        path: 'informasi-publik',
        name: 'informasi-publik',
        component: () => import('../pages/InformasiPublik.vue'),
        meta: { title: 'Informasi Publik & Statistik - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'berita',
        name: 'berita',
        component: () => import('../pages/Berita.vue'),
        meta: { title: 'Berita & Pengumuman - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'berita/:slug',
        name: 'berita-detail',
        component: () => import('../pages/BeritaDetail.vue'),
        meta: { title: 'Detail Berita - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'galeri',
        name: 'galeri',
        component: () => import('../pages/Galeri.vue'),
        meta: { title: 'Galeri Kegiatan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'galeri/:id',
        name: 'galeri-detail',
        component: () => import('../pages/GaleriDetail.vue'),
        meta: { title: 'Detail Galeri - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'dokumen',
        name: 'dokumen',
        component: () => import('../pages/Dokumen.vue'),
        meta: { title: 'Unduh Dokumen Kelurahan (PDF) - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'unduh-dokumen',
        redirect: '/dokumen'
      },
      {
        path: 'pelayanan',
        name: 'pelayanan',
        component: () => import('../pages/Pelayanan.vue'),
        meta: { title: 'Pelayanan Masyarakat - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'survei-skm',
        name: 'survei-skm',
        component: () => import('../pages/SurveiSkm.vue'),
        meta: { title: 'Survei Kepuasan Masyarakat (SKM) - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'pelayanan/skm',
        redirect: '/survei-skm'
      },
      {
        path: 'kontak',
        name: 'kontak',
        component: () => import('../pages/Kontak.vue'),
        meta: { title: 'Kontak & Pengaduan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'transparansi',
        name: 'transparansi',
        component: () => import('../pages/Transparansi.vue'),
        meta: { title: 'Transparansi & Akuntabilitas Anggaran - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'transparansi/:slug',
        name: 'transparansi-detail',
        component: () => import('../pages/TransparansiDetail.vue'),
        meta: { title: 'Detail Anggaran & Realisasi APBD - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'apbd',
        redirect: '/transparansi'
      },
      {
        path: 'apbd/:slug',
        redirect: to => `/transparansi/${to.params.slug}`
      },
      {
        path: 'informasi-publik/transparansi',
        redirect: '/transparansi'
      },
      {
        path: 'agenda',
        name: 'agenda',
        component: () => import('../pages/Agenda.vue'),
        meta: { title: 'Agenda Kegiatan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'agenda/:slug',
        name: 'agenda-detail',
        component: () => import('../pages/AgendaDetail.vue'),
        meta: { title: 'Detail Agenda Kegiatan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'informasi-publik/agenda',
        redirect: '/agenda'
      },
      {
        path: 'halaman/:slug',
        name: 'halaman-detail',
        component: () => import('../pages/CustomPage.vue'),
        meta: { title: 'Informasi - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'profil/:slug',
        name: 'profil-custom-page',
        component: () => import('../pages/CustomPage.vue'),
        meta: { title: 'Profil - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'pemerintahan/:slug',
        name: 'pemerintahan-custom-page',
        component: () => import('../pages/CustomPage.vue'),
        meta: { title: 'Pemerintahan - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'informasi-publik/:slug',
        name: 'informasi-publik-custom-page',
        component: () => import('../pages/CustomPage.vue'),
        meta: { title: 'Informasi Publik - Kelurahan Kraksaan Wetan' }
      },
      {
        path: 'informasi/:slug',
        name: 'informasi-custom-page',
        component: () => import('../pages/CustomPage.vue'),
        meta: { title: 'Informasi - Kelurahan Kraksaan Wetan' }
      }
    ]
  },

  // Admin CMS Authentication
  {
    path: '/admin/login',
    name: 'admin-login',
    component: () => import('../pages/admin/Login.vue'),
    meta: { title: 'Login Administrator - Kelurahan Kraksaan Wetan' }
  },

  // Admin CMS Panel
  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'admin-dashboard',
        component: () => import('../pages/admin/Dashboard.vue'),
        meta: { title: 'Dashboard Admin - Kelurahan Kraksaan Wetan', requiresAuth: true }
      },
      {
        path: 'staff',
        name: 'admin-staff',
        component: () => import('../pages/admin/ManageStaff.vue'),
        meta: { title: 'Kelola Akun Staf - Super Admin', requiresAuth: true, roles: ['super_admin'] }
      },
      {
        path: 'activity-logs',
        name: 'admin-activity-logs',
        component: () => import('../pages/admin/ManageActivityLogs.vue'),
        meta: { title: 'Log Aktifitas Akun - Super Admin', requiresAuth: true, roles: ['super_admin'] }
      },
      {
        path: 'berita',
        name: 'admin-berita',
        component: () => import('../pages/admin/ManageBerita.vue'),
        meta: { title: 'Kelola Berita - Admin Kelurahan', requiresAuth: true, menu: 'berita', roles: ['super_admin', 'staff_konten'] }
      },
      {
        path: 'pengumuman',
        name: 'admin-pengumuman',
        component: () => import('../pages/admin/ManagePengumuman.vue'),
        meta: { title: 'Kelola Pengumuman - Admin Kelurahan', requiresAuth: true, menu: 'pengumuman', roles: ['super_admin', 'staff_konten'] }
      },
      {
        path: 'layanan',
        name: 'admin-layanan',
        component: () => import('../pages/admin/ManageLayanan.vue'),
        meta: { title: 'Kelola Layanan - Admin Kelurahan', requiresAuth: true, menu: 'layanan', roles: ['super_admin', 'staff_pelayanan'] }
      },
      {
        path: 'galeri',
        name: 'admin-galeri',
        component: () => import('../pages/admin/ManageGaleri.vue'),
        meta: { title: 'Kelola Galeri Foto - Admin Kelurahan', requiresAuth: true, menu: 'galeri', roles: ['super_admin', 'staff_konten'] }
      },
      {
        path: 'agenda',
        name: 'admin-agenda',
        component: () => import('../pages/admin/ManageAgenda.vue'),
        meta: { title: 'Kelola Agenda Kegiatan - Admin Kelurahan', requiresAuth: true, menu: 'agenda', roles: ['super_admin', 'staff_konten'] }
      },
      {
        path: 'dokumen',
        name: 'admin-dokumen',
        component: () => import('../pages/admin/ManageDokumen.vue'),
        meta: { title: 'Kelola Dokumen PDF - Admin Kelurahan', requiresAuth: true, menu: 'dokumen', roles: ['super_admin', 'staff_konten', 'staff_administrasi'] }
      },
      {
        path: 'profil',
        name: 'admin-profil',
        component: () => import('../pages/admin/ManageProfil.vue'),
        meta: { title: 'Profil & Aparatur - Admin Kelurahan', requiresAuth: true, menu: 'profil', roles: ['super_admin'] }
      },
      {
        path: 'system-settings',
        name: 'admin-system-settings',
        component: () => import('../pages/admin/ManageSystemSettings.vue'),
        meta: { title: 'Setting System - Admin Kelurahan', requiresAuth: true, roles: ['super_admin'] }
      },
      {
        path: 'lembaga',
        name: 'admin-lembaga',
        component: () => import('../pages/admin/ManageLembaga.vue'),
        meta: { title: 'Kelola Lembaga Kemasyarakatan - Admin Kelurahan', requiresAuth: true, menu: 'lembaga', roles: ['super_admin', 'staff_administrasi'] }
      },
      {
        path: 'statistik',
        name: 'admin-statistik',
        component: () => import('../pages/admin/ManageStatistik.vue'),
        meta: { title: 'Statistik Wilayah - Admin Kelurahan', requiresAuth: true, menu: 'statistik', roles: ['super_admin', 'staff_administrasi'] }
      },
      {
        path: 'transparansi',
        name: 'admin-transparansi',
        component: () => import('../pages/admin/ManageTransparansi.vue'),
        meta: { title: 'Transparansi Anggaran - Admin Kelurahan', requiresAuth: true, menu: 'transparansi', roles: ['super_admin', 'staff_administrasi'] }
      },
      {
        path: 'kategori',
        name: 'admin-kategori',
        component: () => import('../pages/admin/ManageKategori.vue'),
        meta: { title: 'Master Kategori - Admin Kelurahan', requiresAuth: true, menu: 'kategori', roles: ['super_admin', 'staff_konten', 'staff_pelayanan', 'staff_administrasi'] }
      }
    ]
  },

  // Catch-All
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (to.hash) {
      return {
        el: to.hash,
        behavior: 'smooth',
        top: 90
      };
    }
    if (savedPosition) {
      return savedPosition;
    }
    return { top: 0, behavior: 'smooth' };
  }
});

router.beforeEach((to, from, next) => {
  if (to.path.startsWith('/admin')) {
    stopAllSound();
  }

  const isAuth = AdminService.isAuthenticated();

  if (to.meta.requiresAuth && !isAuth) {
    next('/admin/login');
  } else if (to.path === '/admin/login' && isAuth) {
    next('/admin');
  } else if (to.meta.requiresAuth && isAuth) {
    const user = AdminService.getAuthUser();
    const userRole = user?.role || '';

    // Super Admin memiliki hak penuh ke semua rute
    if (userRole === 'super_admin') {
      next();
      return;
    }

    // Pengecekan izin akses menu granular
    if (to.meta.menu) {
      const allowedMenus = user?.effective_menus || user?.accessible_menus;
      if (Array.isArray(allowedMenus) && allowedMenus.includes(to.meta.menu)) {
        next();
        return;
      }
      next('/admin');
      return;
    }

    // Fallback pengecekan role jika tidak ada meta.menu khusus (misal admin-staff, activity-logs)
    if (to.meta.roles) {
      if (to.meta.roles.includes(userRole)) {
        next();
      } else {
        next('/admin');
      }
      return;
    }

    next();
  } else {
    next();
  }
});

router.afterEach((to) => {
  const defaultTitle = 'Website Resmi Kelurahan Kraksaan Wetan - Kabupaten Probolinggo';
  document.title = to.meta.title || defaultTitle;
});

export default router;
