<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnggaranRealisasiItem extends Model
{
    use HasFactory;

    protected $table = 'anggaran_realisasi_items';

    protected $guarded = ['id'];

    protected $casts = [
        'anggaran' => 'float',
        'realisasi' => 'float',
        'selisih' => 'float',
        'persentase' => 'float',
        'urutan' => 'integer',
    ];

    public function anggaranHeader(): BelongsTo
    {
        return $this->belongsTo(AnggaranRealisasi::class, 'anggaran_id');
    }

    /**
     * Boot model events for automatic selisih and persentase calculation.
     */
    protected static function booted(): void
    {
        static::saving(function (self $item) {
            $anggaran = (float) $item->anggaran;
            $realisasi = (float) $item->realisasi;

            // Selisih = Realisasi - Anggaran
            $item->selisih = $realisasi - $anggaran;

            // Persentase serapan / pencapaian
            $item->persentase = $anggaran > 0 ? round(($realisasi / $anggaran) * 100, 2) : 0.0;
        });
    }
}
