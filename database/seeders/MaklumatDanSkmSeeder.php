<?php

namespace Database\Seeders;

use App\Models\MaklumatPelayanan;
use App\Models\SurveiSkm;
use Illuminate\Database\Seeder;

class MaklumatDanSkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MaklumatPelayanan::updateOrCreate(
            ['id' => 1],
            [
                'judul' => 'Maklumat Pelayanan Kelurahan Kraksaan Wetan',
                'nomor_sk' => 'SK Lurah Kraksaan Wetan No. 188.45/04/426.411.01/2026',
                'motto' => 'Melayani dengan Ikhlas, Ramah, Cepat, Akuntabel, dan 100% Bebas Biaya (Gratis)',
                'konten' => "Dengan ini, kami seluruh jajaran pimpinan dan aparatur Pemerintah Kelurahan Kraksaan Wetan, Kecamatan Kraksaan, Kabupaten Probolinggo menyatakan sanggup menyelenggarakan pelayanan publik sesuai Standar Operasional Prosedur (SOP) yang telah ditetapkan.\n\nKami berkomitmen melayani warga dengan tulus, transparan, cepat, tidak diskriminatif, dan tidak memungut biaya apapun (Rp 0). Apabila kami tidak menepati maklumat ini, kami siap menerima sanksi sesuai ketentuan peraturan perundang-undangan demi peningkatan kualitas pelayanan publik bagi masyarakat.",
                'gambar' => null,
                'aktif' => true,
            ]
        );

        SurveiSkm::updateOrCreate(
            ['tahun' => '2026', 'periode' => 'Semester I'],
            [
                'skor_ikm' => 89.42,
                'skala_maksimal' => 100.00,
                'mutu_pelayanan' => 'A (Sangat Baik)',
                'predikat' => 'Sangat Baik',
                'jumlah_responden' => 284,
                'metodologi' => 'Survei Kepuasan Masyarakat (SKM) Semester I Tahun 2026 disusun berdasarkan Peraturan Menteri PAN-RB Nomor 14 Tahun 2017 tentang Pedoman Penyusunan Survei Kepuasan Masyarakat Unit Penyelenggara Pelayanan Publik. Nilai rata-rata tertimbang dikonversi ke skala interval 25 - 100.',
                'unsur_penilaian' => [
                    ['nama' => 'Kesesuaian Persyaratan Pelayanan', 'nilai' => 89.80, 'bobot' => 1.0],
                    ['nama' => 'Kemudahan Alur & Prosedur Pelayanan', 'nilai' => 90.15, 'bobot' => 1.0],
                    ['nama' => 'Kecepatan Waktu Penyelesaian Berkas', 'nilai' => 88.50, 'bobot' => 1.0],
                    ['nama' => 'Kepastian Biaya / Tarif (100% Gratis Rp 0)', 'nilai' => 98.75, 'bobot' => 1.0],
                    ['nama' => 'Kesesuaian Produk Spesifikasi Layanan', 'nilai' => 89.20, 'bobot' => 1.0],
                    ['nama' => 'Kompetensi Petugas Pelayanan Publik', 'nilai' => 88.90, 'bobot' => 1.0],
                    ['nama' => 'Kesopanan, Disiplin & Perilaku Petugas', 'nilai' => 91.25, 'bobot' => 1.0],
                    ['nama' => 'Kualitas Sarana & Prasarana Ruang Layanan', 'nilai' => 86.40, 'bobot' => 1.0],
                    ['nama' => 'Penanganan Pengaduan, Saran & Masukan', 'nilai' => 88.85, 'bobot' => 1.0],
                ],
                'link_survei' => 'https://forms.gle/KraksaanWetanSKM2026',
                'file_laporan' => null,
                'aktif' => true,
                'urutan' => 1,
            ]
        );

        SurveiSkm::updateOrCreate(
            ['tahun' => '2025', 'periode' => 'Tahunan'],
            [
                'skor_ikm' => 87.85,
                'skala_maksimal' => 100.00,
                'mutu_pelayanan' => 'A (Sangat Baik)',
                'predikat' => 'Sangat Baik',
                'jumlah_responden' => 450,
                'metodologi' => 'Penilaian SKM Tahunan 2025 merekapitulasi seluruh indeks kepuasan warga masyarakat yang terlayani di loket pelayanan terpadu Kelurahan Kraksaan Wetan sepanjang tahun anggaran 2025.',
                'unsur_penilaian' => [
                    ['nama' => 'Kesesuaian Persyaratan Pelayanan', 'nilai' => 87.50, 'bobot' => 1.0],
                    ['nama' => 'Kemudahan Alur & Prosedur', 'nilai' => 88.20, 'bobot' => 1.0],
                    ['nama' => 'Kecepatan Waktu Layanan', 'nilai' => 86.40, 'bobot' => 1.0],
                    ['nama' => 'Biaya / Tarif Pelayanan (Bebas Pungli)', 'nilai' => 97.50, 'bobot' => 1.0],
                    ['nama' => 'Perilaku & Keramahan Petugas', 'nilai' => 90.10, 'bobot' => 1.0],
                    ['nama' => 'Sarana & Prasarana Ruang Pelayanan', 'nilai' => 85.50, 'bobot' => 1.0],
                    ['nama' => 'Pengelolaan Pengaduan Masyarakat', 'nilai' => 87.70, 'bobot' => 1.0],
                ],
                'link_survei' => null,
                'file_laporan' => null,
                'aktif' => true,
                'urutan' => 2,
            ]
        );
    }
}
