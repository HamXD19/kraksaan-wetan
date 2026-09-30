<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilKelurahan extends Model
{
    use HasFactory;

    protected $table = 'profil_kelurahans';

    protected $guarded = ['id'];

    protected $casts = [
        'misi' => 'array',
        'batas_wilayah' => 'array',
        'potensi_unggulan' => 'array',
        'tata_nilai' => 'array',
        'sejarah_timeline' => 'array',
        'custom_nav_menus' => 'array',
    ];
}
