<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumens';

    protected $guarded = ['id'];

    protected $casts = [
        'aktif' => 'boolean',
        'diunduh' => 'integer',
        'tanggal_publikasi' => 'date:Y-m-d',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Dokumen $dokumen) {
            FileStorageHelper::deleteFileIfLocal($dokumen->file);
        });
    }

    protected $appends = [

        'label_periode_lengkap',
        'tanggal_format',
    ];

    public function getLabelPeriodeLengkapAttribute(): string
    {
        $periode = $this->periode ?: 'Tahunan';

        if ($periode === '5 Tahunan') {
            if ($this->tahun && $this->tahun_selesai) {
                return "Periode {$this->tahun}–{$this->tahun_selesai}";
            }

            return $this->tahun ? "Periode {$this->tahun}" : '5 Tahunan';
        }

        if ($periode === 'Triwulanan' || $periode === 'Semesteran' || $periode === 'Bulanan') {
            $parts = [];
            if ($this->tahun) {
                $parts[] = $this->tahun;
            }
            if ($this->periode_ke) {
                $parts[] = $this->periode_ke;
            }

            return ! empty($parts) ? implode(' — ', $parts) : $periode;
        }

        if ($periode === 'Tahunan') {
            return $this->tahun ? "Tahun {$this->tahun}" : 'Tahunan';
        }

        if ($periode === 'Sewaktu-waktu') {
            return $this->tahun ? "Sewaktu-waktu ({$this->tahun})" : 'Sewaktu-waktu / Insidental';
        }

        return $this->tahun ? "Tahun {$this->tahun}" : 'Umum';
    }

    public function getTanggalFormatAttribute(): string
    {
        $date = $this->tanggal_publikasi ?: $this->created_at;
        if (! $date) {
            return '-';
        }

        return Carbon::parse($date)->translatedFormat('d F Y');
    }
}
