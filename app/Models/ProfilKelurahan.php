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
    ];
}
