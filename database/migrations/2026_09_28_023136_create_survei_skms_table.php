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
        Schema::create('survei_skms', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->string('periode')->default('Tahunan');
            $table->decimal('skor_ikm', 5, 2);
            $table->decimal('skala_maksimal', 5, 2)->default(100.00);
            $table->string('mutu_pelayanan')->default('A (Sangat Baik)');
            $table->string('predikat')->default('Sangat Baik');
            $table->integer('jumlah_responden')->default(0);
            $table->text('metodologi')->nullable();
            $table->json('unsur_penilaian')->nullable();
            $table->string('link_survei')->nullable();
            $table->string('file_laporan')->nullable();
            $table->boolean('aktif')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survei_skms');
    }
};
