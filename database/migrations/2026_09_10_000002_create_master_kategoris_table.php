<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('master_kategoris')) {
            Schema::create('master_kategoris', function (Blueprint $table) {
                $table->id();
                $table->string('modul'); // berita, pengumuman, layanan, galeri, lembaga, transparansi
                $table->string('nama');
                $table->string('slug');
                $table->text('keterangan')->nullable();
                $table->string('warna')->default('emerald')->nullable();
                $table->integer('urutan')->default(0);
                $table->boolean('is_aktif')->default(true);
                $table->timestamps();

                $table->index('modul');
                $table->unique(['modul', 'slug']);
            });
        }

        // Seed initial categories from existing data safely
        $now = now();
        $initialData = [
            // Berita
            ['modul' => 'berita', 'nama' => 'Pemerintahan', 'keterangan' => 'Kategori berita kebijakan dan pemerintahan kelurahan', 'warna' => 'emerald'],
            ['modul' => 'berita', 'nama' => 'Masyarakat', 'keterangan' => 'Kategori berita seputar kegiatan warga', 'warna' => 'blue'],
            ['modul' => 'berita', 'nama' => 'Kesehatan', 'keterangan' => 'Kategori berita posyandu dan kesehatan', 'warna' => 'rose'],

            // Pengumuman
            ['modul' => 'pengumuman', 'nama' => 'Kedinasan', 'keterangan' => 'Edaran dan pengumuman resmi dinas kelurahan', 'warna' => 'emerald'],
            ['modul' => 'pengumuman', 'nama' => 'Pelayanan Publik', 'keterangan' => 'Pengumuman operasional pelayanan masyarakat', 'warna' => 'amber'],
            ['modul' => 'pengumuman', 'nama' => 'Himbauan Warga', 'keterangan' => 'Himbauan ketertiban, kebersihan, dan keamanan', 'warna' => 'rose'],
            ['modul' => 'pengumuman', 'nama' => 'Agenda Kegiatan', 'keterangan' => 'Jadwal dan agenda kegiatan tingkat kelurahan', 'warna' => 'blue'],

            // Layanan
            ['modul' => 'layanan', 'nama' => 'Kependudukan', 'keterangan' => 'Layanan administrasi kartu keluarga, KTP, dan domisili', 'warna' => 'emerald'],
            ['modul' => 'layanan', 'nama' => 'Keterangan', 'keterangan' => 'Surat keterangan kelakuan baik, belum menikah, dsb.', 'warna' => 'blue'],
            ['modul' => 'layanan', 'nama' => 'Perekonomian', 'keterangan' => 'Surat keterangan usaha, izin operasional mikro', 'warna' => 'amber'],
            ['modul' => 'layanan', 'nama' => 'Kesejahteraan Sosial', 'keterangan' => 'Surat keterangan tidak mampu, DTKS, bantuan sosial', 'warna' => 'rose'],
            ['modul' => 'layanan', 'nama' => 'Ketertiban Umum', 'keterangan' => 'Izin keramaian, pelaporan ketertiban lingkungan', 'warna' => 'purple'],

            // Galeri
            ['modul' => 'galeri', 'nama' => 'Pemerintahan', 'keterangan' => 'Dokumentasi kegiatan dinas dan aparatur', 'warna' => 'emerald'],
            ['modul' => 'galeri', 'nama' => 'Pelayanan', 'keterangan' => 'Dokumentasi pelayanan tatap muka', 'warna' => 'blue'],
            ['modul' => 'galeri', 'nama' => 'Kesehatan', 'keterangan' => 'Dokumentasi posyandu dan bakti kesehatan', 'warna' => 'rose'],
            ['modul' => 'galeri', 'nama' => 'Kemasyarakatan', 'keterangan' => 'Dokumentasi gotong royong dan kemasyarakatan', 'warna' => 'amber'],
            ['modul' => 'galeri', 'nama' => 'Sosial', 'keterangan' => 'Dokumentasi kegiatan penyaluran bantuan sosial', 'warna' => 'purple'],

            // Lembaga
            ['modul' => 'lembaga', 'nama' => 'Pemberdayaan Masyarakat', 'keterangan' => 'Lembaga LPMK dan RT/RW', 'warna' => 'emerald'],
            ['modul' => 'lembaga', 'nama' => 'Kesejahteraan Keluarga', 'keterangan' => 'PKK dan Dasawisma', 'warna' => 'rose'],
            ['modul' => 'lembaga', 'nama' => 'Kepemudaan & Olahraga', 'keterangan' => 'Karang Taruna dan komunitas pemuda', 'warna' => 'blue'],
            ['modul' => 'lembaga', 'nama' => 'Ketenteraman & Ketertiban', 'keterangan' => 'Linmas dan pos kamling', 'warna' => 'amber'],
            ['modul' => 'lembaga', 'nama' => 'Kesehatan Masyarakat', 'keterangan' => 'Kader Posyandu dan jumantik', 'warna' => 'purple'],

            // Transparansi
            ['modul' => 'transparansi', 'nama' => 'Infrastruktur & Sarpras', 'keterangan' => 'Alokasi pembangunan jalan, drainase, dan fasilitas umum', 'warna' => 'emerald'],
            ['modul' => 'transparansi', 'nama' => 'Pemberdayaan Masyarakat', 'keterangan' => 'Alokasi pelatihan kerja, UMKM, dan kepemudaan', 'warna' => 'amber'],
            ['modul' => 'transparansi', 'nama' => 'Bantuan Sosial & Kesehatan', 'keterangan' => 'Alokasi program stunting, lansia, dan bantuan dhuafa', 'warna' => 'rose'],
            ['modul' => 'transparansi', 'nama' => 'Pemerintahan & Pelayanan Digital', 'keterangan' => 'Alokasi operasional kantor kelurahan dan TI pelayanan', 'warna' => 'blue'],
        ];

        $urutanPerModul = [];
        foreach ($initialData as $item) {
            $modul = $item['modul'];
            $urutanPerModul[$modul] = ($urutanPerModul[$modul] ?? 0) + 1;
            
            DB::table('master_kategoris')->updateOrInsert(
                [
                    'modul' => $modul,
                    'slug' => Str::slug($item['nama']),
                ],
                [
                    'nama' => $item['nama'],
                    'keterangan' => $item['keterangan'],
                    'warna' => $item['warna'],
                    'urutan' => $urutanPerModul[$modul],
                    'is_aktif' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kategoris');
    }
};
