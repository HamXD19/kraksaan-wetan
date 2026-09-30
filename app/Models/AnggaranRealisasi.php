<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AnggaranRealisasi extends Model
{
    use HasFactory;

    protected $table = 'anggaran_realisasis';

    protected $guarded = ['id'];

    protected $casts = [
        'tahun' => 'integer',
        'tanggal_publikasi' => 'date:Y-m-d',
        'total_pendapatan_rencana' => 'float',
        'total_pendapatan_realisasi' => 'float',
        'total_belanja_rencana' => 'float',
        'total_belanja_realisasi' => 'float',
        'total_pembiayaan_penerimaan_rencana' => 'float',
        'total_pembiayaan_penerimaan_realisasi' => 'float',
        'total_pembiayaan_pengeluaran_rencana' => 'float',
        'total_pembiayaan_pengeluaran_realisasi' => 'float',
        'pembiayaan_netto_rencana' => 'float',
        'pembiayaan_netto_realisasi' => 'float',
        'silpa_rencana' => 'float',
        'silpa_realisasi' => 'float',
        'urutan' => 'integer',
    ];

    protected $appends = [
        'surplus_defisit_rencana',
        'surplus_defisit_realisasi',
        'persentase_pendapatan',
        'persentase_belanja',
        'persentase_pembiayaan_netto',
        'is_published',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(AnggaranRealisasiItem::class, 'anggaran_id')->orderBy('urutan')->orderBy('id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('tahun', $year);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    public function getSurplusDefisitRencanaAttribute(): float
    {
        return (float) $this->total_pendapatan_rencana - (float) $this->total_belanja_rencana;
    }

    public function getSurplusDefisitRealisasiAttribute(): float
    {
        return (float) $this->total_pendapatan_realisasi - (float) $this->total_belanja_realisasi;
    }

    public function getPersentasePendapatanAttribute(): float
    {
        return $this->total_pendapatan_rencana > 0
            ? round(($this->total_pendapatan_realisasi / $this->total_pendapatan_rencana) * 100, 2)
            : 0.0;
    }

    public function getPersentaseBelanjaAttribute(): float
    {
        return $this->total_belanja_rencana > 0
            ? round(($this->total_belanja_realisasi / $this->total_belanja_rencana) * 100, 2)
            : 0.0;
    }

    public function getPersentasePembiayaanNettoAttribute(): float
    {
        return $this->pembiayaan_netto_rencana != 0
            ? round(($this->pembiayaan_netto_realisasi / $this->pembiayaan_netto_rencana) * 100, 2)
            : 0.0;
    }

    /**
     * Hitung ulang dan simpan akumulasi nilai anggaran dari tabel detail items.
     */
    public function recalculateTotals(): void
    {
        $items = $this->items()->get();

        $pendapatanItems = $items->where('tipe', 'pendapatan');
        $belanjaItems = $items->where('tipe', 'belanja');
        $pembiayaanItems = $items->where('tipe', 'pembiayaan');

        $penerimaanItems = $pembiayaanItems->filter(function ($i) {
            return str_contains(strtolower($i->kategori), 'penerimaan');
        });

        $pengeluaranItems = $pembiayaanItems->filter(function ($i) {
            return str_contains(strtolower($i->kategori), 'pengeluaran');
        });

        $totalPendapatanRencana = (float) $pendapatanItems->sum('anggaran');
        $totalPendapatanRealisasi = (float) $pendapatanItems->sum('realisasi');

        $totalBelanjaRencana = (float) $belanjaItems->sum('anggaran');
        $totalBelanjaRealisasi = (float) $belanjaItems->sum('realisasi');

        $totalPenerimaanRencana = (float) $penerimaanItems->sum('anggaran');
        $totalPenerimaanRealisasi = (float) $penerimaanItems->sum('realisasi');

        $totalPengeluaranRencana = (float) $pengeluaranItems->sum('anggaran');
        $totalPengeluaranRealisasi = (float) $pengeluaranItems->sum('realisasi');

        $pembiayaanNettoRencana = $totalPenerimaanRencana - $totalPengeluaranRencana;
        $pembiayaanNettoRealisasi = $totalPenerimaanRealisasi - $totalPengeluaranRealisasi;

        // SILPA (Sisa Lebih Pembiayaan Anggaran) = (Pendapatan - Belanja) + Pembiayaan Netto
        $silpaRencana = ($totalPendapatanRencana - $totalBelanjaRencana) + $pembiayaanNettoRencana;
        $silpaRealisasi = ($totalPendapatanRealisasi - $totalBelanjaRealisasi) + $pembiayaanNettoRealisasi;

        $this->updateQuietly([
            'total_pendapatan_rencana' => $totalPendapatanRencana,
            'total_pendapatan_realisasi' => $totalPendapatanRealisasi,
            'total_belanja_rencana' => $totalBelanjaRencana,
            'total_belanja_realisasi' => $totalBelanjaRealisasi,
            'total_pembiayaan_penerimaan_rencana' => $totalPenerimaanRencana,
            'total_pembiayaan_penerimaan_realisasi' => $totalPenerimaanRealisasi,
            'total_pembiayaan_pengeluaran_rencana' => $totalPengeluaranRencana,
            'total_pembiayaan_pengeluaran_realisasi' => $totalPengeluaranRealisasi,
            'pembiayaan_netto_rencana' => $pembiayaanNettoRencana,
            'pembiayaan_netto_realisasi' => $pembiayaanNettoRealisasi,
            'silpa_rencana' => $silpaRencana,
            'silpa_realisasi' => $silpaRealisasi,
        ]);
    }

    /**
     * Boot model events for automatic slug generation & file cleanup on delete.
     */
    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $base = Str::slug($model->judul ?: "apbd-{$model->tahun}");
                $slug = $base;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$base}-{$count}";
                    $count++;
                }
                $model->slug = $slug;
            }
        });

        static::updating(function (self $model) {
            if ($model->isDirty('judul') && ! $model->isDirty('slug')) {
                $base = Str::slug($model->judul ?: "apbd-{$model->tahun}");
                $slug = $base;
                $count = 1;
                while (static::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
                    $slug = "{$base}-{$count}";
                    $count++;
                }
                $model->slug = $slug;
            }

            // Cleanup old files if replaced
            if ($model->isDirty('gambar')) {
                $old = $model->getOriginal('gambar');
                if ($old && $old !== $model->gambar) {
                    FileStorageHelper::deleteFileIfLocal($old);
                }
            }

            if ($model->isDirty('file_lampiran')) {
                $old = $model->getOriginal('file_lampiran');
                if ($old && $old !== $model->file_lampiran) {
                    FileStorageHelper::deleteFileIfLocal($old);
                }
            }
        });

        static::deleting(function (self $model) {
            // Hapus berkas fisik sampul dan lampiran PDF dari storage saat data dihapus
            FileStorageHelper::deleteFileIfLocal($model->gambar);
            FileStorageHelper::deleteFileIfLocal($model->file_lampiran);
        });
    }
}
