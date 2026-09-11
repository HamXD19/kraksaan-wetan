<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Profil Kelurahan
        Schema::create('profil_kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->default('Kelurahan Kraksaan Wetan');
            $table->string('kecamatan')->default('Kraksaan');
            $table->string('kabupaten')->default('Kabupaten Probolinggo');
            $table->string('provinsi')->default('Jawa Timur');
            $table->string('kode_pos')->default('67282');
            $table->text('alamat');
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('jam_kerja')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('sejarah')->nullable();
            $table->text('visi')->nullable();
            $table->json('misi')->nullable();
            $table->string('lurah_nama')->nullable();
            $table->string('lurah_nip')->nullable();
            $table->string('lurah_jabatan')->default('Lurah Kraksaan Wetan');
            $table->text('lurah_sambutan')->nullable();
            $table->string('lurah_foto')->nullable();
            $table->timestamps();
        });

        // 2. Perangkat Kelurahan
        Schema::create('perangkat_kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jabatan');
            $table->string('bidang')->nullable();
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 3. Statistik Wilayah
        Schema::create('statistiks', function (Blueprint $table) {
            $table->id();
            $table->integer('penduduk')->default(0);
            $table->integer('kk')->default(0);
            $table->integer('laki_laki')->default(0);
            $table->integer('perempuan')->default(0);
            $table->integer('rt')->default(0);
            $table->integer('rw')->default(0);
            $table->string('luas_wilayah')->default('1.84');
            $table->string('kepadatan')->nullable();
            $table->timestamps();
        });

        // 4. Lingkungan RW/RT
        Schema::create('lingkungans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->integer('rt')->default(0);
            $table->integer('penduduk')->default(0);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 5. Layanan Masyarakat
        Schema::create('layanans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('judul');
            $table->string('kategori');
            $table->string('icon')->default('FileText');
            $table->text('deskripsi');
            $table->json('persyaratan')->nullable();
            $table->text('alur')->nullable();
            $table->string('waktu')->default('10 - 15 Menit');
            $table->string('biaya')->default('Gratis (Rp 0)');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // 6. Berita Kelurahan
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('judul');
            $table->string('kategori');
            $table->string('tanggal');
            $table->string('penulis')->default('Tim Humas Kelurahan');
            $table->text('ringkasan');
            $table->longText('konten');
            $table->string('gambar')->nullable();
            $table->integer('dilihat')->default(0);
            $table->timestamps();
        });

        // 7. Pengumuman
        Schema::create('pengumumans', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('tanggal');
            $table->string('penyelenggara')->nullable();
            $table->string('prioritas')->default('Pemberitahuan');
            $table->text('isi');
            $table->string('file')->nullable();
            $table->timestamps();
        });

        // 8. Galeri Dokumentasi
        Schema::create('galeris', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori');
            $table->string('tanggal');
            $table->string('gambar');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // 9. Pesan / Aspirasi Warga
        Schema::create('pesans', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('telepon');
            $table->string('email')->nullable();
            $table->string('kategori');
            $table->text('pesan');
            $table->enum('status', ['baru', 'dibaca', 'dibalas'])->default('baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesans');
        Schema::dropIfExists('galeris');
        Schema::dropIfExists('pengumumans');
        Schema::dropIfExists('beritas');
        Schema::dropIfExists('layanans');
        Schema::dropIfExists('lingkungans');
        Schema::dropIfExists('statistiks');
        Schema::dropIfExists('perangkat_kelurahans');
        Schema::dropIfExists('profil_kelurahans');
    }
};
