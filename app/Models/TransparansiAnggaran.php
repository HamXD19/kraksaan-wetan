<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransparansiAnggaran extends Model
{
    use HasFactory;

    protected $table = 'transparansi_anggarans';

    protected $guarded = ['id'];

    protected $casts = [
        'tahun' => 'integer',
        'anggaran_rencana' => 'float',
        'anggaran_realisasi' => 'float',
        'progres_fisik' => 'integer',
        'urutan' => 'integer',
        'aktif' => 'boolean',
    ];

    protected $appends = [
        'sisa_anggaran',
        'persentase_realisasi',
    ];

    /**
     * Hitung sisa anggaran rencana yang belum terealisasi.
     */
    public function getSisaAnggaranAttribute(): float
    {
        return max(0, (float) $this->anggaran_rencana - (float) $this->anggaran_realisasi);
    }

    /**
     * Hitung persentase realisasi anggaran terhadap rencana.
     */
    public function getPersentaseRealisasiAttribute(): float
    {
        if ((float) $this->anggaran_rencana <= 0) {
            return 0.0;
        }

        return round(((float) $this->anggaran_realisasi / (float) $this->anggaran_rencana) * 100, 1);
    }
}
