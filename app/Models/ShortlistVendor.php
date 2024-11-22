<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortlistVendor extends Model
{
    use HasFactory;
    protected $table = 'shortlist_vendor';
    protected $fillable = ['data_vendor_id', 'shortlist_vendor_id', 'nama_vendor', 'pemilik_vendor', 'alamat', 'kontak', 'url_kuisioner', 'sumber_daya'];

    public function perencanaanData()
    {
        return $this->belongsTo(PerencanaanData::class, 'shortlist_vendor_id', 'shortlist_vendor_id');
    }

    public function data_vendor()
    {
        return $this->belongsTo(DataVendor::class, 'data_vendor_id', 'id');
    }

    public function material()
    {
        return $this->hasMany(Material::class, 'identifikasi_kebutuhan_id', 'shortlist_vendor_id');
    }

    public function peralatan()
    {
        return $this->hasMany(Peralatan::class, 'identifikasi_kebutuhan_id', 'shortlist_vendor_id');
    }

    public function tenaga_kerja()
    {
        return $this->hasMany(TenagaKerja::class, 'identifikasi_kebutuhan_id', 'shortlist_vendor_id');
    }
}
