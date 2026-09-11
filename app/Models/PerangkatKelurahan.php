<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerangkatKelurahan extends Model
{
    use HasFactory;

    protected $table = 'perangkat_kelurahans';

    protected $guarded = ['id'];
}
