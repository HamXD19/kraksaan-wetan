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
        Schema::create('maklumat_pelayanans', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('Maklumat Pelayanan Kelurahan Kraksaan Wetan');
            $table->string('nomor_sk')->nullable();
            $table->longText('konten');
            $table->string('motto')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maklumat_pelayanans');
    }
};
