<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'material';
    protected $fillable = [
        'nama_material',
        'satuan',
        'spesifikasi',
        'ukuran',
        'kodefikasi',
        'kelompok_material',
        'jumlah_kebutuhan',
        'merk',
        'provincies_id',
        'cities_id',
    ];

    public function perencanaanData()
    {
        return $this->belongsTo(PerencanaanData::class, 'identifikasi_kebutuhan_id', 'identifikasi_kebutuhan_id');
    }

    public function shortlist_vendor()
    {
        return $this->belongsTo(ShortlistVendor::class, 'identifikasi_kebutuhan_id', 'shortlist_vendor_id');
    }

    public function provinces()
    {
        return $this->belongsTo(Provinces::class, 'provincies_id', 'kode_provinsi');
    }

    public function cities()
    {
        return $this->belongsTo(Cities::class, 'cities_id', 'kode_kota');
    }
}
