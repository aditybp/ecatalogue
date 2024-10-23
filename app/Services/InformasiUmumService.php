<?php

namespace App\Services;

use App\Models\InformasiUmum;
use App\Models\PerencanaanData;

class InformasiUmumService
{
    public function getDataInformasiUmumById($informasiUmumId) {
        return InformasiUmum::find($informasiUmumId);
    }

    public function checkNamaPaket($namaPaket) 
    {
        return InformasiUmum::where('nama_paket', $namaPaket)->exists();
    }

    public function saveInformasiUmum($dataInformasiUmum)
    {
        $informasiUmum = new InformasiUmum();
        $informasiUmum->kode_rup = $dataInformasiUmum->kode_rup;
        $informasiUmum->nama_paket = $dataInformasiUmum->nama_paket;
        $informasiUmum->nama_ppk = $dataInformasiUmum->nama_ppk;
        $informasiUmum->jabatan_ppk = $dataInformasiUmum->jabatan_ppk;
        $informasiUmum->jenis_informasi = $dataInformasiUmum->tipe_informasi_umum;
        
        if ($dataInformasiUmum->tipe_informasi_umum == 'manual') {
            $informasiUmum->nama_balai = $dataInformasiUmum->nama_balai;
            //$informasiUmum->tipologi = $dataInformasiUmum->tipologi;
        }

        $data = $informasiUmum->save();
        $informasiUmumId = $informasiUmum->id;
        $savePrencanaanData = $this->savePerencanaanData($informasiUmumId, 'informasi_umum_id');
        if (!$data && !$savePrencanaanData) {
            return false;
        }

        return $informasiUmum;
    }

    private function savePerencanaanData($id, $namaField)
    {
        $perencanaanData = new PerencanaanData();
        $perencanaanData->$namaField = $id;
        return $perencanaanData->save();
    }

    public function getInformasiUmumByPerencanaanId($id)
    {
        // $informasiUmum = InformasiUmum::with('perencanaanData')
        // ->select('kode_rup', 'nama_paket')
        // ->get();

        return InformasiUmum::with('perencanaanData')
                ->select(
                    'kode_rup',
                    'nama_paket',
                    'nama_ppk',
                    'jabatan_ppk',
                    'nama_balai',
                    'tipologi',
                    'jenis_informasi'
                )->get()->first();
    }
    
}
