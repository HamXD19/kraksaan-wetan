<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MasterKategori extends Model
{
    use HasFactory;

    protected $table = 'master_kategoris';

    protected $guarded = ['id'];

    protected $casts = [
        'parent_id' => 'integer',
        'is_aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    protected $appends = ['aktif'];

    public function getAktifAttribute(): bool
    {
        return (bool) ($this->attributes['is_aktif'] ?? true);
    }

    public function parent()
    {
        return $this->belongsTo(MasterKategori::class, 'parent_id');
    }

    public function subkategoris()
    {
        return $this->hasMany(MasterKategori::class, 'parent_id')->orderBy('urutan')->orderBy('nama');
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug) && ! empty($model->nama)) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }

    public function scopeModul($query, string $modul)
    {
        return $query->where('modul', $modul);
    }

    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeKategoriUtama($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeSubkategori($query)
    {
        return $query->whereNotNull('parent_id');
    }
}
