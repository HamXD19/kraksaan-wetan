<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('master_kategoris') && ! Schema::hasColumn('master_kategoris', 'parent_id')) {
            Schema::table('master_kategoris', function (Blueprint $table) {
                $table->foreignId('parent_id')
                    ->nullable()
                    ->after('modul')
                    ->constrained('master_kategoris')
                    ->nullOnDelete();
            });
        }

        // Siapkan taksonomi master kategori & subkategori awal untuk modul dokumen
        $now = now();
        $dokumenTaxonomy = [
            [
                'kategori' => 'Perencanaan & Pembangunan',
                'warna' => 'emerald',
                'keterangan' => 'Dokumen perencanaan strategis, musrenbang, dan program pembangunan kelurahan',
                'subkategoris' => [
                    'Dokumen Perencanaan',
                    'Rencana Kerja',
                    'Musrenbang',
                    'Program Pembangunan',
                    'Evaluasi Pembangunan',
                ],
            ],
            [
                'kategori' => 'Keuangan & Anggaran',
                'warna' => 'amber',
                'keterangan' => 'Dokumen anggaran, realisasi belanja, laporan keuangan, dan pertanggungjawaban dana',
                'subkategoris' => [
                    'Anggaran',
                    'Perubahan Anggaran',
                    'Realisasi Anggaran',
                    'Laporan Keuangan',
                    'Pertanggungjawaban',
                ],
            ],
            [
                'kategori' => 'Pemerintahan & Administrasi',
                'warna' => 'blue',
                'keterangan' => 'Laporan kinerja pemerintahan, data kelurahan, profil wilayah, dan administrasi kedinasan',
                'subkategoris' => [
                    'Laporan Pemerintahan',
                    'Laporan Kinerja',
                    'Profil Kelurahan',
                    'Data Kelurahan',
                    'Administrasi Pemerintahan',
                ],
            ],
            [
                'kategori' => 'Pelayanan Publik',
                'warna' => 'rose',
                'keterangan' => 'Standar operasional prosedur (SOP), formulir blangko permohonan, dan laporan pelayanan masyarakat',
                'subkategoris' => [
                    'Standar Pelayanan',
                    'SOP Pelayanan',
                    'Laporan Pelayanan',
                    'Informasi Pelayanan',
                    'Formulir Layanan',
                ],
            ],
            [
                'kategori' => 'Kegiatan & Kemasyarakatan',
                'warna' => 'purple',
                'keterangan' => 'Laporan kegiatan pemberdayaan warga, gotong royong, dan agenda kemasyarakatan kelurahan',
                'subkategoris' => [
                    'Laporan Kegiatan',
                    'Pemberdayaan Masyarakat',
                    'Kegiatan Kemasyarakatan',
                    'Kegiatan Kelurahan',
                ],
            ],
            [
                'kategori' => 'Transparansi & Akuntabilitas',
                'warna' => 'emerald',
                'keterangan' => 'Laporan pertanggungjawaban terbuka, keterbukaan informasi publik, dan akuntabilitas kelurahan',
                'subkategoris' => [
                    'Laporan Pertanggungjawaban',
                    'Informasi Anggaran',
                    'Laporan Kinerja Transparansi',
                    'Informasi Publik',
                ],
            ],
        ];

        $urutanKategori = 0;
        foreach ($dokumenTaxonomy as $item) {
            $urutanKategori++;
            $parentSlug = Str::slug($item['kategori']);

            // Insert or find parent
            $parentId = DB::table('master_kategoris')->where('modul', 'dokumen')->where('slug', $parentSlug)->value('id');
            if (! $parentId) {
                $parentId = DB::table('master_kategoris')->insertGetId([
                    'modul' => 'dokumen',
                    'parent_id' => null,
                    'nama' => $item['kategori'],
                    'slug' => $parentSlug,
                    'keterangan' => $item['keterangan'],
                    'warna' => $item['warna'],
                    'urutan' => $urutanKategori,
                    'is_aktif' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }

            $urutanSub = 0;
            foreach ($item['subkategoris'] as $subNama) {
                $urutanSub++;
                $subSlug = Str::slug($item['kategori'].'-'.$subNama);

                // Periksa apakah subkategori sudah ada
                $subExists = DB::table('master_kategoris')->where('modul', 'dokumen')->where('slug', $subSlug)->exists();
                if (! $subExists) {
                    DB::table('master_kategoris')->insert([
                        'modul' => 'dokumen',
                        'parent_id' => $parentId,
                        'nama' => $subNama,
                        'slug' => $subSlug,
                        'keterangan' => 'Subkategori '.$subNama.' di bawah '.$item['kategori'],
                        'warna' => $item['warna'],
                        'urutan' => $urutanSub,
                        'is_aktif' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('master_kategoris') && Schema::hasColumn('master_kategoris', 'parent_id')) {
            Schema::table('master_kategoris', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }
    }
};
