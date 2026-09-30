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
            $table->longText('logo')->nullable()->change();
            $table->longText('hero_image')->nullable()->change();
            $table->longText('lurah_foto')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_kelurahans', function (Blueprint $table) {
            $table->string('logo', 255)->nullable()->change();
            $table->string('hero_image', 255)->nullable()->change();
            $table->string('lurah_foto', 255)->nullable()->change();
        });
    }
};
