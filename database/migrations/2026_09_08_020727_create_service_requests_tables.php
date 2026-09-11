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
        if (! Schema::hasColumn('layanans', 'aktif')) {
            Schema::table('layanans', function (Blueprint $table) {
                $table->boolean('aktif')->default(true)->after('urutan');
            });
        }

        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan')->unique();
            $table->foreignId('layanan_id')->constrained('layanans')->onDelete('restrict');
            $table->string('nama_pemohon');
            $table->string('nik', 16)->index();
            $table->string('no_kk', 16)->nullable();
            $table->string('no_hp', 25);
            $table->text('alamat');
            $table->text('keperluan');
            $table->text('keterangan')->nullable();
            $table->string('status')->default('Menunggu Verifikasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('service_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->string('nama_dokumen');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type')->nullable();
            $table->bigInteger('file_size')->nullable();
            $table->timestamps();
        });

        Schema::create('service_request_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_request_id')->constrained('service_requests')->onDelete('cascade');
            $table->string('status');
            $table->text('catatan')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_request_histories');
        Schema::dropIfExists('service_documents');
        Schema::dropIfExists('service_requests');

        if (Schema::hasColumn('layanans', 'aktif')) {
            Schema::table('layanans', function (Blueprint $table) {
                $table->dropColumn('aktif');
            });
        }
    }
};

