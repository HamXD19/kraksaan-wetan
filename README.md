# Portal Digital Kelurahan Kraksaan Wetan

Aplikasi sistem informasi publik dan manajemen pelayanan administrasi warga Kelurahan Kraksaan Wetan, Kabupaten Probolinggo. Project ini dibangun menggunakan arsitektur **Laravel** sebagai backend API dan **Vue.js** sebagai frontend client.

## 🚀 Fitur Utama
* **Dashboard Admin:** Manajemen data berita, pengumuman, galeri, dan staff kelurahan.
* **Pelayanan Publik:** Pengajuan surat permohonan (*service request*) oleh warga secara online.
* **Transparansi Anggaran:** Visualisasi data realisasi anggaran kelurahan.
* **Statistik Kependudukan:** Grafik data demografi dan lingkungan kelurahan.

## 🛠️ Tech Stack
* **Backend:** Laravel 11, PHP 8.2+
* **Frontend:** Vue.js 3, Vue Router, Axios, Tailwind CSS
* **Database:** MySQL / PostgreSQL

## 💻 Cara Instalasi Lokal

### 1. Persiapan Awal
Clone repository ini ke komputer lokal Anda:
```bash
git clone https://github.com
cd kraksaan-wetan
```

### 2. Konfigurasi Backend (Laravel)
Instal dependensi PHP menggunakan Composer:
```bash
composer install
```

Salin file konfigurasi environment dan sesuaikan pengaturan database Anda di file `.env`:
```bash
cp .env.example .env
php artisan key:generate
```

Jalankan migrasi database beserta data awal (seeder):
```bash
php artisan migrate --seed
```

Nyalakan server backend:
```bash
php artisan serve
```

### 3. Konfigurasi Frontend (Vue.js)
Instal dependensi JavaScript menggunakan NPM:
```bash
npm install
```

Jalankan server development frontend:
```bash
npm run dev
```

Aplikasi sekarang dapat diakses melalui browser di alamat yang tertera pada terminal Anda.
