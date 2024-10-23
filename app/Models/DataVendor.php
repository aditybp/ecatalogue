<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataVendor extends Model
{
    use HasFactory;

    protected $fillable = ['nama_vendor', 'jenis_vendor_id', 'kategori_vendor_id', 'alamat', 'no_telepon',
                            'no_hp', 'nama_pic', 'provinsi_id', 'kota_id', 'koordinat', 'logo_url',
                            'dok_pendukung_url'];

    public function provinces()
    {
        return $this->belongsTo(Provinces::class, 'provinsi_id', 'kode_provinsi');
    }

    public function cities()
    {
        return $this->belongsTo(Cities::class, 'kota_id', 'kode_kota');
    }

}
