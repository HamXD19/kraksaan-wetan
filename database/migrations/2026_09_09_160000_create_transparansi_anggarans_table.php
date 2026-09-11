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
        Schema::create('transparansi_anggarans', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun')->index();
            $table->string('program');
            $table->string('kegiatan');
            $table->string('kategori'); // Infrastruktur & Sarpras, Pemberdayaan Masyarakat, Bantuan Sosial & Kesehatan, Pemerintahan & Pelayanan Digital
            $table->string('sumber_dana'); // Alokasi Dana Kelurahan (ADK), APBD Kab. Probolinggo, BKK, Bagi Hasil Pajak
            $table->decimal('anggaran_rencana', 15, 2);
            $table->decimal('anggaran_realisasi', 15, 2)->default(0);
            $table->string('penerima_manfaat_target')->nullable();
            $table->string('penerima_manfaat_realisasi')->nullable();
            $table->integer('progres_fisik')->default(0); // 0 - 100%
            $table->string('status')->default('Sedang Berjalan'); // Rencana, Sedang Berjalan, Selesai, Evaluasi
            $table->string('lokasi')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transparansi_anggarans');
    }
};
