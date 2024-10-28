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
        return $this->belongsTo(PerencanaanData::class, 'identifikasi_kebutuhan_id','identifikasi_kebutuhan_id');
    }
}
