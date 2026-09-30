<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaklumatPelayanan extends Model
{
    use HasFactory;

    protected $table = 'maklumat_pelayanans';

    protected $guarded = ['id'];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (MaklumatPelayanan $maklumat) {
            // Jika data ini berstatus aktif, otomatis nonaktifkan semua data maklumat lainnya
            if ($maklumat->aktif) {
                static::where('id', '!=', $maklumat->id)->update(['aktif' => false]);
            }
        });

        static::deleting(function (MaklumatPelayanan $maklumat) {
            FileStorageHelper::deleteFileIfLocal($maklumat->gambar);
        });
    }
}
