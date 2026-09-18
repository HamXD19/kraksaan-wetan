<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use HasFactory;

    protected $table = 'galeris';

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::deleting(function (Galeri $galeri) {
            FileStorageHelper::deleteFileIfLocal($galeri->gambar);
        });
    }
}
