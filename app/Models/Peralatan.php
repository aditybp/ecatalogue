<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    protected $table = 'peralatan';
    protected $fillable = [
        'perencanaan_data_id',
        'nama_peralatan', 
        'satuan', 
        'spesifikasi', 
        'kapasitas', 
        'kodefikasi', 
        'kelompok_peralatan',
        'jumlah_kebutuhan',
        'merk',
        'provincies_id',
        'cities_id',
    ];
}
