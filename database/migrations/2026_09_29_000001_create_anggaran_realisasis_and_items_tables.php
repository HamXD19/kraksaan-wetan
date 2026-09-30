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
        Schema::create('anggaran_realisasis', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('judul');
            $table->integer('tahun')->index();
            $table->date('tanggal_publikasi')->index();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('file_lampiran')->nullable();
            $table->enum('status', ['draft', 'published'])->default('published')->index();

            // Cached totals for faster list queries & calculations
            $table->decimal('total_pendapatan_rencana', 15, 2)->default(0);
            $table->decimal('total_pendapatan_realisasi', 15, 2)->default(0);
            $table->decimal('total_belanja_rencana', 15, 2)->default(0);
            $table->decimal('total_belanja_realisasi', 15, 2)->default(0);
            $table->decimal('total_pembiayaan_penerimaan_rencana', 15, 2)->default(0);
            $table->decimal('total_pembiayaan_penerimaan_realisasi', 15, 2)->default(0);
            $table->decimal('total_pembiayaan_pengeluaran_rencana', 15, 2)->default(0);
            $table->decimal('total_pembiayaan_pengeluaran_realisasi', 15, 2)->default(0);
            $table->decimal('pembiayaan_netto_rencana', 15, 2)->default(0);
            $table->decimal('pembiayaan_netto_realisasi', 15, 2)->default(0);
            $table->decimal('silpa_rencana', 15, 2)->default(0);
            $table->decimal('silpa_realisasi', 15, 2)->default(0);

            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('anggaran_realisasi_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('anggaran_id')->constrained('anggaran_realisasis')->cascadeOnDelete();
            $table->enum('tipe', ['pendapatan', 'belanja', 'pembiayaan'])->index();
            $table->string('kategori', 150)->index(); // Sub-kategori / bidang
            $table->string('uraian');
            $table->decimal('anggaran', 15, 2)->default(0); // Rencana / Anggaran
            $table->decimal('realisasi', 15, 2)->default(0); // Realisasi
            $table->decimal('selisih', 15, 2)->default(0); // Lebih / (Kurang)
            $table->decimal('persentase', 8, 2)->default(0);
            $table->string('keterangan')->nullable();
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggaran_realisasi_items');
        Schema::dropIfExists('anggaran_realisasis');
    }
};
