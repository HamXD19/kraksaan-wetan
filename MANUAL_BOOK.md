# BUKU PANDUAN PENGGUNAAN (MANUAL BOOK)
# WEBSITE RESMI PEMERINTAH KELURAHAN KRAKSAAN WETAN
**Kecamatan Kraksaan, Kabupaten Probolinggo, Provinsi Jawa Timur**

---

## DAFTAR ISI
1. [BAB I: PENDAHULUAN & GAMBARAN UMUM](#bab-i-pendahuluan--gambaran-umum)
   - 1.1 Latar Belakang & Tujuan
   - 1.2 Arsitektur Sistem & Spesifikasi Teknis
   - 1.3 Alamat Akses (URL)
   - 1.4 Tingkatan Hak Akses (User Roles)
2. [BAB II: PANDUAN PENGUNJUNG (PORTAL PUBLIK WARGA)](#bab-ii-panduan-pengunjung-portal-publik-warga)
   - 2.1 Halaman Beranda (Homepage)
   - 2.2 Fitur Suara Navigasi & Aksesibilitas
   - 2.3 Menu Profil Kelurahan
   - 2.4 Menu Pemerintahan & Lembaga Kemasyarakatan (LKK)
   - 2.5 Menu Layanan Masyarakat Terpadu
   - 2.6 Maklumat Pelayanan & Survei Kepuasan Masyarakat (SKM)
   - 2.7 Transparansi Anggaran (APBD)
   - 2.8 Berita & Kabar Terkini
   - 2.9 Pengumuman & Himbauan Resmi
   - 2.10 Agenda Kegiatan Kelurahan
   - 2.11 Galeri Dokumentasi Foto
   - 2.12 Unduh Dokumen Publik (PDF)
   - 2.13 Kontak, Lokasi Peta, & Pengaduan Warga
   - 2.14 Panduan Penggunaan di Smartphone / Layar Ponsel
3. [BAB III: PANDUAN PANEL ADMINISTRATOR (CMS KELURAHAN)](#bab-iii-panduan-panel-administrator-cms-kelurahan)
   - 3.1 Prosedur Masuk (Login) Administrator
   - 3.2 Halaman Dashboard Admin
   - 3.3 Kelola Pengaturan Sistem & Identitas Branding (Logo & Hero)
   - 3.4 Kelola Profil Kelurahan & Aparatur
   - 3.5 Kelola Layanan Masyarakat & Maklumat Pelayanan
   - 3.6 Kelola Berita & Publikasi Kegiatan
   - 3.7 Kelola Pengumuman Resmi & Berkas PDF
   - 3.8 Kelola Agenda & Kalender Kegiatan
   - 3.9 Kelola Galeri Dokumentasi Foto
   - 3.10 Kelola Dokumen Publik (PDF)
   - 3.11 Kelola Lembaga Kemasyarakatan (LKK / RT / RW)
   - 3.12 Kelola Statistik Kependudukan & Wilayah
   - 3.13 Kelola Transparansi Anggaran (APBD)
   - 3.14 Kelola Master Kategori Konten
   - 3.15 Kelola Akun Staf Pengguna (Khusus Super Admin)
   - 3.16 Audit Log Aktivitas Akun (Khusus Super Admin)
4. [BAB IV: STANDAR OPERASIONAL & PEDOMAN KONTEN](#bab-iv-standar-operasional--pedoman-konten)
   - 4.1 Standar Format & Ukuran Gambar
   - 4.2 Standar Format Dokumen Lampiran (PDF)
   - 4.3 Kaidah Penulisan Informasi Publik
5. [BAB V: PEMELIHARAAN, CADANGAN DATA, & TROUBLESHOOTING](#bab-v-pemeliharaan-cadangan-data--troubleshooting)
   - 5.1 Mengatasi Gambar/Pratinjau Tidak Berubah (Browser Cache)
   - 5.2 Pencadangan Data (Database Backup)
   - 5.3 Pertanyaan yang Sering Diajukan (FAQ)

---

## BAB I: PENDAHULUAN & GAMBARAN UMUM

### 1.1 Latar Belakang & Tujuan
Website Resmi Pemerintah Kelurahan Kraksaan Wetan dibangun sebagai gerbang transformasi digital pelayanan publik, pusat keterbukaan informasi publik (KIP), dan media interaksi dua arah antara aparatur pemerintah kelurahan dengan masyarakat.

**Tujuan Pembangunan Sistem:**
1. **Transparansi:** Menyajikan informasi akuntabilitas kinerja, anggaran APBD/Kelurahan, dan program pembangunan kepada masyarakat secara terbuka.
2. **Efisiensi Pelayanan:** Mempermudah warga dalam mengetahui persyaratan permohonan surat-menyurat dan dokumen kependudukan tanpa pungutan liar (Rp 0).
3. **Akuntabilitas:** Menyediakan sarana pemantauan Indeks Kepuasan Masyarakat (IKM) dan jalur aspirasi/pengaduan resmi.
4. **Digitalisasi Informasi:** Mendokumentasikan potensi lokal, berita pembangunan, agenda kedinasan, dan data statistik demografi secara terpadu.

### 1.2 Arsitektur Sistem & Spesifikasi Teknis
- **Backend API:** Laravel 11 (PHP 8.2+) dengan arsitektur RESTful JSON API.
- **Frontend Client:** Vue.js 3 Single Page Application (SPA), Vue Router, Tailwind CSS, dan Lucide Icons.
- **Penyimpanan Berkas:** Hybrid Storage (Local Storage Disk & Base64 Resilient Processing dengan auto-kompresi gambar).
- **Keamanan:** Autentikasi berbasis Token/Session terenkripsi, proteksi CSRF, sanitasi HTML XSS pada Rich Text Editor, dan Role-Based Access Control (RBAC).

### 1.3 Alamat Akses (URL)
- **Portal Publik (Masyarakat):** `https://nama-domain-anda.com/`
- **Panel Administrator (Pengelola):** `https://nama-domain-anda.com/admin`
- **Halaman Login Admin:** `https://nama-domain-anda.com/admin/login`

### 1.4 Tingkatan Hak Akses (User Roles)
Sistem memiliki 4 (empat) tingkatan peran hak akses pada panel administrator:
1. **Super Admin:** Memiliki kendali penuh ke seluruh sistem, termasuk pengaturan branding kelurahan, manajemen akun staf pengguna, pengaturan menu navigasi, dan log aktivitas audit.
2. **Staf Konten & Humas:** Berwenang mengelola warta berita, pengumuman, agenda kegiatan, galeri foto dokumentasi, dan master kategori.
3. **Staf Pelayanan:** Berwenang mengelola daftar standar operasional prosedur (SOP) layanan masyarakat, maklumat pelayanan, dan survei kepuasan masyarakat (SKM).
4. **Staf Administrasi & Lembaga:** Berwenang mengelola data statistik kependudukan, dokumen publik (PDF), transparansi anggaran, dan lembaga kemasyarakatan (LKK / RT / RW).

---

## BAB II: PANDUAN PENGUNJUNG (PORTAL PUBLIK WARGA)

Portal publik dirancang dengan tema resmi pemerintahan (Hijau Zamrud & Aksen Emas) serta tata letak modern yang bersih dan ramah untuk semua kalangan.

### 2.1 Halaman Beranda (Homepage)
Halaman Beranda (`/`) memuat ringkasan informasi strategis kelurahan yang tersusun secara hierarkis:
1. **Top Bar Kedinasan:** Menampilkan jam operasional kantor kelurahan, nomor telepon resmi, dan tombol pengatur efek suara.
2. **Header Identitas:** Lambang resmi Pemerintah Kabupaten Probolinggo dan nama Kelurahan Kraksaan Wetan.
3. **Hero Banner Interaktif:** Latar belakang lanskap panorama Gunung Bromo khas Probolinggo dengan kontur melengkung modern dan tombol aksi cepat (*Call to Action*).
4. **Layanan Cepat Warga:** Menampilkan kartu panduan permohonan surat kependudukan utama (SKCK, Surat Keterangan Usaha, Keterangan Tidak Mampu, Pindah Datang, dsb.).
5. **Maklumat Pelayanan & Nilai IKM:** Ikrar komitmen bebas pungli aparatur kelurahan, motto pelayanan, nomor SK maklumat, nilai Indeks Kepuasan Masyarakat, dan pratinjau piagam beresolusi tinggi.
6. **Profil & Sambutan Lurah:** Sambutan resmi Lurah Kraksaan Wetan, foto dinas, NIP, serta gambaran kewilayahan.
7. **Statistik Wilayah & Demografi:** Indikator agregat jumlah penduduk, rasio gender, kepala keluarga, jumlah RT/RW, luas wilayah, dan kepadatan penduduk.
8. **Warta Kraksaan (Berita Terkini):** 3 (tiga) berita dan artikel kegiatan terbaru kelurahan.
9. **Pengumuman Resmi:** Surat edaran kedinasan, jadwal pelayanan keliling, atau agenda penting warga.
10. **Galeri Dokumentasi:** Sorotan foto giat kemasyarakatan dan pembangunan infrastruktur.
11. **Peta Wilayah:** Peta interaktif Google Maps kantor kelurahan beserta koordinat presisi dan navigasi rute.
12. **Footer Kedinasan:** Alamat lengkap, kontak resmi kantor, tautan portal Pemkab Probolinggo, SP4N-LAPOR!, dan WhatsApp Halo SAE.

### 2.2 Fitur Suara Navigasi & Aksesibilitas
Website dilengkapi fitur **Efek Suara Navigasi & Text-to-Speech (TTS)** ramah disabilitas:
- **Cara Mengaktifkan/Mematikan:** Klik tombol **"Suara: On / Off"** yang terdapat di top bar bagian atas atau di dalam menu navigasi mobile.
- **Fungsi:** Saat aktif, kursor yang melintasi tombol atau tautan penting akan memberikan indikator suara yang jelas untuk memudahkan warga penyandang tunanetra atau lansia dalam menjelajahi website.

### 2.3 Menu Profil Kelurahan
Dapat diakses melalui menu navigasi **PROFIL**:
- **Tentang Kelurahan (`/profil`):** Sejarah ringkas, letak geografis, batas-batas wilayah, dan visi kemasyarakatan.
- **Sejarah Kelurahan (`/profil/sejarah`):** Kilas balik asal-usul nama Kraksaan Wetan dan transformasinya dari masa ke masa.
- **Visi dan Misi (`/profil/visi-misi`):** Visi pembangunan jangka menengah dan misi strategis pelayanan kelurahan.
- **Struktur Organisasi (`/profil/struktur-organisasi`):** Bagan hierarki susunan organisasi perangkat kelurahan.

### 2.4 Menu Pemerintahan & Lembaga Kemasyarakatan (LKK)
Dapat diakses melalui menu **PEMERINTAHAN**:
- **Pemerintahan Kelurahan (`/pemerintahan`):** Susunan aparatur pejabat kelurahan mulai dari Lurah, Sekretaris Kelurahan, hingga Kepala Seksi (Kasi Pemerintahan, Kasi Ekobang, Kasi Trantib, dan Staf).
- **Lembaga Kemasyarakatan (`/lembaga`):** Direktori kelembagaan mitra pemerintah kelurahan, seperti Lembaga Pemberdayaan Masyarakat (LPM), Tim Penggerak PKK, Karang Taruna, RT, dan RW.

### 2.5 Menu Layanan Masyarakat Terpadu
Halaman **`/pelayanan`** menyajikan ensiklopedia lengkap pengurusan administrasi warga:
1. **Pencarian Layanan:** Ketikkan nama layanan (misal: "KTP", "Usaha", "Kematian", "Domisili").
2. **Kategori Layanan:** Filter berdasarkan Administrasi Kependudukan, Izin Usaha/Ekonomi, atau Kesejahteraan Sosial.
3. **Detail Informasi Tiap Layanan:**
   - Persyaratan dokumen wajib (KTP, KK, Pengantar RT/RW, surat pendukung).
   - Estimasi jangka waktu penyelesaian dokumen (misal: 15 Menit - 1 Hari Kerja).
   - Biaya tarif resmi: **Rp 0 (Gratis - Bebas Pungutan Liar)**.
   - Alur diagram langkah pengurusan dari loket pelayanan hingga surat ditandatangani.

### 2.6 Maklumat Pelayanan & Survei Kepuasan Masyarakat (SKM)
Halaman **`/survei-skm`** memaparkan transparansi mutu pelayanan:
- **Piagam Maklumat:** Janji aparatur kelurahan untuk memberikan layanan sesuai standar operasional yang ditetapkan. Pengunjung dapat mengeklik tombol **"Buka Piagam HD"** untuk memperbesar plakat sertifikat secara penuh.
- **Hasil Indeks Kepuasan Masyarakat (IKM):** Nilai evaluasi berkala berkisar antara Sangat Baik / Baik (A/B), jumlah responden survei, serta rincian mutu layanan per unsur (kesesuaian persyaratan, kedisiplinan petugas, kecepatan waktu, dan kejelasan alur).

### 2.7 Transparansi Anggaran (APBD)
Halaman **`/transparansi`** menyajikan akuntabilitas keuangan kelurahan:
- Grafik alokasi pendapatan dan belanja tahun anggaran berjalan.
- Persentase realisasi belanja fisik, sarana prasarana lingkungan, dan pemberdayaan masyarakat.
- Pengunjung dapat melihat arsip rincian transparansi anggaran dari tahun-tahun sebelumnya.

### 2.8 Berita & Kabar Terkini
Halaman **`/berita`** memuat warta kegiatan kedinasan dan giat warga:
- Terdapat fitur pencarian teks dan filter berdasarkan kategori (Pemerintahan, Pembangunan, Kegiatan Warga, Sosial, Prestasi).
- Tiap artikel dilengkapi tanggal terbit, nama penulis, jumlah pembaca (*view counter*), dan tombol bagikan (*share*).

### 2.9 Pengumuman & Himbauan Resmi
Tersedia di tab pengumuman pada halaman **`/berita?tab=pengumuman`**:
- Pengumuman mendesak/prioritas ditandai dengan badge merah **"Penting"**.
- Warga dapat membaca rincian pengumuman dan mengunduh berkas edaran resmi berformat PDF melalui tombol **"Unduh PDF"**.

### 2.10 Agenda Kegiatan Kelurahan
Halaman **`/agenda`** menyajikan jadwal kegiatan masyarakat dan aparatur:
- Waktu pelaksanaan (tanggal & jam).
- Lokasi kegiatan (Balai Kelurahan, Balai RW, Lapangan, dll.).
- Penyelenggara kegiatan dan agenda pembahasan.

### 2.11 Galeri Dokumentasi Foto
Halaman **`/galeri`** mendokumentasikan momen kebersamaan warga dan pembangunan:
- Album foto dikelompokkan berdasarkan kategori dan tanggal pelaksanaan.
- Mengeklik foto akan membuka penampil gambar berukuran besar (*lightbox view*) dengan keterangan kegiatan yang informatif.

### 2.12 Unduh Dokumen Publik (PDF)
Halaman **`/dokumen`** merupakan perpustakaan digital berkas resmi kelurahan:
- Berkas Musrenbang Kelurahan (Rencana Kerja Pembangunan).
- Surat Keputusan (SK) Kelembagaan RT/RW dan LKK.
- Panduan Fasilitas UMKM & Wirausaha Warga.
- Formulir blangko permohonan kependudukan.
- Seluruh dokumen dapat diunduh secara langsung tanpa perlu membuat akun (*login*).

### 2.13 Kontak, Lokasi Peta, & Pengaduan Warga
Halaman **`/kontak`** menyediakan kanal interaksi terpadu:
- **Formulir Pengaduan / Aspirasi:** Warga dapat memasukkan Nama Lengkap, Nomor HP/WhatsApp, Subjek, dan Pesan Aspirasi.
- **WhatsApp Halo SAE:** Tautan langsung ke nomor WhatsApp respons cepat kelurahan untuk konsultasi instan.
- **Portal SP4N-LAPOR!:** Tautan ke kanal pengaduan nasional Republik Indonesia.
- **Peta Navigasi:** Petunjuk arah Google Maps menuju Kantor Kelurahan Kraksaan Wetan.

### 2.14 Panduan Penggunaan di Smartphone / Layar Ponsel
Website dirancang dengan standar mobile terbaik (mengadopsi pola modern AMIK Taruna):
- **Floating Card Menu:** Menekan tombol menu tiga garis (hamburger) di pojok kanan atas akan memunculkan menu kartu melayang dengan latar kaca blur yang tidak menutupi seluruh layar.
- **Tombol Kapsul Lebar:** Seluruh tombol aksi utama (*Layanan Masyarakat*, *Baca Berita*, *Unduh Dokumen*) berbentuk kapsul bulat penuh (*pill-rounded*) yang mudah ditekan dengan ibu jari tangan kanan maupun kiri.
- **Bebas Geser Horizontal:** Halaman terkunci rapat secara vertikal (`overflow-x: hidden`) sehingga tidak akan goyang atau bergeser ke kanan dan ke kiri saat digulir (*scrolling*).

---

## BAB III: PANDUAN PANEL ADMINISTRATOR (CMS KELURAHAN)

Panel Administrator merupakan pusat pengelolaan seluruh data, naskah, foto, dan dokumen yang tayang di portal publik.

### 3.1 Prosedur Masuk (Login) Administrator
1. Buka browser dan kunjungi alamat: `https://nama-domain-anda.com/admin/login` (atau klik tombol **LOGIN** di pojok kanan atas navigasi website).
2. Masukkan **Alamat Email Resmi** dan **Kata Sandi (Password)** Anda.
3. Klik tombol **"Masuk ke Panel"**.
4. Jika data benar, Anda akan diarahkan ke Dashboard Administrator.
5. *Catatan Keamanan:* Jangan pernah membagikan password Anda kepada pihak lain. Selalu lakukan **Logout** setelah selesai mengelola website pada komputer bersama.

### 3.2 Halaman Dashboard Admin
Menampilkan ikhtisar operasional terkini:
- Jumlah total Berita, Pengumuman, Layanan, Dokumen, dan Staf.
- Grafik ringkasan kunjungan dan log aktivitas login terakhir.
- Akses pintas cepat (*Quick Action*) untuk membuat berita baru atau menambah pengumuman mendesak.

### 3.3 Kelola Pengaturan Sistem & Identitas Branding (Logo & Hero)
*Menu: Pengaturan Sistem (`/admin/system-settings`)* — *(Khusus Super Admin)*
Menu ini digunakan untuk mengatur visual brand dan identitas resmi website:
1. **Logo Kelurahan & Lambang Daerah:**
   - Klik area **"Unggah Logo Baru"**.
   - Pilih berkas logo (format `.png` transparan sangat direkomendasikan).
   - Pratinjau logo akan langsung muncul secara instan.
   - Klik **"Simpan Pengaturan"**. Logo akan langsung terpasang di navbar atas, footer bawah, dan favicon website.
2. **Hero Banner Gunung Bromo:**
   - Digunakan untuk mengganti gambar latar panorama puncak Gunung Bromo pada seksi teratas beranda.
   - Pilih berkas panorama berorientasi lanskap horizontal.
   - Klik **"Simpan Pengaturan"**.
3. **Pengaturan Kontak & Jam Operasional:**
   - Perbarui nomor telepon kantor, email resmi kedinasan, nomor WhatsApp Halo SAE, dan jam pelayanan kerja.

### 3.4 Kelola Profil Kelurahan & Aparatur
*Menu: Profil Kelurahan (`/admin/profil`)*
1. **Informasi Dasar:** Mengubah deskripsi singkat kelurahan, kecamatan, kabupaten, dan kode wilayah.
2. **Visi & Misi:** Mengubah butir-butir visi pembangunan dan uraian misi pelayanan.
3. **Sejarah:** Menulis atau memperbarui narasi sejarah berdirinya Kelurahan Kraksaan Wetan.
4. **Sambutan & Foto Lurah:**
   - Masukkan Nama Lengkap Lurah beserta gelar dan NIP.
   - Ketikkan naskah sambutan resmi Lurah untuk warga.
   - Unggah foto dinas resmi Lurah. Foto akan otomatis dikompres dan ditampilkan dalam bingkai beranda.
5. **Daftar Aparatur & Staf:**
   - Tambah/edit nama perangkat kelurahan, jabatan kedinasan (Sekkel, Kasi, Staf), dan foto profil.

### 3.5 Kelola Layanan Masyarakat & Maklumat Pelayanan
*Menu: Layanan Masyarakat (`/admin/layanan`)*
1. **Menambah Layanan Baru:**
   - Klik tombol **"+ Tambah Layanan"**.
   - Masukkan *Nama Layanan* (misal: "Surat Keterangan Usaha").
   - Pilih *Kategori Layanan* dan *Ikon* yang sesuai.
   - Masukkan *Persyaratan Berkas* (KTP, KK, Pengantar RT/RW).
   - Tetapkan *Estimasi Waktu* (misal: "15 Menit") dan *Biaya* (secara default "Rp 0 / Gratis").
   - Masukkan langkah alur pengurusan.
   - Klik **"Simpan Data"**.
2. **Mengelola Maklumat Pelayanan & SKM:**
   - Buka tab/seksi **Maklumat & SKM**.
   - Masukkan Nomor SK Maklumat Pelayanan.
   - Tuliskan Motto Pelayanan aparatur.
   - Unggah berkas gambar/foto **Piagam Maklumat Pelayanan HD** (piagam yang telah ditandatangani dan dicap resmi).
   - Masukkan nilai skor evaluasi IKM dan predikat mutu layanan (Sangat Baik / A).
   - Klik **"Simpan Maklumat"**.

### 3.6 Kelola Berita & Publikasi Kegiatan
*Menu: Kelola Berita (`/admin/berita`)*
1. **Menulis Berita Baru:**
   - Klik tombol **"+ Tulis Berita Baru"**.
   - Masukkan *Judul Berita* yang menarik dan sesuai fakta kedinasan.
   - Pilih *Kategori Berita* (Pemerintahan, Pembangunan, Kegiatan Warga, dll.).
   - Unggah *Foto Utama*:
     - Sistem menyediakan fitur **Pemotong Gambar Interaktif (Image Cropper)** berasio 16:9 agar gambar presisi dan tidak lonjong/terpotong di beranda.
     - Gambar otomatis dikompresi agar loading pembaca cepat.
   - Masukkan *Ringkasan Berita* (1–2 kalimat singkat).
   - Ketik isi naskah berita menggunakan **Rich Text Editor** (tersedia fitur format tebal, miring, poin daftar, tabel, dan tautan).
   - Tentukan status: **"Terbitkan Langsung"** atau simpan sebagai **"Draf"**.
   - Klik **"Simpan Berita"**.
2. **Mengedit atau Menghapus Berita:**
   - Gunakan tombol **Edit** (ikon pensil) untuk mengubah isi atau tombol **Hapus** (ikon tempat sampah) untuk menghapus berita lama.

### 3.7 Kelola Pengumuman Resmi & Berkas PDF
*Menu: Kelola Pengumuman (`/admin/pengumuman`)*
1. **Menambah Pengumuman Baru:**
   - Klik **"+ Tambah Pengumuman"**.
   - Masukkan *Judul Pengumuman*.
   - Pilih *Tingkat Prioritas*: Pilih **"Penting"** jika merupakan himbauan darurat atau surat edaran wajib, atau **"Biasa"** untuk warta umum.
   - Unggah Banner Pengumuman (opsional).
   - Tuliskan rincian pengumuman pada editor naskah.
   - Unggah **Lampiran Dokumen PDF Resmi** (misal: edaran surat dinas bercap).
   - Klik **"Simpan Pengumuman"**. Dokumen PDF dapat langsung diunduh oleh warga dari portal publik.

### 3.8 Kelola Agenda & Kalender Kegiatan
*Menu: Agenda Kegiatan (`/admin/agenda`)*
1. Masukkan *Nama Kegiatan* (misal: "Musyawarah Perencanaan Pembangunan (Musrenbang) Kelurahan").
2. Tentukan *Tanggal Mulai*, *Waktu/Jam*, dan *Lokasi Acara*.
3. Tentukan nama penanggung jawab / instansi penyelenggara.
4. Klik **"Simpan Agenda"**.

### 3.9 Kelola Galeri Dokumentasi Foto
*Menu: Galeri Foto (`/admin/galeri`)*
1. Klik **"+ Tambah Galeri"**.
2. Masukkan judul dokumentasi kegiatan dan tanggal giat.
3. Pilih kategori kegiatan.
4. Unggah foto kegiatan (dapat berupa satu foto sampul utama maupun koleksi foto).
5. Klik **"Simpan Galeri"**.

### 3.10 Kelola Dokumen Publik (PDF)
*Menu: Dokumen Publik (`/admin/dokumen`)*
1. Digunakan untuk memublikasikan berkas regulasi, SK Lurah, laporan pertanggungjawaban, dan panduan warga.
2. Masukkan *Judul Dokumen*.
3. Pilih *Kategori Dokumen* (Musrenbang, UMKM, Regulasi & Kebijakan, SK Kelembagaan, atau Renstra & Renja).
4. Masukkan nomor berkas dan tahun terbit.
5. Unggah berkas dokumen dalam format **.pdf**.
6. Klik **"Simpan Dokumen"**.

### 3.11 Kelola Lembaga Kemasyarakatan (LKK / RT / RW)
*Menu: Lembaga Kemasyarakatan (`/admin/lembaga`)*
1. Menambah struktur kepengurusan lembaga mitra (LPM, PKK, Karang Taruna).
2. Mendata susunan pengurus Rukun Tetangga (RT) dan Rukun Warga (RW) di lingkungan Kelurahan Kraksaan Wetan.

### 3.12 Kelola Statistik Kependudukan & Wilayah
*Menu: Statistik Wilayah (`/admin/statistik`)*
1. Memperbarui angka agregat kependudukan berkala (misal: hasil pemutakhiran data semesteran):
   - Jumlah total penduduk (Jiwa).
   - Rincian jumlah warga Laki-laki dan Perempuan.
   - Jumlah Kepala Keluarga (KK).
   - Jumlah RW (7 RW) dan RT (22 RT).
   - Luas wilayah dan kepadatan penduduk per km².
2. Setelah disimpan, angka pada beranda dan menu statistik akan otomatis terkalkulasi dan terpasang.

### 3.13 Kelola Transparansi Anggaran (APBD)
*Menu: Transparansi Anggaran (`/admin/transparansi`)*
1. Menambah data transparansi per tahun anggaran.
2. Memasukkan target pagu pendapatan, rincian pagu belanja, serta nilai realisasi yang telah dibelanjakan.
3. Sistem secara otomatis menghitung persentase serapan anggaran dan memvisualisasikannya dalam diagram batang/lingkaran interaktif.

### 3.14 Kelola Master Kategori Konten
*Menu: Master Kategori (`/admin/kategori`)*
1. Menata kelompok kategori berita, layanan, dokumen, dan pengumuman agar rapi dan seragam.

### 3.15 Kelola Akun Staf Pengguna (Khusus Super Admin)
*Menu: Kelola Staf (`/admin/staff`)*
1. Menambah akun baru untuk perangkat kelurahan sesuai peran tugasnya (*Humas/Konten*, *Pelayanan*, atau *Administrasi*).
2. Me-reset password staf jika yang bersangkutan lupa kata sandi.
3. Menonaktifkan akun staf yang telah berpindah tugas atau purnatugas.

### 3.16 Audit Log Aktivitas Akun (Khusus Super Admin)
*Menu: Log Aktivitas (`/admin/activity-logs`)*
1. Merekam rekam jejak digital (*audit trail*) setiap kali ada penambahan, pengubahan, atau penghapusan data di website.
2. Mencatat waktu kejadian, alamat IP pengguna, nama staf, dan modul data yang diubah untuk menjamin transparansi serta akuntabilitas internal.

---

## BAB IV: STANDAR OPERASIONAL & PEDOMAN KONTEN

Agar website senantiasa tampil profesional, berwibawa, dan cepat saat diakses warga, pengelola wajib mematuhi standar konten berikut:

### 4.1 Standar Format & Ukuran Gambar
| Jenis Gambar | Rekomendasi Rasio | Dimensi Ideal | Format File | Catatan |
|---|---|---|---|---|
| **Logo Kelurahan / Pemkab** | 1:1 atau 3:4 | 512 x 512 px / 600 x 800 px | `.png` transparan | Hindari latar belakang putih buram agar serasi dengan navbar. |
| **Hero Banner Utama** | 16:9 atau 21:9 | 1920 x 1080 px | `.jpg`, `.webp` | Orientasi horizontal panorama, subjek utama di sisi tengah/kanan. |
| **Foto Berita / Dokumentasi** | 16:9 | 1200 x 675 px | `.jpg`, `.jpeg`, `.png` | Gunakan fitur Crop bawaan sistem sebelum menyimpan. |
| **Foto Lurah / Staf Aparatur** | 3:4 | 600 x 800 px | `.jpg`, `.png` | Foto dinas resmi berseragam PDH / Korpri dengan pencahayaan jelas. |
| **Banner Pengumuman** | 16:9 | 1200 x 675 px | `.jpg`, `.png` | Teks dalam gambar proporsional dan tidak terlalu kecil. |
| **Piagam Maklumat HD** | 3:4 atau 4:5 | 1200 x 1600 px | `.jpg`, `.png` | Berkas beresolusi tajam agar teks ikrar terbaca jelas saat diperbesar. |

> **Catatan Teknologi Auto-Kompresi:**
> Sistem telah dilengkapi modul kompresi otomatis di sisi browser (client-side) dan konversi Base64 di backend. Jika Anda mengunggah foto kamera ponsel berukuran 5MB - 10MB, sistem akan otomatis mengoptimalkannya menjadi ukuran ramah web (di bawah 800 KB) tanpa mengurangi kejernihan visual secara kasatmata.

### 4.2 Standar Format Dokumen Lampiran (PDF)
- Seluruh dokumen surat edaran, regulasi, formulir, dan Musrenbang wajib berformat **Portable Document Format (`.pdf`)**.
- Ukuran berkas PDF yang disarankan adalah **di bawah 10 MB**.
- Pastikan berkas PDF telah di-*flatten* atau di-scan dengan resolusi 150–200 DPI (grayscale atau warna) agar mudah diunduh warga melalui smartphone dengan kuota terbatas.

### 4.3 Kaidah Penulisan Informasi Publik
1. Gunakan bahasa Indonesia yang baku, santun, lugas, dan mudah dimengerti oleh seluruh lapisan warga masyarakat.
2. Hindari singkatan yang tidak umum tanpa menyertakan kepanjangannya terlebih dahulu.
3. Selalu periksa kembali akurasi tanggal, waktu, nomor surat, dan nama pejabat sebelum memublikasikan berita atau pengumuman.
4. Jangan pernah mengunggah informasi rahasia kependudukan warga seperti Nomor Kartu Keluarga, NIK lengkap, atau data medis pribadi tanpa sensor (*redaksi*).

---

## BAB V: PEMELIHARAAN, CADANGAN DATA, & TROUBLESHOOTING

### 5.1 Mengatasi Gambar/Pratinjau Tidak Berubah (Browser Cache)
Jika Anda telah memperbarui logo atau hero banner di panel admin namun tampilan di portal publik belum berubah:
- **Penyebab:** Browser menyimpan salinan gambar lama di memori sementara (*cache*).
- **Solusi:**
  1. Tekan kombinasi tombol **Ctrl + F5** (di Google Chrome / Edge Windows) atau **Cmd + Shift + R** (di macOS) untuk melakukan *Hard Refresh*.
  2. Di ponsel Android/iOS, bersihkan riwayat penjelajahan atau buka website melalui mode penyamaran (*Incognito Tab*).

### 5.2 Pencadangan Data (Database Backup)
Untuk menjaga keamanan data jangka panjang:
1. **Hosting cPanel:** Buka cPanel > *phpMyAdmin* > Pilih database kelurahan > Klik tab **Export** > Format **SQL** > Klik **Export**.
2. Simpan salinan berkas cadangan database (`.sql`) di media penyimpanan terpisah yang aman (Google Drive kedinasan atau flashdisk arsip).
3. Lakukan pencadangan rutin minimal satu bulan sekali atau setiap setelah penginputan data dokumen strategis dalam jumlah besar.

### 5.3 Pertanyaan yang Sering Diajukan (FAQ)
**Q: Mengapa formulir tidak tersimpan saat saya mengeklik tombol Simpan?**
> A: Pastikan seluruh kolom yang bertanda bintang (*) telah terisi lengkap. Periksa apakah berkas foto yang dipilih melebihi batasan ukuran (maksimal 10 MB). Sistem akan menampilkan notifikasi toast berwarna hijau jika berhasil atau merah jika terdapat isian yang belum lengkap.

**Q: Bagaimana jika ada staf yang lupa password panel admin?**
> A: Super Admin dapat masuk ke menu **Kelola Staf (`/admin/staff`)**, klik tombol **Edit** pada akun staf yang bersangkutan, lalu masukkan password baru sementara.

**Q: Apakah website ini aman diakses dari smartphone?**
> A: Ya, website 100% responsif dan telah dioptimalkan khusus untuk pengguna smartphone dengan floating card navbar, tombol sentuh kapsul, dan pemuatan aset cepat.

---

*Buku panduan ini disusun resmi untuk Pemerintah Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo.*  
*Dokumen diperbarui pada: Oktober 2026.*
