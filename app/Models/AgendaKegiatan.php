<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaKegiatan extends Model
{
    use HasFactory;

    protected $table = 'agenda_kegiatans';

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::deleting(function (AgendaKegiatan $agenda) {
            FileStorageHelper::deleteFileIfLocal($agenda->foto);
        });
    }

    /**
     * The attributes that should be cast.

     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal_mulai' => 'datetime',
            'tanggal_selesai' => 'datetime',
            'is_aktif' => 'boolean',
        ];
    }

    /**
     * Appends accessors to array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'status_agenda',
        'status_label',
        'is_expired',
        'formatted_jadwal',
    ];

    /**
     * Status agenda saat ini.
     */
    public function getStatusAgendaAttribute(): string
    {
        if (! $this->is_aktif) {
            return 'nonaktif';
        }

        $now = Carbon::now();

        if ($this->tanggal_selesai && $now->greaterThan($this->tanggal_selesai)) {
            return 'selesai';
        }

        if ($this->tanggal_mulai && $now->greaterThanOrEqualTo($this->tanggal_mulai) && $now->lessThanOrEqualTo($this->tanggal_selesai)) {
            return 'berlangsung';
        }

        return 'akan_datang';
    }

    /**
     * Label human-readable untuk status agenda.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status_agenda) {
            'berlangsung' => 'Sedang Berlangsung',
            'akan_datang' => 'Akan Datang',
            'selesai' => 'Selesai',
            'nonaktif' => 'Nonaktif',
            default => 'Akan Datang',
        };
    }

    /**
     * Apakah agenda sudah melewati batas tanggal_selesai.
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->tanggal_selesai ? Carbon::now()->greaterThan($this->tanggal_selesai) : false;
    }

    /**
     * Format jadwal lengkap tanggal mulai sampai selesai dalam bahasa Indonesia.
     */
    public function getFormattedJadwalAttribute(): string
    {
        if (! $this->tanggal_mulai || ! $this->tanggal_selesai) {
            return '-';
        }

        $mulai = Carbon::parse($this->tanggal_mulai);
        $selesai = Carbon::parse($this->tanggal_selesai);

        // Jika dalam hari yang sama
        if ($mulai->isSameDay($selesai)) {
            return $mulai->translatedFormat('d F Y').', '.$mulai->format('H:i').' - '.$selesai->format('H:i').' WIB';
        }

        // Jika beda hari
        return $mulai->translatedFormat('d F Y (H:i)').' s/d '.$selesai->translatedFormat('d F Y (H:i)').' WIB';
    }

    /**
     * Scope agenda aktif
     */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('is_aktif', true);
    }

    /**
     * Scope agenda aktif yang belum lewat tanggal selesai (sedang berlangsung atau akan datang)
     */
    public function scopeBerlangsungDanMendatang(Builder $query): Builder
    {
        return $query->where('is_aktif', true)
            ->where('tanggal_selesai', '>=', Carbon::now());
    }

    /**
     * Scope agenda yang sudah selesai / lewat tanggal selesai
     */
    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('tanggal_selesai', '<', Carbon::now());
    }
}
