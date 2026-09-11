<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'service_requests';

    protected $guarded = ['id'];

    protected $casts = [
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ServiceDocument::class, 'service_request_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(ServiceRequestHistory::class, 'service_request_id')->latest();
    }

    /**
     * Generate Nomor Pengajuan unik berformat KW-YYYYMMDD-XXXX
     */
    public static function generateNomorPengajuan(): string
    {
        $prefix = 'KW-'.date('Ymd').'-';
        $last = self::where('nomor_pengajuan', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->lockForUpdate()
            ->first();

        if ($last && preg_match('/-(\d{4})$/', $last->nomor_pengajuan, $matches)) {
            $seq = (int) $matches[1] + 1;
        } else {
            $seq = 1;
        }

        return $prefix.str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Masked NIK untuk keamanan publik (contoh: 351307******0001)
     */
    public function getMaskedNikAttribute(): string
    {
        $len = strlen($this->nik);
        if ($len <= 8) {
            return str_repeat('*', $len);
        }

        return substr($this->nik, 0, 6).'******'.substr($this->nik, -4);
    }

    /**
     * Masked No KK untuk keamanan publik
     */
    public function getMaskedNoKkAttribute(): ?string
    {
        if (! $this->no_kk) {
            return null;
        }
        $len = strlen($this->no_kk);
        if ($len <= 8) {
            return str_repeat('*', $len);
        }

        return substr($this->no_kk, 0, 6).'******'.substr($this->no_kk, -4);
    }
}
