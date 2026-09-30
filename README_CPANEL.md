# Panduan Deployment & Hosting di cPanel
### Portal Digital Kelurahan Kraksaan Wetan (Laravel 11 + Vue 3 Vite)

Dokumen ini berisi panduan lengkap langkah demi langkah untuk melakukan proses hosting dan *deployment* aplikasi **Kelurahan Kraksaan Wetan** pada web hosting berbasis **cPanel**.

---

## 📋 Daftar Isi
1. [Kebutuhan Server (System Requirements)](#1-kebutuhan-server-system-requirements)
2. [Langkah 1: Persiapan File di Komputer Lokal](#langkah-1-persiapan-file-di-komputer-lokal)
3. [Langkah 2: Konfigurasi PHP di cPanel](#langkah-2-konfigurasi-php-di-cpanel)
4. [Langkah 3: Pembuatan Database MySQL di cPanel](#langkah-3-pembuatan-database-mysql-di-cpanel)
5. [Langkah 4: Upload File Proyek ke cPanel (Metode Direkomendasikan)](#langkah-4-upload-file-proyek-ke-cpanel-metode-direkomendasikan)
6. [Langkah 5: Konfigurasi File .env](#langkah-5-konfigurasi-file-env)
7. [Langkah 6: Menghubungkan Storage (Symlink Berkas & Dokumen)](#langkah-6-menghubungkan-storage-symlink-berkas--dokumen)
8. [Langkah 7: Migrasi Database & Optimasi Cache](#langkah-7-migrasi-database--optimasi-cache)
9. [Solusi Permasalahan Umum (Troubleshooting)](#solusi-permasalahan-umum-troubleshooting)

---

## 1. Kebutuhan Server (System Requirements)

Pastikan paket hosting cPanel Anda mendukung spesifikasi berikut:
- **PHP Version:** PHP 8.2 atau PHP 8.3+ (Dianjurkan PHP 8.2 atau 8.3)
- **Ekstensi PHP Wajib:**
  - `BCMath`
  - `Ctype`
  - `cURL`
  - `DOM` / `XML`
  - `Fileinfo`
  - `GD` atau `Imagick` (untuk resize gambar profil/galeri)
  - `JSON`
  - `Mbstring`
  - `OpenSSL`
  - `PCRE`
  - `PDO` & `pdo_mysql`
  - `Tokenizer`
  - `Zip`
- **Database:** MySQL 5.7+ atau MariaDB 10.3+
- **Fitur cPanel:** File Manager, MySQL Databases, phpMyAdmin, Terminal / SSH (opsional namun sangat disarankan).

---

## Langkah 1: Persiapan File di Komputer Lokal

Sebelum mengunggah ke cPanel, aset frontend Vue harus dikompilasi terlebih dahulu:

1. **Build Aset Frontend (Vue 3 + Tailwind CSS)**:
   Buka terminal di komputer lokal Anda dan jalankan:
   ```bash
   npm install
   npm run build
   ```
   *Perintah ini akan membuat folder `public/build` yang berisi file CSS, JS, dan manifest.*

2. **Pastikan Dependensi PHP Terinstal**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Ekspor Database Lokal (Opsional jika ingin menyertakan data awal)**:
   Ekspor database lokal Anda ke dalam file `.sql` melalui phpMyAdmin lokal atau perintah mysqldump.

4. **Kompresi File Proyek Menjadi ZIP**:
   Pilih seluruh file proyek dan kompres menjadi `kraksaan_wetan.zip`.  
   **PENTING: JANGAN** menyertakan folder berikut saat membuat file zip:
   - `node_modules/` (tidak dibutuhkan di server hosting)
   - `.git/` & `.github/`
   - `tests/`

---

## Langkah 2: Konfigurasi PHP di cPanel

1. Masuk ke dashboard **cPanel**.
2. Cari dan klik menu **Select PHP Version** (atau **MultiPHP Manager**).
3. Pilih domain Anda, lalu set versi PHP ke **PHP 8.2** atau **PHP 8.3**.
4. Masuk ke tab **Extensions**, pastikan ekstensi berikut tercentang aktif:
   - `pdo_mysql`, `mbstring`, `fileinfo`, `gd`, `zip`, `openssl`, `curl`, `xml`, `bcmath`.
5. Masuk ke tab **Options**, sesuaikan batas konfigurasi:
   - `upload_max_filesize` = **64M** (atau minimal 32M untuk upload PDF dokumen/galeri)
   - `post_max_size` = **64M**
   - `memory_limit` = **256M** (atau 512M)
   - `max_execution_time` = **300**

---

## Langkah 3: Pembuatan Database MySQL di cPanel

1. Di cPanel, buka menu **MySQL® Databases**.
2. **Buat Database Baru**:
   - Contoh: `username_kraksaan`
   - Klik *Create Database*.
3. **Buat Pengguna Database (MySQL User)**:
   - Contoh: `username_dbadmin`
   - Masukkan password yang kuat dan catat password tersebut.
   - Klik *Create User*.
4. **Hubungkan User ke Database**:
   - Pada bagian *Add User to Database*, pilih user dan database yang baru saja dibuat.
   - Klik *Add*.
   - Centang opsi **ALL PRIVILEGES**.
   - Klik *Make Changes*.
5. **Import Data Awal**:
   - Kembali ke cPanel, buka menu **phpMyAdmin**.
   - Pilih database `username_kraksaan`.
   - Klik tab **Import**, pilih file `.sql` dari komputer lokal Anda, lalu klik **Import**.

---

## Langkah 4: Upload File Proyek ke cPanel (Metode Direkomendasikan)

Untuk keamanan standar Laravel, struktur file inti sistem diletakkan di **luar** folder `public_html` agar file sensitif (seperti `.env`) tidak dapat diakses secara publik.

Struktur folder yang akan kita gunakan:
```text
/home/username/
├── kraksaan_core/            <-- Semua file Laravel inti disimpan di sini
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── vendor/
│   ├── .env
│   └── artisan
│
└── public_html/              <-- Hanya isi dari folder 'public' Laravel
    ├── build/                <-- Hasil dari 'npm run build'
    ├── images/
    ├── storage/              <-- Symlink ke folder storage/app/public
    ├── .htaccess
    ├── favicon.ico
    ├── index.php
    └── robots.txt
```

### Langkah Praktis:
1. Buka **File Manager** di cPanel.
2. Di folder root akun Anda (`/home/username/`), buat folder baru bernama:
   `kraksaan_core`
3. Masuk ke folder `kraksaan_core`, klik **Upload**, lalu unggah file `kraksaan_wetan.zip`.
4. Setelah selesai, klik kanan file `kraksaan_wetan.zip` lalu pilih **Extract**.
5. Masuk ke folder `kraksaan_core/public/`:
   - Pilih semua file & folder di dalam `public/` (`build`, `images`, `index.php`, `.htaccess`, `favicon.ico`, `robots.txt`, dll.).
   - Klik tombol **Move** (Pindahkan) ke: `/public_html/`.
6. Hapus folder `kraksaan_core/public/` yang sudah kosong.

### Edit File `/public_html/index.php`:
Buka dan edit file `/public_html/index.php` melalui File Manager cPanel. Sesuaikan baris `require` agar mengarah ke folder `kraksaan_core`:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Maintenance Mode
if (file_exists($maintenance = __DIR__.'/../kraksaan_core/storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Composer Autoloader
require __DIR__.'/../kraksaan_core/vendor/autoload.php';

// 3. Bootstrap Laravel Application
/** @var Application $app */
$app = require_once __DIR__.'/../kraksaan_core/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

---

## Langkah 5: Konfigurasi File .env

1. Di File Manager, masuk ke folder `/home/username/kraksaan_core/`.
2. Jika file `.env` belum terlihat, klik tombol **Settings** di pojok kanan atas File Manager dan centang **Show Hidden Files (dotfiles)**.
3. Edit file `.env` dan sesuaikan dengan data produksi:

```dotenv
APP_NAME="Kelurahan Kraksaan Wetan"
APP_ENV=production
APP_KEY=base64:PASTE_APP_KEY_ANDA_DI_SINI
APP_DEBUG=false
APP_URL=https://namadomainanda.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# Konfigurasi Database cPanel
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=username_kraksaan
DB_USERNAME=username_dbadmin
DB_PASSWORD=PasswordDatabaseAndaYangKuat

# Konfigurasi Storage & Session
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=public
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database
```

> **Catatan:** Pastikan `APP_DEBUG=false` untuk keamanan website di lingkungan live.

---

## Langkah 6: Menghubungkan Storage (Symlink Berkas & Dokumen)

Agar foto galeri, logo kelurahan, serta berkas dokumen publik (PDF) dapat diunduh oleh pengunjung, folder storage harus terhubung ke `public_html`.

### Cara A (Menggunakan cPanel Terminal / SSH - Paling Cepat):
Buka menu **Terminal** di cPanel, lalu jalankan:
```bash
ln -s /home/username/kraksaan_core/storage/app/public /home/username/public_html/storage
```

### Cara B (Jika Terminal cPanel Tidak Aktif / Dinonaktifkan Hosting):
1. Buat file baru bernama `symlink.php` di dalam folder `/public_html/`.
2. Isi file tersebut dengan kode berikut:
   ```php
   <?php
   $targetFolder = $_SERVER['DOCUMENT_ROOT'] . '/../kraksaan_core/storage/app/public';
   $linkFolder = $_SERVER['DOCUMENT_ROOT'] . '/storage';
   
   if (symlink($targetFolder, $linkFolder)) {
       echo "Berhasil! Storage symlink berhasil dibuat.";
   } else {
       echo "Gagal membuat symlink. Periksa permission folder.";
   }
   ```
3. Buka browser dan akses alamat: `https://namadomainanda.com/symlink.php`.
4. Setelah muncul pesan *Berhasil!*, **segera hapus** file `symlink.php` tersebut demi keamanan.

---

## Langkah 7: Migrasi Database & Optimasi Cache

### Jika Memiliki Akses Terminal di cPanel:
Masuk ke direktori core proyek:
```bash
cd /home/username/kraksaan_core
```

Jalankan perintah migrasi dan optimasi:
```bash
# Migrasi tabel dan data awal (jika belum import manual via phpMyAdmin)
php artisan migrate --seed --force

# Optimasi performa produksi
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Mengatur Izin Folder (File Permissions):
Pastikan folder cache dan penyimpanan memiliki izin tulis (*writable*):
- Folder `kraksaan_core/storage/` -> Permission **775** atau **755**
- Folder `kraksaan_core/bootstrap/cache/` -> Permission **775** atau **755**
- File biasa -> Permission **644**
- Folder biasa -> Permission **755**

---

## 8. Konfigurasi Cron Job (Opsional untuk Jadwal Otomatis)

Jika Anda ingin menjalankan fitur terjadwal Laravel (seperti pembersihan token kadaluarsa atau log aktivitas otomatis):
1. Buka menu **Cron Jobs** di cPanel.
2. Tambahkan cron job baru setiap menit (`* * * * *`):
   ```bash
   /usr/local/bin/php /home/username/kraksaan_core/artisan schedule:run >> /dev/null 2>&1
   ```
   *(Sesuaikan path binary PHP sesuai hosting Anda, misal `/usr/bin/php` atau `/usr/local/bin/ea-php82`).*

---

## Solusi Permasalahan Umum (Troubleshooting)

### 1. Error 500 (Internal Server Error)
- Buka file log error Laravel di: `/home/username/kraksaan_core/storage/logs/laravel.log`.
- Periksa versi PHP di cPanel (pastikan minimal PHP 8.2).
- Periksa permission folder `storage/` dan `bootstrap/cache/` (harus `775` atau `755`).
- Periksa apakah path file di `/public_html/index.php` sudah benar mengarah ke `kraksaan_core`.

### 2. Tampilan Halaman Kosong (Blank Page) atau CSS / JS Tidak Termuat
- Pastikan folder `public/build/` dari komputer lokal sudah ikut terunggah ke `/public_html/build/`.
- Periksa nilai `APP_URL` di file `.env`, pastikan sudah sesuai protokol (`https://` atau `http://`) dan nama domain.

### 3. File Gambar atau Dokumen PDF Tidak Ditemukan (404 Not Found)
- Pastikan langkah symlink di **Langkah 6** sudah dijalankan dengan benar.
- Pastikan di dalam `/public_html/` terdapat shortcut folder bernama `storage` yang mengarah ke `storage/app/public`.

### 4. Halaman Vue Memberikan Pesan 404 Saat Di-refresh (Page Reload)
Pastikan file `/public_html/.htaccess` memiliki konfigurasi URL Rewrite standar berikut agar seluruh rute ditangani oleh Vue Router:
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

---

*Portal Digital Kelurahan Kraksaan Wetan - Pemerintah Kabupaten Probolinggo*
