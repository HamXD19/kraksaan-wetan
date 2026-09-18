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
        Schema::table('dokumens', function (Blueprint $table) {
            if (! Schema::hasColumn('dokumens', 'subkategori')) {
                $table->string('subkategori')->nullable()->after('kategori');
            }

            if (! Schema::hasColumn('dokumens', 'periode')) {
                $table->string('periode')->nullable()->default('Tahunan')->after('deskripsi');
            }

            if (! Schema::hasColumn('dokumens', 'periode_ke')) {
                $table->string('periode_ke')->nullable()->after('periode');
            }

            if (! Schema::hasColumn('dokumens', 'tahun_selesai')) {
                $table->string('tahun_selesai')->nullable()->after('tahun');
            }

            if (! Schema::hasColumn('dokumens', 'tanggal_publikasi')) {
                $table->date('tanggal_publikasi')->nullable()->after('aktif');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokumens', function (Blueprint $table) {
            $columns = [];
            foreach (['subkategori', 'periode', 'periode_ke', 'tahun_selesai', 'tanggal_publikasi'] as $col) {
                if (Schema::hasColumn('dokumens', $col)) {
                    $columns[] = $col;
                }
            }
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
