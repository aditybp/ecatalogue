<?php

namespace App\Services;

use App\Models\InformasiUmum;
use App\Models\PerencanaanData;

class PerencanaanDataService
{
    public function listAllPerencanaanData()
    {
        $informasiUmum = PerencanaanData::join('informasi_umum', 'perencanaan_data.informasi_umum_id', '=', 'informasi_umum.id')
        ->join('rup', 'perencanaan_data.rup_id', '=', 'rup.id')
        ->join('paket', 'informasi_umum.paket_id', '=', 'paket.id')
        ->select(
            'informasi_umum.kode_rup',
            'informasi_umum.nama_paket',
            'perencanaan_data.nama_ppk',
            'perencanaan_data.jabatan_ppk',
            'rup.rup_name',
            'paket.paket_name'
        )
    ->get();   
    }
    
}
