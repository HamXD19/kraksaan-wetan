<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    protected $table = 'pengumumans';

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::deleting(function (Pengumuman $pengumuman) {
            FileStorageHelper::deleteFilesIfLocal([
                $pengumuman->file,
                $pengumuman->banner,
                $pengumuman->thumbnail,
            ]);
        });
    }
}
