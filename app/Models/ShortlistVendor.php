<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortlistVendor extends Model
{
    use HasFactory;
    protected $table = 'shortlist_vendor';
    protected $fillable = ['data_vendor_id', 'shortlist_vendor_id', 'nama_vendor', 'pemilik_vendor', 'alamat', 'kontak', 'url_kuisioner'];
}
