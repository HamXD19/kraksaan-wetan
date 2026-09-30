import axios from 'axios';
import { mockProfil, mockStatistik, mockLayanan, mockBerita, mockPengumuman, mockGaleri, mockLembaga, mockTransparansi } from './mockData';

const apiClient = axios.create({
    baseURL: '/api',
    timeout: 30000,
    headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    }
});

// Interceptor for Authorization token
apiClient.interceptors.request.use((config) => {
    const token = localStorage.getItem('kw_admin_token');
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
    }
    return config;
});

// Interceptor for Handling 401 Unauthorized Session Expiration
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401 && window.location.pathname.startsWith('/admin') && window.location.pathname !== '/admin/login') {
            localStorage.removeItem('kw_admin_token');
            localStorage.removeItem('kw_admin_user');
            window.location.href = '/admin/login';
        }
        return Promise.reject(error);
    }
);

export const KelurahanService = {
    async getProfil() {
        try {
            const res = await apiClient.get('/profil');
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /profil fallback to mock data:', e);
            return mockProfil;
        }
    },

    async getStatistik() {
        try {
            const res = await apiClient.get('/statistik');
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /statistik fallback to mock data:', e);
            return mockStatistik;
        }
    },

    async getLayanan() {
        try {
            const res = await apiClient.get('/pelayanan');
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /pelayanan fallback to mock data:', e);
            return mockLayanan;
        }
    },

    async getLayananBySlug(slug) {
        const res = await apiClient.get(`/pelayanan/${slug}`);
        return res.data?.data || res.data;
    },

    async getBerita(params = {}) {
        try {
            const res = await apiClient.get('/berita', { params });
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /berita fallback to mock data:', e);
            let result = [...mockBerita];
            if (params.kategori && params.kategori !== 'Semua') {
                result = result.filter(b => b.kategori.toLowerCase() === params.kategori.toLowerCase());
            }
            if (params.search) {
                const s = params.search.toLowerCase();
                result = result.filter(b => b.judul.toLowerCase().includes(s) || b.ringkasan.toLowerCase().includes(s));
            }
            return result;
        }
    },

    async getBeritaBySlug(slug) {
        try {
            const res = await apiClient.get(`/berita/${slug}`);
            return res.data?.data || res.data;
        } catch (e) {
            console.warn(`API /berita/${slug} fallback to mock data:`, e);
            const found = mockBerita.find(b => b.slug === slug);
            if (found) return found;
            throw e;
        }
    },

    async getPengumuman() {
        try {
            const res = await apiClient.get('/pengumuman');
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /pengumuman fallback to mock data:', e);
            return mockPengumuman;
        }
    },

    async getDokumen(params = {}) {
        try {
            const res = await apiClient.get('/dokumen', { params });
            return res.data;
        } catch (e) {
            console.warn('API /dokumen error:', e);
            return { status: 'error', data: [], meta: { total: 0, kategori_list: [], tahun_list: [] } };
        }
    },

    getDokumenUnduhUrl(id) {
        return `/api/dokumen/${id}/unduh`;
    },

    getDokumenPreviewUrl(id) {
        return `/api/dokumen/${id}/pratinjau`;
    },

    async getGaleri() {
        try {
            const res = await apiClient.get('/galeri');
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /galeri fallback to mock data:', e);
            return mockGaleri;
        }
    },

    async getGaleriById(id) {
        try {
            const res = await apiClient.get(`/galeri/${id}`);
            return res.data?.data || res.data;
        } catch (e) {
            console.warn(`API /galeri/${id} fallback to mock data:`, e);
            const found = mockGaleri.find(g => String(g.id) === String(id));
            if (found) return found;
            throw e;
        }
    },

    async getLembaga() {
        try {
            const res = await apiClient.get('/lembaga');
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /lembaga fallback to mock data:', e);
            return mockLembaga;
        }
    },

    async getTransparansi(params = {}) {
        try {
            const res = await apiClient.get('/transparansi', { params });
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /transparansi error:', e);
            return { summary: {}, budgets: [], kegiatan: [] };
        }
    },

    async getTransparansiDetail(slugOrId) {
        const res = await apiClient.get(`/transparansi/${slugOrId}`);
        return res.data?.data || res.data;
    },

    getTransparansiDownloadUrl(slugOrId) {
        return `/api/transparansi/${slugOrId}/unduh`;
    },

    async getKategori(modul = '') {
        try {
            const params = modul ? { modul } : {};
            const res = await apiClient.get('/kategori', { params });
            return res.data?.data || res.data || [];
        } catch (e) {
            console.warn('API /kategori fallback:', e);
            return [];
        }
    },

    async getAgenda(params = {}) {
        const res = await apiClient.get('/agenda', { params });
        return res.data;
    },

    async getAgendaBySlug(slug) {
        const res = await apiClient.get(`/agenda/${slug}`);
        return res.data?.data || res.data;
    },

    async getHalamanBySlug(slug) {
        const res = await apiClient.get(`/halaman/${slug}`);
        return res.data?.data || res.data;
    },

    async kirimKontak(data) {
        const res = await apiClient.post('/kontak', data);
        return res.data;
    },

    // Maklumat Pelayanan
    async getMaklumatPelayanan() {
        try {
            const res = await apiClient.get('/maklumat-pelayanan');
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /maklumat-pelayanan fallback:', e);
            return null;
        }
    },

    // Survei Kepuasan Masyarakat (SKM)
    async getSurveiSkm(params = {}) {
        try {
            const res = await apiClient.get('/survei-skm', { params });
            return res.data?.data || res.data;
        } catch (e) {
            console.warn('API /survei-skm fallback:', e);
            return { latest: null, list: [], available_years: [] };
        }
    },

    async getSurveiSkmDetail(id) {
        const res = await apiClient.get(`/survei-skm/${id}`);
        return res.data?.data || res.data;
    },

    // Running Text Warta
    async getRunningText() {
        try {
            const res = await apiClient.get('/running-text');
            return res.data?.data || res.data || [];
        } catch (e) {
            console.warn('API /running-text fallback:', e);
            return [];
        }
    }
};

export const AdminService = {
    isAuthenticated() {
        return !!localStorage.getItem('kw_admin_token');
    },

    getAuthUser() {
        const user = localStorage.getItem('kw_admin_user');
        return user ? JSON.parse(user) : null;
    },

    getRole() {
        return this.getAuthUser()?.role || null;
    },

    isSuperAdmin() {
        return this.getRole() === 'super_admin';
    },

    canAccessMenu(menuKey) {
        if (this.isSuperAdmin()) return true;
        const user = this.getAuthUser();
        const menus = user?.effective_menus || user?.accessible_menus;
        if (Array.isArray(menus)) {
            return menus.includes(menuKey);
        }
        return false;
    },

    hasRole(allowedRoles) {
        if (this.isSuperAdmin()) return true;
        const currentRole = this.getRole();
        if (!currentRole) return false;
        if (Array.isArray(allowedRoles)) {
            return allowedRoles.includes(currentRole);
        }
        return currentRole === allowedRoles;
    },

    async getCaptcha() {
        const res = await apiClient.get('/admin/captcha');
        return res.data?.data || res.data;
    },

    async login(credentials) {
        const res = await apiClient.post('/admin/login', credentials);
        if (res.data?.data?.token) {
            localStorage.setItem('kw_admin_token', res.data.data.token);
            localStorage.setItem('kw_admin_user', JSON.stringify(res.data.data.user));
        }
        return res.data;
    },

    async logout() {
        try {
            await apiClient.post('/admin/logout');
        } catch (e) {
            // Ignore error if network fails or already expired
        } finally {
            localStorage.removeItem('kw_admin_token');
            localStorage.removeItem('kw_admin_user');
        }
    },

    async getMe() {
        const res = await apiClient.get('/admin/me');
        if (res.data?.data) {
            localStorage.setItem('kw_admin_user', JSON.stringify(res.data.data));
        }
        return res.data?.data;
    },

    getToken() {
        return localStorage.getItem('kw_admin_token') || '';
    },

    async updatePassword(data) {
        return (await apiClient.put('/admin/password', data)).data;
    },

    async uploadFile(file, type = 'image') {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        const res = await apiClient.post('/admin/upload', formData, {
            headers: {
                'Content-Type': undefined
            },
            timeout: 60000
        });
        return res.data;
    },

    async getDashboard() {
        const res = await apiClient.get('/admin/dashboard');
        return res.data?.data;
    },

    // Berita
    async getBerita() {
        const res = await apiClient.get('/admin/berita');
        return res.data?.data;
    },
    async saveBerita(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/berita/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/berita', data)).data;
    },
    async deleteBerita(id) {
        return (await apiClient.delete(`/admin/berita/${id}`)).data;
    },
    async toggleStatusBerita(id) {
        return (await apiClient.put(`/admin/berita/${id}/toggle-status`)).data;
    },
    async toggleRunningTextBerita(id) {
        return (await apiClient.put(`/admin/berita/${id}/toggle-running-text`)).data;
    },

    // Pengumuman
    async getPengumuman() {
        const res = await apiClient.get('/admin/pengumuman');
        return res.data?.data;
    },
    async savePengumuman(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/pengumuman/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/pengumuman', data)).data;
    },
    async deletePengumuman(id) {
        return (await apiClient.delete(`/admin/pengumuman/${id}`)).data;
    },

    // Dokumen Publik (PDF)
    async getDokumen(params = {}) {
        const res = await apiClient.get('/admin/dokumen', { params });
        return res.data;
    },
    async saveDokumen(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/dokumen/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/dokumen', data)).data;
    },
    async deleteDokumen(id) {
        return (await apiClient.delete(`/admin/dokumen/${id}`)).data;
    },
    async toggleDokumenStatus(id) {
        return (await apiClient.put(`/admin/dokumen/${id}/toggle-status`)).data;
    },

    // Pengajuan Pelayanan Online
    async getPengajuan(params = {}) {
        const res = await apiClient.get('/admin/pengajuan', { params });
        return res.data;
    },
    async getDetailPengajuan(id) {
        const res = await apiClient.get(`/admin/pengajuan/${id}`);
        return res.data?.data;
    },
    async updateStatusPengajuan(id, data) {
        return (await apiClient.put(`/admin/pengajuan/${id}/status`, data)).data;
    },
    async toggleAktifLayanan(id, aktif) {
        return (await apiClient.put(`/admin/layanan/${id}/toggle-aktif`, { aktif })).data;
    },
    async downloadDokumen(requestId, documentId, fileName, inline = false) {
        const response = await apiClient.get(`/admin/pengajuan/${requestId}/dokumen/${documentId}${inline ? '?inline=1' : ''}`, {
            responseType: 'blob'
        });
        const blob = new Blob([response.data], { type: response.headers['content-type'] });
        const url = window.URL.createObjectURL(blob);
        if (inline) {
            window.open(url, '_blank');
        } else {
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', fileName || `dokumen-${requestId}-${documentId}`);
            document.body.appendChild(link);
            link.click();
            link.parentNode.removeChild(link);
        }
        setTimeout(() => window.URL.revokeObjectURL(url), 10000);
    },

    // Layanan
    async getLayanan() {
        const res = await apiClient.get('/admin/layanan');
        return res.data?.data;
    },
    async saveLayanan(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/layanan/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/layanan', data)).data;
    },
    async deleteLayanan(id) {
        return (await apiClient.delete(`/admin/layanan/${id}`)).data;
    },

    // Galeri
    async getGaleri() {
        const res = await apiClient.get('/admin/galeri');
        return res.data?.data;
    },
    async saveGaleri(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/galeri/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/galeri', data)).data;
    },
    async deleteGaleri(id) {
        return (await apiClient.delete(`/admin/galeri/${id}`)).data;
    },

    // Profil & Aparatur
    async updateProfil(data) {
        return (await apiClient.put('/admin/profil', data)).data;
    },
    async savePerangkat(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/perangkat/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/perangkat', data)).data;
    },
    async deletePerangkat(id) {
        return (await apiClient.delete(`/admin/perangkat/${id}`)).data;
    },

    // Statistik & Lingkungan
    async updateStatistik(data) {
        return (await apiClient.put('/admin/statistik', data)).data;
    },
    async saveLingkungan(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/lingkungan/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/lingkungan', data)).data;
    },
    async deleteLingkungan(id) {
        return (await apiClient.delete(`/admin/lingkungan/${id}`)).data;
    },

    // Lembaga Kemasyarakatan (LKK)
    async getLembaga() {
        const res = await apiClient.get('/admin/lembaga');
        return res.data?.data;
    },
    async saveLembaga(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/lembaga/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/lembaga', data)).data;
    },
    async toggleAktifLembaga(id, aktif) {
        return (await apiClient.put(`/admin/lembaga/${id}/toggle-aktif`, { aktif })).data;
    },
    async deleteLembaga(id) {
        return (await apiClient.delete(`/admin/lembaga/${id}`)).data;
    },

    // Transparansi & Akuntabilitas Anggaran (APBD)
    async getTransparansi(params = {}) {
        const res = await apiClient.get('/admin/transparansi', { params });
        return res.data?.data;
    },
    async getTransparansiDetail(id) {
        const res = await apiClient.get(`/admin/transparansi/${id}`);
        return res.data?.data;
    },
    async saveTransparansi(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/transparansi/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/transparansi', data)).data;
    },
    async toggleStatusTransparansi(id, status) {
        return (await apiClient.put(`/admin/transparansi/${id}/toggle-status`, { status })).data;
    },
    async toggleAktifTransparansi(id, aktif) {
        return (await apiClient.put(`/admin/transparansi/${id}/toggle-aktif`, { aktif })).data;
    },
    async deleteTransparansi(id) {
        return (await apiClient.delete(`/admin/transparansi/${id}`)).data;
    },

    // Manajemen Akun Staf (Super Admin Only)
    async getStaff(params = {}) {
        const res = await apiClient.get('/admin/staff', { params });
        return res.data?.data;
    },
    async storeStaff(data) {
        return (await apiClient.post('/admin/staff', data)).data;
    },
    async updateStaff(id, data) {
        return (await apiClient.put(`/admin/staff/${id}`, data)).data;
    },
    async resetStaffPassword(id, data) {
        return (await apiClient.put(`/admin/staff/${id}/reset-password`, data)).data;
    },
    async deleteStaff(id) {
        return (await apiClient.delete(`/admin/staff/${id}`)).data;
    },

    // Master Kategori Terpusat
    async getMasterKategori(modul = '') {
        const params = modul ? { modul } : {};
        const res = await apiClient.get('/admin/kategori', { params });
        return res.data?.data || res.data || [];
    },
    async storeMasterKategori(data) {
        return (await apiClient.post('/admin/kategori', data)).data;
    },
    async updateMasterKategori(id, data) {
        return (await apiClient.put(`/admin/kategori/${id}`, data)).data;
    },
    async deleteMasterKategori(id) {
        return (await apiClient.delete(`/admin/kategori/${id}`)).data;
    },

    // Pemantauan Log Aktifitas (Super Admin Only)
    async getActivityLogs(params = {}) {
        const res = await apiClient.get('/admin/activity-logs', { params });
        return res.data;
    },
    async getActivityLogUsers() {
        const res = await apiClient.get('/admin/activity-logs/users');
        return res.data?.data || [];
    },

    // Kelola Agenda Kegiatan
    async getAgenda(params = {}) {
        const res = await apiClient.get('/admin/agenda', { params });
        return res.data;
    },
    async saveAgenda(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/agenda/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/agenda', data)).data;
    },
    async toggleAktifAgenda(id) {
        return (await apiClient.put(`/admin/agenda/${id}/toggle-aktif`)).data;
    },
    async deleteAgenda(id) {
        return (await apiClient.delete(`/admin/agenda/${id}`)).data;
    },

    // Kelola Setting System & Halaman Kustom
    async getHalamanKustom(params = {}) {
        const res = await apiClient.get('/admin/halaman-kustom', { params });
        return res.data;
    },
    async saveHalamanKustom(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/halaman-kustom/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/halaman-kustom', data)).data;
    },
    async deleteHalamanKustom(id) {
        return (await apiClient.delete(`/admin/halaman-kustom/${id}`)).data;
    },

    // Manajemen Penyimpanan & Berkas Orphan (Super Admin Only)
    async getStorageStats() {
        const res = await apiClient.get('/admin/storage/stats');
        return res.data?.data || res.data;
    },
    async cleanOrphanedStorage() {
        const res = await apiClient.post('/admin/storage/clean-orphans');
        return res.data;
    },

    // Maklumat Pelayanan
    async getMaklumatAdmin() {
        const res = await apiClient.get('/admin/maklumat-pelayanan');
        return res.data;
    },
    async saveMaklumatAdmin(data) {
        return (await apiClient.post('/admin/maklumat-pelayanan', data)).data;
    },
    async toggleAktifMaklumat(id) {
        return (await apiClient.put(`/admin/maklumat-pelayanan/${id}/toggle-aktif`)).data;
    },
    async deleteMaklumat(id) {
        return (await apiClient.delete(`/admin/maklumat-pelayanan/${id}`)).data;
    },

    // Survei Kepuasan Masyarakat (SKM)
    async getSurveiSkmAdmin() {
        const res = await apiClient.get('/admin/survei-skm');
        return res.data?.data || res.data;
    },
    async saveSurveiSkm(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/survei-skm/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/survei-skm', data)).data;
    },
    async toggleAktifSurveiSkm(id, aktif) {
        return (await apiClient.put(`/admin/survei-skm/${id}/toggle-aktif`, { aktif })).data;
    },
    async deleteSurveiSkm(id) {
        return (await apiClient.delete(`/admin/survei-skm/${id}`)).data;
    }
};

export default apiClient;
