<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peralatan extends Model
{
    use HasFactory;

    protected $table = 'peralatan';
    protected $fillable = [
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

    public function perencanaanData()
    {
        return $this->belongsTo(PerencanaanData::class, 'identifikasi_kebutuhan_id', 'identifikasi_kebutuhan_id');
    }

    public function  shortlist_vendor()
    {
        return $this->belongsTo(ShortlistVendor::class, 'identifikasi_kebutuhan_id', 'shortlist_vendor_id');
    }
}
