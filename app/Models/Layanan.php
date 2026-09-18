<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanans';

    protected $guarded = ['id'];

    protected $casts = [
        'persyaratan' => 'array',
        'aktif' => 'boolean',
    ];

    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('aktif', true);
    }
}
