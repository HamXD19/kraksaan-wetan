import axios from 'axios';
import { mockProfil, mockStatistik, mockLayanan, mockBerita, mockPengumuman, mockGaleri, mockLembaga, mockTransparansi } from './mockData';

const apiClient = axios.create({
    baseURL: '/api',
    timeout: 10000,
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

    async submitPengajuan(formData) {
        const res = await apiClient.post('/pelayanan/pengajuan', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return res.data;
    },

    async trackPengajuan(data) {
        const res = await apiClient.post('/pelayanan/tracking', data);
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

    async kirimKontak(data) {
        const res = await apiClient.post('/kontak', data);
        return res.data;
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
            console.warn('API /transparansi fallback to mock data:', e);
            return mockTransparansi;
        }
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

    async updatePassword(data) {
        return (await apiClient.put('/admin/password', data)).data;
    },

    async uploadFile(file, type = 'image') {
        const formData = new FormData();
        formData.append('file', file);
        formData.append('type', type);
        const res = await apiClient.post('/admin/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
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

    // Pesan / Aspirasi
    async getPesan() {
        const res = await apiClient.get('/admin/pesan');
        return res.data?.data;
    },
    async updateStatusPesan(id, status) {
        return (await apiClient.put(`/admin/pesan/${id}/status`, { status })).data;
    },
    async deletePesan(id) {
        return (await apiClient.delete(`/admin/pesan/${id}`)).data;
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

    // Transparansi & Akuntabilitas Anggaran
    async getTransparansi(params = {}) {
        const res = await apiClient.get('/admin/transparansi', { params });
        return res.data?.data;
    },
    async saveTransparansi(data, id = null) {
        if (id) {
            return (await apiClient.put(`/admin/transparansi/${id}`, data)).data;
        }
        return (await apiClient.post('/admin/transparansi', data)).data;
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
    }
};

export default apiClient;
