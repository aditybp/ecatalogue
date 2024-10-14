<?php

namespace App\Services;

use App\Models\Material;
use App\Models\Peralatan;
use App\Models\TenagaKerja;

class IdentifikasiKebutuhanService
{
    public function storeMaterial($dataMaterial)
    {
        $material = new Material();
        $material->nama_material = $dataMaterial->nama_material;
        $material->satuan = $dataMaterial->satuan;
        $material->spesifikasi = $dataMaterial->spesifikasi;
        $material->ukuran = $dataMaterial->ukuran;
        $material->kodefikasi = $dataMaterial->kodefikasi;
        $material->kelompok_material = $dataMaterial->kelompok_material;
        $material->jumlah_kebutuhan = $dataMaterial->jumlah_kebutuhan;
        $material->merk = $dataMaterial->merk;
        $material->provincies_id = $dataMaterial->provincies_id;
        $material->cities_id = $dataMaterial->cities_id;
        $material->save();
        return $material;
    }

    public function storePeralatan($dataPeralatan)
    {
        $peralatan = new Peralatan();
        $peralatan->nama_peralatan = $dataPeralatan->nama_peralatan;
        $peralatan->satuan = $dataPeralatan->satuan;
        $peralatan->spesifikasi = $dataPeralatan->spesifikasi;
        $peralatan->kapasitas = $dataPeralatan->kapasitas;
        $peralatan->kodefikasi = $dataPeralatan->kodefikasi;
        $peralatan->kelompok_peralatan = $dataPeralatan->kelompok_peralatan;
        $peralatan->jumlah_kebutuhan = $dataPeralatan->jumlah_kebutuhan;
        $peralatan->merk = $dataPeralatan->merk;
        $peralatan->provincies_id = $dataPeralatan->provincies_id;
        $peralatan->cities_id = $dataPeralatan->cities_id;
        $peralatan->save();
        return $peralatan;
    }

    public function storeTenagaKerja($dataTenagaKerja) 
    {
        $tenagaKerja = new TenagaKerja();
        $tenagaKerja->jenis_tenaga_kerja = $dataTenagaKerja->jenis_tenaga_kerja;
        $tenagaKerja->satuan = $dataTenagaKerja->satuan;
        $tenagaKerja->jumlah_kebutuhan = $dataTenagaKerja->jumlah_kebutuhan;
        $tenagaKerja->kodefikasi = $dataTenagaKerja->kodefikasi;
        $tenagaKerja->provincies_id = $dataTenagaKerja->provincies_id;
        $tenagaKerja->cities_id = $dataTenagaKerja->cities_id;
        $tenagaKerja->save();
        return $tenagaKerja;
    }

    public function getIdentifikasiKebutuhanByPerencanaanId() 
    {
        
    }
    
}
