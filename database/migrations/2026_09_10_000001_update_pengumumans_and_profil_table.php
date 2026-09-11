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
        Schema::table('pengumumans', function (Blueprint $table) {
            if (!Schema::hasColumn('pengumumans', 'banner')) {
                $table->string('banner')->nullable()->after('isi');
            }
            if (!Schema::hasColumn('pengumumans', 'thumbnail')) {
                $table->string('thumbnail')->nullable()->after('banner');
            }
            if (!Schema::hasColumn('pengumumans', 'kategori')) {
                $table->string('kategori')->nullable()->after('thumbnail');
            }
        });

        Schema::table('profil_kelurahans', function (Blueprint $table) {
            if (!Schema::hasColumn('profil_kelurahans', 'link_span_lapor')) {
                $table->string('link_span_lapor')->default('https://www.lapor.go.id/')->nullable()->after('email');
            }
            if (!Schema::hasColumn('profil_kelurahans', 'halo_sae_wa')) {
                $table->string('halo_sae_wa')->default('082131001001')->nullable()->after('link_span_lapor');
            }
            if (!Schema::hasColumn('profil_kelurahans', 'halo_sae_link')) {
                $table->string('halo_sae_link')->default('https://halosae.probolinggokab.go.id')->nullable()->after('halo_sae_wa');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengumumans', function (Blueprint $table) {
            if (Schema::hasColumn('pengumumans', 'banner')) {
                $table->dropColumn('banner');
            }
            if (Schema::hasColumn('pengumumans', 'thumbnail')) {
                $table->dropColumn('thumbnail');
            }
            if (Schema::hasColumn('pengumumans', 'kategori')) {
                $table->dropColumn('kategori');
            }
        });

        Schema::table('profil_kelurahans', function (Blueprint $table) {
            if (Schema::hasColumn('profil_kelurahans', 'link_span_lapor')) {
                $table->dropColumn('link_span_lapor');
            }
            if (Schema::hasColumn('profil_kelurahans', 'halo_sae_wa')) {
                $table->dropColumn('halo_sae_wa');
            }
            if (Schema::hasColumn('profil_kelurahans', 'halo_sae_link')) {
                $table->dropColumn('halo_sae_link');
            }
        });
    }
};
