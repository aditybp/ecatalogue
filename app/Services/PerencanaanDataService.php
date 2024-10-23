<?php

namespace App\Services;

use App\Models\InformasiUmum;
use App\Models\PerencanaanData;

class PerencanaanDataService
{
    private function getInformasiAndShortlistById($id)
    {
        return PerencanaanData::where('identifikasi', true);
    }

    public function listAllPerencanaanData($data)
    {
        // $identifikasiKebutuhan = $this->informasiUmumById($data);
        // $informasiUmumAndShortlist = '';
    }

    public function updatePerencanaanData($informasiUmumId, $field, $value)
    {
        $perencanaanData = PerencanaanData::where('informasi_umum_id', $informasiUmumId)->first();
        if (!$perencanaanData) {
            return false;
        }

        if ($field == 'identifikasi_kebutuhan') {
            $perencanaanData->identifikasi_kebutuhan_id = $value;
        } elseif ($field == 'shortlist_vendor') {
            $perencanaanData->shortlist_vendor_id = $value;
        }

        $perencanaanData->save();
        return true;
    }

}
