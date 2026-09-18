<?php

namespace App\Models;

use App\Services\FileStorageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerangkatKelurahan extends Model
{
    use HasFactory;

    protected $table = 'perangkat_kelurahans';

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::deleting(function (PerangkatKelurahan $perangkat) {
            FileStorageHelper::deleteFileIfLocal($perangkat->foto);
        });
    }
}
