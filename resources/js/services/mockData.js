export const mockProfil = {
    nama: 'Kelurahan Kraksaan Wetan',
    logo: null,
    kecamatan: 'Kraksaan',
    kabupaten: 'Kabupaten Probolinggo',
    provinsi: 'Jawa Timur',
    kode_pos: '67282',
    alamat: 'Jl. Dr. Soetomo No. 12, Kraksaan Wetan, Kec. Kraksaan, Kab. Probolinggo, Jawa Timur 67282',
    telepon: '(0335) 841234',
    email: 'kelurahan.kraksaanwetan@probolinggokab.go.id',
    jam_kerja: 'Senin - Jumat: 08:00 - 15:30 WIB',
    deskripsi: 'Kelurahan Kraksaan Wetan merupakan kelurahan strategis di jantung Ibu Kota Kabupaten Probolinggo, Kecamatan Kraksaan. Berkomitmen menghadirkan pelayanan publik yang ramah, transparan, akuntabel, dan berbasis digital untuk seluruh lapisan masyarakat.',
    sejarah: 'Nama Kraksaan memiliki akar historis mendalam di kawasan tapal kuda Jawa Timur. Kraksaan Wetan tumbuh dari kawasan permukiman dan niaga yang dinamis di sisi timur Sungai Kraksaan / Kali Rondokuning. Seiring ditetapkannya Kota Kraksaan sebagai pusat pemerintahan Kabupaten Probolinggo pada tahun 2010 (PP No. 2 Tahun 2010), Kelurahan Kraksaan Wetan bertransformasi menjadi pusat pemukiman, sentra UMKM, dan simpul pelayanan masyarakat modern yang harmonis memadukan kearifan lokal dan kemajuan peradaban.',
    visi: 'Terwujudnya Pelayanan Kelurahan Kraksaan Wetan yang Prima, Transparan, Berakhlak, Menuju Masyarakat Sejahtera, Mandiri, dan Berdaya Saing.',
    misi: [
        'Meningkatkan mutu pelayanan publik yang cepat, mudah, transparan, dan bebas pungli berbasis teknologi informasi.',
        'Mendorong partisipasi aktif masyarakat dalam pembangunan lingkungan yang bersih, sehat, aman, dan tertib.',
        'Mengembangkan potensi ekonomi kerakyatan, UMKM, dan pemberdayaan ekonomi keluarga.',
        'Memperkuat kerukunan warga, nilai-nilai kegotongroyongan, serta kearifan lokal berakhlak mulia.',
        'Meningkatkan kualitas tata kelola kelembagaan RT, RW, dan Lembaga Kemasyarakatan Kelurahan (LKK).'
    ],
    lurah: {
        nama: 'Ahmad Fauzi, S.STP., M.Si.',
        nip: '19820514 200602 1 003',
        jabatan: 'Lurah Kraksaan Wetan',
        sambutan: 'Assalamu’alaikum Warahmatullahi Wabarakatuh. Selamat datang di portal resmi Kelurahan Kraksaan Wetan. Melalui website ini, kami membuka seluas-luasnya akses informasi, transparansi program pembangunan, serta kemudahan panduan pelayanan administrasi kependudukan untuk segenap warga dan publik. Mari kita bersama-sama mewujudkan Kraksaan Wetan yang Guyub, Berdaya, dan Maju.',
        foto: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=400&q=80'
    },
    perangkat: [
        { nama: 'Bambang Supriyanto, S.AP.', jabatan: 'Sekretaris Kelurahan', bidang: 'Tata Usaha & Administrasi' },
        { nama: 'Siti Aminah, S.Sos.', jabatan: 'Kasi Pemerintahan, Ketentraman & Ketertiban', bidang: 'Trantib & Kependudukan' },
        { nama: 'H. Mulyadi, S.E.', jabatan: 'Kasi Perekonomian & Pembangunan', bidang: 'Ekbang & Sarpras' },
        { nama: 'Dewi Lestari, S.Pd.', jabatan: 'Kasi Kesejahteraan Rakyat', bidang: 'Kesra & Sosial' },
        { nama: 'Rahmat Hidayat', jabatan: 'Pengadministrasi Umum', bidang: 'Pelayanan Surat Menyurat' }
    ]
};

export const mockStatistik = {
    penduduk: 6842,
    kk: 2185,
    laki_laki: 3390,
    perempuan: 3452,
    rt: 28,
    rw: 7,
    luas_wilayah: '1.84',
    kepadatan: '3,718 jiwa/km²',
    lingkungan: [
        { nama: 'RW 01 Kraksaan Timur', rt: 4, penduduk: 980 },
        { nama: 'RW 02 Pasar Lama', rt: 4, penduduk: 950 },
        { nama: 'RW 03 Kauman Wetan', rt: 4, penduduk: 1020 },
        { nama: 'RW 04 Patemon Indah', rt: 4, penduduk: 930 },
        { nama: 'RW 05 Kebon Agung Asri', rt: 4, penduduk: 990 },
        { nama: 'RW 06 Sumber Mulya', rt: 4, penduduk: 1010 },
        { nama: 'RW 07 Asri Sentosa', rt: 4, penduduk: 962 }
    ]
};

export const mockLayanan = [
    {
        id: 1,
        slug: 'administrasi-kependudukan',
        judul: 'Administrasi Kependudukan',
        kategori: 'Kependudukan',
        icon: 'Users',
        deskripsi: 'Pengurusan pengantar pembuatan KTP Elektronik, Kartu Keluarga baru/perubahan, serta Kartu Identitas Anak (KIA).',
        persyaratan: [
            'Pengantar dari Ketua RT dan RW setempat',
            'Fotokopi Kartu Keluarga (KK) lama',
            'Fotokopi Akta Kelahiran / Ijazah terakhir',
            'Pas foto berwarna 3x4 (2 lembar) latar biru/merah'
        ],
        alur: 'Warga mengajukan berkas ke loket pelayanan kelurahan -> Verifikasi petugas -> Penerbitan surat pengantar Dispendukcapil.',
        waktu: '10 - 15 Menit',
        biaya: 'Gratis (Rp 0)'
    },
    {
        id: 2,
        slug: 'surat-keterangan-domisili',
        judul: 'Surat Keterangan Domisili',
        kategori: 'Keterangan',
        icon: 'Home',
        deskripsi: 'Keterangan bukti tempat tinggal bagi warga penduduk tetap maupun warga berdomisili sementara di wilayah Kraksaan Wetan.',
        persyaratan: [
            'Surat pengantar RT dan RW domisili',
            'Fotokopi KTP pemohon',
            'Fotokopi KK asal',
            'Surat pernyataan jaminan tempat tinggal dari pemilik rumah/kontrak'
        ],
        alur: 'Pemeriksaan berkas di loket -> Input data kependudukan -> Penandatanganan Lurah/Kasi -> Surat diserahkan.',
        waktu: '10 - 20 Menit',
        biaya: 'Gratis (Rp 0)'
    },
    {
        id: 3,
        slug: 'surat-keterangan-usaha',
        judul: 'Surat Keterangan Usaha (SKU)',
        kategori: 'Perekonomian',
        icon: 'Briefcase',
        deskripsi: 'Legalitas pengantar izin usaha mikro dan kecil untuk keperluan perbankan, KUR, atau perizinan lanjutan OSS.',
        persyaratan: [
            'Surat pengantar RT dan RW',
            'Fotokopi KTP dan KK pemohon',
            'Foto lokasi / tempat usaha yang sedang berjalan',
            'Pernyataan jenis kegiatan usaha'
        ],
        alur: 'Verifikasi keabsahan lokasi usaha oleh Kasi Ekbang -> Pembuatan surat keterangan -> Pengesahan oleh Lurah.',
        waktu: '15 - 30 Menit',
        biaya: 'Gratis (Rp 0)'
    },
    {
        id: 4,
        slug: 'surat-keterangan-tidak-mampu',
        judul: 'Surat Keterangan Tidak Mampu (SKTM)',
        kategori: 'Kesejahteraan Sosial',
        icon: 'HeartHandshake',
        deskripsi: 'Keterangan kondisi sosial ekonomi untuk keperluan beasiswa sekolah/kuliah, bantuan kesehatan (KIS/BPJS PBI), dan program bansos.',
        persyaratan: [
            'Surat pengantar RT dan RW yang menyatakan kondisi ekonomi',
            'Fotokopi KTP & KK yang masih berlaku',
            'Foto kondisi rumah (tampak depan & dalam)',
            'Surat pernyataan tidak mampu bermaterai'
        ],
        alur: 'Pengecekan data DTKS Kemensos / P3KE -> Validasi faktual lapangan bila diperlukan -> Terbit SKTM bertanda tangan Lurah.',
        waktu: '20 - 30 Menit',
        biaya: 'Gratis (Rp 0)'
    },
    {
        id: 5,
        slug: 'surat-pengantar-skck',
        judul: 'Surat Pengantar SKCK',
        kategori: 'Ketertiban Umum',
        icon: 'ShieldCheck',
        deskripsi: 'Pengantar permohonan Surat Keterangan Catatan Kepolisian ke Polsek Kraksaan / Polres Probolinggo untuk keperluan melamar kerja atau dinas.',
        persyaratan: [
            'Pengantar dari RT & RW setempat',
            'Fotokopi KTP & Kartu Keluarga',
            'Fotokopi Akta Kelahiran'
        ],
        alur: 'Pemeriksaan identitas -> Verifikasi catatan ketertiban -> Penerbitan surat pengantar kepolisian.',
        waktu: '10 Menit',
        biaya: 'Gratis (Rp 0)'
    },
    {
        id: 6,
        slug: 'surat-keterangan-kematian-kelahiran',
        judul: 'Surat Pengantar Kelahiran & Kematian',
        kategori: 'Kependudukan',
        icon: 'FileText',
        deskripsi: 'Pengurusan surat pengantar akta kelahiran atau surat keterangan kematian untuk pembaharuan data kependudukan keluarga.',
        persyaratan: [
            'Surat keterangan kelahiran dari bidan/RS atau surat kematian dari dokter/RS',
            'KTP & KK orang tua / almarhum',
            'Buku nikah orang tua (untuk kelahiran)',
            'KTP pelapor dan 2 orang saksi'
        ],
        alur: 'Registrasi buku pokok kelurahan -> Pencetakan surat pengantar resmi -> Legalisasi Lurah.',
        waktu: '15 Menit',
        biaya: 'Gratis (Rp 0)'
    }
];

export const mockBerita = [
    {
        id: 1,
        slug: 'musrenbangkel-kraksaan-wetan-2026-sepakati-prioritas-drainase-dan-pemberdayaan-umkm',
        judul: 'Musrenbangkel Kraksaan Wetan Sepakati Prioritas Drainase Lingkungan dan Penguatan UMKM',
        kategori: 'Pemerintahan',
        tanggal: '04 Maret 2026',
        penulis: 'Tim Humas Kelurahan',
        ringkasan: 'Musyawarah Perencanaan Pembangunan Kelurahan (Musrenbangkel) Kraksaan Wetan sukses digelar dengan melibatkan seluruh perwakilan RT, RW, tokoh masyarakat, dan LPMK.',
        konten: 'Kegiatan Musyawarah Perencanaan Pembangunan Kelurahan (Musrenbangkel) Kraksaan Wetan berlangsung khidmat di Balai Kelurahan Kraksaan Wetan. Acara ini dihadiri langsung oleh jajaran Forkopimca Kraksaan, Lurah Kraksaan Wetan, Ketua LPMK, Ketua TP PKK, serta seluruh Ketua RT dan RW se-Kelurahan Kraksaan Wetan.\n\nDalam sambutannya, Lurah Kraksaan Wetan menekankan bahwa usulan program tahun anggaran mendatang berfokus pada dua poros utama: normalisasi drainase terpadu untuk mengantisipasi genangan air hujan di permukiman padat dan penguatan sarana pelatihan bagi pelaku UMKM kuliner dan kerajinan khas Kraksaan.\n\n"Partisipasi aktif warga menjadi kunci keberhasilan pembangunan. Semua usulan telah kita inventarisir secara transparan dan berkeadilan untuk diteruskan ke Musrenbang tingkat Kecamatan Kraksaan," tegas Lurah.',
        gambar: 'https://images.unsplash.com/photo-1577495508048-b635879837f1?auto=format&fit=crop&w=800&q=80',
        dilihat: 412
    },
    {
        id: 2,
        slug: 'penyaluran-bantuan-pangan-beras-cadangan-pemerintah-di-balai-kraksaan-wetan',
        judul: 'Penyaluran Bantuan Pangan Beras Cadangan Pemerintah untuk KPM Kraksaan Wetan Berjalan Tertib',
        kategori: 'Masyarakat',
        tanggal: '28 Februari 2026',
        penulis: 'Kasi Kesra',
        ringkasan: 'Sebanyak ratusan Keluarga Penerima Manfaat (KPM) di Kelurahan Kraksaan Wetan menerima alokasi cadangan beras pemerintah pusat dengan pengawasan ketat aparat kelurahan.',
        konten: 'Pemerintah Kelurahan Kraksaan Wetan mendistribusikan bantuan pangan beras bersumber dari Cadangan Beras Pemerintah (CBP) kepada ratusan Keluarga Penerima Manfaat (KPM) yang tercatat dalam data resmi P3KE/DTKS.\n\nPenyaluran dilaksanakan dengan sistem giliran per-RW guna menghindari antrean panjang dan menjaga kenyamanan para lansia. Pendampingan dilakukan langsung oleh Babinsa, Bhabinkamtibmas, serta Kasi Kesejahteraan Rakyat Kelurahan Kraksaan Wetan.',
        gambar: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
        dilihat: 285
    },
    {
        id: 3,
        slug: 'posyandu-integrasi-layanan-primer-di-rw-03-kauman-wetan-tingkatkan-kesehatan-warga',
        judul: 'Posyandu Integrasi Layanan Primer (ILP) di RW 03 Kauman Wetan Tingkatkan Kesehatan Keluarga',
        kategori: 'Kesehatan',
        tanggal: '20 Februari 2026',
        penulis: 'Kader TP PKK',
        ringkasan: 'Puskesmas Kraksaan bersama TP PKK Kelurahan menggelar Posyandu ILP yang melayani balita, remaja, hingga lansia dalam satu atap pelayanan terpadu.',
        konten: 'Pelaksanaan Posyandu Integrasi Layanan Primer (ILP) di Balai RW 03 Kauman Wetan disambut antusias tinggi oleh masyarakat. Berbeda dengan posyandu konvensional, Posyandu ILP melayani siklus hidup lengkap mulai dari pemantauan gizi dan tumbuh kembang balita, skrining anemia remaja putri, hingga cek tensi dan gula darah gratis bagi warga lanjut usia.\n\nKetua TP PKK Kelurahan Kraksaan Wetan menyampaikan apresiasi kepada para kader posyandu yang berdedikasi menjaga kesehatan generasi penerus dan meminimalkan angka stunting di wilayah kelurahan.',
        gambar: 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80',
        dilihat: 198
    },
    {
        id: 4,
        slug: 'kerja-bakti-serentak-bersih-lingkungan-dan-saluran-air-warga-kraksaan-wetan',
        judul: 'Gerakan Kerja Bakti Bersih Saluran Air dan Lingkungan Hijau di 7 RW Kelurahan Kraksaan Wetan',
        kategori: 'Masyarakat',
        tanggal: '15 Februari 2026',
        penulis: 'Kasi Trantib',
        ringkasan: 'Semangat gotong royong warga terlihat nyata saat gotong royong massal membersihkan saluran air dan peremajaan taman lingkungan di seluruh RW.',
        konten: 'Minggu pagi menjadi momentum kebersamaan warga Kelurahan Kraksaan Wetan dalam aksi kerja bakti serentak. Warga bergotong-royong membersihkan sedimentasi parit, memangkas dahan pohon yang membahayakan kabel listrik, dan menata pot tanaman di sepanjang gang pemukiman.\n\nKegiatan ini juga menjadi sarana mempererat silaturahmi antarwarga serta mewujudkan lingkungan permukiman yang bersih, asri, dan terhindar dari potensi sarang nyamuk DBD.',
        gambar: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
        dilihat: 254
    },
    {
        id: 5,
        slug: 'sosialisasi-digitalisasi-administrasi-kependudukan-ikd-bagi-warga-kraksaan',
        judul: 'Sosialisasi Penerapan Identitas Kependudukan Digital (IKD) Dispendukcapil di Kraksaan Wetan',
        kategori: 'Pemerintahan',
        tanggal: '08 Februari 2026',
        penulis: 'Staf IT Kelurahan',
        ringkasan: 'Kelurahan Kraksaan Wetan memfasilitasi aktivasi aplikasi IKD di smartphone warga untuk mempermudah akses layanan publik tanpa perlu membawa KTP fisik.',
        konten: 'Seiring akselerasi Sistem Pemerintahan Berbasis Elektronik (SPBE) Pemerintah Kabupaten Probolinggo, Kelurahan Kraksaan Wetan bekerjasama dengan Dinas Kependudukan dan Pencatatan Sipil membuka loket khusus aktivasi Identitas Kependudukan Digital (IKD).\n\nPetugas mendampingi warga melakukan registrasi mulai dari scan QR code, verifikasi wajah (face recognition), hingga integrasi dokumen Kartu Keluarga dan Kartu BPJS ke dalam aplikasi telepon pintar masing-masing.',
        gambar: 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?auto=format&fit=crop&w=800&q=80',
        dilihat: 310
    }
];

export const mockPengumuman = [
    {
        id: 1,
        judul: 'Jadwal Pelayanan Pembayaran PBB-P2 Keliling BPPKAD di Kantor Kelurahan Kraksaan Wetan',
        tanggal: '10 Maret 2026',
        penyelenggara: 'BPPKAD Kab. Probolinggo & Kelurahan',
        prioritas: 'Penting',
        isi: 'Diberitahukan kepada seluruh warga wajib pajak Kelurahan Kraksaan Wetan, loket mobil keliling pembayaran PBB-P2 akan hadir pada hari Rabu, 18 Maret 2026 pukul 08.30 - 13.00 WIB di halaman kantor kelurahan. Warga dimohon membawa SPPT PBB tahun berjalan.',
        file: 'Pengumuman_PBB_2026.pdf'
    },
    {
        id: 2,
        judul: 'Himbauan Kewaspadaan Cuaca Ekstrem dan Pembersihan Saluran Air Musim Hujan',
        tanggal: '02 Maret 2026',
        penyelenggara: 'Kasi Trantib Kelurahan',
        prioritas: 'Himbauan',
        isi: 'Menindaklanjuti rilis BMKG dan BPBD Kabupaten Probolinggo terkait potensi hujan dengan intensitas sedang hingga lebat, seluruh pengurus RT/RW dihimbau menggerakkan warga membersihkan saluran pembuangan air dan tidak membuang sampah sembarangan ke sungai.',
        file: null
    },
    {
        id: 3,
        judul: 'Pendataan Calon Penerima Bantuan Pelatihan Keterampilan Wirausaha Pemuda Karang Taruna',
        tanggal: '25 Februari 2026',
        penyelenggara: 'Disnaker & Kelurahan',
        prioritas: 'Pemberitahuan',
        isi: 'Bagi pemuda warga Kraksaan Wetan rentang usia 18-30 tahun yang berminat mengikuti pelatihan barista kopi, tata boga, atau digital marketing bersertifikat BNSP, dipersilakan mendaftar ke Seksi Ekbang Kelurahan paling lambat 15 Maret 2026.',
        file: 'Form_Pelatihan_Disnaker.pdf'
    }
];

export const mockGaleri = [
    {
        id: 1,
        judul: 'Musrenbangkel Penetapan RKPD Kraksaan Wetan',
        kategori: 'Pemerintahan',
        tanggal: '04 Maret 2026',
        gambar: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
        deskripsi: 'Rapat koordinasi dan musyawarah perencanaan pembangunan bersama para ketua RW dan tokoh masyarakat.'
    },
    {
        id: 2,
        judul: 'Layanan Jemput Bola Kependudukan untuk Lansia dan Disabilitas',
        kategori: 'Pelayanan',
        tanggal: '26 Februari 2026',
        gambar: 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?auto=format&fit=crop&w=800&q=80',
        deskripsi: 'Petugas kelurahan mendatangi kediaman warga lanjut usia untuk pembaruan kartu keluarga dan berkas kependudukan.'
    },
    {
        id: 3,
        judul: 'Kegiatan Posyandu ILP Balita & Lansia RW 03 Kauman',
        kategori: 'Kesehatan',
        tanggal: '20 Februari 2026',
        gambar: 'https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?auto=format&fit=crop&w=800&q=80',
        deskripsi: 'Pemeriksaan kesehatan terpadu dan penimbangan berat badan balita untuk pencegahan stunting.'
    },
    {
        id: 4,
        judul: 'Gotong Royong Bersih Saluran Air dan Lingkungan Hijau',
        kategori: 'Kemasyarakatan',
        tanggal: '15 Februari 2026',
        gambar: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=80',
        deskripsi: 'Warga secara antusias membersihkan saluran irigasi dan lingkungan bersama aparat tiga pilar.'
    },
    {
        id: 5,
        judul: 'Penyaluran Bantuan Pangan Beras Cadangan Pemerintah',
        kategori: 'Sosial',
        tanggal: '28 Februari 2026',
        gambar: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?auto=format&fit=crop&w=800&q=80',
        deskripsi: 'Pendistribusian beras bantuan pangan bagi warga yang berhak secara tertib di pendopo kelurahan.'
    },
    {
        id: 6,
        judul: 'Bazar UMKM dan Produk Olahan Kuliner Kraksaan',
        kategori: 'Kemasyarakatan',
        tanggal: '10 Februari 2026',
        gambar: 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=800&q=80',
        deskripsi: 'Pameran aneka produk UMKM binaan kelurahan untuk menggeliatkan roda perekonomian lokal.'
    }
];

export const mockLembaga = [
    {
        id: 1,
        nama: 'Lembaga Pemberdayaan Masyarakat Kelurahan',
        singkatan: 'LPMK',
        kategori: 'Pemberdayaan Masyarakat',
        ketua: 'H. Suwandi, S.E.',
        kontak: '(0335) 841234 / ext. 104',
        alamat: 'Sekretariat Bersama LKK Kelurahan Kraksaan Wetan',
        deskripsi: 'Mitra kerja strategis pemerintah kelurahan dalam menampung dan menyalurkan aspirasi warga, mengawal musyawarah perencanaan pembangunan (Musrenbang), serta menggerakkan prakarsa gotong royong masyarakat dalam pembangunan fisik maupun non-fisik.',
        program_kerja: [
            'Penyusunan usulan Musrenbang Kelurahan partisipatif tahunan',
            'Monitoring dan evaluasi program pembangunan infrastruktur lingkungan',
            'Fasilitasi kemitraan UMKM warga dengan dinas terkait dan sektor swasta',
            'Penguatan ketahanan sosial dan gotong royong antar RW/RT'
        ],
        jumlah_anggota: '24 Pengurus & Koordinator Wilayah',
        warna_tema: 'amber',
        urutan: 1,
        aktif: true
    },
    {
        id: 2,
        nama: 'Tim Penggerak PKK Kelurahan',
        singkatan: 'TP-PKK',
        kategori: 'Kesejahteraan Keluarga',
        ketua: 'Hj. Nurul Hidayati, S.Pd.',
        kontak: 'pkk.kraksaanwetan@probolinggokab.go.id',
        alamat: 'Gedung Kartini / Balai Kelurahan Kraksaan Wetan',
        deskripsi: 'Gerakan pemberdayaan keluarga yang berfokus pada peningkatan taraf hidup, kesehatan ibu dan anak, pencegahan stunting melalui Posyandu terintegrasi, serta pelatihan keterampilan ekonomi kreatif bagi kaum perempuan.',
        program_kerja: [
            'Pembinaan 10 Program Pokok PKK tingkat RW dan RT se-Kraksaan Wetan',
            'Gerakan Cegah Stunting bersama Posyandu dan Puskesmas Kraksaan',
            'Pelatihan kewirausahaan boga, kriya, dan ekonomi digital ibu rumah tangga',
            'Sosialisasi pola asuh anak remaja dan penguatan ketahanan pangan pekarangan (HATINYA PKK)'
        ],
        jumlah_anggota: '42 Kader PKK & Dasa Wisma',
        warna_tema: 'rose',
        urutan: 2,
        aktif: true
    },
    {
        id: 3,
        nama: 'Karang Taruna Tunas Harapan',
        singkatan: 'Karang Taruna',
        kategori: 'Kepemudaan & Olahraga',
        ketua: 'Dimas Prasetyo, S.Kom.',
        kontak: '0822-4567-8901',
        alamat: 'Gedung Serbaguna Pemuda Kraksaan Wetan',
        deskripsi: 'Wadah pembinaan, kreativitas, dan pengembangan potensi generasi muda Kraksaan Wetan dalam bidang sosial kemanusiaan, kewirausahaan pemuda, seni budaya daerah, dan olahraga prestasi.',
        program_kerja: [
            'Penyelenggaraan turnamen olahraga antar lingkungan & peringatan HUT RI',
            'Pelatihan literasi digital, desain grafis, dan UMKM pemuda kreatif',
            'Aksi tanggap bencana, donor darah berkala, dan kerja bakti kebersihan sungai',
            'Pengembangan sanggar seni budaya lokal khas Probolinggo'
        ],
        jumlah_anggota: '38 Pengurus & Relawan Pemuda',
        warna_tema: 'blue',
        urutan: 3,
        aktif: true
    },
    {
        id: 4,
        nama: 'Satuan Perlindungan Masyarakat',
        singkatan: 'Satlinmas',
        kategori: 'Ketenteraman & Ketertiban',
        ketua: 'Slamet Riyadi',
        kontak: '(0335) 841234 (Posko Trantib)',
        alamat: 'Pos Komando Satlinmas Kelurahan Kraksaan Wetan',
        deskripsi: 'Garda terdepan pengamanan swakarsa warga, pemeliharaan ketenteraman dan ketertiban umum (Trantibum), patroli lingkungan terpadu, serta kesiapsiagaan penanggulangan bencana darurat di wilayah kelurahan.',
        program_kerja: [
            'Patroli lingkungan malam terpadu bersama Babinsa dan Bhabinkamtibmas',
            'Pengamanan kegiatan keagamaan, perayaan hari besar nasional, dan pemilu',
            'Pikad Pos Kamling dan sosialisasi ronda malam di seluruh RT/RW',
            'Kesiapsiagaan penanganan pohon tumbang, genangan air, dan evakuasi bencana'
        ],
        jumlah_anggota: '28 Anggota Satlinmas Terlatih',
        warna_tema: 'emerald',
        urutan: 4,
        aktif: true
    },
    {
        id: 5,
        nama: 'Forum Kader Kesehatan & Posyandu',
        singkatan: 'Posyandu',
        kategori: 'Kesehatan Masyarakat',
        ketua: 'dr. Retno Wulandari (Pembina) / Ny. Endang S.',
        kontak: 'posyandu.kraksaanwetan@probolinggokab.go.id',
        alamat: 'Posyandu Bougenville RW 01 - RW 07',
        deskripsi: 'Jaringan pos pelayanan terpadu yang memberikan pemantauan tumbuh kembang balita, imunisasi dasar lengkap, pemeriksaan kesehatan ibu hamil, serta posyandu lansia secara rutin setiap bulan di setiap RW.',
        program_kerja: [
            'Pelayanan rutin posyandu balita (penimbangan, ukur tinggi badan, vitamin A)',
            'Pemberian Makanan Tambahan (PMT) bergizi berbahan pangan lokal',
            'Posyandu Lansia untuk pemeriksaan tensi, gula darah, dan senam sehat',
            'Penyuluhan pola hidup bersih dan sehat (PHBS) serta sanitasi lingkungan'
        ],
        jumlah_anggota: '35 Kader Kesehatan Lingkungan',
        warna_tema: 'indigo',
        urutan: 5,
        aktif: true
    }
];

export const mockTransparansi = {
    summary: {
        tahun_terpilih: 2026,
        total_rencana: 545000000,
        total_realisasi: 476500000,
        total_sisa: 68500000,
        persentase_total: 87.4,
        total_kegiatan: 6,
        kegiatan_selesai: 1,
        kegiatan_berjalan: 5,
        daftar_tahun: [2026, 2025],
        daftar_kategori: [
            'Infrastruktur & Sarpras',
            'Pemberdayaan Masyarakat',
            'Bantuan Sosial & Kesehatan',
            'Pemerintahan & Pelayanan Digital'
        ],
        breakdown_kategori: [
            {
                kategori: 'Infrastruktur & Sarpras',
                rencana: 295000000,
                realisasi: 268500000,
                sisa: 26500000,
                persentase: 91.0,
                jumlah_kegiatan: 2
            },
            {
                kategori: 'Pemberdayaan Masyarakat',
                rencana: 65000000,
                realisasi: 52000000,
                sisa: 13000000,
                persentase: 80.0,
                jumlah_kegiatan: 1
            },
            {
                kategori: 'Bantuan Sosial & Kesehatan',
                rencana: 130000000,
                realisasi: 106500000,
                sisa: 23500000,
                persentase: 81.9,
                jumlah_kegiatan: 2
            },
            {
                kategori: 'Pemerintahan & Pelayanan Digital',
                rencana: 55000000,
                realisasi: 49500000,
                sisa: 5500000,
                persentase: 90.0,
                jumlah_kegiatan: 1
            }
        ]
    },
    kegiatan: [
        {
            id: 1,
            tahun: 2026,
            program: 'Program Peningkatan Sarana dan Prasarana Lingkungan Perkotaan',
            kegiatan: 'Pembangunan & Normalisasi Saluran Drainase U-Ditch Anti-Banjir',
            kategori: 'Infrastruktur & Sarpras',
            sumber_dana: 'Alokasi Dana Kelurahan (ADK)',
            anggaran_rencana: 175000000,
            anggaran_realisasi: 148500000,
            sisa_anggaran: 26500000,
            persentase_realisasi: 84.9,
            penerima_manfaat_target: '950 Jiwa (RW 02 Pasar Lama & RW 04 Patemon)',
            penerima_manfaat_realisasi: '950 Jiwa (Terbebas dari genangan air)',
            progres_fisik: 88,
            status: 'Sedang Berjalan',
            lokasi: 'Jl. Pattimura s.d. Gang Patemon RW 02 - RW 04',
            penanggung_jawab: 'Kasi Perekonomian & Pembangunan (Ekbang)',
            deskripsi: 'Pemasangan beton precast U-Ditch sepanjang 420 meter untuk memperlancar aliran drainase warga menuju Kali Rondokuning guna mencegah genangan saat musim penghujan.',
            urutan: 1,
            aktif: true
        },
        {
            id: 2,
            tahun: 2026,
            program: 'Program Peningkatan Sarana dan Prasarana Lingkungan Perkotaan',
            kegiatan: 'Pavingisasi Jalan Pemukiman & Penerangan Jalan Lingkungan Hemat Energi',
            kategori: 'Infrastruktur & Sarpras',
            sumber_dana: 'APBD Kab. Probolinggo',
            anggaran_rencana: 120000000,
            anggaran_realisasi: 120000000,
            sisa_anggaran: 0,
            persentase_realisasi: 100.0,
            penerima_manfaat_target: '420 KK di RW 03 Kauman & RW 05 Kebon Agung',
            penerima_manfaat_realisasi: '420 KK (Akses jalan tertata rapi & terang)',
            progres_fisik: 100,
            status: 'Selesai',
            lokasi: 'Kawasan Lingkungan RW 03 & RW 05',
            penanggung_jawab: 'Kasi Perekonomian & Pembangunan (Ekbang)',
            deskripsi: 'Peningkatan kualitas jalan paving block K-300 seluas 850 m² dan pemasangan 25 titik lampu PJU LED tenaga surya ramah lingkungan.',
            urutan: 2,
            aktif: true
        },
        {
            id: 3,
            tahun: 2026,
            program: 'Program Pemberdayaan Ekonomi Masyarakat & Penguatan UMKM',
            kegiatan: 'Pelatihan Digital Marketing, Desain Kemasan & Bantuan Sarana Usaha UMKM',
            kategori: 'Pemberdayaan Masyarakat',
            sumber_dana: 'Alokasi Dana Kelurahan (ADK)',
            anggaran_rencana: 65000000,
            anggaran_realisasi: 52000000,
            sisa_anggaran: 13000000,
            persentase_realisasi: 80.0,
            penerima_manfaat_target: '45 Pelaku Usaha Mikro & Industri Rumahan',
            penerima_manfaat_realisasi: '40 UMKM (Telah tersertifikasi halal & NIB)',
            progres_fisik: 85,
            status: 'Sedang Berjalan',
            lokasi: 'Balai Kelurahan Kraksaan Wetan',
            penanggung_jawab: 'Kasi Perekonomian & Pembangunan bersama LPMK',
            deskripsi: 'Pendampingan pembuatan izin NIB OSS, sertifikasi halal, foto produk katalog, dan bantuan kemasan kedap udara bagi produk olahan ikan dan boga khas Kraksaan.',
            urutan: 3,
            aktif: true
        },
        {
            id: 4,
            tahun: 2026,
            program: 'Program Jaring Pengaman Sosial & Peningkatan Derajat Kesehatan',
            kegiatan: 'Gerakan Terpadu Pencegahan Stunting & Pemberian Makanan Tambahan (PMT)',
            kategori: 'Bantuan Sosial & Kesehatan',
            sumber_dana: 'Alokasi Dana Kelurahan (ADK)',
            anggaran_rencana: 85000000,
            anggaran_realisasi: 68500000,
            sisa_anggaran: 16500000,
            persentase_realisasi: 80.6,
            penerima_manfaat_target: '120 Balita Resiko Stunting & 60 Ibu Hamil KEK',
            penerima_manfaat_realisasi: '115 Balita & 58 Ibu Hamil (BB meningkat)',
            progres_fisik: 80,
            status: 'Sedang Berjalan',
            lokasi: 'Posyandu Bougenville RW 01 s.d. RW 07',
            penanggung_jawab: 'Kasi Kesejahteraan Rakyat (Kesra) & TP-PKK',
            deskripsi: 'Penyaluran makanan bergizi berbahan dasar pangan lokal tinggi protein hewani selama 90 hari, suplemen vitamin, serta pendampingan intensif kader posyandu.',
            urutan: 4,
            aktif: true
        },
        {
            id: 5,
            tahun: 2026,
            program: 'Program Jaring Pengaman Sosial & Peningkatan Derajat Kesehatan',
            kegiatan: 'Validasi Faktual Data Terpadu Kesejahteraan Sosial (DTKS) & Penyaluran Bansos',
            kategori: 'Bantuan Sosial & Kesehatan',
            sumber_dana: 'Bagi Hasil Pajak & Retribusi',
            anggaran_rencana: 45000000,
            anggaran_realisasi: 38000000,
            sisa_anggaran: 7000000,
            persentase_realisasi: 84.4,
            penerima_manfaat_target: '385 KPM (Keluarga Penerima Manfaat) Prasejahtera',
            penerima_manfaat_realisasi: '385 KPM (Tepat sasaran)',
            progres_fisik: 90,
            status: 'Sedang Berjalan',
            lokasi: 'Pendopo Kelurahan Kraksaan Wetan',
            penanggung_jawab: 'Kasi Kesejahteraan Rakyat (Kesra)',
            deskripsi: 'Verifikasi dan validasi lapangan data kemiskinan ekstrem bekerjasama dengan pengurus RT/RW untuk memastikan bantuan beras cadangan pangan, PKH, dan BPNT tepat sasaran.',
            urutan: 5,
            aktif: true
        },
        {
            id: 6,
            tahun: 2026,
            program: 'Program Transformasi Digital & Tata Kelola Pelayanan Publik',
            kegiatan: 'Pengembangan Sistem Pelayanan Surat Online Mandiri & Kiosk Layanan Publik',
            kategori: 'Pemerintahan & Pelayanan Digital',
            sumber_dana: 'Bagi Hasil Pajak & Retribusi',
            anggaran_rencana: 55000000,
            anggaran_realisasi: 49500000,
            sisa_anggaran: 5500000,
            persentase_realisasi: 90.0,
            penerima_manfaat_target: 'Seluruh Warga Kelurahan Kraksaan Wetan (6.842 Jiwa)',
            penerima_manfaat_realisasi: 'Aktif digunakan warga untuk pengajuan online',
            progres_fisik: 95,
            status: 'Sedang Berjalan',
            lokasi: 'Kantor Kelurahan Kraksaan Wetan & Portal Web',
            penanggung_jawab: 'Sekretaris Kelurahan & Pengadministrasi Umum',
            deskripsi: 'Digitalisasi pengajuan dokumen kependudukan (KTP, Domisili, SKU, SKTM, SKCK) terintegrasi sistem tracking status berbasis web dan notifikasi WhatsApp.',
            urutan: 6,
            aktif: true
        }
    ]
};
