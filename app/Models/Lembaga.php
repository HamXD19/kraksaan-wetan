<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembaga extends Model
{
    use HasFactory;

    protected $table = 'lembagas';

    protected $guarded = ['id'];

    protected $casts = [
        'program_kerja' => 'array',
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Lembaga $lembaga) {
            FileStorageHelper::deleteFileIfLocal($lembaga->logo);
        });
    }
}
