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
        path: 'pelayanan',
        name: 'pelayanan',
        component: () => import('../pages/Pelayanan.vue'),
        meta: { title: 'Pelayanan Masyarakat - Kelurahan Kraksaan Wetan' }
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
        path: 'informasi-publik/transparansi',
        redirect: '/transparansi'
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
        meta: { title: 'Kelola Berita - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_konten'] }
      },
      {
        path: 'pengumuman',
        name: 'admin-pengumuman',
        component: () => import('../pages/admin/ManagePengumuman.vue'),
        meta: { title: 'Kelola Pengumuman - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_konten'] }
      },
      {
        path: 'layanan',
        name: 'admin-layanan',
        component: () => import('../pages/admin/ManageLayanan.vue'),
        meta: { title: 'Kelola Layanan - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_pelayanan'] }
      },
      {
        path: 'galeri',
        name: 'admin-galeri',
        component: () => import('../pages/admin/ManageGaleri.vue'),
        meta: { title: 'Kelola Galeri Foto - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_konten'] }
      },
      {
        path: 'profil',
        name: 'admin-profil',
        component: () => import('../pages/admin/ManageProfil.vue'),
        meta: { title: 'Profil & Aparatur - Admin Kelurahan', requiresAuth: true, roles: ['super_admin'] }
      },
      {
        path: 'lembaga',
        name: 'admin-lembaga',
        component: () => import('../pages/admin/ManageLembaga.vue'),
        meta: { title: 'Kelola Lembaga Kemasyarakatan - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_administrasi'] }
      },
      {
        path: 'statistik',
        name: 'admin-statistik',
        component: () => import('../pages/admin/ManageStatistik.vue'),
        meta: { title: 'Statistik Wilayah - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_administrasi'] }
      },
      {
        path: 'transparansi',
        name: 'admin-transparansi',
        component: () => import('../pages/admin/ManageTransparansi.vue'),
        meta: { title: 'Transparansi Anggaran - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_administrasi'] }
      },
      {
        path: 'kategori',
        name: 'admin-kategori',
        component: () => import('../pages/admin/ManageKategori.vue'),
        meta: { title: 'Master Kategori - Admin Kelurahan', requiresAuth: true, roles: ['super_admin', 'staff_konten', 'staff_pelayanan', 'staff_administrasi'] }
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
  } else if (to.meta.roles && isAuth) {
    const user = AdminService.getAuthUser();
    const userRole = user?.role || '';
    if (userRole === 'super_admin' || to.meta.roles.includes(userRole)) {
      next();
    } else {
      next('/admin');
    }
  } else {
    next();
  }
});

router.afterEach((to) => {
  const defaultTitle = 'Website Resmi Kelurahan Kraksaan Wetan - Kabupaten Probolinggo';
  document.title = to.meta.title || defaultTitle;
});

export default router;
