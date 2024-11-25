<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetugasLapangan extends Model
{
    use HasFactory;
    protected $table = 'petugas_lapangan';
    protected $fillable = [
        'nama_petugas_lapangan',
        'nip_petugas_lapangan',
        'tanggal_survey',
        'nama_pengawas',
        'nip_pengawas',
        'tanggal_pengawasan',
        'pemberi_informasi',
        'tanda_tangan_responden'
    ];
}
