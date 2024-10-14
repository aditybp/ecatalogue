<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenagaKerja extends Model
{
    use HasFactory;

    protected $table = 'tenaga_kerja';
    protected $fillable = [
        'jenis_tenaga_kerja', 
        'satuan', 
        'jumlah_kebutuhan',
        'kodefikasi', 
        'provincies_id',
        'cities_id',
    ];
}
