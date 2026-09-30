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
        Schema::table('profil_kelurahans', function (Blueprint $table) {
            if (! Schema::hasColumn('profil_kelurahans', 'batas_wilayah')) {
                $table->json('batas_wilayah')->nullable()->after('deskripsi');
            }
            if (! Schema::hasColumn('profil_kelurahans', 'potensi_unggulan')) {
                $table->json('potensi_unggulan')->nullable()->after('batas_wilayah');
            }
            if (! Schema::hasColumn('profil_kelurahans', 'tata_nilai')) {
                $table->json('tata_nilai')->nullable()->after('misi');
            }
            if (! Schema::hasColumn('profil_kelurahans', 'sejarah_timeline')) {
                $table->json('sejarah_timeline')->nullable()->after('sejarah');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_kelurahans', function (Blueprint $table) {
            $table->dropColumn(['batas_wilayah', 'potensi_unggulan', 'tata_nilai', 'sejarah_timeline']);
        });
    }
};
