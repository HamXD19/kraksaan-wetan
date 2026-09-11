<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Lingkungan;
use App\Models\Pengumuman;
use App\Models\PerangkatKelurahan;
use App\Models\ProfilKelurahan;
use App\Models\Statistik;
use Illuminate\Database\Seeder;

class KelurahanSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Profil Kelurahan
        if (ProfilKelurahan::count() === 0) {
            ProfilKelurahan::create([
                'nama' => 'Kelurahan Kraksaan Wetan',
                'kecamatan' => 'Kraksaan',
                'kabupaten' => 'Kabupaten Probolinggo',
                'provinsi' => 'Jawa Timur',
                'kode_pos' => '67282',
                'alamat' => 'Jl. Dr. Soetomo No. 12, Kraksaan Wetan, Kec. Kraksaan, Kab. Probolinggo, Jawa Timur 67282',
                'telepon' => '(0335) 841234',
                'email' => 'kelurahan.kraksaanwetan@probolinggokab.go.id',
                'jam_kerja' => 'Senin - Jumat: 08:00 - 15:30 WIB',
                'deskripsi' => 'Kelurahan Kraksaan Wetan merupakan kelurahan strategis di jantung Ibu Kota Kabupaten Probolinggo, Kecamatan Kraksaan. Berkomitmen menghadirkan pelayanan publik yang ramah, transparan, akuntabel, dan berbasis digital untuk seluruh lapisan masyarakat.',
                'sejarah' => 'Nama Kraksaan memiliki akar historis mendalam di kawasan tapal kuda Jawa Timur. Kraksaan Wetan tumbuh dari kawasan permukiman dan niaga yang dinamis di sisi timur Sungai Kraksaan / Kali Rondokuning. Seiring ditetapkannya Kota Kraksaan sebagai pusat pemerintahan Kabupaten Probolinggo pada tahun 2010 (PP No. 2 Tahun 2010), Kelurahan Kraksaan Wetan bertransformasi menjadi pusat pemukiman, sentra UMKM, dan simpul pelayanan masyarakat modern yang harmonis memadukan kearifan lokal dan kemajuan peradaban.',
                'visi' => 'Terwujudnya Pelayanan Kelurahan Kraksaan Wetan yang Prima, Transparan, Berakhlak, Menuju Masyarakat Sejahtera, Mandiri, dan Berdaya Saing.',
                'misi' => [
                    'Meningkatkan mutu pelayanan publik yang cepat, mudah, transparan, dan bebas pungli berbasis teknologi informasi.',
                    'Mendorong partisipasi aktif masyarakat dalam pembangunan lingkungan yang bersih, sehat, aman, dan tertib.',
                    'Mengembangkan potensi ekonomi kerakyatan, UMKM, dan pemberdayaan ekonomi keluarga.',
                    'Memperkuat kerukunan warga, nilai-nilai kegotongroyongan, serta kearifan lokal berakhlak mulia.',
                    'Meningkatkan kualitas tata kelola kelembagaan RT, RW, dan Lembaga Kemasyarakatan Kelurahan (LKK).',
                ],
                'lurah_nama' => 'Ahmad Fauzi, S.STP., M.Si.',
                'lurah_nip' => '19820514 200602 1 003',
                'lurah_jabatan' => 'Lurah Kraksaan Wetan',
                'lurah_sambutan' => 'Assalamu’alaikum Warahmatullahi Wabarakatuh. Selamat datang di portal resmi Kelurahan Kraksaan Wetan. Melalui website ini, kami membuka seluas-luasnya akses informasi, transparansi program pembangunan, serta kemudahan panduan pelayanan administrasi kependudukan untuk segenap warga dan publik. Mari kita bersama-sama mewujudkan Kraksaan Wetan yang Guyub, Berdaya, dan Maju.',
                'lurah_foto' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80',
            ]);
        }

        // 2. Perangkat Kelurahan
        if (PerangkatKelurahan::count() === 0) {
            $perangkatList = [
                ['nama' => 'Bambang Supriyanto, S.AP.', 'jabatan' => 'Sekretaris Kelurahan', 'bidang' => 'Tata Usaha & Administrasi', 'urutan' => 1],
                ['nama' => 'Siti Aminah, S.Sos.', 'jabatan' => 'Kasi Pemerintahan, Ketentraman & Ketertiban', 'bidang' => 'Trantib & Kependudukan', 'urutan' => 2],
                ['nama' => 'H. Mulyadi, S.E.', 'jabatan' => 'Kasi Perekonomian & Pembangunan', 'bidang' => 'Ekbang & Sarpras', 'urutan' => 3],
                ['nama' => 'Dewi Lestari, S.Pd.', 'jabatan' => 'Kasi Kesejahteraan Rakyat', 'bidang' => 'Kesra & Sosial', 'urutan' => 4],
                ['nama' => 'Rahmat Hidayat', 'jabatan' => 'Pengadministrasi Umum', 'bidang' => 'Pelayanan Surat Menyurat', 'urutan' => 5],
            ];
            foreach ($perangkatList as $p) {
                PerangkatKelurahan::create($p);
            }
        }

        // 3. Statistik
        if (Statistik::count() === 0) {
            Statistik::create([
                'penduduk' => 6842,
                'kk' => 2185,
                'laki_laki' => 3390,
                'perempuan' => 3452,
                'rt' => 28,
                'rw' => 7,
                'luas_wilayah' => '1.84',
                'kepadatan' => '3,718 jiwa/km²',
            ]);
        }

        // 4. Lingkungan RW
        if (Lingkungan::count() === 0) {
            $lingkunganList = [
                ['nama' => 'RW 01 Kraksaan Timur', 'rt' => 4, 'penduduk' => 980, 'urutan' => 1],
                ['nama' => 'RW 02 Pasar Lama', 'rt' => 4, 'penduduk' => 950, 'urutan' => 2],
                ['nama' => 'RW 03 Kauman Wetan', 'rt' => 4, 'penduduk' => 1020, 'urutan' => 3],
                ['nama' => 'RW 04 Patemon Indah', 'rt' => 4, 'penduduk' => 930, 'urutan' => 4],
                ['nama' => 'RW 05 Kebon Agung Asri', 'rt' => 4, 'penduduk' => 990, 'urutan' => 5],
                ['nama' => 'RW 06 Sumber Mulya', 'rt' => 4, 'penduduk' => 1010, 'urutan' => 6],
                ['nama' => 'RW 07 Asri Sentosa', 'rt' => 4, 'penduduk' => 962, 'urutan' => 7],
            ];
            foreach ($lingkunganList as $l) {
                Lingkungan::create($l);
            }
        }

        // 5. Layanan Masyarakat
        if (Layanan::count() === 0) {
            $layananList = [
                [
                    'slug' => 'administrasi-kependudukan',
                    'judul' => 'Administrasi Kependudukan',
                    'kategori' => 'Kependudukan',
                    'icon' => 'Users',
                    'deskripsi' => 'Pengurusan pengantar pembuatan KTP Elektronik, Kartu Keluarga baru/perubahan, serta Kartu Identitas Anak (KIA).',
                    'persyaratan' => [
                        'Pengantar dari Ketua RT dan RW setempat',
                        'Fotokopi Kartu Keluarga (KK) lama',
                        'Fotokopi Akta Kelahiran / Ijazah terakhir',
                        'Pas foto berwarna 3x4 (2 lembar) latar biru/merah',
                    ],
                    'alur' => 'Warga mengajukan berkas ke loket pelayanan kelurahan -> Verifikasi petugas -> Penerbitan surat pengantar Dispendukcapil.',
                    'waktu' => '10 - 15 Menit',
                    'biaya' => 'Gratis (Rp 0)',
                    'urutan' => 1,
                ],
                [
                    'slug' => 'surat-keterangan-domisili',
                    'judul' => 'Surat Keterangan Domisili',
                    'kategori' => 'Keterangan',
                    'icon' => 'Home',
                    'deskripsi' => 'Keterangan bukti tempat tinggal bagi warga penduduk tetap maupun warga berdomisili sementara di wilayah Kraksaan Wetan.',
                    'persyaratan' => [
                        'Surat pengantar RT dan RW domisili',
                        'Fotokopi KTP pemohon',
                        'Fotokopi KK asal',
                        'Surat pernyataan jaminan tempat tinggal dari pemilik rumah/kontrak',
                    ],
                    'alur' => 'Pemeriksaan berkas di loket -> Input data kependudukan -> Penandatanganan Lurah/Kasi -> Surat diserahkan.',
                    'waktu' => '10 - 20 Menit',
                    'biaya' => 'Gratis (Rp 0)',
                    'urutan' => 2,
                ],
                [
                    'slug' => 'surat-keterangan-usaha',
                    'judul' => 'Surat Keterangan Usaha (SKU)',
                    'kategori' => 'Perekonomian',
                    'icon' => 'Briefcase',
                    'deskripsi' => 'Legalitas pengantar izin usaha mikro dan kecil untuk keperluan perbankan, KUR, atau perizinan lanjutan OSS.',
                    'persyaratan' => [
                        'Surat pengantar RT dan RW',
                        'Fotokopi KTP dan KK pemohon',
                        'Foto lokasi / tempat usaha yang sedang berjalan',
                        'Pernyataan jenis kegiatan usaha',
                    ],
                    'alur' => 'Verifikasi keabsahan lokasi usaha oleh Kasi Ekbang -> Pembuatan surat keterangan -> Pengesahan oleh Lurah.',
                    'waktu' => '15 - 30 Menit',
                    'biaya' => 'Gratis (Rp 0)',
                    'urutan' => 3,
                ],
                [
                    'slug' => 'surat-keterangan-tidak-mampu',
                    'judul' => 'Surat Keterangan Tidak Mampu (SKTM)',
                    'kategori' => 'Kesejahteraan Sosial',
                    'icon' => 'HeartHandshake',
                    'deskripsi' => 'Keterangan kondisi sosial ekonomi untuk keperluan beasiswa sekolah/kuliah, bantuan kesehatan (KIS/BPJS PBI), dan program bansos.',
                    'persyaratan' => [
                        'Surat pengantar RT dan RW yang menyatakan kondisi ekonomi',
                        'Fotokopi KTP & KK yang masih berlaku',
                        'Foto kondisi rumah (tampak depan & dalam)',
                        'Surat pernyataan tidak mampu bermaterai',
                    ],
                    'alur' => 'Pengecekan data DTKS Kemensos / P3KE -> Validasi faktual lapangan bila diperlukan -> Terbit SKTM bertanda tangan Lurah.',
                    'waktu' => '20 - 30 Menit',
                    'biaya' => 'Gratis (Rp 0)',
                    'urutan' => 4,
                ],
                [
                    'slug' => 'surat-pengantar-skck',
                    'judul' => 'Surat Pengantar SKCK',
                    'kategori' => 'Ketertiban Umum',
                    'icon' => 'ShieldCheck',
                    'deskripsi' => 'Pengantar permohonan Surat Keterangan Catatan Kepolisian ke Polsek Kraksaan / Polres Probolinggo untuk keperluan melamar kerja atau dinas.',
                    'persyaratan' => [
                        'Pengantar dari RT & RW setempat',
                        'Fotokopi KTP & Kartu Keluarga',
                        'Fotokopi Akta Kelahiran',
                    ],
                    'alur' => 'Pemeriksaan identitas -> Verifikasi catatan ketertiban -> Penerbitan surat pengantar kepolisian.',
                    'waktu' => '10 Menit',
                    'biaya' => 'Gratis (Rp 0)',
                    'urutan' => 5,
                ],
                [
                    'slug' => 'surat-keterangan-kematian-kelahiran',
                    'judul' => 'Surat Pengantar Kelahiran & Kematian',
                    'kategori' => 'Kependudukan',
                    'icon' => 'FileText',
                    'deskripsi' => 'Pengurusan surat pengantar akta kelahiran atau surat keterangan kematian untuk pembaharuan data kependudukan keluarga.',
                    'persyaratan' => [
                        'Surat keterangan kelahiran dari bidan/RS atau surat kematian dari dokter/RS',
                        'KTP & KK orang tua / almarhum',
                        'Buku nikah orang tua (untuk kelahiran)',
                        'KTP pelapor dan 2 orang saksi',
                    ],
                    'alur' => 'Registrasi buku pokok kelurahan -> Pencetakan surat pengantar resmi -> Legalisasi Lurah.',
                    'waktu' => '15 Menit',
                    'biaya' => 'Gratis (Rp 0)',
                    'urutan' => 6,
                ],
            ];
            foreach ($layananList as $ly) {
                Layanan::create($ly);
            }
        }

        // 6. Berita
        if (Berita::count() === 0) {
            $beritaList = [
                [
                    'slug' => 'musrenbangkel-kraksaan-wetan-2026-sepakati-prioritas-drainase-dan-pemberdayaan-umkm',
                    'judul' => 'Musrenbangkel Kraksaan Wetan Sepakati Prioritas Drainase Lingkungan dan Penguatan UMKM',
                    'kategori' => 'Pemerintahan',
                    'tanggal' => '04 Maret 2026',
                    'penulis' => 'Tim Humas Kelurahan',
                    'ringkasan' => 'Musyawarah Perencanaan Pembangunan Kelurahan (Musrenbangkel) Kraksaan Wetan sukses digelar dengan melibatkan seluruh perwakilan RT, RW, tokoh masyarakat, dan LPMK.',
                    'konten' => "Kegiatan Musyawarah Perencanaan Pembangunan Kelurahan (Musrenbangkel) Kraksaan Wetan berlangsung khidmat di Balai Kelurahan Kraksaan Wetan. Acara ini dihadiri langsung oleh jajaran Forkopimca Kraksaan, Lurah Kraksaan Wetan, Ketua LPMK, Ketua TP PKK, serta seluruh Ketua RT dan RW se-Kelurahan Kraksaan Wetan.\n\nDalam sambutannya, Lurah Kraksaan Wetan menekankan bahwa usulan program tahun anggaran mendatang berfokus pada dua poros utama: normalisasi drainase terpadu untuk mengantisipasi genangan air hujan di permukiman padat dan penguatan sarana pelatihan bagi pelaku UMKM kuliner dan kerajinan khas Kraksaan.\n\n\"Partisipasi aktif warga menjadi kunci keberhasilan pembangunan. Semua usulan telah kita inventarisir secara transparan dan berkeadilan untuk diteruskan ke Musrenbang tingkat Kecamatan Kraksaan,\" tegas Lurah.",
                    'gambar' => 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80',
                    'dilihat' => 412,
                ],
                [
                    'slug' => 'penyaluran-bantuan-pangan-beras-cadangan-pemerintah-di-balai-kraksaan-wetan',
                    'judul' => 'Penyaluran Bantuan Pangan Beras Cadangan Pemerintah untuk KPM Kraksaan Wetan Berjalan Tertib',
                    'kategori' => 'Masyarakat',
                    'tanggal' => '28 Februari 2026',
                    'penulis' => 'Kasi Kesra',
                    'ringkasan' => 'Sebanyak ratusan Keluarga Penerima Manfaat (KPM) di Kelurahan Kraksaan Wetan menerima alokasi cadangan beras pemerintah pusat dengan pengawasan ketat aparat kelurahan.',
                    'konten' => "Pemerintah Kelurahan Kraksaan Wetan mendistribusikan bantuan pangan beras bersumber dari Cadangan Beras Pemerintah (CBP) kepada ratusan Keluarga Penerima Manfaat (KPM) yang tercatat dalam data resmi P3KE/DTKS.\n\nPenyaluran dilaksanakan dengan sistem giliran per-RW guna menghindari antrean panjang dan menjaga kenyamanan para lansia. Pendampingan dilakukan langsung oleh Babinsa, Bhabinkamtibmas, serta Kasi Kesejahteraan Rakyat Kelurahan Kraksaan Wetan.",
                    'gambar' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
                    'dilihat' => 285,
                ],
                [
                    'slug' => 'posyandu-integrasi-layanan-primer-di-rw-03-kauman-wetan-tingkatkan-kesehatan-warga',
                    'judul' => 'Posyandu Integrasi Layanan Primer (ILP) di RW 03 Kauman Wetan Tingkatkan Kesehatan Keluarga',
                    'kategori' => 'Kesehatan',
                    'tanggal' => '20 Februari 2026',
                    'penulis' => 'Kader TP PKK',
                    'ringkasan' => 'Puskesmas Kraksaan bersama TP PKK Kelurahan menggelar Posyandu ILP yang melayani balita, remaja, hingga lansia dalam satu atap pelayanan terpadu.',
                    'konten' => "Pelaksanaan Posyandu Integrasi Layanan Primer (ILP) di Balai RW 03 Kauman Wetan disambut antusias tinggi oleh masyarakat. Berbeda dengan posyandu konvensional, Posyandu ILP melayani siklus hidup lengkap mulai dari pemantauan gizi dan tumbuh kembang balita, skrining anemia remaja putri, hingga cek tensi dan gula darah gratis bagi warga lanjut usia.\n\nKetua TP PKK Kelurahan Kraksaan Wetan menyampaikan apresiasi kepada para kader posyandu yang berdedikasi menjaga kesehatan generasi penerus dan meminimalkan angka stunting di wilayah kelurahan.",
                    'gambar' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80',
                    'dilihat' => 198,
                ],
                [
                    'slug' => 'kerja-bakti-serentak-bersih-lingkungan-dan-saluran-air-warga-kraksaan-wetan',
                    'judul' => 'Gerakan Kerja Bakti Bersih Saluran Air dan Lingkungan Hijau di 7 RW Kelurahan Kraksaan Wetan',
                    'kategori' => 'Masyarakat',
                    'tanggal' => '15 Februari 2026',
                    'penulis' => 'Kasi Trantib',
                    'ringkasan' => 'Semangat gotong royong warga terlihat nyata saat gotong royong massal membersihkan saluran air dan peremajaan taman lingkungan di seluruh RW.',
                    'konten' => "Minggu pagi menjadi momentum kebersamaan warga Kelurahan Kraksaan Wetan dalam aksi kerja bakti serentak. Warga bergotong-royong membersihkan sedimentasi parit, memangkas dahan pohon yang membahayakan kabel listrik, dan menata pot tanaman di sepanjang gang pemukiman.\n\nKegiatan ini juga menjadi sarana mempererat silaturahmi antarwarga serta mewujudkan lingkungan permukiman yang bersih, asri, dan terhindar dari potensi sarang nyamuk DBD.",
                    'gambar' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
                    'dilihat' => 254,
                ],
                [
                    'slug' => 'sosialisasi-digitalisasi-administrasi-kependudukan-ikd-bagi-warga-kraksaan',
                    'judul' => 'Sosialisasi Penerapan Identitas Kependudukan Digital (IKD) Dispendukcapil di Kraksaan Wetan',
                    'kategori' => 'Pemerintahan',
                    'tanggal' => '08 Februari 2026',
                    'penulis' => 'Staf IT Kelurahan',
                    'ringkasan' => 'Kelurahan Kraksaan Wetan memfasilitasi aktivasi aplikasi IKD di smartphone warga untuk mempermudah akses layanan publik tanpa perlu membawa KTP fisik.',
                    'konten' => "Seiring akselerasi Sistem Pemerintahan Berbasis Elektronik (SPBE) Pemerintah Kabupaten Probolinggo, Kelurahan Kraksaan Wetan bekerjasama dengan Dinas Kependudukan dan Pencatatan Sipil membuka loket khusus aktivasi Identitas Kependudukan Digital (IKD).\n\nPetugas mendampingi warga melakukan registrasi mulai dari scan QR code, verifikasi wajah (face recognition), hingga integrasi dokumen Kartu Keluarga dan Kartu BPJS ke dalam aplikasi telepon pintar masing-masing.",
                    'gambar' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
                    'dilihat' => 310,
                ],
            ];
            foreach ($beritaList as $b) {
                Berita::create($b);
            }
        }

        // 7. Pengumuman
        if (Pengumuman::count() === 0) {
            $pengumumanList = [
                [
                    'judul' => 'Jadwal Pelayanan Pembayaran PBB-P2 Keliling BPPKAD di Kantor Kelurahan Kraksaan Wetan',
                    'tanggal' => '10 Maret 2026',
                    'penyelenggara' => 'BPPKAD Kab. Probolinggo & Kelurahan',
                    'prioritas' => 'Penting',
                    'isi' => 'Diberitahukan kepada seluruh warga wajib pajak Kelurahan Kraksaan Wetan, loket mobil keliling pembayaran PBB-P2 akan hadir pada hari Rabu, 18 Maret 2026 pukul 08.30 - 13.00 WIB di halaman kantor kelurahan. Warga dimohon membawa SPPT PBB tahun berjalan.',
                    'file' => 'Pengumuman_PBB_2026.pdf',
                ],
                [
                    'judul' => 'Himbauan Kewaspadaan Cuaca Ekstrem dan Pembersihan Saluran Air Musim Hujan',
                    'tanggal' => '02 Maret 2026',
                    'penyelenggara' => 'Kasi Trantib Kelurahan',
                    'prioritas' => 'Himbauan',
                    'isi' => 'Menindaklanjuti rilis BMKG dan BPBD Kabupaten Probolinggo terkait potensi hujan dengan intensitas sedang hingga lebat, seluruh pengurus RT/RW dihimbau menggerakkan warga membersihkan saluran pembuangan air dan tidak membuang sampah sembarangan ke sungai.',
                    'file' => null,
                ],
                [
                    'judul' => 'Pendataan Calon Penerima Bantuan Pelatihan Keterampilan Wirausaha Pemuda Karang Taruna',
                    'tanggal' => '25 Februari 2026',
                    'penyelenggara' => 'Disnaker & Kelurahan',
                    'prioritas' => 'Pemberitahuan',
                    'isi' => 'Bagi pemuda warga Kraksaan Wetan rentang usia 18-30 tahun yang berminat mengikuti pelatihan barista kopi, tata boga, atau digital marketing bersertifikat BNSP, dipersilakan mendaftar ke Seksi Ekbang Kelurahan paling lambat 15 Maret 2026.',
                    'file' => 'Form_Pelatihan_Disnaker.pdf',
                ],
            ];
            foreach ($pengumumanList as $pg) {
                Pengumuman::create($pg);
            }
        }

        // 8. Galeri
        if (Galeri::count() === 0) {
            $galeriList = [
                [
                    'judul' => 'Musrenbangkel Penetapan RKPD Kraksaan Wetan',
                    'kategori' => 'Pemerintahan',
                    'tanggal' => '04 Maret 2026',
                    'gambar' => 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Rapat koordinasi dan musyawarah perencanaan pembangunan bersama para ketua RW dan tokoh masyarakat.',
                ],
                [
                    'judul' => 'Layanan Jemput Bola Kependudukan untuk Lansia dan Disabilitas',
                    'kategori' => 'Pelayanan',
                    'tanggal' => '26 Februari 2026',
                    'gambar' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Petugas kelurahan mendatangi kediaman warga lanjut usia untuk pembaruan kartu keluarga dan berkas kependudukan.',
                ],
                [
                    'judul' => 'Kegiatan Posyandu ILP Balita & Lansia RW 03 Kauman',
                    'kategori' => 'Kesehatan',
                    'tanggal' => '20 Februari 2026',
                    'gambar' => 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Pemeriksaan kesehatan terpadu dan penimbangan berat badan balita untuk pencegahan stunting.',
                ],
                [
                    'judul' => 'Gotong Royong Bersih Saluran Air dan Lingkungan Hijau',
                    'kategori' => 'Kemasyarakatan',
                    'tanggal' => '15 Februari 2026',
                    'gambar' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Warga secara antusias membersihkan saluran irigasi dan lingkungan bersama aparat tiga pilar.',
                ],
                [
                    'judul' => 'Penyaluran Bantuan Pangan Beras Cadangan Pemerintah',
                    'kategori' => 'Sosial',
                    'tanggal' => '28 Februari 2026',
                    'gambar' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Pendistribusian beras bantuan pangan bagi warga yang berhak secara tertib di pendopo kelurahan.',
                ],
                [
                    'judul' => 'Bazar UMKM dan Produk Olahan Kuliner Kraksaan',
                    'kategori' => 'Kemasyarakatan',
                    'tanggal' => '10 Februari 2026',
                    'gambar' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
                    'deskripsi' => 'Pameran aneka produk UMKM binaan kelurahan untuk menggeliatkan roda perekonomian lokal.',
                ],
            ];
            foreach ($galeriList as $g) {
                Galeri::create($g);
            }
        }
    }
}
