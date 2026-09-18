<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    protected $table = 'beritas';

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::deleting(function (Berita $berita) {
            FileStorageHelper::deleteFileIfLocal($berita->gambar);
        });
    }
}
