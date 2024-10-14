<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerencanaanData extends Model
{
    use HasFactory;

    protected $table = 'perencanaan_data';
    protected $fillable = ['informasi_umum_id', 'identifikasi_kebutuhan_id', 'shortlist_vendor_id'];
}
