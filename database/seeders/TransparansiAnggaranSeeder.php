<?php

namespace Database\Seeders;

use App\Models\AnggaranRealisasi;
use App\Services\FileStorageHelper;
use Illuminate\Database\Seeder;

class TransparansiAnggaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1 Contoh Anggaran Resmi APBD Kelurahan Kraksaan Wetan Tahun Anggaran 2026
        $existing = AnggaranRealisasi::where('tahun', 2026)->first();
        if ($existing) {
            return;
        }

        // Pastikan berkas PDF lampiran resmi tersedia
        $pdfPath = FileStorageHelper::ensureValidPdfFile(
            'dokumen/APBD_Kraksaan_Wetan_TA_2026.pdf',
            'Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan Tahun Anggaran 2026',
            'Transparansi APBD',
            'APBD-2026/KW',
            'Ringkasan Laporan Realisasi Anggaran Pendapatan dan Belanja Kelurahan (APB-Kelurahan) Kraksaan Wetan Tahun Anggaran 2026. Dokumen resmi transparansi publik bagi warga masyarakat.'
        );

        $budget = AnggaranRealisasi::create([
            'tahun' => 2026,
            'judul' => 'Anggaran Pendapatan dan Belanja Kelurahan Kraksaan Wetan Tahun Anggaran 2026',
            'slug' => 'anggaran-pendapatan-dan-belanja-kelurahan-kraksaan-wetan-tahun-anggaran-2026',
            'tanggal_publikasi' => '2026-01-15',
            'deskripsi' => 'Ringkasan Laporan Realisasi Anggaran Pendapatan dan Belanja Kelurahan (APB-Kelurahan) Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo Tahun Anggaran 2026. Disajikan secara transparan dan akuntabel guna memberikan informasi keterbukaan pengelolaan keuangan publik kepada seluruh lapisan masyarakat.',
            'gambar' => null,
            'file_lampiran' => $pdfPath,
            'status' => 'published',
            'urutan' => 1,
        ]);

        $items = [
            // --- 1. PENDAPATAN ---
            // A. Pendapatan Asli
            [
                'tipe' => 'pendapatan',
                'kategori' => 'Pendapatan Asli Daerah (PAD) / Kelurahan',
                'uraian' => 'Hasil Pengelolaan Kekayaan & Pemanfaatan Aset Kelurahan',
                'anggaran' => 45000000,
                'realisasi' => 48500000,
                'urutan' => 1,
            ],
            [
                'tipe' => 'pendapatan',
                'kategori' => 'Pendapatan Asli Daerah (PAD) / Kelurahan',
                'uraian' => 'Swadaya, Partisipasi, dan Gotong Royong Masyarakat',
                'anggaran' => 25000000,
                'realisasi' => 26200000,
                'urutan' => 2,
            ],
            // B. Pendapatan Transfer
            [
                'tipe' => 'pendapatan',
                'kategori' => 'Pendapatan Transfer',
                'uraian' => 'Alokasi Dana Kelurahan (ADK) APBD Kabupaten Probolinggo',
                'anggaran' => 750000000,
                'realisasi' => 750000000,
                'urutan' => 3,
            ],
            [
                'tipe' => 'pendapatan',
                'kategori' => 'Pendapatan Transfer',
                'uraian' => 'Bagi Hasil Pajak Daerah dan Retribusi Daerah (BHPRD)',
                'anggaran' => 120000000,
                'realisasi' => 118500000,
                'urutan' => 4,
            ],
            // C. Pendapatan Lain-lain
            [
                'tipe' => 'pendapatan',
                'kategori' => 'Lain-lain Pendapatan yang Sah',
                'uraian' => 'Penerimaan Bantuan Keuangan Khusus & Hibah Daerah',
                'anggaran' => 50000000,
                'realisasi' => 50000000,
                'urutan' => 5,
            ],

            // --- 2. BELANJA ---
            // A. Belanja Operasi
            [
                'tipe' => 'belanja',
                'kategori' => 'Belanja Operasi',
                'uraian' => 'Belanja Pegawai (Honorarium Kelembagaan RT/RW, LPM, Linmas)',
                'anggaran' => 210000000,
                'realisasi' => 205000000,
                'urutan' => 6,
            ],
            [
                'tipe' => 'belanja',
                'kategori' => 'Belanja Operasi',
                'uraian' => 'Belanja Barang dan Jasa (Operasional Kantor, ATK, Listrik, Internet)',
                'anggaran' => 160000000,
                'realisasi' => 152400000,
                'urutan' => 7,
            ],
            [
                'tipe' => 'belanja',
                'kategori' => 'Belanja Operasi',
                'uraian' => 'Belanja Pemeliharaan Sarana Gedung Kantor & Fasilitas Umum',
                'anggaran' => 60000000,
                'realisasi' => 58600000,
                'urutan' => 8,
            ],
            // B. Belanja Modal
            [
                'tipe' => 'belanja',
                'kategori' => 'Belanja Modal',
                'uraian' => 'Belanja Modal Pembangunan & Normalisasi Saluran Drainase U-Ditch',
                'anggaran' => 240000000,
                'realisasi' => 238500000,
                'urutan' => 9,
            ],
            [
                'tipe' => 'belanja',
                'kategori' => 'Belanja Modal',
                'uraian' => 'Belanja Modal Pavingisasi Jalan Pemukiman & Penerangan Lingkungan',
                'anggaran' => 180000000,
                'realisasi' => 179000000,
                'urutan' => 10,
            ],
            [
                'tipe' => 'belanja',
                'kategori' => 'Belanja Modal',
                'uraian' => 'Belanja Modal Pengadaan Kiosk Pelayanan Surat Digital & Perangkat IT',
                'anggaran' => 90000000,
                'realisasi' => 88500000,
                'urutan' => 11,
            ],
            // C. Belanja Tidak Terduga
            [
                'tipe' => 'belanja',
                'kategori' => 'Belanja Tidak Terduga',
                'uraian' => 'Belanja Penanggulangan Bencana & Keadaan Mendesak Lingkungan',
                'anggaran' => 40000000,
                'realisasi' => 25000000,
                'urutan' => 12,
            ],

            // --- 3. PEMBIAYAAN ---
            // A. Penerimaan Pembiayaan
            [
                'tipe' => 'pembiayaan',
                'kategori' => 'Penerimaan Pembiayaan',
                'uraian' => 'Sisa Lebih Perhitungan Anggaran (SiLPA) Tahun Anggaran Sebelumnya',
                'anggaran' => 15000000,
                'realisasi' => 15000000,
                'urutan' => 13,
            ],
            // B. Pengeluaran Pembiayaan
            [
                'tipe' => 'pembiayaan',
                'kategori' => 'Pengeluaran Pembiayaan',
                'uraian' => 'Pembentukan Dana Cadangan Kelurahan Kraksaan Wetan',
                'anggaran' => 10000000,
                'realisasi' => 10000000,
                'urutan' => 14,
            ],
        ];

        foreach ($items as $itemData) {
            $budget->items()->create($itemData);
        }

        $budget->recalculateTotals();
    }
}
