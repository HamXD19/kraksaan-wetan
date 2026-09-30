<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Model;

class HalamanKustom extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (HalamanKustom $halaman) {
            FileStorageHelper::deleteFileIfLocal($halaman->gambar);
        });
    }
}
