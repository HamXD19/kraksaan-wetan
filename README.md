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

## 📖 Buku Panduan Penggunaan (Manual Book)

Untuk panduan lengkap pengoperasian website bagi warga masyarakat maupun staf administrator kelurahan, silakan baca:

👉 **[BUKU PANDUAN PENGGUNAAN LENGKAP (MANUAL_BOOK.md)](MANUAL_BOOK.md)**

Panduan tersebut mencakup:
* **Panduan Pengunjung (Portal Publik):** Navigasi suara aksesibilitas, profil kelurahan, pengajuan surat online, transparansi anggaran, cek pengumuman & berita, serta tampilan mobile smartphone.
* **Panduan Administrator (CMS):** Prosedur login, kelola logo & hero banner, berita & foto cropper, pengumuman & lampiran PDF, agenda, galeri, aparatur & LKK, master kategori, serta manajemen staf & audit log.
* **SOP Konten & Media:** Ketentuan resolusi gambar, rasio aspek, kompresi otomatis, dan standar dokumen PDF.
* **Troubleshooting:** Pembersihan cache browser, backup database phpMyAdmin, dan solusi kendala teknis umum.

---

## 🌐 Panduan Hosting di cPanel

Untuk panduan lengkap cara melakukan deployment dan hosting aplikasi ini ke web hosting berbasis **cPanel**, silakan baca dokumen panduan khusus:

👉 **[PANDUAN LENGKAP HOSTING DI CPANEL (README_CPANEL.md)](README_CPANEL.md)**

Panduan tersebut mencakup:
* Kebutuhan versi PHP dan ekstensi wajib cPanel.
* Struktur pemisahan folder core & public demi keamanan maksimal.
* Pengaturan file `.env` untuk production.
* Cara menghubungkan storage (symlink) untuk berkas dan dokumen.
* Troubleshooting error 500, aset Vite CSS/JS, dan routing SPA.

