<?php

namespace Database\Seeders;

use App\Models\TransparansiAnggaran;
use Illuminate\Database\Seeder;

class TransparansiAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // Tahun Anggaran 2026 (Tahun Berjalan)
            [
                'tahun' => 2026,
                'program' => 'Program Peningkatan Sarana dan Prasarana Lingkungan Perkotaan',
                'kegiatan' => 'Pembangunan & Normalisasi Saluran Drainase U-Ditch Anti-Banjir',
                'kategori' => 'Infrastruktur & Sarpras',
                'sumber_dana' => 'Alokasi Dana Kelurahan (ADK)',
                'anggaran_rencana' => 175000000,
                'anggaran_realisasi' => 148500000,
                'penerima_manfaat_target' => '950 Jiwa (RW 02 Pasar Lama & RW 04 Patemon)',
                'penerima_manfaat_realisasi' => '950 Jiwa (Terbebas dari genangan air)',
                'progres_fisik' => 88,
                'status' => 'Sedang Berjalan',
                'lokasi' => 'Jl. Pattimura s.d. Gang Patemon RW 02 - RW 04',
                'penanggung_jawab' => 'Kasi Perekonomian & Pembangunan (Ekbang)',
                'deskripsi' => 'Pemasangan beton precast U-Ditch sepanjang 420 meter untuk memperlancar aliran drainase warga menuju Kali Rondokuning guna mencegah genangan saat musim penghujan.',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'tahun' => 2026,
                'program' => 'Program Peningkatan Sarana dan Prasarana Lingkungan Perkotaan',
                'kegiatan' => 'Pavingisasi Jalan Pemukiman & Penerangan Jalan Lingkungan Hemat Energi',
                'kategori' => 'Infrastruktur & Sarpras',
                'sumber_dana' => 'APBD Kab. Probolinggo',
                'anggaran_rencana' => 120000000,
                'anggaran_realisasi' => 120000000,
                'penerima_manfaat_target' => '420 KK di RW 03 Kauman & RW 05 Kebon Agung',
                'penerima_manfaat_realisasi' => '420 KK (Akses jalan tertata rapi & terang)',
                'progres_fisik' => 100,
                'status' => 'Selesai',
                'lokasi' => 'Kawasan Lingkungan RW 03 & RW 05',
                'penanggung_jawab' => 'Kasi Perekonomian & Pembangunan (Ekbang)',
                'deskripsi' => 'Peningkatan kualitas jalan paving block K-300 seluas 850 m² dan pemasangan 25 titik lampu PJU LED tenaga surya ramah lingkungan.',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'tahun' => 2026,
                'program' => 'Program Pemberdayaan Ekonomi Masyarakat & Penguatan UMKM',
                'kegiatan' => 'Pelatihan Digital Marketing, Desain Kemasan & Bantuan Sarana Usaha UMKM',
                'kategori' => 'Pemberdayaan Masyarakat',
                'sumber_dana' => 'Alokasi Dana Kelurahan (ADK)',
                'anggaran_rencana' => 65000000,
                'anggaran_realisasi' => 52000000,
                'penerima_manfaat_target' => '45 Pelaku Usaha Mikro & Industri Rumahan',
                'penerima_manfaat_realisasi' => '40 UMKM (Telah tersertifikasi halal & NIB)',
                'progres_fisik' => 85,
                'status' => 'Sedang Berjalan',
                'lokasi' => 'Balai Kelurahan Kraksaan Wetan',
                'penanggung_jawab' => 'Kasi Perekonomian & Pembangunan bersama LPMK',
                'deskripsi' => 'Pendampingan pembuatan izin NIB OSS, sertifikasi halal, foto produk katalog, dan bantuan kemasan kedap udara bagi produk olahan ikan dan boga khas Kraksaan.',
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'tahun' => 2026,
                'program' => 'Program Jaring Pengaman Sosial & Peningkatan Derajat Kesehatan',
                'kegiatan' => 'Gerakan Terpadu Pencegahan Stunting & Pemberian Makanan Tambahan (PMT)',
                'kategori' => 'Bantuan Sosial & Kesehatan',
                'sumber_dana' => 'Alokasi Dana Kelurahan (ADK)',
                'anggaran_rencana' => 85000000,
                'anggaran_realisasi' => 68500000,
                'penerima_manfaat_target' => '120 Balita Resiko Stunting & 60 Ibu Hamil KEK',
                'penerima_manfaat_realisasi' => '115 Balita & 58 Ibu Hamil (BB meningkat)',
                'progres_fisik' => 80,
                'status' => 'Sedang Berjalan',
                'lokasi' => 'Posyandu Bougenville RW 01 s.d. RW 07',
                'penanggung_jawab' => 'Kasi Kesejahteraan Rakyat (Kesra) & TP-PKK',
                'deskripsi' => 'Penyaluran makanan bergizi berbahan dasar pangan lokal tinggi protein hewani selama 90 hari, suplemen vitamin, serta pendampingan intensif kader posyandu.',
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'tahun' => 2026,
                'program' => 'Program Jaring Pengaman Sosial & Peningkatan Derajat Kesehatan',
                'kegiatan' => 'Validasi Faktual Data Terpadu Kesejahteraan Sosial (DTKS) & Penyaluran Bansos',
                'kategori' => 'Bantuan Sosial & Kesehatan',
                'sumber_dana' => 'Bagi Hasil Pajak & Retribusi',
                'anggaran_rencana' => 45000000,
                'anggaran_realisasi' => 38000000,
                'penerima_manfaat_target' => '385 KPM (Keluarga Penerima Manfaat) Prasejahtera',
                'penerima_manfaat_realisasi' => '385 KPM (Tepat sasaran)',
                'progres_fisik' => 90,
                'status' => 'Sedang Berjalan',
                'lokasi' => 'Pendopo Kelurahan Kraksaan Wetan',
                'penanggung_jawab' => 'Kasi Kesejahteraan Rakyat (Kesra)',
                'deskripsi' => 'Verifikasi dan validasi lapangan data kemiskinan ekstrem bekerjasama dengan pengurus RT/RW untuk memastikan bantuan beras cadangan pangan, PKH, dan BPNT tepat sasaran.',
                'urutan' => 5,
                'aktif' => true,
            ],
            [
                'tahun' => 2026,
                'program' => 'Program Transformasi Digital & Tata Kelola Pelayanan Publik',
                'kegiatan' => 'Pengembangan Sistem Pelayanan Surat Online Mandiri & Kiosk Layanan Publik',
                'kategori' => 'Pemerintahan & Pelayanan Digital',
                'sumber_dana' => 'Bagi Hasil Pajak & Retribusi',
                'anggaran_rencana' => 55000000,
                'anggaran_realisasi' => 49500000,
                'penerima_manfaat_target' => 'Seluruh Warga Kelurahan Kraksaan Wetan (6.842 Jiwa)',
                'penerima_manfaat_realisasi' => 'Aktif digunakan warga untuk pengajuan online',
                'progres_fisik' => 95,
                'status' => 'Sedang Berjalan',
                'lokasi' => 'Kantor Kelurahan Kraksaan Wetan & Portal Web',
                'penanggung_jawab' => 'Sekretaris Kelurahan & Pengadministrasi Umum',
                'deskripsi' => 'Digitalisasi pengajuan dokumen kependudukan (KTP, Domisili, SKU, SKTM, SKCK) terintegrasi sistem tracking status berbasis web dan notifikasi WhatsApp.',
                'urutan' => 6,
                'aktif' => true,
            ],

            // Tahun Anggaran 2025 (Tahun Sebelumnya)
            [
                'tahun' => 2025,
                'program' => 'Program Peningkatan Sarana dan Prasarana Lingkungan Perkotaan',
                'kegiatan' => 'Pembangunan Talud Penahan Tanah & Penghijauan Bantaran Kali Rondokuning',
                'kategori' => 'Infrastruktur & Sarpras',
                'sumber_dana' => 'Alokasi Dana Kelurahan (ADK)',
                'anggaran_rencana' => 150000000,
                'anggaran_realisasi' => 149200000,
                'penerima_manfaat_target' => 'Warga Sempadan Sungai RW 01 & RW 06',
                'penerima_manfaat_realisasi' => 'Tanggul kokoh, 500 pohon ditanam',
                'progres_fisik' => 100,
                'status' => 'Selesai',
                'lokasi' => 'Sisi Timur Kali Rondokuning',
                'penanggung_jawab' => 'Kasi Ekbang',
                'deskripsi' => 'Penguatan talud pasangan batu kali sepanjang 300 meter untuk mencegah erosi bibir sungai dan penanaman pohon peneduh.',
                'urutan' => 7,
                'aktif' => true,
            ],
            [
                'tahun' => 2025,
                'program' => 'Program Pemberdayaan Ekonomi Masyarakat & Penguatan UMKM',
                'kegiatan' => 'Penyelenggaraan Festival Kuliner Rakyat & Pameran Produk UMKM Kraksaan',
                'kategori' => 'Pemberdayaan Masyarakat',
                'sumber_dana' => 'APBD Kab. Probolinggo',
                'anggaran_rencana' => 45000000,
                'anggaran_realisasi' => 44800000,
                'penerima_manfaat_target' => '60 Pedagang Kuliner & Ribuan Pengunjung',
                'penerima_manfaat_realisasi' => 'Perputaran transaksi UMKM mencapai Rp 120 Juta',
                'progres_fisik' => 100,
                'status' => 'Selesai',
                'lokasi' => 'Kawasan Sentra Kuliner Kraksaan Wetan',
                'penanggung_jawab' => 'Kasi Ekbang bersama Karang Taruna',
                'deskripsi' => 'Pameran ekonomi kreatif dan bazar rakyat guna meningkatkan pendapatan warga pasca panen raya.',
                'urutan' => 8,
                'aktif' => true,
            ],
        ];

        foreach ($data as $item) {
            TransparansiAnggaran::updateOrCreate(
                [
                    'tahun' => $item['tahun'],
                    'kegiatan' => $item['kegiatan'],
                ],
                $item
            );
        }
    }
}
