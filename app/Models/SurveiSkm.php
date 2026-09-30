<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveiSkm extends Model
{
    use HasFactory;

    protected $table = 'survei_skms';

    protected $guarded = ['id'];

    protected $casts = [
        'unsur_penilaian' => 'array',
        'skor_ikm' => 'float',
        'skala_maksimal' => 'float',
        'jumlah_responden' => 'integer',
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    protected static function booted(): void
    {
        static::deleting(function (SurveiSkm $skm) {
            FileStorageHelper::deleteFileIfLocal($skm->file_laporan);
        });
    }
}
