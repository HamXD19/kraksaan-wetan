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
            if (! Schema::hasColumn('profil_kelurahans', 'custom_nav_menus')) {
                $table->json('custom_nav_menus')->nullable()->after('halo_sae_link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_kelurahans', function (Blueprint $table) {
            if (Schema::hasColumn('profil_kelurahans', 'custom_nav_menus')) {
                $table->dropColumn('custom_nav_menus');
            }
        });
    }
};
