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

    protected $casts = [
        'tampil_running_text' => 'boolean',
    ];

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeRunningText($query)
    {
        return $query->where('status', 'published')->where('tampil_running_text', true);
    }

    protected static function booted(): void
    {
        static::deleting(function (Berita $berita) {
            FileStorageHelper::deleteFileIfLocal($berita->gambar);
        });
    }
}
