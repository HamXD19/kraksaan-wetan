<?php

namespace Database\Seeders;

use App\Models\Lembaga;
use Illuminate\Database\Seeder;

class LembagaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Lembaga Pemberdayaan Masyarakat Kelurahan',
                'singkatan' => 'LPMK',
                'kategori' => 'Pemberdayaan Masyarakat',
                'ketua' => 'H. Suwandi, S.E.',
                'kontak' => '(0335) 841234 / ext. 104',
                'alamat' => 'Sekretariat Bersama LKK Kelurahan Kraksaan Wetan',
                'deskripsi' => 'Mitra kerja strategis pemerintah kelurahan dalam menampung dan menyalurkan aspirasi warga, mengawal musyawarah perencanaan pembangunan (Musrenbang), serta menggerakkan prakarsa gotong royong masyarakat dalam pembangunan fisik maupun non-fisik.',
                'program_kerja' => [
                    'Penyusunan usulan Musrenbang Kelurahan partisipatif tahunan',
                    'Monitoring dan evaluasi program pembangunan infrastruktur lingkungan',
                    'Fasilitasi kemitraan UMKM warga dengan dinas terkait dan sektor swasta',
                    'Penguatan ketahanan sosial dan gotong royong antar RW/RT',
                ],
                'jumlah_anggota' => '24 Pengurus & Koordinator Wilayah',
                'warna_tema' => 'amber',
                'urutan' => 1,
                'aktif' => true,
            ],
            [
                'nama' => 'Tim Penggerak PKK Kelurahan',
                'singkatan' => 'TP-PKK',
                'kategori' => 'Kesejahteraan Keluarga',
                'ketua' => 'Hj. Nurul Hidayati, S.Pd.',
                'kontak' => 'pkk.kraksaanwetan@probolinggokab.go.id',
                'alamat' => 'Gedung Kartini / Balai Kelurahan Kraksaan Wetan',
                'deskripsi' => 'Gerakan pemberdayaan keluarga yang berfokus pada peningkatan taraf hidup, kesehatan ibu dan anak, pencegahan stunting melalui Posyandu terintegrasi, serta pelatihan keterampilan ekonomi kreatif bagi kaum perempuan.',
                'program_kerja' => [
                    'Pembinaan 10 Program Pokok PKK tingkat RW dan RT se-Kraksaan Wetan',
                    'Gerakan Cegah Stunting bersama Posyandu dan Puskesmas Kraksaan',
                    'Pelatihan kewirausahaan boga, kriya, dan ekonomi digital ibu rumah tangga',
                    'Sosialisasi pola asuh anak remaja dan penguatan ketahanan pangan pekarangan (HATINYA PKK)',
                ],
                'jumlah_anggota' => '42 Kader PKK & Dasa Wisma',
                'warna_tema' => 'rose',
                'urutan' => 2,
                'aktif' => true,
            ],
            [
                'nama' => 'Karang Taruna Tunas Harapan',
                'singkatan' => 'Karang Taruna',
                'kategori' => 'Kepemudaan & Olahraga',
                'ketua' => 'Dimas Prasetyo, S.Kom.',
                'kontak' => '0822-4567-8901',
                'alamat' => 'Gedung Serbaguna Pemuda Kraksaan Wetan',
                'deskripsi' => 'Wadah pembinaan, kreativitas, dan pengembangan potensi generasi muda Kraksaan Wetan dalam bidang sosial kemanusiaan, kewirausahaan pemuda, seni budaya daerah, dan olahraga prestasi.',
                'program_kerja' => [
                    'Penyelenggaraan turnamen olahraga antar lingkungan & peringatan HUT RI',
                    'Pelatihan literasi digital, desain grafis, dan UMKM pemuda kreatif',
                    'Aksi tanggap bencana, donor darah berkala, dan kerja bakti kebersihan sungai',
                    'Pengembangan sanggar seni budaya lokal khas Probolinggo',
                ],
                'jumlah_anggota' => '38 Pengurus & Relawan Pemuda',
                'warna_tema' => 'blue',
                'urutan' => 3,
                'aktif' => true,
            ],
            [
                'nama' => 'Satuan Perlindungan Masyarakat',
                'singkatan' => 'Satlinmas',
                'kategori' => 'Ketenteraman & Ketertiban',
                'ketua' => 'Slamet Riyadi',
                'kontak' => '(0335) 841234 (Posko Trantib)',
                'alamat' => 'Pos Komando Satlinmas Kelurahan Kraksaan Wetan',
                'deskripsi' => 'Garda terdepan pengamanan swakarsa warga, pemeliharaan ketenteraman dan ketertiban umum (Trantibum), patroli lingkungan terpadu, serta kesiapsiagaan penanggulangan bencana darurat di wilayah kelurahan.',
                'program_kerja' => [
                    'Patroli lingkungan malam terpadu bersama Babinsa dan Bhabinkamtibmas',
                    'Pengamanan kegiatan keagamaan, perayaan hari besar nasional, dan pemilu',
                    'Pikad Pos Kamling dan sosialisasi ronda malam di seluruh RT/RW',
                    'Kesiapsiagaan penanganan pohon tumbang, genangan air, dan evakuasi bencana',
                ],
                'jumlah_anggota' => '28 Anggota Satlinmas Terlatih',
                'warna_tema' => 'emerald',
                'urutan' => 4,
                'aktif' => true,
            ],
            [
                'nama' => 'Forum Kader Kesehatan & Posyandu',
                'singkatan' => 'Posyandu',
                'kategori' => 'Kesehatan Masyarakat',
                'ketua' => 'dr. Retno Wulandari (Pembina) / Ny. Endang S.',
                'kontak' => 'posyandu.kraksaanwetan@probolinggokab.go.id',
                'alamat' => 'Posyandu Bougenville RW 01 - RW 07',
                'deskripsi' => 'Jaringan pos pelayanan terpadu yang memberikan pemantauan tumbuh kembang balita, imunisasi dasar lengkap, pemeriksaan kesehatan ibu hamil, serta posyandu lansia secara rutin setiap bulan di setiap RW.',
                'program_kerja' => [
                    'Pelayanan rutin posyandu balita (penimbangan, ukur tinggi badan, vitamin A)',
                    'Pemberian Makanan Tambahan (PMT) bergizi berbahan pangan lokal',
                    'Posyandu Lansia untuk pemeriksaan tensi, gula darah, dan senam sehat',
                    'Penyuluhan pola hidup bersih dan sehat (PHBS) serta sanitasi lingkungan',
                ],
                'jumlah_anggota' => '35 Kader Kesehatan Lingkungan',
                'warna_tema' => 'indigo',
                'urutan' => 5,
                'aktif' => true,
            ],
        ];

        foreach ($data as $item) {
            Lembaga::updateOrCreate(
                ['singkatan' => $item['singkatan']],
                $item
            );
        }
    }
}
